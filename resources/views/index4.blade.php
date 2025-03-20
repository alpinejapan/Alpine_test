@extends('layout4')
@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
@endsection

@php
use Carbon\Carbon;
@endphp

@section('body-content')
<main class="w-100 main_wid">
    <div id="pageLoader">
        <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
        </div>
  </div>
    <!-- banner-part-start  -->

    <section class="mb-5 banner background_image">
        <div class="container">

            <!-- <div class="px-2 pb-5 row align-items-center px-lg-5"> -->
            <div class="px-5 pt-2 pb-5 row align-items-center">

                <div class="col-lg-12 col-xl-8">
                    <div class="banner-taitel">
                        <span>{{ $homepage->home3_intro_short_title }}</span>
                        <h1 class="banner-h1">Simplifying Your Car </br>
                        <span class="banner-h1">Buying Experience</span></h1>
                        <p>We're committed to helping you find the perfect car with confidence and ease. 
                        Start your simplified car buying experience with us today!</p>
                    </div>
                    <div class="banner-search-bar">
                        <div class="nav-tabs-hide">
                            <ul class="nav nav-tabs custom-tabs heading-section">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#">{{__('translate.Brand')}}</a>
                                </li>
                                <li class="nav-item model-text">
                                    <a class="nav-link" href="#">{{__('translate.Model')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">{{__('translate.Year')}}</a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <!-- <form class="btn-group btn-dc7" id="jdm_stock_form" action="{{route('jdm-stock-all')}}"> -->
                            <form class="btn-group btn-dc7" id="jdm_stock_form" action="{{route('fixed-car-marketplace')}}">
                                <ul class="mb-3 nav nav-tabs custom-tabs rounded-0">
                                    <li class="nav-item">
                                        <div class="custom-select-wrapper">
                                            <!-- <select class="aj-dropdown" id="jdm_brand" name="jdm_brand[]"> -->
                                            <select class="aj-dropdown" id="jdm_brand" name="brand[]">
                                            <option selected value="">Brand</option>
                                            @foreach($jdm_core_brand as $brand)
                                            <!-- <option value="{{ $brand->slug }}"  onchange="updateButtonText('{{ $brand->slug }}')">{{ html_decode($brand->name) }}</option> -->
                                            <option value="{{ $brand->slug }}"  onchange="updateButtonText('{{ $brand->slug }}')">{{ html_decode($brand->company_en) }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </li>
                                    <li class="nav-item"> 
                                        <div class="custom-select-wrapper">
                                            <select class="aj-dropdown" name="model[]" id="jdm_model">
                                            <option selected value="">Model</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <div class="custom-select-wrapper">
                                            <select class="aj-dropdown" class="jdm_year" id="jdm_year" name="year">
                                            <option selected value="">Year</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <button class="gap-3 btn banner-serch d-flex thm-btn-search" type="submit"><img src="{{ asset('japan_home/search.svg') }}" height="15" width="15" /> search</button>   
                                    </li>
                                </ul>
                            </form>    
                        </div>
                    </div>
                </div>


                <div class="pt-5 mt-5 col-lg-4">
                    <div class="banner-slick-main">

                        <div class="banner-slick">
                        @foreach (array_slice($top_sells, 0, 3) as $index => $car)
                                <div class="banner-slick-thumb">
                                <a href="{{ route('fixed-car-marketplace-details', ['category' => 'top-selling', 'id' => $car['id']]) }}">
                                    <img src="{{ asset($car['picture']) }}" alt="thumb" >
                                </a>   
                                     

                                    <div class="banner-slick-thumb-overlay">
                                        <div class="banner-slick-thumb-txt">
                                            <p>{{ $car['model_name_en'] }}</p>
                                            <h6>
                                                {{'$'.$car['start_price_num'] }}
                                            </h6>
                                        </div>
                                        <div class="banner-slick-thumb-txt-two">
                                            <a href="{{ route('fixed-car-marketplace-details', ['category' => 'top-selling', 'id' => $car['id']]) }}">
                                                <h4>{{ html_decode($car['model_name_en']) }}</h4>
                                            </a>
                                        </div>
                                        <div class="banner-slick-thumb-overlay-icon-main">
                                            <div class="banner-slick-thumb-overlay-icon-main-item">
                                                <span class="banner-slick-thumb-overlay-icon" >
                                                <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M20 10.2935C20 7.75456 18.9535 5.45057 17.2608 3.77159C17.2476 3.7544 17.2335 3.73758 17.2175 3.72192C17.2015 3.70626 17.1843 3.69249 17.1668 3.67963C15.4505 2.02368 13.0953 1 10.5 1C7.90472 1 5.54953 2.02374 3.83318 3.67963C3.81561 3.69255 3.79848 3.70632 3.78247 3.72192C3.76646 3.73758 3.75238 3.75434 3.73918 3.77159C2.0465 5.45057 1 7.75456 1 10.2935C1 12.7755 1.98794 15.1089 3.78179 16.8642C3.78204 16.8644 3.78229 16.8647 3.78253 16.865C3.78272 16.8651 3.78285 16.8653 3.78303 16.8654C3.78328 16.8656 3.78353 16.8659 3.78378 16.8661C3.87498 16.9553 3.99452 16.9999 4.11407 16.9999C4.23368 16.9999 4.35328 16.9553 4.44448 16.866C4.45227 16.8584 4.45931 16.8503 4.46641 16.8422L5.90617 15.4337C6.08864 15.2552 6.08864 14.9658 5.90617 14.7873C5.72371 14.6089 5.42787 14.6089 5.24547 14.7873L4.12192 15.8864C2.81179 14.4602 2.05173 12.6653 1.9472 10.7505H3.53616C3.79418 10.7505 4.00337 10.546 4.00337 10.2935C4.00337 10.041 3.79418 9.83642 3.53616 9.83642H1.94732C2.05596 7.86974 2.86107 6.08137 4.12497 4.70343L5.24547 5.79958C5.33667 5.88879 5.45628 5.9334 5.57582 5.9334C5.69537 5.9334 5.81497 5.88879 5.90617 5.79958C6.08864 5.62102 6.08864 5.33167 5.90617 5.15318L4.78573 4.05697C6.19435 2.82055 8.0224 2.03295 10.0328 1.92673V3.48108C10.0328 3.73356 10.242 3.93814 10.5 3.93814C10.758 3.93814 10.9672 3.73356 10.9672 3.48108V1.92673C12.9776 2.03295 14.8056 2.82061 16.2143 4.05703L15.0938 5.15318C14.9113 5.33173 14.9113 5.62108 15.0938 5.79958C15.185 5.88879 15.3046 5.9334 15.4241 5.9334C15.5437 5.9334 15.6633 5.88879 15.7545 5.79958L16.875 4.70343C18.1389 6.08143 18.944 7.86974 19.0526 9.83642H17.4637C17.2057 9.83642 16.9965 10.041 16.9965 10.2935C16.9965 10.546 17.2057 10.7505 17.4637 10.7505H19.0527C18.9481 12.6653 18.1881 14.4603 16.878 15.8865L15.7545 14.7873C15.5721 14.6089 15.2762 14.6089 15.0938 14.7873C14.9113 14.9659 14.9113 15.2552 15.0938 15.4337L16.5568 16.8649C16.648 16.9541 16.7676 16.9987 16.8871 16.9987C16.9469 16.9987 17.0067 16.9876 17.0629 16.9653C17.1192 16.943 17.1719 16.9095 17.2175 16.8649C19.0118 15.1096 20 12.7758 20 10.2935Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                        <path d="M12.6465 5.05246C12.4068 4.95855 12.135 5.07238 12.039 5.30676L10.6889 8.60366C10.626 8.59708 10.5631 8.59257 10.5001 8.59257C9.8425 8.59257 9.24852 8.94889 8.94981 9.52246C8.63759 10.1221 8.71758 10.8385 9.16361 11.4387C9.20921 11.5001 9.26652 11.5562 9.32969 11.6012C9.69206 11.8589 10.0968 11.9951 10.5001 11.9951C11.1577 11.9951 11.7517 11.6388 12.0504 11.0652C12.3626 10.4656 12.2826 9.74922 11.8369 9.14938C11.7913 9.08783 11.7338 9.03152 11.6705 8.98643C11.6364 8.96217 11.6016 8.94005 11.5668 8.91799L12.9064 5.64663C13.0024 5.41237 12.886 5.1463 12.6465 5.05246ZM11.2177 10.6502C11.0793 10.9159 10.8043 11.0809 10.5 11.0809C10.3004 11.0809 10.0995 11.0127 9.90268 10.8782C9.67842 10.5631 9.63437 10.2216 9.78245 9.93735C9.92075 9.67171 10.1957 9.50668 10.5001 9.50668C10.5971 9.50668 10.6944 9.52313 10.7915 9.55513C10.7947 9.55641 10.7976 9.55805 10.8008 9.55933C10.8111 9.56329 10.8213 9.56652 10.8316 9.56975C10.9207 9.60321 11.0094 9.64928 11.0974 9.70937C11.3216 10.0244 11.3657 10.3659 11.2177 10.6502Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                        </svg>
                                                        <!-- <img src="{{asset('frontend/assets/images/icon/fuel.png')}}" /> -->
                                                </span>
                                                <h5 class="banner-slick-thumb-overlay-txt" >  
                                                    @if(session('front_lang')=='en')
                                                    {{ html_decode($car['mileage_en']) . ',000' }}
                                                    @else
                                                    {{ html_decode($car['mileage']) . ',000'}}
                                                    @endif
                                                </h5>
                                            </div>

                                            <div class="banner-slick-thumb-overlay-icon-main-item">
                                                <span class="banner-slick-thumb-overlay-icon" >
                                                <svg width="20" height="20" viewBox="0 0 20 20" 
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M16.6597 2.2666C16.6571 2.25505 16.6501 2.24593 16.6466 2.23503C16.641 2.21631 16.6335 2.19824 16.6243 2.18099C16.6139 2.16113 16.6014 2.14258 16.5871 2.12533C16.5755 2.111 16.5628 2.09766 16.549 2.08529C16.5322 2.07113 16.5138 2.05892 16.4943 2.04867C16.4779 2.03874 16.4604 2.03027 16.4424 2.02327C16.4207 2.01644 16.3983 2.01188 16.3757 2.0096C16.3618 2.00553 16.3477 2.00228 16.3333 2H14.6527C14.5667 0.839681 14.0283 0 13.3333 0C12.6383 0 12.0999 0.839681 12.014 2H11.3193C11.2334 0.839681 10.695 0 10 0C9.30501 0 8.7666 0.839681 8.68066 2H7.986C7.90007 0.839681 7.36165 0 6.66667 0C5.97168 0 5.43327 0.839681 5.34733 2H4.65267C4.56673 0.839681 4.02832 0 3.33333 0C2.63835 0 2.09994 0.839681 2.014 2H0.333333C0.149251 2 0 2.14925 0 2.33333V19.6667C0 19.8507 0.149251 20 0.333333 20H16.3333C16.5174 20 16.6667 19.8507 16.6667 19.6667V18.6667H19.6667C19.7668 18.6667 19.8615 18.6216 19.9246 18.5441C19.988 18.4665 20.013 18.3647 19.993 18.2666L16.6597 2.2666ZM13.3333 0.666667C13.5741 0.666667 13.91 1.17741 13.984 2H12.6839C12.7567 1.17741 13.0926 0.666667 13.3333 0.666667ZM10 0.666667C10.2407 0.666667 10.5767 1.17741 10.6507 2H9.35059C9.42334 1.17741 9.75928 0.666667 10 0.666667ZM6.66667 0.666667C6.90739 0.666667 7.24333 1.17741 7.31738 2H6.01725C6.09001 1.17741 6.42594 0.666667 6.66667 0.666667ZM3.33333 0.666667C3.57406 0.666667 3.90999 1.17741 3.98405 2H2.68392C2.75667 1.17741 3.09261 0.666667 3.33333 0.666667ZM0.666667 2.66667H2.014C2.09994 3.82699 2.63835 4.66667 3.33333 4.66667C3.51742 4.66667 3.66667 4.51742 3.66667 4.33333C3.66667 4.14925 3.51742 4 3.33333 4C3.09261 4 2.75667 3.48926 2.68262 2.66667H5.34733C5.43441 3.82699 5.97168 4.66667 6.66667 4.66667C6.85075 4.66667 7 4.51742 7 4.33333C7 4.14925 6.85075 4 6.66667 4C6.42594 4 6.09001 3.48926 6.01595 2.66667H8.68066C8.76774 3.82699 9.30501 4.66667 10 4.66667C10.1841 4.66667 10.3333 4.51742 10.3333 4.33333C10.3333 4.14925 10.1841 4 10 4C9.75928 4 9.42334 3.48926 9.34928 2.66667H12.014C12.1011 3.82699 12.6383 4.66667 13.3333 4.66667C13.5174 4.66667 13.6667 4.51742 13.6667 4.33333C13.6667 4.14925 13.5174 4 13.3333 4C13.0926 4 12.7567 3.48926 12.6826 2.66667H16V6H0.666667V2.66667ZM16 19.3333H0.666667V6.66667H16V19.3333ZM16.6667 18V5.56673L19.257 18H16.6667Z" />
                                                                        <path
                                                                            d="M7.33268 11.9999H5.99935C5.81527 11.9999 5.66602 12.1492 5.66602 12.3333V13.6666C5.66602 13.8507 5.81527 13.9999 5.99935 13.9999H7.33268C7.51676 13.9999 7.66602 13.8507 7.66602 13.6666V12.3333C7.66602 12.1492 7.51676 11.9999 7.33268 11.9999ZM6.99935 13.3333H6.33268V12.6666H6.99935V13.3333Z" />
                                                                        <path
                                                                            d="M10.6667 12H9.33333C9.14925 12 9 12.1493 9 12.3333V13.6667C9 13.8507 9.14925 14 9.33333 14H10.6667C10.8507 14 11 13.8507 11 13.6667V12.3333C11 12.1493 10.8507 12 10.6667 12ZM10.3333 13.3333H9.66667V12.6667H10.3333V13.3333Z" />
                                                                        <path
                                                                            d="M4.00065 12H2.66732C2.48324 12 2.33398 12.1493 2.33398 12.3333V13.6667C2.33398 13.8507 2.48324 14 2.66732 14H4.00065C4.18473 14 4.33398 13.8507 4.33398 13.6667V12.3333C4.33398 12.1493 4.18473 12 4.00065 12ZM3.66732 13.3333H3.00065V12.6667H3.66732V13.3333Z" />
                                                                        <path
                                                                            d="M7.33268 15.3334H5.99935C5.81527 15.3334 5.66602 15.4826 5.66602 15.6667V17C5.66602 17.1841 5.81527 17.3334 5.99935 17.3334H7.33268C7.51676 17.3334 7.66602 17.1841 7.66602 17V15.6667C7.66602 15.4826 7.51676 15.3334 7.33268 15.3334ZM6.99935 16.6667H6.33268V16H6.99935V16.6667Z" />
                                                                        <path
                                                                            d="M10.6667 15.3334H9.33333C9.14925 15.3334 9 15.4826 9 15.6667V17C9 17.1841 9.14925 17.3334 9.33333 17.3334H10.6667C10.8507 17.3334 11 17.1841 11 17V15.6667C11 15.4826 10.8507 15.3334 10.6667 15.3334ZM10.3333 16.6667H9.66667V16H10.3333V16.6667Z" />
                                                                        <path
                                                                            d="M4.00065 15.3334H2.66732C2.48324 15.3334 2.33398 15.4826 2.33398 15.6667V17C2.33398 17.1841 2.48324 17.3334 2.66732 17.3334H4.00065C4.18473 17.3334 4.33398 17.1841 4.33398 17V15.6667C4.33398 15.4826 4.18473 15.3334 4.00065 15.3334ZM3.66732 16.6667H3.00065V16H3.66732V16.6667Z" />
                                                                        <path
                                                                            d="M7.33268 8.66669H5.99935C5.81527 8.66669 5.66602 8.81594 5.66602 9.00002V10.3334C5.66602 10.5174 5.81527 10.6667 5.99935 10.6667H7.33268C7.51676 10.6667 7.66602 10.5174 7.66602 10.3334V9.00002C7.66602 8.81594 7.51676 8.66669 7.33268 8.66669ZM6.99935 10H6.33268V9.33335H6.99935V10Z" />
                                                                        <path
                                                                            d="M10.6667 8.66663H9.33333C9.14925 8.66663 9 8.81588 9 8.99996V10.3333C9 10.5174 9.14925 10.6666 9.33333 10.6666H10.6667C10.8507 10.6666 11 10.5174 11 10.3333V8.99996C11 8.81588 10.8507 8.66663 10.6667 8.66663ZM10.3333 9.99996H9.66667V9.33329H10.3333V9.99996Z" />
                                                                        <path
                                                                            d="M14.0007 12H12.6673C12.4832 12 12.334 12.1493 12.334 12.3333V13.6667C12.334 13.8507 12.4832 14 12.6673 14H14.0007C14.1847 14 14.334 13.8507 14.334 13.6667V12.3333C14.334 12.1493 14.1847 12 14.0007 12ZM13.6673 13.3333H13.0007V12.6667H13.6673V13.3333Z" />
                                                                        <path
                                                                            d="M14.0007 15.3334H12.6673C12.4832 15.3334 12.334 15.4826 12.334 15.6667V17C12.334 17.1841 12.4832 17.3334 12.6673 17.3334H14.0007C14.1847 17.3334 14.334 17.1841 14.334 17V15.6667C14.334 15.4826 14.1847 15.3334 14.0007 15.3334ZM13.6673 16.6667H13.0007V16H13.6673V16.6667Z" />
                                                                        <path
                                                                            d="M14.0007 8.66663H12.6673C12.4832 8.66663 12.334 8.81588 12.334 8.99996V10.3333C12.334 10.5174 12.4832 10.6666 12.6673 10.6666H14.0007C14.1847 10.6666 14.334 10.5174 14.334 10.3333V8.99996C14.334 8.81588 14.1847 8.66663 14.0007 8.66663ZM13.6673 9.99996H13.0007V9.33329H13.6673V9.99996Z" />
                                                                        <path
                                                                            d="M4.00065 8.66663H2.66732C2.48324 8.66663 2.33398 8.81588 2.33398 8.99996V10.3333C2.33398 10.5174 2.48324 10.6666 2.66732 10.6666H4.00065C4.18473 10.6666 4.33398 10.5174 4.33398 10.3333V8.99996C4.33398 8.81588 4.18473 8.66663 4.00065 8.66663ZM3.66732 9.99996H3.00065V9.33329H3.66732V9.99996Z" />
                                                                    </svg>
                                                </span>
                                                <h5 class="banner-slick-thumb-overlay-txt">
                                                    @if(session('front_lang')=='en')
                                                        {{ html_decode($car['year_en']) }}
                                                    @else
                                                        {{ html_decode($car['year']) }}
                                                    @endif
                                                </h5>
                                            </div>

                                            <div class="banner-slick-thumb-overlay-icon-main-item">
                                                <span class="banner-slick-thumb-overlay-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M17 11.5H17.5V11V7.5H18.5V12.5H13H12.5V13V16.5H11.5V13V12.5H11H7H6.5V13V16.5H5.5V7.5H6.5V11V11.5H7H11H11.5V11V7.5H12.5V11V11.5H13H17ZM7.5 4.5H4.5V2.5H7.5V4.5ZM7.5 19.5V21.5H4.5V19.5H7.5ZM10.5 4.5V2.5H13.5V4.5H10.5ZM13.5 19.5V21.5H10.5V19.5H13.5ZM19.5 4.5H16.5V2.5H19.5V4.5Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                    </svg>
                                                </span>
                                                <h5 class="banner-slick-thumb-overlay-txt">
                                                    @if(session('front_lang')=='en')
                                                        {{ html_decode(!empty($car['model_details_en']) ? $car['model_details_en'] : '--') }}
                                                            @else
                                                        {{ html_decode(!empty($car['model_details_en']) ? $car['model_details_en'] : '--') }}
                                                    @endif
                                                </h5>
                                            </div>
                                        </div>
                                    </div>      
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
    <!-- banner-part-end -->


    <!-- Categories-part-start -->
    <section class="px-2 categories px-sm-3 px-lg-5">
        <div class="container">
            <div class="pb-3 row align-items-end">
                <div class="col-lg-8 col-sm-8 col-md-12 ">
                    <h2 class="section-heading">{{__('translate.Popular')}} <span class="highlight">{{__('translate.Brands')}}<span></h2>
                </div>

                <!-- <div class="col-lg-4">
                    <div class="categories-three-view-btn">
                        <a href="{{ route('listings') }}" class="thm-btn">{{ __('translate.View All') }}</a>
                    </div>
                </div> -->

                

            </div>

            <div class="pt-4 row g-5">
                @foreach ($brands->take(6) as $index => $brand)
                    <div class="col-xl-2 col-lg-4 col-6 col-md-6" data-aos="fade-right" data-aos-delay="50">
                        <div class="categories-logo">
                            <a href="{{ route('jdm-stock',[$brand->slug, 'car']) }}" class="categories-logo-thumb">
                            <img src="{{ asset('Brand/'.$brand->image) }}" alt="logo">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-5 d-flex align-items-center justify-content-center">
                <div class="categories-three-view-btn">
                    <a href="{{ route('brand-listing') }}" class="thm-btn">SEE ALL</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Categories-part-end -->


    <!--  Brand Car-part-start -->
       @if(count($top_sells) > 0 )
        <section class="px-2 py-5 my-3 brand-car px-sm-3 px-lg-5">
            <div class="container">
                <div class="row align-items-end">
                    <div class="col-lg-6 col-sm-6 col-md-6">
                        <h2 class="section-heading">{{__('translate.Top Selling')}} <span class="highlight">{{__('translate.Cars')}}<span></h2>
                    </div>
                </div>

                <div class="row mt-60px">
                    <div class="col-lg-12">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab">


                                <div class="row g-5">
                                    @foreach ($top_sells as $car)
                                        <div class=" col-xl-3 col-lg-4 col-sm-6 col-md-6" data-aos="fade-u p"
                                            data-aos-delay="50">
                                            <div class="brand-car-item">
                                                <div class="brand-car-item-img">
                                                    <div class="">
                                                        <a href="{{ route('fixed-car-marketplace-details', ['category' => 'top-selling', 'id' => $car['id']]) }}"data-bs-toggle="tooltip">
                                                            <img src="{{asset($car['picture']) }}" alt="thumb" class="card_image">
                                                        </a>
                                                    </div>
                                                </div>

                                                
                                                    <div class="brand-car-inner position-relative">
                                                        <div class="position-absolute heart_absolute parent">
                                                        @if(Auth::guard('web')->check()) 
                                                             @if(in_array($car['id'], $one_price_wishlists))
                                                                <img src="{{ asset('japan_home/heart_bg.svg') }}" alt="close" class="img_heart image_1"/>
                                                                <a href="javascript:void(0);" class="after_auth_wishlist" data-car-id='{{$car['id']}}' data-table-id='1'><img src="{{ asset('japan_home/heart.svg') }}" alt="close" class="img_heart heart-img image_2"/></a>
                                                              @else
                                                              <a href="javascript:void(0);" class="after_auth_wishlist" data-car-id='{{$car['id']}}' data-table-id='1'><img src="{{ asset('japan_home/heart_bg.svg') }}" alt="close" class="img_heart heart-img image_2"/></a>
                                                             @endif
                                                         @else 
                                                        <a href="javascript:void(0);" class="before_auth_wishlist"> <img src="{{ asset('japan_home/heart_bg.svg') }}" alt="close" class="img_heart image_1"/></a>
                                                        @endif          
                                                        </div>
                                                        <div class="brand-car-inner-item">
                                                        <span class="pt-3 text-truncate car-name ps-3" data-bs-toggle="tooltip" 
                                                            title="@if(session('front_lang')=='en')
                                                            {{ $car['company_en'] }}
                                                            @else
                                                            {{ $car['company'] }}
                                                            @endif">
                                                            @if(session('front_lang')=='en')
                                                            {{ $car['company_en'] }}
                                                            @else
                                                            {{ $car['company'] }}
                                                            @endif
                                                        </span>
                                                        <p class="pt-3 listcar_price pe-4">
                                                            @if(session('front_lang')=='en')
                                                            {{ '$' . number_format($car['start_price_num'], 0, '.', ',') }}
                                                            @else
                                                            {{ '$' . number_format($car['start_price'], 0, '.', ',') }}
                                                            @endif
                                                        </p>

                                                    </div>

                                                     <a href="{{ route('fixed-car-marketplace-details', ['category' => 'top-selling', 'id' => $car['id']]) }}"data-bs-toggle="tooltip" 
                                                        title="@if(session('front_lang')=='en')
                                                            {{ html_decode($car['model_name_en']) }}
                                                            @else
                                                                {{ html_decode($car['model_name']) }}
                                                            @endif">
                                                        <h3 class="pt-3 text-truncate car-fullname ps-3"> 
                                                            @if(session('front_lang')=='en')
                                                            {{ html_decode($car['model_name_en']) }}
                                                            @else
                                                                {{ html_decode($car['model_name']) }}
                                                            @endif
                                                        </h3>
                                                    </a>

                                                    <div class="px-4 pt-2 brand-car-inner-item-main">
                                                        <div class="brand-car-inner-item-two">
                                                            <div class="brand-car-inner-item-thumb">
                                                                <span>
                                                                     <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M20 10.2935C20 7.75456 18.9535 5.45057 17.2608 3.77159C17.2476 3.7544 17.2335 3.73758 17.2175 3.72192C17.2015 3.70626 17.1843 3.69249 17.1668 3.67963C15.4505 2.02368 13.0953 1 10.5 1C7.90472 1 5.54953 2.02374 3.83318 3.67963C3.81561 3.69255 3.79848 3.70632 3.78247 3.72192C3.76646 3.73758 3.75238 3.75434 3.73918 3.77159C2.0465 5.45057 1 7.75456 1 10.2935C1 12.7755 1.98794 15.1089 3.78179 16.8642C3.78204 16.8644 3.78229 16.8647 3.78253 16.865C3.78272 16.8651 3.78285 16.8653 3.78303 16.8654C3.78328 16.8656 3.78353 16.8659 3.78378 16.8661C3.87498 16.9553 3.99452 16.9999 4.11407 16.9999C4.23368 16.9999 4.35328 16.9553 4.44448 16.866C4.45227 16.8584 4.45931 16.8503 4.46641 16.8422L5.90617 15.4337C6.08864 15.2552 6.08864 14.9658 5.90617 14.7873C5.72371 14.6089 5.42787 14.6089 5.24547 14.7873L4.12192 15.8864C2.81179 14.4602 2.05173 12.6653 1.9472 10.7505H3.53616C3.79418 10.7505 4.00337 10.546 4.00337 10.2935C4.00337 10.041 3.79418 9.83642 3.53616 9.83642H1.94732C2.05596 7.86974 2.86107 6.08137 4.12497 4.70343L5.24547 5.79958C5.33667 5.88879 5.45628 5.9334 5.57582 5.9334C5.69537 5.9334 5.81497 5.88879 5.90617 5.79958C6.08864 5.62102 6.08864 5.33167 5.90617 5.15318L4.78573 4.05697C6.19435 2.82055 8.0224 2.03295 10.0328 1.92673V3.48108C10.0328 3.73356 10.242 3.93814 10.5 3.93814C10.758 3.93814 10.9672 3.73356 10.9672 3.48108V1.92673C12.9776 2.03295 14.8056 2.82061 16.2143 4.05703L15.0938 5.15318C14.9113 5.33173 14.9113 5.62108 15.0938 5.79958C15.185 5.88879 15.3046 5.9334 15.4241 5.9334C15.5437 5.9334 15.6633 5.88879 15.7545 5.79958L16.875 4.70343C18.1389 6.08143 18.944 7.86974 19.0526 9.83642H17.4637C17.2057 9.83642 16.9965 10.041 16.9965 10.2935C16.9965 10.546 17.2057 10.7505 17.4637 10.7505H19.0527C18.9481 12.6653 18.1881 14.4603 16.878 15.8865L15.7545 14.7873C15.5721 14.6089 15.2762 14.6089 15.0938 14.7873C14.9113 14.9659 14.9113 15.2552 15.0938 15.4337L16.5568 16.8649C16.648 16.9541 16.7676 16.9987 16.8871 16.9987C16.9469 16.9987 17.0067 16.9876 17.0629 16.9653C17.1192 16.943 17.1719 16.9095 17.2175 16.8649C19.0118 15.1096 20 12.7758 20 10.2935Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                        <path d="M12.6465 5.05246C12.4068 4.95855 12.135 5.07238 12.039 5.30676L10.6889 8.60366C10.626 8.59708 10.5631 8.59257 10.5001 8.59257C9.8425 8.59257 9.24852 8.94889 8.94981 9.52246C8.63759 10.1221 8.71758 10.8385 9.16361 11.4387C9.20921 11.5001 9.26652 11.5562 9.32969 11.6012C9.69206 11.8589 10.0968 11.9951 10.5001 11.9951C11.1577 11.9951 11.7517 11.6388 12.0504 11.0652C12.3626 10.4656 12.2826 9.74922 11.8369 9.14938C11.7913 9.08783 11.7338 9.03152 11.6705 8.98643C11.6364 8.96217 11.6016 8.94005 11.5668 8.91799L12.9064 5.64663C13.0024 5.41237 12.886 5.1463 12.6465 5.05246ZM11.2177 10.6502C11.0793 10.9159 10.8043 11.0809 10.5 11.0809C10.3004 11.0809 10.0995 11.0127 9.90268 10.8782C9.67842 10.5631 9.63437 10.2216 9.78245 9.93735C9.92075 9.67171 10.1957 9.50668 10.5001 9.50668C10.5971 9.50668 10.6944 9.52313 10.7915 9.55513C10.7947 9.55641 10.7976 9.55805 10.8008 9.55933C10.8111 9.56329 10.8213 9.56652 10.8316 9.56975C10.9207 9.60321 11.0094 9.64928 11.0974 9.70937C11.3216 10.0244 11.3657 10.3659 11.2177 10.6502Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                     </svg>

                                                                </span>
                                                            </div>

                                                            <span class="truncate-card-text card-text-center" data-toggle="tooltip" data-placement="top" title="@if(session('front_lang')=='en')
                                                                {{ html_decode($car['mileage_en']) . ',000' }}
                                                                @else
                                                                {{ html_decode($car['mileage']) . ',000'}}
                                                                @endif">
                                                                @if(session('front_lang')=='en')
                                                                {{ html_decode($car['mileage_en']) . ',000' }}
                                                                @else
                                                                {{ html_decode($car['mileage']) . ',000'}}
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="brand-car-inner-item-two">
                                                            <div class="brand-car-inner-item-thumb">
                                                                <span class="fs-6">
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill="#0D274E" stroke="#0D274E" stroke-width="0.2" d="M19.5 4H16.5V2.5C16.5 2.36739 16.4473 2.24021 16.3536 2.14645C16.2598 2.05268 16.1326 2 16 2C15.8674 2 15.7402 2.05268 15.6464 2.14645C15.5527 2.24021 15.5 2.36739 15.5 2.5V4H8.5V2.5C8.5 2.36739 8.44732 2.24021 8.35355 2.14645C8.25979 2.05268 8.13261 2 8 2C7.86739 2 7.74021 2.05268 7.64645 2.14645C7.55268 2.24021 7.5 2.36739 7.5 2.5V4H4.5C3.8372 4.00079 3.20178 4.26444 2.73311 4.73311C2.26444 5.20178 2.00079 5.8372 2 6.5V19.5C2.00079 20.1628 2.26444 20.7982 2.73311 21.2669C3.20178 21.7356 3.8372 21.9992 4.5 22H19.5C20.163 22 20.7989 21.7366 21.2678 21.2678C21.7366 20.7989 22 20.163 22 19.5V6.5C22 5.83696 21.7366 5.20107 21.2678 4.73223C20.7989 4.26339 20.163 4 19.5 4ZM21 19.5C21 19.8978 20.842 20.2794 20.5607 20.5607C20.2794 20.842 19.8978 21 19.5 21H4.5C4.10218 21 3.72064 20.842 3.43934 20.5607C3.15804 20.2794 3 19.8978 3 19.5V11H21V19.5ZM21 10H3V6.5C3 5.672 3.67 5 4.5 5H7.5V6.5C7.5 6.63261 7.55268 6.75979 7.64645 6.85355C7.74021 6.94732 7.86739 7 8 7C8.13261 7 8.25979 6.94732 8.35355 6.85355C8.44732 6.75979 8.5 6.63261 8.5 6.5V5H15.5V6.5C15.5 6.63261 15.5527 6.75979 15.6464 6.85355C15.7402 6.94732 15.8674 7 16 7C16.1326 7 16.2598 6.94732 16.3536 6.85355C16.4473 6.75979 16.5 6.63261 16.5 6.5V5H19.5C19.8978 5 20.2794 5.15804 20.5607 5.43934C20.842 5.72064 21 6.10218 21 6.5V10Z" fill="black" stroke="black" stroke-width="0.5"/>
                                                                    </svg>
                                                                </span>
                                                            </div>

                                                            <span class="truncate-card-text card-text-center" data-toggle="tooltip" data-placement="top" title="@if(session('front_lang')=='en')
                                                                {{ html_decode($car['year_en']) }}
                                                                @else
                                                                {{ html_decode($car['year']) }}
                                                                @endif">
                                                            @if(session('front_lang')=='en')
                                                                {{ html_decode($car['year_en']) }}
                                                                @else
                                                                {{ html_decode($car['year']) }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="brand-car-inner-item-two">
                                                            <div class="brand-car-inner-item-thumb">
                                                                <span>
                                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M17 11.5H17.5V11V7.5H18.5V12.5H13H12.5V13V16.5H11.5V13V12.5H11H7H6.5V13V16.5H5.5V7.5H6.5V11V11.5H7H11H11.5V11V7.5H12.5V11V11.5H13H17ZM7.5 4.5H4.5V2.5H7.5V4.5ZM7.5 19.5V21.5H4.5V19.5H7.5ZM10.5 4.5V2.5H13.5V4.5H10.5ZM13.5 19.5V21.5H10.5V19.5H13.5ZM19.5 4.5H16.5V2.5H19.5V4.5Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                </svg>

                                                                </span>
                                                            </div>

                                                            <span class="truncate-card-text card-text-center" data-toggle="tooltip" data-placement="top" title="@if(session('front_lang')=='en')
                                                                {{ html_decode(!empty($car['model_details_en']) ? $car['model_details_en'] : '--') }}
                                                                @else
                                                                {{ html_decode(!empty($car['model_details_en']) ? $car['model_details_en'] : '--') }}
                                                                @endif">
                                                            @if(session('front_lang')=='en')
                                                                {{ html_decode(!empty($car['model_details_en']) ? $car['model_details_en'] : '--') }}
                                                                @else
                                                                {{ html_decode(!empty($car['model_details_en']) ? $car['model_details_en'] : '--') }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="px-3 py-2 brand-car-btm-txt-btm">
                                                        @php
                                                         $parsed_data=parseCustomFormat($car['parsed_data']);
                                                         $carbonInstance = Carbon::parse($car['datetime']);
                                                        @endphp
                                                        <p>
                                                            <i class="bi bi-geo-alt-fill fs-6"></i>
                                                            <span class="brand-location">
                                                            {{ isset($parsed_data['vehicle  location']) ? trim($parsed_data['vehicle  location']) : '--' }}
                                                            </span>
                                                        </p>
                                                        <div class="d-flex flex-column">
                                                            <span class="brand-date fw-light">{{ $carbonInstance->format('Y-m-d') }}</span>
                                                            <span class="brand-date fw-light">{{ $carbonInstance->format('H:i:s') }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                     
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                aria-labelledby="pills-profile-tab">


                                <div class="row g-5">
                                    @foreach ($used_cars as $car)
                                    <div class="col-xl-3 col-lg-4 col-sm-6 col-md-6">
                                        <div class="brand-car-item">
                                            <div class="brand-car-item-img">
                                                <a href="{{ route('fixed-car-marketplace-details',  ['category' => 'top-selling', 'id' => $car['id']]) }}"data-bs-toggle="tooltip">
                                                    <img src="{{ asset($car->thumb_image) }}" alt="thumb">
                                                </a>    
                                                <div class="brand-car-item-img-text">

                                                    <div class="text-df">
                                                        @if ($car->offer_price)
                                                            <p class="text">{{ calculate_percentage($car->regular_price, $car->offer_price) }}% {{ __('translate.Off') }}</p>
                                                        @endif

                                                        @if ($car->condition == 'New')
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
                                                                    d="M9.61204 2.324L9 2.96329L8.38796  2.324C6.69786 0.558667 3.95767 0.558666 2.26757 2.324C0.577476 4.08933 0.577475 6.95151 2.26757 8.71684L7.77592 14.4704C8.45196 15.1765 9.54804 15.1765 10.2241 14.4704L15.7324 8.71684C17.4225 6.95151 17.4225 4.08934 15.7324 2.324C14.0423 0.558667 11.3021 0.558666 9.61204 2.324Z"
                                                                    stroke-width="1.3" stroke-linejoin="round"></path>
                                                            </svg>

                                                                </span>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('user.add-to-wishlist', $car->id) }}" class="icon">
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


                                                        <a href="{{ route('add-to-compare', $car->id) }}" class="icon">
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

                                            <div class="brand-car-inner position-relative">
                                                <div class="position-absolute heart_absolute parent">
                                                      
                                                </div>
                                                <div class="brand-car-inner-item">
                                                    <span>{{ $car?->brand?->name }}</span>
                                                    <p>
                                                        @if ($car->offer_price)
                                                            {{ currency($car->offer_price) }}
                                                        @else
                                                            {{ currency($car->regular_price) }}
                                                        @endif
                                                    </p>
                                                </div>

                                                <a href="{{ route('listing', $car->slug) }}">
                                                    <h3 class="px-2">{{ html_decode($car->title) }}</h3>
                                                </a>

                                                <div class="px-2 brand-car-inner-item-main">
                                                    <div class="brand-car-inner-item-two">
                                                        <div class="brand-car-inner-item-thumb">
                                                            <span>
                                                                <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M20 10.2935C20 7.75456 18.9535 5.45057 17.2608 3.77159C17.2476 3.7544 17.2335 3.73758 17.2175 3.72192C17.2015 3.70626 17.1843 3.69249 17.1668 3.67963C15.4505 2.02368 13.0953 1 10.5 1C7.90472 1 5.54953 2.02374 3.83318 3.67963C3.81561 3.69255 3.79848 3.70632 3.78247 3.72192C3.76646 3.73758 3.75238 3.75434 3.73918 3.77159C2.0465 5.45057 1 7.75456 1 10.2935C1 12.7755 1.98794 15.1089 3.78179 16.8642C3.78204 16.8644 3.78229 16.8647 3.78253 16.865C3.78272 16.8651 3.78285 16.8653 3.78303 16.8654C3.78328 16.8656 3.78353 16.8659 3.78378 16.8661C3.87498 16.9553 3.99452 16.9999 4.11407 16.9999C4.23368 16.9999 4.35328 16.9553 4.44448 16.866C4.45227 16.8584 4.45931 16.8503 4.46641 16.8422L5.90617 15.4337C6.08864 15.2552 6.08864 14.9658 5.90617 14.7873C5.72371 14.6089 5.42787 14.6089 5.24547 14.7873L4.12192 15.8864C2.81179 14.4602 2.05173 12.6653 1.9472 10.7505H3.53616C3.79418 10.7505 4.00337 10.546 4.00337 10.2935C4.00337 10.041 3.79418 9.83642 3.53616 9.83642H1.94732C2.05596 7.86974 2.86107 6.08137 4.12497 4.70343L5.24547 5.79958C5.33667 5.88879 5.45628 5.9334 5.57582 5.9334C5.69537 5.9334 5.81497 5.88879 5.90617 5.79958C6.08864 5.62102 6.08864 5.33167 5.90617 5.15318L4.78573 4.05697C6.19435 2.82055 8.0224 2.03295 10.0328 1.92673V3.48108C10.0328 3.73356 10.242 3.93814 10.5 3.93814C10.758 3.93814 10.9672 3.73356 10.9672 3.48108V1.92673C12.9776 2.03295 14.8056 2.82061 16.2143 4.05703L15.0938 5.15318C14.9113 5.33173 14.9113 5.62108 15.0938 5.79958C15.185 5.88879 15.3046 5.9334 15.4241 5.9334C15.5437 5.9334 15.6633 5.88879 15.7545 5.79958L16.875 4.70343C18.1389 6.08143 18.944 7.86974 19.0526 9.83642H17.4637C17.2057 9.83642 16.9965 10.041 16.9965 10.2935C16.9965 10.546 17.2057 10.7505 17.4637 10.7505H19.0527C18.9481 12.6653 18.1881 14.4603 16.878 15.8865L15.7545 14.7873C15.5721 14.6089 15.2762 14.6089 15.0938 14.7873C14.9113 14.9659 14.9113 15.2552 15.0938 15.4337L16.5568 16.8649C16.648 16.9541 16.7676 16.9987 16.8871 16.9987C16.9469 16.9987 17.0067 16.9876 17.0629 16.9653C17.1192 16.943 17.1719 16.9095 17.2175 16.8649C19.0118 15.1096 20 12.7758 20 10.2935Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                    <path d="M12.6465 5.05246C12.4068 4.95855 12.135 5.07238 12.039 5.30676L10.6889 8.60366C10.626 8.59708 10.5631 8.59257 10.5001 8.59257C9.8425 8.59257 9.24852 8.94889 8.94981 9.52246C8.63759 10.1221 8.71758 10.8385 9.16361 11.4387C9.20921 11.5001 9.26652 11.5562 9.32969 11.6012C9.69206 11.8589 10.0968 11.9951 10.5001 11.9951C11.1577 11.9951 11.7517 11.6388 12.0504 11.0652C12.3626 10.4656 12.2826 9.74922 11.8369 9.14938C11.7913 9.08783 11.7338 9.03152 11.6705 8.98643C11.6364 8.96217 11.6016 8.94005 11.5668 8.91799L12.9064 5.64663C13.0024 5.41237 12.886 5.1463 12.6465 5.05246ZM11.2177 10.6502C11.0793 10.9159 10.8043 11.0809 10.5 11.0809C10.3004 11.0809 10.0995 11.0127 9.90268 10.8782C9.67842 10.5631 9.63437 10.2216 9.78245 9.93735C9.92075 9.67171 10.1957 9.50668 10.5001 9.50668C10.5971 9.50668 10.6944 9.52313 10.7915 9.55513C10.7947 9.55641 10.7976 9.55805 10.8008 9.55933C10.8111 9.56329 10.8213 9.56652 10.8316 9.56975C10.9207 9.60321 11.0094 9.64928 11.0974 9.70937C11.3216 10.0244 11.3657 10.3659 11.2177 10.6502Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                    </svg>

                                                            </span>
                                                        </div>

                                                        <span class="truncate-card-text card-text-center" data-toggle="tooltip" data-placement="top" title="{{ html_decode($car->mileage) }}">
                                                            {{ html_decode($car->mileage) }}
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

                                                        <span class="truncate-card-text card-text-center" data-toggle="tooltip" data-placement="top" title="{{ html_decode($car->fuel_type) }}">
                                                            {{ html_decode($car->fuel_type) }}
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

                                                        <span class="truncate-card-text card-text-center" data-toggle="tooltip" data-placement="top" title="{{ html_decode($car->engine_size) }}">
                                                            {{ html_decode($car->engine_size) }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="px-2 pb-3 brand-car-btm-txt-btm">
                                                    <h6 class="brand-car-btm-txt"><span>{{ __('translate.Listed by') }} :</span>{{ html_decode($car?->dealer?->name) }}
                                                    </h6>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="pt-5 d-flex align-items-center justify-content-center">
                        <div class="categories-three-view-btn">
                            <a href="{{ route('top-selling') }}" class="thm-btn">SEE ALL</a>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </section>
       @endif 
    <!--  Brand Car-part-end -->



<!-- JDM Car listing-->
    <section class="px-2 py-5 my-3 brand-car px-sm-3 px-lg-5">
        <div class="container">
            <!-- <div class="row">
                <div class="col-lg-12">
                    <div class="brand-car-position-img">

                    </div>
                </div>
            </div> -->
            <div class="row align-items-end">
                <div class="col-lg-6 col-sm-6 col-md-6">
                    <div class="taitel">
                        <div class="taitel-img">
                            <span>
                                <!-- <svg width="188" height="6" viewBox="0 0 188 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 5C26.4245 1.98151 99.2187 -2.24439 187 5" stroke="#46D993" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg> -->

                            </span>
                        </div>
                        <!-- <span>{{ __('translate.verified Available Brand Car') }}</span> -->
                    </div>

                    <div class="row align-items-end">
                        <div class="col-lg-6 col-sm-6 col-md-6">
                            <h2 class="section-heading text-nowrap">{{__('translate.JDM')}} <span class="highlight">{{__('translate.Cars')}}<span></h2>
                        </div>
                    </div>        
                </div>

               
            </div>

            <div class="row mt-60px">
                <div class="col-lg-12">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab">


                            <div class="row g-5">
                                @foreach ($jdm_car_listings as $car)
                                    <div class=" col-xl-3 col-lg-4 col-sm-6 col-md-6"  data-aos="fade-up"
                                        data-aos-delay="50">
                                        <div class="brand-car-item">
                                            <div class="brand-car-item-img">
                                                <div class="">
                                                    <a href="{{ route('jdm-stock-listing', [$car->id, 'car']) }}"data-bs-toggle="tooltip">
                                                        <img src="{{ file_exists(public_path('Cars/' . $car->image)) ? 
                                                                    asset('Cars/' . $car->image) : 
                                                                    asset('uploads/website-images/no-image.jpg') }}" 
                                                            alt="thumb" class="card_image">
                                                    </a>        
                                                </div>
                                                

                                                <div class="brand-car-item-img-text justify-content-end">
                                                    <div class="icon-main">
                                                        @guest('web')
                                                        @else
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="brand-car-inner position-relative">
                                                    <div class="position-absolute heart_absolute parent">
                                                    @if(Auth::guard('web')->check()) 
                                                             @if(in_array($car->id, $jdm_wishlists))
                                                                <img src="{{ asset('japan_home/heart_bg.svg') }}" alt="close" class="img_heart image_1"/>
                                                                <a href="javascript:void(0);" class="after_auth_wishlist" data-car-id='{{$car->id}}' data-table-id='3'><img src="{{ asset('japan_home/heart.svg') }}" alt="close" class="img_heart heart-img image_2"/></a>
                                                              @else
                                                              <a href="javascript:void(0);" class="after_auth_wishlist" data-car-id='{{$car->id}}' data-table-id='3'><img src="{{ asset('japan_home/heart_bg.svg') }}" alt="close" class="img_heart heart-img image_2"/></a>
                                                             @endif
                                                         @else 
                                                        <a href="javascript:void(0);" class="before_auth_wishlist"> <img src="{{ asset('japan_home/heart_bg.svg') }}" alt="close" class="img_heart image_1"/></a>
                                                        @endif           
                                                    </div>
                                                <div class="brand-car-inner-item">
                                                    <span class="pt-3 text-truncate car-name ps-3" data-bs-toggle="tooltip" title="@if(session('front_lang')=='en')
                                                                {{ $car->make }}
                                                            @else
                                                            {{ $car->make }}
                                                            @endif">
                                    
                                                            @if(session('front_lang')=='en')
                                                                {{ $car->make }}
                                                            @else
                                                            {{ $car->make }}
                                                            @endif
                                                        
                                                    </span>

                    
                                                    <p class="pt-3 listcar_price pe-4">
                                                        @if(session('front_lang')=='en')
                                                            {{ '$'.$car->price }}
                                                        @else
                                                            {{ '$'.$car->price }}
                                                        @endif
                                                    </p>
                                                </div>

                                            

                                                <a href="{{ route('jdm-stock-listing', [$car->id, 'car']) }}"data-bs-toggle="tooltip" title=" @if(session('front_lang')=='en')
                                                            {{!empty($car->model) ? $car->model  : '--'}}
                                                        @else
                                                            {{!empty($car->model) ?  $car->model : '--'}}
                                                        @endif">
                                                    <h3 class="pt-3 text-truncate car-fullname ps-3"> 
                                                        @if(session('front_lang')=='en')
                                                            {{!empty($car->model) ? $car->model  : '--'}}
                                                        @else
                                                            {{!empty($car->model) ?  $car->model : '--'}}
                                                        @endif     
                                                        <!-- <div class="py-1">
                                                            &nbsp;&nbsp;&nbsp;
                                                        </div> -->
                                                        
                                                    </h3>
                                                </a>


                                                <div class="px-4 pt-2 brand-car-inner-item-main">
                                                    <div class="brand-car-inner-item-two">
                                                        <div class="brand-car-inner-item-thumb">
                                                            <span>
                                                                <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M20 10.2935C20 7.75456 18.9535 5.45057 17.2608 3.77159C17.2476 3.7544 17.2335 3.73758 17.2175 3.72192C17.2015 3.70626 17.1843 3.69249 17.1668 3.67963C15.4505 2.02368 13.0953 1 10.5 1C7.90472 1 5.54953 2.02374 3.83318 3.67963C3.81561 3.69255 3.79848 3.70632 3.78247 3.72192C3.76646 3.73758 3.75238 3.75434 3.73918 3.77159C2.0465 5.45057 1 7.75456 1 10.2935C1 12.7755 1.98794 15.1089 3.78179 16.8642C3.78204 16.8644 3.78229 16.8647 3.78253 16.865C3.78272 16.8651 3.78285 16.8653 3.78303 16.8654C3.78328 16.8656 3.78353 16.8659 3.78378 16.8661C3.87498 16.9553 3.99452 16.9999 4.11407 16.9999C4.23368 16.9999 4.35328 16.9553 4.44448 16.866C4.45227 16.8584 4.45931 16.8503 4.46641 16.8422L5.90617 15.4337C6.08864 15.2552 6.08864 14.9658 5.90617 14.7873C5.72371 14.6089 5.42787 14.6089 5.24547 14.7873L4.12192 15.8864C2.81179 14.4602 2.05173 12.6653 1.9472 10.7505H3.53616C3.79418 10.7505 4.00337 10.546 4.00337 10.2935C4.00337 10.041 3.79418 9.83642 3.53616 9.83642H1.94732C2.05596 7.86974 2.86107 6.08137 4.12497 4.70343L5.24547 5.79958C5.33667 5.88879 5.45628 5.9334 5.57582 5.9334C5.69537 5.9334 5.81497 5.88879 5.90617 5.79958C6.08864 5.62102 6.08864 5.33167 5.90617 5.15318L4.78573 4.05697C6.19435 2.82055 8.0224 2.03295 10.0328 1.92673V3.48108C10.0328 3.73356 10.242 3.93814 10.5 3.93814C10.758 3.93814 10.9672 3.73356 10.9672 3.48108V1.92673C12.9776 2.03295 14.8056 2.82061 16.2143 4.05703L15.0938 5.15318C14.9113 5.33173 14.9113 5.62108 15.0938 5.79958C15.185 5.88879 15.3046 5.9334 15.4241 5.9334C15.5437 5.9334 15.6633 5.88879 15.7545 5.79958L16.875 4.70343C18.1389 6.08143 18.944 7.86974 19.0526 9.83642H17.4637C17.2057 9.83642 16.9965 10.041 16.9965 10.2935C16.9965 10.546 17.2057 10.7505 17.4637 10.7505H19.0527C18.9481 12.6653 18.1881 14.4603 16.878 15.8865L15.7545 14.7873C15.5721 14.6089 15.2762 14.6089 15.0938 14.7873C14.9113 14.9659 14.9113 15.2552 15.0938 15.4337L16.5568 16.8649C16.648 16.9541 16.7676 16.9987 16.8871 16.9987C16.9469 16.9987 17.0067 16.9876 17.0629 16.9653C17.1192 16.943 17.1719 16.9095 17.2175 16.8649C19.0118 15.1096 20 12.7758 20 10.2935Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                    <path d="M12.6465 5.05246C12.4068 4.95855 12.135 5.07238 12.039 5.30676L10.6889 8.60366C10.626 8.59708 10.5631 8.59257 10.5001 8.59257C9.8425 8.59257 9.24852 8.94889 8.94981 9.52246C8.63759 10.1221 8.71758 10.8385 9.16361 11.4387C9.20921 11.5001 9.26652 11.5562 9.32969 11.6012C9.69206 11.8589 10.0968 11.9951 10.5001 11.9951C11.1577 11.9951 11.7517 11.6388 12.0504 11.0652C12.3626 10.4656 12.2826 9.74922 11.8369 9.14938C11.7913 9.08783 11.7338 9.03152 11.6705 8.98643C11.6364 8.96217 11.6016 8.94005 11.5668 8.91799L12.9064 5.64663C13.0024 5.41237 12.886 5.1463 12.6465 5.05246ZM11.2177 10.6502C11.0793 10.9159 10.8043 11.0809 10.5 11.0809C10.3004 11.0809 10.0995 11.0127 9.90268 10.8782C9.67842 10.5631 9.63437 10.2216 9.78245 9.93735C9.92075 9.67171 10.1957 9.50668 10.5001 9.50668C10.5971 9.50668 10.6944 9.52313 10.7915 9.55513C10.7947 9.55641 10.7976 9.55805 10.8008 9.55933C10.8111 9.56329 10.8213 9.56652 10.8316 9.56975C10.9207 9.60321 11.0094 9.64928 11.0974 9.70937C11.3216 10.0244 11.3657 10.3659 11.2177 10.6502Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                                    </svg>

                                                            </span>
                                                        </div>
                                                        <span class="truncate-card-text card-text-center" data-toggle="tooltip" data-placement="top" title="Tooltip on top">
                                                            --
                                                        </span>
                                                    </div>
                                                    <div class="brand-car-inner-item-two">
                                                        <div class="brand-car-inner-item-thumb">
                                                            <span>
                                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill="#0D274E" stroke="#0D274E" stroke-width="0.2" d="M19.5 4H16.5V2.5C16.5 2.36739 16.4473 2.24021 16.3536 2.14645C16.2598 2.05268 16.1326 2 16 2C15.8674 2 15.7402 2.05268 15.6464 2.14645C15.5527 2.24021 15.5 2.36739 15.5 2.5V4H8.5V2.5C8.5 2.36739 8.44732 2.24021 8.35355 2.14645C8.25979 2.05268 8.13261 2 8 2C7.86739 2 7.74021 2.05268 7.64645 2.14645C7.55268 2.24021 7.5 2.36739 7.5 2.5V4H4.5C3.8372 4.00079 3.20178 4.26444 2.73311 4.73311C2.26444 5.20178 2.00079 5.8372 2 6.5V19.5C2.00079 20.1628 2.26444 20.7982 2.73311 21.2669C3.20178 21.7356 3.8372 21.9992 4.5 22H19.5C20.163 22 20.7989 21.7366 21.2678 21.2678C21.7366 20.7989 22 20.163 22 19.5V6.5C22 5.83696 21.7366 5.20107 21.2678 4.73223C20.7989 4.26339 20.163 4 19.5 4ZM21 19.5C21 19.8978 20.842 20.2794 20.5607 20.5607C20.2794 20.842 19.8978 21 19.5 21H4.5C4.10218 21 3.72064 20.842 3.43934 20.5607C3.15804 20.2794 3 19.8978 3 19.5V11H21V19.5ZM21 10H3V6.5C3 5.672 3.67 5 4.5 5H7.5V6.5C7.5 6.63261 7.55268 6.75979 7.64645 6.85355C7.74021 6.94732 7.86739 7 8 7C8.13261 7 8.25979 6.94732 8.35355 6.85355C8.44732 6.75979 8.5 6.63261 8.5 6.5V5H15.5V6.5C15.5 6.63261 15.5527 6.75979 15.6464 6.85355C15.7402 6.94732 15.8674 7 16 7C16.1326 7 16.2598 6.94732 16.3536 6.85355C16.4473 6.75979 16.5 6.63261 16.5 6.5V5H19.5C19.8978 5 20.2794 5.15804 20.5607 5.43934C20.842 5.72064 21 6.10218 21 6.5V10Z" fill="black" stroke="black" stroke-width="0.5"/>
                                                                </svg> 
                                                            </span>
                                                        </div>
                                                        <span class="truncate-card-text card-text-center" data-toggle="tooltip" data-placement="top" title="{{$car->yom}}">
                                                            {{$car->yom}}
                                                        </span>
                                                    </div>
                                                    <div class="brand-car-inner-item-two">
                                                        <div class="brand-car-inner-item-thumb">
                                                            <span>
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M17 11.5H17.5V11V7.5H18.5V12.5H13H12.5V13V16.5H11.5V13V12.5H11H7H6.5V13V16.5H5.5V7.5H6.5V11V11.5H7H11H11.5V11V7.5H12.5V11V11.5H13H17ZM7.5 4.5H4.5V2.5H7.5V4.5ZM7.5 19.5V21.5H4.5V19.5H7.5ZM10.5 4.5V2.5H13.5V4.5H10.5ZM13.5 19.5V21.5H10.5V19.5H13.5ZM19.5 4.5H16.5V2.5H19.5V4.5Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                            </svg>
                                                            </span>
                                                        </div>  
                                                        <span class="truncate-card-text card-text-center" data-toggle="tooltip" data-placement="top" title="{{$car->transmission}}">
                                                            {{$car->transmission}}
                                                        </span>
                                                    </div>
                                                </div>

                                                    <div class="px-3 py-2 brand-car-btm-txt-btm">
                                                        @php
                                                        $carbonInstance = Carbon::parse($car->created_at);
                                                        @endphp

                                                        <p>
                                                            <i class="bi bi-geo-alt-fill fs-6"></i>
                                                            <span class="brand-location">{{$car->location}}</span>
                                                        </p>
                                                        <div class="d-flex flex-column">
                                                            <span class="brand-date fw-light">{{ $carbonInstance->format('Y-m-d') }}</span>
                                                            <span class="brand-date fw-light">{{ $carbonInstance->format('H:i:s') }}</span>
                                                        </div>
                                                    </div>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                            aria-labelledby="pills-profile-tab">

                        </div>
                    </div>

                    {{--<div class="pt-5 d-flex align-items-center justify-content-center">
                        <div class="categories-three-view-btn">
                            <a href="{{ route('listings') }}" class="thm-btn">SEE ALL</a>
                        </div>
                    </div> --}}
                </div>
            </div>    
        </div>
    </section>
<!-- End of JDM car listing -->


    <!--  Car-Poster-part-start -->
    <!-- <section class="flex-wrap gap-2 px-2 mb-5 car-poster d-flex justify-content-center px-sm-3 px-lg-5"> -->
    <section class="flex-wrap gap-2 mb-5 car-poster d-flex justify-content-center px-sm-2 px-md-5">
        <div class="col-md-auto">
            <a href="#">
                <img src="{{ asset('japan_home/Poster4.svg') }}" class="img-fluid poster-img" alt="Poster 1"/>
            </a>
        </div>
        <div class="col-md-auto">
            <a href="#">
                <img src="{{ asset('japan_home/Poster2.svg') }}" class="img-fluid poster-img" alt="Poster 2"/>
            </a>
        </div>
        <div class="col-md-auto">
            <a href="#">
                <img src="{{ asset('japan_home/Poster3.svg') }}" class="img-fluid poster-img" alt="Poster 3"/>
            </a>
        </div>
        <div class="col-md-auto">
            <a href="#">
                <img src="{{ asset('japan_home/Poster4.svg') }}" class="img-fluid poster-img" alt="Poster 4"/>
            </a>
        </div>
    </section>

    <!-- <section class="gap-2 px-5 mb-5 car-poster d-flex justify-content-center">
        <div class="col-12 col-sm-6 col-md-3">
            <a href="#">
            <img src="{{ asset('frontend/japan_home/Poster4.svg') }}" class="img-fluid poster-img" alt="Poster 1"/>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="#">
            <img src="{{ asset('frontend/japan_home/Poster4.svg') }}" class="img-fluid poster-img" alt="Poster 2"/>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="#">
            <img src="{{ asset('frontend/japan_home/Poster4.svg') }}" class="img-fluid poster-img" alt="Poster 3"/>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="#">
            <img src="{{ asset('frontend/japan_home/Poster4.svg') }}" class="img-fluid poster-img" alt="Poster 4"/>
            </a>
        </div>
    </section> -->

    <!--  Car-Poster-part-end -->


    <!--  Feature-part-start -->
      @if(count($new_arrived_cars) > 0)
        <section class="px-2 py-5 my-5 feature px-sm-3 px-lg-5">
        <div class="container ">
            <div class="row">
                <div class="pt-2 col-lg-9 New_arrival">
                    <div class="row feature-taitel align-items-end align-items-baseline">
                        <div class="col-lg-8 col-sm-6 col-md-6">
                            <h2 class="section-heading">{{__('translate.New')}} <span class="highlight">{{__('translate.Arrivals')}}</span></h2>
                        </div>

                        <div class="col-lg-4 col-sm-6 col-md-6">
                            <div class="feature-slick-icon">
                                <div class="feature-slick-prev">
                                    <span>
                                        <svg width="23" height="16" viewBox="0 0 23 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8 1L1 8M1 8L8 15M1 8L22 8" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="feature-slick-next">
                                    <span>
                                        <svg width="23" height="16" viewBox="0 0 23 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15 15L22 8M22 8L15 0.999999M22 8L1 8" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row mt-56px feature-slick">
                        @foreach ($new_arrived_cars as $index => $car)
                            <div class="col-lg-4">
                                <div class="brand-car-item">
                                    <div class="brand-car-item-img">
                                     <div class="">
                                        <a href="{{ route('fixed-car-marketplace-details', ['category' => 'new-arrivals', 'id' => $car['id']]) }}"data-bs-toggle="tooltip">
                                            <img src="{{ asset($car['picture']) }}" alt="thumb" class="card_image">
                                        </a>
                                    </div>

                                    </div>

                                    <div class="brand-car-inner position-relative">
                                        <div class="position-absolute heart_absolute parent">
                                            @if(Auth::guard('web')->check()) 
                                                @if(in_array($car['id'], $one_price_wishlists))
                                                    <img src="{{ asset('japan_home/heart_bg.svg') }}" alt="close" class="img_heart image_1"/>
                                                    <a href="javascript:void(0);" class="after_auth_wishlist" data-car-id='{{$car['id']}}' data-table-id='1'>
                                                        <img src="{{ asset('japan_home/heart.svg') }}" alt="close" class="img_heart heart-img image_2"/></a>
                                                @else
                                                <a href="javascript:void(0);" class="after_auth_wishlist" data-car-id='{{$car['id']}}' data-table-id='1'><img src="{{ asset('japan_home/heart_bg.svg') }}" alt="close" class="img_heart heart-img image_2"/></a>
                                                @endif
                                            @else 
                                            <a href="javascript:void(0);" class="before_auth_wishlist"> <img src="{{ asset('japan_home/heart_bg.svg') }}" alt="close" class="img_heart image_1"/></a>
                                            @endif             
                                        </div>
                                        <div class="brand-car-inner-item">
                                             <span class="pt-3 text-truncate car-name ps-3" data-bs-toggle="tooltip" title="@if(session('front_lang')=='en')
                                                {{ $car['company_en'] }}
                                            @else
                                                {{ $car['company'] }}
                                            @endif">
                                            @if(session('front_lang')=='en')
                                                {{ $car['company_en'] }}
                                            @else
                                                {{ $car['company'] }}
                                            @endif
                                            </span>
                                            <p class="pt-3 listcar_price pe-4">
                                                @if(session('front_lang')=='en')
                                                {{ '$' . number_format($car['start_price_num'], 0, '.', ',') }}
                                                @else
                                                {{ '$' . number_format($car['start_price'], 0, '.', ',') }}
                                                @endif
                                            </p>
                                        </div>

                                        <a href="{{ route('fixed-car-marketplace-details', ['category' => 'new-arrivals', 'id' => $car['id']]) }}"data-bs-toggle="tooltip" title="@if(session('front_lang')=='en')
                                                    {{ html_decode(!empty($car['model_name_en']) ? $car['model_name_en'] : '') }}
                                                @else
                                                    {{ html_decode(!empty($car['model_name']) ? $car['model_name'] : '') }}
                                                @endif">
                                            <h3 class="pt-3 text-truncate car-fullname ps-3"> 
                                                @if(session('front_lang')=='en')
                                                    {{ html_decode(!empty($car['model_name_en']) ? $car['model_name_en'] : '') }}
                                                @else
                                                    {{ html_decode(!empty($car['model_name']) ? $car['model_name'] : '') }}
                                                @endif
                                            </h3>
                                        </a>
                                        

                                        <div class="px-4 pt-2 brand-car-inner-item-main">
                                            <div class="brand-car-inner-item-two">
                                                <div class="brand-car-inner-item-thumb">
                                                    <span>
                                                        <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M20 10.2935C20 7.75456 18.9535 5.45057 17.2608 3.77159C17.2476 3.7544 17.2335 3.73758 17.2175 3.72192C17.2015 3.70626 17.1843 3.69249 17.1668 3.67963C15.4505 2.02368 13.0953 1 10.5 1C7.90472 1 5.54953 2.02374 3.83318 3.67963C3.81561 3.69255 3.79848 3.70632 3.78247 3.72192C3.76646 3.73758 3.75238 3.75434 3.73918 3.77159C2.0465 5.45057 1 7.75456 1 10.2935C1 12.7755 1.98794 15.1089 3.78179 16.8642C3.78204 16.8644 3.78229 16.8647 3.78253 16.865C3.78272 16.8651 3.78285 16.8653 3.78303 16.8654C3.78328 16.8656 3.78353 16.8659 3.78378 16.8661C3.87498 16.9553 3.99452 16.9999 4.11407 16.9999C4.23368 16.9999 4.35328 16.9553 4.44448 16.866C4.45227 16.8584 4.45931 16.8503 4.46641 16.8422L5.90617 15.4337C6.08864 15.2552 6.08864 14.9658 5.90617 14.7873C5.72371 14.6089 5.42787 14.6089 5.24547 14.7873L4.12192 15.8864C2.81179 14.4602 2.05173 12.6653 1.9472 10.7505H3.53616C3.79418 10.7505 4.00337 10.546 4.00337 10.2935C4.00337 10.041 3.79418 9.83642 3.53616 9.83642H1.94732C2.05596 7.86974 2.86107 6.08137 4.12497 4.70343L5.24547 5.79958C5.33667 5.88879 5.45628 5.9334 5.57582 5.9334C5.69537 5.9334 5.81497 5.88879 5.90617 5.79958C6.08864 5.62102 6.08864 5.33167 5.90617 5.15318L4.78573 4.05697C6.19435 2.82055 8.0224 2.03295 10.0328 1.92673V3.48108C10.0328 3.73356 10.242 3.93814 10.5 3.93814C10.758 3.93814 10.9672 3.73356 10.9672 3.48108V1.92673C12.9776 2.03295 14.8056 2.82061 16.2143 4.05703L15.0938 5.15318C14.9113 5.33173 14.9113 5.62108 15.0938 5.79958C15.185 5.88879 15.3046 5.9334 15.4241 5.9334C15.5437 5.9334 15.6633 5.88879 15.7545 5.79958L16.875 4.70343C18.1389 6.08143 18.944 7.86974 19.0526 9.83642H17.4637C17.2057 9.83642 16.9965 10.041 16.9965 10.2935C16.9965 10.546 17.2057 10.7505 17.4637 10.7505H19.0527C18.9481 12.6653 18.1881 14.4603 16.878 15.8865L15.7545 14.7873C15.5721 14.6089 15.2762 14.6089 15.0938 14.7873C14.9113 14.9659 14.9113 15.2552 15.0938 15.4337L16.5568 16.8649C16.648 16.9541 16.7676 16.9987 16.8871 16.9987C16.9469 16.9987 17.0067 16.9876 17.0629 16.9653C17.1192 16.943 17.1719 16.9095 17.2175 16.8649C19.0118 15.1096 20 12.7758 20 10.2935Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                            <path d="M12.6465 5.05246C12.4068 4.95855 12.135 5.07238 12.039 5.30676L10.6889 8.60366C10.626 8.59708 10.5631 8.59257 10.5001 8.59257C9.8425 8.59257 9.24852 8.94889 8.94981 9.52246C8.63759 10.1221 8.71758 10.8385 9.16361 11.4387C9.20921 11.5001 9.26652 11.5562 9.32969 11.6012C9.69206 11.8589 10.0968 11.9951 10.5001 11.9951C11.1577 11.9951 11.7517 11.6388 12.0504 11.0652C12.3626 10.4656 12.2826 9.74922 11.8369 9.14938C11.7913 9.08783 11.7338 9.03152 11.6705 8.98643C11.6364 8.96217 11.6016 8.94005 11.5668 8.91799L12.9064 5.64663C13.0024 5.41237 12.886 5.1463 12.6465 5.05246ZM11.2177 10.6502C11.0793 10.9159 10.8043 11.0809 10.5 11.0809C10.3004 11.0809 10.0995 11.0127 9.90268 10.8782C9.67842 10.5631 9.63437 10.2216 9.78245 9.93735C9.92075 9.67171 10.1957 9.50668 10.5001 9.50668C10.5971 9.50668 10.6944 9.52313 10.7915 9.55513C10.7947 9.55641 10.7976 9.55805 10.8008 9.55933C10.8111 9.56329 10.8213 9.56652 10.8316 9.56975C10.9207 9.60321 11.0094 9.64928 11.0974 9.70937C11.3216 10.0244 11.3657 10.3659 11.2177 10.6502Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                            </svg>

                                                    </span>
                                                </div>

                                                <span class="fw-light spez_text truncate-card-text card-text-center">
                                                @if(session('front_lang')=='en')
                                                {{ html_decode($car['mileage']) .',000' }}
                                                @else
                                                    {{ html_decode($car['mileage_en'])  .',000'}}   
                                                @endif
                                                </span>
                                            </div>
                                            <div class="brand-car-inner-item-two">
                                                <div class="brand-car-inner-item-thumb">
                                                    <span>
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill="#0D274E" stroke="#0D274E" stroke-width="0.2" d="M19.5 4H16.5V2.5C16.5 2.36739 16.4473 2.24021 16.3536 2.14645C16.2598 2.05268 16.1326 2 16 2C15.8674 2 15.7402 2.05268 15.6464 2.14645C15.5527 2.24021 15.5 2.36739 15.5 2.5V4H8.5V2.5C8.5 2.36739 8.44732 2.24021 8.35355 2.14645C8.25979 2.05268 8.13261 2 8 2C7.86739 2 7.74021 2.05268 7.64645 2.14645C7.55268 2.24021 7.5 2.36739 7.5 2.5V4H4.5C3.8372 4.00079 3.20178 4.26444 2.73311 4.73311C2.26444 5.20178 2.00079 5.8372 2 6.5V19.5C2.00079 20.1628 2.26444 20.7982 2.73311 21.2669C3.20178 21.7356 3.8372 21.9992 4.5 22H19.5C20.163 22 20.7989 21.7366 21.2678 21.2678C21.7366 20.7989 22 20.163 22 19.5V6.5C22 5.83696 21.7366 5.20107 21.2678 4.73223C20.7989 4.26339 20.163 4 19.5 4ZM21 19.5C21 19.8978 20.842 20.2794 20.5607 20.5607C20.2794 20.842 19.8978 21 19.5 21H4.5C4.10218 21 3.72064 20.842 3.43934 20.5607C3.15804 20.2794 3 19.8978 3 19.5V11H21V19.5ZM21 10H3V6.5C3 5.672 3.67 5 4.5 5H7.5V6.5C7.5 6.63261 7.55268 6.75979 7.64645 6.85355C7.74021 6.94732 7.86739 7 8 7C8.13261 7 8.25979 6.94732 8.35355 6.85355C8.44732 6.75979 8.5 6.63261 8.5 6.5V5H15.5V6.5C15.5 6.63261 15.5527 6.75979 15.6464 6.85355C15.7402 6.94732 15.8674 7 16 7C16.1326 7 16.2598 6.94732 16.3536 6.85355C16.4473 6.75979 16.5 6.63261 16.5 6.5V5H19.5C19.8978 5 20.2794 5.15804 20.5607 5.43934C20.842 5.72064 21 6.10218 21 6.5V10Z" fill="black" stroke="black" stroke-width="0.5"/>
                                                        </svg>
                                                    </span>
                                                </div>

                                                <span class="truncate-card-text card-text-center" data-toggle="tooltip" data-placement="top" title="@if(session('front_lang')=='en')
                                                    {{ html_decode($car['year_en']) }}
                                                    @else
                                                        {{ html_decode($car['year']) }}   
                                                    @endif">
                                                    @if(session('front_lang')=='en')
                                                    {{ html_decode($car['year_en']) }}
                                                    @else
                                                        {{ html_decode($car['year']) }}   
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="brand-car-inner-item-two">
                                                <div class="brand-car-inner-item-thumb">
                                                    <span>
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M17 11.5H17.5V11V7.5H18.5V12.5H13H12.5V13V16.5H11.5V13V12.5H11H7H6.5V13V16.5H5.5V7.5H6.5V11V11.5H7H11H11.5V11V7.5H12.5V11V11.5H13H17ZM7.5 4.5H4.5V2.5H7.5V4.5ZM7.5 19.5V21.5H4.5V19.5H7.5ZM10.5 4.5V2.5H13.5V4.5H10.5ZM13.5 19.5V21.5H10.5V19.5H13.5ZM19.5 4.5H16.5V2.5H19.5V4.5Z" fill="#0D274E" stroke="#0D274E" stroke-width="0.2"/>
                                                    </svg>


                                                    </span>
                                                </div>

                                                <span class="truncate-card-text card-text-center" data-toggle="tooltip" data-placement="top" 
                                                title="@if(session('front_lang')=='en')
                                                        {{ html_decode(!empty($car['model_details_en']) ? $car['model_details_en'] : '--') }}
                                                        @else
                                                        {{ html_decode(!empty($car['model_details_en']) ? $car['model_details_en'] : '--') }}
                                                    @endif">
                                                    @if(session('front_lang')=='en')
                                                        {{ html_decode(!empty($car['model_details_en']) ? $car['model_details_en'] : '--') }}
                                                        @else
                                                        {{ html_decode(!empty($car['model_details_en']) ? $car['model_details_en'] : '--') }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div class="px-3 py-2 brand-car-btm-txt-btm">
                                            @php
                                            $carbonInstance = Carbon::parse($car['datetime']);
                                             $parsed_data=parseCustomFormat($car['parsed_data']);
                                            @endphp
                                            <p>
                                                <i class="bi bi-geo-alt-fill fs-6"></i>
                                                <span class="brand-location"> {{ isset($parsed_data['vehicle  location']) ? trim($parsed_data['vehicle  location']) : '--' }}</span>
                                            </p>
                                            <div class="d-flex flex-column">
                                                <span class="brand-date fw-light">{{ $carbonInstance->format('Y-m-d') }}</span>
                                                <span class="brand-date fw-light">{{ $carbonInstance->format('H:i:s') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                @if ($home3_ads->status == 'enable')
                <div class="col-lg-3">
                    <div class="feature-thumb">
                        <a  href="{{ $home3_ads->link }}" target="_blank"> <img src="{{ asset('japan_home/UsedCar_1.jpg') }}" class="image_bigsale" alt="img"></a>
                    </div>
                </div>
                @endif
                <div>

                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="feature-position-img"></div>
                </div>
            </div>

            <div class="pt-5 d-flex align-items-center justify-content-center">
                <a href="{{ route('new-arrivals') }}" class="thm-btn">{{ __('SEE ALL') }}</a>
            </div>
        </div>
        </section>
     @endif
    <!--  Feature-part-end -->


    <!--  vedio-part-start -->
    <!-- <section class="px-2 vedio px-sm-3 px-lg-5"> -->
    <section class="px-2 vedio px-sm-2 px-md-5">
        <div class="container vedio-bg"
            style="background: url({{ asset($homepage->video_bg_image) }});">
            <div class="row align-items-center">
                <div class="col-lg-6 col-sm-6 video-content">
                    <p>Watch The Video</p>
                    <h2 class="py-4 vedio-taitel video-Text">JDM Car From Japan To US, Canada, Australia Completely Legal</h2>

                    <div class="">
                        <a href="{{ route('contact-us') }}" class="thm-btn text-case-change">Contact Us</a>
                    </div>
                </div>

                <div class="col-lg-6 col-sm-6 ">
                    <div class="vedio-item">
                        <a class="my-video-links" data-autoplay="true" data-vbtype="video"
                            href="https://youtu.be/{{ $homepage->video_id }}">

                            <div class="vedio-item-icon">
                                <span>
                                    <svg width="44" height="44" viewBox="0 0 44 44" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_48_15344)">
                                            <path
                                                d="M37.5687 14.6098L20.0823 1.7875C18.7166 0.787393 17.1005 0.18501 15.4133 0.0471256C13.7261 -0.0907584 12.0337 0.241243 10.5237 1.00633C9.01364 1.77141 7.74494 2.9397 6.85822 4.38168C5.9715 5.82367 5.5014 7.48303 5.50001 9.17584V34.8333C5.49738 36.5278 5.96503 38.1898 6.8509 39.6342C7.73678 41.0787 9.00612 42.2489 10.5176 43.0148C12.0292 43.7806 13.7236 44.1119 15.4122 43.9719C17.1009 43.8319 18.7176 43.226 20.0823 42.2217L37.5687 29.3993C38.729 28.5478 39.6724 27.4351 40.3228 26.1512C40.9731 24.8673 41.312 23.4484 41.312 22.0092C41.312 20.57 40.9731 19.151 40.3228 17.8671C39.6724 16.5832 38.729 15.4705 37.5687 14.619V14.6098Z" />
                                        </g>
                                    </svg>
                                </span>
                            </div>


                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!--  vedio-part-end -->


    <!--   Testimonial-part-start -->
    <section class="py-5 overflow-hidden testimonial testimonial-padding px-lg-5">
        <div class="container px-2 px-sm-3 px-lg-5">
            <div class="px-5 row">
                <div class="col-lg-5">

                    <div class="sm-df">
                        <div class="t-df-sm">

                            <h2 class="section-heading">Customer Say About </br><span class="highlight">Our Services</span></h2>
                            <p class="testimonial-p cust-global-text">{{ __('translate.We have 15m+ Global and Local Happy Customers') }}</p>
                        </div>

                        <div class="t-df-item">
                            <div class="testimonial-slick-btn">
                                <div class="feature-slick-prev testimonial-slick-prve">
                                    <span>
                                        <svg width="23" height="16" viewBox="0 0 23 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8 1L1 8M1 8L8 15M1 8L22 8" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="feature-slick-next testimonial-slick-next">
                                    <span>
                                        <svg width="23" height="16" viewBox="0 0 23 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15 15L22 8M22 8L15 0.999999M22 8L1 8" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-1"></div>

                <div class="col-lg-6">
                    <div class="testimonial-slick-main">
                        <div class="testimonial-slick">

                            @foreach ($testimonials as $index => $testimonial)
                                <div class="testimonial-slick-top-main">
                                    <div class="testimonial-slick-top">
                                        <div class="testimonial-slick-top-thumb">
                                            <img src="{{ asset($testimonial->image) }}"
                                                alt="thumb">
                                        </div>

                                        <div class="testimonial-slick-top-txt">
                                            <h4>{{ $testimonial->name }}</h4>
                                            <p>
                                                <span>Hyogo, Japan  -   Jun 07, 2024 &nbsp;</span>
                                                <span class="testimonial-verify-text"> &nbsp; Verified Buyer</span>
                                            </p>
                                            <!-- <p>{{ $testimonial->designation }}</p> -->
                                        </div>
                                    </div>
                                    <p class="testimonial-p">{{ $testimonial->comment }}</p>


                                    <div class="testimonial-btm-item">
                                        <div class="testimonial-btm-item-thumb">
                                            <span>
                                                <svg width="54" height="40" viewBox="0 0 54 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M49.5406 4.06287C47.1408 1.36698 44.2282 0 40.8838 0C37.8781 0 35.33 1.07033 33.3098 3.18112C31.3024 5.27842 30.2845 7.88345 30.2845 10.924C30.2845 13.7974 31.3127 16.3578 33.3404 18.5344C35.129 20.4545 37.3822 21.6759 40.0496 22.1736C39.5831 25.7101 36.4568 28.9111 30.7387 31.7003L29.709 32.2026L33.9307 39.9964L34.8837 39.5134C46.976 33.3839 53.1072 24.7214 53.1072 13.7664C53.1072 9.98687 51.9073 6.72226 49.5406 4.06287ZM34.8388 37.062L32.7236 33.1576C39.0843 29.8246 42.3069 25.8181 42.3069 21.2372V20.2564L41.3324 20.146C38.7077 19.849 36.6191 18.8322 34.9474 17.0374C33.2877 15.2557 32.4808 13.2562 32.4808 10.924C32.4808 8.43381 33.2711 6.39791 34.8964 4.69953C36.5086 3.01495 38.4672 2.19605 40.8839 2.19605C43.6124 2.19605 45.9074 3.28432 47.9 5.5229C49.9262 7.79943 50.9111 10.4958 50.9111 13.7663C50.9111 18.7872 49.4973 23.3202 46.7091 27.2392C44.0485 30.9785 40.0582 34.2797 34.8388 37.062Z" fill="#038FFC"/>
                                                    <path d="M19.7738 4.0579C17.3473 1.36532 14.4226 0 11.0807 0C8.07213 0 5.53555 1.0723 3.54187 3.18703C1.5653 5.2835 0.563015 7.88657 0.563015 10.924C0.563015 13.7973 1.59113 16.3577 3.61863 18.5344C5.40351 20.4504 7.62964 21.6706 10.2474 22.1706C9.78658 25.7098 6.68627 28.9124 1.01401 31.7021L0 32.2006L4.1166 40L5.07906 39.5144C17.2262 33.3852 23.3853 24.7223 23.3853 13.7663C23.3852 9.98387 22.17 6.71749 19.7738 4.0579ZM5.04711 37.0583L2.98964 33.1599C9.30416 29.8257 12.5037 25.8182 12.5037 21.2371V20.2585L11.5314 20.1463C8.96052 19.8496 6.89766 18.8327 5.22542 17.0373C3.56573 15.2558 2.75906 13.2561 2.75906 10.924C2.75906 8.4306 3.53782 6.39252 5.13964 4.69362C6.72402 3.01308 8.6675 2.19605 11.0807 2.19605C13.8119 2.19605 16.122 3.28588 18.1422 5.52798C20.1925 7.80328 21.1892 10.4981 21.1892 13.7663C21.1892 18.7864 19.7692 23.3188 16.9683 27.2374C14.2963 30.9756 10.2888 34.2763 5.04711 37.0583Z" fill="#038FFC"/>
                                                    </svg>

                                            </span>
                                        </div>

                                        <div class="testimonial-btm-item-txt-item">
                                            <!-- <h6>{{ __('translate.Quality Service') }}</h6> -->

                                            <ul>
                                                <li>
                                                    <span>
                                                        <i class="fa-solid fa-star"></i>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span>
                                                        <i class="fa-solid fa-star"></i>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span>
                                                        <i class="fa-solid fa-star"></i>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span>
                                                        <i class="fa-solid fa-star"></i>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span>
                                                        <i class="fa-solid fa-star"></i>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="testimonial-position-img">
                        <div class="testimonial-position-img-left">

                        </div>
                        <div class="testimonial-position-img-right d-none d-lg-block">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--   Testimonial-part-end -->


    <!-- Car Buy section Start -->
        <section class="gap-5 py-5 buy-section steps-section px-sm-2 px-md-5">
            <div class="buy-container">
                <div class="pb-5">
                    <h1 class="pb-2">Buy in 3 Easy Steps</h1>
                    <p class="text-center disc buy-text-white d-flex justify-content-center">Adding smiles to your miles. Car buying made simpler</p>
                </div>

                <div class="gap-5 px-5 d-flex flex-column flex-lg-row flex-md-column flex-sm-column gap-lg-4 justify-content-center">
                    <div>
                        <div class="step-circle">
                            <div class="inner-card">
                                <img src="{{asset('japan_home/car_search.svg')}}" alt="search"/>
                            </div>
                        </div>
                        <h5 class="step-title buy-text-white">Find the perfect car</h5>
                        <p class="step-description buy-text-white">Seamlessly browse thousands of MRL Certified cars</p>
                    </div>
                    <div>
                        <div class="step-circle">
                            <div class="inner-card">
                                <img src="{{asset('japan_home/file.svg')}}" alt="search"/>
                            </div>
                        </div>
                        <h5 class="step-title buy-text-white">Send Enquiry to Alpine Japan</h5>
                        <p class="step-description buy-text-white">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    </div>
                    <div>
                        <div class="step-circle">
                            <div class="inner-card">
                                <img src="{{asset('japan_home/dollar.svg')}}" alt="search"/>
                            </div>
                        </div>
                        <h5 class="step-title buy-text-white">Buy it your way</h5>
                        <p class="step-description buy-text-white">You can pay in full or have it financed, the choice is yours</p>
                    </div>
                </div>
            </div>
        </section>
    <!-- Car Buy section End -->


    <!-- About section Start-->
    <section class="py-5 about-section px-md-5 px-sm-2">
  <div class="container">
    <h2 class="mb-3 text-center section-heading text-md-start">
      About <span class="highlight">Alpine Japan</span>
    </h2>
    <div class="pt-4 about-row">
      <!-- Image Column -->
      <div class="about-image-container"></div>

      <!-- Content Column -->
      <div class="about-content">
        <p class="about-text">
          Alpine Japan, founded in 2009, provides a unique range of vehicles
          from passenger cars to heavy equipment and cranes. Japanese vehicles
          are known to stand out for their quality, reliability, and ease of
          use. Our company is committed to making the unique experience of
          owning Japanese vehicles available to as many people as possible in
          different countries and continents.
        </p>
        <p class="about-text">
          We specialize in exporting JDM vehicles to markets in the United
          States, Canada, Australia, and the United Kingdom. To Malaysia, we
          supply minivans and new cars, and to the Middle East we supply classic
          cars. In the African continent market, we provide conventional cars
          that are becoming an integral part of everyday life.
        </p>
        <a href="#" class="btn thm-btn">Read More</a>
      </div>
    </div>
  </div>
</section>

    <!-- About section End -->


    <!-- Quality section start -->
        <!-- <section class="py-5 quality-compliance "> -->
        <section class="py-5 quality-compliance px-sm-2 px-md-5 ">
            <!-- <div class="container px-2 text-center px-sm-3 px-lg-5"> -->
            <!-- <div class="container px-5 text-center"> -->
            <div class="container text-center">
                <h2 class="section-title">Quality <span class="highlight">Compliance</span></h2>
                <p class="section-subtitle px-md-5 px-sm-2">We arrange third party inspection for quality compliance, as per import regulations of every country worldwide. Here are some of the services we work with:</p>
                </div>
                <div class="px-4 row justify-content-center">
                    <div class="mb-4 col-md-4 col-12">
                        <div class="mb-3 compliance-item">
                            <img src="{{ asset('japan_home/qc_2.svg') }}" alt="QISJ Logo" class="compliance-logo">
                            <div class="compliance-icon"><i class="fas fa-globe"></i></div>
                        </div>
                        <div class="compliance-content">
                            <h4 class="compliance-title">Quality Inspection Services CO.,LTD</h4>
                            <p class="compliance-text">Kenya, Tanzania</p>
                        </div>
                    </div>

                    <div class="mb-4 col-md-4 col-12">
                        <div class="mb-3 compliance-item">
                            <img src="{{ asset('japan_home/qc_3.svg') }}" alt="Bureau Veritas Logo" class="compliance-logo">
                            <div class="compliance-icon"><i class="fas fa-globe"></i></div>
                        </div>
                        <div class="compliance-content">
                            <h4 class="compliance-title">Bureau Veritas Japan CO.,LTD.</h4>
                            <p class="compliance-text">Sri Lanka, Mauritius</p>
                        </div>
                    </div>

                    <div class="mb-4 col-md-4 col-12">
                        <div class="mb-3 compliance-item">
                            <img src="{{ asset('japan_home/qc_1.svg') }}" alt="JAAI Logo" class="compliance-logo">
                            <div class="compliance-icon"><i class="fas fa-globe"></i></div>
                        </div>
                        <div class="compliance-content">
                            <h4 class="compliance-title">East Africa Automobile Service</h4>
                            <p class="compliance-text">Tanzania, Uganda</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <!-- Quality section end -->

</main>
@endsection
@push('js_section')
<script>
       $(()=>{
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
         });
         });

         $(".after_auth_wishlist").on('click',function(){
                 var type=$(this).data('table-id');
                 $.ajax({
                    type: "POST",
                    url:"{{route('add-user-wishlist')}}",
                    data:{'id': $(this).data('car-id'),
                        'type': type,
                    },
                    beforeSend:function(data){
                        console.log('loading');
                    },
                    success:function(response){
                        toastr.success(response.message);
                        window.location.reload();
                    }
                 })
            })

         $('#jdm_stock_form').submit(function(e) {
            e.preventDefault();
  
            var brand=$("#jdm_brand").val();

            var model = $("#jdm_model");
            // Change the name attribute correctly
            model.attr('name', 'model[' + brand + '][]');
            this.submit();  

         });   

         window.addEventListener("load", function() {
            document.getElementById("pageLoader").classList.add("hidden");
        });


function getModel(selectedModel){
    document.getElementById('dropdownMenuClickableOutside').innerText = selectedModel; 
    $("#jdm_model").val(selectedModel);


    $.ajax({
        url:"get-model-year",
        type:"POST",
        datatype:"JSON",
        data:{"brand":document.getElementById('defaultDropdown').innerText,
            'model':selectedModel},
        beforeSend:function(response){
            console.log("loading")
        },
        success:function(data){
            console.log(data.response);
             var brands=data.response;
            for (let index = 0; index < brands.length; index++) {
                const element = brands[index];
                $(".year-ul").append(`<li><a class="dropdown-item" href="javascript:void(0);" onclick="getModelYear('${element.yom}')">${element.yom}</a></li>`);
            }

        }
    })
}

$(()=>{
    $("#jdm_brand").on('change',function(){
        $("#jdm_brand").val();
        $.ajax({
            url:"get-brands",
            type:"POST",
            datatype:"JSON",
            data:{"brand":$("#jdm_brand").val()},
            beforeSend:function(response){
                console.log("loading")
            },
            success:function(data){
                $("#jdm_model").empty();
                $("#jdm_model").append(
                        `<option value="">Model</option>`);
                var brands=data.response;
                for (let index = 0; index < brands.length; index++) {
                    const element = brands[index];
                    $("#jdm_model").append(
                        `<option value='${element}'>${element}</option>`);
                    }
                }
        })  
    })

    $("#jdm_model").on('change',function(){
        $.ajax({
        url:"get-model-year",
        type:"POST",
        datatype:"JSON",
        data:{"brand": $("#jdm_brand").val(),
            'model':$("#jdm_model").val()},
        beforeSend:function(response){
            console.log("loading")
        },
        success:function(data){
            $("#jdm_year").empty();
            $("#jdm_year").append(`
            <option  value=""}>Year</option>`);
             var brands=data.response;
            for (let index = 0; index < brands.length; index++) {
                const element = brands[index];
                $("#jdm_year").append(`
                <option  value=${element.model_year_en}>${element.model_year_en}</option>`);
            }

        }
    })
    })
})

 function getModelYear(yom){
    document.getElementById('dropdownMenuClickableInside').innerText = yom; 
    $("#jdm_year").val(yom)
}

$("#searchBtn").on('click',function(){
     $("#jdm_stock_form").submit();
})



    // jQuery to handle active state
    $(document).ready(function () {
      $(".custom-tabs .nav-item a").click(function () {
        // Remove 'active' class from all <li>
        $(".custom-tabs .nav-item a").removeClass("active");

        // Add 'active' class to the clicked <li>
        $(this).addClass("active");
      });
    });
    </script>
@endpush

