<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\HomePage;
use Modules\Page\Entities\HomePageTranslation;
use Modules\Page\Http\Requests\JoinDealerRequest;
use Image, File, Str;

class JoinDealerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $join_dealer = HomePage::first();
        $translate = HomePageTranslation::where(['home_page_id' => $join_dealer->id, 'lang_code' => $request->lang_code])->first();

        return view('page::section.join_as_dealer', compact('join_dealer','translate'));
    }

    public function update(JoinDealerRequest $request)
    {

        $translate = HomePageTranslation::where(['id' => $request->translate_id])->first();
        $translate->dealer_short_title = $request->short_title;
        $translate->dealer_title = $request->dealer_title;
        $translate->save();

        $join_dealer = HomePage::first();

        if($request->dealer_bg_image){
            $old_image = $join_dealer->dealer_bg_image;
            $image_name = 'dealer-bg-image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/website-images/'.$image_name;
            Image::make($request->dealer_bg_image)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $join_dealer->dealer_bg_image = $image_name;
            $join_dealer->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        if($request->dealer_foreground_image){
            $old_image = $join_dealer->dealer_foreground_image;
            $image_name = 'dealer-forground-image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/website-images/'.$image_name;
            Image::make($request->dealer_foreground_image)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $join_dealer->dealer_foreground_image = $image_name;
            $join_dealer->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }



        $notification = trans('translate.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }
}
