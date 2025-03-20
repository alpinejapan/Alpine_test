<?php

namespace Modules\Imports\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessSqlFile;
use Exception;
use Illuminate\Support\Facades\Session;
use Modules\Imports\Entities\CarDataJpOp;
use Modules\Imports\Entities\AuctLotsXmlJpOpOtherChargers;

class ImportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('imports::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('imports::create');
    }

    public function companies(){
        return view('imports::companies');
    }

    public function TopSale(Request $request){
        if(AuctLotsXmlJpOpOtherChargers::whereAuctId($request->selectedIds)->Exists()){
            AuctLotsXmlJpOpOtherChargers::where('auct_id',$request->selectedIds)
            ->update(['top_sell'=>$request->check == 1 ? '1' : '0']);
        } else {
            AuctLotsXmlJpOpOtherChargers::create([
                    'auct_id'=>$request->selectedIds,
                    'top_sell'=>$request->check == 1 ? '1' : '0'
            ]);
        }
    
        return response()->json(['status'=>true,'message'=>'status changes successfully']);
    }

    public function getNumberFromUrl($url) {
        $parsed_url = parse_url($url);
        parse_str( isset($parsed_url['query']) ? $parsed_url['query'] : '', $query_params);
        return isset($query_params['number']) ? (int)$query_params['number'] : null;
    }

    public function commission(Request $request){
        $is_english = admin_lang() == "en";
        $commission_id_array = Session::get('commission_id') ?? [];
        $commission_ids = implode(",",$commission_id_array);

        $cars=CarDataJpOp::Query();

        $commissions=$cars->leftjoin('auct_lots_xml_jp_op_other_chargers as oc','auct_lots_xml_jp_op.id','=','oc.auct_id');


        if($request->year){
            $commissions=$cars->where('auct_lots_xml_jp_op.model_year_en',$request->year);
        }
        if($request->make){
            $commissions=$cars->where(DB::raw('LOWER(auct_lots_xml_jp_op.company_en)'), $request->make);
        }
        if($request->model){
            $commissions=$cars->where('auct_lots_xml_jp_op.model_name_en',$request->model);
        }

        $commissions = $cars
        ->select('auct_lots_xml_jp_op.*','oc.commission_value','oc.shipping_value')
        // ->where('active_status', 1)
        ->orderBy('auct_lots_xml_jp_op.updated_at', 'desc')
        ->paginate(10);
       
        foreach($commissions as $key => $value){
            $sorted_image_array = [];
            $picture_array = explode("#",$value->pictures);
            if(count($picture_array) > 0){
                $image_array = [];
                foreach ($picture_array as $picture) {
                    $image_array[] = [
                        'url' => $picture,
                        'number' => ImportsController::getNumberFromUrl($picture)
                    ];
                }
                usort($image_array, function($a, $b) {
                    return $b['number'] - $a['number'];
                });
                $sorted_image_array = array_column($image_array, 'url');
            }
            $value->sorted_image_array = $sorted_image_array;
            $value->sorted_image_first = isset($sorted_image_array[0]) ? $sorted_image_array[0] : '';
        }

        return view('imports::commission', compact('commission_ids','commissions','is_english'));
    }
    
    public function commissionStore(Request $request){
        
        // $request->validate([
        //     'commission' => 'required|integer',
        // ]);
        // CarDataJpOp::where('active_status', 1)
        // ->update(['commission_value' => $request->commission]);
        // ]);

        AuctLotsXmlJpOpOtherChargers::whereIn('auct_id',$request->selectedIds)
        ->update(['commission_value' => $request->commission]);
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
        // return redirect()->route('admin.commission')->with($notification);
    }
    public function storeCarInsurance(Request $request){
        AuctLotsXmlJpOpOtherChargers::whereIn('auct_id',$request->selectedIds)
        ->update(['marine_insurance_value' => $request->commission]);
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
        // return redirect()->route('admin.commission')->with($notification);
    }
    public function storeCarInland(Request $request){
        AuctLotsXmlJpOpOtherChargers::whereIn('auct_id',$request->selectedIds)
        ->update(['inland_inspection_value' => $request->commission]);
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
        // return redirect()->route('admin.commission')->with($notification);
    }

    






    public function shippingStore(Request $request){
        CarDataJpOp::where('active_status', 1)
        ->whereIn('id',$request->selectedIds)
        ->update(['shipping_value' => $request->commission]);
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
        // return redirect()->route('admin.commission')->with($notification);
    }
    public function storeAllComission(Request $request){
        // CarDataJpOp::where('active_status', 1)
        // ->update(['commission_value' => $request->commission]);
        $commissionValue = $request->commission;
        $chunkSize = 1000; // Adjust based on your server capacity

        // Get all auction IDs in chunks
        DB::table('auct_lots_xml_jp_op')
            ->select('id')
            ->orderBy('id')
            ->chunk($chunkSize, function ($auctionLots) use ($commissionValue) {
                $insertData = $auctionLots->map(function ($lot) use ($commissionValue) {
                    return [
                        'auct_id' => $lot->id,
                        'commission_value' => $commissionValue,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();
    
                // Process each chunk with upsert
                DB::table('auct_lots_xml_jp_op_other_chargers')->upsert(
                    $insertData,
                    ['auct_id'],
                    ['commission_value']
                );
            });
    
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }
    public function storeAllShipping(Request $request){
        // CarDataJpOp::where('active_status', 1)
        // ->update(['shipping_value' => $request->commission]);

        $shippingValue = $request->commission;
        $chunkSize = 1000; // Adjust based on your server capacity

        // Get all auction IDs in chunks
        DB::statement("
        UPDATE auct_lots_xml_jp_op_other_chargers
        SET shipping_value = ?,
            updated_at = NOW()
        WHERE auct_id IN (SELECT id FROM auct_lots_xml_jp_op)
    ", [$shippingValue]);


        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }
    public function NewArrival(Request $request){
        if(AuctLotsXmlJpOpOtherChargers::whereAuctId($request->selectedIds)->Exists()){
            AuctLotsXmlJpOpOtherChargers::where('auct_id',$request->selectedIds)
            ->update(['new_arrival'=>$request->check == 1 ? '1' : '0']);
        } else {
            AuctLotsXmlJpOpOtherChargers::create([
                    'auct_id'=>$request->selectedIds,
                    'new_arrival'=>$request->check == 1 ? '1' : '0'
            ]);
        }
    // //    DB::enableQueryLog();
    //     CarDataJpOp::where('active_status', 1)
    //     ->where('id',$request->selectedIds)
    //     ->update(['new_arrival' => $request->check]);
    //     // dd(DB::getQueryLog());
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }

    public function validateUpdateRequest(Request $request)
    {
        return $request->validate([
            'type' => 'required|in:shipping,commission,inland_inspection,marine_insurance',
            'value' => 'required|numeric|min:0',
        ]);
    }


    public function updateValuesWithValidation(Request $request)
    {
    try {
        // Validate the request
        $validated = $this->validateUpdateRequest($request);
        
        $value = $validated['value'];
        $type = $validated['type'];
        
        // Get count of existing records
        $existingRecordsCount = DB::table('auct_lots_xml_jp_op_other_chargers')->count();
        
        DB::beginTransaction();
        
        try {
            if ($existingRecordsCount === 0) {
                // No records exist - do initial insert
                $chunkSize = 1000;
                
                DB::table('auct_lots_xml_jp_op')
                    ->select('id')
                    ->orderBy('id')
                    ->chunk($chunkSize, function ($auctionLots) use ($value, $type) {
                        $insertData = [];
                        
                        foreach ($auctionLots as $lot) {
                            $insertData[] = [
                                'auct_id' => $lot->id,
                                $type . '_value' => $value,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }

                        DB::table('auct_lots_xml_jp_op_other_chargers')->insert($insertData);
                    });
            } else {
                // Records exist - do update
                $affected = DB::statement("
                    UPDATE auct_lots_xml_jp_op_other_chargers
                    SET {$type}_value = ?,
                        updated_at = NOW()
                    WHERE auct_id IN (SELECT id FROM auct_lots_xml_jp_op)
                ", [$value]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => $existingRecordsCount === 0 ? 
                    'Initial values inserted successfully' : 
                    'Values updated successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while processing your request',
            'error' => $e->getMessage()
        ], 500);
    }
}





    public function bulkDelete(Request $request){
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No IDs provided.']);
        }
        CarDataJpOp::whereIn('id', $ids)->update([
            'active_status' => 0
        ]);
    
        return response()->json(['success' => true, 'message' => 'Records deleted successfully.']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // set_time_limit(0);
        // ini_set('upload_max_filesize', '100M');
        // ini_set('post_max_size', '100M');
        // ini_set('memory_limit', '4G');// Not wokring ( INI is not set at runtime ) 
        $request->validate([
            'sql_file' => 'required|file|mimes:bz2', // 10MB max
            // 'sql_file' => 'required|file|mimes:gz,sql,bz2,txt', // Previous validation
        ]);

        try{
            $file = $request->file('sql_file');
            $path = $file->storeAs('temp', 'import_' . time() . '.' . $file->getClientOriginalExtension());        
    
            ProcessSqlFile::dispatch($path);
    
            $notification= trans('translate.SQL Imported');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('admin.commission')->with($notification);
        }
        catch(Exception $e){
            $notification = trans('translate.Something went wrong, please try again');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.import.index')->with($notification);
        }
        // return redirect()->route('admin.import.index')->with('success', 'SQL file import is in progress.');

        // try {
        //     $handle = fopen(Storage::path($path), 'r');
        //     $chunkSize = 1048576; // 1MB chunk size
        //     $sql = '';
    
        //     while (!feof($handle)) {
        //         $chunk = fread($handle, $chunkSize);
        //         $sql .= $chunk;
    
        //         // Execute queries in chunks
        //         $queries = explode(';', $sql);
        //         foreach ($queries as $query) {

        //             $query = trim($query);
        //             if (!empty($query)) {

        //                 DB::unprepared($query);

        //             }
                 
        //         }
    
        //         $sql = '';
        //     }
    
        //     fclose($handle);
    
        //     Storage::delete($path);
    
        //     return redirect()->route('admin.import.index')->with('success', 'SQL file imported successfully.');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     Storage::delete($path);
        //     return redirect()->route('admin.import.index')->with('error', 'An error occurred: ' . $e->getMessage());
        // }
    }

  

    private function decompressAndRead($path)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if ($extension === 'gz') {
            $handle = gzopen($path, 'rb');
            $contents = '';
            while (!gzeof($handle)) {
                $contents .= gzread($handle, 4096);
            }
            gzclose($handle);
        } 
        elseif ($extension === 'bz2') {
            // echo "one";die();
            // Handle .bz2 files
            $handle = bzopen($path, 'r');
            $contents = '';
            while (!feof($handle)) {
                $contents .= bzread($handle, 4096);
            }
            bzclose($handle);
        }  else {
            $contents = file_get_contents($path);
        }

        // Remove CDATA sections
         $contents = preg_replace('/<!\[CDATA\[(.*?)\]\]>/', '$1', $contents);
        return $contents;
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('imports::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $commissions = CarDataJpOp::where('id',$id)->first();
        if(!isset($commissions->commission_value) || $commissions->commission_value == "NULL"){
            $commissions->commission_value = "";
        }
        return view('imports::edit',compact('commissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try{
            $update_array = [
                "date" => ($request->date != null) ? $request->date : "",
                "bid" => ($request->bid != null) ? $request->bid : "",
                "auct_ref" => ($request->auct_ref != null) ? $request->auct_ref : "",
                "auction_system" => ($request->auction_system != null) ? $request->auction_system : "",
                "datetime" => ($request->datetime != null) ? $request->datetime : "",
                "commission_value" => ($request->commission_value != null) ? $request->commission_value : "",
                "company" => ($request->company != null) ? $request->company : "",
                "company_en" => ($request->company_en != null) ? $request->company_en : "",
                "company_ref" => ($request->company_ref != null) ? $request->company_ref : "",
                "model_name" => ($request->model_name != null) ? $request->model_name : "",
                "model_name_en" => ($request->model_name_en != null) ? $request->model_name_en : "",
                "model_name_ref" => ($request->model_name_ref != null) ? $request->model_name_ref : "",
                "model_year" => ($request->model_year != null) ? $request->model_year : "",
                "model_year_en" => ($request->model_year_en != null) ? $request->model_year_en : "",
                "model_details_en" => ($request->model_details_en != null) ? $request->model_details_en : "",
                "model_grade" => ($request->model_grade != null) ? $request->model_grade : "",
                "model_grade_en" => ($request->model_grade_en != null) ? $request->model_grade_en : "",
                "awd_num" => ($request->awd_num != null) ? $request->awd_num : "",
                "model_type" => ($request->model_type != null) ? $request->model_type : "",
                "model_type_en" => ($request->model_type_en != null) ? $request->model_type_en : "",
                "sw_type" => ($request->sw_type != null) ? $request->sw_type : "",
                "mileage" => ($request->mileage != null) ? $request->mileage : "",
                "mileage_en" => ($request->mileage_en != null) ? $request->mileage_en : "",
                "mileage_num" => ($request->mileage_num != null) ? $request->mileage_num : "",
                "inspection" => ($request->inspection != null) ? $request->inspection : "",
                "inspection_en" => ($request->inspection_en != null) ? $request->inspection_en : "",
                "is_special" => ($request->is_special != null) ? $request->is_special : "",
                "equipment" => ($request->equipment != null) ? $request->equipment : "",
                "equipment_en" => ($request->equipment_en != null) ? $request->equipment_en : "",
                "transmission" => ($request->transmission != null) ? $request->transmission : "",
                "transmission_en" => ($request->transmission_en != null) ? $request->transmission_en : "",
                "displacement" => ($request->displacement != null) ? $request->displacement : "",
                "displacement_num" => ($request->displacement_num != null) ? $request->displacement_num : "",
                "start_price" => ($request->start_price != null) ? $request->start_price : "",
                "start_price_num" => ($request->start_price_num != null) ? $request->start_price_num : "",
                "end_price" => ($request->end_price != null) ? $request->end_price : "",
                "end_price_num" => ($request->end_price_num != null) ? $request->end_price_num : "",
                "color" => ($request->color != null) ? $request->color : "",
                "color_en" => ($request->color_en != null) ? $request->color_en : "",
                "color_ref" => ($request->color_ref != null) ? $request->color_ref : "",
                "scores" => ($request->scores != null) ? $request->scores : "",
                "scores_en" => ($request->scores_en != null) ? $request->scores_en : "",
                "result" => ($request->result != null) ? $request->result : "",
                "result_ref" => ($request->result_ref != null) ? $request->result_ref : "",
                "vin" => ($request->vin != null) ? $request->vin : "",
                "service_data" => ($request->service_data != null) ? $request->service_data : "",
                "parsed_data" => ($request->parsed_data != null) ? $request->parsed_data : "",
                "parsed_data_en" => ($request->parsed_data_en != null) ? $request->parsed_data_en : "",
                "parsed_data_ru" => ($request->parsed_data_ru != null) ? $request->parsed_data_ru : "",
                "download_time" => ($request->download_time != null) ? $request->download_time : "",
                "pics_downloaded" => ($request->pics_downloaded != null) ? $request->pics_downloaded : "",
                "pictures" => ($request->pictures != null) ? $request->pictures : "",
            ];

            CarDataJpOp::where('id',$id)->update($update_array);

            $notification= trans('translate.Updated Successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('admin.commission')->with($notification);
        }
        catch(Exception $e){
            $notification = trans('translate.Something went wrong, please try again');
            $notification = array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.commission')->with($notification);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
