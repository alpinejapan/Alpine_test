<?php

namespace Modules\Heavy\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Categories\Entities\ProductCategories;
use Modules\Heavy\Entities\Heavy;
use File;
use Modules\Brand\Entities\Brand;
use Modules\Models\Entities\ModelsCars;
use Illuminate\Support\Facades\DB;
use App\Models\JdmStockHeavyOtherCharges;

class HeavyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $heavies = Heavy::all();
        return view('heavy::index',compact('heavies'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $category=ProductCategories::get();
        $brands=Brand::get();
        $models=ModelsCars::get();
        return view('heavy::create',compact('category','brands','models'));
    }

    public function storeHeavyComission(Request $request){
        Heavy::where('is_active', 1)
        ->update(['commission_value' => $request->commission]);
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

    public function storeAllHeavyInsurance(Request $request){

        $chunkSize = 1000; // Adjust based on your server capacity

        $validated = $this->validateUpdateRequest($request);
        
        $value = $validated['value'];
        $type = $validated['type'];


        // Get all auction IDs in chunks
        DB::table('heavy')
        ->select('id')
        ->orderBy('id')
        ->chunk($chunkSize, function ($auctionLots) use ($value, $type) {
            $ids = $auctionLots->pluck('id')->toArray();
            
            // Split processing based on what exists and what doesn't
            $existingIds = DB::table('jdm_stock_heavy_other_charges')
                ->whereIn('jdm_heavy_id', $ids)
                ->pluck('jdm_heavy_id')
                ->toArray();
                
            $newIds = array_diff($ids, $existingIds);
            
            // Handle updates in bulk
            if (!empty($existingIds)) {
                $updateData = ['updated_at' => now()];
                
                if ($type == 'marine_insurance') {
                    $updateData['marine_insurance_value'] = (int) $value;
                } elseif ($type == 'inland_inspection') {
                    $updateData['inland_inspection_value'] = (int) $value;
                }
                
                DB::table('jdm_stock_heavy_other_charges')
                    ->whereIn('jdm_heavy_id', $existingIds)
                    ->update($updateData);
            }
            
            // Handle inserts in bulk
            if (!empty($newIds)) {
                $insertData = collect($newIds)->map(function ($id) use ($value, $type) {
                    return [
                        'jdm_heavy_id' => $id,
                        'marine_insurance_value' => ($type == 'marine_insurance') ? (int) $value : 0,
                        'inland_inspection_value' => ($type == 'inland_inspection') ? (int) $value : 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                })->toArray();
                
                DB::table('jdm_stock_heavy_other_charges')->insert($insertData);
            }
        });
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }

    public function storeCarInsuranceById(Request $request){
        JdmStockHeavyOtherCharges::whereIn('jdm_heavy_id',$request->selectedIds)
        ->update([$request->type . '_value' => $request->commission]);
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }
    public function storeHeavyShippingId(Request $request){
        Heavy::where('is_active', 1)
        ->where('id',$request->selectedIds)
        ->update(['shipping_value' => $request->commission]);
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }
    public function storeAllHeavyComission(Request $request){
        Heavy::where('is_active', 1)
        ->update(['commission_value' => $request->commission]);
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }
    public function storeAllHeavyShipping(Request $request){
        Heavy::where('is_active', 1)
        ->update(['shipping_value' => $request->commission]);
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }
    public function newArrivalCars(Request $request){
        Heavy::where('is_active', 1)
        ->where('id',$request->selectedIds)
        ->update(['new_arrival' => $request->check]);
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $Heavy=new Heavy();
        $Heavy->category=$request->category;
        $model_image=$request->file('image');
        if(!empty($model_image)){
          $org_filename = $model_image->getClientOriginalName();
          $org_extension = $model_image->getClientOriginalExtension();
          $image_name = 'heavy-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$org_extension;
          $org_path = public_path() . '/Cars';
          File::isDirectory($org_path) or File::makeDirectory($org_path, 0777, true, true);
          $model_image->move($org_path, $image_name);
        } else {
            $image_name = '';
        }
        $Heavy->image=$image_name;
        $Heavy->title=$request->title;
        // $Heavy->make=$request->maker;
        $model=ModelsCars::whereId($request->model)->value('model');
        if(!empty($model)){
            $Heavy->model=$model;
          } else {
              $notification= trans('translate.Model Not Found');
              $notification=array('messege'=>$notification,'alert-type'=>'error');
              return redirect()->route('admin.heavy.index',)->with($notification); 
          }
          $brand=Brand::whereId($request->brand)->value('slug');
          if(!empty($brand)){
            $Heavy->make=$brand;
          } else {
              $notification= trans('translate.Brand Not Found');
              $notification=array('messege'=>$notification,'alert-type'=>'error');
              return redirect()->route('admin.heavy.index',)->with($notification); 
          }
     
        $Heavy->year_of_reg=$request->year_of_registration;
        // $Heavy->grade
        $Heavy->chassis=$request->chassis_number;
        $Heavy->serial=$request->serial_number;
        $Heavy->yom=$request->year_of_made;
        $Heavy->kms=$request->kilometers;
        $Heavy->hrs=$request->hours_work_engine;
        $Heavy->engine=$request->engine_type;
        $Heavy->transmission=$request->transmission_type;
        $Heavy->fuel=$request->fuel;
        $Heavy->dimensions=$request->dimensions;
        $Heavy->price=$request->price_dollar;
        $Heavy->price_ru=$request->price_rupees;
        $Heavy->price_jpy=$request->price_yen;
        $Heavy->location=$request->location;
        $Heavy->sell_points=$request->sell_points;
        $Heavy->remarks=$request->remarks;
        $Heavy->is_active=$request->active == 'on' ? '1' : '0';
        $Heavy->is_ru_market=$request->russia_market == 'on' ? '1' : '0';
        $Heavy->is_na_market=$request->north_america_market == 'on' ? '1' : '0';
        $Heavy->hooks=$request->hooks;
        $Heavy->boom=$request->boom;
        $Heavy->jib=$request->jib;
        $Heavy->outrigger=$request->outrigger;
        $Heavy->save();
        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.heavy.index',)->with($notification);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('heavy::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $Heavy=Heavy::find($id);
        $category=ProductCategories::get();
        $brands=Brand::get();
        $models=ModelsCars::get();
        return view('heavy::edit',compact('Heavy','category','brands','models'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $Heavy=Heavy::find($id);
        $Heavy->category=$request->category;
        $model_image=$request->file('image');
        if(!empty($model_image)){
          $org_filename = $model_image->getClientOriginalName();
          $org_extension = $model_image->getClientOriginalExtension();
          $image_name = 'heavy-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$org_extension;
          $org_path = public_path() . '/Cars';
          File::isDirectory($org_path) or File::makeDirectory($org_path, 0777, true, true);
          $model_image->move($org_path, $image_name);
          $Heavy->image=$image_name;
        }
        $Heavy->title=$request->title;
        // $Heavy->make=$request->maker;
        // $Heavy->model=$request->model;
        $model=ModelsCars::whereId($request->model)->value('model');    
        if(!empty($model)){
            $Heavy->model=$model;
          } else {
              $notification= trans('translate.Model Not Found');
              $notification=array('messege'=>$notification,'alert-type'=>'error');
              return redirect()->route('admin.heavy.index',)->with($notification); 
          }
        $brand=Brand::whereId($request->brand)->value('slug');
        if(!empty($brand)){
          $Heavy->make=$brand;
        } else {
            $notification= trans('translate.Brand Not Found');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.heavy.index',)->with($notification); 
        }
        $Heavy->year_of_reg=$request->year_of_registration;
        // $Heavy->grade
        $Heavy->chassis=$request->chassis_number;
        $Heavy->serial=$request->serial_number;
        $Heavy->yom=$request->year_of_made;
        $Heavy->kms=$request->kilometers;
        $Heavy->hrs=$request->hours_work_engine;
        $Heavy->engine=$request->engine_type;
        $Heavy->transmission=$request->transmission_type;
        $Heavy->fuel=$request->fuel;
        $Heavy->dimensions=$request->dimensions;
        $Heavy->price=$request->price_dollar;
        $Heavy->price_ru=$request->price_rupees;
        $Heavy->price_jpy=$request->price_yen;
        $Heavy->location=$request->location;
        $Heavy->sell_points=$request->sell_points;
        $Heavy->remarks=$request->remarks;
        $Heavy->is_active=$request->active == 'on' ? '1' : '0';
        $Heavy->is_ru_market=$request->russia_market == 'on' ? '1' : '0';
        $Heavy->is_na_market=$request->north_america_market == 'on' ? '1' : '0';
        $Heavy->hooks=$request->hooks;
        $Heavy->boom=$request->boom;
        $Heavy->jib=$request->jib;
        $Heavy->outrigger=$request->outrigger;
        $Heavy->save();
        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.heavy.index',)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        Heavy::find($id)?->delete();
        $notification= trans('translate.Deleted Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.heavy.index',)->with($notification);
    }
    public function deleteHeavy(Request $request){
        $ids = $request->input('ids', []);
    
        // Validate input
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No IDs provided.']);
        }
        
        // Delete records
        Heavy::whereIn('id', $ids)->delete();
    
        return response()->json(['success' => true, 'message' => 'Records deleted successfully.']);
    }
}
