@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Edit Delivery Charge') }}</title>
@endsection

@section('body-header') 
    <h3 class="crancy-header__title m-0">{{ __('translate.Edit Delivery Charge') }}</h3>
    <a href="{{route('admin.dashboard')}}"><p class="crancy-header__text">{{ __('translate.Delivery Charges') }} >> {{ __('translate.Edit Delivery Charge') }}</p></a>
    <style>
         .file_image{
          width: 30px ! important;
          height: 30px ! important;
          border-radius: 50% ! important;
          margin-top:3px ! important;
        }
    </style>
@endsection

@section('body-content')
    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <!-- Dashboard Inner -->
                        <div class="crancy-dsinner">
                            <form action="{{ route('admin.delivery-charges.update',$delivery_charge_data->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <!-- Product Card -->
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ __('translate.Edit Delivery Charge') }}</h4>
                                                <a href="{{ route('admin.delivery-charges.index') }}" class="crancy-btn "><i class="fa fa-list"></i> {{ __('translate.Delivery Charges List') }}</a>
                                            </div>
                                            <div class="row mg-top-30">
                                                <div class="col-3">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Country') }} * </label>
                                                        <input class="crancy__item-input" type="text" name="country" id="country" value="{{$delivery_charge_data->country_name}}">
                                                        @error('country')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Rate') }} * </label>
                                                        <input class="crancy__item-input" type="text" name="rate" id="rate" value="{{$delivery_charge_data->rate}}">
                                                        @error('rate')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.RoRo') }} * </label>
                                                        <input class="crancy__item-input" type="text" name="roro" id="roro" value="{{$delivery_charge_data->roro}}">
                                                        @error('roro')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Container') }} * </label>
                                                        <input class="crancy__item-input" type="text" name="container" id="container" value="{{$delivery_charge_data->container}}">
                                                        @error('container')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                         
                                            <button class="crancy-btn mg-top-25" type="submit">{{ __('translate.Update') }}</button>

                                        </div>
                                        <!-- End Product card -->
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- End Dashboard Inner -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->
@endsection

@push('js_section')
    <script>
        (function($) {
            "use strict"
            $(document).ready(function () {
                $("#name").on("keyup",function(e){
                    let inputValue = $(this).val();
                    let slug = inputValue.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-');
                    $("#slug").val(slug);
                })
            });
        })(jQuery);

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('view_img');
                output.src = reader.result;
            }

            reader.readAsDataURL(event.target.files[0]);
        };
    </script>
@endpush


