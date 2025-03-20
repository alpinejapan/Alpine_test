<?php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon; 
use App\Models\JPYRate;
function html_decode($text){
    $after_decode =  htmlspecialchars_decode($text, ENT_QUOTES);
    return $after_decode;
}

function admin_lang(){
    return Session::get('admin_lang');
}

function front_lang(){
    return Session::get('front_lang');
}

function amount($amount) {
    $amount = number_format($amount, 2, '.', ',');

    return $amount;
}

function calculate_percentage($regular_price, $offer_price){

    $offer = (($regular_price - $offer_price) / $regular_price) * 100;
    $offer = round($offer, 2);
    return $offer;

}




    // @codingStandardsIgnoreLine
    function countries()
    {
        if (!cache()->has('countries')) {
            cache(['countries' => \App\Models\Country::all()]);
        }

        return cache('countries');
    }





function currency($price){
    // currency information will be loaded by Session value

    $currency_icon = Session::get('currency_icon');
    $currency_code = Session::get('currency_code');
    $currency_rate = Session::get('currency_rate');
    $currency_position = Session::get('currency_position');

    $price = $price * $currency_rate;
    $price = amount($price, 2, '.', ',');

    if($currency_position == 'before_price'){
        $price = $currency_icon.$price;
    }elseif($currency_position == 'before_price_with_space'){
        $price = $currency_icon.' '.$price;
    }elseif($currency_position == 'after_price'){
        $price = $price.$currency_icon;
    }elseif($currency_position == 'after_price_with_space'){
        $price = $price.' '.$currency_icon;
    }else{
        $price = $currency_icon.$price;
    }

    return $price;
}


function getAllResourceFiles($dir, &$results = array()) {
    $files = scandir($dir);
    foreach ($files as $key => $value) {
        $path = $dir ."/". $value;
        if (!is_dir($path)) {
            $results[] = $path;
        } else if ($value != "." && $value != "..") {
            getAllResourceFiles($path, $results);
        }
    }
    return $results;
}

function getRegexBetween($content) {

    preg_match_all("%\{{ __\(['|\"](.*?)['\"]\) }}%i", $content, $matches1, PREG_PATTERN_ORDER);
    preg_match_all("%\@lang\(['|\"](.*?)['\"]\)%i", $content, $matches2, PREG_PATTERN_ORDER);
    preg_match_all("%trans\(['|\"](.*?)['\"]\)%i", $content, $matches3, PREG_PATTERN_ORDER);
    $Alldata = [$matches1[1], $matches2[1], $matches3[1]];
    $data = [];
    foreach ($Alldata as  $value) {
        if(!empty($value)){
            foreach ($value as $val) {
                $data[$val] = $val;
            }
        }
    }
    return $data;
}

function generateLang($path = ''){

    // user panel
    $paths = getAllResourceFiles(resource_path('views'));

    $paths = array_merge($paths, getAllResourceFiles(app_path()));

    $paths = array_merge($paths, getAllResourceFiles(base_path('Modules')));

    // end user panel

    // user validation
    $paths = getAllResourceFiles(app_path());

    $paths = array_merge($paths, getAllResourceFiles(app_path('Http/Controllers/test')));
    $paths = array_merge($paths, getAllResourceFiles(app_path('Http/Controllers/Auth')));
    // end user validation

    // admin panel
    $paths = getAllResourceFiles(resource_path('views/admin'));
    // end admin panel

    // admin validation
    $paths = getAllResourceFiles(app_path('Http/Controllers/Admin'));
    // end validation
    $AllData= [];
    foreach ($paths as $key => $path) {
    $AllData[] = getRegexBetween(file_get_contents($path));
    }
    $modifiedData = [];
    foreach ($AllData as  $value) {
        if(!empty($value)){
            foreach ($value as $val) {
                $modifiedData[$val] = $val;
            }
        }
    }

    $modifiedData = var_export($modifiedData, true);

    file_put_contents('lang/en/translate.php', "<?php\n return {$modifiedData};\n ?>");

}

if (!function_exists('hasCheckedModels')) {
    function hasCheckedModels($brandSlug, $brand_arr, $selectedModels) {
        if (!array_key_exists($brandSlug, $brand_arr) || empty($selectedModels)) {
            return false;
        }
        
        foreach ($brand_arr[$brandSlug] as $model) {
            if (in_array(trim($model['model']), array_map('trim', (array) $selectedModels))) {
                return true;
            }
        }
        
        return false;
    }
}

if (!function_exists('hasCheckedModelsCar')) {
    function hasCheckedModelsCar($brandSlug, $brand_arr, $selectedModels) {
        // Ensure the brand exists in the provided brand array
        if (!array_key_exists($brandSlug, $brand_arr) || empty($selectedModels)) {
            return false;
        }

        // Check if models for this brand exist in the selected models
        if (!array_key_exists($brandSlug, $selectedModels) || empty($selectedModels[$brandSlug])) {
            return false;
        }

        // Iterate through the brand's models and compare with selected models for this brand
        foreach ($brand_arr[$brandSlug] as $model) {
            if (in_array(trim($model['model']), array_map('trim', (array) $selectedModels[$brandSlug]))) {
                return true;
            }
        }

        return false;
    }
}


function parseCustomFormat($string)
{
    // Remove CDATA wrapper if present
    $string = preg_replace('/<!\[CDATA\[(.*?)\]\]>/s', '$1', $string);
    
    // Remove outer curly braces if present
    $string = trim($string, '{}');
    
    // Split the string into key-value pairs
    $pairs = preg_split('/","|,(?=[^:]+:)/', $string);
    
    $result = [];
    foreach ($pairs as $pair) {
        // Split each pair into key and value
        list($key, $value) = array_pad(explode(':', $pair, 2), 2, null);
        
        // Clean up key and value
        $key = trim($key, '" ');
        $value = trim($value, '" ');
        
        // Unescape special characters
        $value = stripcslashes($value);
        
        $result[$key] = $value;
    }
 
    // vehicle  location
    
    return $result;
}

function getConversionRate(){
    $rate = Cache::get('usd_to_jpy_rate');

   
    if (!$rate) {
        $endpoint = 'convert';
        $access_key = '0c78261d10091415dceebaaa60077246';  // Your API key
        
        // Parameters for conversion
        $from = 'USD';  // From US Dollar
        $to = 'JPY';    // To Japanese Yen
        $amount = 1;    // Amount to convert (we only need the conversion rate, so amount is 1)
        
        
        // Initialize CURL:
        // $ch = curl_init('https://api.currencylayer.com/'.$endpoint.'?access_key='.$access_key.'&from='.$from.'&to='.$to.'&amount='.$amount);
        
        // // Set CURL options:
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
        
        // // Execute the request and store the response
        // $json = curl_exec($ch);
        $json=JPYRate::first();


        if (!empty($json)) {
            // curl_close($ch);
            // $conversionResult = json_decode($json, true);
            // Get the exchange rate for USD to JPY
            $rate = round($json->yen_rate);
            $expiry_date=Carbon::tomorrow()->startOfDay();

            // Store the conversion rate in cache for 24 hours
            Cache::put('usd_to_jpy_rate', $rate, $expiry_date);
        } else {
            // Handle API error gracefully
            // You can either throw an exception or return a default value
            $rate = 110;  // Example: Use a fallback rate if API fails
        }        
    }    
    return $rate;
}


function convertCurrency($amount, $rate) {
    // Convert strings to BCMath strings to maintain precision
    $amount = strval($amount);
    $rate = strval($rate);
    
    // Perform the division with BCMath for higher precision
    $result = bcDiv($amount, $rate, 10); // Increased internal precision

    // First round to 4 decimal places to match Google's internal precision
    $result = round((float)$result, 4);
    
    // Then round to final 2 decimal places
    $result = round($result);
    return $result;
}
