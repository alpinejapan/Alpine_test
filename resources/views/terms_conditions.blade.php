@extends('layout4')
@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
@endsection

@section('body-content')

    <main class="">
        <!-- banner-part-start  -->

        <section class="privacy_policy_header pt-5">
            <div class="container pt-5">
                <div class="row pt-5 px-sm-2 px-md-5 banner_color">
                    <div class="col-lg-12">
                        <div class="pb-2 pt-3">
                            <!-- <h2 class="contact_us" >Contact</h2> 
                            <h2 class="contact_us contact-us-color">Us</h2> -->
                            <h2 class="section-heading">Terms & <span class="highlight"> Conditions<span></h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- banner-part-end -->


        <!-- Privacy and Policy-part start  -->

        <section class="privacy">
            <div class="container">
                <div class="row px-sm-2 px-md-5">
                    <div class="col-lg-12">

                        <div class="privacy-text-item">
                            {!! clean($terms_condition->description) !!}
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- Privacy and Policy-part end  -->

    </main>

@endsection
