@extends('layout4')
@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
    
@endsection

@section('body-content')

<style>
    .page-item.active .page-link {
        color:white !important;
    }

    .page-link,
    .page-link:hover{
        color: #0a58ca !important;
    }

    .page-item .page-link {
        color: #0a58ca !important;
    }
</style>


<main>
<div id="pageLoader">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
</div>
    <section class="categories  pb-120px">
        <div class="container-fluid pt-lg-5 px-5">
            <div class="row align-items-end mt-lg-5 pt-lg-4">
                <div class="col-lg-8 col-sm-8 col-md-12  ">
                    <div class="taitel">
                        <div class="taitel-img">
                            <span>
                                <!-- <svg width="71" height="8" viewBox="0 0 71 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 6.08589C15.5 0.18137 51.5 -0.151783 70 6.42496" stroke="#46d993" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg> -->

                            </span>
                        </div>
                        <!-- <span>{{ __('translate.Brands') }}</span> -->
                    </div>

                    <div>
                        <h2 class="page-heading">Popular <span class="page-heading-highlight">Brands</span></h2>
                    </div>
                </div>

            </div>


            <div class="row g-3  mt-30px ">
                @foreach ($brands as $index => $brand)
                <div class="col-xl-2 col-xl-2 col-lg-4 col-6 col-md-6">
                    <div class="categories-logo">
                        <a href="{{ route('jdm-stock',[$brand->slug, 'car']) }}" class="categories-logo-thumb">
                            <img src="{{ asset('Brand/'.$brand->image) }}" alt="logo">
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

        <!-- <div class="container ">
            <div class="col-lg-4">
                    <div class="categories-three-view-btn" style="margin-top: 40px; margin-right: -300px">
                    <a href="{{ route('listings') }}" class="thm-btn">{{ __('translate.View All') }}</a>
                    </div>
            </div>
        </div> -->
    </section>
</main>

@endsection


@push('js_section')

<script>







(function($) {
    "use strict";
    $(()=>{
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
         });
         });
    $(document).ready(function () {
        const form = $('#search_form');
        $("#outside_form_search").on("keyup", function(e) {
            let inputValue = $(this).val();
            $("#inside_form_search").val(inputValue);
        });

        $("#outside_form_btn").on("click", function(e) {
            e.preventDefault();   
            form.submit();
        });

        $(".popular-search").on('change',function(e){
            e.preventDefault();   
            form.submit();
        })
        $(".brand-search").on('change',function(e){
            e.preventDefault();   
            $(".model-search").val("")
            form.submit();
           
        }) 
        $(".model-search").on('change',function(e){
            e.preventDefault();   
            form.submit();    
        })

    

      
        $("#start").on('input',function(e){
            $("#age_output").val(parseInt($(this).val()))
            $("#start_year").val(parseInt($(this).val()));
            // form.submit();
        })

        $("#brand_new_cars").on('change',function(e){
            e.preventDefault();
                form.submit();
        })

    
    });
})(jQuery);
    window.addEventListener("load", function() {
        document.getElementById("pageLoader").classList.add("hidden");
    });
</script>   <!------- Range ------->
@endpush




