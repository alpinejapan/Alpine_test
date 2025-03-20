<?php

namespace Modules\Page\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Entities\HomePage;
use Image, File, Str;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $loan_calculator = HomePage::first();

        return view('page::section.loan_calculator', ['loan_calculator' => $loan_calculator]);
    }

    public function update(Request $request)
    {

        $loan_calculator = HomePage::first();

        if($request->loan_bg_image){
            $old_image = $loan_calculator->loan_bg_image;
            $image_name = 'dealer-bg-image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/website-images/'.$image_name;
            Image::make($request->loan_bg_image)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $loan_calculator->loan_bg_image = $image_name;
            $loan_calculator->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        if($request->loan_foreground_image){
            $old_image = $loan_calculator->loan_foreground_image;
            $image_name = 'dealer-forground-image'.date('-Y-m-d-h-i-s-').rand(999,9999).'.webp';
            $image_name ='uploads/website-images/'.$image_name;
            Image::make($request->loan_foreground_image)
                ->encode('webp', 80)
                ->save(public_path().'/'.$image_name);
            $loan_calculator->loan_foreground_image = $image_name;
            $loan_calculator->save();

            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        $notification = trans('translate.Updated Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }
}
