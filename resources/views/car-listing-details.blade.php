@extends('layout4')
@section('title')
    <title>{{ html_decode($seo_setting->seo_title) }}</title>
    <meta name="title" content="{{ html_decode($seo_setting->seo_title) }}">
    <meta name="description" content="{{ html_decode($seo_setting->seo_description) }}">
@endsection

@section('body-content')

<main class="bg-light-grey px-sm-2 px-md-5">
<div id="pageLoader">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
</div>


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
                    @foreach ($galleries as $gallery)
                        <div class="inventory-details-slick-img">
                            <div class="inventory-details-slick-img-tag">
                                <div class="icon-main">
                                    
                                </div>
                            </div>
                            <div class="image-zoom-container">
                                <img class="main-image" src="{{ asset($gallery) }}" alt="img">
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
                                 <!-- ${{convertCurrency($car->start_price_num,$usd_rate,2)}} -->
                                 {{ '$' . number_format(convertCurrency($car->start_price_num,$usd_rate,2), 0, '.', ',') }}
                                @else
                                {{$car->start_price}}
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
                        <input class="form-check-input" type="checkbox"  id="marine_insurance1" name="marine_insurance1">
                        <label class="form-check-label ps-2" for="marine_insurance1">
                            Marine insurance 
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"  id="inland_inspection1" name="inland_inspection1">
                        <label class="form-check-label ps-2" for="inland_inspection1">
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
                                 <button type="submit" class="thm-btn-two w-100">BUY NOW</button>  
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



    <!-- Inventory Details-part-start -->


    <section class="inventory-details py-120px listing-breadcrumb bg-light-grey">   
        <div class="container">
            <nav aria-label="breadcrumb" class="px-4">
                <ol class="mb-4 breadcrumb breadcrumb-list">
                    <li class="breadcrumb-item breadcrumb-link"><a href="{{ route($head_page) }}">{{ __('translate.Home') }}</a></li>
                    <li class="breadcrumb-item breadcrumb-link" aria-current="page">{{ $car->model_name_en }}</li>
                    
                </ol>
            </nav>
            <div class="row">
                <div class="px-0 col-lg-8 col-sm-12 col-12 listing_image">
                    <div class="pb-3 row">
                        <div class="m-0 inventory-details-slick-for">
                            @foreach ($galleries as $gallery)
                                <div class="inventory-details-slick-img">
                                    <div class="inventory-details-slick-img-tag">
                                        <div class="icon-main">
                                           
                                        </div>
                                    </div>
                                    <div class="image-zoom-container">
                                        <img class="main-image" src="{{ asset($gallery) }}" alt="img">
                                        <div class="zoom-lens"></div>
                                    </div>
                                    <!-- <a href="#"><img class="main-image" src="{{ asset($gallery) }}" alt="img"></a> -->
                                </div>
                            @endforeach
                        </div>

                        <div class="inventory-details-slick-nav">
                            @foreach ($galleries as $gallery)
                                <div class="inventory-details-slick-img">
                                <img src="{{ asset($gallery) }}" alt="img">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="px-3 d-flex justify-content-end">
                     <button class="thm-btn-two download-gallery" aria-label="Previous" type="button">{{__('translate.Download Car Pictures')}} <span class="ps-2"><i class="ml-3 fa-solid fa-download"></i> <span></button>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 col-sm-12 col-12 listing_form">
                    <div class="p-sticky">
                        <div class="auto-sales-item form-section">
                            <form method="POST" action="{{ route('post.fixed.marketplace', ['id' => request()->segment(3)]) }}" class="sales-form">
                                @csrf
                                <div class="gap-2 d-flex flex-column car-listing-details">
                                    <p class="brand-text fw-bolder">{{$car->company_en}}</p>
                                    <h3>{{$car->model_name_en}}</h3>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="py-3 amount-text" id="price_value">Price 
                                            <span class="price-text">
                                                @if(session('front_lang')=='en')    
                                                    {{ '$' . number_format(convertCurrency($car->start_price_num,$usd_rate,2), 0, '.', ',') }}
                                                    @else
                                                    {{$car->start_price}}
                                                @endif 
                                            </span>
                                        </p>
                                        <p style="display:none;" id="commission_value">${{$car->commission_value}}</p>
                                    </div>
                                    <div class="gap-3 d-flex align-items-center">
                                        <div class="dropdown location-dropdown w-100">
                                            <select class="form-select location-select"
                                                aria-label=".form-select example" name="location" id="location">
                                                <option class="" selected value="">Select Location<i class="bi bi-caret-down"></i>
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
                                        <button type="submit" class="h-full m-0 thm-btn-two" id="fixed_car_btn">BUY NOW</button>
                                </div> 
                            </form>         
                        </div>   
                    </div>
                </div>
            </div>
            <div class="row">
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
                                                        <!-- <svg width="19" height="16" viewBox="0 0 19 16" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M2.58054 6.91356H16.4207C16.5881 6.91356 16.7239 6.77542 16.7239 6.60503C16.7239 6.43465 16.5881 6.29651 16.4207 6.29651H2.58054C2.41309 6.29651 2.27734 6.43465 2.27734 6.60503C2.27734 6.77542 2.41309 6.91356 2.58054 6.91356Z" />
                                                            <path
                                                                d="M18.736 7.73774L17.9522 6.48184L17.6586 5.28173H18.2926C18.46 5.28173 18.5957 5.14359 18.5957 4.9732C18.5957 4.80282 18.46 4.66468 18.2926 4.66468H17.5076L16.7166 1.43155C16.5203 0.628813 15.7296 0 14.9167 0H4.0833C3.27039 0 2.47974 0.628813 2.28336 1.43155L1.49239 4.66468H0.707447C0.540004 4.66468 0.404255 4.80282 0.404255 4.9732C0.404255 5.14359 0.540004 5.28173 0.707447 5.28173H1.3414L1.04779 6.48184L0.263938 7.73778C0.113474 7.97893 0 8.37618 0 8.66179V13.1308C0 13.3012 0.135749 13.4393 0.303191 13.4393H0.404255V15.6915C0.404255 15.8619 0.540004 16.0001 0.707447 16.0001H3.89747C4.06491 16.0001 4.20066 15.8619 4.20066 15.6915V14.3649C4.20066 14.1945 4.06491 14.0564 3.89747 14.0564C3.73002 14.0564 3.59427 14.1945 3.59427 14.3649V15.383H1.01064V13.4393H5.33403C5.33488 13.4393 13.6704 13.4393 13.6704 13.4393C13.6731 13.4393 17.9894 13.4393 17.9894 13.4393V15.383H15.4057V14.3649C15.4057 14.1945 15.27 14.0564 15.1025 14.0564C14.9351 14.0564 14.7993 14.1945 14.7993 14.3649V15.6915C14.7993 15.8619 14.9351 16.0001 15.1025 16.0001H18.2926C18.46 16.0001 18.5957 15.8619 18.5957 15.6915V13.4393H18.6968C18.8643 13.4393 19 13.3012 19 13.1308V8.66179C19 8.37618 18.8865 7.97893 18.736 7.73774ZM0.606383 8.66179C0.606383 8.49346 0.687153 8.21069 0.775847 8.0686L1.58606 6.77042C1.60352 6.7424 1.61646 6.71172 1.62434 6.67955L2.87179 1.58059C2.99957 1.05828 3.55437 0.617048 4.0833 0.617048H14.9167C15.4456 0.617048 16.0005 1.05832 16.1282 1.58063L17.3756 6.67951C17.3835 6.71167 17.3964 6.74236 17.4139 6.77038L18.2241 8.06856C18.3128 8.21069 18.3936 8.49346 18.3936 8.66179V12.8223H13.8767L13.1444 10.9576C13.0984 10.8404 12.9869 10.7636 12.8628 10.7636H6.13716C6.01314 10.7636 5.90164 10.8404 5.85564 10.9576L5.12333 12.8223H0.606383V8.66179ZM13.2237 12.8223H5.77632L6.34252 11.3806H12.6575L13.2237 12.8223Z" />
                                                            <path
                                                                d="M1.51608 11.2778H2.65689C3.56068 11.2778 4.29602 10.5296 4.29602 9.60986C4.29602 8.69017 3.56072 7.94189 2.65689 7.94189H1.74C1.57256 7.94189 1.43681 8.08003 1.43681 8.25042C1.43681 8.42081 1.57256 8.55894 1.74 8.55894H2.65689C3.22632 8.55894 3.68964 9.03037 3.68964 9.60986C3.68964 10.1893 3.22636 10.6608 2.65689 10.6608H1.51608C1.34864 10.6608 1.21289 10.7989 1.21289 10.9693C1.21289 11.1397 1.34864 11.2778 1.51608 11.2778Z" />
                                                            <path
                                                                d="M14.7051 9.60986C14.7051 10.5296 15.4404 11.2778 16.3442 11.2778H17.485C17.6525 11.2778 17.7882 11.1397 17.7882 10.9693C17.7882 10.7989 17.6525 10.6608 17.485 10.6608H16.3442C15.7748 10.6608 15.3115 10.1893 15.3115 9.60986C15.3115 9.03041 15.7747 8.55894 16.3442 8.55894H17.2611C17.4286 8.55894 17.5643 8.42081 17.5643 8.25042C17.5643 8.08003 17.4286 7.94189 17.2611 7.94189H16.3442C15.4404 7.94189 14.7051 8.69013 14.7051 9.60986Z" />
                                                        </svg> -->

                                                        {{ __('translate.Make') }}
                                                    </span>
                                                    {{ html_decode($car->company_en) }}
                                                </li>

                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        
                                                        <!-- <svg width="20" height="23" viewBox="0 0 20 23" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M2.82221 15.8551H2.01367V6.50986C2.01464 5.81158 2.32095 5.14216 2.86543 4.64847C3.4099 4.15478 4.14805 3.87714 4.91794 3.87646H15.078C15.8479 3.87714 16.5861 4.15478 17.1305 4.64847C17.675 5.14216 17.9813 5.81158 17.9823 6.50986V15.8547H17.1738V6.50986C17.173 6.00601 16.9519 5.52301 16.5591 5.1668C16.1662 4.8106 15.6336 4.61028 15.078 4.6098H4.91794C4.36242 4.61028 3.8298 4.8106 3.43691 5.1668C3.04403 5.52301 2.82296 6.00601 2.82221 6.50986V15.8551Z" />
                                                            <path
                                                                d="M13.2638 4.60972H6.73165C6.37874 4.60904 6.0405 4.48156 5.79099 4.25519C5.54148 4.02882 5.40103 3.72201 5.40039 3.40192V1.2078C5.40103 0.88771 5.54148 0.580896 5.79099 0.354527C6.0405 0.128157 6.37874 0.000678442 6.73165 0H13.2638C13.6167 0.000678442 13.955 0.128157 14.2045 0.354527C14.454 0.580896 14.5944 0.88771 14.5951 1.2078V3.40192C14.5944 3.72201 14.454 4.02882 14.2045 4.25519C13.955 4.48156 13.6167 4.60904 13.2638 4.60972ZM6.73165 0.733331C6.59317 0.734006 6.46059 0.784222 6.36271 0.873066C6.26483 0.96191 6.20957 1.0822 6.20893 1.2078V3.40192C6.20957 3.52752 6.26483 3.64781 6.36271 3.73665C6.46059 3.82549 6.59317 3.87571 6.73165 3.87639H13.2638C13.4023 3.87571 13.5349 3.82549 13.6328 3.73665C13.7306 3.64781 13.7859 3.52752 13.7865 3.40192V1.2078C13.7859 1.0822 13.7306 0.96191 13.6328 0.873066C13.5349 0.784222 13.4023 0.734006 13.2638 0.733331H6.73165Z" />
                                                            <path
                                                                d="M13.1431 18.1214H6.85349C6.74627 18.1214 6.64344 18.0828 6.56763 18.014C6.49181 17.9453 6.44922 17.852 6.44922 17.7548V9.68005C6.44954 9.36753 6.58659 9.06789 6.83028 8.84694C7.07397 8.62599 7.40437 8.50178 7.74894 8.50159H12.2476C12.5922 8.50178 12.9226 8.62599 13.1663 8.84694C13.41 9.06789 13.5471 9.36753 13.5474 9.68005V17.754C13.5475 17.8022 13.5371 17.85 13.5168 17.8946C13.4966 17.9391 13.4668 17.9796 13.4293 18.0138C13.3917 18.0479 13.3471 18.075 13.298 18.0934C13.2489 18.1119 13.1963 18.1214 13.1431 18.1214ZM7.25776 17.3881H12.7388V9.68005C12.7386 9.56199 12.6868 9.44882 12.5947 9.36538C12.5026 9.28193 12.3778 9.23501 12.2476 9.23492H7.74894C7.61878 9.23501 7.49396 9.28193 7.40188 9.36538C7.3098 9.44882 7.25797 9.56199 7.25776 9.68005V17.3881Z" />
                                                            <path
                                                                d="M6.85352 14.3715H13.1435V15.1048H6.85352V14.3715Z" />
                                                            <path
                                                                d="M6.85352 5.81824H13.1435V6.55157H6.85352V5.81824Z" />
                                                            <path
                                                                d="M16.8984 22.1441H3.10155C2.27923 22.1433 1.49084 21.8467 0.909369 21.3193C0.327901 20.7919 0.000856138 20.0768 0 19.331V16.8223C0.000427818 16.4682 0.155674 16.1287 0.431689 15.8783C0.707703 15.6279 1.08195 15.487 1.47235 15.4865H3.12257C3.41408 15.4872 3.70042 15.5564 3.9537 15.6873C4.20697 15.8182 4.41856 16.0063 4.56784 16.2334L5.3218 17.388H14.6782L15.4326 16.2334C15.5817 16.0063 15.7932 15.8181 16.0464 15.6872C16.2997 15.5563 16.586 15.4872 16.8774 15.4865H18.5277C18.918 15.487 19.2923 15.6279 19.5683 15.8783C19.8443 16.1287 19.9996 16.4682 20 16.8223V19.331C19.9991 20.0768 19.6721 20.7919 19.0906 21.3193C18.5092 21.8467 17.7208 22.1433 16.8984 22.1441ZM1.47235 16.2198C1.29632 16.2201 1.12761 16.2837 1.00318 16.3966C0.878744 16.5096 0.808752 16.6626 0.808538 16.8223V19.331C0.80918 19.8824 1.05097 20.411 1.48086 20.8009C1.91074 21.1908 2.4936 21.4101 3.10155 21.4107H16.8984C17.5064 21.4101 18.0893 21.1908 18.5191 20.8009C18.949 20.411 19.1908 19.8824 19.1915 19.331V16.8223C19.1912 16.6626 19.1213 16.5096 18.9968 16.3966C18.8724 16.2837 18.7037 16.2201 18.5277 16.2198H16.8774C16.7262 16.2203 16.5777 16.2562 16.4464 16.3241C16.315 16.392 16.2053 16.4896 16.1279 16.6074L15.2555 17.9417C15.2197 17.9964 15.1688 18.0418 15.1079 18.0733C15.0469 18.1048 14.978 18.1214 14.9078 18.1214H5.09096C5.0208 18.1214 4.95186 18.1048 4.8909 18.0733C4.82994 18.0418 4.77907 17.9964 4.74329 17.9417L3.87209 16.6074C3.79474 16.4896 3.68503 16.3919 3.55367 16.324C3.42231 16.2561 3.27378 16.2202 3.12257 16.2198H1.47235Z" />
                                                            <path
                                                                d="M12.0212 2.38332H7.97849C7.87127 2.38332 7.76844 2.34469 7.69263 2.27593C7.61681 2.20717 7.57422 2.1139 7.57422 2.01666V0.366665C7.57422 0.26942 7.61681 0.176157 7.69263 0.107394C7.76844 0.0386307 7.87127 0 7.97849 0H12.0212C12.1284 0 12.2312 0.0386307 12.307 0.107394C12.3829 0.176157 12.4254 0.26942 12.4254 0.366665V2.01666C12.4254 2.1139 12.3829 2.20717 12.307 2.27593C12.2312 2.34469 12.1284 2.38332 12.0212 2.38332ZM8.38276 1.64999H11.6169V0.733331H8.38276V1.64999Z" />
                                                        </svg> -->

                                                        {{ __('translate.Model') }}
                                                    </span>
                                                    {{ html_decode($car->model_name_en) }}
                                                </li>
                                            
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        <!-- <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M10 8.99982C8.89713 8.99982 8 9.89694 8 10.9998C8 12.1027 8.89713 12.9998 10 12.9998C11.1029 12.9998 12 12.1027 12 10.9998C12 9.89694 11.1029 8.99982 10 8.99982ZM10 12.3332C9.26479 12.3332 8.66667 11.7352 8.66667 10.9998C8.66667 10.2645 9.26479 9.66648 10 9.66648C10.7352 9.66648 11.3333 10.2645 11.3333 10.9998C11.3333 11.7352 10.7352 12.3332 10 12.3332Z" />
                                                            <path
                                                                d="M10 0C4.486 0 0 4.486 0 10C0 15.514 4.486 20 10 20C15.514 20 20 15.514 20 10C20 4.486 15.514 0 10 0ZM10 19.3333C4.8535 19.3333 0.666667 15.1465 0.666667 10C0.666667 4.8535 4.8535 0.666667 10 0.666667C15.1465 0.666667 19.3333 4.8535 19.3333 10C19.3333 15.1465 15.1465 19.3333 10 19.3333Z" />
                                                            <path
                                                                d="M10 2C5.58888 2 2 5.58888 2 10C2 14.4111 5.58888 18 10 18C14.4111 18 18 14.4111 18 10C18 5.58888 14.4111 2 10 2ZM10 2.66667C13.116 2.66667 15.7825 4.62104 16.8428 7.36771L14.3454 8.20017C13.4888 8.48567 12.5627 8.44104 11.737 8.07387C10.6332 7.583 9.36671 7.583 8.26321 8.07387L8.13692 8.12988C7.38625 8.46388 6.53521 8.53125 5.74058 8.31933L3.06967 7.60708C4.06417 4.73525 6.79421 2.66667 10 2.66667ZM2.66667 10C2.66667 9.93208 2.66996 9.86496 2.67183 9.7975L2.90333 9.87467C6.18983 10.9701 8.51404 13.8415 8.92746 17.2541C5.39054 16.7335 2.66667 13.6793 2.66667 10ZM10 17.3333C9.86763 17.3333 9.73617 17.3294 9.6055 17.3224C9.21158 13.589 6.69408 10.4356 3.11425 9.2425L2.72233 9.11188C2.75804 8.81771 2.81058 8.52875 2.88017 8.24629L5.56867 8.96325C5.929 9.05929 6.29896 9.10713 6.66858 9.10713C7.26429 9.10713 7.85854 8.98342 8.4075 8.73929L8.53379 8.68329C9.46542 8.26858 10.5341 8.26892 11.467 8.68363C12.4444 9.11754 13.5414 9.17092 14.5563 8.83238L17.0537 7.99988C17.156 8.36017 17.2314 8.7315 17.2776 9.11188L16.8857 9.2425C13.3058 10.4356 10.7883 13.589 10.3944 17.3224C10.2638 17.3294 10.1324 17.3333 10 17.3333ZM11.0726 17.2541C11.486 13.8415 13.8102 10.9701 17.0967 9.87467L17.3282 9.7975C17.33 9.86496 17.3334 9.93208 17.3334 10C17.3333 13.6793 14.6095 16.7335 11.0726 17.2541Z" />
                                                        </svg>  -->

                                                        {{ __('translate.Grade') }}
                                                    </span>
                                                    {{ html_decode(array_key_exists('model_grade',$process_data_en) ? $process_data_en['model_grade'] : $car->model_grade_en) }}
                                              
                                                </li>

                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Engine Size') }}
                                                    </span>
                                                    {{ html_decode(array_key_exists('displacement',$process_data_en) ? $process_data_en['displacement'] : $car->displacement) }}
                                             
                                                </li>

                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Chassis number') }}
                                                    </span>
                                                    {{ html_decode(array_key_exists('model_type',$process_data_en) ? $process_data_en['model_type'] : $car->model_type_en) }}

                                                </li>

                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Model Detail Name') }}
                                                    </span>
                                                    {{ html_decode($car->model_details_en) }}
                                                </li>
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Date') }}
                                                    </span>
                                                    {{ html_decode($car->date) }}
                                                </li>
                                             
                                            </ul>
                                        </div>
                                        <div class="col-lg-6 ">
                                            <ul class="key-information two">
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Year') }}
                                                    </span>
                                                    {{ html_decode($car->model_year_en) }}

                                                </li>
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Auction System') }}
                                                    </span>
                                                       {{$car->auction_system}}
                                                </li>
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Mileage') }}
                                                    </span>
                                                    {{ html_decode(array_key_exists('mileage',$process_data_en) ? $process_data_en['mileage'] : $car->mileage_en) }}
                                                 
                                                </li>

                                            

                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Exterior Color') }}
                                                    </span>
                                                    {{ html_decode($car->color_en) }}
                                                </li>



                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Fuel Type') }}
                                                    </span>
                                                    {{ html_decode($car->fuel_type) }}
                                                </li>
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        
                                                        {{ __('translate.Transmission') }}
                                                    </span>
                                                    {{ html_decode(array_key_exists('transmission',$process_data_en) ? $process_data_en['transmission'] : $car->transmission) }}
                                                </li>
                                                <li class="car_model_name">
                                                    <span class="car_model_spec">
                                                        {{ __('translate.Inspection') }}
                                                    </span>
                                                    {{ html_decode(array_key_exists('inspection',$process_data_en) ? $process_data_en['inspection'] : $car->inspection) }}

                                                    {{ html_decode($car->inspection) }}
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

                <!-- Accessories Start -->
                <div class="pb-3 col-sm-12">
                    <div class="accordion" id="accordionPanelsStayOpenExample4" data-aos="fade-up" data-aos-delay="300">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="panelsStayOpen-headingfive">
                                <button class="accordion-button accordion-title" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#panelsStayOpen-collapsefive" aria-expanded="true"
                                    aria-controls="panelsStayOpen-collapsefive">
                                    {{__('translate.Sell Points')}}
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapsefive" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingfive">
                                <div class="accordion-body">
                                    <div class="flex-wrap gap-1 py-2 ps-4 d-flex ">
                                        <span class="px-3 py-1 accessories-text1 h-100">
                                            {{ isset($process_data_en['Condition']) ? $process_data_en['Condition'] : '--' }}
                                        </span>
                                       
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



    <!-- Cars Listing-part-start -->
      {{-- @if ($related_listings->count() > 0)
            <section class="mt-3 cars-listing feature-two bg-light-grey">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h2 class="section-heading">Popular <span class="highlight"> Brands<span></h2>
                                    <div class="categories-three-view-btn">
                                        <a href="#" class="thm-btn">SEE ALL</a>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-56px ">

                                @foreach ($related_listings as $related_car)
                                    <div class="col-lg-3">
                                        <div class="brand-car-item">
                                            <div class="brand-car-item-img">
                                                <img src="{{ asset($related_car->thumb_image) }}" alt="thumb">

                                                <div class="brand-car-item-img-text">

                                                    <div class="text-df">
                                                        @if ($related_car->offer_price)
                                                            <p class="text">{{ calculate_percentage($related_car->regular_price, $related_car->offer_price) }}% {{ __('translate.Off') }}</p>
                                                        @endif

                                                        @if ($related_car->condition == 'New')
                                                            <p class="text text-two ">{{ __('translate.New') }}</p>
                                                        @else
                                                            <p class="text text-two ">{{ __('translate.Used') }}</p>
                                                        @endif
                                                    </div>

                                                    <div class="icon-main">

                                                        @guest('web')
                                                            <a  href="javascript:;" class="icon before_auth_wishlist">
                                                                <span>
                                                                    <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M9.61204 2.324L9 2.96329L8.38796 2.324C6.69786 0.558667 3.95767 0.558666 2.26757 2.324C0.577476 4.08933 0.577475 6.95151 2.26757 8.71684L7.77592 14.4704C8.45196 15.1765 9.54804 15.1765 10.2241 14.4704L15.7324 8.71684C17.4225 6.95151 17.4225 4.08934 15.7324 2.324C14.0423 0.558667 11.3021 0.558666 9.61204 2.324Z"
                                                                        stroke-width="1.3" stroke-linejoin="round"></path>
                                                                </svg>

                                                                </span>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('user.add-to-wishlist', $related_car->id) }}" class="icon">
                                                                <span>
                                                                    <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M9.61204 2.324L9 2.96329L8.38796 2.324C6.69786 0.558667 3.95767 0.558666 2.26757 2.324C0.577476 4.08933 0.577475 6.95151 2.26757 8.71684L7.77592 14.4704C8.45196 15.1765 9.54804 15.1765 10.2241 14.4704L15.7324 8.71684C17.4225 6.95151 17.4225 4.08934 15.7324 2.324C14.0423 0.558667 11.3021 0.558666 9.61204 2.324Z"
                                                                        stroke-width="1.3" stroke-linejoin="round"></path>
                                                                </svg>

                                                                </span>
                                                            </a>

                                                        @endif


                                                        <a href="{{ route('add-to-compare', $related_car->id) }}" class="icon">
                                                            <span>
                                                                <svg width="18" height="20" viewBox="0 0 18 20" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M1 10V9C1 6.23858 3.23858 4 6 4H17L14 1"
                                                                        stroke-width="1.3" stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                    <path d="M17 10V11C17 13.7614 14.7614 16 12 16H1L4 19"
                                                                        stroke-width="1.3" stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                </svg>
                                                            </span>
                                                        </a>
                                                    </div>


                                                </div>
                                            </div>

                                            <div class="brand-car-inner">
                                                <div class="brand-car-inner-item">
                                                    <span>{{ $related_car?->brand?->name }}</span>
                                                    <p>
                                                        @if ($related_car->offer_price)
                                                            {{ currency($related_car->offer_price) }}
                                                        @else
                                                            {{ currency($related_car->regular_price) }}
                                                        @endif
                                                    </p>
                                                </div>

                                                <a href="{{ route('listing', $related_car->slug) }}">
                                                    <h3>{{ html_decode($related_car->title) }}</h3>
                                                </a>

                                                <div class="brand-car-inner-item-main">
                                                    <div class="brand-car-inner-item-two">
                                                        <div class="brand-car-inner-item-thumb">
                                                            <span>
                                                                <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M20 10.2935C20 7.75456 18.9535 5.45057 17.2608 3.77159C17.2476 3.7544 17.2335 3.73758 17.2175 3.72192C17.2015 3.70626 17.1843 3.69249 17.1668 3.67963C15.4505 2.02368 13.0953 1 10.5 1C7.90472 1 5.54953 2.02374 3.83318 3.67963C3.81561 3.69255 3.79848 3.70632 3.78247 3.72192C3.76646 3.73758 3.75238 3.75434 3.73918 3.77159C2.0465 5.45057 1 7.75456 1 10.2935C1 12.7755 1.98794 15.1089 3.78179 16.8642C3.78204 16.8644 3.78229 16.8647 3.78253 16.865C3.78272 16.8651 3.78285 16.8653 3.78303 16.8654C3.78328 16.8656 3.78353 16.8659 3.78378 16.8661C3.87498 16.9553 3.99452 16.9999 4.11407 16.9999C4.23368 16.9999 4.35328 16.9553 4.44448 16.866C4.45227 16.8584 4.45931 16.8503 4.46641 16.8422L5.90617 15.4337C6.08864 15.2552 6.08864 14.9658 5.90617 14.7873C5.72371 14.6089 5.42787 14.6089 5.24547 14.7873L4.12192 15.8864C2.81179 14.4602 2.05173 12.6653 1.9472 10.7505H3.53616C3.79418 10.7505 4.00337 10.546 4.00337 10.2935C4.00337 10.041 3.79418 9.83642 3.53616 9.83642H1.94732C2.05596 7.86974 2.86107 6.08137 4.12497 4.70343L5.24547 5.79958C5.33667 5.88879 5.45628 5.9334 5.57582 5.9334C5.69537 5.9334 5.81497 5.88879 5.90617 5.79958C6.08864 5.62102 6.08864 5.33167 5.90617 5.15318L4.78573 4.05697C6.19435 2.82055 8.0224 2.03295 10.0328 1.92673V3.48108C10.0328 3.73356 10.242 3.93814 10.5 3.93814C10.758 3.93814 10.9672 3.73356 10.9672 3.48108V1.92673C12.9776 2.03295 14.8056 2.82061 16.2143 4.05703L15.0938 5.15318C14.9113 5.33173 14.9113 5.62108 15.0938 5.79958C15.185 5.88879 15.3046 5.9334 15.4241 5.9334C15.5437 5.9334 15.6633 5.88879 15.7545 5.79958L16.875 4.70343C18.1389 6.08143 18.944 7.86974 19.0526 9.83642H17.4637C17.2057 9.83642 16.9965 10.041 16.9965 10.2935C16.9965 10.546 17.2057 10.7505 17.4637 10.7505H19.0527C18.9481 12.6653 18.1881 14.4603 16.878 15.8865L15.7545 14.7873C15.5721 14.6089 15.2762 14.6089 15.0938 14.7873C14.9113 14.9659 14.9113 15.2552 15.0938 15.4337L16.5568 16.8649C16.648 16.9541 16.7676 16.9987 16.8871 16.9987C16.9469 16.9987 17.0067 16.9876 17.0629 16.9653C17.1192 16.943 17.1719 16.9095 17.2175 16.8649C19.0118 15.1096 20 12.7758 20 10.2935Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                    <path d="M12.6465 5.05246C12.4068 4.95855 12.135 5.07238 12.039 5.30676L10.6889 8.60366C10.626 8.59708 10.5631 8.59257 10.5001 8.59257C9.8425 8.59257 9.24852 8.94889 8.94981 9.52246C8.63759 10.1221 8.71758 10.8385 9.16361 11.4387C9.20921 11.5001 9.26652 11.5562 9.32969 11.6012C9.69206 11.8589 10.0968 11.9951 10.5001 11.9951C11.1577 11.9951 11.7517 11.6388 12.0504 11.0652C12.3626 10.4656 12.2826 9.74922 11.8369 9.14938C11.7913 9.08783 11.7338 9.03152 11.6705 8.98643C11.6364 8.96217 11.6016 8.94005 11.5668 8.91799L12.9064 5.64663C13.0024 5.41237 12.886 5.1463 12.6465 5.05246ZM11.2177 10.6502C11.0793 10.9159 10.8043 11.0809 10.5 11.0809C10.3004 11.0809 10.0995 11.0127 9.90268 10.8782C9.67842 10.5631 9.63437 10.2216 9.78245 9.93735C9.92075 9.67171 10.1957 9.50668 10.5001 9.50668C10.5971 9.50668 10.6944 9.52313 10.7915 9.55513C10.7947 9.55641 10.7976 9.55805 10.8008 9.55933C10.8111 9.56329 10.8213 9.56652 10.8316 9.56975C10.9207 9.60321 11.0094 9.64928 11.0974 9.70937C11.3216 10.0244 11.3657 10.3659 11.2177 10.6502Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                    </svg>

                                                            </span>
                                                        </div>

                                                        <span>
                                                            {{ html_decode($related_car->mileage) }}
                                                        </span>
                                                    </div>
                                                    <div class="brand-car-inner-item-two">
                                                        <div class="brand-car-inner-item-thumb">
                                                            <span>
                                                                <svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M15.8901 3.09765L14.3901 1.76431C14.2436 1.63409 14.0063 1.63409 13.8598 1.76431C13.7133 1.89453 13.7133 2.10547 13.8598 2.23565L15.0947 3.33331L13.8598 4.43096C13.7895 4.49346 13.75 4.57809 13.75 4.66665V5.66665C13.75 6.40202 14.4227 6.99999 15.25 6.99999V12.6666C15.25 12.8505 15.0819 13 14.875 13C14.6681 13 14.5 12.8506 14.5 12.6666V12C14.5 11.4485 13.9953 11 13.375 11H13V2.33334C13 1.59797 12.3273 1 11.5 1H4.00001C3.17275 1 2.50001 1.59797 2.50001 2.33334V14.3333C1.67275 14.3333 1 14.9313 1 15.6667V16.6667C1 16.8509 1.16773 17 1.37501 17H14.125C14.3323 17 14.5 16.8509 14.5 16.6667V15.6667C14.5 14.9313 13.8273 14.3333 13 14.3333V11.6667H13.375C13.5819 11.6667 13.75 11.8161 13.75 12V12.6667C13.75 13.2181 14.2546 13.6667 14.875 13.6667C15.4954 13.6667 16 13.2181 16 12.6667V3.33334C16 3.24478 15.9604 3.16015 15.8901 3.09765ZM3.25003 2.33334C3.25003 1.96584 3.58658 1.66669 4.00001 1.66669H11.5C11.9134 1.66669 12.25 1.96584 12.25 2.33334V14.3333H3.24999L3.25003 2.33334ZM13.75 15.6666V16.3333H1.75002V15.6666C1.75002 15.2991 2.08657 15 2.50001 15H13C13.4134 15 13.75 15.2991 13.75 15.6666ZM15.25 6.33333C14.8365 6.33333 14.5 6.03418 14.5 5.66668V4.80468L15.25 4.13803V6.33333Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                    <path d="M11.041 2.52344H4.29103C4.08375 2.52344 3.91602 2.66929 3.91602 2.84954V6.76876C3.91602 6.94901 4.08375 7.09487 4.29103 7.09487H11.041C11.2483 7.09487 11.416 6.94901 11.416 6.76876V2.84951C11.416 2.66929 11.2483 2.52344 11.041 2.52344ZM10.666 6.44265H4.666V3.17562H10.666V6.44265Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                    </svg>

                                                            </span>
                                                        </div>

                                                        <span>
                                                            {{ html_decode($related_car->fuel_type) }}
                                                        </span>
                                                    </div>
                                                    <div class="brand-car-inner-item-two">
                                                        <div class="brand-car-inner-item-thumb">
                                                            <span>
                                                                <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M17.9167 8.23819H17.2833C17.0314 8.23819 16.7897 8.3586 16.6116 8.57293C16.4334 8.78726 16.3333 9.07795 16.3333 9.38106V9.76202H15.7V7.85723C15.7 7.55412 15.5999 7.26343 15.4218 7.0491C15.2436 6.83477 15.002 6.71436 14.75 6.71436H13.8C13.716 6.71436 13.6355 6.67422 13.5761 6.60278C13.5167 6.53134 13.4833 6.43444 13.4833 6.3334V5.57149C13.4833 5.26838 13.3832 4.97769 13.2051 4.76336C13.0269 4.54903 12.7853 4.42862 12.5333 4.42862H11.2667V3.28574H12.85C13.102 3.28574 13.3436 3.16533 13.5218 2.951C13.6999 2.73667 13.8 2.44598 13.8 2.14287C13.8 1.83976 13.6999 1.54907 13.5218 1.33474C13.3436 1.12041 13.102 1 12.85 1H6.51667C6.26471 1 6.02307 1.12041 5.84491 1.33474C5.66676 1.54907 5.56667 1.83976 5.56667 2.14287C5.56667 2.44598 5.66676 2.73667 5.84491 2.951C6.02307 3.16533 6.26471 3.28574 6.51667 3.28574H8.1V4.42862H6.51667C6.26471 4.42862 6.02307 4.54903 5.84491 4.76336C5.66676 4.97769 5.56667 5.26838 5.56667 5.57149C5.56667 5.67252 5.5333 5.76942 5.47392 5.84087C5.41453 5.91231 5.33399 5.95245 5.25 5.95245H4.3C4.04804 5.95245 3.80641 6.07285 3.62825 6.28719C3.45009 6.50152 3.35 6.79221 3.35 7.09532V8.61915H2.4V7.09532C2.4 6.79221 2.29991 6.50152 2.12175 6.28719C1.94359 6.07285 1.70196 5.95245 1.45 5.95245C1.19804 5.95245 0.956408 6.07285 0.778249 6.28719C0.600089 6.50152 0.5 6.79221 0.5 7.09532L0.5 13.1906C0.5 13.4937 0.600089 13.7844 0.778249 13.9988C0.956408 14.2131 1.19804 14.3335 1.45 14.3335C1.70196 14.3335 1.94359 14.2131 2.12175 13.9988C2.29991 13.7844 2.4 13.4937 2.4 13.1906V11.6668H3.35V13.5716C3.35 13.8747 3.45009 14.1654 3.62825 14.3797C3.80641 14.5941 4.04804 14.7145 4.3 14.7145H5.62113C5.70511 14.7145 5.78564 14.7546 5.84502 14.8261L7.37388 16.6653C7.46185 16.7719 7.56651 16.8563 7.68181 16.9138C7.7971 16.9713 7.92073 17.0006 8.04553 17.0002H14.75C15.002 17.0002 15.2436 16.8798 15.4218 16.6655C15.5999 16.4511 15.7 16.1604 15.7 15.8573V14.3335H16.3333V14.7145C16.3333 15.0176 16.4334 15.3083 16.6116 15.5226C16.7897 15.7369 17.0314 15.8573 17.2833 15.8573H17.9167C18.3364 15.8567 18.7389 15.6559 19.0357 15.2988C19.3325 14.9417 19.4995 14.4575 19.5 13.9526V10.143C19.4995 9.63798 19.3325 9.15384 19.0357 8.79676C18.7389 8.43967 18.3364 8.23879 17.9167 8.23819ZM6.2 2.14287C6.2 2.04184 6.23336 1.94494 6.29275 1.87349C6.35214 1.80205 6.43268 1.76191 6.51667 1.76191H12.85C12.934 1.76191 13.0145 1.80205 13.0739 1.87349C13.1333 1.94494 13.1667 2.04184 13.1667 2.14287C13.1667 2.24391 13.1333 2.34081 13.0739 2.41225C13.0145 2.48369 12.934 2.52383 12.85 2.52383H6.51667C6.43268 2.52383 6.35214 2.48369 6.29275 2.41225C6.23336 2.34081 6.2 2.24391 6.2 2.14287ZM8.73333 3.28574H10.6333V4.42862H8.73333V3.28574ZM1.76667 13.1906C1.76667 13.2917 1.7333 13.3886 1.67392 13.46C1.61453 13.5315 1.53399 13.5716 1.45 13.5716C1.36601 13.5716 1.28547 13.5315 1.22608 13.46C1.1667 13.3886 1.13333 13.2917 1.13333 13.1906V7.09532C1.13333 6.99428 1.1667 6.89738 1.22608 6.82594C1.28547 6.7545 1.36601 6.71436 1.45 6.71436C1.53399 6.71436 1.61453 6.7545 1.67392 6.82594C1.7333 6.89738 1.76667 6.99428 1.76667 7.09532V13.1906ZM2.4 10.9049V9.38106H3.35V10.9049H2.4ZM15.0667 15.8573C15.0667 15.9584 15.0333 16.0553 14.9739 16.1267C14.9145 16.1982 14.834 16.2383 14.75 16.2383H8.04553C7.96155 16.2383 7.88102 16.1981 7.82165 16.1267L6.29278 14.2874C6.20478 14.181 6.1001 14.0966 5.98482 14.0391C5.86954 13.9816 5.74593 13.9522 5.62113 13.9526H4.3C4.21601 13.9526 4.13547 13.9124 4.07608 13.841C4.0167 13.7695 3.98333 13.6726 3.98333 13.5716V7.09532C3.98333 6.99428 4.0167 6.89738 4.07608 6.82594C4.13547 6.7545 4.21601 6.71436 4.3 6.71436H5.25C5.50196 6.71436 5.74359 6.59395 5.92175 6.37962C6.09991 6.16529 6.2 5.8746 6.2 5.57149C6.2 5.47045 6.23336 5.37355 6.29275 5.30211C6.35214 5.23067 6.43268 5.19053 6.51667 5.19053H12.5333C12.6173 5.19053 12.6979 5.23067 12.7573 5.30211C12.8166 5.37355 12.85 5.47045 12.85 5.57149V6.3334C12.85 6.63651 12.9501 6.92721 13.1282 7.14154C13.3064 7.35587 13.548 7.47627 13.8 7.47628H14.75C14.834 7.47628 14.9145 7.51641 14.9739 7.58785C15.0333 7.6593 15.0667 7.7562 15.0667 7.85723V15.8573ZM15.7 13.5716V10.5239H16.3333V13.5716H15.7ZM18.8667 13.9526C18.8667 14.2557 18.7666 14.5464 18.5884 14.7607C18.4103 14.975 18.1686 15.0954 17.9167 15.0954H17.2833C17.1993 15.0954 17.1188 15.0553 17.0594 14.9838C17 14.9124 16.9667 14.8155 16.9667 14.7145V9.38106C16.9667 9.28003 17 9.18313 17.0594 9.11168C17.1188 9.04024 17.1993 9.0001 17.2833 9.0001H17.9167C18.1686 9.0001 18.4103 9.12051 18.5884 9.33484C18.7666 9.54917 18.8667 9.83987 18.8667 10.143V13.9526Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                    </svg>

                                                            </span>
                                                        </div>

                                                        <span>
                                                            {{ html_decode($related_car->engine_size) }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="brand-car-btm-txt-btm">
                                                    <h6 class="brand-car-btm-txt"><span>{{ __('translate.Listed by') }} :</span>{{ html_decode($related_car?->dealer?->name) }}
                                                    </h6>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach


                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif --}}

    <!--Cars Listing-part-end -->

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
             var comission_price = {{ round($car->commission_value) }}; // Clean the commission price
            //  var delivery_charge = $("#delivery_charge").text().replace(/[^0-9.-]+/g, ''); // Clean the commission price
            //  var delivery_charge = $("#delivery_charge").text().replace(/[^0-9.-]+/g, ''); // Clean the commission price
             var shipping_charge={{ round($car->shipping_value) }};
            //  alert(shipping_charge);
            //  shipping_charge = shipping_charge.replace(/[^0-9.-]+/g, ''); // Clean the commission price


            //  var shipping_charge = $("#shipping_charge").text().replace(/[^0-9.-]+/g, ''); // Clean the commission price
 
            var delivery_location=$("#location").val();
            var delivery_charges=@json($delivery_charges);
            var filtered_charge = delivery_charges.filter(function(item) {
                return item.id == delivery_location;
            });


            if ($("#marine_insurance").is(':checked')) {
                var marine_insurance={{ round($car->marine_insurance_value) }}
            } else {
                var marine_insurance=0;
            }
 
            if ($("#inland_inspection").is(':checked')) {
                var inland_inspection={{ round($car->inland_inspection_value) }}
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
             var comission_price_num = Number(comission_price);
            //  var delivery_charge_num = Number(delivery_charge);
             var delivery_charge_num = Number(shipment);
             var shipping_charge_num = Number(shipping_charge);
             // Check if conversion was successful
             if (isNaN(start_price_num) || isNaN(comission_price_num) || isNaN(delivery_charge_num) || isNaN(shipping_charge_num)) {
                toastr.error("One of the prices is not a valid number.",'Failed');
             } else {
                 // Calculate total
                 var total_price = start_price_num + comission_price_num + delivery_charge_num +shipping_charge_num +marine_insurance+inland_inspection;
 
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
            //  var delivery_charge = $("#delivery_charge1").text().replace(/[^0-9.-]+/g, ''); // Clean the commission price
            //  var shipping_charge={{ round($car->shipping_value) }};
             var comission_price = {{ round($car->commission_value) }};
             //  var comission_price = $("#commission_value1").text().replace(/[^0-9.-]+/g, ''); // Clean the commission price
            //  var shipping_charge = $("#shipping_charge1").text().replace(/[^0-9.-]+/g, ''); // Clean the commission price
            var shipping_charge={{ round($car->shipping_value) }};

            var delivery_location=$("#location1").val();
            var delivery_charges=@json($delivery_charges);
            var filtered_charge = delivery_charges.filter(function(item) {
                return item.id == delivery_location;
            });
            
            
            if ($("#marine_insurance1").is(':checked')) {
                // var delivery_charge_num = Number(shipment); 
                 var marine_insurance={{ round($car->marine_insurance_value) }}
            } else {
                var marine_insurance=0;
            }
 
            if ($("#inland_inspection1").is(':checked')) {
                var inland_inspection={{ round($car->inland_inspection_value) }}
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
             var comission_price_num = Number(comission_price);
            //  var delivery_charge_num = Number(delivery_charge);
            var delivery_charge_num = Number(shipment);
             var shipping_charge_num = Number(shipping_charge);


           


 
             // Check if conversion was successful
             if (isNaN(start_price_num) || isNaN(comission_price_num) || isNaN(delivery_charge_num) || isNaN(shipping_charge_num)) {
                toastr.error("One of the prices is not a valid number.",'Failed');
             } else {
                
                 // Calculate total
                 var total_price = start_price_num + comission_price_num + delivery_charge_num +shipping_charge_num +marine_insurance+inland_inspection;
 
                 $("#hidden_commission").val(comission_price_num);
                 $("#hidden_delivery_charge").val(delivery_charge_num);
                 $("#hidden_shipping_charge").val(shipping_charge_num);
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
            
            var images = @json($galleries);
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

            window.addEventListener("load", function() {
             document.getElementById("pageLoader").classList.add("hidden");
            });


    const locationSelect = document.getElementById("location");
    const deliveryChargeButton = document.getElementById("delivery_charge");
    const locationSelect1 = document.getElementById("location1");
    const deliveryChargeButton1 = document.getElementById("delivery_charge1");

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

    locationSelect1.addEventListener("change", () => {
        if (locationSelect1.value) {
            deliveryChargeButton1.style.setProperty("color", "#0d274e", "important");
            deliveryChargeButton1.style.setProperty("font-size", "14px", "important");
            deliveryChargeButton1.style.setProperty("font-weight", "600", "important");
            locationSelect1.style.setProperty("color", "#0d274e", "important");
            locationSelect1.style.setProperty("font-size", "14px", "important");
            locationSelect1.style.setProperty("font-weight", "600", "important");
        } else {
            deliveryChargeButton1.style.color = "#868b96";
            deliveryChargeButton1.style.setProperty("color", "#868b96", "important");
            locationSelect1.style.setProperty("color", "#868b96", "important");
            locationSelect1.style.setProperty("font-size", "12px", "important");
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
