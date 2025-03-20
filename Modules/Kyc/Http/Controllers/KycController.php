<?php

namespace Modules\Kyc\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Kyc\Entities\KycInformation;
use Modules\Kyc\Entities\KycType;
use Session, Auth, Image, File, Str ,Mail;
use App\Helpers\MailHelper;
use Modules\Kyc\Emails\KycVerifactionEmail;
use App\Models\Homepage;
use App\Models\Setting;
use Modules\Cars\Entities\Cars;
use Modules\Heavy\Entities\Heavy;
use Modules\SmallHeavy\Entities\SmallHeavy;
use Modules\Brand\Entities\Brand;
use DB;

class KycController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function kyc(){

        $jdm_core_brand = Brand::where('status', 'enable')->get();

        $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
                     ->join('brand_translations as bt','bt.brand_id','=','b.id')
                     ->where('bt.lang_code',Session::get('front_lang'))
        ->select('b.slug','bt.name as brand_name')
        ->distinct('b.slug')->get();


        $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        ->join('brand_translations as bt','bt.brand_id','=','b.id')
        ->where('bt.lang_code',Session::get('front_lang'))
        ->select('b.slug','bt.name as brand_name')
        ->distinct('b.slug')->get();

        $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        ->join('brand_translations as bt','bt.brand_id','=','b.id')
        ->where('bt.lang_code',Session::get('front_lang'))
        ->select('b.slug','bt.name as brand_name')
        ->distinct('b.slug')->get();


        $jdm_brand['car']=$jdm_legend;
        $jdm_brand['heavy']=$jdm_legend_heavy;
        $jdm_brand['small_heavy']=$jdm_legend_heavy;

        $influencer = Auth::guard('web')->user();

        $kyc = KycInformation::where(['user_id' => $influencer->id])->first();
        $kycType = KycType::orderBy('id', 'desc')->get();

        return view('kyc::User.kyc.index', [
            'kyc' => $kyc,
            'kycType' => $kycType,
            'jdm_legend' => $jdm_brand,
            'jdm_core_brand' => $jdm_core_brand,
        ]);
    }

    public function kycSubmit(Request $request){
        $influencer = Auth::guard('web')->user();
        $rules = [
            'kyc_id'=>'required',
            'file'=>'required',
        ];
        $customMessages = [
            'kyc_id.required' => trans('translate.Type of is required'),
            'file' => trans('translate.File is required'),
        ];

        $request->validate($rules,$customMessages);

        $kyc = new KycInformation();

        if($request->file){
            $extention = $request->file->getClientOriginalExtension();
            $image_name = 'document'.date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name = 'uploads/custom-images/'.$image_name;
            $request->file->move(public_path('uploads/custom-images/'),$image_name);
            $kyc->file = $image_name;
        }

        $kyc->kyc_id = $request->kyc_id;
        $kyc->user_id = $influencer->id;
        $kyc->message = $request->message;
        $kyc->status = 0;
        $kyc->save();

        $notification= trans('translate.Information Submited Successfully. Pls Wait for Conformation');
        MailHelper::setMailConfig();

        $subject= trans('translate.KYC Verifaction');
        $message = 'Name: ' . $influencer->name . '<br>' . $notification;

        Mail::to($influencer->email)->send(new KycVerifactionEmail($message,$subject));

        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

}

