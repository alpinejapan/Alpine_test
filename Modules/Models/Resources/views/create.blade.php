@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Add Models') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Add Models') }}</h3>
    <a href="{{route('admin.dashboard')}}"><p class="crancy-header__text">{{ __('translate.Models') }} >> {{ __('translate.Add Models') }}</p></a>
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
                            <form action="{{ route('admin.models.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <!-- Product Card -->
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ __('translate.Add Models') }}</h4>

                                                <a href="{{ route('admin.categories.index') }}" class="crancy-btn "><i class="fa fa-list"></i> {{ __('translate.Model List') }}</a>
                                            </div>


                                            <div class="row mg-top-30">
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Brands') }} * </label>
                                                        <select  name="brand" id="brand"  class="crancy__item-input">
                                                            <option value="">Select Brands</option>
                                                           @foreach($brands as $category)
                                                              <option value="{{$category->id}}">{{$category->getBrands->name}}</option>
                                                           @endforeach  
                                                        </select>
                                                        @error('category')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Image') }} * </label>
                                                        <div class="crancy-product-card__upload crancy-product-card__upload--border">
                                                        <input type="file" class="btn-check" name="image" id="input-img1" autocomplete="off" onchange="previewImage(event)">
                                                        <label class="crancy-image-video-upload__label" for="input-img1">
                                                        <img id="view_img" src="{{ asset($setting->placeholder_image) }}">
                                                        <h4 class="crancy-image-video-upload__title">{{ __('translate.Click here to') }} <span class="crancy-primary-color">{{ __('translate.Choose File') }}</span> {{ __('translate.and upload') }} </h4>
                                                        </label>
                                                    </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Model') }} * </label>
                                                        <input class="crancy__item-input" type="text" name="model" id="model">
                                                        @error('model')
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


