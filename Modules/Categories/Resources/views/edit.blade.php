@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Edit Categories') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Edit Categories') }}</h3>
    <a href="{{route('admin.dashboard')}}"><p class="crancy-header__text">{{ __('translate.Categories') }} >> {{ __('translate.Edit Categories') }}</p></a>
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
                            <form action="{{ route('admin.categories.update',$categories->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <!-- Product Card -->
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ __('translate.Edit Categories') }}</h4>
                                                <a href="{{ route('admin.categories.index') }}" class="crancy-btn "><i class="fa fa-list"></i> {{ __('translate.Categories List') }}</a>
                                            </div>


                                            <div class="row mg-top-30">
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Name') }} * </label>
                                                            <input type="text" class="crancy__item-input" name="name" id="name" value="{{$categories->name}}">
                                                            @error('name')
                                                                <div style="color: red;">{{ $message }}</div>
                                                            @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Average Price') }} * </label>
                                                        <input class="crancy__item-input" type="text" name="average_price" id="average_price" value="{{$categories->price}}">
                                                        @error('average_price')
                                                                <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{__('Active')}} </label>
                                                        <div class="crancy-ptabs__notify-switch  crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                            <input name="active" type="checkbox" {{$categories->is_active == '1' ? 'checked': ''}}>
                                                            <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
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


