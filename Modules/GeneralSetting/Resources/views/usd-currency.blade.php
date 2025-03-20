@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Create Categories') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Today JPY') }}</h3>
    <a href="{{route('admin.dashboard')}}"><p class="crancy-header__text">{{ __('translate.Today JPY') }} >> {{ __('translate.Today JPY') }}</p></a>
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
                            <form action="{{ route('admin.store-jpy-rate') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                            

                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <!-- Product Card -->
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">JPY</h4>

                                            </div>


                                            <div class="row mg-top-30">
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Name') }} * </label>
                                                            <input type="text" class="crancy__item-input" name="jpy_rate" 
                                                            id="jpy_rate" value="{{!empty($jpy_rate) ? round($jpy_rate->yen_rate) :''}}">
                                                            @error('jpn_rate')
                                                                <div style="color: red;">{{ $message }}</div>
                                                            @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <button class="crancy-btn mg-top-25" type="submit">{{ __('translate.Save') }}</button>

                                        </div>
                                        <!-- End Product Card -->
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


