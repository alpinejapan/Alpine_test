@extends('layout4')
@section('title')
    <title>{{ __('translate.Payment') }}</title>
@endsection

@section('body-content')
<main>
    <!-- banner-part-start  -->

    <section class="inner-banner">
    <div class="inner-banner-img" style=" background-image: url({{ asset($breadcrumb) }}) ;"></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('translate.Payment') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Payment') }}</li>
                    </ol>
                </nav>
            </div>
            </div>
        </div>
    </section>
    <!-- banner-part-end -->



    <!-- dashboard-part-start -->

    <section class="pricing package-details">
        <div class="container">

            <div class="row">
                <div class="col-lg-8">
                    <div class="package-details-item">
                        <div class="package-details-table">
                            <table class=" table table-bordered ">
                                <tr>
                                    <td>{{ __('translate.Total Price') }}</td>
                                    <td>{{ '$' . number_format($total, 0, '.', ',') }}</td>

                                </tr>

                                <tr>
                                    <td>{{ __('translate.Delivery Charge') }}</td>
                                    <td>{{ '$' . number_format($delivery_charge, 0, '.', ',') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="payment">
                        <h2 class="payment-txt">{{ __('translate.Payment Gateway') }}</h2>
                        <div class="payment-inner">
                            @if ($stripe->status == 1)
                                <div class="payment-inner-item modal-btn" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                    <div class="payment-inner-item-label">
                                        <img src="{{ asset($stripe->image) }}" alt="img">
                                    </div>
                                    <input type="button" class="payment-inner-item-input">
                                </div>
                            @endif


                            @if ($paypal->status == 1)
                                <a href="{{ route('pay-via-paypal',
                                        ['id' => $id, 
                                         'id1' => $type,
                                         'id2'=>$delievery_charge_id,
                                         'id3'=>$status,
                                         'id4'=>$shipment
                                         ])}}" class="payment-inner-item modal-btn">
                                    <div class="payment-inner-item-label">
                                        <img src="{{ asset($paypal->image) }}" alt="img">
                                    </div>

                                    <input type="button" class="payment-inner-item-input">
                                </a>
                            @endif

                            @if ($razorpay->status == 1)
                            <div class="payment-inner-item" id="razorpayBtn">
                                <div class="payment-inner-item-label">
                                    <img src="{{ asset($razorpay->image) }}" alt="img">
                                </div>

                                <input type="button" class="payment-inner-item-input">
                            </div>

                            {{--<form action="{{ route('pay-via-razorpay', 1) }}" method="POST" class="d-none">
                                @csrf
                                 @php
                                    $payable_amount = $subscription_plan->plan_price * $razorpay->currency->currency_rate;
                                    $payable_amount = round($payable_amount, 2);
                                @endphp
                                <script src="https://checkout.razorpay.com/v1/checkout.js"
                                        data-key="{{ $razorpay->key }}"
                                        data-currency="{{ $razorpay->currency->currency_code }}"
                                        data-amount= "{{ $payable_amount * 100 }}"
                                        data-buttontext="{{ __('translate.Pay') }} {{ $payable_amount }} {{ $razorpay->currency->currency_code }}"
                                        data-name="{{ $razorpay->name }}"
                                        data-description="{{ $razorpay->description }}"
                                        data-image="{{ asset($razorpay->image) }}"
                                        data-prefill.name=""
                                        data-prefill.email=""
                                        data-theme.color="{{ $razorpay->color }}">
                                </script>
                            </form> --}} 

                            @endif

                            @if ($flutterwave->status == 1)
                                <div class="payment-inner-item" id="flutterwavePayment">
                                    <div class="payment-inner-item-label">
                                        <img src="{{ asset($flutterwave->logo) }}" alt="img">
                                    </div>

                                    <input type="button" class="payment-inner-item-input">
                                </div>
                            @endif

                            @if ($paystack->paystack_status == 1)
                                <div class="payment-inner-item" id="paystackPayment">
                                    <div class="payment-inner-item-label">
                                        <img src="{{ asset($paystack->paystack_image) }}" alt="img">
                                    </div>

                                    <input type="submit" class="payment-inner-item-input">
                                </div>
                            @endif


                            @if ($mollie->mollie_status == 1)
                                <a href="{{ route('pay-via-mollie',1) }}" class="payment-inner-item">
                                    <div class="payment-inner-item-label">
                                        <img src="{{ asset($mollie->mollie_image) }}" alt="img">
                                    </div>

                                    <input type="submit" class="payment-inner-item-input">
                                </a>
                            @endif

                            @if ($instamojo->status == 1)
                                <a href="{{ route('pay-via-instamojo',1) }}" class="payment-inner-item">
                                    <div class="payment-inner-item-label">
                                        <img src="{{ asset($instamojo->image) }}" alt="img">
                                    </div>

                                    <input type="submit" class="payment-inner-item-input">
                                </a>
                            @endif


                            @if ($bank->status == 1)
                                <div class="payment-inner-item" data-bs-toggle="modal"
                                data-bs-target="#exampleModal7">
                                    <div class="payment-inner-item-label">
                                        <img src="{{ asset($bank->image) }}" alt="img">
                                    </div>

                                    <input type="submit" class="payment-inner-item-input">
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- dashboard-part-end -->


     <!-- Stripe Modal -->
     <div class="modal payment-modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('translate.Pay via Stripe') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="payment-modal-item">
                        <h4>{{ __('translate.Amount') }}<span></span></h4>
                    </div>

                        <form role="form" action="{{ route('pay-via-stripe', 1) }}" method="POST" class="require-validation payment-modal-from" data-cc-on-file="false" data-stripe-publishable-key="{{ $stripe->stripe_key }}" id="payment-form">
                            @csrf

                        <div class="payment-modal-from-item">
                            <div class="payment-modal-from-inner">
                                <input type="text" class="card-number form-control" name="card_number" placeholder="{{ __('translate.Card Number') }}">
                            </div>
                        </div>
                        <div class="payment-modal-from-item">
                            <div class="payment-modal-from-inner">
                                <input type="text" class="card-expiry-month form-control" name="month" placeholder="{{ __('translate.Expired Month') }}">
                            </div>
                            <div class="payment-modal-from-inner">
                                <input type="text" class="card-expiry-year form-control" name="year" placeholder="{{ __('translate.Expired Year') }}">
                            </div>
                        </div>
                        <div class="payment-modal-from-item">
                            <div class="payment-modal-from-inner">
                                <input type="text" class="card-cvc form-control" name="cvc" placeholder="{{ __('translate.CVC') }}">
                            </div>
                        </div>

                        <div class="payment-modal-from-item stripe_error d-none">
                            <div class="payment-modal-from-inner">
                                <div class='alert-danger alert '>{{ __('translate.Please provide your valid card information') }}</div>
                            </div>
                        </div>


                        <div class="payment-modal-from-item">
                            <div class="payment-modal-from-inner">
                                <button class="thm-btn-two" type="submit">{{ __('translate.Payment Now') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <!-- Bank Modal -->
        <div class="modal bank-payment-modal fade" id="exampleModal7" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">{{ __('translate.Bank Payment') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="payment-modal-item">
                            <h4>{{ __('translate.Amount') }}<span></span></h4>
                        </div>

                        <div class="bank-payment-modal-txt">
                            {!! clean(nl2br($bank->account_info)) !!}
                        </div>

                        <form class="payment-modal-from" action="{{ route('pay-via-bank', 1) }}" method="POST">
                            @csrf
                            <div class="payment-modal-from-item">
                                <div class="payment-modal-from-inner">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"
                                        placeholder="{{ __('translate.Transaction information') }}" name="tnx_info"></textarea>
                                </div>
                            </div>
                            <div class="payment-modal-from-item">
                                <div class="payment-modal-from-inner">
                                    <button class="thm-btn-two" type="submit">{{ __('translate.Payment Now') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


</main>
@endsection


@push('js_section')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script>
        "use strict";
        $(function() {
            var $form = $(".require-validation");
            $('form.require-validation').bind('submit', function(e) {
                var $form         = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                                    'input[type=text]', 'input[type=file]',
                                    'textarea'].join(', '),
                $inputs       = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.stripe_error'),
                valid         = true;
                $errorMessage.addClass('d-none');

                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorMessage.removeClass('d-none');
                        e.preventDefault();
                    }
                });

                if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
                }

            });

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.stripe_error')
                        .removeClass('d-none')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    var token = response['id'];
                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $form.get(0).submit();
                }
            }

            $("#razorpayBtn").on("click", function(){
                $(".razorpay-payment-button").click();
            })

        });
    </script>

   
@endpush
