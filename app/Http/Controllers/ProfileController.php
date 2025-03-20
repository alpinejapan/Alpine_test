<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Modules\Subscription\Entities\SubscriptionPlan;
use Modules\Subscription\Entities\SubscriptionHistory;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\Review;
use App\Rules\Captcha;
use Hash, Image, File, Str, Session;
use Modules\Car\Entities\Car;
use Modules\Cars\Entities\Cars;
use Modules\Heavy\Entities\Heavy;
use Modules\SmallHeavy\Entities\SmallHeavy;
use Modules\Brand\Entities\Brand;
use DB;
use App\Models\VehicleEnquiry;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function dashboard(Request $request)
    {

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

        $user = Auth::guard('web')->user();

        $cars = Car::where('agent_id', $user->id)->get()->take(10);

        $total_car = Car::where('agent_id', $user->id)->count();

        $total_featured_car = Car::where('agent_id', $user->id)->where('is_featured', 'enable')->count();

        $total_wishlist = Wishlist::where('user_id', $user->id)->count();

        return view('profile.dashboard', ['user' => $user, 
        'cars' => $cars, 
        'total_car' => $total_car, 
        'total_featured_car' => $total_featured_car, 
        'total_wishlist' => $total_wishlist,
        'jdm_legend'=>$jdm_brand,
        'jdm_core_brand'=>$jdm_core_brand]);
    }

    public function edit(Request $request)
    {
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

        
        $user = Auth::guard('web')->user();

        return view('profile.edit', [
            'user' => $user,
            'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand
        ]);
    }

    public function update(Request $request)
    {
        $rules = [
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'address'=>'required|max:220',
        ];
        $customMessages = [
            'name.required' => trans('translate.Name is required'),
            'email.required' => trans('translate.Email is required'),
            'phone.required' => trans('translate.Phone is required'),
            'address.required' => trans('translate.Address is required')
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('web')->user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->designation = $request->designation;
        $user->google_map = $request->google_map;
        $user->about_me = $request->about_me;
        $user->instagram = $request->instagram;
        $user->facebook = $request->facebook;
        $user->linkedin = $request->linkedin;
        $user->twitter = $request->twitter;
        $user->sunday = $request->sunday;
        $user->monday = $request->monday;
        $user->tuesday = $request->tuesday;
        $user->wednesday = $request->wednesday;
        $user->thursday = $request->thursday;
        $user->friday = $request->friday;
        $user->saturday = $request->saturday;
        $user->save();

        $notification= trans('translate.Your profile updated successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function change_password(Request $request)
    {
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

        return view('profile.change_password', [
        'jdm_legend'=>$jdm_brand,
        'jdm_core_brand'=>$jdm_core_brand]);
    }

    public function update_password(Request $request)
    {
        $rules = [
            'current_password'=>'required',
            'password'=>'required|min:4|confirmed',
        ];
        $customMessages = [
            'current_password.required' => trans('translate.Current password is required'),
            'password.required' => trans('translate.Password is required'),
            'password.min' => trans('translate.Password minimum 4 character'),
            'password.confirmed' => trans('translate.Confirm password does not match'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('web')->user();
        if(Hash::check($request->current_password, $user->password)){
            $user->password = Hash::make($request->password);
            $user->save();

            $notification = trans('translate.Password change successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);

        }else{
            $notification = trans('translate.Current password does not match');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
    }

    public function upload_user_avatar(Request $request){

        $rules = [
            'image' => 'sometimes|required|mimes:jpeg,png,jpg|max:1024'
        ];
        $customMessages = [
            'image.required' => trans('translate.Image is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('web')->user();
        if($request->file('image')){
            $old_image = $user->image;
            $user_image = $request->image;
            $extention = $user_image->getClientOriginalExtension();
            $image_name = Str::slug($user->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name = 'uploads/custom-images/'.$image_name;
            Image::make($user_image)->save(public_path().'/'.$image_name);
            $user->image = $image_name;
            $user->save();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }

        $notification = trans('translate.Image updated successfully');
        return response()->json(['message' => $notification]);
    }


    public function pricing_plan(){

        $subscription_plans = SubscriptionPlan::orderBy('serial', 'asc')->where('status', 'active')->get();

        return view('pricing_plan', ['subscription_plans' => $subscription_plans]);
    }

    public function orders(){
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


        $user = Auth::guard('web')->user();

        $histories = SubscriptionHistory::where('user_id', $user->id)->latest()->get();

        return view('profile.orders', ['histories' => $histories, 'user' => $user,
        'jdm_legend'=>$jdm_brand,
        'jdm_core_brand'=>$jdm_core_brand]);
    }


    public function reviews(){

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


        $user = Auth::guard('web')->user();

        $reviews = Review::with('car.dealer')->latest()->where('user_id', $user->id)->get();

        return view('profile.reviews', ['reviews' => $reviews,
        'jdm_legend'=>$jdm_brand,
        'jdm_core_brand'=>$jdm_core_brand]);
    }

    public function store_review(Request $request){

        $rules = [
            'rating'=>'required',
            'comment'=>'required',
            'agent_id'=>'required',
            'car_id'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];
        $customMessages = [
            'rating.required' => trans('translate.Rating is required'),
            'comment.required' => trans('translate.Review is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user = Auth::guard('web')->user();

        $is_exist = Review::where(['user_id' => $user->id, 'car_id' => $request->car_id])->count();

        if($is_exist == 0){
            $review = new Review();
            $review->user_id = $user->id;
            $review->rating = $request->rating;
            $review->comment = $request->comment;
            $review->agent_id = $request->agent_id;
            $review->car_id = $request->car_id;
            $review->save();

            $notification = trans('translate.Review submited successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);

        }else{
            $notification = trans('translate.Review already submited');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

    }


    public function add_to_wishlist($id){
        $user = Auth::guard('web')->user();
        $is_exist = Wishlist::where(['user_id' => $user->id, 'car_id' => $id])->count();
        if($is_exist == 0){

            $wishlist = new Wishlist();
            $wishlist->car_id = $id;
            $wishlist->user_id = $user->id;
            $wishlist->save();

            $notification = trans('translate.Item added to favourite list');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->back()->with($notification);

        }else{
            $notification = trans('translate.Already added to favourite list');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

    }

    public function wishlists(){

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


        $user = Auth::guard('web')->user();
        $wishlists = Wishlist::where(['user_id' => $user->id])->get();
        $wishlist_arr = array();
        foreach($wishlists as $wishlist){
            $wishlist_arr [] = $wishlist->car_id;
        }
        $one_price_wishlists= DB::table('wishlists as um')
        ->join('auct_lots_xml_jp_op as auct', 'um.car_id', '=', 'auct.id')
        ->where('um.table_id', 1)
        ->where('um.user_id', $user->id)
        ->select('auct.*')->get();
        $one_price_arr=[];
        foreach($one_price_wishlists as $price){
            $car_image=$this->last_image($price->pictures);
            $one_price_arr [] = array(
                'company_name'=>Session::get('front_lang')=='en' ? $price->company_en : $price_company,
                'price'=>Session::get('front_lang')=='en' ? $price->start_price_num : $price->start_price,
                'model_en'=>Session::get('front_lang')=='en'? $price->model_name_en : $price->model_name,
                'mileage'=>Session::get('front_lang')=='en'? $price->mileage_en : $price->mileage,
                'year'=>Session::get('front_lang')=='en'? $price->model_year_en : $price->model_year,
                'engine'=>$price->model_details_en,
                'picture'=>$car_image[0],
                'url' => route('fixed-car-marketplace-details', [$price->id]),
            );
        }


        $auction_wishlists= DB::table('wishlists as um')
        ->join('auct_lots_xml_jp as auct', 'um.car_id', '=', 'auct.id')
        ->where('um.table_id', 2)
        ->where('um.user_id', $user->id)
        ->select('auct.*')->get();
        $auction_arr =[];
        foreach($auction_wishlists as $price){
            $car_image=$this->last_image($price->pictures);
            $auction_arr [] = array(
                'company_name'=>Session::get('front_lang')=='en' ? $price->company_en : $price_company,
                'price'=>Session::get('front_lang')=='en' ? $price->start_price_num : $price->start_price,
                'model_en'=>Session::get('front_lang')=='en'? $price->model_name_en : $price->model_name,
                'mileage'=>Session::get('front_lang')=='en'? $price->mileage_en : $price->mileage,
                'year'=>Session::get('front_lang')=='en'? $price->model_year_en : $price->model_year,
                'engine'=>$price->model_details_en,
                'picture'=>$car_image[0],
                'url' => route('auction_listing', [$price->id]),
            );
        }


        $jdm_wishlists= DB::table('wishlists as um')
        ->join('blog as b', 'um.car_id', '=', 'b.id')
        ->where('um.table_id', 3)
        ->where('um.user_id', $user->id)
        ->select('b.*')->get();

        $blog_arr =[];
        foreach($jdm_wishlists as $price){
            $image = file_exists(public_path('Cars/' . $price->image)) 
            ? 'Cars/' . $price->image 
            : 'uploads/website-images/no-image.jpg';
            $blog_arr [] = array(
                'company_name'=>$price->make,
                'price'=>$price->price,
                'model_en'=>$price->model,
                'mileage'=>$price->kms,
                'year'=>$price->yom,
                'engine'=>'--',
                'picture'=>$image,
                'url' => route('jdm-stock-listing', [$price->id, 'car']),
            );
        }

        $heavy_wishlists= DB::table('wishlists as um')
        ->join('heavy as b', 'um.car_id', '=', 'b.id')
        ->where('um.table_id', 4)
        ->where('um.user_id', $user->id)
        ->select('b.*')->get();

        $heavy_arr =[];
        foreach($heavy_wishlists as $price){
            $image = file_exists(public_path('Cars/' . $price->image)) 
            ? 'Cars/' . $price->image
            : 'uploads/website-images/no-image.jpg';
            $heavy_arr [] = array(
                'company_name'=>$price->make,
                'price'=>$price->price,
                'model_en'=>$price->model,
                'mileage'=>$price->kms,
                'year'=>$price->yom,
                'engine'=>'--',
                'picture'=>$image,
                'url' => route('jdm-stock-listing', [$price->id, 'heavy']),
            );
        }

        $small_wishlists= DB::table('wishlists as um')
        ->join('small_heavy as b', 'um.car_id', '=', 'b.id')
        ->where('um.table_id', 5)
        ->where('um.user_id', $user->id)
        ->select('b.*')->get();

        $small_heavy_arr =[];
        foreach($small_wishlists as $price){
            $image = file_exists(public_path('Cars/' . $price->image)) 
            ? 'Cars/' . $price->image 
            : 'uploads/website-images/no-image.jpg';
            $small_heavy_arr [] = array(
                'company_name'=>$price->make,
                'price'=>$price->price,
                'model_en'=>$price->model,
                'mileage'=>$price->kms,
                'year'=>$price->yom,
                'engine'=>'--',
                'picture'=>$image,
                'url' => route('jdm-stock-listing', [$price->id, 'small_heavy']),
            );
        }
        $combined_arr = array_merge($auction_arr, $blog_arr, $heavy_arr, $small_heavy_arr);

        // echo json_encode($combined_arr);die();
    //    foreach($combined_arr as $arr){
    //     echo json_encode($arr['mileage']);
    //    }
    //    die();



        $cars = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->whereIn('id', $wishlist_arr)->get();


        return view('profile.wishlists', ['cars' => $cars,
        'jdm_legend'=>$jdm_brand,
        'jdm_core_brand'=>$jdm_core_brand,
        'combined_arr'=>$combined_arr
        ]);

    }

    public function last_image($picture){
        $picture_array = explode("#",$picture);
                if(count($picture_array) > 0){
                    $image_array = [];
                    foreach ($picture_array as $picture) {
                        $image_array[] = [
                            'url' => $picture,
                            'number' =>$this->getNumberFromUrl($picture)
                        ];
                    }
                    usort($image_array, function($a, $b) {
                        return $b['number'] - $a['number'];
                    });
                    $sorted_image_array = array_column($image_array, 'url');
                    return $sorted_image_array;
                }    
    }

    public function getNumberFromUrl($url) {
        $parsed_url = parse_url($url);
         // Check if the query key exists
            if (!isset($parsed_url['query'])) {
                return null; // Return null if there's no query string
            }

            // Parse the query string
            parse_str($parsed_url['query'], $query_params);

            // Return the number if it exists, or null otherwise
            return isset($query_params['number']) ? (int)$query_params['number'] : null;
        // parse_str($parsed_url['query'], $query_params);
        // return isset($query_params['number']) ? (int)$query_params['number'] : null;
    }


    public function remove_wishlist($id){

        $user = Auth::guard('web')->user();
        Wishlist::where(['user_id' => $user->id, 'car_id' => $id])->delete();

        $notification = trans('translate.Item remove to favourite list');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
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



            
        // DB::enableQueryLog();
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
        $vehicle_enquiry = $query->orderBy('id', 'desc')
                           ->where('user_id',Auth::user()->id)->get();

        $jdm_legend=$this->jdm_brands();
        // echo json_encode($jdm_brand);die();

        // $vehicle_enquiry = VehicleEnquiry::orderBy('id','desc')->latest()->get();
        return view('user-enquiry', compact('vehicle_enquiry','filters','brands',
        'jdm_legend'));

    }
    function jdm_brands(){
        $jdm_legend = DB::table('brands as b')
        ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
        ->join('blog as blog', DB::raw('LOWER(blog.make)'), '=', DB::raw('LOWER(b.slug)')) // Ensure blog.make matches b.slug
        ->join('models_cars as mc', function ($join) {
            $join->on('blog.model', '=', 'mc.model')
                ->on('blog.category', '=', 'mc.category'); // Match model and category
        })
        ->where('blog.is_active','1')
        ->where('bt.lang_code', Session::get('front_lang')) // Filter by language code
        ->whereRaw('REGEXP_REPLACE(blog.price, "[,\\s]", "") REGEXP "^[0-9]+$"')
        ->select('b.slug', 'bt.name as brand_name') // Select slug and name
        ->distinct('b.slug') // Ensure distinct slugs
        ->get()
        ->map(function($item) {
            return [
                'slug' => $item->slug,
                'brand_name' => $item->brand_name
            ];
        })
        ->toArray();
        $jdm_legend_heavy = DB::table('brands as b')
        ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
        ->join('heavy as blog', DB::raw('LOWER(blog.make)'), '=', DB::raw('LOWER(b.slug)')) // Ensure blog.make matches b.slug
        ->join('models_cars as mc', function ($join) {
            $join->on('blog.model', '=', 'mc.model')
                ->on('blog.category', '=', 'mc.category'); // Match model and category
        })
        ->where('blog.is_active','1')
        ->where('bt.lang_code', Session::get('front_lang')) // Filter by language code
        ->whereRaw('REGEXP_REPLACE(blog.price, "[,\\s]", "") REGEXP "^[0-9]+$"')
        ->select('b.slug', 'bt.name as brand_name') // Select slug and name
        ->distinct('b.slug') // Ensure distinct slugs
        ->get()
        ->map(function($item) {
            return [
                'slug' => $item->slug,
                'brand_name' => $item->brand_name
            ];
        })
        ->toArray();

        $jdm_legend_small_heavy = DB::table('brands as b')
        ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
        ->join('small_heavy as blog', DB::raw('LOWER(blog.make)'), '=', DB::raw('LOWER(b.slug)')) // Ensure blog.make matches b.slug
        ->join('models_cars as mc', function ($join) {
            $join->on('blog.model', '=', 'mc.model')
                ->on('blog.category', '=', 'mc.category'); // Match model and category
        })
        ->where('blog.is_active','1')
        ->where('bt.lang_code', Session::get('front_lang')) // Filter by language code
        ->whereRaw('REGEXP_REPLACE(blog.price, "[,\\s]", "") REGEXP "^[0-9]+$"')
        ->select('b.slug', 'bt.name as brand_name') // Select slug and name
        ->distinct('b.slug') // Ensure distinct slugs
        ->get()
        ->map(function($item) {
            return [
                'slug' => $item->slug,
                'brand_name' => $item->brand_name
            ];
        })
        ->toArray();
        $jdm_brand['car']=$jdm_legend;
        $jdm_brand['heavy']=$jdm_legend_heavy;
        $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
        return $jdm_brand;    
      
    }


}
