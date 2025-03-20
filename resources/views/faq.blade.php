@extends('layout4')
@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
@endsection

@section('body-content')
<main>
    <!-- banner-part-start  -->

    <section>
        <div class="container-fluid faq_con_flu">
            <div class="row faq_row">
                <div class="col-md-12 faq-img p-0">
                    <img src="{{  asset('japan_home/faq_img.svg')  }}" alt="HTML tutorial" class="img-fluid how-to-buy-image">

                    <div class="faq-top-left">
                        <p class="faq-top-left-p" style="display: inline">How To Buy</p>
                        <p class="faq-top-left-p1" style="display: inline">JDM Car</p>
                        <br>
                        <p class="faq-top-left-p" style="display: inline;margin-left:180px; top:30px">from</p>
                        <p class="faq-top-left-p1" style="display: inline">Japan</p>
                    </div>

                    <div class="faq-bottom-left">
                        <p class="faq-bottom-left-p"><b>How to find JDM car from?</b></p><br>
                        <p class="faq-bottom-left-p"><b>How to choose Japan car?</b></p><br>
                        <p class="faq-bottom-left-p"><b>How do we export and delivery Japan car?</b></p><br>
                        <p class="faq-bottom-left-p"><b>How is the payment processed?</b></p><br>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- banner-part-end -->


    <!--FAQ-part-start -->

    <section class=" faq" style="padding-top:100px; margin-left: 10px">
        <div class="container-fluid">
            <div class="row  justify-content-center">
                <div class="col-lg-11">
                    <!-- <div class="accordion" id="accordionExample">
                        @foreach ($faqs as $index => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne-{{ $index }}">
                                    <button class="accordion-button {{ $index != 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne-{{ $index }}" aria-expanded="true" aria-controls="collapseOne-{{ $index }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                
                                <div id="collapseOne-{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }} "
                                    aria-labelledby="headingOne-{{ $index }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body faq_answer">
                                        {!! clean($faq->answer) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div> -->

                    <div class="accordion" id="accordionExample">
                        <!-- @foreach ($faqs as $index => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne-{{ $index }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne-{{ $index }}" aria-expanded="false" aria-controls="collapseOne-{{ $index }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                
                                <div id="collapseOne-{{ $index }}" class="accordion-collapse collapse"
                                    aria-labelledby="headingOne-{{ $index }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body faq_answer">
                                        {!! clean($faq->answer) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach -->
                        @foreach($faqs->reverse() as $index => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne-{{ $index }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne-{{ $index }}" aria-expanded="false" aria-controls="collapseOne-{{ $index }}">
                                        {{ $faq->question }}
                                    </button>
                                </h2>
                                
                                <div id="collapseOne-{{ $index }}" class="accordion-collapse collapse"
                                    aria-labelledby="headingOne-{{ $index }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body faq_answer">
                                        {!! clean($faq->answer) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    
                </div>
            </div>
        </div>
    </section>

    <!--FAQ-part-end -->

      <!-- Our Business section Start -->
        <section class="help">
            <div class="container">
                <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="help-box">
                    <div class="icon">
                        <span>
                            <img src="{{  asset('japan_home/contact-info.svg')  }}" alt="HTML tutorial">
                        </span>
                    </div>

                    <div class="text">
                        <h6>Need Any Help?</h6>
                        <h3><a href="">+81 78 242-1568</a></h3>
                    </div>
                    </div>

                    <h3 class="help-taitel">
                    <p class="text-center our_help pb-2">We Are Proud Of Our Business</p>
                    <!-- <span class="text-center our_help_quotation">Get a Free Quotation Now!</span> -->
                    </h3>
                </div>
                </div>

                <div class="row">
                <div class="col-lg-12">
                    <div class="help-img text-center">
                        <img src="{{ asset('japan_home/faq.png') }}" alt="img" style="width:95%" class="img-fluid">
                    </div>
                </div>
                </div>
            </div>
        </section>
      <!-- Our Business section End -->


</main>
@endsection
