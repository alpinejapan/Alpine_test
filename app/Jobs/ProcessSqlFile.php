<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ProcessSqlFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;
    private $chunkSize = 5 * 1024 * 1024; // 5 MB

    public function __construct($path)
    {
        $this->path = $path;
    }

    // Function to extract .bz2 file to .sql
    private function extractBz2File($bz2File)
    {
        set_time_limit(800);
        ini_set('memory_limit', '4G');
        $bz = bzopen($bz2File, "r");

        if (!$bz) {
            return false;
        }

        // Temporary SQL file to store extracted content
        $tempSqlFile = sys_get_temp_dir() . "/extracted_sql_" . time() . ".sql";
        $sqlContent = '';

        // Read the bz2 file and extract its content
        while (!feof($bz)) {
            $sqlContent .= bzread($bz, 4096);
        }
        bzclose($bz);

        // Save extracted content to a temporary SQL file
        file_put_contents($tempSqlFile, $sqlContent);

        return $tempSqlFile;
    }

    private function checkAndCreateTable($sqlContent)
    {
        // Step 1: Extract table name from the SQL content using regex
        if (preg_match('/CREATE TABLE `(\w+)`/', $sqlContent, $matches)) {
            $tableName = $matches[1];
            echo $tableName;
            // Prepare and execute the SQL query to check if the table exists
            $query = "SHOW TABLES LIKE '$tableName'";

            $result = DB::select($query);
            
            // Check if the table exists
            if (count($result) > 0) {
                echo "Table '$tableName' is present.\n";
                //echo "Table '$tableName' is present.\n";
            } else {
                // If the table does not exist, create it
                // Extract CREATE TABLE statement
    
                if (preg_match('/(CREATE TABLE `\w+` .*)/s', $sqlContent, $createMatches)) {
                    $createTableSQL = $createMatches[1];
                    echo "snk";
                    $createTableSQL = preg_replace('/\/\*.*?\*\//s', '', $createTableSQL);
    
                    $cleanedSql = preg_replace('/;.*$/s', ';', $createTableSQL);
    
                    // Display the cleaned SQL
                    echo $cleanedSql;
                    //  print_r($createTableSQL);
                   
                    try {
                        DB::statement($cleanedSql);
                    } catch (\Illuminate\Database\QueryException $e) {
                        die("Error creating table: " . $e->getMessage());
                    }
                    echo "Table '$tableName' created successfully.\n";
                } else {
                    die("CREATE TABLE statement not found in SQL content.");
                }
            }
            
            
        } else {
            die("Table name not found in SQL content. last else");
        }
    }

    // finding colum name 
    private function extractColumnNames($sqlContent)
    {
        // Use a regular expression to extract the CREATE TABLE structure
        if (preg_match('/CREATE TABLE `\w+` \((.*?)\)\s*ENGINE/s', $sqlContent, $matches)) {
            // $matches[1] contains the part inside the parentheses
            $tableDefinition = $matches[1];

            // Use another regular expression to extract all column names, allowing for spaces and various data types
            preg_match_all('/`(\w+)`\s+\w+/s', $tableDefinition, $columnMatches);

            // $columnMatches[1] will contain all the column names
            $columnNames = $columnMatches[1];

            return $columnNames;
        }

        return []; // Return empty array if no column names are found
    }

    // Process the SQL file and convert valid INSERT INTO queries to UPDATE queries
    private function processSqlFile($sqlContent, $cloum_name)
    {
        // Use a regular expression to extract only the INSERT statements
        preg_match('/INSERT INTO `(\w+)` VALUES\s*\((.*)\);/s', $sqlContent, $matches);
        
        $id_array = [];
        $queries = [];
        // Check if any INSERT statement was found
        if (isset($matches[1]) && isset($matches[2])) {
            $tableName = $matches[1]; // Table name
            $insertValues = $matches[2]; // Values in the INSERT query

            // Define the table's column names (You can dynamically fetch these from the database schema)
            $columnNames = $cloum_name; // Replace or fetch dynamically if needed

            // Extract each set of values within the INSERT statement
            $valuesArray = explode("),(", trim($insertValues, "()"));

            // Initialize an array to hold the queries
            // Loop through each value set and convert it to an INSERT ... ON DUPLICATE KEY UPDATE query

            foreach ($valuesArray as $valueSet) {
                // Split the values by commas
                $values = explode(",", $valueSet);

                // Initialize an array for building both INSERT and UPDATE parts
                $insertColumns = [];
                $insertValues = [];
                $updateClauses = [];

                // Loop through the column names and their corresponding values
                foreach ($columnNames as $index => $columnName) {
                    if(isset($values[$index])){
                        $value = trim($values[$index], "'"); // Trim the quotes from the value
                        $insertColumns[] = "`$columnName`";
                        $insertValues[] = "'$value'";
    
                        if ($index > 0) {
                            // Build the SET clause for UPDATE part (excluding the primary key)
                            $updateClauses[] = "`$columnName` = VALUES(`$columnName`)";
                        }
                    }
                }

                
                // Combine the columns and values for the INSERT query
                $insertColumnsStr = implode(", ", $insertColumns);
                $insertValuesStr = implode(", ", $insertValues);
                // array_push($id_array, intval(trim($insertValues[0], "'")));
                // Combine the SET clause for the ON DUPLICATE KEY UPDATE part
                $updateClauseStr = implode(", ", $updateClauses);

                // Final query: INSERT with ON DUPLICATE KEY UPDATE
                $query = "INSERT INTO `$tableName` ($insertColumnsStr) VALUES ($insertValuesStr) ON DUPLICATE KEY UPDATE $updateClauseStr;";
                $queries[] = $query;
            }

            // Output all the queries
            foreach ($queries as $query) {
                // echo $query . PHP_EOL;
            }
        } else {
            echo "No INSERT statement found!";
        }
        return [
            "queries" => $queries,
            "id_array" => $id_array
        ];
    }

//     private function processSqlFile($sqlContent, $cloum_name)
// {
//     // Use a regular expression to extract only the INSERT statements
//     preg_match_all('/INSERT INTO `(\w+)` VALUES\s*\((.*?)\);/s', $sqlContent, $matches);
    
//     $id_array = [];
//     $queries = [];
    
//     // Loop through each match found
//     foreach ($matches[1] as $key => $tableName) {
//         $insertValues = $matches[2][$key]; // Values in the INSERT query
        
//         // Define the table's column names (You can dynamically fetch these from the database schema)
//         $columnNames = $cloum_name; // Replace or fetch dynamically if needed
        
//         // Extract each set of values within the INSERT statement
//         $valuesArray = explode("),(", trim($insertValues, "()"));

//         // Loop through each value set and convert it to an INSERT ... ON DUPLICATE KEY UPDATE query
//         foreach ($valuesArray as $valueSet) {
//             // Split the values by commas
//             $values = explode(",", $valueSet);

//             // Initialize an array for building both INSERT and UPDATE parts
//             $insertColumns = [];
//             $insertValues = [];
//             $updateClauses = [];

//             // Loop through the column names and their corresponding values
//             foreach ($columnNames as $index => $columnName) {
//                 if (isset($values[$index])) {
//                     $value = trim($values[$index]);

//                     // Handle NULL or empty values properly
//                     if (strtolower($value) === "null" || empty($value)) {
//                         $insertValues[] = "NULL";
//                     } else {
//                         // For non-NULL values, ensure they are properly quoted
//                         $value = "'".addslashes($value)."'";  // Add slashes to escape any quotes in the value
//                         $insertValues[] = $value;
//                     }

//                     // Build the columns list for the INSERT query
//                     $insertColumns[] = "`$columnName`";

//                     if ($index > 0) {
//                         // Build the SET clause for UPDATE part (excluding the primary key)
//                         $updateClauses[] = "`$columnName` = VALUES(`$columnName`)";
//                     }
//                 }
//             }

//             // Combine the columns and values for the INSERT query
//             $insertColumnsStr = implode(", ", $insertColumns);
//             $insertValuesStr = implode(", ", $insertValues);

//             // Combine the SET clause for the ON DUPLICATE KEY UPDATE part
//             $updateClauseStr = implode(", ", $updateClauses);

//             // Final query: INSERT with ON DUPLICATE KEY UPDATE
//             $query = "INSERT INTO `$tableName` ($insertColumnsStr) VALUES ($insertValuesStr) ON DUPLICATE KEY UPDATE $updateClauseStr;";
//             $queries[] = $query;
//         }
//     }
    
//     // Output all the queries
//     foreach ($queries as $query) {
//         // Optionally, you can output or store these queries
//         // echo $query . PHP_EOL;
//     }

//     return [
//         "queries" => $queries,
//         "id_array" => $id_array
//     ];
// }


    // Execute queries
    private function executeQueries($queries, $id_array)
    {   
        $updated_id_array = [];
        foreach ($queries as $key => $query) {
            if (!empty($query)) {
                  $affectingStatement = DB::affectingStatement($query);
                //   if($affectingStatement > 0){
                //     array_push($updated_id_array,$id_array[$key]);
                //     Session::put('commission_id',$updated_id_array);
                //   }
                // The above code is not needed as per requirement
            }
        }
    }

    public function handle(): void
    {
        $extension = pathinfo($this->path, PATHINFO_EXTENSION);
        $handle = $this->getFileHandle($extension);

        if (!$handle) {
            throw new \Exception("Unable to open file");
        }
        
        $this->path = Storage::disk('local')->path($this->path);
        try {
            $extractedSqlFile = $this->extractBz2File($this->path);
            if ($extractedSqlFile) {
                // Read the content of the SQL file
                $sqlContent = file_get_contents($extractedSqlFile);
                $this->checkAndCreateTable($sqlContent);
                $cloum_name = $this->extractColumnNames($sqlContent);
                print_r($cloum_name);
                //  echo $sqlContent;
                // Process the SQL content
                $responseData = $this->processSqlFile($sqlContent, $cloum_name);
                
                // dd('aaa',$responseData);
                $updateQueries = $responseData['queries'];
                $id_array = $responseData['id_array'];
                //var_dump($updateQueries);
                // Execute the update queries
                $this->executeQueries($updateQueries, $id_array);
                echo "SQL queries executed successfully!";
            } else {
                echo "Failed to extract the bz2 file.";
            }
        } catch (\Exception $e) {
            Log::error('Error processing SQL file', ['error' => $e->getMessage()]);
            throw $e;
        } finally {
            $this->closeFileHandle($handle, $extension);
            Storage::delete($this->path);
        }
    }

    private function stripCData($content)
    {
        $content = preg_replace('/<!\[CDATA\[|\]\]>/', '', $content);
        // $content = addslashes($content); // Escape special characters
        return $content;
    }

    private function getFileHandle($extension)
    {
        switch ($extension) {
            case 'gz':
                return gzopen(Storage::path($this->path), 'rb');
            case 'bz2':
                return bzopen(Storage::path($this->path), 'r');
            default:
                return fopen(Storage::path($this->path), 'r');
        }
    }

    private function isEndOfFile($handle, $extension)
    {
        switch ($extension) {
            case 'gz':
                return gzeof($handle);
            case 'bz2':
            default:
                return feof($handle);
        }
    }

    private function readChunk($handle, $extension)
    {
        switch ($extension) {
            case 'gz':
                return gzread($handle, $this->chunkSize);
            case 'bz2':
                return bzread($handle, $this->chunkSize);
            default:
                return fread($handle, $this->chunkSize);
        }
    }

    private function closeFileHandle($handle, $extension)
    {
        switch ($extension) {
            case 'gz':
                gzclose($handle);
                break;
            case 'bz2':
                bzclose($handle);
                break;
            default:
                fclose($handle);
        }
    }

    private function processChunk($chunk)
    {
        // Split queries by semicolon, but ignore semicolons within quotes or comments
        $queries = preg_split('/;(?=(?:[^\'"]|\'[^\']*\'|\"[^\"]*\")*$)/', $chunk);

        foreach ($queries as $query) {
            $query = trim($query); // Remove whitespace, but preserve essential spaces

            if (!empty($query)) {
                try {
                    // Log the query being executed for debugging purposes
                    Log::info('Executing SQL Query:', ['query' => $query]);

                    // Use prepared statements to execute the query
                   DB::statement($query);
                  
                } catch (\Exception $e) {
                    Log::error('SQL Error:', ['error' => $e->getMessage(), 'query' => $query]);
                }
            }
        }
    }
}
