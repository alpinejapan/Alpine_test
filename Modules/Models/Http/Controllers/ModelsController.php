<?php

namespace Modules\Models\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Models\Entities\ModelsCars;
use Modules\Categories\Entities\ProductCategories;
use Modules\Models\Http\Requests\ModelRequest;
use File;
use Modules\Brand\Entities\Brand;
use Modules\Brand\Entities\BrandTranslation;
use Session;

class ModelsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $ModelsCars=ModelsCars::with('getBrand')->
        orderBy('id','DESC')->get();
        return view('models::index')->with('models_cars',$ModelsCars);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {   
        // $category=ProductCategories::get();
        $brands = Brand::with(['getBrands' => function($query) {
            $query->where('lang_code', Session::get('front_lang'))
                  ->select('brand_translations.brand_id', 'brand_translations.name'); // Select columns from the BrandTranslation table
        }])->get();
        return view('models::create')->with('brands',$brands);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(ModelRequest $request)
    {
        $model=new ModelsCars();
        $model_image=$request->file('image');
        if(!empty($model_image)){
          $org_filename = $model_image->getClientOriginalName();
          $org_extension = $model_image->getClientOriginalExtension();
          $image_name = 'model-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$org_extension;
          $org_path = public_path() . '/Cars';
          File::isDirectory($org_path) or File::makeDirectory($org_path, 0777, true, true);
          $model_image->move($org_path, $image_name);
        } else {
            $image_name = '';
        }
       $model->image=$image_name;
    //    $model->category=$request->category;
       $model->brand_id=$request->brand;
       $model->model=$request->model;
       $model->save();
       $notification= trans('translate.Created Successfully');
       $notification=array('messege'=>$notification,'alert-type'=>'success');
       return redirect()->route('admin.models.index',)->with($notification);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('models::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        // $models=ModelsCars::find($id);
        $models=ModelsCars::with(['getBrand'=>function($query){
            $query->where('brand_translations.lang_code',Session::get('front_lang'));
        }])->
        orderBy('id','DESC')->find($id);
        
        // $categories=ProductCategories::get();
        $brand=BrandTranslation::where('brand_translations.lang_code', Session::get('front_lang'))->get();
        return view('models::edit',compact('models','brand'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(ModelRequest $request, $id)
    {
        $model=ModelsCars::find($id);
        $model_image=$request->file('image');
        if(!empty($model_image)){
          $org_filename = $model_image->getClientOriginalName();
          $org_extension = $model_image->getClientOriginalExtension();
          $image_name = 'model-'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$org_extension;
          $org_path = public_path() . '/Model';
          File::isDirectory($org_path) or File::makeDirectory($org_path, 0777, true, true);
          $model_image->move($org_path, $image_name);
          $model->image=$image_name;
        }
       $model->brand_id=$request->brand;
       $model->model=$request->model;
       $model->save();
        $notification= trans('translate.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.models.index',)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        ModelsCars::find($id)?->delete();
        $notification= trans('translate.Deleted Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.models.index',)->with($notification);
    }

    public function deleteModels(Request $request){
        $ids = $request->input('ids', []);
    
        // Validate input
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No IDs provided.']);
        }
        
        // Delete records
        ModelsCars::whereIn('id', $ids)->delete();
    
        return response()->json(['success' => true, 'message' => 'Records deleted successfully.']);
    }
}
