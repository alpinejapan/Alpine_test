<?php

namespace Modules\Categories\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Categories\Entities\ProductCategories;
use Modules\Categories\Http\Requests\CategoriesRequest;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $categories = ProductCategories::all();
        return view('categories::index')->with('categories',$categories);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('categories::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CategoriesRequest $request)
    {

        $ProductCategories=new ProductCategories();
        $ProductCategories->name=$request->name;
        $ProductCategories->price=$request->average_price;
        $ProductCategories->is_active=$request->active == 'on' ? '1' : '0';
        $ProductCategories->save();
        $notification= trans('translate.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.categories.index',)->with($notification);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('categories::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $category=ProductCategories::find($id);
        return view('categories::edit')->with('categories',$category);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(CategoriesRequest $request, $id)
    {
        $category=ProductCategories::find($id);
        $category->name=$request->name;
        $category->price=$request->average_price;
        $category->is_active=$request->active == 'on' ? '1' : '0';
        $category->save();
        $notification= trans('translate.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.categories.index',)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        ProductCategories::find($id)?->delete();
        $notification= trans('translate.Deleted Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.categories.index',)->with($notification);
    }

    public function deleteCategories(Request $request){
        $ids = $request->input('ids', []);
    
        // Validate input
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No IDs provided.']);
        }
        
        // Delete records
        ProductCategories::whereIn('id', $ids)->delete();
    
        return response()->json(['success' => true, 'message' => 'Records deleted successfully.']);
    }
}
