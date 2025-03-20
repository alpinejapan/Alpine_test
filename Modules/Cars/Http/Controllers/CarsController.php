<?php

namespace Modules\Cars\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Categories\Entities\ProductCategories;
use Modules\Cars\Entities\Cars;
use File;
use Modules\Brand\Entities\Brand;
use Modules\Models\Entities\ModelsCars;
use Modules\Brand\Entities\BrandTranslation;
use Modules\Cars\Entities\AddProductImages;
use Session;
use Illuminate\Support\Facades\DB;
use App\Models\JdmStockBlogOtherCharges;
class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Cars::orderBy('id','desc')->get();
        return view('cars::index',compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category=ProductCategories::get();
        // $brands=Brand::get();
        $brands=BrandTranslation::where('lang_code',Session::get('front_lang'))->get();
        $models=ModelsCars::get();
        return view('cars::create')->with([
            'category' => $category,
            'brands' => $brands,
            'models'=>$models,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cars=new Cars();
        // $cars->category=$request->category;
        $model_image=$request->file('image');
        if(!empty($model_image)){
          $org_filename = $model_image->getClientOriginalName();
          $org_extension = $model_image->getClientOriginalExtension();
          $image_name = 'cars-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$org_extension;
          $org_path = public_path() . '/Cars';
          File::isDirectory($org_path) or File::makeDirectory($org_path, 0777, true, true);
          $model_image->move($org_path, $image_name);
        } else {
            $image_name = '';
        }





        $cars->image=$image_name;
        $cars->title=$request->title;
        // $cars->make=$request->maker;
        $model=ModelsCars::whereId($request->model)->value('model');
        if(!empty($model)){
            $cars->model=$model;
        } else {
              $notification= trans('translate.Model Not Found');
              $notification=array('messege'=>$notification,'alert-type'=>'error');
              return redirect()->route('admin.cars.index',)->with($notification); 
        }
        $brand=Brand::whereId($request->brand)->value('slug');
        if(!empty($brand)){
          $cars->make=$brand;
        } else {
            $notification= trans('translate.Brand Not Found');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.cars.index',)->with($notification); 
        }
        $cars->grade=$request->grade;
        $cars->color=$request->color;
        $cars->int_col=$request->interial_color;
        $cars->year_of_reg=$request->year_of_registration;
        $cars->chassis=$request->chassis_number;
        $cars->location=$request->location;
        // $cars->score=$request->score;
        $cars->yom=$request->year_of_made;
        $cars->kms=$request->kilometers;
        $cars->engine=$request->engine_captibility;
        $cars->engine_type=$request->engine_type;
        $cars->transmission=$request->transmission;
        $cars->fuel=$request->fuel;
        $cars->has_video=$request->video_link;
        $cars->inside=$request->inside;
        $cars->outside=$request->outside;
        // $cars->dimensions=$request->dimensions;
        $cars->price=$request->price_dollar;
        $cars->price_ru=$request->price_rupees;
        $cars->price_jpy=$request->price_yen;
        $cars->sell_points=$request->sell_points;
        $cars->is_active=$request->active == 'on' ? '1' : '0';
        $cars->is_ru_market=$request->russia_market == 'on' ? '1' : '0';
        $cars->is_na_market=$request->north_america_market == 'on' ? '1' : '0';
        $cars->abs=$request->abs == 'on' ? '1' : '0';
        $cars->aw=$request->aw == 'on' ? '1' : '0';
        $cars->pw=$request->pw_power_windows == 'on' ? '1' : '0';
        $cars->ps=$request->ps_power_steering == 'on' ? '1' : '0';
        $cars->ab=$request->ab_air_bag == 'on' ? '1' : '0';
        $cars->sr=$request->sr_sunroof == 'on' ? '1' : '0';
        $cars->save();

       // First get the last ID or set to 0 if no records exist
$lastId = AddProductImages::max('id') ?? 0;

// Create directory for the car if it doesn't exist


if($request->hasFile('cover_image')) {
    $model_image = $request->file('cover_image');
    $baseDir = public_path() . '/Cars/' . $cars->id;
    foreach($model_image as $model_image) {

        // Create new instance for each image
        $product_images = new AddProductImages();
        $product_images->category = $cars->id;
        
        // Increment lastId for each new image
        $lastId++;
        
        // Get original file details
        $org_filename = $model_image->getClientOriginalName();
 
        $org_extension = $model_image->getClientOriginalExtension();
        
        // Generate unique name for each image
        $image_name = $cars->id . '-' . $lastId . $org_extension;
        
        // Set the full path for storing the image
        $full_path =  $image_name;
        
        // Move the file
        $model_image->move($baseDir, $image_name);
        
        // Save image record
        $product_images->image = $full_path;
        $product_images->save();
    }
}



        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.cars.index',)->with($notification);
    }

    public function storeCarComission(Request $request){
        Cars::where('is_active', 1)
        ->whereIn('id',$request->selectedIds)
        ->update(['commission_value' => $request->commission]);
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }

    public function storeShippingIdWise(Request $request){
        Cars::where('is_active', 1)
        ->whereIn('id',$request->selectedIds)
        ->update(['shipping_value' => $request->commission]);
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }
    public function storeCarInsuranceById(Request $request){
        JdmStockBlogOtherCharges::
        // where('is_active', 1)
        whereIn('jdm_blog_id',$request->selectedIds)
        ->update([$request->type . '_value' => $request->commission]);
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }
    public function storeAllCarComission(Request $request){
        Cars::where('is_active', 1)
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
    public function storeAllCarInsurance(Request $request){

        $chunkSize = 1000; // Adjust based on your server capacity

        $validated = $this->validateUpdateRequest($request);
        
        $value = $validated['value'];
        $type = $validated['type'];


        // Get all auction IDs in chunks
        DB::table('blog')
        ->select('id')
        ->orderBy('id')
        ->chunk($chunkSize, function ($auctionLots) use ($value, $type) {
            $ids = $auctionLots->pluck('id')->toArray();
            
            // Split processing based on what exists and what doesn't
            $existingIds = DB::table('jdm_stock_blog_other_charges')
                ->whereIn('jdm_blog_id', $ids)
                ->pluck('jdm_blog_id')
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
                
                DB::table('jdm_stock_blog_other_charges')
                    ->whereIn('jdm_blog_id', $existingIds)
                    ->update($updateData);
            }
            
            // Handle inserts in bulk
            if (!empty($newIds)) {
                $insertData = collect($newIds)->map(function ($id) use ($value, $type) {
                    return [
                        'jdm_blog_id' => $id,
                        'marine_insurance_value' => ($type == 'marine_insurance') ? (int) $value : 0,
                        'inland_inspection_value' => ($type == 'inland_inspection') ? (int) $value : 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                })->toArray();
                
                DB::table('jdm_stock_blog_other_charges')->insert($insertData);
            }
        });
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }
    public function storeAllCarShipping(Request $request){
        Cars::where('is_active', 1)
        ->update(['shipping_value' => $request->commission]);
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }
    public function newArrivalCars(Request $request){
        $test=Cars::where('is_active', 1)
        ->where('id',$request->selectedIds)
        ->update(['new_arrival' => $request->check]);
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }

    

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('cars::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cars=Cars::find($id);
        $category=ProductCategories::get();
        $brands=Brand::get();
        $models=ModelsCars::get();
        return view('cars::edit',compact('cars','category','brands','models'));
    }

    /** 
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $cars=Cars::find($id);
        $cars->category=$request->category;
        $model_image=$request->file('image');
        if(!empty($model_image)){
          $org_filename = $model_image->getClientOriginalName();
          $org_extension = $model_image->getClientOriginalExtension();
          $image_name = 'cars-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$org_extension;
          $org_path = public_path() . '/Cars';
          File::isDirectory($org_path) or File::makeDirectory($org_path, 0777, true, true);
          $model_image->move($org_path, $image_name);
          $cars->image=$image_name;
        } 
        $cars->title=$request->title;   
        // $cars->make=$request->maker;
        $model=ModelsCars::whereId($request->model)->value('model');    
        if(!empty($model)){
            $cars->model=$model;
          } else {
              $notification= trans('translate.Model Not Found');
              $notification=array('messege'=>$notification,'alert-type'=>'error');
              return redirect()->route('admin.cars.index',)->with($notification); 
          }
        $brand=Brand::whereId($request->brand)->value('slug');
        if(!empty($brand)){
          $cars->make=$brand;
        } else {
            $notification= trans('translate.Brand Not Found');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.cars.index',)->with($notification); 
        }
        $cars->grade=$request->grade;
        $cars->color=$request->color;
        $cars->int_col=$request->interial_color;
        $cars->year_of_reg=$request->year_of_registration;
        $cars->chassis=$request->chassis_number;
        $cars->score=$request->score;
        $cars->yom=$request->year_of_made;
        $cars->kms=$request->kilometers;
        $cars->engine=$request->engine_captibility;
        $cars->engine_type=$request->engine_type;
        $cars->transmission=$request->transmission_type;
        $cars->fuel=$request->fuel;
        $cars->has_video=$request->video_link;
        $cars->has_video=$request->video_link;
        $cars->has_video=$request->video_link;
        $cars->inside=$request->inside;
        $cars->outside=$request->outside;
        // $cars->dimensions=$request->dimensions;
        $cars->price=$request->price_dollar;
        $cars->price_ru=$request->price_rupees;
        $cars->price_jpy=$request->price_yen;
        $cars->sell_points=$request->sell_points;
        $cars->is_active=$request->active == 'on' ? '1' : '0';
        $cars->is_ru_market=$request->russia_market == 'on' ? '1' : '0';
        $cars->is_na_market=$request->north_america_market == 'on' ? '1' : '0';
        $cars->abs=$request->abs == 'on' ? '1' : '0';
        $cars->aw=$request->aw == 'on' ? '1' : '0';
        $cars->pw=$request->pw_power_windows == 'on' ? '1' : '0';
        $cars->ps=$request->ps_power_steering == 'on' ? '1' : '0';
        $cars->ab=$request->ab_air_bag == 'on' ? '1' : '0';
        $cars->sr=$request->sr_sunroof == 'on' ? '1' : '0';
        $cars->save();
        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.cars.index',)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Cars::find($id)?->delete();
        $notification= trans('translate.Deleted Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.car.index',)->with($notification);
    }
    public function deleteCar(Request $request){
        $ids = $request->input('ids', []);
       
    
        // Validate input
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No IDs provided.']);
        }
        
        // Delete records
        Cars::whereIn('id', $ids)->delete();
    
        return response()->json(['success' => true, 'message' => 'Records deleted successfully.']);
    }
}
