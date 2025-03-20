<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\AdsBanner;
use Image, Str, File;

class AdsBannerController extends Controller
{
    public function index(){

        $ads_banner = AdsBanner::all();

        return view('admin.ads_banner', ['ads_banner' => $ads_banner]);
    }

    public function update(Request $request, $id){

        $rules = [
            'link'=>'required',
        ];
        $customMessages = [
            'link.required' => trans('translate.Link is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $banner = AdsBanner::findOrFail($id);
        $banner->link = $request->link;
        $banner->status = $request->status ? 'enable' : 'disable';
        $banner->save();

        if($request->hasFile('image')){
            $old_image = $banner->image;
            $extention = $request->image->getClientOriginalExtension();
            $image_name = 'Home1'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name = 'uploads/website-images/'.$image_name;
            Image::make($request->image)
                ->save(public_path($image_name));

            $banner->image = $image_name;
            $banner->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        $notification= trans('translate.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);


    }


}
