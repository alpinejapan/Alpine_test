<?php

namespace Modules\ContactMessage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ContactMessage\Entities\ContactMessage;
use Modules\GeneralSetting\Entities\Setting;
use App\Models\VehicleEnquiry;
use DB;
use Carbon\Carbon;
use Modules\Cars\Entities\Cars;
use Session;
use Modules\Brand\Entities\Brand;

class ContactMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    public function getEnquiryBrands(Request $request){
       $models=VehicleEnquiry::where(DB::raw('LOWER(make)'),$request->brand)
       ->select('model')
       ->distinct()
       ->get();
       return response()->json(['status'=>true,'message'=>$models]);
    }
    public function VehicleEnquiry(Request $request){
        $filters = [
            'start_year' => null,
            'end_year' => null,
            'make'=>null,
            'model'=>null
        ];

        $brands = VehicleEnquiry::join('brands as b', DB::raw('LOWER(vehicle_enquiries.make)'), '=', 'b.slug')
                         ->join('brand_translations as bt','bt.brand_id','=','b.id')
                         ->where('bt.lang_code',Session::get('front_lang'))
            ->select('b.slug','bt.name as brand_name')
            ->distinct('b.slug')->get();

            
        DB::enableQueryLog();
        $query = VehicleEnquiry::query();
        
        // Filter by start date
        if ($request->filled('start_year')) {
            $filters['start_year'] = $request->start_year;
            $startDate = Carbon::parse($request->start_year)->startOfDay();
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($request->filled('end_year')) {
            $filters['end_year'] = $request->end_year;
            $endDate = Carbon::parse($request->end_year)->endOfDay();
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        if ($request->filled('make')) {
            $filters['make'] = $request->make;
            $query->where( DB::raw('LOWER(make)'),$request->make);
        }
        
        if ($request->filled('model')) {
            $filters['model'] = $request->model;
            $query->where('model',$request->model);
        }
        
        // Fetch results
        $vehicle_enquiry = $query->orderBy('id', 'desc')->get();

        // echo json_encode($filters);die();

   

        // $vehicle_enquiry = VehicleEnquiry::orderBy('id','desc')->latest()->get();
        return view('contactmessage::vehicle_enquiry', compact('vehicle_enquiry','filters','brands'));

    }

    public function contact_message(){
        $contact_messages = ContactMessage::orderBy('id','desc')->latest()->get();
        return view('contactmessage::contact_message', compact('contact_messages'));
    }

    public function show_message($id){

        $contact_message = ContactMessage::findOrFail($id);
        return view('contactmessage::show_contact_message', compact('contact_message'));
    }

    public function delete_message($id){

        $contact_message = ContactMessage::findOrFail($id);
        $contact_message->delete();

        $notification = trans('translate.Delete Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function contact_message_setting(Request $request){
        $rules = [
            'contact_message_mail' => 'required',
        ];
        $customMessages = [
            'contact_message_mail.required' => trans('translate.Contact email is required'),
        ];
        $request->validate($rules,$customMessages);

        $setting = Setting::first();
        $setting->contact_message_mail = $request->contact_message_mail;
        $setting->send_contact_message = $request->send_contact_message;
        $setting->save_contact_message = $request->save_contact_message;
        $setting->save();

        $notification = trans('translate.Update Successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }
}
