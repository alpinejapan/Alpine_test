<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\AboutUs;
use Modules\Page\Entities\AboutUsTranslation;
use Modules\Page\Http\Requests\AboutUsRequest;
use Image, File, Str;

class AboutUsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $about_us = AboutUs::first();
        $translate = AboutUsTranslation::where('lang_code', $request->lang_code)->first();

        return view('page::about_us', compact('about_us','translate'));
    }


    public function update(AboutUsRequest $request)
    {
        $about_us = AboutUs::first();

        if($request->about_image){
            $old_image = $about_us->about_image;
            $image_name = 'about-us'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/website-images/'.$image_name;
            Image::make($request->about_image)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $about_us->about_image = $image_name;
            $about_us->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        if($request->car_image){
            $old_image = $about_us->car_image;
            $image_name = 'car-icon'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/website-images/'.$image_name;
            Image::make($request->car_image)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $about_us->car_image = $image_name;
            $about_us->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        if($request->review_image){
            $old_image = $about_us->review_image;
            $image_name = 'reveiw-icon'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/website-images/'.$image_name;
            Image::make($request->review_image)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $about_us->review_image = $image_name;
            $about_us->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        $translate = AboutUsTranslation::where('lang_code', $request->lang_code)->first();
        $translate->header = $request->header;
        $translate->title = $request->title;
        $translate->description = $request->description;
        $translate->total_car = $request->total_car;
        $translate->total_car_title = $request->total_car_title;
        $translate->total_review = $request->total_review;
        $translate->total_review_title = $request->total_review_title;
        $translate->save();

        $notification= trans('translate.Updated Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function assign_language($lang_code){
        $about_translates = AboutUsTranslation::where('lang_code', admin_lang())->get();
        foreach($about_translates as $about_translate){
            $translate = new AboutUsTranslation();
            $translate->about_us_id = $about_translate->about_us_id;
            $translate->lang_code = $lang_code;
            $translate->header = $about_translate->header;
            $translate->title = $about_translate->title;
            $translate->description = $about_translate->description;
            $translate->total_car = $about_translate->total_car;
            $translate->total_car_title = $about_translate->total_car_title;
            $translate->total_review = $about_translate->total_review;
            $translate->total_review_title = $about_translate->total_review_title;
            $translate->save();
        }
    }

}
