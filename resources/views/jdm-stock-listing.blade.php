@extends('layout4')
@section('title')
    <title>{{ html_decode($seo_setting->seo_title) }}</title>
    <meta name="title" content="{{ html_decode($seo_setting->seo_title) }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
@endsection

@section('body-content')

<main class="bg-light-grey px-sm-2 px-md-5">
    <!-- <div id="pageLoader">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div> -->

    <!-- Inventory Details-part-start -->
     <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Centered & Large Size -->
    <div class="modal-content">
      <div class="modal-body position-relative">
        <span class="close-btn" data-bs-dismiss="modal">
            <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" width="30px" height="30px"><path fill="#f44336" d="M44,24c0,11.045-8.955,20-20,20S4,35.045,4,24S12.955,4,24,4S44,12.955,44,24z"/><path fill="#fff" d="M29.656,15.516l2.828,2.828l-14.14,14.14l-2.828-2.828L29.656,15.516z"/><path fill="#fff" d="M32.484,29.656l-2.828,2.828l-14.14-14.14l2.828-2.828L32.484,29.656z"/></svg>
        </span>
        <h2 class="mb-3 text-center section-heading modal-heading">Get a Free <span class="highlight"> &nbsp; Quotation<span></h2>
        <div class="row">
            <div class="mb-3 border col-lg-6 col-sm-12 col-12 mb-lg-0 rounded-3">
                <div class="m-0 inventory-details-slick-for">
                @foreach ($car_images as $gallery)
                                <div class="inventory-details-slick-img">
                                    <div class="inventory-details-slick-img-tag">
                                        <div class="icon-main">
                                            
                                        </div>
                                    </div>
                                    <div class="image-zoom-container">
                                        <img src="{{ file_exists(public_path($image_folder.'/'.$slug.'/'. $gallery->image)) ? 
                                                asset($image_folder.'/'.$slug.'/'. $gallery->image) : 
                                                asset('uploads/website-images/no-image.jpg') }}" 
                                            alt="thumb" class="card_image" />
                                        <div class="zoom-lens"></div>
                                    </div>
                                </div>
                            @endforeach 
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 col-12">
                <div class="gap-3 d-flex flex-column car-listing-details">
                    <p class="brand-text fw-bolder">{{$car->company_en}}</p>
                     <h3>{{$car->model_name_en}}</h3>
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="py-2 amount-text" id="price_value1">Price <span class="price-text">
                        @if(session('front_lang')=='en')
                                ${{$car->price}}
                                @else
                                {{$car->price}}
                        @endif 
                        </span></p>
                        <p style="display:none;" id="commission_value1">${{$car->commission_value}}</p>
                        <!-- <p class="amount-text" id="commission_value">Commission <span class="commission-text"> 
                        ${{$car->commission_value}}
                        </span></p> -->
                    </div>
                    <div class="gap-3 d-flex align-items-center">
                        <div class="dropdown location-dropdown w-100">
                            <select class="form-select location-select"
                                aria-label=".form-select example" name="location" id="location1">
                                <option class="" selected value="">
                                    {{ __('translate.Select Location') }} <i class="bi bi-caret-down"></i>
                                </option>
                                
                                @foreach ($delivery_charges as $charges)
                                <option value="{{ $charges->id }}"><i class="bi bi-caret-down-fill"></i>{{ $charges->country_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{--<div class="bg-white w-100">
                            <button class="bg-white btn w-100 charge-btn" type="button" id="delivery_charge1">
                                Delivery Charge 
                            </button> 
                        </div>--}}
                    </div>
                    {{--<div class="gap-3 d-flex align-items-center">
                        <div class="dropdown location-dropdown w-100">
                            <button class="bg-white btn w-100 charge-btn">
                                Shipping 
                            </button> 
                        </div>
                        <div class="bg-white w-100">
                            <button class="bg-white btn w-100 charge-btn" type="button" id="shipping_charge1">
                               {{'$'.round($car->shipping_value)}}
                            </button> 
                        </div>
                    </div>--}}
                    <div class="d-flex justify-content-between">
                        <div class="form-check p-0">
                            <label class="form-check-label" for="marine_insurance">
                                Shipment 
                            </label>
                        </div>
                        <div class="form-check ">
                            <input class="form-check-input" type="radio" name="shipment1" value="1">
                            <label class="form-check-label ps-2">
                                RoRo
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shipment1" value="2">
                            <label class="form-check-label ps-2">
                                Container 
                            </label>
                        </div>  
                    </div> 
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"  id="marine_insurance1" name="marine_insurance">
                        <label class="form-check-label ps-2" for="marine_insurance">
                            Marine insurance 
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"  id="inland_inspection1" name="inland_inspection">
                        <label class="form-check-label ps-2" for="inland_inspection">
                            Inland inspection 
                        </label>
                    </div>
                        <button class="w-100 cal-btn" type="button" id="calculate_total_price1">CALCULATE TOTAL PRICE</button>
                        <div class="mt-3 d-flex align-content-center total-price-container">
                            <p class="total-price position-relative">Total Price <span class="position-absolute">-</span></p>
                            <p class="" id="total_price1"></p>
                        </div>
                </div>
            </div>
            <div class="col-12">
                
                <form method="POST" action="{{route('send_message_to_company')}}" class="px-1 py-3 sales-form">
                    @csrf
                    <div class="row">
                        <div class="mb-2 col-lg-6 col-sm-12 col-12">
                            <div class="textarea-wrapper">
                                <input type="text" class="p-3 form-control" id="exampleFormControlInput3"
                                    placeholder="" name="name" value="{{ old('name') }}">
                                <span class="placeholder-text">Name <span class="required">*</span></span>
                            </div>
                        </div>
                        <div class="mb-2 col-lg-6 col-sm-12 col-12">
                        <div class="textarea-wrapper">
                                <input type="email" class="p-3 form-control" id="exampleFormControlInput4"
                                    placeholder="" name="email" value="{{ old('email') }}">
                                <span class="placeholder-text">Email <span class="required">*</span></span>
                            </div>
                        </div>
                        <div class="mb-2 col-lg-6 col-sm-12 col-12">
                            <div class="textarea-wrapper">
                                <input type="text" class="p-3 form-control" id="exampleFormControlInput5"
                                    placeholder="" name="phone" value="{{ old('phone') }}">
                                <span class="placeholder-text">Phone <span class="required">*</span></span>
                            </div>
                        </div>
                        <div class="mb-2 col-lg-6 col-sm-12 col-12">
                            <div class="textarea-wrapper">
                                <input type="text" class="p-3 form-control" id="exampleFormControlInpu6"
                                    placeholder="" value="{{ old('subject') }}" name="subject">
                                <span class="placeholder-text">Country of Delivery <span class="required">*</span></span>
                            </div>
                        </div>
                        <div class="mb-2 col-12">
                            <div class="textarea-wrapper">
                                <textarea class="p-3 form-control" id="exampleFormControlTextarea11" rows="3"
                                    placeholder="" name="message">{{ old('message') }}</textarea>
                                <span class="placeholder-text">Message <span class="required">*</span></span>
                            </div>
                        </div>
                        <div class="p-0 col-12 row">
                            <div class="mb-2 col-lg-6 col-sm-12 col-12 mb-lg-0">
                                <button type="button" class="w-full h-full btn btn-secondary cancel-btn" data-bs-dismiss="modal">Cancel</button>
                            </div>
                            <div class="mb-2 col-lg-6 col-sm-12 col-12 mb-lg-0">
                                 <button type="submit" class="thm-btn-two">BUY NOW</button>  
                            </div>
                          
                        </div>
                    </div>
                    <input type="hidden" name="car_id" value="{{$car->id}}">
                    <input type="hidden" name="commission" value="" id="hidden_commission">
                    <input type="hidden" name="delivery" value="" id="hidden_delivery_charge">
                    <input type="hidden" name="shipping" value="" id="hidden_shipping_charge">
                    <input type="hidden" name="total_car_price" value="" id="hidden_total">
                    <input type="hidden" name="vehicle_brand" value="{{$car->company_en}}">
                    <input type="hidden" name="vehicle_model" value="{{$car->model_name_en}}">
                    <input type="hidden" name="url_link" value="{{$url_link}}">
                </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>


    <section class="inventory-details py-120px listing-breadcrumb bg-light-grey">
        <div class="container">
            <nav aria-label="breadcrumb" class="px-4">
                <ol class="mb-4 breadcrumb breadcrumb-list">
                    <li class="breadcrumb-item breadcrumb-link"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                    <li class="breadcrumb-item breadcrumb-link" aria-current="page"><a href="{{ route('jdm-stock',[$car->make, $type]) }}">{{ __('translate.JDM Stock Listing') }}</a></li>
                    <li class="breadcrumb-item breadcrumb-link" aria-current="page">{{ $car->model}}</li>
                </ol>
            </nav>
            <div class="row">
                <div class="px-0 col-lg-8 col-sm-12 col-12 listing_image">
                    <div class="pb-3 row">
                        <div class="m-0 inventory-details-slick-for">
                            @foreach ($car_images as $gallery)
                                <div class="inventory-details-slick-img">
                                    <div class="inventory-details-slick-img-tag">
                                        <div class="icon-main">
                                            
                                        </div>
                                    </div>
                                    <div class="image-zoom-container">
                                        <img src="{{ file_exists(public_path($image_folder.'/'.$slug.'/'. $gallery->image)) ? 
                                                asset($image_folder.'/'.$slug.'/'. $gallery->image) : 
                                                asset('uploads/website-images/no-image.jpg') }}" 
                                            alt="thumb" class="card_image" />
                                        <div class="zoom-lens"></div>
                                    </div>
                                </div>
                            @endforeach        
                        </div>

                        <div class="inventory-details-slick-nav">
                            @foreach ($car_images as $gallery)
                                <div class="inventory-details-slick-img">
                                <img src="{{ file_exists(public_path($image_folder.'/'.$slug.'/'. $gallery->image)) ? 
                                        asset($image_folder.'/'.$slug.'/'. $gallery->image) : 
                                        asset('uploads/website-images/no-image.jpg') }}" 
                                    alt="thumb" class="card_image">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="px-3 d-flex justify-content-end">
                        <button class="thm-btn-two download-gallery" aria-label="Previous" type="button">{{__('translate.Download Car Pictures')}} <span class="ps-2"><i class="ml-3 fa-solid fa-download"></i> <span></button>
                    </div>


                    {{-- @if ($listing_ads->status == 'enable')
                        <div class="inventory-details-thumb" data-aos="fade-up" data-aos-delay="50">
                            <a href="{{ $listing_ads->link }}" target="_blank"> <img src="{{ asset($listing_ads->image) }}" alt="img"></a>
                        </div>
                    @endif --}}


                  
                    {{-- @if ($reviews->count() > 0)
                        <!-- Write Your Review -->
                        <div class="accordion aos-init aos-animate" id="accordionPanelsStayOpenExample5" data-aos="fade-up"
                        data-aos-delay="350">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-headingsix">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsesix" aria-expanded="false" aria-controls="panelsStayOpen-collapsesix">
                                        {{ __('translate.Car Reviews') }}
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapsesix" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingsix">
                                    <div class="accordion-body">

                                        @foreach ($reviews as $review)
                                            <div class="reviews">
                                                <div class="reviews-item">

                                                    <ul class="icon">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($review->rating < $i)
                                                            <li><span><i class="fa-regular fa-star"></i></span></li>
                                                            @else
                                                            <li><span><i class="fa-solid fa-star"></i></span></li>
                                                            @endif
                                                        @endfor
                                                    </ul>

                                                    <div class="text">
                                                        <h6>{{ $review->created_at->format('M d Y') }}</h6>
                                                    </div>
                                                </div>

                                                <p>
                                                    {{ html_decode($review->comment) }}
                                                </p>

                                                <div class="reviews-inner">
                                                    <div class="reviews-inner-item">
                                                        <div class="reviews-inner-img">
                                                            <img src="{{ asset($review?->user?->image) }}" alt="img">
                                                        </div>

                                                        <div class="reviews-inner-text">
                                                            <h3>{{ html_decode($review?->user?->name) }}</h3>
                                                            <p>{{ html_decode($review?->user?->designation) }}</p>
                                                        </div>
                                                    </div>


                                                </div>


                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif --}}

                


                </div>

                <div class="col-lg-4 col-md-12 col-sm-12 col-12 listing_form">
                    <div class="p-sticky">
                    <div class="auto-sales-item form-section">
                    <form method="POST" action="{{ route('post.jdm', ['slug' => request()->segment(2), 'type' => request()->segment(3)]) }}" class="sales-form">

                            @csrf
                            <div class="gap-2 d-flex flex-column car-listing-details">
                                    <p class="brand-text fw-bolder">{{$car->make}}</p>
                                    <h3>{{$car->model}}</h3>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="py-3 amount-text" id="price_value">Price <span class="price-text">
                                            @if(session('front_lang')=='en')
                                                ${{$car->price}}
                                                @else
                                                {{$car->price}}
                                            @endif 
                                        </span></p>
                                        <p style="display:none;" id="commission_value">${{$car->commission_value}}</p>
                                        <!-- <p class="amount-text" id="commission_value">Commission <span class="commission-text"> 
                                        ${{$car->commission_value}}
                                        </span></p> -->
                                    </div>
                                    <div class="gap-3 d-flex align-items-center">
                                        <div class="dropdown location-dropdown w-100">
                                            <select class="form-select location-select"
                                                aria-label=".form-select example" name="location" id="location">
                                                <option selected value="">
                                                    {{ __('translate.Select Location') }} <i class="bi bi-caret-down"></i>
                                                </option>
                                                @foreach ($delivery_charges as $charges)
                                                <option value="{{ $charges->id }}"><i class="bi bi-caret-down-fill"></i>{{ $charges->country_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{--<div class="bg-white w-100">
                                            <button class="bg-white btn w-100 charge-btn" type="button" id="delivery_charge">
                                                Delivery Charge
                                            </button>
                                        </div>--}}
                                    </div>
                                    @error('location')
                                        <span style="color: red;">{{ $message }}</span>
                                    @enderror
                                    {{--<div class="gap-3 d-flex align-items-center">
                                        <div class="dropdown location-dropdown w-100">
                                            <button class="bg-white btn w-100 charge-btn">
                                                Shipping 
                                            </button> 
                                        </div>
                                        <div class="bg-white w-100">
                                            <button class="bg-white btn w-100 charge-btn" type="button" id="shipping_charge">
                                            {{'$'.round($car->shipping_value)}}
                                            </button> 
                                        </div>
                                    </div>--}}
                                    <div class="d-flex justify-content-between">
                                        <div class="form-check p-0">
                                            <label class="form-check-label" for="marine_insurance">
                                              Shipment 
                                            </label>
                                        </div>
                                        <div class="form-check ">
                                            <input class="form-check-input" type="radio" name="shipment" value="1">
                                            <label class="form-check-label ps-2" for="marine_insurance">
                                              RoRo
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="shipment" value="2">
                                            <label class="form-check-label ps-2">
                                                Container 
                                            </label>
                                        </div>  
                                    </div> 
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"  id="marine_insurance" name="marine_insurance">
                                        <label class="form-check-label ps-2" for="marine_insurance">
                                            Marine insurance 
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"  id="inland_inspection" name="inland_inspection">
                                        <label class="form-check-label ps-2" for="inland_inspection">
                                            Inland inspection 
                                        </label>
                                    </div>
                                    <button class="w-100 cal-btn" type="button" id="calculate_total_price">CALCULATE TOTAL PRICE</button>
                                    <div class="mt-4 d-flex align-content-center total-price-container">
                                        <p class="total-price position-relative">Total Price <span class="position-absolute">-</span></p>
                                        <p class="" id="total_price"></p>
                                    </div>
                            </div>
                            <div class='gap-3 mt-4 d-flex'>
                                <button type="button" class="btn btn-primary w-100 inquiery_clr" data-bs-toggle="modal" data-bs-target="#myModal">INQUIERY NOW</button>
                                <button type="submit" class="h-full m-0 thm-btn-two">BUY NOW</button>  
                            </div>
                           
                        </form>   

                        

                            <!-- <form method="POST" action="{{route('send_message_to_company')}}"  class="sales-form">
                                @csrf
                                <div class="auto-sales-form">

                                    <div class="auto-sales-form-item">
                                        <div class="textarea-wrapper">
                                            <input type="text" class="form-control" id="exampleFormControlInput3"
                                                placeholder="" name="name" value="{{ old('name') }}">
                                            <span class="placeholder-text">Name <span class="required">*</span></span>
                                        </div>
                                    </div>
                                    <div class="auto-sales-form-item">
                                        <div class="textarea-wrapper">
                                            <input type="email" class="form-control" id="exampleFormControlInput4"
                                                placeholder="" name="email" value="{{ old('email') }}">
                                            <span class="placeholder-text">Email <span class="required">*</span></span>
                                        </div>
                                    </div>

                                    <div class="auto-sales-form-item">
                                        <div class="textarea-wrapper">
                                            <input type="text" class="form-control" id="exampleFormControlInput5"
                                                placeholder="" name="phone" value="{{ old('phone') }}">
                                            <span class="placeholder-text">Phone <span class="required">*</span></span>
                                        </div>
                                    </div>

                                    <div class="auto-sales-form-item">
                                        <div class="textarea-wrapper">
                                            <input type="text" class="form-control" id="exampleFormControlInpu6"
                                                placeholder="" value="{{ old('subject') }}" name="subject">
                                            <span class="placeholder-text">Country of Delivery <span class="required">*</span></span>
                                        </div>
                                    </div>

                                    <div class="auto-sales-form-item">
                                        <div class="textarea-wrapper">
                                            <textarea class="form-control" id="exampleFormControlTextarea11" rows="3"
                                                    placeholder="" name="message">{{ old('message') }}</textarea>
                                            <span class="placeholder-text">Message <span class="required">*</span></span>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="car_id" value="{{$car->id}}">
                                    <input type="hidden" name="commission" value="" id="hidden_commission">
                                    <input type="hidden" name="delivery_charge" value="" id="hidden_delivery_charge">
                                    <input type="hidden" name="total_car_price" value="" id="hidden_total">
                                    <input type="hidden" name="vehicle_brand" value="{{$car->company_en}}">
                                    <input type="hidden" name="vehicle_model" value="{{$car->model_name_en}}">
                                    <input type="hidden" name="url_link" value="{{$url_link}}">

                                    <button type="submit" class="thm-btn-two">{{ __('translate.Send Message') }}</button>
                                </div>
                            </form> -->



                        </div>

                    </div>
                </div>
            </div>
            <div class="pb-3 row">

                <!-- Car Specifications Start  -->
                <div class="col-sm-12">
                    <div class="accordion" id="accordionPanelsStayOpenExample1" data-aos="fade-up" data-aos-delay="150">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingtwo">
                                <button class="accordion-button accordion-title" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#panelsStayOpen-collapsetwo" aria-expanded="true"
                                    aria-controls="panelsStayOpen-collapsetwo">
                                    {{ __('translate.Car Specifications') }}
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapsetwo" class="accordion-collapse collapse show"
                                aria-labelledby="panelsStayOpen-headingtwo">
                                <div class="accordion-body">
                                    <div class="row gx-5">
                                        <div class="col-lg-6 ">
                                            <ul class="key-information" >
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Make') }}
                                                    </span>
                                                    {{ html_decode(empty($car->make) ? '--' : $car->make ) }}
                                                </li>
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Model') }}
                                                        <!-- {{ __('translate.Engine Size') }} -->
                                                    </span>
                                                    {{ html_decode(empty($car->model) ? '--' : $car->model ) }}

                                                    <!-- {{ __('translate.Engine Size') }} -->
                                                </li>
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        <!-- {{ __('translate.Model') }} -->
                                                        {{ __('translate.Grade') }}
                                                    </span>
                                                    {{ html_decode(empty($car->grade) ? '--' : $car->grade ) }}
                                                </li>
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        <!-- {{ __('translate.Interior Color') }} -->
                                                        {{ __('translate.Chassis number') }}
                                                    </span>
                                                    <!-- {{ html_decode(!empty($car->int_col) ? $car->int_col : '--') }} -->
                                                    {{ html_decode(!empty($car->chassis) ? $car->chassis : '--') }}
                                                </li>
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Year') }}
                                                    </span>
                                                    {{ html_decode(!empty($car->yom) ? $car->yom : '--') }}
                                                </li>
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Kilometers') }}
                                                    </span>
                                                    {{ empty($car->kms) ? '--' : $car->kms }}
                                                </li>
                                             
                                            </ul>
                                        </div>
                                        <div class="col-lg-6 ">
                                            <ul class="key-information two">
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Transmission') }}
                                                    </span>
                                                    {{ html_decode(empty($car->transmission) ? '--' : $car->transmission) }}

                                                </li>
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Fuel Type') }}
                                                    </span>
                                                    {{ html_decode(empty($car->fuel) ? '--' : $car->fuel) }}
                                                </li>
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Exterior Color') }}
                                                    </span>
                                                    {{ html_decode(empty($car->color) ? '--' : $car->color ) }}
                                                </li>
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Interior Color') }}
                                                    </span>
                                                      {{ html_decode(!empty($car->int_col) ? $car->int_col : '--') }}
                                                </li>
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Location') }}
                                                    </span>
                                                      --
                                                </li>
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Seller Type') }}
                                                    </span>
                                                    {{ html_decode($car->seller_type) }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Car Specifications End  -->

                <!-- Sell Point Start -->
                <div class="col-sm-12">
                    <div class="accordion" id="accordionPanelsStayOpenExample1" data-aos="fade-up" data-aos-delay="300">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="sell-points">
                                <button class="accordion-button accordion-title" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#sell-points-collapsefive" aria-expanded="true"
                                    aria-controls="sell-points-collapsefive">
                                    {{__('translate.Sell Points')}}
                                </button>
                            </h2>
                            <div id="sell-points-collapsefive" class="accordion-collapse collapse show" aria-labelledby="sell-points">
                                <div class="accordion-body">
                                    <div class="flex-wrap gap-3 py-2 d-flex">
                                    <span class="px-3 accessories-text1 h-100 ms-2">
                                            {{ isset($car->sell_points) ? $car->sell_points : '--' }}
                                        </span>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sell Point End -->

                <!-- Remarks Start -->
                <div class="col-sm-12">
                    <div class="accordion" id="accordionPanelsStayOpenExample4" data-aos="fade-up" data-aos-delay="300">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="remarks-accordion">
                                <button class="accordion-button accordion-title" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#remarks-accordion-collapsefive" aria-expanded="true"
                                    aria-controls="remarks-accordion-collapsefive">
                                    {{__('translate.Remarks')}}
                                </button>
                            </h2>
                            <div id="remarks-accordion-collapsefive" class="accordion-collapse collapse show" aria-labelledby="remarks-accordion">
                                <div class="accordion-body">
                                    <div class="flex-wrap gap-3 py-2 d-flex">
                                    <span class="px-3 accessories-text1 h-100 ms-2">
                                            {{ isset($car->remarks) ? $car->remarks : '--' }}
                                        </span>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Remarks End -->

                <!-- Accessories Start -->
                <div class="col-sm-12">
                    <div class="accordion" id="accordionPanelsStayOpenExample4" data-aos="fade-up" data-aos-delay="300">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="accessories-accordion">
                                <button class="accordion-button accordion-title" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#accessories-accordion-collapsefive" aria-expanded="true"
                                    aria-controls="accessories-accordion-collapsefive">
                                    {{__('translate.Accessories')}}
                                </button>
                            </h2>
                            <div id="accessories-accordion-collapsefive" class="accordion-collapse collapse show" aria-labelledby="accessories-accordion">
                                <div class=" accordion-body">
                                    <div class="flex-row gap-1 ps-4 d-flex">                                        
                                        @foreach($accesories as $value)
                                            <div class="gap-3 py-2">
                                                <span class="px-3 py-1 accessories-text h-100">
                                                    
                                                    {{ isset($value) ? $value : '--' }}
                                                </span> 
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            

                        </div>
                    </div>
                </div>
                <!-- Accessories End -->
                 
            </div>
        </div>
    </section>
    <!-- Inventory Details-part-end -->



   

</main>

@endsection


@push('js_section')

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        "use strict";
        document.getElementById("openModalBtn").addEventListener("click", function () {
            let myModal = new bootstrap.Modal(document.getElementById("myModal"), {});
            myModal.show();
        });

        function carReview(rating){
            $(".car_rat").each(function(){
                var car_rat = $(this).data('rating')
                if(car_rat > rating){
                    $(this).removeClass('fa-solid fa-star').addClass('fa-regular fa-star');
                }else{
                    $(this).removeClass('fa-regular fa-star').addClass('fa-solid fa-star');
                }
            })
            $("#car_rating").val(rating);
        }
    </script>


    <script>
        "use strict";

        let currencyPosition = "{{ Session::get('currency_position') }}";
        let currencyIcon = "{{ Session::get('currency_icon') }}";

        function calculateMonthlyPayment(loanAmount, interestRate, loanTermYears)
        {
            let monthlyInterestRate = (interestRate / 100) / 12;
            let totalPayments = loanTermYears * 12;

            let monthlyPayment = loanAmount * (monthlyInterestRate * Math.pow(1 + monthlyInterestRate, totalPayments))
                / (Math.pow(1 + monthlyInterestRate, totalPayments) - 1);

            return monthlyPayment;
        }

        $("#calculate_btn").on("click", function(e){
            e.preventDefault();

            let loanAmount = $("#loan_amount").val();
            let interestRate = $("#interest_rate").val();
            let loanTermYears = $("#total_year").val();

            if(!loanAmount){
                toastr.error("{{ __('translate.Please fill out the form') }}")
                return;
            }

            if(!interestRate){
                toastr.error("{{ __('translate.Please fill out the form') }}")
                return;
            }

            if(!loanTermYears){
                toastr.error("{{ __('translate.Please fill out the form') }}")
                return;
            }

            let finalPayment = calculateMonthlyPayment(loanAmount, interestRate, loanTermYears)
            finalPayment = finalPayment.toFixed(2)
            finalPayment = finalPayment.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            let appendCurrency = '';

            if(currencyPosition == 'before_price'){
                appendCurrency = `${currencyIcon}${finalPayment}`
            }else if(currencyPosition == 'before_price_with_space'){
                appendCurrency = `${currencyIcon} ${finalPayment}`
            }else if(currencyPosition == 'after_price'){
                appendCurrency = `${finalPayment}${currencyIcon}`
            }else if(currencyPosition == 'after_price_with_space'){
                appendCurrency = `${finalPayment} ${currencyIcon}`
            }

            $("#monthly_payment").html(appendCurrency);
        })


        $("#calculate_total_price").on('click',function(){
           
           if($("#location").val() != ""){
            var start_price = $("#price_value").text().replace(/[^0-9.-]+/g, ''); // Clean the start price
            //  var comission_price = $("#commission_value").text().replace(/[^0-9.-]+/g, ''); // Clean the commission price
            // var comission_price = {{ round($car->commission_value) }}; // Clean the commission price
            //  var delivery_charge = $("#delivery_charge").text().replace(/[^0-9.-]+/g, ''); // Clean the commission price
            //  var shipping_charge = $("#shipping_charge").text().replace(/[^0-9.-]+/g, ''); 
            // var shipping_charge={{ round($car->shipping_value) }}; 


            var delivery_location=$("#location").val();
            var delivery_charges=@json($delivery_charges);
            var filtered_charge = delivery_charges.filter(function(item) {
                return item.id == delivery_location;
            });
 
            if ($("#marine_insurance").is(':checked')) {
                // var delivery_charge_num = Number(shipment);  
                var marine_insurance={{ round($car_charges->marine_insurance_value) }}
            } else {
                var marine_insurance=0;
            }
 
            if ($("#inland_inspection").is(':checked')) {
                var inland_inspection={{ round($car_charges->inland_inspection_value) }}
            } else {
                var inland_inspection=0;
            }


            var shipment=0;
            
            if(filtered_charge.length > 0){
                if ($("input[name='shipment']:checked").val() === "1") {
                    shipment =filtered_charge[0].roro;
                } else if ($("input[name='shipment']:checked").val() === "2") {
                    // Container is selected
                    shipment =filtered_charge[0].container;
                } else {
                    toastr.error('Select Shipment','Failed')
                }   
            } 
 
             // Convert to numbers
             var start_price_num = Number(start_price);
            //  var comission_price_num = Number(comission_price);
            //  var delivery_charge_num = Number(delivery_charge);
            var delivery_charge_num = Number(shipment);

            //  var shipping_charge_num = Number(shipping_charge);
 
             // Check if conversion was successful
             if (isNaN(start_price_num) || isNaN(marine_insurance) || isNaN(delivery_charge_num) || isNaN(inland_inspection)) {
            //  if (isNaN(start_price_num) || isNaN(delivery_charge_num)) {
                toastr.error("One of the prices is not a valid number.",'Failed');
             } else {
                 // Calculate total
                //  var total_price = start_price_num + comission_price_num + delivery_charge_num +shipping_charge_num;
                 var total_price = start_price_num + delivery_charge_num+marine_insurance+inland_inspection;
 
                 // Display the total price
                 $("#total_price").text('$'+total_price);
             }
           } else {
             toastr.error('Select Location','Failed')
           }
 
             
 
         })

         $("#calculate_total_price1").on('click',function(){
           if($("#location1").val() != ""){
             var start_price = $("#price_value1").text().replace(/[^0-9.-]+/g, ''); // Clean the start price
            //  var comission_price = $("#commission_value1").text().replace(/[^0-9.-]+/g,  ''); // Clean the commission price
            // var comission_price = {{ round($car->commission_value) }}; // Clean the commission price
            //  var delivery_charge = $("#delivery_charge1").text().replace(/[^0-9.-]+/g, ''); // Clean the commission price
            //  var shipping_charge = $("#shipping_charge1").text().replace(/[^0-9.-]+/g, ''); // Clean the commission price
            // var shipping_charge={{ round($car->shipping_value) }}; // Clean the commission price
 
            
            var delivery_location=$("#location1").val();
            var delivery_charges=@json($delivery_charges);
            var filtered_charge = delivery_charges.filter(function(item) {
                return item.id == delivery_location;
            });

            if ($("#marine_insurance1").is(':checked')) {
                // var delivery_charge_num = Number(shipment); 
                 var marine_insurance={{ round($car_charges->marine_insurance_value) }}
            } else {
                var marine_insurance=0;
            }
 
            if ($("#inland_inspection1").is(':checked')) {
                var inland_inspection={{ round($car_charges->inland_inspection_value) }}
            } else {
                var inland_inspection=0;
            }

            var shipment=0;
            
            if(filtered_charge.length > 0){
                if ($("input[name='shipment1']:checked").val() === "1") {
                    shipment =filtered_charge[0].roro;
                } else if ($("input[name='shipment1']:checked").val() === "2") {
                    // Container is selected
                    shipment =filtered_charge[0].container;
                } else {
                    toastr.error('Select Shipment','Failed')
                }   
            } 

             // Convert to numbers
             var start_price_num = Number(start_price);
            //  var comission_price_num = Number(comission_price);
            //  var delivery_charge_num = Number(delivery_charge);
             var delivery_charge_num = Number(shipment);
            //  var shipping_charge_num = Number(shipping_charge);
 
             // Check if conversion was successful
            //  if (isNaN(start_price_num) || isNaN(comission_price_num) || isNaN(delivery_charge_num) || isNaN(shipping_charge_num)) {
             if (isNaN(start_price_num)  || isNaN(delivery_charge_num)) {
                toastr.error("One of the prices is not a valid number.",'Failed');
             } else {
                
                 // Calculate total
                //  var total_price = start_price_num +  delivery_charge_num + comission_price_num  +shipping_charge_num;
                var total_price = start_price_num + delivery_charge_num+marine_insurance+inland_inspection;
 
                //  $("#hidden_commission").val(comission_price_num);
                //  $("#hidden_delivery_charge").val(delivery_charge_num);
                //  $("#hidden_shipping_charge").val(shipping_charge_num);
                 $("#hidden_total").val(total_price);
                 // Display the total price
                 $("#total_price1").text('$'+total_price);
             }
           } else {
             toastr.error('Select Location','Failed')
           }
         })

        $("#loan_amount").on("keyup", function(e){
            let enteredValue = e.target.value;
            let numericValue = enteredValue.replace(/[^0-9.]/g, '');
            $(this).val(numericValue);
        })

        $("#interest_rate").on("keyup", function(e){
            let enteredValue = e.target.value;
            let numericValue = enteredValue.replace(/[^0-9.]/g, '');
            $(this).val(numericValue);
        })

        $("#total_year").on("keyup", function(e){
            let enteredValue = e.target.value;
            let numericValue = enteredValue.replace(/[^0-9.]/g, '');
            $(this).val(numericValue);
        })

        $("#reset_btn").on("click", function(){
            $("#loan_amount").val('');
            $("#interest_rate").val('');
            $("#total_year").val('');

            let appendCurrency = '';

            if(currencyPosition == 'before_price'){
                appendCurrency = `${currencyIcon}0.00`
            }else if(currencyPosition == 'before_price_with_space'){
                appendCurrency = `${currencyIcon} 0.00`
            }else if(currencyPosition == 'after_price'){
                appendCurrency = `0.00${currencyIcon}`
            }else if(currencyPosition == 'after_price_with_space'){
                appendCurrency = `0.00 ${currencyIcon}`
            }

            $("#monthly_payment").html(appendCurrency);

        })

        $("#location").on('change',function(){
             var locations=@json($delivery_charges);
             var user_info=locations.filter(p=>p.id==$(this).val());
             if(user_info!=""){
                $("#delivery_charge").text('$'+user_info[0].rate);
             }
        })

        $("#location1").on('change',function(){
             var locations=@json($delivery_charges);
             var user_info=locations.filter(p=>p.id==$(this).val());
             if(user_info!=""){
                $("#delivery_charge1").text('$'+user_info[0].rate);
             }
        })

        $('.download-gallery').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var images = @json($car_images);
            var baseUrl = window.location.origin;
            var button = $(this);
            var originalText = button.text();  // Save original text
            button.text('Processing...');   
            button.attr('disabled',true);  

            images.forEach(function(imageUrl, index) {
                var proxyUrl = '/proxy-image?url=' + encodeURIComponent(imageUrl);
                    $.ajax({
                        url: proxyUrl,
                        method: 'GET',
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function(blob) {
                            const url = window.URL.createObjectURL(blob);
                            const link = document.createElement('a');
                            link.href = url;
                            link.download = `image_${index + 1}.jpg`;
                            link.style.display = 'none';
                            document.body.appendChild(link);
                            link.click();

                            // Cleanup
                            setTimeout(function() {
                                window.URL.revokeObjectURL(url);
                                document.body.removeChild(link);
                            }, 100);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error downloading image:', error);
                        }
                    });

            });

            setTimeout(function() {
                button.text(originalText); // Restore original button text
                button.attr('disabled', false);
            }, images.length * 1000);
            });

            // window.addEventListener("load", function() {
            //  document.getElementById("pageLoader").classList.add("hidden");
            // });


            const locationSelect = document.getElementById("location");
            const deliveryChargeButton = document.getElementById("delivery_charge");

            locationSelect.addEventListener("change", () => {
            if (locationSelect.value) {
                deliveryChargeButton.style.setProperty("color", "#0d274e", "important");
                deliveryChargeButton.style.setProperty("font-size", "14px", "important");
                deliveryChargeButton.style.setProperty("font-weight", "600", "important");
                locationSelect.style.setProperty("color", "#0d274e", "important");
                locationSelect.style.setProperty("font-size", "14px", "important");
                locationSelect.style.setProperty("font-weight", "600", "important");
            } else {
                deliveryChargeButton.style.color = "#868b96";
                deliveryChargeButton.style.setProperty("color", "#868b96", "important");
                locationSelect.style.setProperty("color", "#868b96", "important");
                locationSelect.style.setProperty("font-size", "12px", "important");
            }
        });

    $(document).ready(function() {
        const imageContainer = $(".image-zoom-container");
        const image = $(".main-image");
        const zoomLens = $(".zoom-lens");

        imageContainer.mousemove(function(event) {
            // Get the position of the mouse relative to the container
            const containerOffset = imageContainer.offset();
            const mouseX = event.pageX - containerOffset.left;
            const mouseY = event.pageY - containerOffset.top;

            // Set the position of the zoom lens
            zoomLens.css({
                left: mouseX - zoomLens.width() / 2 + 'px',  // Adjust lens center to mouse position
                top: mouseY - zoomLens.height() / 2 + 'px',  // Adjust lens center to mouse position
                visibility: 'visible'
            });

            // Zoom effect: scale the image
            const zoomRatio = 2; // Adjust zoom level
            const imageWidth = image.width();
            const imageHeight = image.height();

            const bgX = (mouseX / imageWidth) * 100;
            const bgY = (mouseY / imageHeight) * 100;

            image.css({
                transform: `scale(${zoomRatio})`,
                transformOrigin: `${bgX}% ${bgY}%`
            });
        });

        imageContainer.mouseleave(function() {
            zoomLens.css("visibility", "hidden");
            image.css("transform", "scale(1)");  // Reset zoom
        });
});

    
    </script>


@endpush
