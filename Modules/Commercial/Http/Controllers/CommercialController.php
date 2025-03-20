<?php

namespace Modules\Commercial\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Commercial\Entities\Commercial;
use Modules\Categories\Entities\ProductCategories;
use File;
use Yajra\DataTables\Facades\DataTables;
use Modules\Cars\Entities\AddCommercialImages;
use Modules\Brand\Entities\Brand;
use Modules\Models\Entities\ModelsCars;
use DB;

class CommercialController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $commercials = Commercial::all();
        return view('commercial::index',compact('commercials'));
    }

    public function fetchCommercials(Request $request){
        $commercials = Commercial::all();
        return DataTables::of($commercials)
            ->addIndexColumn()
            ->addColumn('Action', function ($row) {
                $editUrl = route('admin.commercial.edit', ['commercial' => $row->id]);
                $btn = '<a href="' . $editUrl . '" class="crancy-btn"><i class="fas fa-edit"></i> Edit</a>';
                $btn .= ' <a onclick="itemDeleteConfirmation(' . $row->id . ')" href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal" class="crancy-btn delete_danger_btn"><i class="fas fa-trash"></i> Delete</a>';
                return $btn;
            })
            ->editColumn('image',function($row){
                return '<a href="'.url('Small-heavy'.$row->image).'" download style="display: inline-block; margin-top: -9px;">
                <img src="'.url('Small-heavy'.$row->image).'" height="50" width="50" style="vertical-align: middle;"></a>';      
              })
            ->addColumn('Status', function ($row) {
                $status = ($row->is_active == 1) 
                    ? '<span class="badge bg-success text-white">Active</span>' 
                    : '<span class="badge bg-danger text-white">Inactive</span>';
                
                return $status;
            })
            ->addColumn('checkbox',function($row){
                return '<input type="checkbox" name="" id="masterCheckbox" class="form-control">';
            })
            ->rawColumns(['Action', 'Status','image','checkbox'])
            ->make(true);

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
        return view('commercial::create',compact('category','brands','models'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $smallHeavy=new Commercial();
        $smallHeavy->category=$request->category;
        $model_image=$request->file('image');
        if(!empty($model_image)){
          $org_filename = $model_image->getClientOriginalName();
          $org_extension = $model_image->getClientOriginalExtension();
          $image_name = 'small-heavy-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$org_extension;
          $org_path = public_path() . '/Cars';
          File::isDirectory($org_path) or File::makeDirectory($org_path, 0777, true, true);
          $model_image->move($org_path, $image_name);
        } else {
            $image_name = '';
        }
        $smallHeavy->image=$image_name;
        $smallHeavy->title=$request->title;
        // $smallHeavy->make=$request->maker;
        $model=ModelsCars::whereId($request->model)->value('model');
        if(!empty($model)){
            $smallHeavy->model=$model;
          } else {
              $notification= trans('translate.Model Not Found');
              $notification=array('messege'=>$notification,'alert-type'=>'error');
              return redirect()->route('admin.commercial.index',)->with($notification); 
          }
        $brand=Brand::whereId($request->brand)->value('slug');
        if(!empty($brand)){
          $smallHeavy->make=$brand;
        } else {
            $notification= trans('translate.Brand Not Found');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.commercial.index',)->with($notification); 
        }
        // $smallHeavy->model=$request->model;
        $smallHeavy->year_of_reg=$request->year_of_registration;
        // $smallHeavy->grade
        $smallHeavy->chassis=$request->chassis_number;
        $smallHeavy->serial=$request->serial_number;
        $smallHeavy->yom=$request->year_of_made;
        $smallHeavy->kms=$request->kilometers;
        $smallHeavy->engine=$request->engine_type;
        $smallHeavy->transmission=$request->transmission_type;
        $smallHeavy->fuel=$request->fuel;
        $smallHeavy->dimensions=$request->dimensions;
        $smallHeavy->price=$request->price_dollar;
        $smallHeavy->price_ru=$request->price_rupees;
        $smallHeavy->price_jpy=$request->price_yen;
        $smallHeavy->location=$request->location;;
        $smallHeavy->sell_points=$request->sell_points;
        $smallHeavy->remarks=$request->remarks;
        $smallHeavy->is_active=$request->active == 'on' ? '1' : '0';
        $smallHeavy->is_ru_market=$request->russia_market == 'on' ? '1' : '0';
        $smallHeavy->is_na_market=$request->north_america_market == 'on' ? '1' : '0';
        $smallHeavy->save();
        
        $lastId = AddCommercialImages::max('id') ?? 0;

// Create directory for the car if it doesn't exist


if($request->hasFile('cover_image')) {
    $model_image = $request->file('cover_image');
    $baseDir = public_path() . '/Cars/ProductImages/' . $smallHeavy->id;
    foreach($model_image as $model_image) {

        // Create new instance for each image
        $product_images = new AddCommercialImages();
        $product_images->category = $smallHeavy->id;
        
        // Increment lastId for each new image
        $lastId++;
        
        // Get original file details
        $org_filename = $model_image->getClientOriginalName();
 
        $org_extension = $model_image->getClientOriginalExtension();
        
        // Generate unique name for each image
        $image_name = $smallHeavy->id . '-' . $lastId . 
                     date('-Y-m-d-h-i-s-') . 
                     rand(999,9999) . '.' . 
                     $org_extension;
        
        // Set the full path for storing the image
        $full_path = 'heavy_photos/' . $smallHeavy->id . '/' . $image_name;
        
        // Move the file
        $model_image->move($baseDir, $image_name);
        
        // Save image record
        $product_images->image = $full_path;
        $product_images->save();
    }
}




        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.commercial.index',)->with($notification);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('commercial::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $category=ProductCategories::get();
        $commercial=Commercial::find($id);
        $brands=Brand::get();
        $models=ModelsCars::get();
        return view('commercial::edit',compact('category','commercial','brands','models'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $smallHeavy=Commercial::find($id);
        $smallHeavy->category=$request->category;
        $model_image=$request->file('image');
        if(!empty($model_image)){
          $org_filename = $model_image->getClientOriginalName();
          $org_extension = $model_image->getClientOriginalExtension();
          $image_name = 'small-heavy-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$org_extension;
          $org_path = public_path() . '/Small-Heavy';
          File::isDirectory($org_path) or File::makeDirectory($org_path, 0777, true, true);
          $model_image->move($org_path, $image_name);
          $smallHeavy->image=$image_name;
        } 
        $smallHeavy->title=$request->title;
        // $smallHeavy->make=$request->maker;
        // $smallHeavy->model=$request->model;
        $model=ModelsCars::whereId($request->model)->value('model');
        if(!empty($model)){
            $smallHeavy->model=$model;
          } else {
              $notification= trans('translate.Model Not Found');
              $notification=array('messege'=>$notification,'alert-type'=>'error');
              return redirect()->route('admin.commercial.index',)->with($notification); 
          }
        $brand=Brand::whereId($request->brand)->value('slug');
        if(!empty($brand)){
          $smallHeavy->make=$brand;
        } else {
            $notification= trans('translate.Brand Not Found');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->route('admin.commercial.index',)->with($notification); 
        }
        $smallHeavy->year_of_reg=$request->year_of_registration;
        // $smallHeavy->grade
        $smallHeavy->chassis=$request->chassis_number;
        $smallHeavy->serial=$request->serial_number;
        $smallHeavy->yom=$request->year_of_made;
        $smallHeavy->kms=$request->kilometers;
        $smallHeavy->engine=$request->engine_type;
        $smallHeavy->transmission=$request->transmission_type;
        $smallHeavy->fuel=$request->fuel;
        $smallHeavy->dimensions=$request->dimensions;
        $smallHeavy->price=$request->price_dollar;
        $smallHeavy->price_ru=$request->price_rupees;
        $smallHeavy->price_jpy=$request->price_yen;
        $smallHeavy->location=$request->location;
        $smallHeavy->remarks=$request->remarks;
        $smallHeavy->sell_points=$request->sell_points;
        $smallHeavy->is_active=$request->active == 'on' ? '1' : '0';
        $smallHeavy->is_ru_market=$request->russia_market == 'on' ? '1' : '0';
        $smallHeavy->is_na_market=$request->north_america_market == 'on' ? '1' : '0';
        $smallHeavy->save();
        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.commercial.index',)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        Commercial::find($id)?->delete();
        $notification= trans('translate.Deleted Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.commercial.index',)->with($notification);   
    }
    public function deleteCommercials(Request $request){
        $ids = $request->input('ids', []);
    
        // Validate input
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No IDs provided.']);
        }
        
        // Delete records
        Commercial::whereIn('id', $ids)->delete();
    
        return response()->json(['success' => true, 'message' => 'Records deleted successfully.']);  
    }
    public function storeCommercialComission(Request $request){
        Commercial::where('is_active', 1)
        ->update(['commission_value' => $request->commission]);
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }
    public function storeAllComissionComission(Request $request){
        Commercial::where('is_active', 1)
        ->update(['commission_value' => $request->commission]);
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }
    public function newArrivalCars(Request $request){
        // DB::enableQueryLog();
        Commercial::where('is_active', 1)
        ->where('id',$request->selectedIds)
        ->update(['new_arrival' => $request->check]);
        // dd(DB::getQueryLog());
        $notification= trans('translate.Success');
        $notification=array('message'=>$notification,'alert-type'=>'success');
        return response()->json(['success' => true, 'message' => 'Stored Successfully']);
    }
}
