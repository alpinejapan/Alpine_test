<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Modules\Page\Entities\HomePage;
use Modules\Brand\Entities\Brand;
use Modules\Testimonial\Entities\Testimonial;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Modules\Blog\Entities\BlogComment;
use Modules\Imports\Entities\AuctLotsXmlJpOpOtherChargers;
use Modules\Car\Entities\Car;
use Modules\Cars\Entities\Cars;
use Modules\Car\Entities\CarGallery;
use Modules\Feature\Entities\Feature;
use Modules\GeneralSetting\Entities\SeoSetting;
use Modules\Page\Entities\CustomPage;
use Modules\Page\Entities\AboutUs;
use Modules\Page\Entities\ContactUs;
use Modules\GeneralSetting\Entities\Setting;
use Modules\GeneralSetting\Entities\EmailTemplate;
use Modules\Page\Entities\TermAndCondition;
use Modules\Page\Entities\PrivacyPolicy;
use Modules\Page\Entities\Faq;
use Modules\Page\Entities\howtobuy;
use Modules\City\Entities\City;
use Modules\Language\Entities\Language;
use App\Models\User;
use App\Models\AdsBanner;
use App\Models\Review;  
use App\Models\VehicleEnquiry;
use App\Models\Auct_lots_xml_jp;
use Modules\ContactMessage\Http\Requests\ContactMessageRequest;
use Modules\ContactMessage\Emails\SendContactMessage;
use DB;

use Modules\Subscription\Entities\SubscriptionPlan;
use Modules\Currency\app\Models\MultiCurrency;
use Modules\Imports\Entities\CarDataJpOp;
use Modules\DeliveryCharges\Entities\DeliveryCharge;
use Modules\Models\Entities\ModelsCars;
use Modules\Heavy\Entities\Heavy;
use Modules\SmallHeavy\Entities\SmallHeavy;
use Cache;
use Carbon\Carbon;  
use App\Models\Wishlist;

use App\Models\StripePayment;
use App\Models\PaypalPayment;
use App\Models\RazorpayPayment;
use App\Models\Flutterwave;
use App\Models\PaystackAndMollie;
use App\Models\InstamojoPayment;
use App\Models\BankPayment;
use App\Http\Requests\PricingRequest;


use App\Helpers\MailHelper;

use Str, Mail, Hash, Auth, Session,Config,Artisan;

use App\Rules\Captcha;
use App\Models\JdmStockBlogOtherCharges;
use App\Models\JdmStockHeavyOtherCharges;

class HomeController extends Controller
{

    public function __construct(){
        parent::__construct();
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


    public function index(Request $request){      
        $setting = Setting::select('selected_theme')->first();
        Session::put('selected_theme', 'theme_three');
        $seo_setting = SeoSetting::where('id', 1)->first();

        $homepage = HomePage::with('front_translate')->first();

        $brands = Brand::where('status', 'enable')->get();
        $top_cars=[];

        

        // $top_sells=CarDataJpOp::where('top_sell','1')->orderBy('id','desc')->get()->take(12);
        // $top_sells = CarDataJpOp::query()
        //     ->select('auct_lots_xml_jp_op.*')
        //     ->where('auct_lots_xml_jp_op.top_sell', '1')
        //     ->where('auct_lots_xml_jp_op.active_status', '1')
        //     ->orderBy('auct_lots_xml_jp_op.id', 'desc')
        //     ->get()->take(12);
        $top_sells = CarDataJpOp::Join('auct_lots_xml_jp_op_other_chargers as oc','oc.auct_id','=','auct_lots_xml_jp_op.id')
            ->select('auct_lots_xml_jp_op.*')
            ->where('oc.top_sell', '1')
            // ->where('auct_lots_xml_jp_op.active_status', '1')
            ->orderBy('auct_lots_xml_jp_op.id', 'desc')
            ->get()->take(12);
       
        $used_cars = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['condition' => 'Used', 'status' => 'enable', 'approved_by_admin' => 'approved'])->get()->take(8);

        $new_cars = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['condition' => 'New', 'status' => 'enable', 'approved_by_admin' => 'approved'])->get()->take(8);


        $testimonials = Testimonial::where('status', 'active')->orderBy('id','desc')->get();

        $blogs = Blog::where('status', 1)->orderBy('id','desc')->get()->take(4);

        $home1_ads = AdsBanner::where('position_key', 'home1_featured_car_sidebar')->first();
        $home2_ads = AdsBanner::where('position_key', 'home2_brand_sidebar')->first();
        $home3_ads = AdsBanner::where('position_key', 'home_new_arrivals')->first();

        $jdm_car_listings = DB::table('brands as b')
        ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
        ->join('blog as blog', DB::raw('LOWER(blog.make)'), '=', DB::raw('LOWER(b.slug)'))
        ->where('blog.is_active','1')
        ->where('bt.lang_code', Session::get('front_lang')) // Filter by language code
        ->whereRaw('REGEXP_REPLACE(blog.price, "[,\\s]", "") REGEXP "^[0-9]+$"')
        ->select('blog.*')
        ->orderBy('id','desc')->take(8)->get();


        $brands = Brand::where('status', 'enable')->get();

        $cities = City::with('translate')->get();

        $selected_theme = Session::get('selected_theme');

        
            foreach($top_sells as $cars){
                  $car_image=$this->last_image($cars->pictures);
                  $imageUrl='uploads/website-images/no-image.jpg';
                  if(count($car_image)> 0){
                      if ($this->isImageAvailable($car_image[0])) {
                          $imageUrl= $car_image[0];
                      } else {
                          $imageUrl='uploads/website-images/no-image.jpg';
                      }
                  }
                    $top_cars[]=array(
                        'company_en'=>$cars->company_en,
                        'company'=>$cars->company,
                        'model_name'=>$cars->model_name,
                        'model_name_en'=>$cars->model_name_en,
                        'start_price'=>$cars->start_price,
                        'start_price_num'=>$this->convertCurrency($cars->start_price_num, $this->usdRate),
                        'end_price'=>$cars->end_price,
                        'end_price_num'=>$this->convertCurrency($cars->end_price_num, $this->usdRate),
                        'picture'=>$imageUrl,
                        'id'=>$cars->id,
                        'mileage'=>$cars->mileage,
                        'mileage_en'=>$cars->mileage_en,
                        'year'=>$cars->model_year,
                        'year_en'=>$cars->model_year_en,
                        'transmission'=>$cars->transmission,
                        'transmission_en'=>$cars->transmission_en,
                        'model_details'=>$cars->model_details,
                        'model_details_en'=>$cars->model_details_en,
                        'parsed_data'=>$cars->parsed_data_en,
                        'datetime'=>$cars->datetime,
                    );
                // }
           
            }
            $current_year=Date('Y');
            // $new_arrivals = CarDataJpOp::query()
            // ->select('auct_lots_xml_jp_op.*')
            // ->where('auct_lots_xml_jp_op.new_arrival', '1')
            // ->where('auct_lots_xml_jp_op.active_status', '1')
            // ->orderBy('auct_lots_xml_jp_op.id', 'desc')
            // ->get()->take(5);
            $new_arrivals = CarDataJpOp::Join('auct_lots_xml_jp_op_other_chargers as oc','oc.auct_id','=','auct_lots_xml_jp_op.id')
            ->select('auct_lots_xml_jp_op.*','oc.shipping_value','oc.commission_value')
            ->where('oc.new_arrival', '1')
            // ->where('auct_lots_xml_jp_op.active_status', '1')
            ->orderBy('auct_lots_xml_jp_op.id', 'desc')
            ->get()->take(5);

            // echo json_encode($new_arrivals);die();

            $new_arrived_cars=[];
         
            foreach($new_arrivals as $cars){
                $car_image=$this->last_image($cars->pictures);
                $imageUrl='uploads/website-images/no-image.jpg';
                if(count($car_image)> 0){
                    if ($this->isImageAvailable($car_image[0])) {
                        $imageUrl= $car_image[0];
                    } else {
                        $imageUrl='uploads/website-images/no-image.jpg';
                    }
                }
                  $new_arrived_cars[]=array(
                      'company_en'=>$cars->company_en,
                      'company'=>$cars->company,
                      'model_name'=>$cars->model_name,
                      'model_name_en'=>$cars->model_name_en,
                      'start_price'=>$cars->start_price,
                      'start_price_num'=>$this->convertCurrency($cars->start_price_num, $this->usdRate),
                      'end_price'=>$cars->end_price,
                      'end_price_num'=>$this->convertCurrency($cars->end_price_num, $this->usdRate),
                      'picture'=>$imageUrl,
                      'id'=>$cars->id,  
                      'mileage'=>$cars->mileage,
                      'mileage_en'=>$cars->mileage_en,
                      'year'=>$cars->year,
                      'year_en'=>$cars->model_year_en,
                      'transmission'=>$cars->transmission,
                      'transmission_en'=>$cars->transmission_en,
                      'parsed_data'=>$cars->parsed_data_en,
                      'datetime'=>$cars->datetime,
                  );    
          }



            // $jdm_core_brand = Brand::where('status', 'enable')->get();
            $jdm_core_brand = CarDataJpOp::select(
                'company_en',
                \DB::raw('COUNT(*) as company_record'),
                \DB::raw('LOWER(company_en) as slug')
            )
            ->groupBy('company_en')
            ->having('company_record', '>=', 1)
            ->get();
           

            $userId = $userId ?? auth()->id();
            $one_price_wishlists= DB::table('wishlists as um')
            ->join('auct_lots_xml_jp_op as auct', 'um.car_id', '=', 'auct.id')
            ->where('um.table_id', 1)
            ->where('um.user_id', $userId)
            ->pluck('auct.id')->toArray();
            

            $jdm_wishlists= DB::table('wishlists as um')
                ->join('blog as b', 'um.car_id', '=', 'b.id')
                ->where('um.table_id', 3)
                ->where('um.user_id', $userId)
                ->pluck('b.id')->toArray();


           

        
            return view('index4', [
                'one_price_wishlists'=>$one_price_wishlists,
                'jdm_wishlists'=>$jdm_wishlists,
                'seo_setting' => $seo_setting,
                'homepage' => $homepage,
                'brands' => $brands,
                'cities' => $cities,
                'new_cars' => $new_cars,
                // 'jdm_legend'=>$jdm_brand,
                'jdm_core_brand'=>$jdm_core_brand,
                'used_cars' => $used_cars,
                'testimonials' => $testimonials,
                'blogs' => $blogs,
                'home1_ads' => $home1_ads,
                'home2_ads' => $home2_ads,
                'home3_ads' => $home3_ads,
                'top_sells'=>$top_cars,
                'top_rated'=>$top_sells,
                'new_arrived_cars'=>$new_arrived_cars,
                'jdm_car_listings'=>$jdm_car_listings
            ]);
    }
    public function home_page_responsive(Request $request){       
        Artisan::call('optimize:clear');
        $setting = Setting::select('selected_theme')->first();
       
          Session::put('selected_theme', 'theme_three');

        $seo_setting = SeoSetting::where('id', 1)->first();

        $homepage = HomePage::with('front_translate')->first();

        $brands = Brand::where('status', 'enable')->get();

        $top_sells=CarDataJpOp::where('top_sell','1')->orderBy('id','desc')->get()->take(8);

       
 

        $used_cars = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['condition' => 'Used', 'status' => 'enable', 'approved_by_admin' => 'approved'])->get()->take(8);

        $new_cars = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['condition' => 'New', 'status' => 'enable', 'approved_by_admin' => 'approved'])->get()->take(8);

        $featured_cars = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['is_featured' => 'enable', 'status' => 'enable', 'approved_by_admin' => 'approved'])->get()->take(6);

        $testimonials = Testimonial::where('status', 'active')->orderBy('id','desc')->get();

        $blogs = Blog::where('status', 1)->orderBy('id','desc')->get()->take(4);

        $dealers = User::where(['status' => 'enable' , 'is_banned' => 'no', 'is_dealer' => 1])->where('email_verified_at', '!=', null)->orderBy('id','desc')->select('id','name','username','designation','image','status','is_banned','is_dealer', 'address', 'email', 'phone')->paginate(12);

        $subscription_plans = SubscriptionPlan::orderBy('serial', 'asc')->where('status', 'active')->get();

        $home1_ads = AdsBanner::where('position_key', 'home1_featured_car_sidebar')->first();
        $home2_ads = AdsBanner::where('position_key', 'home2_brand_sidebar')->first();
        $home3_ads = AdsBanner::where('position_key', 'home_new_arrivals')->first();
        $jdm_car_listings = Cars::where('is_active', '1')->orderBy('id','desc')->take(8)->get();

        $brands = Brand::where('status', 'enable')->get();

        $cities = City::with('translate')->get();

        $selected_theme = Session::get('selected_theme');

        if ($selected_theme == 'theme_one'){
        
            return view('index', [
                'seo_setting' => $seo_setting,
                'homepage' => $homepage,
                'brands' => $brands,
                'cities' => $cities,
                'new_cars' => $new_cars,
                'used_cars' => $used_cars,
                'featured_cars' => $featured_cars,
                'dealers' => $dealers,
                'testimonials' => $testimonials,
                'blogs' => $blogs,
                'subscription_plans' => $subscription_plans,
                'home1_ads' => $home1_ads,
                'home2_ads' => $home2_ads,
                'home3_ads' => $home3_ads,

            ]);
        }elseif($selected_theme == 'theme_two'){
          
            return view('index2', [
                'seo_setting' => $seo_setting,
                'homepage' => $homepage,
                'brands' => $brands,
                'cities' => $cities,
                'new_cars' => $new_cars,
                'used_cars' => $used_cars,
                'featured_cars' => $featured_cars,
                'dealers' => $dealers,
                'testimonials' => $testimonials,
                'blogs' => $blogs,
                'subscription_plans' => $subscription_plans,
                'home1_ads' => $home1_ads,
                'home2_ads' => $home2_ads,
                'home3_ads' => $home3_ads,
            ]);
        }elseif($selected_theme == 'theme_three'){
            foreach($top_sells as $cars){
                  $last_image=$this->last_image($cars->pictures);
                    $top_cars[]=array(
                        'company_en'=>$cars->company_en,
                        'company'=>$cars->company,
                        'model_name'=>$cars->model_name,
                        'model_name_en'=>$cars->model_name_en,
                        'start_price'=>$cars->start_price,
                        'start_price_num'=>$cars->start_price_num,
                        'end_price'=>$cars->end_price,
                        'end_price_num'=>$cars->end_price_num,
                        'picture'=>$last_image[0],
                        'id'=>$cars->id,
                        'mileage'=>$cars->mileage,
                        'mileage_en'=>$cars->mileage_en,
                    );
                // }
           
            }
            $current_year=Date('Y');
            $new_arrivals=CarDataJpOp::where('model_year_en',$current_year)->
            orderBy('id','desc')->get()->take(5);
    
            foreach($new_arrivals as $cars){
                $last_image=$this->last_image($cars->pictures);
                  $new_arrived_cars[]=array(
                      'company_en'=>$cars->company_en,
                      'company'=>$cars->company,
                      'model_name'=>$cars->model_name,
                      'model_name_en'=>$cars->model_name_en,
                      'start_price'=>$cars->start_price,
                      'start_price_num'=>$cars->start_price_num,
                      'end_price'=>$cars->end_price,
                      'end_price_num'=>$cars->end_price_num,
                      'picture'=>$last_image[0],
                      'id'=>$cars->id,
                      'mileage'=>$cars->mileage,
                      'mileage_en'=>$cars->mileage_en,
                      'year'=>$cars->yom,
                      'transmission'=>$cars->transmission,
                  );    
          }

            // $jdm_legend = \DB::table('blog')
            // ->where('category', 'JDM Legend')
            // ->where('make', '!=', '')
            // ->whereNotNull('make')
            // ->distinct()
            // ->pluck('make');

            $jdm_core_brand = Brand::where('status', 'enable')->get();

            // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
            //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
            //              ->where('bt.lang_code',Session::get('front_lang'))
            // ->select('b.slug','bt.name as brand_name')
            // ->distinct('b.slug')->get();


            // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
            // ->join('brand_translations as bt','bt.brand_id','=','b.id')
            // ->where('bt.lang_code',Session::get('front_lang'))
            // ->select('b.slug','bt.name as brand_name')
            // ->distinct('b.slug')->get();

            // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
            // ->join('brand_translations as bt','bt.brand_id','=','b.id')
            // ->where('bt.lang_code',Session::get('front_lang'))
            // ->select('b.slug','bt.name as brand_name')
            // ->distinct('b.slug')->get();


            // $jdm_brand['car']=$jdm_legend;
            // $jdm_brand['heavy']=$jdm_legend_heavy;
            // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
                 
            // $jdm_brand=$this->jdm_brands();
          
        
            return view('index4', [
                'seo_setting' => $seo_setting,
                'homepage' => $homepage,
                'brands' => $brands,
                'cities' => $cities,
                'new_cars' => $new_cars,
                // 'jdm_legend'=>$jdm_brand,
                'jdm_core_brand'=>$jdm_core_brand,
                // 'jdm_legend_heavy'=>$jdm_legend_heavy,
                // 'jdm_legend_small_heavy'=>$jdm_legend_small_heavy,
                'used_cars' => $used_cars,
                'featured_cars' => $featured_cars,
                'dealers' => $dealers,
                'testimonials' => $testimonials,
                'blogs' => $blogs,
                'subscription_plans' => $subscription_plans,
                'home1_ads' => $home1_ads,
                'home2_ads' => $home2_ads,
                'home3_ads' => $home3_ads,
                'top_sells'=>$top_cars,
                'top_rated'=>$top_sells,
                'new_arrived_cars'=>$new_arrived_cars,
                'jdm_car_listings'=>$jdm_car_listings
            ]);
        }else{
            return view('index', [
                'seo_setting' => $seo_setting,
                'homepage' => $homepage,
                'brands' => $brands,
                'cities' => $cities,
                'new_cars' => $new_cars,
                'used_cars' => $used_cars,
                'featured_cars' => $featured_cars,
                'dealers' => $dealers,
                'testimonials' => $testimonials,
                'blogs' => $blogs,
                'subscription_plans' => $subscription_plans,
                'home1_ads' => $home1_ads,
                'home2_ads' => $home2_ads,
                'home3_ads' => $home3_ads,
            ]);
        }

    }




    public function jdm_stock(Request $request,$slug,$type){
        $jdm_legend = Brand::where('status', 'enable')->get();
        $seo_setting = SeoSetting::where('id', 1)->first();
        $brands=\DB::table('blog')
        ->where('make', $slug)
        ->distinct()
        ->select('model')->get();

        // DB::enableQueryLog();
        if($type == 'car'){
            $carsQuery = Cars::join('models_cars as mc', 'mc.model', '=', 'blog.model')
            ->where(DB::raw('LOWER(blog.make)'), $slug)
            ->where('is_active','1')
            ->select('blog.*');
            
        } else if($type == 'heavy'){
            $carsQuery = Heavy::join('models_cars as mc', 'mc.model', '=', 'heavy.model')
            // ->where('heavy.category', 'JDM Legend')
            ->where('heavy.make', $slug)
            ->where('is_active','1')
            ->select('heavy.*');
        } else if($type =='small_heavy') {
            $carsQuery = SmallHeavy::join('models_cars as mc', 'mc.model', '=', 'small_heavy.model')
            // ->where('small_heavy.category', 'JDM Legend')
            ->where('small_heavy.make', $slug)
            ->where('is_active','1')
            ->select('small_heavy.*');
        }

        if($request->search){
            if($request->search !=""){
                if($type == 'car'){
                    $carsQuery->where(function ($query) use ($request) {
                        $query->where('blog.title', 'LIKE', $request->search . '%')
                              ->orWhere('blog.make', 'LIKE', $request->search . '%')  // Replace 'column2' with the actual column name
                              ->orWhere('blog.model', 'LIKE', $request->search . '%');  // Replace 'column3' with the actual column name
                    }); 
                }  else if($type == 'heavy'){
                    $carsQuery->where(function ($query) use ($request) {
                        $query->where('heavy.title', 'LIKE', $request->search . '%')
                              ->orWhere('heavy.make', 'LIKE', $request->search . '%')  // Replace 'column2' with the actual column name
                              ->orWhere('heavy.model', 'LIKE', $request->search . '%');  // Replace 'column3' with the actual column name
                    }); 
                }  else if($type =='small_heavy') {
                    $carsQuery->where(function ($query) use ($request) {
                        $query->where('small_heavy.title', 'LIKE', $request->search . '%')
                              ->orWhere('small_heavy.make', 'LIKE', $request->search . '%')  // Replace 'column2' with the actual column name
                              ->orWhere('small_heavy.model', 'LIKE', $request->search . '%');  // Replace 'column3' with the actual column name
                    }); 
                }     
            }
        }

        if($request->price_range_scale){
            if($request->price_range_scale !=""){  
                $parts = explode('-', $request->price_range_scale);
                $startValue = trim($parts[0]);
                $endValue = trim($parts[1]);
                if($type== 'car'){
                    $carsQuery = $carsQuery->where(function ($q) use ($startValue,$endValue) {
                        $q->whereBetween('blog.price', [$startValue, $endValue]);
                    });
                  }  else if($type == 'heavy'){
                    $carsQuery = $carsQuery->where(function ($q) use ($startValue,$endValue) {
                        $q->whereBetween('heavy.price', [$startValue, $endValue]);
                    });
                  } else if($type =='small_heavy'){
                    $carsQuery = $carsQuery->where(function ($q) use ($startValue,$endValue) {
                        $q->whereBetween('small_heavy.price', [$startValue, $endValue]);
                    }); 
                  }
             
            }
        }
        if($request->year){
            if($request->year !="")
            {
                if($type== 'car'){
                    $carsQuery->where('blog.yom', 'like', '%' . $request->year . '%'); 
                  }  else if($type == 'heavy'){
                    $carsQuery->where('heavy.yom', 'like', '%' . $request->year . '%'); 
                  } else if($type =='small_heavy'){
                    $carsQuery->where('small_heavy.yom', 'like', '%' . $request->year . '%'); 
                  }
            }
        }
        

      
      
    
        // Apply filters based on request parameters
        if ($request->brand) {
            $brand_arr = array_filter($request->brand); // Filter out any empty values
            if ($brand_arr) {
                if($type== 'car'){
                  $carsQuery->whereIn('blog.model', $brand_arr); 
                }  else if($type == 'heavy'){
                    $carsQuery->whereIn('heavy.model', $brand_arr);
                } else if($type =='small_heavy'){
                    $carsQuery->whereIn('small_heavy.model', $brand_arr);
                }
            }    
        }

        if($request->brand_new_cars){
            $year = date('Y'); 
            if($type == 'car'){
             $carsQuery->where('blog.year_of_reg', 'LIKE', $year . '%');    
            } else if($type == 'heavy'){
             $carsQuery->where('heavy.year_of_reg', 'LIKE', $year . '%');
            } else if($type =='small_heavy'){
                $carsQuery->where('small_heavy.year_of_reg', 'LIKE', $year . '%');
            }
        }

        if ($request->sort_by) {
            switch ($request->sort_by) {
                case 'price_low_high':
                    if($type == 'car'){
                        $carsQuery->orderBy('blog.price', 'asc');    
                       } else if($type == 'heavy'){
                        $carsQuery->orderBy('heavy.price', 'asc');
                       } else if($type =='small_heavy'){
                           $carsQuery->orderBy('small_heavy.price', 'asc');
                       }
                    break;
                case 'price_high_low':
                   if($type == 'car'){
                        $carsQuery->orderBy('blog.price', 'desc');    
                       } else if($type == 'heavy'){
                        $carsQuery->orderBy('heavy.price', 'desc');
                       } else if($type =='small_heavy'){
                           $carsQuery->orderBy('small_heavy.price', 'desc');
                       }
                    break;
                case 'recent':  
                    if($type == 'car'){   
                        $recentCarIds = $carsQuery->orderBy('blog.id', 'desc')
                        ->limit(100)
                        ->pluck('id');
                        $carsQuery = $carsQuery->whereIn('blog.id', $recentCarIds);
                       } else if($type == 'heavy'){
                            $recentCarIds = $carsQuery->orderBy('heavy.id', 'desc')
                            ->limit(100)
                            ->pluck('id');
                            $carsQuery = $carsQuery->whereIn('heavy.id', $recentCarIds);
                       } else if($type =='small_heavy'){
                            $recentCarIds = $carsQuery->orderBy('small_heavy.id', 'desc')
                            ->limit(100)
                            ->pluck('id');
                            $carsQuery = $carsQuery->whereIn('small_heavy.id', $recentCarIds);
                       }
                    break;
            }
        }

        if($request->price_range){
            $priceRanges = [
                "Under $5000" => ["start" => 0, "end" => 5000],
                "$5000 - $50000" => ["start" => 5000, "end" => 50000],
                "$50000 - $100000" => ["start" => 50000, "end" => 100000],
                "$100000 - $200000" => ["start" => 100000, "end" => 200000],
                "$200000 - $300000" => ["start" => 200000, "end" => 300000],
                "Above $300000" => ["start" => 300000, "end" => null] // Use PHP_INT_MAX for "Above"
            ];
    
            $result = $this->getPriceRangestart($request->price_range, $priceRanges);
          
    
      
            if ($result['start_price_num'] === null) {
                $carsQuery = $carsQuery->whereBetween('price', [$result['start_price_num'], $result['end_price_num']]);

            } else {
                // Count for other ranges
                $carsQuery = $carsQuery->where(function ($q) use ($result) {
                    $q->whereBetween('price', [$result['start_price_num'], $result['end_price_num']]);
                });
            }  
        }
        
        // Pagination
        $cars = $carsQuery->paginate(12);
        // Transform cars into an array for the view
        $cars_array = $cars->map(function ($car) {
        // $car_image=$this->last_image($car->pictures);
            return [
                'model_name' => $car->model,
                'start_price' => $car->price,
                'picture' =>$car->image,
                'id' => $car->id,
                'make'=>$car->make
            ];
        });

     
    
    
        // Get additional data
        $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();

        $brand_count = CarDataJpOp::selectRaw('company_en,company,COUNT(*) as count')
            ->groupBy('company_en')
            ->having('count', '>', 1)
            ->get();
    
      
            $jdm_core_brand = Brand::where('status', 'enable')->get();

            // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
            //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
            //              ->where('bt.lang_code',Session::get('front_lang'))
            // ->select('b.slug','bt.name as brand_name')
            // ->distinct('b.slug')->get();


            // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
            // ->join('brand_translations as bt','bt.brand_id','=','b.id')
            // ->where('bt.lang_code',Session::get('front_lang'))
            // ->select('b.slug','bt.name as brand_name')
            // ->distinct('b.slug')->get();

            // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
            // ->join('brand_translations as bt','bt.brand_id','=','b.id')
            // ->where('bt.lang_code',Session::get('front_lang'))
            // ->select('b.slug','bt.name as brand_name')
            // ->distinct('b.slug')->get();
            // $jdm_core_brand = Brand::where('status', 'enable')->get();

            // $jdm_brand['car']=$jdm_legend;
            // $jdm_brand['heavy']=$jdm_legend_heavy;
            // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
            // $jdm_brand=$this->jdm_brands();
            $price_range = $this->getPriceRange();
            $transmission = CarDataJpOp::selectRaw('transmission_en, COUNT(*) as count')
            ->groupBy('transmission_en')
            ->having('count', '>', 1)
            ->get();
            $scores = CarDataJpOp::selectRaw('scores_en, COUNT(*) as count')
            ->groupBy('scores_en')
            ->having('count', '>', 1)
            ->get();

          
    

        return view('jdm_stock', [
            'seo_setting' => $seo_setting,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand,
            'brands' => $brands,
            'cars_array' => $cars_array,
            'listing_ads' => $listing_ads,
            'cars' => $cars,
            'brand_count' => $brand_count,
            'slug'=>$slug,
            'type'=>$type,
            'price_range' => $price_range,
            'transmission'=>$transmission,
            'scores'=>$scores
            // 'jdm_legend_heavy'=>$jdm_legend_heavy,
            // 'jdm_legend_small_heavy'=>$jdm_legend_small_heavy
        ]);
    }

    private function getJdmSpecificRecord($type){
        $brands = DB::table('brands as b')
        ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
        ->where('bt.lang_code', Session::get('front_lang'))
        ->select('b.slug', 'bt.name')
        ->get();

        

        $result = [];

        if($type == 'car'){
            $tableName="blog";   
        } else if($type == 'heavy'){
            $tableName="heavy";
        } else if($type =='small_heavy') {
            $tableName="small_heavy";
        }   
        // DB::enableQueryLog();

        DB::table($tableName)
        ->join('models_cars as mc', "{$tableName}.model", '=', 'mc.model')
        ->select(
            DB::raw('LOWER(make) as slug'),
            "{$tableName}.model", DB::raw('COUNT(*) as model_count')
        )
        ->where(DB::raw('LOWER(make)'), $brands->pluck('slug'))
        ->groupBy(DB::raw('LOWER(make)'), 'model')
        // ->whereNotNull("{$tableName}.model")
        // ->whereNotNull("{$tableName}.make")
        ->distinct()
        ->orderBy('make')
        ->chunk(1000, function($models) use (&$result) {
            foreach ($models as $model) {
                // Normalize case in PHP
                $normalizedName = ucwords(strtolower($model->model));
                // $result[$model->slug][$normalizedName] = true;
                $result[$model->slug][$normalizedName] =  $model->model_count;
            }
        });

        // dd(DB::getQueryLog());
        // return collect($result)->map(function($models) {
        //     return collect(array_keys($models))->sort()->values();
        // })->all();
        return collect($result)->map(function ($models) {
            return collect($models)->map(function ($count, $model) {
                return ['model' => $model, 'count' => $count];
            })->sortBy('model')->values();
        })->all();
    }

    private function getJdmSpecificJDMRecord($type,$slug){
        $brands = DB::table('brands as b')
        ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
        ->where('bt.lang_code', Session::get('front_lang'))
        ->select('b.slug', 'bt.name')
        ->get();

        $result = [];

        if($type == 'car'){
            $tableName="blog";   
            $model=Cars::class;
        } else if($type == 'heavy'){
            $model=Heavy::class;
            $tableName="heavy";
        } else if($type =='small_heavy') {
            $tableName="small_heavy";
        }   
        $results =$model::
            whereRaw('LOWER(' . $tableName . '.make) = ?', [$slug])
            ->where($tableName . '.is_active', 1)
            // ->whereNull($tableName . '.deleted_at')
            ->whereRaw('REGEXP_REPLACE(' . $tableName . '.price, "[,\\s]", "") REGEXP "^[0-9]+$"')
            ->select(DB::raw('TRIM(' . $tableName . '.model) as model'), \DB::raw('COUNT(*) as count'))
            ->groupBy($tableName . '.model')
            ->orderByDesc('count')
            ->get();
        return $results;
    
       
    }

    private function getJdmSpecificJDMRecordNew(){
        $brands = DB::table('brands as b')
        ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
        ->where('bt.lang_code', Session::get('front_lang'))
        ->select('b.slug', 'bt.name')
        ->get();



      
        

        $result = [];

            $tableName="blog";   
          
        
        $brandSlugs = $brands->pluck('slug')->map(fn($slug) => strtolower($slug))->toArray();

        // echo json_encode($brandSlugs);die();
        // Db::enableQueryLog();
        DB::table($tableName)
            ->join('models_cars as mc', "{$tableName}.model", '=', 'mc.model')
            ->select(
                DB::raw('LOWER(make) as slug'),
                "{$tableName}.model",
                DB::raw('COUNT(*) as model_count')
            )
            ->whereIn(DB::raw('LOWER(make)'), $brandSlugs) // Use dynamic brand slugs here
            ->groupBy(DB::raw('LOWER(make)'), "{$tableName}.model")
            ->distinct()
            ->whereNotNull(DB::raw("REGEXP_SUBSTR(yom, '[0-9]{4}')"))
                      ->whereBetween(
                          DB::raw("REGEXP_SUBSTR(yom, '[0-9]{4}')"),
                          [now()->subYear()->year, now()->year]
            )
            ->orderBy('make')
            ->chunk(1000, function ($models) use (&$result) {
                foreach ($models as $model) {
                    // Normalize the model name for consistency
                    $normalizedName = ucwords(strtolower($model->model));
                    $result[$model->slug][$normalizedName] = $model->model_count;
                }
            });
            return collect($result)->map(function ($models) {
                return collect($models)->map(function ($count, $model) {
                    return ['model' => $model, 'count' => $count];
                })->sortBy('model')->values();
            })->all();
        
            // echo json_encode($results);die();
        // dd(DB::getQueryLog());    

    
    // Sum up all the model counts to get the total

        // return $results;
    
        
    }

    public function getJdmBrands($type,$slug){
        if($type == 'car'){
            // DB::enableQueryLog();
            $brands = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
            ->join('brand_translations as bt','bt.brand_id','=','b.id')
            ->where('bt.lang_code',Session::get('front_lang'))
            ->where('blog.make', $slug)
            ->select('blog.model')
            ->distinct('b.slug')->get();  
            // dd(DB::getQueryLog());
        } else if($type == 'heavy'){
            $brands = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
            ->join('brand_translations as bt','bt.brand_id','=','b.id')
            ->where('bt.lang_code',Session::get('front_lang'))
            ->where('heavy.make', $slug)
            ->select('heavy.model')
            ->distinct('b.slug')->get();
        } else if($type =='small_heavy') {
            $brands = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
            ->join('brand_translations as bt','bt.brand_id','=','b.id')
            ->where('bt.lang_code',Session::get('front_lang'))
            ->where('small_heavy.make',$slug)
            ->select('small_heavy.model')
            ->distinct('b.slug')->get();
        }  else{
            $brands=[];
        }
        return $brands;
    }

    
    public function jdm_stock_responsive(Request $request,$slug,$type){

        // echo json_encode($request->all());die();
        $priceRanges = [
            "Under $5000" => ["start" => 0, "end" => 5000],
            "$5000 - $50000" => ["start" => 5000, "end" => 50000],
            "$50000 - $100000" => ["start" => 50001, "end" => 100000],
            "$100000 - $200000" => ["start" => 100001, "end" => 200000],
            "$200000 - $300000" => ["start" => 200001, "end" => 300000],
            "Above $300000" => ["start" => 300001, "end" => PHP_INT_MAX] // Use PHP_INT_MAX for "Above"
        ];

       
        $jdm_legend = Brand::where('status', 'enable')->get();
        $seo_setting = SeoSetting::where('id', 1)->first();

   
        $brand_label=Brand::where('slug',$slug)->first();
     
          
        $jdm_core_brand = Brand::where('status', 'enable')->get();

        $brands=$this->getJdmBrands($type,$slug);
        $brand_list=$this->getJdmSpecificJDMRecord($type,$slug);
        $brand_total=$brand_list->sum('count');
        $model = $type == 'car' ? Cars::class : Heavy::class;
        $joinTable = ($type == 'car') ? 'blog' : 'heavy'; 
        // DB::enableQueryLog();
        $yearRange = $model
        ::where(DB::raw('LOWER(' . $joinTable . '.make)'), $slug)
        ->where($joinTable . '.is_active', '1')
        ->whereRaw("REGEXP_REPLACE(REGEXP_REPLACE(" . $joinTable . ".price, '[,]', ''), '[\s]', '') REGEXP '^[0-9]+$'")
        ->selectRaw('
                MIN(YEAR(
                    IF(
                        STR_TO_DATE(yom, "%M %Y") IS NOT NULL, 
                        STR_TO_DATE(yom, "%M %Y"), 
                        CONCAT(CAST(yom AS CHAR), "-01-01")
                    )
                )) AS min_year, 
                MAX(YEAR(
                    IF(
                        STR_TO_DATE(yom, "%M %Y") IS NOT NULL, 
                        STR_TO_DATE(yom, "%M %Y"), 
                        CONCAT(CAST(yom AS CHAR), "-01-01")
                    )
                )) AS max_year
            ')
            ->whereNotNull('yom')
            ->first();  

            $priceRange = $model::
            where(DB::raw('LOWER(' . $joinTable . '.make)'), $slug)
            ->where($joinTable . '.is_active', '1')
            ->whereRaw("CAST(REGEXP_REPLACE(REGEXP_REPLACE(" . $joinTable . ".price, '[,]', ''), '[\\s]', '') AS UNSIGNED) REGEXP '^[0-9]+$'")
            ->selectRaw("
                MIN(CAST(REGEXP_REPLACE(REGEXP_REPLACE(" . $joinTable . ".price, '[,]', ''), '[\\s]', '') AS UNSIGNED)) as min_sal,
                MAX(CAST(REGEXP_REPLACE(REGEXP_REPLACE(" . $joinTable . ".price, '[,]', ''), '[\\s]', '') AS UNSIGNED)) as max_sal
            ")
            ->first();
        
        // dd(DB::getQueryLog());
    
    
    
        // echo json_encode($priceRange);die();
    
        // Get min and max years
        $minYear = $yearRange->min_year;
        // $minYear = 1950;

        $maxYear = $yearRange->max_year;
        // $maxYear = Carbon::now()->year;
        $minPrice = $priceRange->min_sal;
        $maxPrice = $priceRange->max_sal;

        $hasPriceRangeScale = false;
        $startValue = $minPrice;
        $endValue = $maxPrice;
        $wishlists=[];
 

        DB::enableQueryLog();

            if($type == 'car'){
        
                if(Auth::guard('web')->check()){
                    $userId = $userId ?? auth()->id();
                    $wishlists=Cars::join('wishlists as w', 'blog.id', '=', 'w.car_id')
                    // $wishlistItems = \App\Models\Wishlist::where('user_id', $userId)
                        ->where('w.table_id', '3')
                        ->where('w.user_id', $userId)
                        ->pluck('w.car_id')
                        ->toArray();
                }
            
                
                // DB::enableQueryLog();
                $carsQuery =Cars::where(DB::raw('LOWER(make)'), $slug)
                ->where('is_active','1')
                ->whereRaw('REGEXP_REPLACE(price, "[,\\s]", "") REGEXP "^[0-9]+$"')
                ->select('blog.*');    

                $filterQuery=$carsQuery;
            } else if($type == 'heavy'){
                if(Auth::guard('web')->check()){
                    $userId = $userId ?? auth()->id();
                    $wishlists=Heavy::join('wishlists as w', 'heavy.id', '=', 'w.car_id')
                    // $wishlistItems = \App\Models\Wishlist::where('user_id', $userId)
                        ->where('w.table_id', '4')
                        ->pluck('w.car_id')
                        ->toArray();
                }
                $carsQuery = Heavy::
                where('heavy.make', $slug)
                ->where('is_active','1')
                ->whereRaw('REGEXP_REPLACE(heavy.price, "[,\\s]", "") REGEXP "^[0-9]+$"')
                ->select('heavy.*');
                  $filterQuery=$carsQuery;
                // dd(DB::getQueryLog()); 
            } 
            
            if($request->search){
                if($request->search !=""){
                    if($type == 'car'){
                        $carsQuery->where(function ($query) use ($request) {
                            $query->where('blog.title', 'LIKE', $request->search . '%')
                                ->orWhere('blog.make', 'LIKE', $request->search . '%')  // Replace 'column2' with the actual column name
                                ->orWhere('blog.model', 'LIKE', $request->search . '%');  // Replace 'column3' with the actual column name
                        }); 
                    }  else if($type == 'heavy'){
                        $carsQuery->where(function ($query) use ($request) {
                            $query->where('heavy.title', 'LIKE', $request->search . '%')
                                ->orWhere('heavy.make', 'LIKE', $request->search . '%')  // Replace 'column2'jdm with the actual column name
                                ->orWhere('heavy.model', 'LIKE', $request->search . '%');  // Replace 'column3' with the actual column name
                        }); 
                    }  else if($type =='small_heavy') {
                        $carsQuery->where(function ($query) use ($request) {
                            $query->where('small_heavy.title', 'LIKE', $request->search . '%')
                                ->orWhere('small_heavy.make', 'LIKE', $request->search . '%')  // Replace 'column2' with the actual column name
                                ->orWhere('small_heavy.model', 'LIKE', $request->search . '%');  // Replace 'column3' with the actual column name
                        }); 
                    }     
                }
            }

            if($request->year){
                // if($request->year !=""){  
                //     $hasPriceRangeScale = true;
                    $parts = explode(',', $request->year);
                    $startYear=trim($parts[0]);
                    $endYear=trim($parts[1]);
    
                //     // $carsQuery = $carsQuery->where(function ($q) use ($startYear,$endYear) {
                //     //     $q->whereBetween('model_year_en', [($startYear), ($endYear)]);
                //     // });
                // }
                if($request->year !="")
                {
                    if($type== 'car'){
                        // $carsQuery->where('blog.yom', 'like', '%' . $request->year . '%'); 
                        $carsQuery = $carsQuery->whereRaw("
                        CAST(REGEXP_SUBSTR(blog.yom, '[0-9]{4}') AS UNSIGNED) BETWEEN ? AND ?", 
                        [$startYear, $endYear]
                    );
                        
                        
                    }  else if($type == 'heavy'){
                        // $carsQuery->where('heavy.yom', 'like', '%' . $request->year . '%'); 
                        $carsQuery = $carsQuery->whereRaw("
                        CAST(REGEXP_SUBSTR(heavy.yom, '[0-9]{4}') AS UNSIGNED) BETWEEN ? AND ?", 
                        [$startYear, $endYear]
                        );
                    } else if($type =='small_heavy'){
                        // $carsQuery->where('small_heavy.yom', 'like', '%' . $request->year . '%'); 
                        $carsQuery = $carsQuery->whereRaw("
                        CAST(REGEXP_SUBSTR(small_heavy.yom, '[0-9]{4}') AS UNSIGNED) BETWEEN ? AND ?", 
                        [$startYear, $endYear]
                        );
                        // $carsQuery->whereRaw("REGEXP_SUBSTR(small_heavy.yom, '[0-9]{4}') = ?", [
                        //     $request->year 
                        // ]);
                    }
                }
            }
            
            if($request->model){
                $model_arr = array_filter($request->model); 
                // echo $request->model;
                // echo json_encode($model_arr);die();
                // Filter out any empty values
                if ($model_arr) {
                    // echo "one";die();
                    if($type== 'car'){
                        $carsQuery->where(function ($query) use ($model_arr) {
                            foreach ($model_arr as $model) {
                                $query->orWhereRaw('TRIM(blog.model) = ?', [$model]);
                            }
                        });
                        // $carsQuery->whereIn('blog.model', $model_arr); 
                    }  else if($type == 'heavy'){
                        $carsQuery->where(function ($query) use ($model_arr) {
                            foreach ($model_arr as $model) {
                                $query->orWhereRaw('TRIM(heavy.model) = ?', [$model]);
                            }
                        });
                        //   $carsQuery->whereIn('heavy.model', $model_arr);
                    } else if($type =='small_heavy'){
                        $carsQuery->where(function ($query) use ($model_arr) {
                            foreach ($model_arr as $model) {
                                $query->orWhereRaw('TRIM(small_heavy.model) = ?', [$model]);
                            }
                        });
                        $carsQuery->whereIn('small_heavy.model', $model_arr);
                    }
                }
            }
            if($request->vertical_filter_model){
                $model_arr = $request->vertical_filter_model; 
                if ($model_arr) {
                    if($type== 'car'){
                        $carsQuery->where(function ($query) use ($model_arr) {
                                $query->WhereRaw('TRIM(blog.model) = ?', $model_arr);
                        });
                        // $carsQuery->whereIn('blog.model', $model_arr); 
                    }  else {
                    (function ($query) use ($model_arr) {
                                $query->WhereRaw('TRIM(heavy.model) = ?', $model_arr);
                        });
                        //   $carsQuery->whereIn('heavy.model', $model_arr);
                    }
                }
            }
            if($request->vertical_transmission){
                $vertical_transmission = $request->vertical_transmission; 
                if ($vertical_transmission) {
                    if($type== 'car'){
                        $carsQuery->Where('transmission', $vertical_transmission);
                        // $carsQuery->whereIn('blog.model', $model_arr); 
                    }  else {
                        $carsQuery->Where('transmission', $vertical_transmission);
                    }
                }
            }

            if($request->vertical_start_year || $request->vertical_end_year){
                $vertical_start=$request->vertical_start_year;
                $vertical_end=$request->vertical_end_year;
                if($type== 'car'){
                    $carsQuery = $carsQuery->whereRaw("
                        CAST(REGEXP_SUBSTR(blog.yom, '[0-9]{4}') AS UNSIGNED) BETWEEN ? AND ?", 
                        [$vertical_start, $vertical_end]
                    );    
                }  else {
                    $carsQuery = $carsQuery->whereRaw("
                        CAST(REGEXP_SUBSTR(heavy.yom, '[0-9]{4}') AS UNSIGNED) BETWEEN ? AND ?", 
                        [$vertical_start, $vertical_end]
                    );
                } 
            }
            if($request->vertical_budget_start || $request->vertical_budget_end){
                $vertical_budget_start=(int)trim(str_replace('"', '', $request->vertical_budget_start));
                $vertical_budget_end=(int)trim(str_replace('"', '', $request->vertical_budget_end));
                if($type=='car')
                {
                    $carsQuery->where(function ($query) use ($request, $vertical_budget_start,$vertical_budget_end) {
                            $query->orWhereRaw("REGEXP_REPLACE(blog.price, '[^0-9]', '') BETWEEN ? AND ?", [
                                $vertical_budget_start,
                                $vertical_budget_end
                            ]);
                    }); 
                } else {
                    $carsQuery->where(function ($query) use ($request, $vertical_budget_start,$vertical_budget_end) {
                            $query->orWhereRaw("REGEXP_REPLACE(heavy.price, '[^0-9]', '') BETWEEN ? AND ?", [
                                $vertical_budget_start,
                                $vertical_budget_end
                            ]);
                    }); 
                }
            }    
            if($request->vertical_mileage_from || $request->vertical_mileage_to){
                $vertical_mileage_from=(int)trim(str_replace('"', '', $request->vertical_mileage_from));
                $vertical_mileage_to=(int)trim(str_replace('"', '', $request->vertical_mileage_to));
                if($type=='car')
                {
                    $carsQuery->whereBetween('blog.kms', [$vertical_mileage_from, $vertical_mileage_to]);
                } else {
                    $carsQuery->whereBetween('heavy.kms', [$vertical_mileage_from, $vertical_mileage_to]);
                }
            }    

            if($request->chassis_number){
                $carsQuery->where('chassis',$request->chassis_number);
            }

            if($request->brand_new_cars){
                $year = date('Y'); 
                if($type == 'car'){
                $carsQuery->where('blog.year_of_reg', 'LIKE', $year . '%');    
                } else if($type == 'heavy'){
                $carsQuery->where('heavy.year_of_reg', 'LIKE', $year . '%');
                } else if($type =='small_heavy'){
                    $carsQuery->where('small_heavy.year_of_reg', 'LIKE', $year . '%');
                }
            }

            if ($request->sort_by) {
                switch ($request->sort_by) {
                    case 'price_low_high':
                        if($type == 'car'){
                            // $carsQuery->orderBy('blog.price', 'asc');  
                            $carsQuery->orderByRaw("CAST(REGEXP_REPLACE(blog.price, '[^0-9]', '') AS UNSIGNED) ASC");  
                        } else if($type == 'heavy'){
                            // $carsQuery->orderBy('heavy.price', 'asc');
                            $carsQuery->orderByRaw("CAST(REGEXP_REPLACE(heavy.price, '[^0-9]', '') AS UNSIGNED) ASC");  
                        }  
                        break;
                    case 'price_high_low':
                    if($type == 'car'){
                            // $carsQuery->orderBy('blog.price', 'desc');    
                            $carsQuery->orderByRaw("CAST(REGEXP_REPLACE(blog.price, '[^0-9]', '') AS UNSIGNED) desc");  
                        } else if($type == 'heavy'){
                            // $carsQuery->orderBy('heavy.price', 'desc');
                            $carsQuery->orderByRaw("CAST(REGEXP_REPLACE(heavy.price, '[^0-9]', '') AS UNSIGNED) desc");  
                        } 
                        break;
                    case 'old_to_new':
                    if($type == 'car'){
                            // $carsQuery->orderBy('blog.price', 'desc');    
                            $carsQuery->orderByRaw("CAST(REGEXP_REPLACE(REGEXP_SUBSTR(blog.yom, '[0-9]+'), '[^0-9]', '') AS UNSIGNED) ASC"); 
                        } else if($type == 'heavy'){
                            // $carsQuery->orderBy('heavy.price', 'desc');
                            $carsQuery->orderByRaw("CAST(REGEXP_REPLACE(REGEXP_SUBSTR(heavy.yom, '[0-9]+'), '[^0-9]', '') AS UNSIGNED) ASC");
                        } 
                        break;
                    case 'new_to_old':
                    if($type == 'car'){
                            // $carsQuery->orderBy('blog.price', 'desc');    
                            $carsQuery->orderByRaw("CAST(REGEXP_REPLACE(REGEXP_SUBSTR(blog.yom, '[0-9]+'), '[^0-9]', '') AS UNSIGNED) desc"); 
                        } else if($type == 'heavy'){
                            // $carsQuery->orderBy('heavy.price', 'desc');
                            $carsQuery->orderByRaw("CAST(REGEXP_REPLACE(REGEXP_SUBSTR(heavy.yom, '[0-9]+'), '[^0-9]', '') AS UNSIGNED) desc");
                        } 
                        break;
                    case 'recent':  
                        if($type == 'car'){   
                            $recentCarIds = $carsQuery->orderBy('blog.id', 'desc')
                            ->limit(100)
                            ->pluck('id');
                            $carsQuery = $carsQuery->whereIn('blog.id', $recentCarIds);
                        } else if($type == 'heavy'){
                                $recentCarIds = $carsQuery->orderBy('heavy.id', 'desc')
                                ->limit(100)
                                ->pluck('id');
                                $carsQuery = $carsQuery->whereIn('heavy.id', $recentCarIds);
                        } 
                        break;
                }
            }
            if($request->price_range_scale){
                if($request->price_range_scale !=""){  
                    $hasPriceRangeScale=true;
                    $parts = explode(',', $request->price_range_scale);
                    $startValue = trim($parts[0]);
                    $endValue = trim($parts[1]);
                    // $carsQuery = $carsQuery->where(function ($q) use ($startValue,$endValue) {
                    //     $q->whereBetween('start_price_num', [$startValue, $endValue])
                    //     ->orWhereBetween('end_price_num', [$startValue, $endValue]);
                    // });
                    if($type=='car')
                    {
                        $startValue = (int)trim(str_replace('"', '', $parts[0]));
                        $endValue = (int)trim(str_replace('"', '', $parts[1]));
                        $carsQuery->whereRaw("REGEXP_REPLACE(blog.price, '[^0-9]', '') BETWEEN ? AND ?", [
                                        $startValue,
                                        $endValue
                                ]);
                    } else if($type == 'heavy'){
                        $carsQuery->whereRaw("REGEXP_REPLACE(heavy.price, '[^0-9]', '') BETWEEN ? AND ?", [
                            $startValue,
                            $endValue
                    ]);
                    } else if($type == 'small_heavy'){
                        $carsQuery->whereRaw("REGEXP_REPLACE(small_heavy.price, '[^0-9]', '') BETWEEN ? AND ?", [
                            $startValue,
                            $endValue
                    ]);
                    }
                }
            }

            if($request->price_range){
                // $carsQuery->whereRaw('REGEXP_REPLACE(price, "[,\\\\s]", "") REGEXP "^[0-9]+$"');
                    $priceRanges = [
                        "Under $5000" => ["start" => 0, "end" => 5000],
                        "$5000 - $50000" => ["start" => 5001, "end" => 50000],
                        "$50000 - $100000" => ["start" => 50001, "end" => 100000],
                        "$100000 - $200000" => ["start" => 100001, "end" => 200000],
                        "$200000 - $300000" => ["start" => 200001, "end" => 300000],
                        "Above $300000" => ["start" => 300001, "end" => PHP_INT_MAX] // Use PHP_INT_MAX for "Above"
                    ];
                    
                    if($type=='car')
                    {
                        $table='blog.price';
                    } else if($type == 'heavy'){
                        $table='heavy.price';
                    } else if($type == 'small_heavy'){
                        $table='small_heavy.price';
                    }    

                    $carsQuery->where(function ($query) use ($request, $priceRanges,$table) {
                        foreach ($request->price_range as $range) {
                            $result = $this->getPriceRangestart($range, $priceRanges);
                
                            $query->orWhereRaw("REGEXP_REPLACE($table, '[^0-9]', '') BETWEEN ? AND ?", [
                                $result['start_price_num'],
                                $result['end_price_num']
                            ]);
                        }
                    }); 
        
                    


            }
     
        
            
            // Pagination
            $cars = $carsQuery->paginate(12);
        // Transform cars into an array for the view
        $cars_array = $cars->map(function ($car) {
        // $car_image=$this->last_image($car->pictures);
            return [
                'model_name' => $car->model,
                'start_price' => $car->price,
                'picture' =>$car->image,
                'id' => $car->id,
                'make'=>$car->make,
                'kms'=>$car->kms,
                'yor'=>$car->yom,
                'chassis_number'=>$car->chassis,
                'location'=>$car->location,
                'created_at'=>$car->created_at,
            ];
        });

     
    
    
        // Get additional data
        $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();

        $brand_count = CarDataJpOp::selectRaw('company_en,company,COUNT(*) as count')
            ->groupBy('company_en')
            ->having('count', '>', 1)
            ->get();
    
      

          
           
            

        $price_range=$this->SelectedJdmRange($type,$priceRanges,$slug,
        $hasPriceRangeScale,$startValue,$endValue);


        // echo json_encode($brand_list);die();
      
         
        $results = $filterQuery->select('yom', 'kms', 'transmission','fuel','chassis')
        ->groupBy('yom', 'kms', 'transmission','fuel','chassis')
        ->get();

        // Extract the distinct values and remove null/empty values
        // $distinctYears = $results->pluck('yom')->filter()->unique()->values();
        $distinctYears = $results->pluck('yom')
                ->map(function ($year) {
                    // Attempt to parse the year and format it
                    try {
                        return Carbon::parse($year)->format('Y');
                    } catch (\Exception $e) {
                        // If parsing fails (e.g., invalid date), return the original value
                        return $year;
                    }
                })
                ->filter() // Remove null or empty values
                ->unique() // Remove duplicate years    
                ->values();
        $distinctMileage = $results->pluck('kms')->filter()->unique()->values();
        $distinctTransmission = $results->pluck('transmission')->filter()->unique()->values();
        $distinctFuel = $results->pluck('fuel')->filter()->unique()->values();
        $distinctChassis = $results->pluck('chassis')->filter()->unique()->values();

        $distinctData= [
        'years' => $distinctYears,
        'mileage' => $distinctMileage,
        'transmission' => $distinctTransmission,
        'fuel' => $distinctFuel,
        'chassis'=>$distinctChassis
        ];  

  
        return view('jdm-stock-client', [
        // return view('jdm-stock-responsive', [
            'seo_setting' => $seo_setting,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand,
            'brands' => $brands,
            'cars_array' => $cars_array,
            'listing_ads' => $listing_ads,
            'cars' => $cars,
            'brand_count' => $brand_count,
            'slug'=>$slug,
            'type'=>$type,
            'price_range' => $price_range,
            // 'transmission'=>$transmission,
            // 'scores'=>$scores,
            'brand_arr'=>$brand_list,
            'minYear'=>$minYear,
            'maxYear'=>$maxYear,
            'minPrice'=>$minPrice,
            'maxPrice'=>$maxPrice,
            'brand_label'=>$brand_label,
            'wishlists'=>$wishlists,
            'brand_total'=>$brand_total,
            'distinctData'=>$distinctData,
            // 'jdm_legend_heavy'=>$jdm_legend_heavy,
            // 'jdm_legend_small_heavy'=>$jdm_legend_small_heavy
        ]);
    }

    public function searchFilter(Request $request){
        $type = 'car';  // You can dynamically change this based on your application logic

            if ($type == 'car') {
                if (Auth::guard('web')->check()) {
                    $userId = $userId ?? auth()->id();  // Assign user ID if authenticated
                }
                // Build the car query
                $carsQuery = Cars::where(DB::raw('LOWER(make)'), $request->brand)
                    ->where('is_active', '1')
                    ->WhereRaw('TRIM(blog.model) = ?', $request->model)
                    ->whereRaw('REGEXP_REPLACE(price, "[,\\s]", "") REGEXP "^[0-9]+$"')
                    ->select('blog.*');
            } else if ($type == 'heavy') {
                if (Auth::guard('web')->check()) {
                    $userId = $userId ?? auth()->id();
                }
                // Build the heavy query
                $carsQuery = Heavy::where('heavy.make', $slug)
                    ->where('is_active', '1')
                    ->whereRaw('REGEXP_REPLACE(heavy.price, "[,\\s]", "") REGEXP "^[0-9]+$"')
                    ->select('heavy.*');
            }

            // Get the filtered and grouped data
            $results = $carsQuery->get();

            // Extract the distinct values and remove null/empty values
            $distinctYears = $results->pluck('yom')
                ->map(function ($year) {
                    try {
                        return Carbon::parse($year)->format('Y');  // Format the year if possible
                    } catch (\Exception $e) {
                        return $year;  // Return the original value if parsing fails
                    }
                })
                ->filter()  // Remove null or empty values
                ->unique()  // Remove duplicate years
                ->values();

            $distinctMileage = $results->pluck('kms')->filter()->unique()->values();
            $distinctTransmission = $results->pluck('transmission')->filter()->unique()->values();
            $distinctFuel = $results->pluck('fuel')->filter()->unique()->values();
            $distinctChassis = $results->pluck('chassis')->filter()->unique()->values();
            $distinctPrice = $results->pluck('price')->filter()->unique()->values();

            // Prepare the distinct data for JSON response
            $distinctData = [
                'years' => $distinctYears,
                'mileage' => $distinctMileage,
                'transmission' => $distinctTransmission,
                'fuel' => $distinctFuel,
                'chassis'=>$distinctChassis,
                'price'=>$distinctPrice
            ];
        return response()->json(['status' => true, 'response' => $distinctData]);
    }


    public function jdm_listing(Request $request,$slug,$type)
    {
        if($type=='car'){
        $car = Cars::where('id',$slug)->firstOrFail();
        } else if($type=='heavy'){
            $car = Heavy::where('id',$slug)->firstOrFail();      
        } else if($type == 'small_heavy'){
            $car = SmallHeavy::where('id',$slug)->firstOrFail();
        }
        $related_listings = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->where('brand_id', $car->brand_id)->where('id', '!=', $car->id)->get()->take(6);

        $reviews = Review::with('user')->where('car_id', $car->id)->where('status', 'enable')->latest()->get();
        $listing_ads = AdsBanner::where('position_key', 'listing_detail_page_banner')->first();

        $delivery_charges = DeliveryCharge::all();
        // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //              ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();

        // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_brand['car']=$jdm_legend;
        // $jdm_brand['heavy']=$jdm_legend_heavy;
        // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
        // $jdm_brand=$this->jdm_brands();
        $jdm_core_brand = Brand::where('status', 'enable')->get();



        return view('jdm-listing', [
            'car' => $car,
            'related_listings' => $related_listings,
            'reviews' => $reviews,
            'listing_ads' => $listing_ads,
            'delivery_charges'=>$delivery_charges,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand,
            'type'=>$type,
            'url_link'=>url()->full() 
        ]);
    }
    public function jdm_stock_listing(Request $request,$slug,$type)
    {
        if($type=='car'){
            $car = Cars::where('id',$slug)->firstOrFail();
            $car_images=Cars::Join('add_product_images as pi','pi.category','=','blog.id')
            ->where('pi.category',$slug)
            ->select('pi.image')->get();
            $car_charges=JdmStockBlogOtherCharges::where('jdm_blog_id',$slug)->first();
            $image_folder='Cars';
        } else if($type=='heavy'){
            $car = Heavy::where('id',$slug)->firstOrFail(); 
            $car_images=Heavy::Join('add_heavy_images as pi','pi.category','=','heavy.id')
            ->where('pi.category',$slug)
            ->select('pi.image')->get(); 
            $car_charges=JdmStockHeavyOtherCharges::where('jdm_heavy_id',$slug)->first(); 
            $image_folder='heavy_photos';   
        } else if($type == 'small_heavy'){
            $car = SmallHeavy::where('id',$slug)->firstOrFail();
            $car_images=SmallHeavy::Join('add_small_heavy_images  as pi','pi.category','=','small_heavy.id')
            ->where('pi.category')
            ->select('pi.image')->get();
            $image_folder='small_heavy';
        }

        $car_accessories=array(
            'abs'=>'ABS Anti-lock braking systems',
            'aw'=>'AW Alloy Wheels',
            'pw'=>'PW Power Windows',
            'ps'=>'PS Power Steering',
            'ab'=>'AB Airbag',
            'sr'=>'SR Sunroof'
        );
        $accesories=[];

        foreach($car_accessories as $key=>$value){
            if($car->$key == '1'){
               array_push($accesories,$value);
            }
        }
      

        $listing_ads = AdsBanner::where('position_key', 'listing_detail_page_banner')->first();

        $delivery_charges = DeliveryCharge::all();
        // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //              ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();

        // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_brand['car']=$jdm_legend;
        // $jdm_brand['heavy']=$jdm_legend_heavy;
        // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
        // $jdm_brand=$this->jdm_brands();
        $jdm_core_brand = Brand::where('status', 'enable')->get();
        $seo_setting = SeoSetting::where('id', 1)->first();


        return view('jdm-stock-listing', [
            'car' => $car,
            'seo_setting' => $seo_setting,
            'listing_ads' => $listing_ads,
            'delivery_charges'=>$delivery_charges,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand,
            'type'=>$type,
            'url_link'=>url()->full(),
            'car_images'=>$car_images,
            'slug'=>$slug,
            'image_folder'=>$image_folder,
            'accesories'=>$accesories,
            'usd_rate'=>$this->usdRate,
            'car_charges'=>$car_charges
        ]);
    }



    public function last_image($picture){
        if ($picture === null || $picture === "NULL" || trim($picture) === "") {
            return []; // Ensure an empty array is returned
        }
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

    public function about_us(){

        $jdm_core_brand = Brand::where('status', 'enable')->get();

        // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //              ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();

        // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_brand['car']=$jdm_legend;
        // $jdm_brand['heavy']=$jdm_legend_heavy;
        // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
        //  $jdm_brand=$this->jdm_brands();

        $seo_setting = SeoSetting::where('id', 3)->first();

        $about_us = AboutUs::first();

        $brands = Brand::where('status', 'enable')->get();

        $homepage = HomePage::first();

        $testimonials = Testimonial::where('status', 'active')->orderBy('id','desc')->get();

        return view('about_us')->with([
            'seo_setting' => $seo_setting,
            'about_us' => $about_us,
            'brands' => $brands,
            'homepage' => $homepage,
            'testimonials' => $testimonials,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand
        ]);
    }


    public function contact_us(){
        $seo_setting = SeoSetting::where('id', 4)->first();

        $contact_us = ContactUs::first();
        $jdm_core_brand = Brand::where('status', 'enable')->get();

        // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //              ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();

        // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_brand['car']=$jdm_legend;
        // $jdm_brand['heavy']=$jdm_legend_heavy;
        // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
        // $jdm_brand=$this->jdm_brands();


        return view('contact_us')->with([
            'seo_setting' => $seo_setting,
            'contact_us' => $contact_us,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand
        ]);
    }


    public function shipment(){
         $seo_setting = SeoSetting::where('id', 4)->first();
         $jdm_core_brand = Brand::where('status', 'enable')->get();

        //  $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        //               ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //               ->where('bt.lang_code',Session::get('front_lang'))
        //  ->select('b.slug','bt.name as brand_name')
        //  ->distinct('b.slug')->get();

        //  $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        //  ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //  ->where('bt.lang_code',Session::get('front_lang'))
        //  ->select('b.slug','bt.name as brand_name')
        //  ->distinct('b.slug')->get();

        //  $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        //  ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //  ->where('bt.lang_code',Session::get('front_lang'))
        //  ->select('b.slug','bt.name as brand_name')
        //  ->distinct('b.slug')->get();

 
 
        //  $jdm_brand['car']=$jdm_legend;
        //  $jdm_brand['heavy']=$jdm_legend_heavy;
        //  $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
        // $jdm_brand=$this->jdm_brands();
 
        return view('shipment')->with([
            'seo_setting' => $seo_setting,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand
        ]);
    }



    

    public function terms_conditions(){
        $seo_setting = SeoSetting::where('id', 6)->first();

        $terms_condition = TermAndCondition::where('lang_code', Session::get('front_lang'))->first();
        $jdm_core_brand = Brand::where('status', 'enable')->get();

        // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //              ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();

        // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();

        // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();



        // $jdm_brand['car']=$jdm_legend;
        // $jdm_brand['heavy']=$jdm_legend_heavy;
        // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;

        // $jdm_brand=$this->jdm_brands();


        $jdm_core_brand = Brand::where('status', 'enable')->get();
        // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //              ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();
        // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();
        // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();
        // $jdm_brand['car']=$jdm_legend;
        // $jdm_brand['heavy']=$jdm_legend_heavy;
        // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
        // $jdm_brand=$this->jdm_brands();
        return view('terms_conditions')->with([
            'seo_setting' => $seo_setting,
            'terms_condition' => $terms_condition,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand
        ]);
    }

    public function privacy_policy(){
        $seo_setting = SeoSetting::where('id', 7)->first();

        $privacy_policy = PrivacyPolicy::where('lang_code', Session::get('front_lang'))->first();
        $jdm_core_brand = Brand::where('status', 'enable')->get();

        // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //              ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();

        // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();

        // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();



        // $jdm_brand['car']=$jdm_legend;
        // $jdm_brand['heavy']=$jdm_legend_heavy;
        // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;

        // $jdm_brand=$this->jdm_brands();

      $jdm_core_brand = Brand::where('status', 'enable')->get();
        // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //              ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();
        // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();
        // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();
        // $jdm_brand['car']=$jdm_legend;
        // $jdm_brand['heavy']=$jdm_legend_heavy;
        // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
        // $jdm_brand=$this->jdm_brands();
        return view('privacy_policy')->with([
            'seo_setting' => $seo_setting,
            'privacy_policy' => $privacy_policy,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand
        ]);
    }

    public function faq(){
        $seo_setting = SeoSetting::where('id', 5)->first();

        $faqs = Faq::latest()->get();

        $homepage = HomePage::first();
        $jdm_core_brand = Brand::where('status', 'enable')->get();

        // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //              ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();

        // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_brand['car']=$jdm_legend;
        // $jdm_brand['heavy']=$jdm_legend_heavy;
        // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;

        // $jdm_brand=$this->jdm_brands();
        

        return view('faq')->with([
            'seo_setting' => $seo_setting,
            'faqs' => $faqs,
            'homepage' => $homepage,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand
        ]);
    }

    public function howtobuy(){
        $seo_setting = SeoSetting::where('id', 5)->first();

        $faqs = Faq::latest()->get();

        $homepage = HomePage::first();

        return view('howtobuy')->with([
            'seo_setting' => $seo_setting,
            'faqs' => $faqs,
            'homepage' => $homepage,
        ]);
    }


    public function BrandListig(){
        $seo_setting = SeoSetting::where('id', 3)->first();

        $about_us = AboutUs::first();

        $brands = Brand::where('status', 'enable')->get();

        $homepage = HomePage::first();

        $testimonials = Testimonial::where('status', 'active')->orderBy('id','desc')->get();
        $jdm_core_brand = Brand::where('status', 'enable')->get();

        // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //              ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();

        // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_brand['car']=$jdm_legend;
        // $jdm_brand['heavy']=$jdm_legend_heavy;
        // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;

        // $jdm_brand=$this->jdm_brands();

        return view('brand-listing')->with([
            'seo_setting' => $seo_setting,
            'about_us' => $about_us,
            'brands' => $brands,
            'homepage' => $homepage,
            'testimonials' => $testimonials,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand
        ]);
    }

    public function blogs(Request $request){

        $seo_setting = SeoSetting::where('id', 2)->first();

        $blogs = Blog::with('author')->orderBy('id','desc')->where('status', 1);

        if($request->category){
            $blog_category = BlogCategory::where('slug', $request->category)->first();
            $blogs = $blogs->where('blog_category_id', $blog_category->id);
        }

        if($request->search){
            $blogs = $blogs->whereHas('translations', function ($query) use ($request) {
                            $query->where('title', 'like', '%' . $request->search . '%')
                                ->orWhere('description', 'like', '%' . $request->search . '%');
                        })
                        ->orWhere(function ($query) use ($request) {
                            $query->whereJsonContains('tags', ['value' => $request->search]);
                        });
        }

        $blogs = $blogs->paginate(9);

        $popular_blogs = Blog::where('is_popular', 'yes')->where('status', 1)->orderBy('id','desc')->get();

        $categories = BlogCategory::where('status', 1)->get();
        $jdm_core_brand = Brand::where('status', 'enable')->get();

        // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //              ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();

        // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_brand['car']=$jdm_legend;
        // $jdm_brand['heavy']=$jdm_legend_heavy;
        // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;


        // $jdm_brand=$this->jdm_brands();
     

        return view('blog')->with([
            'seo_setting' => $seo_setting,
            'blogs' => $blogs,
            'popular_blogs' => $popular_blogs,
            'categories' => $categories,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand
        ]);
    }

    public function blog_show(Request $request, $slug){
        $blog = Blog::where('status', 1)->where(['slug' => $slug])->first();
        $blog->views += 1;
        $blog->save();

        $blog_comments = BlogComment::orderBy('id','desc')->where('blog_id', $blog->id)->where('status', 1)->get();

        $popular_blogs = Blog::where('is_popular', 'yes')->where('status', 1)->orderBy('id','desc')->get();

        $categories = BlogCategory::where('status', 1)->get();
        $jdm_core_brand = Brand::where('status', 'enable')->get();

        // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //              ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();

        // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_brand['car']=$jdm_legend;
        // $jdm_brand['heavy']=$jdm_legend_heavy;
        // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
        // $jdm_brand=$this->jdm_brands();


        
        return view('blog_detail')->with([
            'blog' => $blog,
            'blog_comments' => $blog_comments,
            'popular_blogs' => $popular_blogs,
            'categories' => $categories,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand
        ]);
    }

    public function store_comment(Request $request){
        $rules = [
            'blog_id'=>'required',
            'name'=>'required',
            'email'=>'required',
            'comment'=>'required',
            'g-recaptcha-response'=>new Captcha()
        ];
        
        $customMessages = [
            'name.required' => trans('translate.Name is required'),
            'email.required' => trans('translate.Email is required'),
            'comment.required' => trans('translate.Comment is required')
        ];
        $this->validate($request, $rules,$customMessages);

        $blog_comment = new BlogComment();
        $blog_comment->blog_id = $request->blog_id;
        $blog_comment->name = $request->name;
        $blog_comment->email = $request->email;
        $blog_comment->comment = $request->comment;
        $blog_comment->save();

        $notification= trans('translate.Blog comment has submited');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function custom_page($slug){
        $seo_setting = SeoSetting::where('id', 1)->first();
        $custom_page = CustomPage::where('slug', $slug)->first();
         $jdm_core_brand = Brand::where('status', 'enable')->get();

        // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //              ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();

        // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_brand['car']=$jdm_legend;
        // $jdm_brand['heavy']=$jdm_legend_heavy;
        // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;

        // $jdm_brand=$this->jdm_brands();
        

        return view('custom_page')->with([
            'seo_setting' => $seo_setting,
              'custom_page' => $custom_page,
          
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand
        ]);
    }


    function getPriceRangestart($selectedRanges, $priceRanges) {
        $start_price_num = null;
        $end_price_num = null;

        // echo json_encode($priceRanges);die();
    
        // foreach ($selectedRanges as $range) {
            if (isset($priceRanges[$selectedRanges])) {
                $start = $priceRanges[$selectedRanges]['start'];
                $end = $priceRanges[$selectedRanges]['end'];
    
                // Set the overall start and end prices based on the selected ranges
                if ($start_price_num === null || $start < $start_price_num) {
                    $start_price_num = $start; // Update if it's lower
                }
                if ($end_price_num === null || $end > $end_price_num) {
                    $end_price_num = $end; // Update if it's higher
                }
            }
        // }
    
        return ['start_price_num' => $start_price_num, 'end_price_num' => $end_price_num];
    }






    

    public function listings(Request $request){

        $seo_setting = SeoSetting::where('id', 1)->first();

        // $brands = Brand::where('status', 'enable')->get();

        $brands = CarDataJpOp::join('brands as b', DB::raw('LOWER(auct_lots_xml_jp_op.company_en)'), '=', 'b.slug')
        ->join('brand_translations as bt','bt.brand_id','=','b.id')
        ->where('bt.lang_code',Session::get('front_lang'))
        ->select('b.slug','bt.name as name')
        ->distinct('b.slug')->get();
        
        $models=[];

    DB::enableQueryLog();
    // Initialize the query for cars
    $carsQuery = CarDataJpOp::query();

    // Apply filters based on request parameters
    if ($request->location) {
        $carsQuery->where('city_id', $request->location);
    }

    if($request->price_range_scale){
        if($request->price_range_scale !=""){  
            $parts = explode('-', $request->price_range_scale);
            $startValue = trim($parts[0]);
            $endValue = trim($parts[1]);
            $carsQuery = $carsQuery->where(function ($q) use ($startValue,$endValue) {
                $q->whereBetween('start_price_num', [$startValue, $endValue])
                ->orWhereBetween('end_price_num', [$startValue, $endValue]);
            });
        }
    }

    if($request->brand_new_cars){
        $year = date('Y'); 
         $carsQuery->where('model_year_en', 'LIKE', $year . '%');    
    }


    



    if ($request->brand) {
        // $brand_arr = array_filter($request->brand); // Filter out any empty values
        // if ($brand_arr) {
            // $carsQuery->whereIn('company_en', $brand_arr); 
            $carsQuery->where(DB::raw('LOWER(company_en)'), $request->brand); 
            $models = \DB::table('auct_lots_xml_jp_op')
            ->where(DB::raw('LOWER(company_en)'), $request->brand)
            ->groupBy('model_name_en') 
            ->select('model_name_en')
            ->get();
        // }    
    }

    if($request->model){
        $carsQuery->where('model_name_en', $request->model); 
    }
    if ($request->tranmission) {
        $transmission_arr = array_filter($request->transmission_arr); // Filter out any empty values
        if ($transmission_arr) {
            $carsQuery->whereIn('transmission_en', $transmission_arr); 
        }    
    }

    if($request->year){
        if($request->year !="")
        {
            $carsQuery->where('model_year_en', $request->year); 
        }
    }

    if($request->price_range){
        $priceRanges = [
            "Under $5000" => ["start" => 0, "end" => 5000],
            "$5000 - $50000" => ["start" => 5000, "end" => 50000],
            "$50000 - $100000" => ["start" => 50000, "end" => 100000],
            "$100000 - $200000" => ["start" => 100000, "end" => 200000],
            "$200000 - $300000" => ["start" => 200000, "end" => 300000],
            "Above $300000" => ["start" => 300000, "end" => null] // Use PHP_INT_MAX for "Above"
        ];

        $result = $this->getPriceRangestart($request->price_range, $priceRanges);
      

  
        if ($result['start_price_num'] === null) {
            // Count for "Above" range
            $carsQuery = $carsQuery->where('start_price_num', '>', $result['start_price_num'])
                           ->orWhere('end_price_num', '>', $result['start_price_num']);
        } else {
            // Count for other ranges
            $carsQuery = $carsQuery->where(function ($q) use ($result) {
                $q->whereBetween('start_price_num', [$result['start_price_num'], $result['end_price_num']])
                  ->orWhereBetween('end_price_num', [$result['start_price_num'], $result['end_price_num']]);
            });
        }  
    }

    if ($request->scores_en) {
        $score_arr = array_filter($request->scores_en); // Filter out any empty values
        if ($score_arr) {
            $carsQuery->whereIn('scores_en', $score_arr); 
        }
    }


    if ($request->price_filter) {
        if ($request->price_filter === 'low_to_high') {
            $carsQuery->orderBy('regular_price', 'asc');
        } elseif ($request->price_filter === 'high_to_low') {
            $carsQuery->orderBy('regular_price', 'desc');
        }
    }

    if ($request->search) {
        // if(Session::get('front_lang') == '')
        // $carsQuery->whereHas('front_translate', function ($query) use ($request) {
            $carsQuery->where('model_name_en', 'like', '%' . $request->search . '%');
        //         //   ->orWhere('description', 'like', '%' . $request->search . '%');
        // });
    }

    if ($request->sort_by) {
        switch ($request->sort_by) {
            case 'price_low_high':
                $carsQuery->orderBy('start_price_num', 'asc');
                break;
            case 'price_high_low':
                $carsQuery->orderBy('start_price_num', 'desc');
                break;
            case 'recent':
                $recentCarIds = $carsQuery->orderBy('id', 'desc')
                ->limit(100)
                ->pluck('id');
            
            // Then reset the query and use these IDs
            $carsQuery = $carsQuery->whereIn('id', $recentCarIds);
                break;
        }
    }

    // $carsQuery->get();

    // Pagination
    $cars = $carsQuery->where('active_status','1')
    ->orderBy('id','desc')
    ->paginate(12);


    // Transform cars into an array for the view
    $cars_array = $cars->map(function ($car) {
    $car_image=$this->last_image($car->pictures);
        return [
            'company_en' => $car->company_en,
            'company' => $car->company,
            'model_name' => $car->model_name,
            'model_name_en' => $car->model_name_en,
            'start_price' => $car->start_price,
            'start_price_num' => $car->start_price_num,
            'end_price' => $car->end_price,
            'end_price_num' => $car->end_price_num,
            'picture' =>$car_image[0],
            'id' => $car->id,
            'mileage' => $car->mileage,
            'mileage_en' => $car->mileage_en,
            
        ];
    });


    // Get additional data
    $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();
    $cities = City::with('translate')->get();
    $features = Feature::with('translate')->get();

    $brand_count = CarDataJpOp::selectRaw('company_en,company,COUNT(*) as count')
        ->groupBy('company_en')
        ->having('count', '>', 1)
        ->get();

    $transmission = CarDataJpOp::selectRaw('transmission_en, COUNT(*) as count')
        ->groupBy('transmission_en')
        ->having('count', '>', 1)
        ->get();

    $scores = CarDataJpOp::selectRaw('scores_en, COUNT(*) as count')
        ->groupBy('scores_en')
        ->having('count', '>', 1)
        ->get();

    $price_range = $this->getPriceRange();

    //     $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
    //     ->join('brand_translations as bt','bt.brand_id','=','b.id')
    //     ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();


    // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();

    // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();
    $jdm_core_brand = Brand::where('status', 'enable')->get();

    // $jdm_brand['car']=$jdm_legend;
    // $jdm_brand['heavy']=$jdm_legend_heavy;
    // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;

    // $jdm_brand=$this->jdm_brands();


    return view('listing', [
        'seo_setting' => $seo_setting,
        'brands' => $brands,
        'cities' => $cities,
        'jdm_core_brand'=>$jdm_core_brand,
        'features' => $features,
        'cars_array' => $cars_array,
        'listing_ads' => $listing_ads,
        'cars' => $cars,
        'brand_count' => $brand_count,
        'price_range' => $price_range,
        'transmission' => $transmission,
        'scores' => $scores,
        // 'jdm_legend'=>$jdm_brand,
        'models'=>$models
        // 'jdm_legend_heavy'=>$jdm_legend_heavy,
        // 'jdm_legend_small_heavy'=>$jdm_legend_small_heavy
    ]);
    }


public function getBrandsWithModels($keywhere,$database_name,$brand_new): array
{    
   
    
    // Process in chunks to handle large datasets efficiently
        // $brands = DB::table('brands as b')
        //     ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
        //     ->where('bt.lang_code', Session::get('front_lang'))
        //     ->select('b.slug', 'bt.name')
        //     ->get();

   

        $result = [];

        if($database_name=='1'){
            $tableName='auct_lots_xml_jp_op';
        } else {
            $tableName='auct_lots_xml_jp';
        }

        $brands=DB::table($tableName)->select(
            'company_en',
            \DB::raw('LOWER(company_en) as slug')
        )
        ->groupBy('company_en')
        ->get();   
         
        // Db::enableQueryLog();

        // DB::table($tableName)
        //     ->select(
        //         DB::raw('LOWER(company_en) as brand_slug'),
        //         'model_name_en'
        //     )
        //     ->whereIn(DB::raw('LOWER(company_en)'), $brands->pluck('slug'))
        //     ->when($keywhere == 'new-arrival', function($query) {
        //         return $query->where('new_arrival', '1');
        //     })
        //     ->when($keywhere == 'top-sell', function($query) {
        //         return $query->where('top_sell', '1');
        //     })
        //     ->distinct()
        //     ->orderBy('company_en')
        //     ->chunk(1000, function($models) use (&$result) {
        //         foreach ($models as $model) {
        //             // Normalize case in PHP
        //             $normalizedName = ucwords(strtolower($model->model_name_en));
        //             $result[$model->brand_slug][$normalizedName] = true;
        //         }
        //     });
        // Db::enableQueryLog();
        DB::table($tableName)
            ->leftJoin('auct_lots_xml_jp_op_other_chargers as oc', 'oc.auct_id', '=', $tableName . '.id')
            // ->join('brands as b', DB::raw('LOWER(company_en)'), '=', 'b.slug') // Add join with brands
            // ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id') 
            ->select(
                DB::raw('LOWER(company_en) as brand_slug'),
                'model_name_en',
                DB::raw('COUNT(*) as model_count')  // Add the model count
            )
            // ->where('bt.lang_code', Session::get('front_lang')) 
            ->whereIn(DB::raw('LOWER(company_en)'), $brands->pluck('slug'))
            ->when($keywhere == 'new-arrival', function($query) {
                return $query->where('oc.new_arrival', '1');
            })
            ->when($keywhere == 'top-sell', function($query) {
                return $query->where('oc.top_sell', '1');  
            })
            // ->when($tableName == 'auct_lots_xml_jp_op', function($query) {
            //     return $query->where('active_status', '1');
            // })
            ->when($brand_new == 'brand-new', function($query) {
                return $query->whereBetween('model_year_en', [now()->subYear()->year,now()->year]);
            })
            ->groupBy(DB::raw('LOWER(company_en)'), 'model_name_en') // Group by brand and model
            ->distinct()
            ->orderBy('company_en')
            ->chunk(1000, function($models) use (&$result) {
                foreach ($models as $model) {
                    // Normalize case in PHP
                    $normalizedName = ucwords(strtolower($model->model_name_en));

                    // Store the model count for each brand and model
                    $result[$model->brand_slug][$normalizedName] = $model->model_count;
                }
            });

        // dd(DB::getQueryLog());    

    // Convert to final format and sort
    // return collect($result)->map(function($models) {
    //     return collect(array_keys($models))->sort()->values();
    // })->all();

    return collect($result)->map(function ($models) {
        return collect($models)->map(function ($count, $model) {
            return ['model' => $model, 'count' => $count];
        })->sortBy('model')->values();
    })->all();
}
public function getJdmBrandsWithModels(): array
{    
    // Process in chunks to handle large datasets efficiently
    $brands = DB::table('brands as b')
        ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
        ->where('bt.lang_code', Session::get('front_lang'))
        ->select('b.slug', 'bt.name')
        ->get();

    $result = [];



    DB::table('blog')
        ->select(
            DB::raw('LOWER(make) as brand_slug'),
            'model'
        )
        ->whereIn(DB::raw('LOWER(make)'), $brands->pluck('slug'))
        ->distinct()
        ->orderBy('make')
        ->chunk(1000, function($models) use (&$result) {
            foreach ($models as $model) {
                // Normalize case in PHP
                $normalizedName = ucwords(strtolower($model->model));
                $result[$model->brand_slug][$normalizedName] = true;
            }
        });

    // Convert to final format and sort
    return collect($result)->map(function($models) {
        return collect(array_keys($models))->sort()->values();
    })->all();
}
public function car_listing(Request $request){
    // dd($request->all());
        // $brands = CarDataJpOp::join('brands as b', DB::raw('LOWER(auct_lots_xml_jp_op.company_en)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as name')
        // ->distinct('b.slug')->get();
        $seo_setting = SeoSetting::where('id', 1)->first();
        $brands=CarDataJpOp::select(
            'company_en as name',
            \DB::raw('LOWER(company_en) as slug')
        )
        ->where('active_status', '1')
        ->groupBy('company_en')
        ->get();

        $keyWhere ="";
        $tableName='1';
        $brand_list=$this->getBrandsWithModels($keyWhere,$tableName,'');
        $models=[];

        $yearRange = CarDataJpOp::where('active_status', '1')
        ->selectRaw('MIN(model_year_en) as min_year, MAX(model_year_en) as max_year')
        ->first();
        $priceRange = CarDataJpOp::where('active_status', '1')
        ->selectRaw('MIN(start_price_num) as min_sal, MAX(start_price_num) as max_sal')
        ->first();


        $minYear = $yearRange->min_year;
        // $maxYear = $yearRange->max_year;
        $maxYear = Carbon::now()->year;
       

        // $minPrice = $priceRange->min_sal;
        $minPrice = $this->convertCurrency($priceRange->min_sal, $this->usdRate);
        // $maxPrice = $priceRange->max_sal;
        $maxPrice = $this->convertCurrency($priceRange->max_sal, $this->usdRate);
    

        $hasPriceRangeScale = false;
        $startValue = $minPrice;
        $endValue = $maxPrice;


    // DB::enableQueryLog();

    // Initialize the query for cars


    // echo json_encode($request->model);die();
    
    $carsQuery = CarDataJpOp::query();

    // $carsQuery->join('brands as b', DB::raw('LOWER(auct_lots_xml_jp_op.company_en)'), '=', 'b.slug')
    // ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
    // ->where('bt.lang_code', Session::get('front_lang'));

    // Apply filters based on request parameters
   

    if($request->price_range_scale){
        if($request->price_range_scale !=""){  
            $hasPriceRangeScale = true;
            $parts = explode(',', $request->price_range_scale);
            $start_value=trim($parts[0]);
            $end_value=trim($parts[1]);

            $startValue = ($start_value *  $this->usdRate);
            $endValue = ($end_value *  $this->usdRate);
            
             if($start_value ==($minPrice || $maxPrice)){
                 if($start_value == $minPrice){
                    $startValue=$priceRange->min_sal;
                 } else if($start_value == $maxPrice){
                       $startValue=$priceRange->max_sal;
                 }
             }
             if($end_value ==($minPrice || $maxPrice)){
                 if($end_value == $minPrice){
                    $endValue=$priceRange->min_sal;
                 } else if($end_value == $maxPrice){
                    $endValue=$priceRange->max_sal;
                 }
             }  
            $carsQuery = $carsQuery->where(function ($q) use ($startValue,$endValue) {
                $q->whereBetween('start_price_num', [$startValue, $endValue]);
                // $q->whereBetween('start_price_num', [$startValue, $endValue])
                // ->orWhereBetween('end_price_num', [$startValue, $endValue]);
            });
        }
    }



    if ($request->brand) {
        $brand_arr = array_filter($request->brand); // Filter out any empty values
        if ($brand_arr) {
            $carsQuery->whereIn(DB::raw('LOWER(company_en)'), $brand_arr); 
            // $carsQuery->where(DB::raw('LOWER(company_en)'), $request->brand); 
            $models = \DB::table('auct_lots_xml_jp_op')
            ->whereIn(DB::raw('LOWER(company_en)'), $request->brand)
            ->groupBy('model_name_en') 
            ->select('model_name_en')
            ->get();
        }    
    }

    if($request->model){
        $model_arr = [];
        foreach ($request->model as $brandSlug => $models) {
            if (is_array($models)) {
                $model_arr = array_merge($model_arr, array_filter($models)); // Flatten the nested array
            }
        }
        if ($model_arr) {
            $carsQuery->whereIn('model_name_en', $model_arr);
        }
        // $model_arr = array_filter($request->model); // Filter out any empty values
        // if ($model_arr) {
        //     $carsQuery->whereIn('model_name_en', $model_arr); 
        // }
    }


    if($request->year){
        if($request->year !=""){  
            $hasPriceRangeScale = true;
            $parts = explode(',', $request->year);
            $startYear=trim($parts[0]);
            $endYear=trim($parts[1]);

            $carsQuery = $carsQuery->where(function ($q) use ($startYear,$endYear) {
                // $q->whereBetween('start_price_num', [$startValue, $endValue])
                // ->orWhereBetween('end_price_num', [$startValue, $endValue]);
                $q->whereBetween('model_year_en', [($startYear), ($endYear)]);
            });
        }
        // if($request->year !="")
        // {
        //     $carsQuery->where('model_year_en', $request->year); 
        // }
    }

    if($request->price_range){
        $priceRanges = [
            "Under $5000" => ["start" => 0, "end" => (5000 * $this->usdRate)],
            "$5000 - $50000" => ["start" => (5001 * $this->usdRate), "end" => (50000 * $this->usdRate)],
            "$50000 - $100000" => ["start" => (50001 * $this->usdRate), "end" => (100000 * $this->usdRate)],
            "$100000 - $200000" => ["start" => (100001 * $this->usdRate), "end" => (200000 * $this->usdRate)],
            "$200000 - $300000" => ["start" => (200001 * $this->usdRate), "end" => (300000 * $this->usdRate)],
            "Above $300000" => ["start" => (300001 * $this->usdRate), "end" => PHP_INT_MAX] // Use PHP_INT_MAX for "Above"
        ];



        $carsQuery->where(function ($query) use ($request, $priceRanges) {
            foreach ($request->price_range as $range) {
                $result = $this->getPriceRangestart($range, $priceRanges);
    
                if ($result['start_price_num'] !== null && $result['end_price_num'] !== null) {
                    // Group the conditions for this range
                    $query->orWhere(function ($subQuery) use ($result) {
                        $subQuery->whereBetween('start_price_num', [$result['start_price_num'], $result['end_price_num']]);
                        // $subQuery->whereBetween('start_price_num', [$result['start_price_num'], $result['end_price_num']])
                        //          ->orWhereBetween('end_price_num', [$result['start_price_num'], $result['end_price_num']]);
                    });
                }
            }
        }); 




            // foreach($request->price_range as $range){
            //     $result = $this->getPriceRangestart($range, $priceRanges);

            //     $carsQuery=$carsQuery->whereBetween('start_price_num', [$result['start_price_num'], $result['end_price_num']])
            //     ->orWhereBetween('end_price_num', [$result['start_price_num'], $result['end_price_num']]);
            //     // if ($result['start_price_num'] === null) {
            //     //     // Count for "Above" range
                
            //     //     $carsQuery = $carsQuery->where('start_price_num', '>', $result['start_price_num'])
            //     //                    ->orWhere('end_price_num', '>', $result['start_price_num']);
            //     // } else {
            //     //     $result['end_price_num']=$result['end_price_num'] == null ? $endValue : $result['end_price_num'];
            //     //     // Count for other ranges
            //     //     $carsQuery = $carsQuery->where(function ($q) use ($result) {
            //     //         $q->whereBetween('start_price_num', [$result['start_price_num'], $result['end_price_num']])
            //     //           ->orWhereBetween('end_price_num', [$result['start_price_num'], $result['end_price_num']]);
            //     //     });
            //     // }  
                
            // }  
    }

    

    if ($request->search) {
        // if(Session::get('front_lang') == '')
        // $carsQuery->whereHas('front_translate', function ($query) use ($request) {
            $carsQuery->where(function($query) use ($request) {
                $query->where('model_name_en', 'like', '%' . $request->search . '%')
                      ->orWhere('company_en', 'like', '%' . $request->search . '%');
            });
        // });
    }

    if ($request->sort_by) {
        switch ($request->sort_by) {
            case 'price_low_high':
                $carsQuery->orderBy('auct_lots_xml_jp_op.start_price_num', 'asc');
                break;
            case 'price_high_low':
                $carsQuery->orderBy('auct_lots_xml_jp_op.start_price_num', 'desc');
                break;
            case 'old_to_new':
                $carsQuery->orderBy('auct_lots_xml_jp_op.model_year_en', 'asc');
                break;
            case 'new_to_old':
                $carsQuery->orderBy('auct_lots_xml_jp_op.model_year_en', 'desc');
                break;    
            case 'recent':
                $recentCarIds = $carsQuery->orderBy('auct_lots_xml_jp_op.id', 'desc')
                ->limit(100)
                ->pluck('auct_lots_xml_jp_op.id');
            
            // Then reset the query and use these IDs
            $carsQuery = $carsQuery->whereIn('auct_lots_xml_jp_op.id', $recentCarIds);
                break;
        }
    }

  
    // Pagination
        $price_range_counts = $this->getPriceRangeCounts(
            $carsQuery, 
            $hasPriceRangeScale, 
            $startValue, 
            $endValue,
            '',
        );



        // $carsQuery->orderBy('id', 'desc')
        // ->select('auct_lots_xml_jp_op.*')->get();
  

    // dd(DB::getQueryLog($carsQuery));
    // $price_range = $this->getPriceRangeCounts();
    // ->orderBy('id','desc')y
    // ->paginate(12);
    
    if(!$request->sort_by){
        $carsQuery->orderBy('auct_lots_xml_jp_op.id', 'desc');
    }

    // $cars =$carsQuery->where('auct_lots_xml_jp_op.active_status','1')
    // ->select('auct_lots_xml_jp_op.*')->get();

    // dd(DB::getQueryLog());

    $cars =$carsQuery->where('auct_lots_xml_jp_op.active_status','1')
    ->select('auct_lots_xml_jp_op.*')->paginate(12);


    



    // Transform cars into an array for the view
    $cars_array = $cars->map(function ($car) {
    // $car_image=$this->last_image($car->pictures);
    $car_image=$car_image =$this->last_image($car->pictures);
    $imageUrl='uploads/website-images/no-image.jpg';
    if(count($car_image)> 0){
        if ($this->isImageAvailable($car_image[0])) {
            $imageUrl= $car_image[0];
        } else {
            $imageUrl='uploads/website-images/no-image.jpg';
        }
    }
        return [
            'company_en' => $car->company_en,
            'company' => $car->company,
            'model_name' => $car->model_name,
            'model_name_en' => $car->model_name_en,
            'start_price' => $car->start_price,
            'start_price_num' => $this->convertCurrency($car->start_price_num, $this->usdRate),
            'end_price' => $car->end_price,
            'end_price_num' => $this->convertCurrency($car->end_price_num, $this->usdRate),
            'picture' =>$imageUrl,
            'id' => $car->id,
            'mileage' => $car->mileage,
            'mileage_en' => $car->mileage_en,
            'year'=>$car->model_year,
            'year_en'=>$car->model_year_en,
            'transmission'=>$car->transmission,
            'transmission_en'=>$car->transmission_en,
            'parsed_data'=>$car->parsed_data_en,
            'datetime'=>$car->datetime    
        ];
    });


 
    // Get additional data
    $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();
    // $cities = City::with('translate')->get();
    // $features = Feature::with('translate')->get();

    $brand_count = CarDataJpOp::selectRaw('company_en,company,COUNT(*) as count')
        ->groupBy('company_en')
        ->having('count', '>', 1)
        ->get();

    // $transmission = CarDataJpOp::selectRaw('transmission_en, COUNT(*) as count')
    //     ->groupBy('transmission_en')
    //     ->having('count', '>', 1)
    //     ->get();

    // $scores = CarDataJpOp::selectRaw('scores_en, COUNT(*) as count')
    //     ->groupBy('scores_en')
    //     ->having('count', '>', 1)
    //     ->get();


    // echo json_encode($price_range);die();

    // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
    //     ->join('brand_translations as bt','bt.brand_id','=','b.id')
    //     ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();


    // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();

    // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();
    $jdm_core_brand = Brand::where('status', 'enable')->get();

    // $jdm_brand['car']=$jdm_legend;
    // $jdm_brand['heavy']=$jdm_legend_heavy;
    // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
    // $jdm_brand=$this->jdm_brands();

    $wishlists=[];
    if(Auth::guard('web')->check()){
        $userId = $userId ?? auth()->id();
        $wishlists=CarDataJpOp::join('wishlists as w', 'auct_lots_xml_jp_op.id', '=', 'w.car_id')
        // $wishlistItems = \App\Models\Wishlist::where('user_id', $userId)
            ->where('w.table_id', '1')
            ->pluck('w.car_id')
            ->toArray();
    }


    return view('car-listing', [
        'seo_setting' => $seo_setting,
        'brands' => $brands,
        // 'cities' => $cities,
        'jdm_core_brand'=>$jdm_core_brand,
        // 'features' => $features,
        'cars_array' => $cars_array,
        'listing_ads' => $listing_ads,
        'cars' => $cars,
        'brand_count' => $brand_count,
        'price_range' => $price_range_counts,
        // 'transmission' => $transmission,
        // 'scores' => $scores,
        // 'jdm_legend'=>$jdm_brand,
        'models'=>$models,
        'brand_arr'=>$brand_list,
        'minYear'=>$minYear,
        'maxYear'=>$maxYear,
        'minPrice'=>$minPrice,
        'maxPrice'=>$maxPrice,
        'wishlists'=>$wishlists
        // 'jdm_legend_heavy'=>$jdm_legend_heavy,
        // 'jdm_legend_small_heavy'=>$jdm_legend_small_heavy
    ]);
    }

    public function carListingBrandNew(Request $request){
        $seo_setting = SeoSetting::where('id', 1)->first();
        
        $minYear = now()->subYear()->year;
        $maxYear = now()->year;
        // $brands = CarDataJpOp::join('brands as b', DB::raw('LOWER(auct_lots_xml_jp_op.company_en)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as name')
        // ->distinct('b.slug')->get();

        $brands=CarDataJpOp::select(
            'company_en as name',
            \DB::raw('LOWER(company_en) as slug')
        )
        ->whereBetween('model_year_en', [$minYear,$maxYear])
        ->groupBy('company_en')
        ->get();

        $keyWhere ="";
        $tableName='1';
        $brand_list=$this->getBrandsWithModels($keyWhere,$tableName,'brand-new');
        $models=[];

        $yearRange = CarDataJpOp::where('active_status', '1')
        ->selectRaw('MIN(model_year_en) as min_year, MAX(model_year_en) as max_year')
        ->whereBetween('model_year_en', [$minYear,$maxYear])
        ->first();
        $priceRange = CarDataJpOp::where('active_status', '1')
        ->selectRaw('MIN(start_price_num) as min_sal, MAX(start_price_num) as max_sal')
        ->whereBetween('model_year_en', [$minYear,$maxYear])
        ->first();


       

        // $minPrice = $priceRange->min_sal;
        $minPrice = $this->convertCurrency($priceRange->min_sal, $this->usdRate);
        // $maxPrice = $priceRange->max_sal;
        $maxPrice = $this->convertCurrency($priceRange->max_sal, $this->usdRate);

        $hasPriceRangeScale = false;
        $startValue = $minPrice;
        $endValue = $maxPrice;



    
    
    $carsQuery = CarDataJpOp::query();

    // $carsQuery->join('brands as b', DB::raw('LOWER(auct_lots_xml_jp_op.company_en)'), '=', 'b.slug')
    // ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
    // ->where('bt.lang_code', Session::get('front_lang'));

    // Apply filters based on request parameters
   

    if($request->price_range_scale){
        if($request->price_range_scale !=""){  
            $hasPriceRangeScale = true;
            $parts = explode(',', $request->price_range_scale);
            $start_value=trim($parts[0]);
            $end_value=trim($parts[1]);

            $startValue = ($start_value *  $this->usdRate);
            $endValue = ($end_value *  $this->usdRate);
            
                if($start_value ==($minPrice || $maxPrice)){
                    if($start_value == $minPrice){
                    $startValue=$priceRange->min_sal;
                    } else if($start_value == $maxPrice){
                        $startValue=$priceRange->max_sal;
                    }
                }
                if($end_value ==($minPrice || $maxPrice)){
                    if($end_value == $minPrice){
                    $endValue=$priceRange->min_sal;
                    } else if($end_value == $maxPrice){
                    $endValue=$priceRange->max_sal;
                    }
                }   
            $carsQuery = $carsQuery->where(function ($q) use ($startValue,$endValue) {
                $q->whereBetween('start_price_num', [$startValue, $endValue]);
            });
        }
    }



    if ($request->brand) {
        $brand_arr = array_filter($request->brand); // Filter out any empty values
        if ($brand_arr) {
            $carsQuery->whereIn(DB::raw('LOWER(company_en)'), $brand_arr); 
            // $carsQuery->where(DB::raw('LOWER(company_en)'), $request->brand); 
            $models = \DB::table('auct_lots_xml_jp_op')
            ->whereIn(DB::raw('LOWER(company_en)'), $request->brand)
            ->groupBy('model_name_en') 
            ->select('model_name_en')
            ->get();
        }    
    }

    if($request->model){
        $model_arr = [];
        foreach ($request->model as $brandSlug => $models) {
            if (is_array($models)) {
                $model_arr = array_merge($model_arr, array_filter($models)); // Flatten the nested array
            }
        }
        if ($model_arr) {
            $carsQuery->whereIn('model_name_en', $model_arr);
        }
        // $model_arr = array_filter($request->model); // Filter out any empty values
        // if ($model_arr) {
        //     $carsQuery->whereIn('model_name_en', $model_arr); 
        // }
    }


    if($request->year){
        if($request->year !=""){  
            $hasPriceRangeScale = true;
            $parts = explode(',', $request->year);
            $startYear=trim($parts[0]);
            $endYear=trim($parts[1]);

            $carsQuery = $carsQuery->where(function ($q) use ($startYear,$endYear) {
                // $q->whereBetween('start_price_num', [$startValue, $endValue])
                // ->orWhereBetween('end_price_num', [$startValue, $endValue]);
                $q->whereBetween('model_year_en', [($startYear), ($endYear)]);
            });
        }
        // if($request->year !="")
        // {
        //     $carsQuery->where('model_year_en', $request->year); 
        // }
    }

    if($request->price_range){
        $priceRanges = [
            "Under $5000" => ["start" => 0, "end" => (5000 * $this->usdRate)],
            "$5000 - $50000" => ["start" => (5001 * $this->usdRate), "end" => (50000 * $this->usdRate)],
            "$50000 - $100000" => ["start" => (50001 * $this->usdRate), "end" => (100000 * $this->usdRate)],
            "$100000 - $200000" => ["start" => (100001 * $this->usdRate), "end" => (200000 * $this->usdRate)],
            "$200000 - $300000" => ["start" => (200001 * $this->usdRate), "end" => (300000 * $this->usdRate)],
            "Above $300000" => ["start" => (300001 * $this->usdRate), "end" => PHP_INT_MAX] // Use PHP_INT_MAX for "Above"
        ];

  


        $carsQuery->where(function ($query) use ($request, $priceRanges) {
            foreach ($request->price_range as $range) {
                $result = $this->getPriceRangestart($range, $priceRanges);
    
                if ($result['start_price_num'] !== null && $result['end_price_num'] !== null) {
                    $startValue = (int)trim(str_replace('"', '', $result['start_price_num'],));
                    $endValue = (int)trim(str_replace('"', '', $result['end_price_num']));
                    // Group the conditions for this range
                    $query->orWhere(function ($subQuery) use ($result,$startValue,$endValue) {
                        // $subQuery->whereBetween('start_price_num', [$result['start_price_num'], $result['end_price_num']]);
                        $subQuery->whereBetween('start_price_num', [$startValue, $endValue]);
                    });
                }
            }
        }); 
    }

    

    if ($request->search) {
        // if(Session::get('front_lang') == '')
        // $carsQuery->whereHas('front_translate', function ($query) use ($request) {
            $carsQuery->where(function($query) use ($request) {
                $query->where('model_name_en', 'like', '%' . $request->search . '%')
                      ->orWhere('company_en', 'like', '%' . $request->search . '%');
            });
        // });
    }

    if ($request->sort_by) {
        switch ($request->sort_by) {
            case 'price_low_high':
                $carsQuery->orderBy('auct_lots_xml_jp_op.start_price_num', 'asc');
                break;
            case 'price_high_low':
                $carsQuery->orderBy('auct_lots_xml_jp_op.start_price_num', 'desc');
                break;
            case 'old_to_new':
                $carsQuery->orderBy('auct_lots_xml_jp_op.model_year_en', 'asc');
                break;
            case 'new_to_old':
                $carsQuery->orderBy('auct_lots_xml_jp_op.model_year_en', 'desc');
                break;    
            case 'recent':
                $recentCarIds = $carsQuery->orderBy('auct_lots_xml_jp_op.id', 'desc')
                ->limit(100)
                ->pluck('auct_lots_xml_jp_op.id');
            
            // Then reset the query and use these IDs
            $carsQuery = $carsQuery->whereIn('auct_lots_xml_jp_op.id', $recentCarIds);
                break;
        }
    }

  
    // Pagination
        $price_range_counts = $this->getPriceRangeCounts(
            $carsQuery, 
            $hasPriceRangeScale, 
            $startValue, 
            $endValue,
            'brand-new',
        );
    
    $carsQuery->whereBetween('model_year_en', [$minYear,$maxYear]);

    if(!$request->sort_by){
        $carsQuery->orderBy('auct_lots_xml_jp_op.id', 'desc');
    }

    $cars =$carsQuery->where('auct_lots_xml_jp_op.active_status','1')
    ->select('auct_lots_xml_jp_op.*')->paginate(12);

    // Transform cars into an array for the view
    $cars_array = $cars->map(function ($car) {
    // $car_image=$this->last_image($car->pictures);
    $car_image=$car_image =$this->last_image($car->pictures);
    $imageUrl='uploads/website-images/no-image.jpg';
    if(count($car_image)> 0){
        if ($this->isImageAvailable($car_image[0])) {
            $imageUrl= $car_image[0];
        } else {
            $imageUrl='uploads/website-images/no-image.jpg';
        }
    }

                    
        return [
            'company_en' => $car->company_en,
            'company' => $car->company,
            'model_name' => $car->model_name,
            'model_name_en' => $car->model_name_en,
            'start_price' => $car->start_price,
            'start_price_num' =>$this->convertCurrency($car->start_price_num, $this->usdRate),
            'end_price' => $car->end_price,
            'end_price_num' =>$this->convertCurrency($car->end_price_num, $this->usdRate),
            'picture' =>$imageUrl,
            'id' => $car->id,
            'mileage' => $car->mileage,
            'mileage_en' => $car->mileage_en,
            'year'=>$car->model_year,
            'year_en'=>$car->model_year_en,
            'transmission'=>$car->transmission,
            'transmission_en'=>$car->transmission_en,
            'parsed_data'=>$car->parsed_data_en,
            'datetime'=>$car->datetime    
        ];
    });


 
    // Get additional data
    $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();
    $brand_count = CarDataJpOp::selectRaw('company_en,company,COUNT(*) as count')
        ->groupBy('company_en')
        ->having('count', '>', 1)
        ->get();
    $jdm_core_brand = Brand::where('status', 'enable')->get();
    $wishlists=[];
    if(Auth::guard('web')->check()){
        $userId = $userId ?? auth()->id();
        $wishlists=CarDataJpOp::join('wishlists as w', 'auct_lots_xml_jp_op.id', '=', 'w.car_id')
        // $wishlistItems = \App\Models\Wishlist::where('user_id', $userId)
            ->where('w.table_id', '1')
            ->pluck('w.car_id')
            ->toArray();
    }


    return view('car-listing-brand-new-cars', [
        'seo_setting' => $seo_setting,
        'brands' => $brands,
        // 'cities' => $cities,
        'jdm_core_brand'=>$jdm_core_brand,
        // 'features' => $features,
        'cars_array' => $cars_array,
        'listing_ads' => $listing_ads,
        'cars' => $cars,
        'brand_count' => $brand_count,
        'price_range' => $price_range_counts,
        // 'transmission' => $transmission,
        // 'scores' => $scores,
        // 'jdm_legend'=>$jdm_brand,
        'models'=>$models,
        'brand_arr'=>$brand_list,
        'minYear'=>$minYear,
        'maxYear'=>$maxYear,
        'minPrice'=>$minPrice,
        'maxPrice'=>$maxPrice,
        'wishlists'=>$wishlists
        // 'jdm_legend_heavy'=>$jdm_legend_heavy,
        // 'jdm_legend_small_heavy'=>$jdm_legend_small_heavy
    ]);
    }


    function isImageAvailable($url) {
        $headers = @get_headers($url);
        return $headers && strpos($headers[0], '200') !== false; // Check for 200 OK response
    }

    public function getPriceRangeCounts($baseQuery, $hasRangeFilter = false, $startValue = null, $endValue = null,$param) {
        $price_ranges = [
            'Under $5000' => ['min' => 0, 'max' => (5000 * $this->usdRate)],
            '$5000 - $50000' => ['min' => (5001 * $this->usdRate), 'max' => (50000 * $this->usdRate)],
            '$50000 - $100000' => ['min' => (50001 * $this->usdRate), 'max' => (100000 * $this->usdRate)],
            '$100000 - $200000' => ['min' => (100001 * $this->usdRate), 'max' => (200000 * $this->usdRate)],
            '$200000 - $300000' => ['min' => (200001 * $this->usdRate), 'max' => (300000 * $this->usdRate)],
            'Above $300000' => ['min' => (300001 * $this->usdRate), 'max' => PHP_INT_MAX],
        ];
            // DB::enableQueryLog();

        // foreach ($price_ranges as $label => $range) {
        //     // Clone the base query to avoid modifying the original
        //     $query = CarDataJpOp::query();

        //     $query->join('brands as b', DB::raw('LOWER(auct_lots_xml_jp_op.company_en)'), '=', 'b.slug')
        //     ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //     ->where('bt.lang_code',Session::get('front_lang'));
    
        //     if ($hasRangeFilter) {
        //         // Apply the price_range_scale filter if it's enabled
        //         $query->where(function ($q) use ($startValue, $endValue) {
        //             $q->whereBetween('start_price_num', [$startValue, $endValue])
        //               ->orWhereBetween('end_price_num', [$startValue, $endValue]);
        //         })
        //         ->when($param == 'top-selling', function($query) {
        //             return $query->where('top_sell', 1);
        //         })
        //         ->when($param == 'new_arrival', function($query) {
        //             return $query->where('new_arrival', 1);
        //         });
        //     }
            
        //     // Then apply the range counting logic
        //     if ($range['max'] === null) {
        //         // For "Above" range
        //         $count = $query->where(function($q) use ($range) {
        //             $q->where('start_price_num', '>', $range['min'])
        //               ->orWhere('end_price_num', '>', $range['min']);
        //         })
        //         ->when($param == 'top-selling',function($query){
        //             return $query->where('top_sell',1);
        //         })
        //         ->when($param == 'new_arrival', function($query) {
        //             return $query->where('new_arrival', 1);
        //         })
        //         ->count();
        //     } else {
        //         // For other ranges
        //         $count = $query->where(function($q) use ($range) {
        //             $q->whereBetween('start_price_num', [$range['min'], $range['max']])
        //               ->orWhereBetween('end_price_num', [$range['min'], $range['max']]);
        //         })
        //         ->when($param == 'top-selling',function($query){
        //             return $query->where('top_sell',1);
        //         })
        //         ->when($param == 'new_arrival', function($query) {
        //             return $query->where('new_arrival', 1);
        //         })
        //         ->count();
        //     }


    
        //     $counts[$label] = $count;
        // }
    
        $counts = [];
        $priceCount=[];

        if($hasRangeFilter){
            foreach ($price_ranges as $label => $range) {    
                $query =DB::table('auct_lots_xml_jp_op as t') // Alias the table dynamically
                // ->join('brands as b', DB::raw('LOWER(t.company_en)'), '=', 'b.slug')
                // ->join('brand_translations as bt','bt.brand_id','=','b.id')
                // ->where('bt.lang_code',Session::get('front_lang'))
                ->leftJoin('auct_lots_xml_jp_op_other_chargers as oc', 'oc.auct_id', '=','t.id')
                ->where('active_status','1')
                ->when($param == 'top-selling',function($query){
                    return $query->where('oc.top_sell',1);
                })
                ->when($param == 'new-arrival', function($query) {
                    return $query->where('oc.new_arrival', 1);
                })
                ->when($param == 'brand-new', function($query) {
                    return $query->whereBetween('model_year_en', [now()->subYear()->year,now()->year]);
                })
                ->where(function ($q) use ($startValue, $endValue) {
                    // Apply the budget filter on start_price_num and end_price_num
                    $q->whereBetween('start_price_num', [$startValue, $endValue]);
                    //   ->orWhereBetween('end_price_num', [$startValue, $endValue]);
                });
                                  
                $count = $query->where(function ($q) use ($range) {
                    $q->whereBetween('start_price_num', [$range['min'], $range['max']]);
                    //   ->orWhereBetween('end_price_num', [$range['min'], $range['max']]);
                })->count();
                // Store the count for the current range
                $priceCount[$label] = $count;
            }
        } else {
            foreach ($price_ranges as $label => $range) {    
                // DB::enableQueryLog();
                $query =DB::table('auct_lots_xml_jp_op as t') // Alias the table dynamically
                // ->join('brands as b', DB::raw('LOWER(t.company_en)'), '=', 'b.slug')
                // ->join('brand_translations as bt','bt.brand_id','=','b.id')
                // ->where('bt.lang_code',Session::get('front_lang')) 
                ->leftJoin('auct_lots_xml_jp_op_other_chargers as oc', 'oc.auct_id', '=','t.id')
                ->where('active_status','1')     
                ->when($param == 'top-selling',function($query){
                    return $query->where('oc.top_sell',1);
                })
                ->when($param == 'new-arrival', function($query) {
                    return $query->where('oc.new_arrival', 1);
                }) 
                ->when($param == 'brand-new', function($query) {
                    return $query->whereBetween('model_year_en', [now()->subYear()->year,now()->year]);
                });       
                $count = $query->where(function ($q) use ($range) {
                    $q->whereBetween('start_price_num', [$range['min'], $range['max']]);
                    //   ->orWhereBetween('end_price_num', [$range['min'], $range['max']]);
                })->count();
                // dd(DB::getQueryLog());
                // Store the count for the current range
                $priceCount[$label] = $count;
            }
        }
    
        // DB::enableQueryLog();
        

        // dd(DB::getQueryLog());

        return $priceCount;
    
        // return $counts;
    }
    
    public function getAuctionPriceRangeCounts($baseQuery, $hasRangeFilter = false, $startValue = null, $endValue = null,$param) {
       
        $usdRate = Cache::get('usd_to_jpy_rate');
        $price_ranges = [
            'Under $5000' => ['min' => 0, 'max' => (5000 * $usdRate)],
            '$5000 - $50000' => ['min' => (5001 * $usdRate), 'max' => (50000 * $usdRate)],
            '$50000 - $100000' => ['min' => (50001 * $usdRate), 'max' => (100000 * $usdRate)],
            '$100000 - $200000' => ['min' => (100001 * $usdRate), 'max' => (200000 * $usdRate)],
            '$200000 - $300000' => ['min' => (200001 * $usdRate), 'max' => (300000 * $usdRate)],
            'Above $300000' => ['min' => (300001 * $usdRate), 'max' => PHP_INT_MAX],
        ];
    
        $counts = [];
        $priceCount=[];
    
        // foreach ($price_ranges as $label => $range) {
         
        //     // DB::enableQueryLog();
        //     // Clone the base query to avoid modifying the original
        //     $query = Auct_lots_xml_jp::query();
    
        //     if ($hasRangeFilter) {

        //         // Apply the price_range_scale filter if it's enabled
        //         $query->where(function ($q) use ($startValue, $endValue) {
        //             $q->whereBetween('start_price_num', [$startValue, $endValue])
        //               ->orWhereBetween('end_price_num', [$startValue, $endValue]);
        //         });
        //     }
            
        //     // Then apply the range counting logic
        //     if ($range['max'] === null) {
        //         // For "Above" range
        //         $count = $query->where(function($q) use ($range) {
        //             $q->where('start_price_num', '>', $range['min'])
        //               ->orWhere('end_price_num', '>', $range['min']);
        //         })
        //         ->count();
        //     } else {
        //         // For other ranges
        //         $count = $query->where(function($q) use ($range) {
        //             $q->whereBetween('start_price_num', [$range['min'], $range['max']])
        //               ->orWhereBetween('end_price_num', [$range['min'], $range['max']]);
        //         })
        //         ->count();
        //     }
            
    
        //     $counts[$label] = $count;
        // }

        if($hasRangeFilter){
            foreach ($price_ranges as $label => $range) {    
                $query =DB::table('auct_lots_xml_jp as t') // Alias the table dynamically
                // ->join('brands as b', DB::raw('LOWER(t.company_en)'), '=', 'b.slug')
                // ->join('brand_translations as bt','bt.brand_id','=','b.id')
                // ->where('bt.lang_code',Session::get('front_lang'))
                // ->where(function ($q) use ($startValue, $endValue) {
                //     // Apply the budget filter on start_price_num and end_price_num
                //     $q->whereBetween('start_price_num', [$startValue, $endValue]);
                // });
                ->where(function ($q) use ($startValue, $endValue) {
                    // Apply the budget filter on start_price_num and end_price_num
                    $q->whereBetween(DB::raw('COALESCE(t.start_price_num, 0)'), [$startValue, $endValue]);
                })
                ->when($param == 'auction-brand-new', function($query) {
                    return $query->whereBetween('model_year_en', [now()->subYear()->year,now()->year]);;
                }); 
                $count = $query->where(function ($q) use ($range) {
                    // Apply the price range filter using COALESCE for null handling
                    $q->whereBetween(DB::raw('COALESCE(t.start_price_num, 0)'), [$range['min'], $range['max']]);
                })->count();
                                  
                // $count = $query->where(function ($q) use ($range) {
                //     $q->whereBetween('start_price_num', [$range['min'], $range['max']]);
                //     //   ->orWhereBetween('end_price_num', [$range['min'], $range['max']]);
                // })->count();
                // Store the count for the current range
                $priceCount[$label] = $count;
            }
        } else {
        
            foreach ($price_ranges as $label => $range) {    
                // DB::enableQueryLog();
                $query =DB::table('auct_lots_xml_jp as t') // Alias the table dynamically
                // ->join('brands as b', DB::raw('LOWER(t.company_en)'), '=', 'b.slug')
                // ->join('brand_translations as bt','bt.brand_id','=','b.id')
                // ->where('bt.lang_code',Session::get('front_lang'))
                ->when($param == 'auction-brand-new', function($query) {
                    return $query->whereBetween('model_year_en', [now()->subYear()->year,now()->year]);;
                }); 

                $count = $query->where(function ($q) use ($range) {
                    $q->whereBetween(DB::raw('COALESCE(t.start_price_num, 0)'), [$range['min'], $range['max']]);
                })->count();          
                // $count = $query->where(function ($q) use ($range) {
                //     $q->whereBetween('start_price_num', [$range['min'], $range['max']]);
                //     //   ->orWhereBetween('end_price_num', [$range['min'], $range['max']]);
                // })->count();
                // dd(DB::getQueryLog());
                $priceCount[$label] = $count;
            }
        }
    
        return $priceCount; 
    }

    public function getBrandModels(Request $request){
        $models = \DB::table('auct_lots_xml_jp_op')
            ->where(DB::raw('LOWER(company_en)'), $request->brand)
            ->groupBy('model_name_en') 
            ->select('model_name_en')
            ->get();
        return response()->json(['status'=>true,'message'=>$models]);    
    }
    public function top_selling(Request $request){

        $seo_setting = SeoSetting::where('id', 1)->first();
        // $brands = Brand::where('status', 'enable')->get();
        $brands = CarDataJpOp::join('brands as b', DB::raw('LOWER(auct_lots_xml_jp_op.company_en)'), '=', 'b.slug')
        ->join('brand_translations as bt','bt.brand_id','=','b.id')
        ->where('bt.lang_code',Session::get('front_lang'))
        ->select('b.slug','bt.name as name')
        ->where('auct_lots_xml_jp_op.top_sell',1)
        ->distinct('b.slug')->get();
        $models=[];


 
        // Initialize the query for cars
        $carsQuery = CarDataJpOp::query();

      
        // Apply filters based on request parameters
        if ($request->location) {
            $carsQuery->where('city_id', $request->location);
        }

        if($request->brand_new_cars){
            $year = date('Y'); 
             $carsQuery->where('model_year_en', 'LIKE', $year . '%');    
        }




      

        if($request->price_range_scale){
            if($request->price_range_scale !=""){  
                $parts = explode('-', $request->price_range_scale);
                $startValue = trim($parts[0]);
                $endValue = trim($parts[1]);
                $carsQuery = $carsQuery->where(function ($q) use ($startValue,$endValue) {
                    $q->whereBetween('start_price_num', [$startValue, $endValue])
                    ->orWhereBetween('end_price_num', [$startValue, $endValue]);
                });
            }
        }
        if ($request->tranmission) {
            $transmission_arr = array_filter($request->transmission_arr); // Filter out any empty values
            if ($transmission_arr) {
                $carsQuery->whereIn('transmission_en', $transmission_arr); 
            }    
        }

        if($request->year){
            if($request->year !="")
            {
                $carsQuery->where('model_year_en', $request->year); 
            }
        }

        if($request->price_range){

            $priceRanges = [
                "Under $5000" => ["start" => 0, "end" => 5000],
                "$5000 - $50000" => ["start" => 5000, "end" => 50000],
                "$50000 - $100000" => ["start" => 50000, "end" => 100000],
                "$100000 - $200000" => ["start" => 100000, "end" => 200000],
                "$200000 - $300000" => ["start" => 200000, "end" => 300000],
                "Above $300000" => ["start" => 300000, "end" => null] // Use PHP_INT_MAX for "Above"
            ];



            $result = $this->getPriceRangestart($request->price_range, $priceRanges);

        

    
            if (is_null($result['end_price_num'])) {
                // Count for "Above" range
                $carsQuery = $carsQuery->where(function ($q) use ($result) {
                    $q->where('start_price_num', '>', $result['start_price_num'])  // Greater than start price
                      ->orWhere('end_price_num', '>', $result['start_price_num']); // Greater than end price
                });
            } else {
                // Count for other ranges
                $carsQuery = $carsQuery->where(function ($q) use ($result) {
                    $q->whereBetween('start_price_num', [$result['start_price_num'], $result['end_price_num']])
                    ->orWhereBetween('end_price_num', [$result['start_price_num'], $result['end_price_num']]);
                });
            }  
        }

        if ($request->scores_en) {
            $score_arr = array_filter($request->scores_en); // Filter out any empty values
            if ($score_arr) {
                $carsQuery->whereIn('scores_en', $score_arr); 
            }
        }



        if ($request->condition) {
            $carsQuery->whereIn('condition', $request->condition);
        }

        if ($request->purpose) {
            $purpose_arr = array_filter($request->purpose);
            if ($purpose_arr) {
                $carsQuery->whereIn('purpose', $purpose_arr);
            }
        }

        if ($request->features) {
            $carsQuery->whereJsonContains('features', $request->features);
        }

        if ($request->price_filter) {
            if ($request->price_filter === 'low_to_high') {
                $carsQuery->orderBy('regular_price', 'asc');
            } elseif ($request->price_filter === 'high_to_low') {
                $carsQuery->orderBy('regular_price', 'desc');
            }
        }

        if ($request->search) {
            if($request->search!=""){
                // $carsQuery->whereHas('front_translate', function ($query) use ($request) {
                        $carsQuery->where('model_name_en', 'like', '%' . $request->search . '%');
            // });
        }
            // if(Session::get('front_lang') == '')
            // $carsQuery->whereHas('front_translate', function ($query) use ($request) {
            //     $query->where('title', 'like', '%' . $request->search . '%');
            //         //   ->orWhere('description', 'like', '%' . $request->search . '%');
            // });
        }
        
        if ($request->brand) {
           
            // DB::enableQueryLog();
            $carsQuery->where(DB::raw('LOWER(company_en)'), $request->brand); 
            $models = \DB::table('auct_lots_xml_jp_op')
            ->where(DB::raw('LOWER(company_en)'), $request->brand)
            ->where('top_sell','1')
            ->groupBy('model_name_en') 
            ->select('model_name_en')
            ->get();

            // echo json_encode($models);die();
    }

    if($request->model){
        $carsQuery->where('model_name_en', $request->model); 
    }

    if ($request->sort_by) {
        switch ($request->sort_by) {
            case 'price_low_high':
                $carsQuery->orderBy('start_price_num', 'asc');
                break;
            case 'price_high_low':
                $carsQuery->orderBy('start_price_num', 'desc');
                break;
            case 'recent':  
                $recentCarIds = $carsQuery->orderBy('id', 'desc')
                ->limit(100)
                ->pluck('id');
            
            // Then reset the query and use these IDs
            $carsQuery = $carsQuery->whereIn('id', $recentCarIds);
                break;
        }
    }

    

        // Pagination
        $cars = $carsQuery->where('top_sell','1')->where('active_status','1')->paginate(12);

        // Transform cars into an array for the view
        $cars_array = $cars->map(function ($car) {
        $car_image=$this->last_image($car->pictures);
            return [
                'company_en' => $car->company_en,
                'company' => $car->company,
                'model_name' => $car->model_name,
                'model_name_en' => $car->model_name_en,
                'start_price' => $car->start_price,
                'start_price_num' => $car->start_price_num,
                'end_price' => $car->end_price,
                'end_price_num' => $car->end_price_num,
                'picture' =>$car_image[0],
                'id' => $car->id,
                'mileage' => $car->mileage,
                'mileage_en' => $car->mileage_en,
            ];
        });


        // Get additional data
        $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();
        $cities = City::with('translate')->get();
        $features = Feature::with('translate')->get();

        $brand_count = CarDataJpOp::selectRaw('company_en,company,COUNT(*) as count')
            ->groupBy('company_en')
            ->having('count', '>', 1)
            ->get();

        $transmission = CarDataJpOp::selectRaw('transmission_en, COUNT(*) as count')
            ->groupBy('transmission_en')
            ->having('count', '>', 1)
            ->get();

        $scores = CarDataJpOp::selectRaw('scores_en, COUNT(*) as count')
            ->groupBy('scores_en')
            ->having('count', '>', 1)
            ->get();

        $price_range = $this->getPriceRange();

    //     $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
    //     ->join('brand_translations as bt','bt.brand_id','=','b.id')
    //     ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();


    // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();

    // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();
    $jdm_core_brand = Brand::where('status', 'enable')->get();

    // $jdm_brand['car']=$jdm_legend;
    // $jdm_brand['heavy']=$jdm_legend_heavy;
    // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
    // $jdm_brand=$this->jdm_brands();

        return view('top-ratings', [
            'seo_setting' => $seo_setting,
            'brands' => $brands,
            'cities' => $cities,
            'features' => $features,
            'cars_array' => $cars_array,
            'listing_ads' => $listing_ads,
            'cars' => $cars,
            'brand_count' => $brand_count,
            'price_range' => $price_range,
            'transmission' => $transmission,
            'scores' => $scores,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'>$jdm_core_brand,
            'models'=>$models
        ]);
    }
    public function top_selling1(Request $request){
        $wishlists=[];
        if(Auth::guard('web')->check()){
            $userId = $userId ?? auth()->id();
            $wishlists=CarDataJpOp::join('wishlists as w', 'auct_lots_xml_jp_op.id', '=', 'w.car_id')
            // $wishlistItems = \App\Models\Wishlist::where('user_id', $userId)
                ->where('w.table_id', '1')
                ->pluck('w.car_id')
                ->toArray();
        }
       
      
       
        $seo_setting = SeoSetting::where('id', 1)->first();
        // $brands = Brand::where('status', 'enable')->get();
        // $brands = CarDataJpOp::join('brands as b', DB::raw('LOWER(auct_lots_xml_jp_op.company_en)'), '=', 'b.slug')
        // // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','company_en as name')
        // ->where('auct_lots_xml_jp_op.top_sell',1)
        // ->distinct('b.slug')->get();
        $brands=CarDataJpOp::Join('auct_lots_xml_jp_op_other_chargers as oc','oc.auct_id','=','auct_lots_xml_jp_op.id')
        ->select(
            'company_en as name',
            \DB::raw('LOWER(company_en) as slug')
        )
        ->where('oc.top_sell',1)
        ->groupBy('company_en')
        ->get();
        $models=[];
        $keyWhere="top-sell";
        $tableName='1';
        $brand_list=$this->getBrandsWithModels($keyWhere,$tableName,'');
       
        // Initialize the query for cars
      

        
        $yearRange = CarDataJpOp::Join('auct_lots_xml_jp_op_other_chargers as oc','oc.auct_id','=','auct_lots_xml_jp_op.id')
        // where('active_status', '1')
        ->where('oc.top_sell','1')
        ->selectRaw('MIN(model_year_en) as min_year, MAX(model_year_en) as max_year')
        ->first();
        $priceRange = CarDataJpOp::Join('auct_lots_xml_jp_op_other_chargers as oc','oc.auct_id','=','auct_lots_xml_jp_op.id')
        // where('active_status', '1')
        ->where('oc.top_sell','1')
        ->selectRaw('MIN(start_price_num) as min_sal, MAX(start_price_num) as max_sal')
        ->first();
        $minYear = $yearRange->min_year;
        $maxYear = $yearRange->max_year;
        // $maxYear = Carbon::now()->year;
        // $minPrice = $priceRange->min_sal;
        $minPrice = $this->convertCurrency($priceRange->min_sal, $this->usdRate);
        // $maxPrice = $priceRange->max_sal;
        $maxPrice = $this->convertCurrency($priceRange->max_sal, $this->usdRate);

        $hasPriceRangeScale = false;
        $startValue = $minPrice;
        $endValue = $maxPrice;

        // DB::enableQueryLog();
        $carsQuery = CarDataJpOp::query();

        // $carsQuery->join('brands as b', DB::raw('LOWER(auct_lots_xml_jp_op.company_en)'), '=', 'b.slug')
        // ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
        // ->where('bt.lang_code', Session::get('front_lang'));

        $carsQuery->Join('auct_lots_xml_jp_op_other_chargers as oc','oc.auct_id','=','auct_lots_xml_jp_op.id');

      
        // Apply filters based on request parameters
        if ($request->location) {
            $carsQuery->where('city_id', $request->location);
        }

        if($request->brand_new_cars){
            $year = date('Y'); 
             $carsQuery->where('model_year_en', 'LIKE', $year . '%');    
        }




      

        if($request->price_range_scale){
            if($request->price_range_scale !=""){  
                $hasPriceRangeScale = true;
                $parts = explode(',', $request->price_range_scale);
                $start_value=trim($parts[0]);
                $end_value=trim($parts[1]);

                $startValue = ($start_value *  $this->usdRate);
                $endValue = ($end_value *  $this->usdRate);

                if($start_value ==($minPrice || $maxPrice)){
                    if($start_value == $minPrice){
                       $startValue=$priceRange->min_sal;
                    } else if($start_value == $maxPrice){
                          $startValue=$priceRange->max_sal;
                    }
                }
                if($end_value ==($minPrice || $maxPrice)){
                    if($end_value == $minPrice){
                       $endValue=$priceRange->min_sal;
                    } else if($end_value == $maxPrice){
                       $endValue=$priceRange->max_sal;
                    }
                }  

                $carsQuery = $carsQuery->where(function ($q) use ($startValue,$endValue) {
                    // $q->whereBetween('start_price_num', [$startValue, $endValue])
                    // ->orWhereBetween('end_price_num', [$startValue, $endValue]);
                    $q->whereBetween('start_price_num', [($startValue), ($endValue)]);
                });
            }
        }
        if ($request->tranmission) {
            $transmission_arr = array_filter($request->transmission_arr); // Filter out any empty values
            if ($transmission_arr) {
                $carsQuery->whereIn('transmission_en', $transmission_arr); 
            }    
        }

        if($request->year){

            if($request->year !=""){  
                $hasPriceRangeScale = true;
                $parts = explode(',', $request->year);
                $startYear=trim($parts[0]);
                $endYear=trim($parts[1]);
 
                $carsQuery = $carsQuery->where(function ($q) use ($startYear,$endYear) {
                    // $q->whereBetween('start_price_num', [$startValue, $endValue])
                    // ->orWhereBetween('end_price_num', [$startValue, $endValue]);
                    $q->whereBetween('model_year_en', [($startYear), ($endYear)]);
                });
            }
            // if($request->year !="")
            // {
            //     $carsQuery->where('model_year_en', $request->year); 
            // }
        }

        if($request->price_range){
            $priceRanges = [
                "Under $5000" => ["start" => 0, "end" => (5000 * $this->usdRate)],
                "$5000 - $50000" => ["start" => (5001* $this->usdRate), "end" => (50000 * $this->usdRate)],
                "$50000 - $100000" => ["start" => (50001 * $this->usdRate ), "end" => (100000 * $this->usdRate)],
                "$100000 - $200000" => ["start" => (100001 * $this->usdRate), "end" => (200000 * $this->usdRate)],
                "$200000 - $300000" => ["start" => (200001 * $this->usdRate), "end" => (300000 * $this->usdRate)],
                "Above $300000" => ["start" => (300001 * $this->usdRate), "end" => PHP_INT_MAX] // Use PHP_INT_MAX for "Above"
            ];

            $carsQuery->where(function ($query) use ($request, $priceRanges) {
                foreach ($request->price_range as $range) {
                    $result = $this->getPriceRangestart($range, $priceRanges);
        
                    if ($result['start_price_num'] !== null && $result['end_price_num'] !== null) {
                        // Group the conditions for this range
                        $query->orWhere(function ($subQuery) use ($result) {
                            $subQuery->whereBetween('start_price_num', [$result['start_price_num'], $result['end_price_num']]);
                                    //  ->orWhereBetween('end_price_num', [$result['start_price_num'], $result['end_price_num']]);
                        });
                    }
                }
            }); 



            // $result = $this->getPriceRangestart($request->price_range, $priceRanges);

            // if (is_null($result['end_price_num'])) {
            //     // Count for "Above" range
            //     $carsQuery = $carsQuery->where(function ($q) use ($result) {
            //         $q->where('start_price_num', '>', $result['start_price_num'])  // Greater than start price
            //           ->orWhere('end_price_num', '>', $result['start_price_num']); // Greater than end price
            //     });
            // } else {
            //     // Count for other ranges
            //     $carsQuery = $carsQuery->where(function ($q) use ($result) {
            //         $q->whereBetween('start_price_num', [$result['start_price_num'], $result['end_price_num']])
            //         ->orWhereBetween('end_price_num', [$result['start_price_num'], $result['end_price_num']]);
            //     });
            // }  
        }

      
        if ($request->search) {
            if($request->search!=""){
                // $carsQuery->whereHas('front_translate', function ($query) use ($request) {
                    $carsQuery->where(function($query) use ($request) {
                        $query->where('model_name_en', 'like', '%' . $request->search . '%')
                              ->orWhere('company_en', 'like', '%' . $request->search . '%');
                    });
            // });
        }
            
        }
        
        if ($request->brand) {
            $brand_arr = array_filter($request->brand); // Filter out any empty values
            if ($brand_arr) {
                $carsQuery->whereIn('company_en', $brand_arr); 
            // DB::enableQueryLog();
            // $carsQuery->where(DB::raw('LOWER(company_en)'), $request->brand);    
            // $models = \DB::table('auct_lots_xml_jp_op')
            // ->where(DB::raw('LOWER(company_en)'), $request->brand)
            // ->where('top_sell','1')
            // ->groupBy('model_name_en') 
            // ->select('model_name_en')
            // ->get();
            }

            // echo json_encode($models);die();
    }

    if($request->model){
        $model_arr = [];
        foreach ($request->model as $brandSlug => $models) {
            if (is_array($models)) {
                $model_arr = array_merge($model_arr, array_filter($models)); // Flatten the nested array
            }
        }
        if ($model_arr) {
            $carsQuery->whereIn('model_name_en', $model_arr);
        }
        // $model_arr = array_filter($request->model); // Filter out any empty values
        // if ($model_arr) {
        //     $carsQuery->whereIn('model_name_en', $model_arr);
        // }    
        // $carsQuery->where('model_name_en', $request->model); 
    }
    $carsQuery->where('oc.top_sell','1')->where('active_status','1');


    $price_range_counts = $this->getPriceRangeCounts(
        $carsQuery, 
        $hasPriceRangeScale, 
        $startValue, 
        $endValue,
        'top-selling',
    );

    if ($request->sort_by) {
        switch ($request->sort_by) {
            case 'price_low_high':
                $carsQuery->orderBy('auct_lots_xml_jp_op.start_price_num', 'asc');
                break;
            case 'price_high_low':
                $carsQuery->orderBy('auct_lots_xml_jp_op.start_price_num', 'desc');
                break;
            case 'old_to_new':
                $carsQuery->orderBy('auct_lots_xml_jp_op.model_year_en', 'asc');
                break;
            case 'new_to_old':
                $carsQuery->orderBy('auct_lots_xml_jp_op.model_year_en', 'desc');
                break;
            case 'recent':  
                $recentCarIds = $carsQuery->orderBy('auct_lots_xml_jp_op.id', 'desc')
                ->limit(100)
                ->pluck('auct_lots_xml_jp_op.id');
            
            // Then reset the query and use these IDs
            $carsQuery = $carsQuery->whereIn('auct_lots_xml_jp_op.id', $recentCarIds);
                break;
        }
    }

    // dd(DB::getQueryLog($carsQuery->get()));


    
    if(!$request->sort_by){
        $carsQuery->orderBy('auct_lots_xml_jp_op.id', 'desc');
    }

        // Pagination
        $cars = $carsQuery->select('auct_lots_xml_jp_op.*')->paginate(12);

        // Transform cars into an array for the view
        $cars_array = $cars->map(function ($car) {
        // $car_image=$this->last_image($car->pictures);
        $car_image=$car_image =$this->last_image($car->pictures);
        $imageUrl='uploads/website-images/no-image.jpg';
        if(count($car_image)> 0){
            if ($this->isImageAvailable($car_image[0])) {
                $imageUrl= $car_image[0];
            } else {
                $imageUrl='uploads/website-images/no-image.jpg';
            }
        }
            return [
                'company_en' => $car->company_en,
                'company' => $car->company,
                'model_name' => $car->model_name,
                'model_name_en' => $car->model_name_en,
                'start_price' => $car->start_price,
                'start_price_num' => $this->convertCurrency($car->start_price_num, $this->usdRate),
                'end_price' => $car->end_price,
                'end_price_num' => $this->convertCurrency($car->end_price_num, $this->usdRate),
                'picture' =>$imageUrl,
                'id' => $car->id,
                'mileage' => $car->mileage,
                'mileage_en' => $car->mileage_en,
                'year'=>$car->model_year,
                'year_en'=>$car->model_year_en,
                'transmission'=>$car->transmission,
                'transmission_en'=>$car->transmission_en,
                'parsed_data'=>$car->parsed_data_en,
                'datetime'=>$car->datetime 
            ];
        });


        // Get additional data
        $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();
        $cities = City::with('translate')->get();
        $features = Feature::with('translate')->get();

        $brand_count = CarDataJpOp::selectRaw('company_en,company,COUNT(*) as count')
            ->groupBy('company_en')
            ->having('count', '>', 1)
            ->get();

        $transmission = CarDataJpOp::selectRaw('transmission_en, COUNT(*) as count')
            ->groupBy('transmission_en')
            ->having('count', '>', 1)
            ->get();

        $scores = CarDataJpOp::selectRaw('scores_en, COUNT(*) as count')
            ->groupBy('scores_en')
            ->having('count', '>', 1)
            ->get();

        // $price_range = $this->getPriceRange('top-selling','one-price');

    //     $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
    //     ->join('brand_translations as bt','bt.brand_id','=','b.id')
    //     ->where('bt.lang_code',Session::get('front_lang'))
    //     ->select('b.slug','bt.name as brand_name')
    //     ->distinct('b.slug')->get();


    // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();

    // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();
    $jdm_core_brand = Brand::where('status', 'enable')->get();

    // $jdm_brand['car']=$jdm_legend;
    // $jdm_brand['heavy']=$jdm_legend_heavy;
    // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
    // $jdm_brand=$this->jdm_brands();

    // echo json_encode($brands);die();

    
        return view('top-ratings1', [
            'seo_setting' => $seo_setting,
            'brands' => $brands,
            'cities' => $cities,
            'features' => $features,
            'cars_array' => $cars_array,
            'listing_ads' => $listing_ads,
            'cars' => $cars,
            'brand_count' => $brand_count,
            'price_range' => $price_range_counts,
            'transmission' => $transmission,
            'scores' => $scores,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'>$jdm_core_brand,
            'models'=>$models,
            'brand_arr'=>$brand_list,
            'minYear'=>$minYear,
            'maxYear'=>$maxYear,
            'minPrice'=>$minPrice,
            'maxPrice'=>$maxPrice,
            'wishlists'=>$wishlists
        ]);
    }
    public function auctionCar(Request $request){

        $seo_setting = SeoSetting::where('id', 1)->first();
        // $brands = Brand::where('status', 'enable')->get();
        $brands = Auct_lots_xml_jp::join('brands as b', DB::raw('LOWER(auct_lots_xml_jp.company_en)'), '=', 'b.slug')
        ->join('brand_translations as bt','bt.brand_id','=','b.id')
        ->where('bt.lang_code',Session::get('front_lang'))
        ->select('b.slug','bt.name as name')
        ->distinct('b.slug')->get();
        $models=[];


        // DB::enableQueryLog();
        // Initialize the query for cars
        $carsQuery = Auct_lots_xml_jp::query();

      

        // Apply filters based on request parameters
        if ($request->location) {
            $carsQuery->where('city_id', $request->location);
        }

        if ($request->brand) {
            // $brand_arr = array_filter($request->brand); // Filter out any empty values
            // if ($brand_arr) {
                // $carsQuery->whereIn('company_en', $brand_arr); 
                $carsQuery->where(DB::raw('LOWER(company_en)'), $request->brand); 
                $models = \DB::table('auct_lots_xml_jp_op')
                ->where(DB::raw('LOWER(company_en)'), $request->brand)
                ->groupBy('model_name_en') 
                ->select('model_name_en')
                ->get();
            // }    
        }
    
        if($request->model){
                $model_arr = array_filter($request->model); // Filter out any empty values
        if ($model_arr) {
            $carsQuery->whereIn('model_name_en', $model_arr); 
        }
        }

        if($request->brand_new_cars){
            $year = date('Y'); 
             $carsQuery->where('model_year_en', 'LIKE', $year . '%');    
        }

        // if ($request->brand) {
        //     $brand_arr = array_filter($request->brand); // Filter out any empty values
        //     if ($brand_arr) {
        //         $carsQuery->whereIn('company_en', $brand_arr); 
        //     }    
        // }
        if ($request->tranmission) {
            $transmission_arr = array_filter($request->transmission_arr); // Filter out any empty values
            if ($transmission_arr) {
                $carsQuery->whereIn('transmission_en', $transmission_arr); 
            }    
        }

        if($request->year){
            if($request->year !="")
            {
                $carsQuery->where('model_year_en', $request->year); 
            }
        }

        if($request->price_range){

            $priceRanges = [
                "Under $5000" => ["start" => 0, "end" => (5000 * $this->usdRate)],
                "$5000 - $50000" => ["start" => (5001 * $this->usdRate), "end" => (50000 * $this->usdRate)],
                "$50000 - $100000" => ["start" => (50001 * $this->usdRate), "end" => (100000 * $this->usdRate)],
                "$100000 - $200000" => ["start" => (100001 * $this->usdRate), "end" =>  (200000 * $this->usdRate)],
                "$200000 - $300000" => ["start" => (200001 * $this->usdRate), "end" => (300000 * $this->usdRate)],
                "Above $300000" => ["start" => (300001 * $this->usdRate), "end" => PHP_INT_MAX] // Use PHP_INT_MAX for "Above"
            ];



            $result = $this->getPriceRangestart($request->price_range, $priceRanges);

        

    
            if (is_null($result['end_price_num'])) {
                // Count for "Above" range
                $carsQuery = $carsQuery->where(function ($q) use ($result) {
                    $q->where('start_price_num', '>', $result['start_price_num'])  // Greater than start price
                      ->orWhere('end_price_num', '>', $result['start_price_num']); // Greater than end price
                });
            } else {
                // Count for other ranges
                $carsQuery = $carsQuery->where(function ($q) use ($result) {
                    $q->whereBetween('start_price_num', [$result['start_price_num'], $result['end_price_num']])
                    ->orWhereBetween('end_price_num', [$result['start_price_num'], $result['end_price_num']]);
                });
            }  
        }

        if ($request->scores_en) {
            $score_arr = array_filter($request->scores_en); // Filter out any empty values
            if ($score_arr) {
                $carsQuery->whereIn('scores_en', $score_arr); 
            }
        }



        if ($request->condition) {
            $carsQuery->whereIn('condition', $request->condition);
        }

        if ($request->purpose) {
            $purpose_arr = array_filter($request->purpose);
            if ($purpose_arr) {
                $carsQuery->whereIn('purpose', $purpose_arr);
            }
        }

        if ($request->features) {
            $carsQuery->whereJsonContains('features', $request->features);
        }


        if($request->price_range_scale){
            if($request->price_range_scale !=""){  
                $parts = explode('-', $request->price_range_scale);
                $startValue = (trim($parts[0]) *  $this->usdRate);
                $endValue = (trim($parts[1]) *  $this->usdRate);
                $carsQuery = $carsQuery->where(function ($q) use ($startValue,$endValue) {
                    $q->whereBetween('start_price_num', [$startValue, $endValue])
                    ->orWhereBetween('end_price_num', [$startValue, $endValue]);
                });
            }
        }

        if ($request->price_filter) {
            if ($request->price_filter === 'low_to_high') {
                $carsQuery->orderBy('regular_price', 'asc');
            } elseif ($request->price_filter === 'high_to_low') {
                $carsQuery->orderBy('regular_price', 'desc');
            }
        }

        if ($request->search) {
            if($request->search!=""){
                $carsQuery->where('model_name_en', 'like', '%' . $request->search . '%');
            }
        }

        if ($request->sort_by) {
            switch ($request->sort_by) {
                case 'price_low_high':
                    $carsQuery->orderBy('start_price_num', 'asc');
                    break;
                case 'price_high_low':
                    $carsQuery->orderBy('start_price_num', 'desc');
                    break;
                case 'recent':
                    $recentCarIds = $carsQuery->orderBy('id', 'desc')
                    ->limit(100)
                    ->pluck('id');
                
                // Then reset the query and use these IDs
                $carsQuery = $carsQuery->whereIn('id', $recentCarIds);
                    break;
            }
        }

        $cars = $carsQuery->orderBy('id', 'desc')
        ->where('auct_lots_xml_jp.active_status','1')
        ->select('auct_lots_xml_jp.*')->paginate(12);

        // Pagination
        // $cars = $carsQuery->paginate(12);

        // Transform cars into an array for the view
        $cars_array = $cars->map(function ($car) {
        $car_image=$this->last_image($car->pictures);
            return [
                'company_en' => $car->company_en,
                'company' => $car->company,
                'model_name' => $car->model_name,
                'model_name_en' => $car->model_name_en,
                'start_price' => $car->start_price,
                'start_price_num' => $car->start_price_num,
                'end_price' => $car->end_price,
                'end_price_num' => $car->end_price_num,
                'picture' =>$car_image[0],
                'id' => $car->id,
                'mileage' => $car->mileage,
                'mileage_en' => $car->mileage_en,
            ];
        });


        // Get additional data
        $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();
        $cities = City::with('translate')->get();
        $features = Feature::with('translate')->get();

        // $brand_count = CarDataJpOp::selectRaw('company_en,company,COUNT(*) as count')
        //     ->groupBy('company_en')
        //     ->having('count', '>', 1)
        //     ->get();

        // echo json_encode($brand_count);die();    

        $transmission = CarDataJpOp::selectRaw('transmission_en, COUNT(*) as count')
            ->groupBy('transmission_en')
            ->having('count', '>', 1)
            ->get();

        $scores = CarDataJpOp::selectRaw('scores_en, COUNT(*) as count')
            ->groupBy('scores_en')
            ->having('count', '>', 1)
            ->get();

        $price_range = $this->getPriceRange('','auction');

    //     $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
    //     ->join('brand_translations as bt','bt.brand_id','=','b.id')
    //     ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();


    // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();

    // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();
    $jdm_core_brand = Brand::where('status', 'enable')->get();

    // $jdm_brand['car']=$jdm_legend;
    // $jdm_brand['heavy']=$jdm_legend_heavy;
    // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
    // $jdm_brand=$this->jdm_brands();

    // echo json_encode($models);die();

        return view('auction-car-marketplace', [
            'seo_setting' => $seo_setting,
            'brands' => $brands,
            'cities' => $cities,
            'features' => $features,
            'cars_array' => $cars_array,
            'listing_ads' => $listing_ads,
            'cars' => $cars,
            // 'brand_count' => $brand_count,
            'price_range' => $price_range,
            'transmission' => $transmission,
            'scores' => $scores,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand,
            'models'=>$models
        ]);
    }
    public function auctionCar1(Request $request){

        $seo_setting = SeoSetting::where('id', 1)->first();
        // $brands = Brand::where('status', 'enable')->get();
        // $brands = Auct_lots_xml_jp::join('brands as b', DB::raw('LOWER(auct_lots_xml_jp.company_en)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as name')
        // ->distinct('b.slug')->get();

        $brands=Auct_lots_xml_jp::select(
            'company_en as name',
            \DB::raw('LOWER(company_en) as slug')
        )
        ->groupBy('company_en')
        ->get();

        $keyWhere ="";
        $tableName='2';
        $brand_list=$this->getBrandsWithModels($keyWhere,$tableName,'');
        $models=[];


        $yearRange = Auct_lots_xml_jp::selectRaw('MIN(model_year_en) as min_year, MAX(model_year_en) as max_year')
        ->first();
        $priceRange = Auct_lots_xml_jp::selectRaw('MIN(start_price_num) as min_sal, MAX(start_price_num) as max_sal')
        ->first();
        $minYear = $yearRange->min_year;
        // $maxYear = $yearRange->max_year;
        $maxYear = Carbon::now()->year;
        // $minPrice = $priceRange->min_sal;
        $minPrice = $this->convertCurrency($priceRange->min_sal, $this->usdRate);
        // $maxPrice = $priceRange->max_sal;
        $maxPrice = $this->convertCurrency($priceRange->max_sal, $this->usdRate);
      
        // 645148

        $hasPriceRangeScale = false;
        $startValue = $minPrice;
        $endValue = $maxPrice;

        // Initialize the query for cars

        // DB::enableQueryLog();
        $carsQuery = Auct_lots_xml_jp::query();


        // Apply filters based on request parameters
        if ($request->location) {
            $carsQuery->where('city_id', $request->location);
        }

        if ($request->brand) {
            $brand_arr = array_filter($request->brand); // Filter out any empty values
            if ($brand_arr) {
                $carsQuery->whereIn(DB::raw('LOWER(company_en)'), $brand_arr); 
                // $carsQuery->where(DB::raw('LOWER(company_en)'), $request->brand); 
                $models = \DB::table('auct_lots_xml_jp')
                ->where(DB::raw('LOWER(company_en)'), $request->brand)
                ->groupBy('model_name_en') 
                ->select('model_name_en')
                ->get();
            }    
        }
    
        if($request->model){
            $model_arr = [];
            foreach ($request->model as $brandSlug => $models) {
                if (is_array($models)) {
                    $model_arr = array_merge($model_arr, array_filter($models)); // Flatten the nested array
                }
            }
            if ($model_arr) {
                $carsQuery->whereIn('model_name_en', $model_arr);
            }
            // $model_arr = array_filter($request->model); // Filter out any empty values
            // if ($model_arr) {
            //     $carsQuery->whereIn('model_name_en', $model_arr); 
            // }
            // $carsQuery->where('model_name_en', $request->model); 
        }

        if($request->brand_new_cars){
            $year = date('Y'); 
             $carsQuery->where('model_year_en', 'LIKE', $year . '%');    
        }
     

        if($request->year){
            if($request->year !=""){  
                $hasPriceRangeScale = true;
                $parts = explode(',', $request->year);
                $startYear=trim($parts[0]);
                $endYear=trim($parts[1]);
 
                $carsQuery = $carsQuery->where(function ($q) use ($startYear,$endYear) {
                    // $q->whereBetween('start_price_num', [$startValue, $endValue])
                    // ->orWhereBetween('end_price_num', [$startValue, $endValue]);
                    $q->whereBetween('model_year_en', [($startYear), ($endYear)]);
                });
            }
            // if($request->year !="")
            // {
            //     $carsQuery->where('model_year_en', $request->year); 
            // }
        }

        if($request->price_range){


            $priceRanges = [
                "Under $5000" => ["start" => 0, "end" => (5000 * $this->usdRate)],
                "$5000 - $50000" => ["start" => (5001 * $this->usdRate), "end" => (50000 * $this->usdRate)],
                "$50000 - $100000" => ["start" => (50001 * $this->usdRate), "end" => (100000 * $this->usdRate)],
                "$100000 - $200000" => ["start" => (100001 * $this->usdRate), "end" => (200000 * $this->usdRate)],
                "$200000 - $300000" => ["start" => (200001 * $this->usdRate), "end" => (300000 * $this->usdRate)],
                "Above $300000" => ["start" => (300001 * $this->usdRate), "end" => PHP_INT_MAX] // Use PHP_INT_MAX for "Above"
            ];



            $carsQuery->where(function ($query) use ($request, $priceRanges) {
                foreach ($request->price_range as $range) {
                    $result = $this->getPriceRangestart($range, $priceRanges);
        
                    if ($result['start_price_num'] !== null && $result['end_price_num'] !== null) {
                        // Group the conditions for this range
                        $query->orWhere(function ($subQuery) use ($result) {
                            $subQuery->whereBetween(DB::raw('COALESCE(start_price_num, 0)'), [$result['start_price_num'], $result['end_price_num']]);
                                    //  ->orWhereBetween('end_price_num', [$result['start_price_num'], $result['end_price_num']]);
                        });
                    }
                }
            }); 


            // $result = $this->getPriceRangestart($request->price_range, $priceRanges);

        

    
            // if (is_null($result['end_price_num'])) {
            //     // Count for "Above" range
            //     $carsQuery = $carsQuery->where(function ($q) use ($result) {
            //         $q->where('start_price_num', '>', $result['start_price_num'])  // Greater than start price
            //           ->orWhere('end_price_num', '>', $result['start_price_num']); // Greater than end price
            //     });
            // } else {
            //     // Count for other ranges
            //     $carsQuery = $carsQuery->where(function ($q) use ($result) {
            //         $q->whereBetween('start_price_num', [$result['start_price_num'], $result['end_price_num']])
            //         ->orWhereBetween('end_price_num', [$result['start_price_num'], $result['end_price_num']]);
            //     });
            // }  
        }
        if($request->price_range_scale){
          

            if($request->price_range_scale !=""){  
                $hasPriceRangeScale = true;
                $parts = explode(',', $request->price_range_scale);
                $start_value=trim($parts[0]);
                $end_value=trim($parts[1]);

                $startValue = ($start_value *  $this->usdRate);
                $endValue = ($end_value *  $this->usdRate);
                
                 if($start_value ==($minPrice || $maxPrice)){
                     if($start_value == $minPrice){
                        $startValue=$priceRange->min_sal;
                     } else if($start_value == $maxPrice){
                           $startValue=$priceRange->max_sal;
                     }
                 }
                 if($end_value ==($minPrice || $maxPrice)){
                     if($end_value == $minPrice){
                        $endValue=$priceRange->min_sal;
                     } else if($end_value == $maxPrice){
                        $endValue=$priceRange->max_sal;
                     }
                 }   
                $carsQuery = $carsQuery->where(function ($q) use ($startValue,$endValue) {
                    // $q->whereBetween('start_price_num', [$startValue, $endValue])
                    // ->orWhereBetween('end_price_num', [$startValue, $endValue]);
                    $q->whereBetween(DB::raw('COALESCE(start_price_num, 0)'), [$startValue, $endValue]);
                });
            }
        }

        if ($request->price_filter) {
            if ($request->price_filter === 'low_to_high') {
                $carsQuery->orderBy('regular_price', 'asc');
            } elseif ($request->price_filter === 'high_to_low') {
                $carsQuery->orderBy('regular_price', 'desc');
            }
        }

        if ($request->search) {
            if($request->search!=""){
                $carsQuery->where(function($query) use ($request) {
                    $query->where('model_name_en', 'like', '%' . $request->search . '%')
                          ->orWhere('company_en', 'like', '%' . $request->search . '%');
                });
                // $carsQuery->where('model_name_en', 'like', '%' . $request->search . '%');
            }
        }

        if ($request->sort_by) {
            switch ($request->sort_by) {
                case 'price_low_high':
                    $carsQuery->orderBy('auct_lots_xml_jp.start_price_num', 'asc');
                    break;
                case 'price_high_low':
                    $carsQuery->orderBy('auct_lots_xml_jp.start_price_num', 'desc');
                    break;
                case 'old_to_new':
                    $carsQuery->orderBy('auct_lots_xml_jp.model_year_en', 'asc');
                    break;
                case 'new_to_old':
                    $carsQuery->orderBy('auct_lots_xml_jp.model_year_en', 'desc');
                    break;
                case 'recent':
                    $recentCarIds = $carsQuery->orderBy('auct_lots_xml_jp.id', 'desc')
                    ->limit(100)
                    ->pluck('auct_lots_xml_jp.id');
                
                // Then reset the query and use these IDs
                $carsQuery = $carsQuery->whereIn('auct_lots_xml_jp.id', $recentCarIds);
                    break;
            }
        }
    

    
      
        if(!$request->sort_by){
            $carsQuery->orderBy('auct_lots_xml_jp.id', 'desc');
        }
       
        // $carsQuery->select('auct_lots_xml_jp.*')->get();
        // dd(DB::getQueryLog());

       
        // Pagination
        $cars = $carsQuery
        ->select('auct_lots_xml_jp.*')->paginate(12);

        $price_range_counts = $this->getAuctionPriceRangeCounts(
            $carsQuery, 
            $hasPriceRangeScale, 
            $startValue, 
            $endValue,
            ''
        );

       

        // Transform cars into an array for the view
        $cars_array = $cars->map(function ($car) {
            $car_image=$car_image =$this->last_image($car->pictures);
            $imageUrl='uploads/website-images/no-image.jpg';
            if(count($car_image)> 0){
                if ($this->isImageAvailable($car_image[0])) {
                    $imageUrl= $car_image[0];
                } else {
                    $imageUrl='uploads/website-images/no-image.jpg';
                }
            }
            return [
                'company_en' => $car->company_en,
                'company' => $car->company,
                'model_name' => $car->model_name,
                'model_name_en' => $car->model_name_en,
                'start_price' => $car->start_price,
                'start_price_num' => $this->convertCurrency($car->start_price_num, $this->usdRate),
                'end_price' => $car->end_price,
                'end_price_num' => $this->convertCurrency($car->end_price_num, $this->usdRate),
                'picture' =>$imageUrl,
                'id' => $car->id,
                'mileage' => $car->mileage,
                'mileage_en' => $car->mileage_en,
                'year'=>$car->model_year,
                'year_en'=>$car->model_year_en,
                'transmission'=>$car->transmission,
                'transmission_en'=>$car->transmission_en,
                'parsed_data'=>$car->parsed_data_en,
                'datetime'=>$car->datetime 
            ];
        });


        // Get additional data
        $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();
        $cities = City::with('translate')->get();
        $features = Feature::with('translate')->get();

        // $brand_count = CarDataJpOp::selectRaw('company_en,company,COUNT(*) as count')
        //     ->groupBy('company_en')
        //     ->having('count', '>', 1)
        //     ->get();

        // echo json_encode($brand_count);die();    

        $transmission = CarDataJpOp::selectRaw('transmission_en, COUNT(*) as count')
            ->groupBy('transmission_en')
            ->having('count', '>', 1)
            ->get();

        $scores = CarDataJpOp::selectRaw('scores_en, COUNT(*) as count')
            ->groupBy('scores_en')
            ->having('count', '>', 1)
            ->get();

        $price_range = $this->getPriceRange('','auction');

    //     $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
    //     ->join('brand_translations as bt','bt.brand_id','=','b.id')
    //     ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();


    // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();

    // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();
    $jdm_core_brand = Brand::where('status', 'enable')->get();

    // $jdm_brand['car']=$jdm_legend;
    // $jdm_brand['heavy']=$jdm_legend_heavy;
    // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
    // $jdm_brand=$this->jdm_brands();

    // echo json_encode($models);die();

    $wishlists=[];
    if(Auth::guard('web')->check()){
        $userId = $userId ?? auth()->id();
        $wishlists=Auct_lots_xml_jp::join('wishlists as w', 'auct_lots_xml_jp.id', '=', 'w.car_id')
        // $wishlistItems = \App\Models\Wishlist::where('user_id', $userId)
            ->where('w.table_id', '2')
            ->pluck('w.car_id')
            ->toArray();
    }

        return view('auction-car-marketplace1', [
            'seo_setting' => $seo_setting,
            'brands' => $brands,
            'cities' => $cities,
            'features' => $features,
            'cars_array' => $cars_array,
            'listing_ads' => $listing_ads,
            'cars' => $cars,
            // 'brand_count' => $brand_count,
            'price_range' => $price_range_counts,
            'transmission' => $transmission,
            'scores' => $scores,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand,
            'models'=>$models,
            'brand_arr'=>$brand_list,
            'minYear'=>$minYear,
            'maxYear'=>$maxYear,
            'minPrice'=>$minPrice,
            'maxPrice'=>$maxPrice,
            'wishlists'=>$wishlists
        ]);
    }
    public function auctionBrandNewCar(Request $request){
    
      

        $usdRate = Cache::get('usd_to_jpy_rate');

        $seo_setting = SeoSetting::where('id', 1)->first();
        // $brands = Brand::where('status', 'enable')->get();
        // $brands = Auct_lots_xml_jp::join('brands as b', DB::raw('LOWER(auct_lots_xml_jp.company_en)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->whereBetween('model_year_en', [now()->subYear()->year,now()->year])
        // ->select('b.slug','bt.name as name')
        // ->distinct('b.slug')->get();

        $brands=Auct_lots_xml_jp::select(
            'company_en as name',
            \DB::raw('LOWER(company_en) as slug')
        )
        ->whereBetween('model_year_en', [now()->subYear()->year,now()->year])
        ->groupBy('company_en')
        ->get();

        $keyWhere ="";
        $tableName='2';
        $brand_list=$this->getBrandsWithModels($keyWhere,$tableName,'brand-new');
        $models=[];

        $minYear = now()->subYear()->year;
        $maxYear = now()->year;

        $yearRange = Auct_lots_xml_jp::whereBetween('model_year_en', [$minYear,$maxYear])
        ->selectRaw('MIN(model_year_en) as min_year, MAX(model_year_en) as max_year')
        ->first();
        $priceRange = Auct_lots_xml_jp::whereBetween('model_year_en', [$minYear,$maxYear])
        ->selectRaw('MIN(start_price_num) as min_sal, MAX(start_price_num) as max_sal')
        ->first();
       
        // $minPrice = $priceRange->min_sal;
        $minPrice = $this->convertCurrency($priceRange->min_sal, $usdRate);
        // $maxPrice = $priceRange->max_sal;
        $maxPrice = $this->convertCurrency($priceRange->max_sal, $usdRate);

        
        $hasPriceRangeScale = false;
        $startValue = $minPrice;
        $endValue = $maxPrice;
        // DB::enableQueryLog();

        // Initialize the query for cars
        $carsQuery = Auct_lots_xml_jp::query();

        // $carsQuery->join('brands as b', DB::raw('LOWER(auct_lots_xml_jp.company_en)'), '=', 'b.slug')
        // ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
        // ->where('bt.lang_code', Session::get('front_lang'));

        // Apply filters based on request parameters
        if ($request->location) {
            $carsQuery->where('city_id', $request->location);
        }

        if ($request->brand) {
            $brand_arr = array_filter($request->brand); // Filter out any empty values
            if ($brand_arr) {
                $carsQuery->whereIn(DB::raw('LOWER(company_en)'), $brand_arr); 
                // $carsQuery->where(DB::raw('LOWER(company_en)'), $request->brand); 
                $models = \DB::table('auct_lots_xml_jp')
                ->where(DB::raw('LOWER(company_en)'), $request->brand)
                ->groupBy('model_name_en') 
                ->select('model_name_en')
                ->get();
            }    
        }
    
        if($request->model){
            // $model_arr = array_filter($request->model); // Filter out any empty values
            // if ($model_arr) {
            //     $carsQuery->whereIn('model_name_en', $model_arr); 
            // }
            $model_arr = [];
            foreach ($request->model as $brandSlug => $models) {
                if (is_array($models)) {
                    $model_arr = array_merge($model_arr, array_filter($models)); // Flatten the nested array
                }
            }
            // echo json_encode($model_arr);die();
            if ($model_arr) {
                $carsQuery->whereIn('model_name_en', $model_arr);
            }
            // $carsQuery->where('model_name_en', $request->model); 
        }

        if($request->brand_new_cars){
            $year = date('Y'); 
             $carsQuery->where('model_year_en', 'LIKE', $year . '%');    
        }

        // if ($request->brand) {
        //     $brand_arr = array_filter($request->brand); // Filter out any empty values
        //     if ($brand_arr) {
        //         $carsQuery->whereIn('company_en', $brand_arr); 
        //     }    
        // }
        if ($request->tranmission) {
            $transmission_arr = array_filter($request->transmission_arr); // Filter out any empty values
            if ($transmission_arr) {
                $carsQuery->whereIn('transmission_en', $transmission_arr); 
            }    
        }

        if($request->year){
            if($request->year !=""){  
                $hasPriceRangeScale = true;
                $parts = explode(',', $request->year);
                $startYear=trim($parts[0]);
                $endYear=trim($parts[1]);
 
                $carsQuery = $carsQuery->where(function ($q) use ($startYear,$endYear) {
                    // $q->whereBetween('start_price_num', [$startValue, $endValue])
                    // ->orWhereBetween('end_price_num', [$startValue, $endValue]);
                    $q->whereBetween('model_year_en', [($startYear), ($endYear)]);
                });
            }
            // if($request->year !="")
            // {
            //     $carsQuery->where('model_year_en', $request->year); 
            // }
        }

        if($request->price_range){

            $priceRanges = [
                "Under $5000" => ["start" => 0, "end" => (5000 * $usdRate)],
                "$5000 - $50000" => ["start" => (50001* $usdRate), "end" => (50000 * $usdRate)],
                "$50000 - $100000" => ["start" => (50001 * $usdRate ), "end" => (100000 * $usdRate)],
                "$100000 - $200000" => ["start" => (100001 * $usdRate), "end" => (200000 * $usdRate)],
                "$200000 - $300000" => ["start" => (200001 * $usdRate), "end" => (300000 * $usdRate)],
                "Above $300000" => ["start" => (300001 * $usdRate), "end" => PHP_INT_MAX] // Use PHP_INT_MAX for "Above"
            ];



            $carsQuery->where(function ($query) use ($request, $priceRanges) {
                foreach ($request->price_range as $range) {
                    $result = $this->getPriceRangestart($range, $priceRanges);
                    $startValue = (int)trim(str_replace('"', '', $result['start_price_num'],));
                    $endValue = (int)trim(str_replace('"', '', $result['end_price_num']));
              

        
                    if ($result['start_price_num'] !== null && $result['end_price_num'] !== null) {
                        // Group the conditions for this range
                        $query->orWhere(function ($subQuery) use ($result,$startValue,$endValue) {
                            // $subQuery->whereBetween(DB::raw('COALESCE(start_price_num, 0)'), [$result['start_price_num'],$result['end_price_num']]);
                            $subQuery->whereBetween(DB::raw('COALESCE(start_price_num, 0)'), [$startValue,$endValue]);
                            // $subQuery->whereBetween('start_price_num', [$startValue, $endValue]);
                                    //  ->orWhereBetween('end_price_num', [$result['start_price_num'], $result['end_price_num']]);
                        });
                    }
                }
            }); 
        }

        if ($request->scores_en) {
            $score_arr = array_filter($request->scores_en); // Filter out any empty values
            if ($score_arr) {
                $carsQuery->whereIn('scores_en', $score_arr); 
            }
        }



        if ($request->condition) {
            $carsQuery->whereIn('condition', $request->condition);
        }

        if ($request->purpose) {
            $purpose_arr = array_filter($request->purpose);
            if ($purpose_arr) {
                $carsQuery->whereIn('purpose', $purpose_arr);
            }
        }

        if ($request->features) {
            $carsQuery->whereJsonContains('features', $request->features);
        }


        if($request->price_range_scale){
            if($request->price_range_scale !=""){  
                $hasPriceRangeScale = true;
                $parts = explode(',', $request->price_range_scale);
                $start_value=trim($parts[0]);
                $end_value=trim($parts[1]);

                $startValue = ($start_value *  $this->usdRate);
                $endValue = ($end_value *  $this->usdRate);
                
                 if($start_value ==($minPrice || $maxPrice)){
                     if($start_value == $minPrice){
                        $startValue=$priceRange->min_sal;
                     } else if($start_value == $maxPrice){
                           $startValue=$priceRange->max_sal;
                     }
                 }
                 if($end_value ==($minPrice || $maxPrice)){
                     if($end_value == $minPrice){
                        $endValue=$priceRange->min_sal;
                     } else if($end_value == $maxPrice){
                        $endValue=$priceRange->max_sal;
                     }
                 }   
                $carsQuery = $carsQuery->where(function ($q) use ($startValue,$endValue) {
                    // $q->whereBetween('start_price_num', [$startValue, $endValue])
                    // ->orWhereBetween('end_price_num', [$startValue, $endValue]);
                    $q->whereBetween(DB::raw('COALESCE(start_price_num, 0)'), [$startValue, $endValue]);
                    // $q->whereBetween('start_price_num', [$startValue, $endValue]);
                });
            }
            
        }

        if ($request->price_filter) {
            if ($request->price_filter === 'low_to_high') {
                $carsQuery->orderBy('regular_price', 'asc');
            } elseif ($request->price_filter === 'high_to_low') {
                $carsQuery->orderBy('regular_price', 'desc');
            }
        }

        if ($request->search) {
            if($request->search!=""){
                $carsQuery->where(function($query) use ($request) {
                    $query->where('model_name_en', 'like', '%' . $request->search . '%')
                          ->orWhere('company_en', 'like', '%' . $request->search . '%');
                });
                $carsQuery->where('model_name_en', 'like', '%' . $request->search . '%');
            }
        }

        if ($request->sort_by) {
            switch ($request->sort_by) {
                case 'price_low_high':
                    $carsQuery->orderBy('auct_lots_xml_jp.start_price_num', 'asc');
                    break;
                case 'price_high_low':
                    $carsQuery->orderBy('auct_lots_xml_jp.start_price_num', 'desc');
                    break;
                case 'old_to_new':
                    $carsQuery->orderBy('auct_lots_xml_jp_op.model_year_en', 'asc');
                    break;
                case 'new_to_old':
                    $carsQuery->orderBy('auct_lots_xml_jp_op.model_year_en', 'desc');
                    break;    
                case 'recent':
                    $recentCarIds = $carsQuery->orderBy('auct_lots_xml_jp.id', 'desc')
                    ->limit(100)
                    ->pluck('auct_lots_xml_jp.id');
                
                // Then reset the query and use these IDs
                $carsQuery = $carsQuery->whereIn('auct_lots_xml_jp.id', $recentCarIds);
                    break;
            }
        }

        // $currentYear = now()->year;
        // $previousYear = now()->subYear()->year;
        $carsQuery->whereBetween('model_year_en', [$minYear,$maxYear]); 
       
        if(!$request->sort_by){
            $carsQuery->orderBy('auct_lots_xml_jp.id', 'desc');
        }
        
        // DB::enableQueryLog();
        // $cars = $carsQuery
        // ->select('auct_lots_xml_jp.*')->get();
        // dd(DB::getQueryLog());

    //   dd(DB::getQueryLog($cars));

        // Pagination
        $cars = $carsQuery
        ->select('auct_lots_xml_jp.*')->paginate(12);
        // DB::raw("start_price_num / $usdRate as price_in_usd")
        // $cars = $carsQuery->paginate(12);

       

        // Transform cars into an array for the view
        $cars_array = $cars->map(function ($car) use ($usdRate) {
        $car_image=$this->last_image($car->pictures);
        $imageUrl='uploads/website-images/no-image.jpg';
        if(count($car_image)> 0){
            if ($this->isImageAvailable($car_image[0])) {
                $imageUrl= $car_image[0];
            } else {
                $imageUrl='uploads/website-images/no-image.jpg';
            }
        }
        $priceInUSD = bcdiv($car->start_price_num, $usdRate, 5);
            return [
                'company_en' => $car->company_en,
                'company' => $car->company,
                'model_name' => $car->model_name,
                'model_name_en' => $car->model_name_en,
                'start_price' => $car->start_price,
                'start_price_num' => $car->start_price_num,
                'end_price' => $car->end_price,
                'end_price_num' => $car->end_price_num,
                'picture' =>$imageUrl,
                'id' => $car->id,
                'mileage' => $car->mileage,
                'mileage_en' => $car->mileage_en,
                'year'=>$car->model_year,
                'year_en'=>$car->model_year_en,
                'transmission'=>$car->transmission,
                'transmission_en'=>$car->transmission_en,
                'parsed_data'=>$car->parsed_data_en,
                'datetime'=>$car->datetime,
                'price_in_usd'=> $this->convertCurrency($car->start_price_num, $usdRate)
            ];
        });


        // Get additional data
        $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();
        $cities = City::with('translate')->get();
        $features = Feature::with('translate')->get();

        // $brand_count = CarDataJpOp::selectRaw('company_en,company,COUNT(*) as count')
        //     ->groupBy('company_en')
        //     ->having('count', '>', 1)
        //     ->get();

        // echo json_encode($brand_count);die();    

        $transmission = CarDataJpOp::selectRaw('transmission_en, COUNT(*) as count')
            ->groupBy('transmission_en')
            ->having('count', '>', 1)
            ->get();

        $scores = CarDataJpOp::selectRaw('scores_en, COUNT(*) as count')
            ->groupBy('scores_en')
            ->having('count', '>', 1)
            ->get();

        // $price_range = $this->getPriceRange();
        $price_range_counts = $this->getAuctionPriceRangeCounts(
            $carsQuery, 
            $hasPriceRangeScale, 
            $startValue, 
            $endValue,
            'auction-brand-new'
        );


    //     $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
    //     ->join('brand_translations as bt','bt.brand_id','=','b.id')
    //     ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();


    // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();

    // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();
    $jdm_core_brand = Brand::where('status', 'enable')->get();

    // $jdm_brand['car']=$jdm_legend;
    // $jdm_brand['heavy']=$jdm_legend_heavy;
    // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
    // $jdm_brand=$this->jdm_brands();

    $wishlists=[];
    if(Auth::guard('web')->check()){
        $userId = $userId ?? auth()->id();
        $wishlists=Auct_lots_xml_jp::join('wishlists as w', 'auct_lots_xml_jp.id', '=', 'w.car_id')
        // $wishlistItems = \App\Models\Wishlist::where('user_id', $userId)
            ->where('w.table_id', '2')
            ->pluck('w.car_id')
            ->toArray();
    }

    // echo json_encode($models);die();

        return view('auction-brand-new-cars', [
            'seo_setting' => $seo_setting,
            'brands' => $brands,
            'cities' => $cities,
            'features' => $features,
            'cars_array' => $cars_array,
            'listing_ads' => $listing_ads,
            'cars' => $cars,
            // 'brand_count' => $brand_count,
            'price_range' => $price_range_counts,
            'transmission' => $transmission,
            'scores' => $scores,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand,
            'models'=>$models,
            'brand_arr'=>$brand_list,
            'minYear'=>$minYear,
            'maxYear'=>$maxYear,
            'minPrice'=>$minPrice,
            'maxPrice'=>$maxPrice,
            'wishlists'=>$wishlists
        ]);
    }
    function convertCurrencyFloat($amount, $rate) {
        // First convert the numbers to decimal format with high precision
        $exactDivision = $amount / $rate;
        
        // Format to keep exactly 10 decimal places without any rounding
        return number_format($exactDivision, 10, '.', '');
    }


    function convertCurrency($amount, $rate) {
        // Convert strings to BCMath strings to maintain precision
        $amount = strval($amount);
        $rate = strval($rate);
    
        // Perform high-precision division and directly round to the nearest whole number
        $result = bcdiv($amount, $rate, 10); // Keep precision high
        $result = round($result);

        return $result;
    }
    public function new_arrival(Request $request){
        $models=[];


        $seo_setting = SeoSetting::where('id', 1)->first();
        // $brands = Brand::where('status', 'enable')->get();
        $brands = CarDataJpOp::join('brands as b', DB::raw('LOWER(auct_lots_xml_jp_op.company_en)'), '=', 'b.slug')
        ->join('brand_translations as bt','bt.brand_id','=','b.id')
        ->where('bt.lang_code',Session::get('front_lang'))
        ->select('b.slug','bt.name as name')
        ->where('auct_lots_xml_jp_op.new_arrival',1)    
        ->distinct('b.slug')->get();
        $keyWhere="new-arrival";
        $tableName='1';
        $brand_list=$this->getBrandsWithModels($keyWhere,$tableName,'');

        // Initialize the query for cars
        $carsQuery = CarDataJpOp::query();

        $yearRange = CarDataJpOp::where('active_status', '1')
        ->selectRaw('MIN(model_year_en) as min_year, MAX(model_year_en) as max_year')
        ->first();
        $priceRange = CarDataJpOp::where('active_status', '1')
        ->selectRaw('MIN(start_price_num) as min_sal, MAX(start_price_num) as max_sal')
        ->first();


    // echo json_encode($priceRange);die();

    // Get min and max years
        $minYear = $yearRange->min_year;
        $maxYear = $yearRange->max_year;
        $minPrice = $priceRange->min_sal;
        $maxPrice = $priceRange->max_sal;

        // Apply filters based on request parameters
        if ($request->location) {
            $carsQuery->where('city_id', $request->location);
        }


        if ($request->brand) {
            // $brand_arr = array_filter($request->brand); // Filter out any empty values
            // if ($brand_arr) {
            //     $carsQuery->whereIn('company_en', $brand_arr); 
                $carsQuery->where(DB::raw('LOWER(company_en)'), $request->brand); 
                $models = \DB::table('auct_lots_xml_jp_op')
                ->where(DB::raw('LOWER(company_en)'), $request->brand)
                ->where('new_arrival','1')
                ->groupBy('model_name_en') 
                ->select('model_name_en')
                ->get();
            // }    
           
        }
    
        if($request->model){
            // $model_arr = array_filter($request->model); // Filter out any empty values
            // if ($model_arr) {
                $carsQuery->whereIn('model_name_en', $model_arr); 
            // $carsQuery->where('model_name_en', $request->model); 
            // }
        }

        if($request->price_range_scale){
            if($request->price_range_scale !=""){  
                $parts = explode(',', $request->price_range_scale);
                $startValue = trim($parts[0]);
                $endValue = trim($parts[1]);
                $carsQuery = $carsQuery->where(function ($q) use ($startValue,$endValue) {
                    $q->whereBetween('start_price_num', [$startValue, $endValue])
                    ->orWhereBetween('end_price_num', [$startValue, $endValue]);
                });
            }
        }
        if ($request->tranmission) {
            $transmission_arr = array_filter($request->transmission_arr); // Filter out any empty values
            if ($transmission_arr) {
                $carsQuery->whereIn('transmission_en', $transmission_arr); 
            }    
        }

        if($request->brand_new_cars){
            $year = date('Y'); 
             $carsQuery->where('model_year_en', 'LIKE', $year . '%');    
        }

        if($request->year){
            if($request->year !="")
            {
                $carsQuery->where('model_year_en', $request->year); 
            }
        }

        if($request->price_range){

            $priceRanges = [
                "Under $5000" => ["start" => 0, "end" => 5000],
                "$5000 - $50000" => ["start" => 5000, "end" => 50000],
                "$50000 - $100000" => ["start" => 50000, "end" => 100000],
                "$100000 - $200000" => ["start" => 100000, "end" => 200000],
                "$200000 - $300000" => ["start" => 200000, "end" => 300000],
                "Above $300000" => ["start" => 300000, "end" => null] // Use PHP_INT_MAX for "Above"
            ];



            $result = $this->getPriceRangestart($request->price_range, $priceRanges);

        

    
            if (is_null($result['end_price_num'])) {
                // Count for "Above" range
                $carsQuery = $carsQuery->where(function ($q) use ($result) {
                    $q->where('start_price_num', '>', $result['start_price_num'])  // Greater than start price
                      ->orWhere('end_price_num', '>', $result['start_price_num']); // Greater than end price
                });
            } else {
                // Count for other ranges
                $carsQuery = $carsQuery->where(function ($q) use ($result) {
                    $q->whereBetween('start_price_num', [$result['start_price_num'], $result['end_price_num']])
                    ->orWhereBetween('end_price_num', [$result['start_price_num'], $result['end_price_num']]);
                });
            }  
        }

        if ($request->scores_en) {
            $score_arr = array_filter($request->scores_en); // Filter out any empty values
            if ($score_arr) {
                $carsQuery->whereIn('scores_en', $score_arr); 
            }
        }



        if ($request->condition) {
            $carsQuery->whereIn('condition', $request->condition);
        }

        if ($request->purpose) {
            $purpose_arr = array_filter($request->purpose);
            if ($purpose_arr) {
                $carsQuery->whereIn('purpose', $purpose_arr);
            }
        }

        if ($request->features) {
            $carsQuery->whereJsonContains('features', $request->features);
        }

        if ($request->price_filter) {
            if ($request->price_filter === 'low_to_high') {
                $carsQuery->orderBy('regular_price', 'asc');
            } elseif ($request->price_filter === 'high_to_low') {
                $carsQuery->orderBy('regular_price', 'desc');
            }
        }

        if ($request->search) {
            if($request->search!=""){
                $carsQuery->where('model_name_en', 'like', '%' . $request->search . '%');
            }
        }

        if ($request->sort_by) {
            switch ($request->sort_by) {
                case 'price_low_high':
                    $carsQuery->orderBy('start_price_num', 'asc');
                    break;
                case 'price_high_low':
                    $carsQuery->orderBy('start_price_num', 'desc');
                    break;
                case 'recent':  
                    $recentCarIds = $carsQuery->orderBy('id', 'desc')
                    ->limit(100)
                    ->pluck('id');
                
                // Then reset the query and use these IDs
                $carsQuery = $carsQuery->whereIn('id', $recentCarIds);
                    break;
            }
        }

        // $carsQuery->get();
        // dd(DB::getQueryLog());

        // Pagination
        $date=date('Y');
        $cars = $carsQuery->where('new_arrival','1')
        ->where('active_status','1')
        ->orderBy('id','desc')
        ->paginate(12);

        // Transform cars into an array for the view
        $cars_array = $cars->map(function ($car) {
        $car_image=$this->last_image($car->pictures);
            return [
                'company_en' => $car->company_en,
                'company' => $car->company,
                'model_name' => $car->model_name,
                'model_name_en' => $car->model_name_en,
                'start_price' => $car->start_price,
                'start_price_num' => $car->start_price_num,
                'end_price' => $car->end_price,
                'end_price_num' => $car->end_price_num,
                'picture' =>$car_image[0],
                'id' => $car->id,
                'mileage' => $car->mileage,
                'mileage_en' => $car->mileage_en,
            ];
        });


        // Get additional data
        $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();
        $cities = City::with('translate')->get();
        $features = Feature::with('translate')->get();

        $brand_count = CarDataJpOp::selectRaw('company_en,company,COUNT(*) as count')
            ->groupBy('company_en')
            ->having('count', '>', 1)
            ->get();

        $transmission = CarDataJpOp::selectRaw('transmission_en, COUNT(*) as count')
            ->groupBy('transmission_en')
            ->having('count', '>', 1)
            ->get();

        $scores = CarDataJpOp::selectRaw('scores_en, COUNT(*) as count')
            ->groupBy('scores_en')
            ->having('count', '>', 1)
            ->get();

        $price_range = $this->getPriceRange();

    //     $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
    //     ->join('brand_translations as bt','bt.brand_id','=','b.id')
    //     ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();


    // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();

    // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();
    $jdm_core_brand = Brand::where('status', 'enable')->get();

    // $jdm_brand['car']=$jdm_legend;
    // $jdm_brand['heavy']=$jdm_legend_heavy;
    // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
    // $jdm_brand=$this->jdm_brands();

        return view('new-arrival', [
            'seo_setting' => $seo_setting,
            'brands' => $brands,
            'cities' => $cities,
            'features' => $features,
            'cars_array' => $cars_array,
            'listing_ads' => $listing_ads,
            'cars' => $cars,
            'brand_count' => $brand_count,
            'price_range' => $price_range,
            'transmission' => $transmission,
            'scores' => $scores,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand,
            'models'=>$models,
            'brand_arr'=>$brand_list,
            'minYear'=>$minYear,
            'maxYear'=>$maxYear,
            'minPrice'=>$minPrice,
            'maxPrice'=>$maxPrice
        ]);
    }

    public function new_arrival1(Request $request){
        $models=[];
    
        $seo_setting = SeoSetting::where('id', 1)->first();
        
        // $brands = CarDataJpOp::join('brands as b', DB::raw('LOWER(auct_lots_xml_jp_op.company_en)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as name')
        // ->where('auct_lots_xml_jp_op.new_arrival',1)    
        // ->distinct('b.slug')->get();

        $brands=CarDataJpOp::Join('auct_lots_xml_jp_op_other_chargers as oc','oc.auct_id','=','auct_lots_xml_jp_op.id')
        ->select(
            'company_en as name',
            \DB::raw('LOWER(company_en) as slug')
        )
        ->where('oc.new_arrival',1)
        // ->where('active_status', '1')
        ->groupBy('company_en')
        ->get();

        // echo json_encode($brands);die();

        $keyWhere="new-arrival";

        $tableName='1';
        $brand_list=$this->getBrandsWithModels($keyWhere,$tableName,'');
       

        // echo json_encode($brand_list);die();
        $yearRange = CarDataJpOp::Join('auct_lots_xml_jp_op_other_chargers as oc','oc.auct_id','=','auct_lots_xml_jp_op.id')
        ->where('oc.new_arrival',1)
        ->selectRaw('MIN(model_year_en) as min_year, MAX(model_year_en) as max_year')
        ->first();
        $priceRange = CarDataJpOp::Join('auct_lots_xml_jp_op_other_chargers as oc','oc.auct_id','=','auct_lots_xml_jp_op.id')
        // where('active_status', '1')
        ->where('oc.new_arrival',1)
        ->selectRaw('MIN(start_price_num) as min_sal, MAX(start_price_num) as max_sal')
        ->first();


    // echo json_encode($priceRange);die();

    // Get min and max years
        $minYear = $yearRange->min_year;
        // $maxYear = $yearRange->max_year;
        $maxYear = Carbon::now()->year;
        // $minPrice = $priceRange->min_sal;
         $minPrice = $this->convertCurrency($priceRange->min_sal, $this->usdRate);
        // $maxPrice = $priceRange->max_sal;
        $maxPrice = $this->convertCurrency($priceRange->max_sal, $this->usdRate);

        $hasPriceRangeScale = false;
        $startValue = $minPrice;
        $endValue = $maxPrice;

        // DB::enableQueryLog();
        // Initialize the query for cars
        $carsQuery = CarDataJpOp::query();

        // $carsQuery->join('brands as b', DB::raw('LOWER(auct_lots_xml_jp_op.company_en)'), '=', 'b.slug')
        // ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
        // ->where('bt.lang_code', Session::get('front_lang'));


        $carsQuery->Join('auct_lots_xml_jp_op_other_chargers as oc','oc.auct_id','=','auct_lots_xml_jp_op.id');


    

        
        // Apply filters based on request parameters
        // if ($request->location) {
        //     $carsQuery->where('city_id', $request->location);
        // }


        if ($request->brand) {
            $brand_arr = array_filter($request->brand); // Filter out any empty values
            if ($brand_arr) {
                $carsQuery->whereIn(DB::raw('LOWER(company_en)'), $brand_arr); 
                // $carsQuery->where(DB::raw('LOWER(company_en)'), $request->brand); 
                // $models = \DB::table('auct_lots_xml_jp_op')
                // ->where(DB::raw('LOWER(company_en)'), $request->brand)
                // ->where('new_arrival','1')
                // ->groupBy('model_name_en') 
                // ->select('model_name_en')
                // ->get();
            }    
           
        }
    
        if($request->model){
            $model_arr = [];
            foreach ($request->model as $brandSlug => $models) {
                if (is_array($models)) {
                    $model_arr = array_merge($model_arr, array_filter($models)); // Flatten the nested array
                }
            }
            if ($model_arr) {
                $carsQuery->whereIn('model_name_en', $model_arr);
            }
            // $model_arr = array_filter($request->model); // Filter out any empty values
            // if ($model_arr) {
            //     $carsQuery->whereIn('model_name_en', $model_arr); 
            // // $carsQuery->where('model_name_en', $request->model); 
            // }
        }

        if($request->price_range_scale){
            if($request->price_range_scale !=""){  
                $hasPriceRangeScale = true;
                $parts = explode(',', $request->price_range_scale);
                $start_value=trim($parts[0]);
                $end_value=trim($parts[1]);

                $startValue = ($start_value *  $this->usdRate);
                $endValue = ($end_value *  $this->usdRate);
                
                 if($start_value ==($minPrice || $maxPrice)){
                     if($start_value == $minPrice){
                        $startValue=$priceRange->min_sal;
                     } else if($start_value == $maxPrice){
                           $startValue=$priceRange->max_sal;
                     }
                 }
                 if($end_value ==($minPrice || $maxPrice)){
                     if($end_value == $minPrice){
                        $endValue=$priceRange->min_sal;
                     } else if($end_value == $maxPrice){
                        $endValue=$priceRange->max_sal;
                     }
                 }   
                $carsQuery = $carsQuery->where(function ($q) use ($startValue,$endValue) {
                    $q->whereBetween('start_price_num', [$startValue, $endValue]);
                    // $q->whereBetween('start_price_num', [$startValue, $endValue])
                    // ->orWhereBetween('end_price_num', [$startValue, $endValue]);
                });
            }
        }
        // if ($request->tranmission) {
        //     $transmission_arr = array_filter($request->transmission_arr); // Filter out any empty values
        //     if ($transmission_arr) {
        //         $carsQuery->whereIn('transmission_en', $transmission_arr); 
        //     }    
        // }

        if($request->brand_new_cars){
            $year = date('Y'); 
             $carsQuery->where('model_year_en', 'LIKE', $year . '%');    
        }

        if($request->year){
            if($request->year !=""){  
                $hasPriceRangeScale = true;
                $parts = explode(',', $request->year);
                $startYear=trim($parts[0]);
                $endYear=trim($parts[1]);
 
                $carsQuery = $carsQuery->where(function ($q) use ($startYear,$endYear) {
                    // $q->whereBetween('start_price_num', [$startValue, $endValue])
                    // ->orWhereBetween('end_price_num', [$startValue, $endValue]);
                    $q->whereBetween('model_year_en', [($startYear), ($endYear)]);
                });
            }
            // if($request->year !="")
            // {
            //     $carsQuery->where('model_year_en', $request->year); 
            // }
        }

        if($request->price_range){

            $priceRanges = [
                "Under $5000" => ["start" => 0, "end" => (5000 * $this->usdRate)],
                "$5000 - $50000" => ["start" => (5001 * $this->usdRate), "end" => (50000 * $this->usdRate)],
                "$50000 - $100000" => ["start" => (50001 * $this->usdRate), "end" => (100000 * $this->usdRate)],
                "$100000 - $200000" => ["start" => (100001 * $this->usdRate), "end" => (200000 * $this->usdRate)],
                "$200000 - $300000" => ["start" => (200001 * $this->usdRate), "end" => (300000 * $this->usdRate)],
                "Above $300000" => ["start" => (300001 * $this->usdRate), "end" => PHP_INT_MAX] // Use PHP_INT_MAX for "Above"
            ];
             
            $carsQuery->where(function ($query) use ($request, $priceRanges) {
                foreach ($request->price_range as $range) {
                    $result = $this->getPriceRangestart($range, $priceRanges);
        
                    if ($result['start_price_num'] !== null && $result['end_price_num'] !== null) {
                        // Group the conditions for this range
                        $query->orWhere(function ($subQuery) use ($result) {
                            // $subQuery->whereBetween('start_price_num', [$result['start_price_num'], $result['end_price_num']])
                            //          ->orWhereBetween('end_price_num', [$result['start_price_num'], $result['end_price_num']]);
                            $subQuery->whereBetween('start_price_num', [$result['start_price_num'], $result['end_price_num']]);
                        });
                    }
                }
            });   
        }
        if ($request->search) {
            if($request->search!=""){
                $carsQuery->where(function($query) use ($request) {
                    $query->where('model_name_en', 'like', '%' . $request->search . '%')
                          ->orWhere('company_en', 'like', '%' . $request->search . '%');
                });
            }
        }

        if ($request->sort_by) {
            switch ($request->sort_by) {
                case 'price_low_high':
                    $carsQuery->orderBy('auct_lots_xml_jp_op.start_price_num', 'asc');
                    break;
                case 'price_high_low':
                    $carsQuery->orderBy('auct_lots_xml_jp_op.start_price_num', 'desc');
                    break;
                    case 'old_to_new':
                        $carsQuery->orderBy('auct_lots_xml_jp_op.model_year_en', 'asc');
                        break;
                    case 'new_to_old':
                        $carsQuery->orderBy('auct_lots_xml_jp_op.model_year_en', 'desc');
                        break;
                case 'recent':  
                    $recentCarIds = $carsQuery->orderBy('auct_lots_xml_jp_op.id', 'desc')
                    ->limit(100)
                    ->pluck('auct_lots_xml_jp_op.id');
                
                // Then reset the query and use these IDs
                $carsQuery = $carsQuery->whereIn('auct_lots_xml_jp_op.id', $recentCarIds);
                    break;
            }
        }


        if(!$request->sort_by){
            $carsQuery->orderBy('auct_lots_xml_jp_op.id', 'desc');
        }
        // Pagination
        $date=date('Y');
        $cars = $carsQuery
        ->where('oc.new_arrival','1')
        ->select('auct_lots_xml_jp_op.*')
        // ->where('active_status','1')
        ->paginate(12);

        // Transform cars into an array for the view
        $cars_array = $cars->map(function ($car) {
            $car_image=$car_image =$this->last_image($car->pictures);
            $imageUrl='uploads/website-images/no-image.jpg';
            if(count($car_image)> 0){
                if ($this->isImageAvailable($car_image[0])) {
                    $imageUrl= $car_image[0];
                } else {
                    $imageUrl='uploads/website-images/no-image.jpg';
                }
            }
            return [
                'company_en' => $car->company_en,
                'company' => $car->company,
                'model_name' => $car->model_name,
                'model_name_en' => $car->model_name_en,
                'start_price' => $car->start_price,
                // 'start_price_num' => $car->start_price_num,
                'start_price_num' => $this->convertCurrency($car->start_price_num, $this->usdRate),
                'end_price' => $car->end_price,
                // 'end_price_num' => $car->end_price_num,
                'end_price_num' => $this->convertCurrency($car->end_price_num, $this->usdRate),
                'picture' =>$imageUrl,
                'id' => $car->id,
                'mileage' => $car->mileage,
                'mileage_en' => $car->mileage_en,
                'year'=>$car->model_year,
                'year_en'=>$car->model_year_en,
                'transmission'=>$car->transmission,
                'transmission_en'=>$car->transmission_en,
                'parsed_data'=>$car->parsed_data_en,
                'datetime'=>$car->datetime 
            ];
        });


        // Get additional data
        $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();
        // $cities = City::with('translate')->get();
        // $features = Feature::with('translate')->get();

        $brand_count = CarDataJpOp::selectRaw('company_en,company,COUNT(*) as count')
            ->groupBy('company_en')
            ->having('count', '>', 1)
            ->get();

        $price_range_counts = $this->getPriceRangeCounts(
            $carsQuery, 
            $hasPriceRangeScale, 
            $startValue, 
            $endValue,
            'new-arrival',
        );


    $jdm_core_brand = Brand::where('status', 'enable')->get();

    $wishlists=[];
    if(Auth::guard('web')->check()){
        $userId = $userId ?? auth()->id();
        $wishlists=CarDataJpOp::join('wishlists as w', 'auct_lots_xml_jp_op.id', '=', 'w.car_id')
        // $wishlistItems = \App\Models\Wishlist::where('user_id', $userId)
            ->where('w.table_id', '1')
            ->where('w.user_id',$userId)
            ->pluck('w.car_id')
            ->toArray();
    }

        return view('new-arrival1', [
            'seo_setting' => $seo_setting,
            'brands' => $brands,
            'cars_array' => $cars_array,
            'listing_ads' => $listing_ads,
            'cars' => $cars,
            'brand_count' => $brand_count,
            'price_range' => $price_range_counts,
            'jdm_core_brand'=>$jdm_core_brand,
            'models'=>$models,
            'brand_arr'=>$brand_list,
            'minYear'=>$minYear,
            'maxYear'=>$maxYear,
            'minPrice'=>$minPrice,
            'maxPrice'=>$maxPrice,
            'wishlists'=>$wishlists
        ]);
    }
  
   

    public function getPriceRange($param,$type){
        $price_ranges = [
            'Under $5000' => ['min' => 0, 'max' => 5000],
            '$5000 - $50000' => ['min' => 5000, 'max' => 50000],
            '$50000 - $100000' => ['min' => 50000, 'max' => 100000],
            '$100000 - $200000' => ['min' => 100000, 'max' => 200000],
            '$200000 - $300000' => ['min' => 200000, 'max' => 300000],
            'Above $300000' => ['min' => 300000, 'max' => null],
        ];
        $counts = [];
        // DB::enableQueryLog();
        if($type=='one-price'){
            $query = CarDataJpOp::query();
        } else if($type == 'auction'){
            $query= Auct_lots_xml_jp::query();
        }
        
        
        
        foreach ($price_ranges as $label => $range) {
        
            if ($range['max'] === null) {
                // Count for "Above" range
            
                $count = $query->where(function ($q) use ($range) {
                    $q->where('start_price_num',  '>', $range['min'])
                      ->orWhere('end_price_num', '>', $range['min']);
                })
                ->when($param == 'top-selling', function($query) {
                    return $query->where('top_sell', 1);
                })
                ->when($param == 'new_arrival', function($query) {
                    return $query->where('new_arrival', 1);
                })
                ->count();

                // $count = $query->where('start_price_num', '>', $range['min'])
                //                ->orWhere('end_price_num', '>', $range['min'])
                //                ->when($param == 'top-selling',function($query){
                //                    return $query->where('top_sell',1);
                //                })
                //                ->count();
            } else {
                // Count for other ranges
                $count = $query->where(function ($q) use ($range) {
                    $q->whereBetween('start_price_num', [$range['min'], $range['max']])
                      ->orWhereBetween('end_price_num', [$range['min'], $range['max']]);
                })
                ->when($param == 'top-selling',function($query){
                    return $query->where('top_sell',1);
                })
                ->when($param == 'new_arrival', function($query) {
                    return $query->where('new_arrival', 1);
                })
                ->count();
            }
            $counts[$label] = $count;
        }
    // dd(DB::getQueryLog());
        return $counts;
        
    }

    public function SelectedJdmRangeNew($price_ranges,$slug,
    $hasPriceRangeScale,$startValue,$endValue){
            $tableName = "blog";         
        $priceCount = [];
    
        // Loop through each price range and count the records in that range
        foreach ($price_ranges as $label => $range) {
            // DB::enableQueryLog();
            $query = DB::table($tableName . ' as t') // Alias the table dynamically
            // ->join('models_cars as mc', function($join) {
            //     $join->on('t.category', '=', 'mc.category')  
            //          ->on('t.model', '=', 'mc.model');       
            // })
            ->where(DB::raw('LOWER(t.make)'), $slug)      
            ->where('t.is_active', 1)                     
            ->whereNull('t.deleted_at')   
            ->whereRaw('REGEXP_REPLACE(t.price, "[,\\s]", "") REGEXP "^[0-9]+$"');                
            if ($hasPriceRangeScale) {
                $query->whereRaw("REGEXP_REPLACE(t.price, '[^0-9]', '') BETWEEN ? AND ?", [$startValue, $endValue]);
            }
        
            // Add the condition for the current price range
            $query->whereRaw("REGEXP_REPLACE(t.price, '[^0-9]', '') BETWEEN ? AND ?", [$range['start'], $range['end']]);
        
            // Get the count
            $count = $query->count();
            // dd(DB::getQueryLog());
    
        
            // Store the count for the current range
            $priceCount[$label] = $count;
        }
        return $priceCount;
            
    }    

    public function SelectedJdmRangeCar($price_ranges,
$hasPriceRangeScale,$startValue,$endValue){

    $brands = DB::table('brands as b')
    ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
    ->where('bt.lang_code', Session::get('front_lang'))
    ->select('b.slug', 'bt.name')
    ->get();
  
        $tableName = "blog";   
   
        $priceCount = [];
        $brandSlugs = $brands->pluck('slug')->map(fn($slug) => strtolower($slug))->toArray();

    // Loop through each price range and count the records in that range
    foreach ($price_ranges as $label => $range) {
        // DB::enableQueryLog();
        $query = DB::table($tableName . ' as t') // Alias the table dynamically
        ->join('models_cars as mc', function($join) {
            $join->on('t.category', '=', 'mc.category')  
                 ->on('t.model', '=', 'mc.model');       
        })
        ->whereIn(DB::raw('LOWER(t.make)'), $brandSlugs)      
        ->where('t.is_active', 1)                     
        ->whereNull('t.deleted_at')  
        ->whereNotNull(DB::raw("REGEXP_SUBSTR(yom, '[0-9]{4}')"))
        ->whereBetween(
            DB::raw("REGEXP_SUBSTR(yom, '[0-9]{4}')"),
            [now()->subYear()->year, now()->year]
        ) 
        ->whereRaw('REGEXP_REPLACE(t.price, "[,\\s]", "") REGEXP "^[0-9]+$"');                
        if ($hasPriceRangeScale) {
            $query->whereRaw("REGEXP_REPLACE(t.price, '[^0-9]', '') BETWEEN ? AND ?", [$startValue, $endValue]);
        }
        
    
        // Add the condition for the current price range
        $query->whereRaw("REGEXP_REPLACE(t.price, '[^0-9]', '') BETWEEN ? AND ?", [$range['start'], $range['end']]);
    
        // Get the count
        $count = $query->count();
        // dd(DB::getQueryLog());

    
        // Store the count for the current range
        $priceCount[$label] = $count;
    }
    return $priceCount;
        
}

public function SelectedJdmRange($type,$price_ranges,$slug,
$hasPriceRangeScale,$startValue,$endValue){
    if ($type == 'car') {
        $model=Cars::class;
        $tableName = "blog";   
    } else if ($type == 'heavy') {  
        $tableName = "heavy";
        $model=Heavy::class;
    } 
    // else if ($type == 'small_heavy') {
    //     $tableName = "small_heavy";
    // }   
    
    // foreach ($price_ranges as $label => $range) {
    //     $blogQuery = DB::table($tableName)
    //         ->join('models_cars as mc', function($join) {
    //             $join->on('blog.category', '=', 'mc.category')
    //                 ->on('blog.model', '=', 'mc.model');
    //         })
    //         ->where(DB::raw('LOWER('.$tableName.'.make)'), '=', $slug)
    //         ->where('is_active', 1)
    //         ->whereRaw('REGEXP_REPLACE(price, "[,\\s]", "") REGEXP "^[0-9]+$"');
    
    //     if ($range['end'] === null) {
    //         // For "Above" price range
    //         $blogCount = $blogQuery->whereRaw(
    //             'CAST(REGEXP_REPLACE('.$tableName.'.price, "[,\\s]", "") AS DECIMAL(10, 0)) >= ?', 
    //             [$range['start']]
    //         )->count();
    //     } else {
    //         // For price ranges between start and end
    //         $blogCount = $blogQuery->whereRaw(
    //             'CAST(REGEXP_REPLACE('.$tableName.'.price, "[,\\s]", "") AS DECIMAL(10, 0)) BETWEEN ? AND ?', 
    //             [$range['start'], $range['end']]
    //         )->count();
    //     }
    
    //     $counts[$label] = $blogCount;
    // } 
    $priceCount = [];

    // Loop through each price range and count the records in that range
    foreach ($price_ranges as $label => $range) {
        // DB::enableQueryLog();
        $query =$model // Alias the table dynamically
        // ->join('models_cars as mc', function($join) {
        //     $join->on('t.category', '=', 'mc.category')  
        //          ->on('t.model', '=', 'mc.model');       
        // })
        ::where(DB::raw('LOWER(make)'), $slug)      
        ->where('is_active', 1)                     
        // ->whereNull('t.deleted_at')   
        ->whereRaw('REGEXP_REPLACE(price, "[,\\s]", "") REGEXP "^[0-9]+$"');                
        if ($hasPriceRangeScale) {
            $query->whereRaw("REGEXP_REPLACE(price, '[^0-9]', '') BETWEEN ? AND ?", [$startValue, $endValue]);
        }
    
        // Add the condition for the current price range
        $query->whereRaw("REGEXP_REPLACE(price, '[^0-9]', '') BETWEEN ? AND ?", [$range['start'], $range['end']]);
    
        // Get the count
        $count = $query->count();
        // dd(DB::getQueryLog());

    
        // Store the count for the current range
        $priceCount[$label] = $count;
    }
    return $priceCount;
        
}
public function getJDMPriceRange()
{
    $price_ranges = [
        'Under $5000' => ['min' => 0, 'max' => 5000],
        '$5000 - $50000' => ['min' => 5000, 'max' => 50000],
        '$50000 - $100000' => ['min' => 50000, 'max' => 100000],
        '$100000 - $200000' => ['min' => 100000, 'max' => 200000],
        '$200000 - $300000' => ['min' => 200000, 'max' => 300000],
        'Above $300000' => ['min' => 300000, 'max' => null],
    ];

    $counts = [];

    foreach ($price_ranges as $label => $range) {
        // Query for all three tables
        $blogQuery = \DB::table('blog'); // Assuming this is for 'blog'
        $heavyQuery = Heavy::query();      // Assuming this is for 'heavy'
        $smallHeavyQuery = SmallHeavy::query(); // Assuming this is for 'small_heavy'

        if ($range['max'] === null) {
            // For "Above" price range
            $blogCount = $blogQuery->where('price', '>', $range['min'])->count();
            $heavyCount = $heavyQuery->where('price', '>', $range['min'])->count();
            $smallHeavyCount = $smallHeavyQuery->where('price', '>', $range['min'])->count();
        } else {
            // For other price ranges
            $blogCount = $blogQuery->whereBetween('price', [$range['min'], $range['max']])->count();
            $heavyCount = $heavyQuery->whereBetween('price', [$range['min'], $range['max']])->count();
            $smallHeavyCount = $smallHeavyQuery->whereBetween('price', [$range['min'], $range['max']])->count();
        }

        // Sum the counts from all three tables
        $counts[$label] = $blogCount + $heavyCount + $smallHeavyCount;
    }

    return $counts;
}


    private function parseCustomFormat($string)
    {
        // Remove CDATA wrapper if present
        $string = preg_replace('/<!\[CDATA\[(.*?)\]\]>/s', '$1', $string);
        
        // Remove outer curly braces if present
        $string = trim($string, '{}');
        
        // Split the string into key-value pairs
        $pairs = preg_split('/","|,(?=[^:]+:)/', $string);
        
        $result = [];
        foreach ($pairs as $pair) {
            // Split each pair into key and value
            list($key, $value) = array_pad(explode(':', $pair, 2), 2, null);
            
            // Clean up key and value
            $key = trim($key, '" ');
            $value = trim($value, '" ');
            
            // Unescape special characters
            $value = stripcslashes($value);
            
            $result[$key] = $value;
        }
        
        return $result;
    }

    public function listing($slug){
        $car = CarDataJpOp::where('id',$slug)->firstOrFail();
        $process_data_en = $this->parseCustomFormat($car->parsed_data_en);
        $images=$this->last_image($car->pictures);
        $related_listings = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->where('brand_id', $car->brand_id)->where('id', '!=', $car->id)->get()->take(6);

        $reviews = Review::with('user')->where('car_id', $car->id)->where('status', 'enable')->latest()->get();
        $listing_ads = AdsBanner::where('position_key', 'listing_detail_page_banner')->first();

        $delivery_charges = DeliveryCharge::all();
        $jdm_core_brand = Brand::where('status', 'enable')->get();

        // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //              ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();

        // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_brand['car']=$jdm_legend;
        // $jdm_brand['heavy']=$jdm_legend_heavy;
        // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
        // $jdm_brand=$this->jdm_brands();



        return view('listing_detail', [
            'car' => $car,
            'galleries' => $images, 
            'related_listings' => $related_listings,
            'reviews' => $reviews,
            'listing_ads' => $listing_ads,
            'delivery_charges'=>$delivery_charges,
            'process_data_en'=>$process_data_en,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand,
            'url_link'=>url()->full()
            // 'jdm_legend_heavy'=>$jdm_legend_heavy,
            // 'jdm_legend_small_heavy'=>$jdm_legend_small_heavy
        ]);

    }
    public function car_listing_details($category,$slug){
     
        // $car = CarDataJpOp::where('id',$slug)->firstOrFail();
        $car=CarDataJpOp::leftjoin('auct_lots_xml_jp_op_other_chargers as oc','auct_lots_xml_jp_op.id','=','oc.auct_id')
                        ->where('auct_lots_xml_jp_op.id',$slug)
                        ->select('auct_lots_xml_jp_op.*','oc.commission_value','oc.shipping_value',
                        'oc.marine_insurance_value','oc.inland_inspection_value')->first();              

        $seo_setting = SeoSetting::where('id', 1)->first();
        $process_data_en = $this->parseCustomFormat($car->parsed_data_en);
        $images=$this->last_image($car->pictures);
        $related_listings = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->where('brand_id', $car->brand_id)->where('id', '!=', $car->id)->get()->take(6);

        $reviews = Review::with('user')->where('car_id', $car->id)->where('status', 'enable')->latest()->get();
        $listing_ads = AdsBanner::where('position_key', 'listing_detail_page_banner')->first();

        $delivery_charges = DeliveryCharge::all();
        $jdm_core_brand = Brand::where('status', 'enable')->get();



        return view('car-listing-details', [
            'car' => $car,
            'galleries' => $images,
            'reviews' => $reviews,
            'listing_ads' => $listing_ads,
            'delivery_charges'=>$delivery_charges,
            'process_data_en'=>$process_data_en,
            'jdm_core_brand'=>$jdm_core_brand,
            'url_link'=>url()->full(),
            'seo_setting'=>$seo_setting,
            'usd_rate'=>$this->usdRate,
            'head_page'=>$category
        ]);

    }
    
    public function get_brands(Request $request){
        // $blogModels = Blog::where(DB::raw('LOWER(make)'), $request->brand)
        // ->select('model');

        $blogModels=\DB::table('blog')
        ->where(DB::raw('LOWER(make)'), $request->brand)
        ->select('model');

        $heavyModels = Heavy::where(DB::raw('LOWER(make)'), $request->brand)
            ->select('model');

        $smallHeavyModels = SmallHeavy::where(DB::raw('LOWER(make)'), $request->brand)
            ->select('model');

            // Combine all using union
            $models = $blogModels
                ->union($heavyModels)
                ->union($smallHeavyModels)
                ->distinct()
                ->get();
            return response()->json(['success'=>true,'response'=>$models]);
    }    
    public function get_oneprice_brands(Request $request){
        $result = [];
            DB::table('auct_lots_xml_jp_op')
            ->select(
                DB::raw('LOWER(company_en) as brand_slug'),
                'model_name_en'
                 // Add the model count
            )
            ->where(DB::raw('LOWER(company_en)'), $request->brand)
            ->groupBy(DB::raw('LOWER(company_en)'), 'model_name_en') // Group by brand and model
            ->distinct()
            ->orderBy('company_en')
            ->chunk(1000, function($models) use (&$result) {
                foreach ($models as $model) {
                    // Normalize case in PHP
                    $normalizedName = ucwords(strtolower($model->model_name_en));
                    // Store the model count for each brand and model
                    $result[] = $normalizedName;
                }
            });
        return response()->json(['success'=>true,'response'=>$result]);
    }    

    public function get_model_year(Request $request){
        $blogModels=\DB::table('blog')
        ->where(DB::raw('LOWER(make)'), $request->brand)
        ->where(DB::raw('LOWER(model)'), $request->model)
        ->select('yom');

        $heavyModels = Heavy::where(DB::raw('LOWER(make)'), $request->brand)
        ->where(DB::raw('LOWER(model)'), $request->model)
        ->select('yom');

        $smallHeavyModels = SmallHeavy::where(DB::raw('LOWER(make)'), $request->brand)
        ->where(DB::raw('LOWER(model)'), $request->model)
        ->select('yom');

            // Combine all using union
        $year = $blogModels
            ->union($heavyModels)
            ->union($smallHeavyModels)
            ->distinct()
            ->get();

        return response()->json(['success'=>true,'response'=>$year]);
    }
    public function get_oneprice_model_year(Request $request){
        
        $year=DB::table('auct_lots_xml_jp_op')
        ->select('model_year_en')
        ->where(DB::raw('LOWER(company_en)'), $request->brand)
        ->where('model_name_en', $request->model) // Group by brand and model
        ->distinct()
        ->get();
   
        return response()->json(['success'=>true,'response'=>$year]);
    }



    public function jdm_stock_all(Request $request)
    {
        $seo_setting = SeoSetting::where('id', 1)->first();
        // $brands = Brand::where('status', 'enable')->get();



        $jdmBrand = $request->input('jdm_brand');
        $jdmModel = $request->input('jdm_model');
        $jdmYear = $request->input('jdm_year');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $price_range_scale=$request->input('price_range_scale');
        $transmission=$request->input('transmission');
        $search=$request->input('search');
        $brand_new_cars=$request->input('brand_new_cars');
        $sort_by=$request->input('sort_by');
        $models=[];
        // Initialize the query for cars
        // $carsQuery = CarDataJpOp::query();
      
           DB::enableQueryLog();
            // Query for Blog table
            $blogCars = Cars::when($jdmBrand, function ($query, $jdmBrand) {
                    return $query->where(DB::raw('LOWER(make)'), $jdmBrand);
                })
                ->when($jdmModel, function ($query, $jdmModel) {
                    // $brand_arr = array_filter($jdmModel);
                    // if ($brand_arr) {
                        return $query->where('model', $jdmModel);
                    // }  
                })
                ->when($search, function ($query, $search) {
                    return $query->where('model', 'like', '%' . $search . '%');
                })
                ->when($jdmYear, function ($query, $jdmYear) {
                    // $yom_arr = array_filter($jdmYear);
                    if ($jdmYear!="") {
                        return $query->where('yom', $jdmYear);
                    } 
                })
                ->when($price_range_scale, function ($query) use ($price_range_scale) {
                    if($price_range_scale !=""){  
                        $parts = explode('-', $price_range_scale);
                        $startValue = trim($parts[0]);
                        $endValue = trim($parts[1]);
                        $carsQuery = $query->where(function ($q) use ($startValue,$endValue) {
                            return $q->whereBetween('price', [$startValue, $endValue]);
                        });
                    }
                })
                ->when($transmission, function ($query, $transmission) {
                    $transmission_arr = array_filter($transmission);
                    if ($transmission_arr) {
                        return $query->whereIn('transmission', $transmission_arr);
                    } 
                })
                ->when($brand_new_cars, function ($query, $brand_new_cars) {
                    return $query->where('yom', $brand_new_cars);
                })
                ->when($sort_by, function ($query, $sort_by) {
                    switch ($sort_by) {
                        case 'price_low_high':
                           return  $query->orderBy('price', 'asc');
                            break;
                        case 'price_high_low':
                            return  $query->orderBy('price', 'desc');
                            break;
                        case 'recent':
                            $recentCarIds = $query->orderBy('id', 'desc')
                            ->limit(100)
                            ->pluck('id');
                        
                        // Then reset the query and use these IDs
                        return $query = $query->whereIn('id', $recentCarIds);
                        break;
                    }
                })
                ->where('is_active','1')
                ->select('model','price','image','id','make','title');



      
            // Query for Heavy table
            $heavyCars = Heavy::when($jdmBrand, function ($query, $jdmBrand) {
                return $query->where(DB::raw('LOWER(make)'), $jdmBrand);
                })
                ->when($jdmModel, function ($query, $jdmModel) {
                    // $brand_arr = array_filter($jdmModel);
                    // if ($brand_arr) {
                        return $query->where('model', $jdmModel);
                    // }  
                })
                ->when($jdmYear, function ($query, $jdmYear) {
                    // $yom_arr = array_filter($jdmYear);
                    if ($jdmYear!="") {
                        return $query->where('yom', $jdmYear);
                    } 
                })
                ->when($transmission, function ($query, $transmission) {
                    $transmission_arr = array_filter($transmission);
                    if ($transmission_arr) {
                        return $query->whereIn('transmission', $transmission_arr);
                    } 
                })
                ->when($price_range_scale, function ($query) use ($price_range_scale) {
                    if($price_range_scale !=""){  
                        $parts = explode('-', $price_range_scale);
                        $startValue = trim($parts[0]);
                        $endValue = trim($parts[1]);
                        $carsQuery = $query->where(function ($q) use ($startValue,$endValue) {
                            return $q->whereBetween('price', [$startValue, $endValue]);
                        });
                    }
                })
                ->when($search, function ($query, $search) {
                    return $query->where('model', 'like', '%' . $search . '%');
                })
                ->when($brand_new_cars, function ($query, $brand_new_cars) {
                    return $query->where('yom', $brand_new_cars);
                })
                ->when($brand_new_cars, function ($query, $brand_new_cars) {
                    return $query->where('yom', $brand_new_cars);
                })
                ->when($sort_by, function ($query, $sort_by) {
                    switch ($sort_by) {
                        case 'price_low_high':
                           return  $query->orderBy('price', 'asc');
                            break;
                        case 'price_high_low':
                            return  $query->orderBy('price', 'desc');
                            break;
                        case 'recent':
                            $recentCarIds = $query->orderBy('id', 'desc')
                            ->limit(100)
                            ->pluck('id');
                        
                        // Then reset the query and use these IDs
                        return $query = $query->whereIn('id', $recentCarIds);
                        break;
                    }
                })
                ->where('is_active','1')
                ->select('model','price','image','id','make','title');

            // Query for Small Heavy table
            $smallHeavyCars = SmallHeavy::when($jdmBrand, function ($query, $jdmBrand) {
                return $query->where(DB::raw('LOWER(make)'), $jdmBrand);
                })
                ->when($jdmModel, function ($query, $jdmModel) {
                    // $brand_arr = array_filter($jdmModel);
                    // if ($brand_arr) {
                        return $query->where('model', $jdmModel);
                    // }  
                })
                ->when($jdmYear, function ($query, $jdmYear) {
                    // $yom_arr = array_filter($jdmYear);
                    if ($jdmYear!="") {
                        return $query->where('yom', $jdmYear);
                    }  
                })
                ->when($price_range_scale, function ($query) use ($price_range_scale) {
                    if($price_range_scale !=""){  
                        $parts = explode('-', $price_range_scale);
                        $startValue = trim($parts[0]);
                        $endValue = trim($parts[1]);
                        $carsQuery = $query->where(function ($q) use ($startValue,$endValue) {
                            return $q->whereBetween('price', [$startValue, $endValue]);
                        });
                    }
                })
                ->when($transmission, function ($query, $transmission) {
                    $transmission_arr = array_filter($transmission);
                    if ($transmission_arr) {
                        return $query->whereIn('transmission', $transmission_arr);
                    } 
                })
                ->when($search, function ($query, $search) {
                    return $query->where('model', 'like', '%' . $search . '%');
                })  
                ->when($brand_new_cars, function ($query, $brand_new_cars) {
                    return $query->where('yom', $brand_new_cars);
                })
                ->when($sort_by, function ($query, $sort_by) {
                    switch ($sort_by) {
                        case 'price_low_high':
                           return  $query->orderBy('price', 'asc');
                            break;
                        case 'price_high_low':
                            return  $query->orderBy('price', 'desc');
                            break;
                        case 'recent':
                            $recentCarIds = $query->orderBy('id', 'desc')
                            ->limit(100)
                            ->pluck('id');
                        
                        // Then reset the query and use these IDs
                        return $query = $query->whereIn('id', $recentCarIds);
                        break;
                    }
                })
                ->where('is_active','1')
                ->select('model','price','image','id','make','title');

        if($request->price_range_scale){
            if($request->price_range_scale !=""){  
                $parts = explode('-', $request->price_range_scale);
                $startValue = trim($parts[0]);
                $endValue = trim($parts[1]);
                $carsQuery = $carsQuery->where(function ($q) use ($startValue,$endValue) {
                    $q->whereBetween('start_price_num', [$startValue, $endValue])
                    ->orWhereBetween('end_price_num', [$startValue, $endValue]);
                });
            }
        }


  
        if ($request->transmission) {
            $transmission_arr = array_filter($request->transmission); // Filter out any empty values
            if ($transmission_arr) {
                $carsQuery->whereIn('transmission', $transmission_arr); 
            }    
        }

        if($request->year){
            if($request->year !="")
            {
                $carsQuery->where('model_year_en', $request->year); 
            }
        }

        if($request->price_range){
            $priceRanges = [
                "Under $5000" => ["start" => 0, "end" => 5000],
                "$5000 - $50000" => ["start" => 5000, "end" => 50000],
                "$50000 - $100000" => ["start" => 50000, "end" => 100000],
                "$100000 - $200000" => ["start" => 100000, "end" => 200000],
                "$200000 - $300000" => ["start" => 200000, "end" => 300000],
                "Above $300000" => ["start" => 300000, "end" => null] // Use PHP_INT_MAX for "Above"
            ];

            $result = $this->getPriceRangestart($request->price_range, $priceRanges);
        

    
            if ($result['start_price_num'] === null) {
                // Count for "Above" range
                $carsQuery = $carsQuery->where('start_price_num', '>', $result['start_price_num'])
                            ->orWhere('end_price_num', '>', $result['start_price_num']);
            } else {
                // Count for other ranges
                $carsQuery = $carsQuery->where(function ($q) use ($result) {
                    $q->whereBetween('start_price_num', [$result['start_price_num'], $result['end_price_num']])
                    ->orWhereBetween('end_price_num', [$result['start_price_num'], $result['end_price_num']]);
                });
            }  
        }

        if ($request->scores_en) {
            $score_arr = array_filter($request->scores_en); // Filter out any empty values
            if ($score_arr) {
                $carsQuery->whereIn('scores_en', $score_arr); 
            }
        }

        if ($request->search) {
            $carsQuery->where('model_name_en', 'like', '%' . $request->search . '%');
        }

       

        $cars = $blogCars
        ->union($heavyCars)
        ->union($smallHeavyCars)
        ->orderBy('model')
        ->distinct()
        ->paginate(12); 

        // dd(DB::getQueryLog());


    if($request->jdm_brand){
        
                $carsMakes = Cars::when($jdmBrand, function ($query, $jdmBrand) {
                    return $query->where('make', $jdmBrand);
                })
                ->distinct()
                ->pluck('model');

                // Fetch distinct makes from Heavy table
                $heavyMakes = Heavy::when($jdmBrand, function ($query, $jdmBrand) {
                            return $query->where('make', $jdmBrand);
                        })
                        ->distinct()
                        ->pluck('model');

                // Fetch distinct makes from Small Heavy table
                $smallHeavyMakes = SmallHeavy::when($jdmBrand, function ($query, $jdmBrand) {
                                return $query->where('make', $jdmBrand);
                            })
                            ->distinct()
                            ->pluck('model');

                            $models = $carsMakes->merge($heavyMakes)
                            ->merge($smallHeavyMakes)
                            ->unique()
                            ->values();                   
    }

 



    
    // Pagination
    // $cars = $carsQuery->paginate(12);

    // Transform cars into an array for the view
    $cars_array = $cars->map(function ($car) {
    // $car_image=$this->last_image($car->pictures);
        return [
            'company_en' => $car->make,
            'model_name_en' => $car->title,
            'start_price_num' => $car->price,
            'picture' =>$car->image,
            'id' => $car->id,
        ];
    });




 


    // Get additional data
    $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();
    $cities = City::with('translate')->get();

    $brand_count = CarDataJpOp::selectRaw('company_en,company,COUNT(*) as count')
        ->groupBy('company_en')
        ->having('count', '>', 1)
        ->get();

        $transmission = CarDataJpOp::selectRaw('transmission_en, COUNT(*) as count')
            ->groupBy('transmission_en')
            ->having('count', '>', 1)
            ->get();

    // $price_range = $this->getPriceRange();
    $price_range = $this->getJDMPriceRange();
    $jdm_legend = Brand::where('status', 'enable')->get();

 

    
    // echo json_encode($models);die();

    $transmissions = \DB::select(
        'SELECT transmission, SUM(model_count) as total_count FROM (
            (SELECT transmission, COUNT(*) as model_count FROM blog WHERE transmission IS NOT NULL AND transmission != "" GROUP BY transmission)
            UNION ALL
            (SELECT transmission, COUNT(*) as model_count FROM heavy WHERE transmission IS NOT NULL AND transmission != "" GROUP BY transmission)
            UNION ALL
            (SELECT transmission, COUNT(*) as model_count FROM small_heavy WHERE transmission IS NOT NULL AND transmission != "" GROUP BY transmission)
        ) as combined_models
        GROUP BY transmission
        HAVING total_count > 0'
    );
    $jdm_core_brand = Brand::where('status', 'enable')->get();

    // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
    //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
    //              ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();



    // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();


    // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();
    $brands = $jdm_legend
    ->concat($jdm_legend_heavy)
    ->concat($jdm_legend_small_heavy)
    ->unique('slug')
    ->values();


 


   





    // $jdm_brand['car']=$jdm_legend;
    // $jdm_brand['heavy']=$jdm_legend_heavy;
    // $jdm_brand['small_heavy']=$jdm_legend_heavy;
    // $jdm_brand=$this->jdm_brands();






    

    return view('jdm_stock_all', [
        'seo_setting' => $seo_setting,
        'brands' => $brands,
        'cities' => $cities,
        'cars_array' => $cars_array,
        'listing_ads' => $listing_ads,
        'cars' => $cars,
        'brand_count' => $models,
        'price_range' => $price_range,
        'transmission' => $transmissions,
        // 'jdm_legend'=>$jdm_brand,
        'jdm_core_brand'=>$jdm_core_brand,
        'models'=>$models

    ]);
    }

    public function getJdmBrandModels($jdmBrand){  
            $carsMakes = Cars::when($jdmBrand, function ($query, $jdmBrand) {
                return $query->where('make', $jdmBrand);
            })
            ->distinct()
            ->pluck('model');

            // Fetch distinct makes from Heavy table
            $heavyMakes = Heavy::when($jdmBrand, function ($query, $jdmBrand) {
                        return $query->where('make', $jdmBrand);
                    })
            ->distinct()
            ->pluck('model');

            // Fetch distinct makes from Small Heavy table
            $smallHeavyMakes = SmallHeavy::when($jdmBrand, function ($query, $jdmBrand) {
                            return $query->where('make', $jdmBrand);
            })
            ->distinct()
            ->pluck('model');

            $models = $carsMakes->merge($heavyMakes)
            ->merge($smallHeavyMakes)
            ->unique()
            ->values();   
            return $models ;               
    }

    public function jdm_stock_all_resposive(Request $request)
    {
        $models=[];
        $seo_setting = SeoSetting::where('id', 1)->first();

       
        $brands="";
        
        $models=$this->getJdmBrandModels($request->jdm_brand);
 

        $jdmBrand = $request->input('jdm_brand');
        $jdmModel = $request->input('jdm_model');
        $jdmYear = $request->input('jdm_year');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $price_range=$request->input('price_range');
        $price_range_scale=$request->input('price_range_scale');
        $transmission=$request->input('transmission');
        $search=$request->input('search');
        $brand_new_cars=$request->input('brand_new_cars');
        $sort_by=$request->input('sort_by');

        $yearRange = Cars::where('is_active', '1')
        ->selectRaw('MIN(YEAR(STR_TO_DATE(yom, "%M %Y"))) as min_year, MAX(YEAR(STR_TO_DATE(yom, "%M %Y"))) as max_year')
        ->first();   
        $priceRange = Cars::where('is_active', '1')
        ->selectRaw('MIN(CAST(price AS DECIMAL)) as min_sal, MAX(CAST(price AS DECIMAL)) as max_sal')
        ->first();

        // $minYear = $yearRange->min_year;
        $minYear = 1950;
        $maxYear = $yearRange->max_year;
        $minPrice = $priceRange->min_sal;
        $maxPrice = $priceRange->max_sal;   

        


        $priceRanges = [
            "Under $5000" => ["start" => 0, "end" => 5000],
            "$5000 - $50000" => ["start" => 5000, "end" => 50000],
            "$50000 - $100000" => ["start" => 50000, "end" => 100000],
            "$100000 - $200000" => ["start" => 100000, "end" => 200000],
            "$200000 - $300000" => ["start" => 200000, "end" => 300000],
            "Above $300000" => ["start" => 300000, "end" => null] // Use PHP_INT_MAX for "Above"
        ];

        // Initialize the query for cars
        // $carsQuery = CarDataJpOp::query();
      
        //    DB::enableQueryLog();
            // Query for Blog table
            $blogCars = Cars::when($jdmBrand, function ($query, $jdmBrand) {
                    $brand_arr = array_filter($jdmBrand);
                    if ($brand_arr) {
                        return $query->whereIn(DB::raw('LOWER(make)'), $jdmBrand);
                    }   
                })
                ->when($jdmModel, function ($query, $jdmModel) {
                    $brand_arr = array_filter($jdmModel);

                    // echo json_encode($brand_arr);die();
                    if ($brand_arr) {
                        // return $query->where('model', $jdmModel);
                        return $query->whereIn('model', $jdmModel);
                    }   
                })
                ->when($price_range, function ($query) use ($price_range, $priceRanges) {
                    $result = $this->getPriceRangestart($price_range, $priceRanges);
                    return $query->whereRaw('CAST(REGEXP_REPLACE(price, "[,\\\\s]", "") AS DECIMAL(10, 0)) BETWEEN ? AND ?', [
                        $result['start_price_num'], 
                        $result['end_price_num']
                    ]);       
                })
                ->when($search, function ($query, $search) {
                    return $query->where('model', 'like', '%' . $search . '%');
                })
                ->when($jdmYear, function ($query, $jdmYear) {
                    $query->whereRaw('REGEXP_REPLACE(yom, "[,\\\\s]", "") REGEXP "^[0-9]+$"');
                    // $yom_arr = array_filter($jdmYear);
                    if ($jdmYear!="") {
                        return $query->whereRaw('CAST(REGEXP_REPLACE(yom, "[,\\\\s]", "") AS DECIMAL(10, 0)) = ?', [
                            $jdmYear
                        ]);
                    } 
                })
                ->when($price_range_scale, function ($query) use ($price_range_scale) {
                    $query->whereRaw('REGEXP_REPLACE(price, "[,\\\\s]", "") REGEXP "^[0-9]+$"');
                    if($price_range_scale !=""){  
                        $parts = explode(',', $price_range_scale);
                        $startValue = trim($parts[0]);
                        $endValue = trim($parts[1]);
                        $carsQuery = $query->where(function ($q) use ($startValue,$endValue) {
                            return $q->whereRaw('CAST(REGEXP_REPLACE(price, "[,\\\\s]", "") AS DECIMAL(10, 0)) BETWEEN ? AND ?', [
                                $startValue, 
                                $endValue
                            ]);
                            // return $q->whereBetween('price', [$startValue, $endValue]);
                        });
                    }
                })
                ->when($transmission, function ($query, $transmission) {
                    $transmission_arr = array_filter($transmission);
                    if ($transmission_arr) {
                        return $query->whereIn('transmission', $transmission_arr);
                    } 
                })
                ->when($brand_new_cars, function ($query, $brand_new_cars) {
                    return $query->where('yom', $brand_new_cars);
                })
                ->when($sort_by, function ($query, $sort_by) {
                    switch ($sort_by) {
                        case 'price_low_high':
                           return  $query->orderBy('price', 'asc');
                            break;
                        case 'price_high_low':
                            return  $query->orderBy('price', 'desc');
                            break;
                        case 'recent':
                            $recentCarIds = $query->orderBy('id', 'desc')
                            ->limit(100)
                            ->pluck('id');
                        
                        // Then reset the query and use these IDs
                        return $query = $query->whereIn('id', $recentCarIds);
                        break;
                    }
                })
                ->where('is_active','1')
                ->select('model','price','image','id','make','title');
                // ->get();
                // dd(DB::getQueryLog());


      
            // Query for Heavy table
            $heavyCars = Heavy::when($jdmBrand, function ($query, $jdmBrand) {
                $brand_arr = array_filter($jdmBrand);
                if ($brand_arr) {
                    return $query->whereIn(DB::raw('LOWER(make)'), $jdmBrand);
                } 
                // return $query->where(DB::raw('LOWER(make)'), $jdmBrand);
                })
                ->when($jdmModel, function ($query, $jdmModel) {
                    $brand_arr = array_filter($jdmModel);
                    if ($brand_arr) {
                        // return $query->where('model', $jdmModel);
                        return $query->whereIn('model', $jdmModel);
                    }  
                })
                ->when($price_range, function ($query) use ($price_range, $priceRanges) {
                    $result = $this->getPriceRangestart($price_range, $priceRanges);
                    return $query->whereRaw('CAST(REGEXP_REPLACE(price, "[,\\\\s]", "") AS DECIMAL(10, 0)) BETWEEN ? AND ?', [
                        $result['start_price_num'], 
                        $result['end_price_num']
                    ]);       
                })
                ->when($jdmYear, function ($query, $jdmYear) {
                    $query->whereRaw('REGEXP_REPLACE(yom, "[,\\\\s]", "") REGEXP "^[0-9]+$"');
                    // $yom_arr = array_filter($jdmYear);
                    if ($jdmYear!="") {
                        return $query->whereRaw('CAST(REGEXP_REPLACE(yom, "[,\\\\s]", "") AS DECIMAL(10, 0)) = ?', [
                            $jdmYear
                        ]);
                    } 
                })
                ->when($transmission, function ($query, $transmission) {
                    $transmission_arr = array_filter($transmission);
                    if ($transmission_arr) {
                        return $query->whereIn('transmission', $transmission_arr);
                    } 
                })
                ->when($price_range_scale, function ($query) use ($price_range_scale) {
                    $query->whereRaw('REGEXP_REPLACE(price, "[,\\\\s]", "") REGEXP "^[0-9]+$"');
                    if($price_range_scale !=""){  
                        $parts = explode(',', $price_range_scale);
                        $startValue = trim($parts[0]);
                        $endValue = trim($parts[1]);
                        $carsQuery = $query->where(function ($q) use ($startValue,$endValue) {
                            return $q->whereRaw('CAST(REGEXP_REPLACE(price, "[,\\\\s]", "") AS DECIMAL(10, 0)) BETWEEN ? AND ?', [
                                $startValue, 
                                $endValue
                            ]);
                        });
                    }
                })
                ->when($search, function ($query, $search) {
                    return $query->where('model', 'like', '%' . $search . '%');
                })
                ->when($brand_new_cars, function ($query, $brand_new_cars) {
                    return $query->where('yom', $brand_new_cars);
                })
                ->when($brand_new_cars, function ($query, $brand_new_cars) {
                    return $query->where('yom', $brand_new_cars);
                })
                ->when($sort_by, function ($query, $sort_by) {
                    switch ($sort_by) {
                        case 'price_low_high':
                           return  $query->orderBy('price', 'asc');
                            break;
                        case 'price_high_low':
                            return  $query->orderBy('price', 'desc');
                            break;
                        case 'recent':
                            $recentCarIds = $query->orderBy('id', 'desc')
                            ->limit(100)
                            ->pluck('id');
                        
                        // Then reset the query and use these IDs
                        return $query = $query->whereIn('id', $recentCarIds);
                        break;
                    }
                })
                ->where('is_active','1')
                ->select('model','price','image','id','make','title');

            // Query for Small Heavy table
            $smallHeavyCars = SmallHeavy::when($jdmBrand, function ($query, $jdmBrand) {
                // return $query->where(DB::raw('LOWER(make)'), $jdmBrand);
                $brand_arr = array_filter($jdmBrand);
                if ($brand_arr) {
                    return $query->whereIn(DB::raw('LOWER(make)'), $jdmBrand);
                } 
                })
                ->when($jdmModel, function ($query, $jdmModel) {
                    $brand_arr = array_filter($jdmModel);
                    if ($brand_arr) {
                        // return $query->where('model', $jdmModel);
                        return $query->whereIn('model', $jdmModel);
                    }  
                })
                ->when($price_range, function ($query) use ($price_range, $priceRanges) {
                    $result = $this->getPriceRangestart($price_range, $priceRanges);
                    return $query->whereRaw('CAST(REGEXP_REPLACE(price, "[,\\\\s]", "") AS DECIMAL(10, 0)) BETWEEN ? AND ?', [
                        $result['start_price_num'], 
                        $result['end_price_num']
                    ]);       
                })
                ->when($jdmYear, function ($query, $jdmYear) {
                    $query->whereRaw('REGEXP_REPLACE(yom, "[,\\\\s]", "") REGEXP "^[0-9]+$"');
                    // $yom_arr = array_filter($jdmYear);
                    if ($jdmYear!="") {
                        return $query->whereRaw('CAST(REGEXP_REPLACE(yom, "[,\\\\s]", "") AS DECIMAL(10, 0)) = ?', [
                            $jdmYear
                        ]);
                    }  
                })
                ->when($price_range_scale, function ($query) use ($price_range_scale) {
                    $query->whereRaw('REGEXP_REPLACE(price, "[,\\\\s]", "") REGEXP "^[0-9]+$"');
                    if($price_range_scale !=""){  
                        $parts = explode(',', $price_range_scale);
                        $startValue = trim($parts[0]);
                        $endValue = trim($parts[1]);
                        $carsQuery = $query->where(function ($q) use ($startValue,$endValue) {
                            return $q->whereRaw('CAST(REGEXP_REPLACE(price, "[,\\\\s]", "") AS DECIMAL(10, 0)) BETWEEN ? AND ?', [
                                $startValue, 
                                $endValue
                            ]);
                        });
                    }
                })
                ->when($transmission, function ($query, $transmission) {
                    $transmission_arr = array_filter($transmission);
                    if ($transmission_arr) {
                        return $query->whereIn('transmission', $transmission_arr);
                    } 
                })
                ->when($search, function ($query, $search) {
                    return $query->where('model', 'like', '%' . $search . '%');
                })  
                ->when($brand_new_cars, function ($query, $brand_new_cars) {
                    return $query->where('yom', $brand_new_cars);
                })
                ->when($sort_by, function ($query, $sort_by) {
                    switch ($sort_by) {
                        case 'price_low_high':
                           return  $query->orderBy('price', 'asc');
                            break;
                        case 'price_high_low':
                            return  $query->orderBy('price', 'desc');
                            break;
                        case 'recent':
                            $recentCarIds = $query->orderBy('id', 'desc')
                            ->limit(100)
                            ->pluck('id');
                        
                        // Then reset the query and use these IDs
                        return $query = $query->whereIn('id', $recentCarIds);
                        break;
                    }
                })
                ->where('is_active','1')
                ->select('model','price','image','id','make','title');

        
       

        $cars = $blogCars
        ->union($heavyCars)
        ->union($smallHeavyCars)
        ->orderBy('model')
        // ->distinct()
        ->paginate(12); 

       
    // Transform cars into an array for the view
    $cars_array = $cars->map(function ($car) {
    // $car_image=$this->last_image($car->pictures);
        return [
            'company_en' => $car->make,
            'model_name_en' => $car->title,
            'start_price_num' => $car->price,
            'picture' =>$car->image,
            'id' => $car->id,
        ];
    });


    // echo json_encode($cars_array);die();



 


    // Get additional data
    $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();
    $price_range = $this->getJDMPriceRange();
    $jdm_legend = Brand::where('status', 'enable')->get();

    $jdm_core_brand = Brand::where('status', 'enable')->get();

    // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
    //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
    //              ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();



    // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();






    // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();

    $brands = $jdm_legend
    ->concat($jdm_legend_heavy)
    ->concat($jdm_legend_small_heavy)
    ->unique('slug')
    ->values();


    // echo json_encode($brands);die();


   
    $result = [];


    // DB::enableQueryLog();
    $processModels = function($query, $makeColumn) use ($brands,&$result) {
        $query->whereIn(DB::raw('LOWER(' . $makeColumn . '.make)'), $brands->pluck('slug'))
            ->select(
                DB::raw('LOWER(' . $makeColumn . '.make) as brand_slug'), 
                DB::raw('TRIM(model) as model')
            )
            ->distinct()
            ->orderBy('make')
            ->chunk(1000, function($models) use (&$result) {
                foreach ($models as $model) {
                    // $normalizedName = ucwords(strtolower($model->model));
                    // $result[$model->brand_slug][] = $normalizedName;    
                    $brand_slug = trim(strtolower($model->brand_slug));
                    $normalizedName = ucwords(strtolower($model->model));

                    // Store the model only if it doesn't exist for this brand
                    if (!in_array($normalizedName, $result[$brand_slug] ?? [])) {
                        $result[$brand_slug][] = $normalizedName;
                    }
                
                    // Use a set (unique combination of brand_slug and model) to track unique models
                    // $result[$model->brand_slug][$normalizedName] = true;
                }
            });
    };

    
    // Process models from three tables
    $processModels(Cars::query(), 'blog');
    // dd(DB::getQueryLog());
    $processModels(Heavy::query(), 'heavy');
   

    $processModels(SmallHeavy::query(), 'small_heavy');

    foreach ($result as $brand_slug => $models) {
        $result[$brand_slug] = array_values(array_unique($models));  // Get only the model names (keys)
    }
    

    



    // $jdm_brand['car']=$jdm_legend;
    // $jdm_brand['heavy']=$jdm_legend_heavy;  
    // $jdm_brand['small_heavy']=$jdm_legend_heavy;
    // $jdm_brand=$this->jdm_brands();




    

    return view('jdm_stock_all_listing', [
        'seo_setting' => $seo_setting,
        'brands' => $brands,
        // 'cities' => $cities,
        'cars_array' => $cars_array,
        'listing_ads' => $listing_ads,
        'cars' => $cars,
        // 'brand_count' => $models,
        'price_range' => $price_range,
        // 'transmission' => $transmissions,
        // 'jdm_legend'=>$jdm_brand,
        'jdm_core_brand'=>$jdm_core_brand,
        'models'=>$models,
        'minYear'=>$minYear,
        'maxYear'=>$maxYear,
        'minPrice'=>$minPrice,
        'maxPrice'=>$maxPrice,
        'brand_arr'=>$result

    ]);
    }


    public function auction_listing($category,$slug){
        $car = Auct_lots_xml_jp::where('id',$slug)->firstOrFail();
        $process_data_en = $this->parseCustomFormat($car->parsed_data_en);
        $images=$this->last_image($car->pictures);
        $related_listings = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->where('brand_id', $car->brand_id)->where('id', '!=', $car->id)->get()->take(6);

        $reviews = Review::with('user')->where('car_id', $car->id)->where('status', 'enable')->latest()->get();
        $listing_ads = AdsBanner::where('position_key', 'listing_detail_page_banner')->first();

        $delivery_charges = DeliveryCharge::all();
        $jdm_core_brand = Brand::where('status', 'enable')->get();

        
        return view('auction-listing1', [
            'car' => $car,
            'galleries' => $images,
            'related_listings' => $related_listings,
            'reviews' => $reviews,
            'listing_ads' => $listing_ads,
            'delivery_charges'=>$delivery_charges,
            'process_data_en'=>$process_data_en,
            'jdm_core_brand'=>$jdm_core_brand,
            'url_link'=>url()->full(),
            'usd_rate'=>$this->usdRate,
            'head_title'=>$category
        ]);

    }


    public function jdm_stock_all_listing($slug){
        $seo_setting = SeoSetting::where('id', 1)->first();
        $blogCar =  DB::table('blog')->where('id', $slug)->first();
        $heavyCar = Heavy::where('id', $slug)->first();
        $smallHeavyCar = SmallHeavy::where('id', $slug)->first();
        
        // Check which one exists
        $car = $blogCar ?? $heavyCar ?? $smallHeavyCar;

        $car_accessories=array(
            'abs'=>'ABS Anti-lock braking systems',
            'aw'=>'AW Alloy Wheels',
            'pw'=>'PW Power Windows',
            'ps'=>'PS Power Steering',
            'ab'=>'AB Airbag',
            'sr'=>'SR Sunroof'
        );
        $accesories=[];

        foreach($car_accessories as $key=>$value){
            if($car->$key == '1'){
               array_push($accesories,$value);
            }
        }

        if(!empty($blogCar)){
            $car_images = DB::table('add_product_images')
            ->where('category', $car->id)
            ->get();
            $image_folder='Cars';
        }
        if(!empty($heavyCar)){
            $car_images = DB::table('add_heavy_images')
            ->where('category', $car->id)
            ->get();
            $image_folder='heavy_photos';   
        }
        if(!empty($smallHeavyCar)){
            $car_images = DB::table('add_small_images')
            ->where('category', $car->id)
            ->get();
            $image_folder='small_heavy';
        }
     

 

        // echo json_encode($car->seo_title);die();
        
        $listing_ads = AdsBanner::where('position_key', 'listing_detail_page_banner')->first();
    

        $delivery_charges = DeliveryCharge::all();
        $jdm_core_brand = Brand::where('status', 'enable')->get();

        // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        //             ->join('brand_translations as bt','bt.brand_id','=','b.id')
        //             ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();

        // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();




        // $jdm_brand['car']=$jdm_legend;
        // $jdm_brand['heavy']=$jdm_legend_heavy;
        // $jdm_brand['small_heavy']=$jdm_legend_heavy;
        $jdm_brand=$this->jdm_brands();

    
        return view('jdm_stock_all_listing1', [
            'car' => $car,
            'listing_ads' => $listing_ads,
            'delivery_charges'=>$delivery_charges,
            // 'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand,
            'url_link'=>url()->full(),
            'car_images'=>$car_images,
            'image_folder'=>$image_folder,
            'slug'=>$slug,
            'accesories'=>$accesories
        ]);
    
    }
    // public function jdm_brand_new(Request $request){
    //     $models=[];
    //     $currentYear = now()->year;
    //     $previousYear = now()->subYear()->year;
    //     $seo_setting = SeoSetting::where('id', 1)->first();

       
    //     $brands="";
        
    //     $models=$this->getJdmBrandModels($request->jdm_brand);
 

    //     $jdmBrand = $request->input('jdm_brand');
    //     $jdmModel = $request->input('jdm_model');
    //     $jdmYear = $request->input('jdm_year');
    //     $minPrice = $request->input('min_price');
    //     $maxPrice = $request->input('max_price');
    //     $price_range=$request->input('price_range');
    //     $price_range_scale=$request->input('price_range_scale');
    //     $transmission=$request->input('transmission');
    //     $search=$request->input('search');
    //     $brand_new_cars=$request->input('brand_new_cars');
    //     $sort_by=$request->input('sort_by');

    //     $yearRange = Cars::where('is_active', '1')
    //     ->selectRaw('MIN(YEAR(STR_TO_DATE(yom, "%M %Y"))) as min_year, MAX(YEAR(STR_TO_DATE(yom, "%M %Y"))) as max_year')
    //     ->first();   
    //     $priceRange = Cars::where('is_active', '1')
    //     ->selectRaw('MIN(CAST(price AS DECIMAL)) as min_sal, MAX(CAST(price AS DECIMAL)) as max_sal')
    //     ->first();

    //     // $minYear = $yearRange->min_year;
    //     $minYear = 1950;
    //     // $maxYear = $yearRange->max_year;
    //     $maxYear = $currentYear;
    //     $minPrice = $priceRange->min_sal;
    //     $maxPrice = $priceRange->max_sal; 
        
        
    //     $hasPriceRangeScale = false;
    //     $startValue = $minPrice;
    //     $endValue = $maxPrice;


    //     $priceRanges = [
    //         "Under $5000" => ["start" => 0, "end" => 5000],
    //         "$5000 - $50000" => ["start" => 5000, "end" => 50000],
    //         "$50000 - $100000" => ["start" => 50000, "end" => 100000],
    //         "$100000 - $200000" => ["start" => 100000, "end" => 200000],
    //         "$200000 - $300000" => ["start" => 200000, "end" => 300000],
    //         "Above $300000" => ["start" => 300000, "end" => null] // Use PHP_INT_MAX for "Above"
    //     ];

    //     // Initialize the query for cars
    //     // $carsQuery = CarDataJpOp::query();
      
    //     //    DB::enableQueryLog();
    //         // Query for Blog table
    //         $blogCars = Cars::when($jdmBrand, function ($query, $jdmBrand) {
    //                 $brand_arr = array_filter($jdmBrand);
    //                 if ($brand_arr) {
    //                     return $query->whereIn(DB::raw('LOWER(make)'), $jdmBrand);
    //                 }   
    //             })
    //             ->when($jdmModel, function ($query, $jdmModel) {
    //                 $brand_arr = array_filter($jdmModel);

    //                 // echo json_encode($brand_arr);die();
    //                 if ($brand_arr) {
    //                     // return $query->where('model', $jdmModel);
    //                     return $query->whereIn('model', $jdmModel);
    //                 }   
    //             })
    //             ->when($price_range, function ($query) use ($price_range, $priceRanges) {
    //                 $result = $this->getPriceRangestart($price_range, $priceRanges);
    //                 return $query->whereRaw('CAST(REGEXP_REPLACE(price, "[,\\\\s]", "") AS DECIMAL(10, 0)) BETWEEN ? AND ?', [
    //                     $result['start_price_num'], 
    //                     $result['end_price_num']
    //                 ]);       
    //             })
    //             ->when($search, function ($query, $search) {
    //                 return $query->where('model', 'like', '%' . $search . '%');
    //             })
    //             ->when($jdmYear, function ($query, $jdmYear) {
    //                 $query->whereRaw('REGEXP_REPLACE(yom, "[,\\\\s]", "") REGEXP "^[0-9]+$"');
    //                 // $yom_arr = array_filter($jdmYear);
    //                 if ($jdmYear!="") {
    //                     return $query->whereRaw('CAST(REGEXP_REPLACE(yom, "[,\\\\s]", "") AS DECIMAL(10, 0)) = ?', [
    //                         $jdmYear
    //                     ]);
    //                 } 
    //             })
    //             ->when($price_range_scale, function ($query) use ($price_range_scale) {
    //                 $query->whereRaw('REGEXP_REPLACE(price, "[,\\\\s]", "") REGEXP "^[0-9]+$"');
    //                 if($price_range_scale !=""){  
    //                     $parts = explode(',', $price_range_scale);
    //                     $startValue = trim($parts[0]);
    //                     $endValue = trim($parts[1]);
    //                     $carsQuery = $query->where(function ($q) use ($startValue,$endValue) {
    //                         return $q->whereRaw('CAST(REGEXP_REPLACE(price, "[,\\\\s]", "") AS DECIMAL(10, 0)) BETWEEN ? AND ?', [
    //                             $startValue, 
    //                             $endValue
    //                         ]);
    //                         // return $q->whereBetween('price', [$startValue, $endValue]);
    //                     });
    //                 }
    //             })
    //             ->when($transmission, function ($query, $transmission) {
    //                 $transmission_arr = array_filter($transmission);
    //                 if ($transmission_arr) {
    //                     return $query->whereIn('transmission', $transmission_arr);
    //                 } 
    //             })
    //             ->when($brand_new_cars, function ($query, $brand_new_cars) {
    //                 return $query->where('yom', $brand_new_cars);
    //             })
    //             ->when($sort_by, function ($query, $sort_by) {
    //                 switch ($sort_by) {
    //                     case 'price_low_high':
    //                        return  $query->orderBy('price', 'asc');
    //                         break;
    //                     case 'price_high_low':
    //                         return  $query->orderBy('price', 'desc');
    //                         break;
    //                     case 'recent':
    //                         $recentCarIds = $query->orderBy('id', 'desc')
    //                         ->limit(100)
    //                         ->pluck('id');
                        
    //                     // Then reset the query and use these IDs
    //                     return $query = $query->whereIn('id', $recentCarIds);
    //                     break;
    //                 }
    //             })
    //             ->where('is_active','1')
    //             ->whereBetween('yom', [$previousYear,$currentYear])
    //             ->select('model','price','image','id','make','title');
    //             // ->get();
    //             // dd(DB::getQueryLog());


      
    //         // Query for Heavy table
    //         $heavyCars = Heavy::when($jdmBrand, function ($query, $jdmBrand) {
    //             $brand_arr = array_filter($jdmBrand);
    //             if ($brand_arr) {
    //                 return $query->whereIn(DB::raw('LOWER(make)'), $jdmBrand);
    //             } 
    //             // return $query->where(DB::raw('LOWER(make)'), $jdmBrand);
    //             })
    //             ->when($jdmModel, function ($query, $jdmModel) {
    //                 $brand_arr = array_filter($jdmModel);
    //                 if ($brand_arr) {
    //                     // return $query->where('model', $jdmModel);
    //                     return $query->whereIn('model', $jdmModel);
    //                 }  
    //             })
    //             ->when($price_range, function ($query) use ($price_range, $priceRanges) {
    //                 $result = $this->getPriceRangestart($price_range, $priceRanges);
    //                 return $query->whereRaw('CAST(REGEXP_REPLACE(price, "[,\\\\s]", "") AS DECIMAL(10, 0)) BETWEEN ? AND ?', [
    //                     $result['start_price_num'], 
    //                     $result['end_price_num']
    //                 ]);       
    //             })
    //             ->when($jdmYear, function ($query, $jdmYear) {
    //                 $query->whereRaw('REGEXP_REPLACE(yom, "[,\\\\s]", "") REGEXP "^[0-9]+$"');
    //                 // $yom_arr = array_filter($jdmYear);
    //                 if ($jdmYear!="") {
    //                     return $query->whereRaw('CAST(REGEXP_REPLACE(yom, "[,\\\\s]", "") AS DECIMAL(10, 0)) = ?', [
    //                         $jdmYear
    //                     ]);
    //                 } 
    //             })
    //             ->when($transmission, function ($query, $transmission) {
    //                 $transmission_arr = array_filter($transmission);
    //                 if ($transmission_arr) {
    //                     return $query->whereIn('transmission', $transmission_arr);
    //                 } 
    //             })
    //             ->when($price_range_scale, function ($query) use ($price_range_scale) {
    //                 $query->whereRaw('REGEXP_REPLACE(price, "[,\\\\s]", "") REGEXP "^[0-9]+$"');
    //                 if($price_range_scale !=""){  
    //                     $parts = explode(',', $price_range_scale);
    //                     $startValue = trim($parts[0]);
    //                     $endValue = trim($parts[1]);
    //                     $carsQuery = $query->where(function ($q) use ($startValue,$endValue) {
    //                         return $q->whereRaw('CAST(REGEXP_REPLACE(price, "[,\\\\s]", "") AS DECIMAL(10, 0)) BETWEEN ? AND ?', [
    //                             $startValue, 
    //                             $endValue
    //                         ]);
    //                     });
    //                 }
    //             })
    //             ->when($search, function ($query, $search) {
    //                 return $query->where('model', 'like', '%' . $search . '%');
    //             })
    //             ->when($brand_new_cars, function ($query, $brand_new_cars) {
    //                 return $query->where('yom', $brand_new_cars);
    //             })
    //             ->when($brand_new_cars, function ($query, $brand_new_cars) {
    //                 return $query->where('yom', $brand_new_cars);
    //             })
    //             ->when($sort_by, function ($query, $sort_by) {
    //                 switch ($sort_by) {
    //                     case 'price_low_high':
    //                        return  $query->orderBy('price', 'asc');
    //                         break;
    //                     case 'price_high_low':
    //                         return  $query->orderBy('price', 'desc');
    //                         break;
    //                     case 'recent':
    //                         $recentCarIds = $query->orderBy('id', 'desc')
    //                         ->limit(100)
    //                         ->pluck('id');
                        
    //                     // Then reset the query and use these IDs
    //                     return $query = $query->whereIn('id', $recentCarIds);
    //                     break;
    //                 }
    //             })
    //             ->where('is_active','1')
    //             ->whereBetween('yom', [$previousYear,$currentYear])
    //             ->select('model','price','image','id','make','title');

    //         // Query for Small Heavy table
    //         $smallHeavyCars = SmallHeavy::when($jdmBrand, function ($query, $jdmBrand) {
    //             // return $query->where(DB::raw('LOWER(make)'), $jdmBrand);
    //             $brand_arr = array_filter($jdmBrand);
    //             if ($brand_arr) {
    //                 return $query->whereIn(DB::raw('LOWER(make)'), $jdmBrand);
    //             } 
    //             })
    //             ->when($jdmModel, function ($query, $jdmModel) {
    //                 $brand_arr = array_filter($jdmModel);
    //                 if ($brand_arr) {
    //                     // return $query->where('model', $jdmModel);
    //                     return $query->whereIn('model', $jdmModel);
    //                 }  
    //             })
    //             ->when($price_range, function ($query) use ($price_range, $priceRanges) {
    //                 $result = $this->getPriceRangestart($price_range, $priceRanges);
    //                 return $query->whereRaw('CAST(REGEXP_REPLACE(price, "[,\\\\s]", "") AS DECIMAL(10, 0)) BETWEEN ? AND ?', [
    //                     $result['start_price_num'], 
    //                     $result['end_price_num']
    //                 ]);       
    //             })
    //             ->when($jdmYear, function ($query, $jdmYear) {
    //                 $query->whereRaw('REGEXP_REPLACE(yom, "[,\\\\s]", "") REGEXP "^[0-9]+$"');
    //                 // $yom_arr = array_filter($jdmYear);
    //                 if ($jdmYear!="") {
    //                     return $query->whereRaw('CAST(REGEXP_REPLACE(yom, "[,\\\\s]", "") AS DECIMAL(10, 0)) = ?', [
    //                         $jdmYear
    //                     ]);
    //                 }  
    //             })
    //             ->when($price_range_scale, function ($query) use ($price_range_scale) {
    //                 $query->whereRaw('REGEXP_REPLACE(price, "[,\\\\s]", "") REGEXP "^[0-9]+$"');
    //                 if($price_range_scale !=""){  
    //                     $parts = explode(',', $price_range_scale);
    //                     $startValue = trim($parts[0]);
    //                     $endValue = trim($parts[1]);
    //                     $carsQuery = $query->where(function ($q) use ($startValue,$endValue) {
    //                         return $q->whereRaw('CAST(REGEXP_REPLACE(price, "[,\\\\s]", "") AS DECIMAL(10, 0)) BETWEEN ? AND ?', [
    //                             $startValue, 
    //                             $endValue
    //                         ]);
    //                     });
    //                 }
    //             })
    //             ->when($transmission, function ($query, $transmission) {
    //                 $transmission_arr = array_filter($transmission);
    //                 if ($transmission_arr) {
    //                     return $query->whereIn('transmission', $transmission_arr);
    //                 } 
    //             })
    //             ->when($search, function ($query, $search) {
    //                 return $query->where('model', 'like', '%' . $search . '%');
    //             })  
    //             ->when($brand_new_cars, function ($query, $brand_new_cars) {
    //                 return $query->where('yom', $brand_new_cars);
    //             })
    //             ->when($sort_by, function ($query, $sort_by) {
    //                 switch ($sort_by) {
    //                     case 'price_low_high':
    //                        return  $query->orderBy('price', 'asc');
    //                         break;
    //                     case 'price_high_low':
    //                         return  $query->orderBy('price', 'desc');
    //                         break;
    //                     case 'recent':
    //                         $recentCarIds = $query->orderBy('id', 'desc')
    //                         ->limit(100)
    //                         ->pluck('id');
                        
    //                     // Then reset the query and use these IDs
    //                     return $query = $query->whereIn('id', $recentCarIds);
    //                     break;
    //                 }
    //             })
    //             ->where('is_active','1')
    //             ->whereBetween('yom', [$previousYear,$currentYear])
    //             ->select('model','price','image','id','make','title');

      
       

    //     $cars = $blogCars
    //     ->union($heavyCars)
    //     ->union($smallHeavyCars)
    //     ->orderBy('model')
    //     // ->distinct()
    //     ->paginate(12); 

    //     // dd(DB::getQueryLog());


    
    // // Pagination
    // // $cars = $carsQuery->paginate(12);

    // // Transform cars into an array for the view
    // $cars_array = $cars->map(function ($car) {
    // // $car_image=$this->last_image($car->pictures);
    //     return [
    //         'company_en' => $car->make,
    //         'model_name_en' => $car->title,
    //         'start_price_num' => $car->price,
    //         'picture' =>$car->image,
    //         'id' => $car->id,
    //     ];
    // });


    // // echo json_encode($cars_array);die();



 


    // // Get additional data
    // $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();
    // // $cities = City::with('translate')->get();

    // // $brand_count = CarDataJpOp::selectRaw('company_en,company,COUNT(*) as count')
    // //     ->groupBy('company_en')
    // //     ->having('count', '>', 1)
    // //     ->get();

    // // $transmission = CarDataJpOp::selectRaw('transmission_en, COUNT(*) as count')
    // //         ->groupBy('transmission_en')
    // //         ->having('count', '>', 1)
    // //         ->get();

    // // $price_range = $this->getPriceRange();
    // $price_range = $this->getJDMPriceRange();
    // // $price_range=$this->SelectedJdmRange($type,$priceRanges,$slug,
    // // $hasPriceRangeScale,$startValue,$endValue);
    // $jdm_legend = Brand::where('status', 'enable')->get();

 



    // // $transmissions = \DB::select(
    // //     'SELECT transmission, SUM(model_count) as total_count FROM (
    // //         (SELECT transmission, COUNT(*) as model_count FROM blog WHERE transmission IS NOT NULL AND transmission != "" GROUP BY transmission)
    // //         UNION ALL
    // //         (SELECT transmission, COUNT(*) as model_count FROM heavy WHERE transmission IS NOT NULL AND transmission != "" GROUP BY transmission)
    // //         UNION ALL
    // //         (SELECT transmission, COUNT(*) as model_count FROM small_heavy WHERE transmission IS NOT NULL AND transmission != "" GROUP BY transmission)
    // //     ) as combined_models
    // //     GROUP BY transmission
    // //     HAVING total_count > 0'
    // // );
    // $jdm_core_brand = Brand::where('status', 'enable')->get();

    // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
    //              ->join('brand_translations as bt','bt.brand_id','=','b.id')
    //              ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();



    // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();






    // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
    // ->join('brand_translations as bt','bt.brand_id','=','b.id')
    // ->where('bt.lang_code',Session::get('front_lang'))
    // ->select('b.slug','bt.name as brand_name')
    // ->distinct('b.slug')->get();

    // $brands = $jdm_legend
    // ->concat($jdm_legend_heavy)
    // ->concat($jdm_legend_small_heavy)
    // ->unique('slug')
    // ->values();


    // // echo json_encode($brands);die();


   
    // $result = [];


    // // DB::enableQueryLog();
    // $processModels = function($query, $makeColumn) use ($brands,&$result) {
    //     $query->whereIn(DB::raw('LOWER(' . $makeColumn . '.make)'), $brands->pluck('slug'))
    //         ->select(
    //             DB::raw('LOWER(' . $makeColumn . '.make) as brand_slug'), 
    //             DB::raw('TRIM(model) as model')
    //         )
    //         ->distinct()
    //         ->orderBy('make')
    //         ->chunk(1000, function($models) use (&$result) {
    //             foreach ($models as $model) {
    //                 // $normalizedName = ucwords(strtolower($model->model));
    //                 // $result[$model->brand_slug][] = $normalizedName;    
    //                 $brand_slug = trim(strtolower($model->brand_slug));
    //                 $normalizedName = ucwords(strtolower($model->model));

    //                 // Store the model only if it doesn't exist for this brand
    //                 if (!in_array($normalizedName, $result[$brand_slug] ?? [])) {
    //                     $result[$brand_slug][] = $normalizedName;
    //                 }
                
    //                 // Use a set (unique combination of brand_slug and model) to track unique models
    //                 // $result[$model->brand_slug][$normalizedName] = true;
    //             }
    //         });
    // };

    
    // // Process models from three tables
    // $processModels(Cars::query(), 'blog');
    // // dd(DB::getQueryLog());
    // $processModels(Heavy::query(), 'heavy');
   

    // $processModels(SmallHeavy::query(), 'small_heavy');

    // foreach ($result as $brand_slug => $models) {
    //     $result[$brand_slug] = array_values(array_unique($models));  // Get only the model names (keys)
    // }
    

    



    // $jdm_brand['car']=$jdm_legend;
    // $jdm_brand['heavy']=$jdm_legend_heavy;  
    // $jdm_brand['small_heavy']=$jdm_legend_heavy;




    

    // return view('jdm-brand-new', [
    //     'seo_setting' => $seo_setting,
    //     'brands' => $brands,
    //     // 'cities' => $cities,
    //     'cars_array' => $cars_array,
    //     'listing_ads' => $listing_ads,
    //     'cars' => $cars,
    //     // 'brand_count' => $models,
    //     'price_range' => $price_range,
    //     // 'transmission' => $transmissions,
    //     'jdm_legend'=>$jdm_brand,
    //     'jdm_core_brand'=>$jdm_core_brand,
    //     'models'=>$models,
    //     'minYear'=>$minYear,
    //     'maxYear'=>$maxYear,
    //     'minPrice'=>$minPrice,
    //     'maxPrice'=>$maxPrice,
    //     'brand_arr'=>$result

    // ]);
    // }
    public function jdm_brand_new(Request $request){
        $priceRanges = [
            "Under $5000" => ["start" => 0, "end" => 5000],
            "$5000 - $50000" => ["start" => 5000, "end" => 50000],
            "$50000 - $100000" => ["start" => 50000, "end" => 100000],
            "$100000 - $200000" => ["start" => 100000, "end" => 200000],
            "$200000 - $300000" => ["start" => 200000, "end" => 300000],
            "Above $300000" => ["start" => 300000, "end" => PHP_INT_MAX] // Use PHP_INT_MAX for "Above"
        ];   
        $jdm_legend = Brand::where('status', 'enable')->get();
        $seo_setting = SeoSetting::where('id', 1)->first();

   
        // $brand_label=Brand::where('slug',$slug)->first();
     
        // $jdm_legend = Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();


        // $jdm_legend_heavy = Heavy::join('brands as b', DB::raw('LOWER(heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();

        // $jdm_legend_small_heavy = SmallHeavy::join('brands as b', DB::raw('LOWER(small_heavy.make)'), '=', 'b.slug')
        // ->join('brand_translations as bt','bt.brand_id','=','b.id')
        // ->where('bt.lang_code',Session::get('front_lang'))
        // ->select('b.slug','bt.name as brand_name')
        // ->distinct('b.slug')->get();
        $jdm_core_brand = Brand::where('status', 'enable')->get();


     
       $brands=Cars::join('brands as b', DB::raw('LOWER(blog.make)'), '=', 'b.slug')
        ->join('brand_translations as bt','bt.brand_id','=','b.id')
        ->where('bt.lang_code',Session::get('front_lang'))
        ->whereNotNull(DB::raw("REGEXP_SUBSTR(yom, '[0-9]{4}')"))
        ->whereBetween(
            DB::raw("REGEXP_SUBSTR(yom, '[0-9]{4}')"),
            [now()->subYear()->year, now()->year]
        ) 
        ->select('b.slug','bt.name as name')
        ->distinct('b.slug')->get();
        
        $brand_list=$this->getJdmSpecificJDMRecordNew();
  

     
        $yearRange = Cars::where('is_active', '1')
                ->selectRaw('
                MIN(YEAR(
                    IF(
                        STR_TO_DATE(yom, "%M %Y") IS NOT NULL, 
                        STR_TO_DATE(yom, "%M %Y"), 
                        CONCAT(CAST(yom AS CHAR), "-01-01")
                    )
                )) AS min_year, 
                MAX(YEAR(
                    IF(
                        STR_TO_DATE(yom, "%M %Y") IS NOT NULL, 
                        STR_TO_DATE(yom, "%M %Y"), 
                        CONCAT(CAST(yom AS CHAR), "-01-01")
                    )
                )) AS max_year
            ')
        // ->selectRaw('MIN(YEAR(STR_TO_DATE(yom, "%M %Y"))) as min_year, MAX(YEAR(STR_TO_DATE(yom, "%M %Y"))) as max_year')
        ->whereNotNull('yom')
        ->first();     
        
        $priceRange = Cars::where('is_active', '1')
        ->selectRaw('MIN(CAST(price AS DECIMAL)) as min_sal, MAX(CAST(price AS DECIMAL)) as max_sal')
        ->first();
    
    
    
        // Get min and max years
        // $minYear = $yearRange->min_year;
        $minYear = 1950;

        // $maxYear = $yearRange->max_year;
        $maxYear = now()->year;
        $minPrice = $priceRange->min_sal;
        $maxPrice = $priceRange->max_sal;

        $hasPriceRangeScale = false;
        $startValue = $minPrice;
        $endValue = $maxPrice;



      

        $carsQuery = Cars::query();

        $carsQuery->join('models_cars as mc', function ($join) {
            $join->on('blog.category', '=', 'mc.category')
                 ->on('blog.model', '=', 'mc.model');
        });
     
        $carsQuery->join('brands as b', DB::raw('LOWER(blog.make)'), '=', DB::raw('LOWER(b.slug)'));
        $carsQuery->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id');
        $lang = Session::get('front_lang');

        $carsQuery->where('bt.lang_code', $lang);
        $carsQuery->where('is_active', '1');
        $carsQuery->whereRaw('REGEXP_REPLACE(blog.price, "[,\\s]", "") REGEXP "^[0-9]+$"');
            if($request->search){
                if($request->search !=""){
                        $carsQuery->where(function ($query) use ($request) {
                            $query->where('blog.title', 'LIKE', $request->search . '%')
                                    ->orWhere('blog.make', 'LIKE', $request->search . '%')  // Replace 'column2' with the actual column name
                                    ->orWhere('blog.model', 'LIKE', $request->search . '%');  // Replace 'column3' with the actual column name
                        });     
                }
            }

            if ($request->brand) {
                $brand_arr = array_filter($request->brand); // Filter out any empty values
                if ($brand_arr) {
                    $carsQuery->whereIn('blog.make', $brand_arr); 
                }    
            }
            if($request->year){
                if($request->year !="")
                {
                    $carsQuery->where('blog.yom', 'like', '%' . $request->year . '%'); 
                }
            }
            
            if($request->model){
                // $model_arr = array_filter($request->model); 
                // if ($model_arr) {
                //         $carsQuery->where(function ($query) use ($model_arr) {
                //             foreach ($model_arr as $model) {
                //                 $query->orWhereRaw('TRIM(blog.model) = ?', [$model]);
                //             }
                //         });
                // }


                $model_arr = [];
                foreach ($request->model as $brandSlug => $models) {
                    if (is_array($models)) {
                        $model_arr = array_merge($model_arr, array_filter($models)); // Flatten the nested array
                    }
                }
                if ($model_arr) {
                    $carsQuery->whereIn('blog.model', $model_arr);
                }
            }

            if($request->brand_new_cars){
                $year = date('Y'); 
                    $carsQuery->where('blog.year_of_reg', 'LIKE', $year . '%');    
            }

            if ($request->sort_by) {
                switch ($request->sort_by) {
                    case 'price_low_high':
                        $carsQuery->orderBy('blog.price', 'asc');    
                        break;
                    case 'price_high_low':
                            $carsQuery->orderBy('blog.price', 'desc');    
                        break;
                    case 'recent':  
                            $recentCarIds = $carsQuery->orderBy('blog.id', 'desc')
                            ->limit(100)
                            ->pluck('id');
                            $carsQuery = $carsQuery->whereIn('blog.id', $recentCarIds);
                        break;
                }
            }
            if($request->price_range_scale){
                if($request->price_range_scale !=""){  
                    $hasPriceRangeScale=true;
                    $parts = explode(',', $request->price_range_scale);
                    $startValue = trim($parts[0]);
                    $endValue = trim($parts[1]);

                    $startValue = (int)trim(str_replace('"', '', $parts[0]));
                    $endValue = (int)trim(str_replace('"', '', $parts[1]));
                    $carsQuery->whereRaw("REGEXP_REPLACE(blog.price, '[^0-9]', '') BETWEEN ? AND ?", [
                                    $startValue,
                                    $endValue
                            ]);
                    
                }
            }

            if($request->price_range){
                    $priceRanges = [
                        "Under $5000" => ["start" => 0, "end" => 5000],
                        "$5000 - $50000" => ["start" => 5001, "end" => 50000],
                        "$50000 - $100000" => ["start" => 50001, "end" => 100000],
                        "$100000 - $200000" => ["start" => 100001, "end" => 200000],
                        "$200000 - $300000" => ["start" => 200001, "end" => 300000],
                        "Above $300000" => ["start" => 300001, "end" => PHP_INT_MAX] // Use PHP_INT_MAX for "Above"
                    ];
                    
                    $table='blog.price';
                    $carsQuery->where(function ($query) use ($request, $priceRanges,$table) {
                        foreach ($request->price_range as $range) {
                            $result = $this->getPriceRangestart($range, $priceRanges);
                
                            $query->orWhereRaw("REGEXP_REPLACE($table, '[^0-9]', '') BETWEEN ? AND ?", [
                                $result['start_price_num'],
                                $result['end_price_num']
                            ]);
                        }
                    }); 

                    

            }
            
            // dd(DB::getQueryLog($carsQuery->get()));
            $currentYear = now()->year;
            $previousYear = now()->subYear()->year;
        
            // Assuming $previousYear and $currentYear are defined
            $carsQuery->whereNotNull(DB::raw("REGEXP_SUBSTR(yom, '[0-9]{4}')"));

            $carsQuery->whereBetween(
                          DB::raw("REGEXP_SUBSTR(yom, '[0-9]{4}')"),
                          [$previousYear, $currentYear]
            );

            if(!$request->sort_by){
                $carsQuery->orderBy('blog.id', 'desc');
            }
       
            $results = $carsQuery->select('blog.*');
      
            // Pagination
            $cars = $results->paginate(12);
            // Transform cars into an array for the view
            $cars_array = $cars->map(function ($car) {
            // $car_image=$this->last_image($car->pictures);
                return [
                    'model_name' => $car->model,
                    'start_price' => $car->price,
                    'picture' =>$car->image,
                    'id' => $car->id,
                    'make'=>$car->make,
                    'kms'=>$car->kms,
                    'yor'=>$car->year_of_reg,
                    'location'=>$car->location,
                    'created_at'=>$car->created_at,
                ];
            });

     
    
    
        // Get additional data
        $listing_ads = AdsBanner::where('position_key', 'listing_page_sidebar')->first();

        $brand_count = CarDataJpOp::selectRaw('company_en,company,COUNT(*) as count')
            ->groupBy('company_en')
            ->having('count', '>', 1)
            ->get();
    
      

      

            // $jdm_brand['car']=$jdm_legend;
            // $jdm_brand['heavy']=$jdm_legend_heavy;
            // $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
            // $jdm_brand=$this->jdm_brands();
            // $price_range = $this->getPriceRange();
            $transmission = CarDataJpOp::selectRaw('transmission_en, COUNT(*) as count')
            ->groupBy('transmission_en')
            ->having('count', '>', 1)
            ->get();
            $scores = CarDataJpOp::selectRaw('scores_en, COUNT(*) as count')
            ->groupBy('scores_en')
            ->having('count', '>', 1)
            ->get();

            

        $price_range=$this->SelectedJdmRangeCar($priceRanges,
        $hasPriceRangeScale,$startValue,$endValue);

        $wishlists=[];
        if(Auth::guard('web')->check()){
            $userId = $userId ?? auth()->id();
            $wishlists=Cars::join('wishlists as w', 'blog.id', '=', 'w.car_id')
                ->where('w.table_id', '3')
                ->where('w.user_id', $userId)
                ->pluck('w.car_id')
                ->toArray();
        }
    
        return view('jdm-brand-new1', [
            'seo_setting' => $seo_setting,
            'jdm_legend'=>$jdm_brand,
            'jdm_core_brand'=>$jdm_core_brand,
            'brands' => $brands,
            'cars_array' => $cars_array,
            'listing_ads' => $listing_ads,
            'cars' => $cars,
            'brand_count' => $brand_count,
            'price_range' => $price_range,
            'transmission'=>$transmission,
            'scores'=>$scores,
            'brand_arr'=>$brand_list,
            'minYear'=>$minYear,
            'maxYear'=>$maxYear,
            'minPrice'=>$minPrice,
            'maxPrice'=>$maxPrice,
            'wishlists'=>$wishlists,
            // 'brand_label'=>$brand_label
            // 'jdm_legend_heavy'=>$jdm_legend_heavy,
            // 'jdm_legend_small_heavy'=>$jdm_legend_small_heavy
        ]);
    }

    public function dealers(Request $request){

        $seo_setting = SeoSetting::where('id', 1)->first();

        $dealers = User::where(['status' => 'enable' , 'is_banned' => 'no', 'is_dealer' => 1])->where('email_verified_at', '!=', null)->orderBy('id','desc')->select('id','name','username','designation','image','status','is_banned','is_dealer', 'address', 'email', 'phone');

        if($request->search){
            $dealers = $dealers->where('name', 'like', '%' . $request->search . '%');
        }

        if($request->location){
            $dealers = $dealers->whereHas('cars', function($query) use($request){
                $query->where('city_id', $request->location);
            });
        }

        $dealers = $dealers->paginate(12);

        $cities = City::with('translate')->get();

        return view('dealer')->with([
            'seo_setting' => $seo_setting,
            'dealers' => $dealers,
            'cities' => $cities,

        ]);

    }


    public function dealer(Request $request, $username){

        $dealer = User::where(['status' => 'enable' , 'is_banned' => 'no', 'is_dealer' => 1])->where('email_verified_at', '!=', null)->orderBy('id','desc')->select('id','name','username','designation','image','status','is_banned','is_dealer', 'address', 'email', 'phone','facebook','linkedin','twitter','instagram', 'about_me','created_at','sunday','monday','tuesday','wednesday','thursday','friday','saturday','google_map')->where('username', $username)->first();

        if(!$dealer) abort(404);

        $total_dealer_rating = Review::where('agent_id', $dealer->id)->where('status', 'enable')->count();

        $cars = Car::with('dealer', 'brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->where('agent_id', $dealer->id)->paginate(9);

        $dealer_ads = AdsBanner::where('position_key', 'dealer_detail_page_banner')->first();

        return view('dealer_detail', [
            'dealer' => $dealer,
            'cars' => $cars,
            'total_dealer_rating' => $total_dealer_rating,
            'dealer_ads' => $dealer_ads,
        ]);
    }

    public function send_message_to_dealer(ContactMessageRequest $request, $dealer_id){
        MailHelper::setMailConfig();

        $template = EmailTemplate::find(2);
        $message = $template->description;
        $subject = $template->subject;
        $message = str_replace('{{user_name}}',$request->name,$message);
        $message = str_replace('{{user_email}}',$request->email,$message);
        $message = str_replace('{{user_phone}}',$request->phone,$message);
        $message = str_replace('{{message_subject}}',$request->subject,$message);
        $message = str_replace('{{message}}',$request->message,$message);

        $dealer = User::findOrFail($dealer_id);

        Mail::to($dealer->email)->send(new SendContactMessage($message,$subject, $request->email, $request->name));

        $notification= trans('translate.Your message has send successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }

    public function send_message_to_company(ContactMessageRequest $request){
        $setting = Setting::first();
        // MailHelper::setMailConfig();
        $template = EmailTemplate::find(2);
        

        $message = $template->description;
        $subject = $template->subject;
        $message = str_replace('{{user_name}}',$request->name,$message);
        $message = str_replace('{{user_email}}',$request->email,$message);
        $message = str_replace('{{user_phone}}',$request->phone,$message);
        $message = str_replace('{{message_subject}}',$request->subject,$message);
        $message = str_replace('{{message}}',$request->message,$message);
        $message = str_replace('{{commission}}',$request->commission,$message);
        $message = str_replace('{{delivery_charge}}',$request->delivery,$message);
        $message = str_replace('{{shipping}}',$request->shipping,$message);
        $message = str_replace('{{total}}',$request->total_car_price,$message);
        $message = str_replace('{{message_url}}',$request->url_link,$message);


      

        // Mail::to(env('MAIL_FROM_ADDRESS'))->send(new SendContactMessage($message,$subject, $request->email, $request->name));
        Mail::to($setting->email)->send(new SendContactMessage($message,$subject, $request->email, $request->name,$request->url_link));

   
        $Enquiry=new VehicleEnquiry();
        $Enquiry->name=$request->name;
        // $Enquiry->user_id=Auth::user()->id;
        $Enquiry->email=$request->email;
        $Enquiry->phone=$request->phone;
        $Enquiry->subject=$request->subject;
        $Enquiry->make=$request->vehicle_brand;
        $Enquiry->model=$request->vehicle_model;
        $Enquiry->commission=$request->commission;
        $Enquiry->delivery_charge=$request->delivery;
        $Enquiry->total_car_price=$request->total_car_price;
        $Enquiry->message=$request->message;
        $Enquiry->url_link=$request->url_link;
        $Enquiry->save();

   
        $notification= trans('translate.Your message has send successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function auct_login(Request $request) {
        // $notification= trans('translate.Logout Successfully');
        // $notification=array('messege'=>$notification,'alert-type'=>'success');
        // return redirect()->route('profile.auct-login')->with($notification);
        return view('profile.auct-login');
    }

    public function pricing_plan(){

        $subscription_plans = SubscriptionPlan::orderBy('serial', 'asc')->where('status', 'active')->get();

        return view('pricing_plan', ['subscription_plans' => $subscription_plans]);
    }

     public function join_as_dealer(){

        return redirect()->route('register');
    }

   public function compare(){

        $compare_array = Session::get('compare_array', []);

        $cars = Car::with('brand')->where(function ($query) {
            $query->where('expired_date', null)
                ->orWhere('expired_date', '>=', date('Y-m-d'));
        })->where(['status' => 'enable', 'approved_by_admin' => 'approved'])->whereIn('id', $compare_array)->get();

        $compare_qty = $cars->count();


        return view('compare', ['cars' => $cars, 'compare_qty' => $compare_qty]);
   }

   public function add_to_compare($id){

        $compare_array = Session::get('compare_array', []);

        if (!in_array($id, $compare_array)) {
            if(count($compare_array) < 4){
                $compare_array[] = $id;
                Session::put('compare_array', $compare_array);

                $notification= trans('translate.Item added successfully');
                $notification=array('messege'=>$notification,'alert-type'=>'success');
                return redirect()->back()->with($notification);
            }else{
                $notification= trans('translate.You can not added more then 4 items');
                $notification=array('messege'=>$notification,'alert-type'=>'error');
                return redirect()->back()->with($notification);
            }

        }else{
            $notification= trans('translate.Item already exist in compare');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }
   }

    public function remove_to_compare($car_id){

        $compare_array = Session::get('compare_array', []);

        $update_compare_array = array_filter($compare_array, function ($id) use ($car_id) {
            return $id !== $car_id;
        });

        Session::put('compare_array', $update_compare_array);

        $notification= trans('translate.Compare item removed successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function language_switcher(Request $request){

        $request_lang = Language::where('lang_code', $request->lang_code)->first();

        Session::put('front_lang', $request->lang_code);
        Session::put('front_lang_name', $request_lang->lang_name);
        Session::put('lang_dir', $request_lang->lang_direction);

        app()->setLocale($request->lang_code);

        $notification= trans('translate.Language switched successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);

    }

    public function currency_switcher(Request $request){

        $request_currency = MultiCurrency::where('currency_code', $request->currency_code)->first();

        Session::put('currency_name', $request_currency->currency_name);
        Session::put('currency_code', $request_currency->currency_code);
        Session::put('currency_icon', $request_currency->currency_icon);
        Session::put('currency_rate', $request_currency->currency_rate);
        Session::put('currency_position', $request_currency->currency_position);

        $notification= trans('translate.Currency switched successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);




    }

    public function addWishList(Request $request){
        // echo json_encode($request->id);die();
        $userId = Auth::id();
    $carId = $request->id;
    $tableId = $request->type;

    // Check if the record exists in the wishlist
    $wishlistEntry = Wishlist::where('user_id', $userId)
        ->where('car_id', $carId)
        ->first();

    if ($wishlistEntry) {
        // If the record exists, delete it
        $wishlistEntry->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'The car has been removed from your wishlist.',
        ], 200);
    }

    // If the record does not exist, create a new one
    Wishlist::create([
        'user_id' => $userId,
        'car_id' => $carId,
        'table_id' => $tableId,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'The car has been added to your wishlist.',
    ], 201);
        return response()->json(['status'=>true,'response'=>'Added Successfully']);
    }


    // function jdm_brands(){
    //     $jdm_legend = DB::table('brands as b')
    //     ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
    //     ->join('blog as blog', DB::raw('LOWER(blog.make)'), '=', DB::raw('LOWER(b.slug)')) // Ensure blog.make matches b.slug
    //     ->join('models_cars as mc', function ($join) {
    //         $join->on('blog.model', '=', 'mc.model')
    //             ->on('blog.category', '=', 'mc.category'); // Match model and category
    //     })
    //     ->where('blog.is_active','1')
    //     ->where('bt.lang_code', Session::get('front_lang')) // Filter by language code
    //     ->whereRaw('REGEXP_REPLACE(blog.price, "[,\\s]", "") REGEXP "^[0-9]+$"')
    //     ->select('b.slug', 'bt.name as brand_name') // Select slug and name
    //     ->distinct('b.slug') // Ensure distinct slugs
    //     ->get()
    //     ->map(function($item) {
    //         return [
    //             'slug' => $item->slug,
    //             'brand_name' => $item->brand_name
    //         ];
    //     })
    //     ->toArray();
    //     $jdm_legend_heavy = DB::table('brands as b')
    //     ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
    //     ->join('heavy as blog', DB::raw('LOWER(blog.make)'), '=', DB::raw('LOWER(b.slug)')) // Ensure blog.make matches b.slug
    //     ->join('models_cars as mc', function ($join) {
    //         $join->on('blog.model', '=', 'mc.model')
    //             ->on('blog.category', '=', 'mc.category'); // Match model and category
    //     })
    //     ->where('blog.is_active','1')
    //     ->where('bt.lang_code', Session::get('front_lang')) // Filter by language code
    //     ->whereRaw('REGEXP_REPLACE(blog.price, "[,\\s]", "") REGEXP "^[0-9]+$"')
    //     ->select('b.slug', 'bt.name as brand_name') // Select slug and name
    //     ->distinct('b.slug') // Ensure distinct slugs
    //     ->get()
    //     ->map(function($item) {
    //         return [
    //             'slug' => $item->slug,
    //             'brand_name' => $item->brand_name
    //         ];
    //     })
    //     ->toArray();

    //     $jdm_legend_small_heavy = DB::table('brands as b')
    //     ->join('brand_translations as bt', 'bt.brand_id', '=', 'b.id')
    //     ->join('small_heavy as blog', DB::raw('LOWER(blog.make)'), '=', DB::raw('LOWER(b.slug)')) // Ensure blog.make matches b.slug
    //     ->join('models_cars as mc', function ($join) {
    //         $join->on('blog.model', '=', 'mc.model')
    //             ->on('blog.category', '=', 'mc.category'); // Match model and category
    //     })
    //     ->where('blog.is_active','1')
    //     ->where('bt.lang_code', Session::get('front_lang')) // Filter by language code
    //     ->whereRaw('REGEXP_REPLACE(blog.price, "[,\\s]", "") REGEXP "^[0-9]+$"')
    //     ->select('b.slug', 'bt.name as brand_name') // Select slug and name
    //     ->distinct('b.slug') // Ensure distinct slugs
    //     ->get()
    //     ->map(function($item) {
    //         return [
    //             'slug' => $item->slug,
    //             'brand_name' => $item->brand_name
    //         ];
    //     })
    //     ->toArray();
    //     $jdm_brand['car']=$jdm_legend;
    //     $jdm_brand['heavy']=$jdm_legend_heavy;
    //     $jdm_brand['small_heavy']=$jdm_legend_small_heavy;
    //     return $jdm_brand;    
      
    // }

    public function store_pricing(PricingRequest $request,$id){
        $get_charges=AuctLotsXmlJpOpOtherChargers::whereAuctId($id)->first();
        // if(!empty($get_charges)){
            $price=CarDataJpOp::where('id', $id)->value('start_price_num');
            if($request->shipment ==1){
                $delivery_charge=DeliveryCharge::where('id',$request->location)->value('roro');
            } else{
                $delivery_charge=DeliveryCharge::where('id',$request->location)->value('container');
            }
            // $delivery_charge=DeliveryCharge::where('id',$request->location)->value('rate');
            $commission=!empty($get_charges) ? $get_charges->commission_value : 0;
            $shipping=!empty($get_charges) ? $get_charges->shipping_value : 0;
            $usd=$this->convertCurrency($price, $this->usdRate);
            $marine_insurance=$request->marine_insurance == 'on' ? $get_charges->marine_insurance_value : 0; 
            $inland_inspection=$request->inland_inspection == 'on' ? $get_charges->inland_inspection_value : 0; 
            $total=($usd+$delivery_charge+$commission+$shipping+$marine_insurance+$inland_inspection);
            $paypal = PaypalPayment::first();
            $stripe = StripePayment::first();
            $razorpay = RazorpayPayment::first();
            $flutterwave = Flutterwave::first();
            $paystack = PaystackAndMollie::first();
            $mollie = $paystack;
            $instamojo = InstamojoPayment::first();
            $bank = BankPayment::first();

            $status = 0;

            if ($request->marine_insurance=='on' && $request->inland_inspection=='on') {
                $status = 3; // Both are checked
            } elseif ($request->marine_insurance == 'on') {
                $status = 1; // Only marine_insurance is checked
            } elseif ($request->inland_inspection=='on') {
                $status = 2; // Only inland_inspection is checked
            }

           
            return view('payment', [
                'usd' => $usd,
                'delivery_charge' => $delivery_charge,
                'commission' => $commission,
                'shipping' => $shipping,
                'total' => $total,
                'stripe' => $stripe,
                'paypal' => $paypal,
                'razorpay' => $razorpay,
                'flutterwave' => $flutterwave,
                'paystack' => $paystack,
                'mollie' => $mollie,
                'instamojo' => $instamojo,
                'bank' => $bank,
                'id'=>$id,
                'type'=>'1',
                'delievery_charge_id'=>$request->location,
                'shipment'=>$request->shipment,
                'status'=>$status
            ]);
        // }    
    }
    public function store_auction_pricing(PricingRequest $request,$id){
        $get_charges=AuctLotsXmlJpOpOtherChargers::whereAuctId($id)->first();
        // if(!empty($get_charges)){
            $price=Auct_lots_xml_jp::where('id', $id)->value('start_price_num');
            $delivery_charge=DeliveryCharge::where('id',$request->location)->value('rate');
            $commission=!empty($get_charges) ? $get_charges->commission_value : 0;
            $shipping=!empty($get_charges) ? $get_charges->shipping_value : 0;
            $usd=$this->convertCurrency($price, $this->usdRate);
            $total=($usd+$delivery_charge+$commission+$shipping);

            $paypal = PaypalPayment::first();
            $stripe = StripePayment::first();
            $razorpay = RazorpayPayment::first();
            $flutterwave = Flutterwave::first();
            $paystack = PaystackAndMollie::first();
            $mollie = $paystack;
            $instamojo = InstamojoPayment::first();
            $bank = BankPayment::first();

           
    
    
            return view('payment', [
                'usd' => $usd,
                'delivery_charge' => $delivery_charge,
                'commission' => $commission,
                'shipping' => $shipping,
                'total' => $total,
                'stripe' => $stripe,
                'paypal' => $paypal,
                'razorpay' => $razorpay,
                'flutterwave' => $flutterwave,
                'paystack' => $paystack,
                'mollie' => $mollie,
                'instamojo' => $instamojo,
                'bank' => $bank,
                'id'=>$id,
                'type'=>'2',
                'delievery_charge_id'=>$request->location,
                'head_title'=>'auction_listing'
            ]);
        // }    
    }
    public function store_jdm(Request $request,$id,$type){
        // $get_charges=AuctLotsXmlJpOpOtherChargers::whereAuctId($id)->first();
        // if(!empty($get_charges)){  
            $status = 0;
  
            if($type=='car'){
                $price=Cars::where('id', $id)->first();
                $get_charges=JdmStockBlogOtherCharges::whereJdmBlogId($id)->first();
                $type='3';
            } else {
                $price=Heavy::where('id', $id)->first();
                $get_charges=JdmStockHeavyOtherCharges::whereJdmHeavyId($id)->first();
                $type='4';
            }
            $marine_insurance=0;
            $inland_insurance=0;

            if ($request->marine_insurance=='on' && $request->inland_inspection=='on') {
                $status = 3; // Both are checked
                $marine_insurance=$get_charges->marine_insurance_value;
                $inland_insurance=$get_charges->inland_inspection_value;
            } elseif ($request->marine_insurance == 'on') {
                $status = 1; // Only marine_insurance is checked
                $marine_insurance=$get_charges->marine_insurance_value;
            } elseif ($request->inland_inspection=='on') {
                $status = 2; // Only inland_inspection is checked
                $inland_insurance=$get_charges->inland_inspection_value;
            }


            if($request->shipment ==1){
                $delivery_charge=DeliveryCharge::where('id',$request->location)->value('roro');
            } else{
                $delivery_charge=DeliveryCharge::where('id',$request->location)->value('container');
            }
            // $delivery_charge=DeliveryCharge::where('id',$request->location)->value('rate');
            $usd=$price->price;
            $usd=floatval(str_replace(',', '', $usd));


          
        
            // $total=($usd+$commission+$shipping);
            $total=($usd+$delivery_charge+$marine_insurance+$inland_insurance);

            $paypal = PaypalPayment::first();
            $stripe = StripePayment::first();
            $razorpay = RazorpayPayment::first();
            $flutterwave = Flutterwave::first();
            $paystack = PaystackAndMollie::first();
            $mollie = $paystack;
            $instamojo = InstamojoPayment::first();
            $bank = BankPayment::first();
    
        
         
      
            
            return view('payment', [
                'usd' => $usd,
                'delivery_charge' => $delivery_charge,
                // 'commission' => $commission,
                // 'shipping' => $shipping,
                'total' => $total,
                'stripe' => $stripe,
                'paypal' => $paypal,
                'razorpay' => $razorpay,
                'flutterwave' => $flutterwave,
                'paystack' => $paystack,
                'mollie' => $mollie,
                'instamojo' => $instamojo,
                'bank' => $bank,
                'id'=>$id,
                'type'=>$type,
                'delievery_charge_id'=>$request->location,
                'shipment'=>$request->shipment,
                'status'=>$status
            ]);
        // }    
    }








}
