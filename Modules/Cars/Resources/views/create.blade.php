@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Create Car') }}</title>
@endsection

@section('body-header') 
    <h3 class="crancy-header__title m-0">{{ __('translate.Create Car') }}</h3>
    <a href="{{route('admin.dashboard')}}"><p class="crancy-header__text">{{ __('translate.Car') }} >> {{ __('translate.Create Car') }}</p></a>
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
                            <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <!-- Product Card -->
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ __('translate.Create Car') }}</h4>

                                                <a href="{{ route('admin.categories.index') }}" class="crancy-btn "><i class="fa fa-list"></i> {{ __('translate.Car List') }}</a>
                                            </div>


                                            <div class="row mg-top-30">
                                                <!-- <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Category') }} *</label>
                                                        <select  name="category" id="category"  class="crancy__item-input">
                                                            <option value="">Select Catagory</option>
                                                           @foreach($category as $category)
                                                              <option value="{{$category->name}}">{{$category->name}}</option>
                                                           @endforeach  
                                                        </select>
                                                        @error('category')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div> -->
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Image') }} </label>
                                                        <input type="file" class="form-control" name="image">
                                                        @error('image')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Cover Image') }} </label>
                                                        <input type="file" class="form-control" name="cover_image[]" multiple>
                                                        @error('image')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                               
                                            </div>
                                            <div class="row mg-top-30">
                                            <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Title') }} * </label>
                                                        <input class="crancy__item-input" type="text" name="title" id="title">
                                                        @error('title')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Maker') }} * </label>
                                                        <select  name="brand" id="brand"  class="crancy__item-input">
                                                            <option value="">Select brand</option>
                                                           @foreach($brands as $brand)
                                                              <option value="{{$brand->brand_id}}">{{$brand->name}}</option>
                                                           @endforeach  
                                                        </select>
                                                        @error('maker')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Model') }} * </label>
                                                        <select  name="model" id="model"  class="crancy__item-input">
                                                            <option value="">Select Model</option> 
                                                        </select>
                                                        @error('model')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                              
                                            </div>
                                            <div class="row mg-top-30">
                                              
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Grade') }}  </label>
                                                        <input class="crancy__item-input" type="text" name="grade" id="grade">
                                                        @error('grade')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Color') }} * </label>
                                                        <input class="crancy__item-input" type="text" name="color" id="color">
                                                        @error('color')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Interial Color') }} * </label>
                                                        <input class="crancy__item-input" type="text" name="interial_color" id="interial_color">
                                                        @error('interial_color')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Year Of Registration') }}  </label>
                                                        <input class="crancy__item-input" type="text" name="year_of_registration" id="year_of_registration">
                                                        @error('year_of_registration')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Chassis number') }} </label>
                                                        <input class="crancy__item-input" type="text" name="chassis_number" id="chassis_number">
                                                        @error('chassis_number')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Score') }} </label>
                                                        <input class="crancy__item-input" type="text" name="score" id="score">
                                                        @error('score')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mg-top-30"> 
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Year Of Made') }} </label>
                                                        <input class="crancy__item-input" type="text" name="year_of_made" id="year_of_made">
                                                        @error('year_of_made')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>      
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Kilometers') }} </label>
                                                        <input class="crancy__item-input" type="text" name="kilometers" id="kilometers">
                                                        @error('kilometers')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Engine Captibility') }}  </label>
                                                        <input class="crancy__item-input" type="text" name="engine_captibility" id="engine_captibility">
                                                        @error('engine_captibility')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div> 
                                            </div>
                                            <div class="row mg-top-30"> 
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Engine type') }} </label>
                                                        <input class="crancy__item-input" type="text" name="engine_type" id="engine_type">
                                                        @error('engine_type')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>                                    
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Transmission') }} </label>
                                                        <input class="crancy__item-input" type="text" name="transmission" id="transmission">
                                                        @error('transmission')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Fuel') }}  </label>
                                                        <input class="crancy__item-input" type="text" name="fuel" id="fuel">
                                                        @error('fuel')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mg-top-30">     
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Video Link') }} </label>
                                                        <input class="crancy__item-input" type="text" name="video_link" id="video_link">
                                                        @error('video_link')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.inside') }} </label>
                                                        <input class="crancy__item-input" type="text" name="inside" id="inside">
                                                        @error('inside')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.outside') }}  </label>
                                                        <input class="crancy__item-input" type="text" name="outside" id="outside">
                                                        @error('outside')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mg-top-30">  
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Price($)') }}  </label>
                                                        <input class="crancy__item-input" type="text" name="price_dollar" id="price_dollar">
                                                        @error('price_dollar')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Price(RU)') }} </label>
                                                        <input class="crancy__item-input" type="text" name="price_rupees" id="price_rupees">
                                                        @error('price_rupees')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Price(Yen)') }}  </label>
                                                        <input class="crancy__item-input" type="text" name="price_yen" id="price_yen">
                                                        @error('price_yen')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mg-top-30">
                                                 <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Location') }}  </label>
                                                        <input class="crancy__item-input" type="text" name="location" id="location">
                                                        @error('price_yen')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div> 
                                            </div>    

                                            <!-- <div class="row mg-top-30">                                  
                                               
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Cover Image') }}  </label>
                                                        <div  class="dropzone" id="myDropzone">
                                                        @error('remarks')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                               
                                            </div> -->
                                            <div class="row mg-top-30">                                  
                                               
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Remarks') }}  </label>
                                                        <!-- <textarea name="remarks" id="remarks" class='crancy__item-input'></textarea> -->
                                                        <textarea class="crancy__item-input crancy__item-textarea summernote"  name="remarks" id="remarks">
                                                            {{ old('remarks') }}
                                                        </textarea>
                                                        @error('remarks')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                               
                                            </div>

                                            <div class="row mg-top-30"> 
                                            <div class="col-12">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Sell Points') }}  </label>
                                                        <!-- <textarea name="sell_points" id="sell_points" class='crancy__item-input'></textarea> -->
                                                        <textarea class="crancy__item-input crancy__item-textarea summernote"  name="sell_points" id="sell_points">
                                                            {{ old('sell_points') }}
                                                        </textarea>
                                                        @error('sell_points')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                          
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{__('Active')}} </label>
                                                        <div class="crancy-ptabs__notify-switch  crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                            <input name="active" type="checkbox">
                                                            <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                        @error('active')
                                                                <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>         
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{__('translate.North America market')}} </label>
                                                        <div class="crancy-ptabs__notify-switch  crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                            <input name="north_america_market" type="checkbox">
                                                            <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                        @error('north_america_market')
                                                                <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{__('translate.Russia market')}} </label>
                                                        <div class="crancy-ptabs__notify-switch  crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                            <input name="russia_market" type="checkbox">
                                                            <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                        @error('russia_market')
                                                                <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{__('translate.ABS Anti-lock braking systems')}} </label>
                                                        <div class="crancy-ptabs__notify-switch  crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                            <input name="abs" type="checkbox">
                                                            <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                        @error('abs')
                                                                <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{__('AW Alloy Wheels')}} </label>
                                                        <div class="crancy-ptabs__notify-switch  crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                            <input name="aw_alloy_wheels" type="checkbox">
                                                            <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                        @error('aw_alloy_wheels')
                                                                <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{__('PW Power Windows')}} </label>
                                                        <div class="crancy-ptabs__notify-switch  crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                            <input name="pw_power_windows" type="checkbox">
                                                            <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                        @error('pw_power_windows')
                                                                <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                          
                                            </div>
                                            <div class="row mg-top-30"> 
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{__('PS Power Steering')}} </label>
                                                        <div class="crancy-ptabs__notify-switch  crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                            <input name="ps_power_steering" type="checkbox">
                                                            <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                        @error('ps_power_steering')
                                                                <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{__('AB Airbag')}} </label>
                                                        <div class="crancy-ptabs__notify-switch  crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                            <input name="ab_air_bag" type="checkbox">
                                                            <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                        @error('ab_air_bag')
                                                                <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{__('SR Sunroof')}} </label>
                                                        <div class="crancy-ptabs__notify-switch  crancy-ptabs__notify-switch--two">
                                                            <label class="crancy__item-switch">
                                                            <input name="sr" type="checkbox">
                                                            <span class="crancy__item-switch--slide crancy__item-switch--round"></span>
                                                            </label>
                                                        </div>
                                                        @error('ab_air_bag')
                                                                <div style="color: red;">{{ $message }}</div>
                                                        @enderror
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

@push('style_section')
    <link rel="stylesheet" href="{{ asset('global/tagify/tagify.css') }}">

    <style>
        .tox .tox-promotion,
        .tox-statusbar__branding{
            display: none !important;
        }
    </style>
@endpush

@push('js_section')
<script src="{{ asset('global/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('global/tagify/tagify.js') }}"></script>
    <script>
        (function($) {
            "use strict"
            $(document).ready(function () {
                $("#name").on("keyup",function(e){
                    let inputValue = $(this).val();
                    let slug = inputValue.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-');
                    $("#slug").val(slug);
                })

                $("#brand").on('change',function(e){
                    
                    // $("#category").on('change',function(e){
                        var models=@json($models);
                        $("#model").empty();
                        var user_comments = models.filter(p => p.brand_id == $(this).val());
                        $("#model").append(
                            '<option value="">Select Model</option>'
                        );
                        for (let index = 0; index < user_comments.length; index++) {
                        const element = user_comments[index];
                        $("#model").append(
                            '<option value='+element.id+'>'+element.model+'</option>'
                        )
                    }
    
                    })



             // Initialize Dropzone
        // Dropzone.autoDiscover = false;
        // var myDropzone = new Dropzone("#myDropzone", {
        //     url: "adminBannerUpload", // Specify your upload URL here
        //     autoProcessQueue: false, // We'll handle file upload manually
        //     addRemoveLinks: true, // Show remove links on uploaded files
        //     maxFilesize: 10, // Maximum file size in MB
        //     acceptedFiles: ".jpg, .jpeg, .png, .gif", // Accepted file types
        //     parallelUploads: 3, // Number of parallel uploads
        //     uploadMultiple: false ,// Disable multiple file upload,
    
   
        //     init: function() {
        //         var myDrop = this;
        //         var existingFiles = banner_images;
        //         if (existingFiles) {
        //             for (var i = 0; i < existingFiles.length; i++) {
        //                 var fileUrl = "{{ asset('/') }}"+ existingFiles[i];
        //                 var mockFile = { 
        //                     name: existingFiles[i], 
        //                     type: 'image/jpeg', 
        //                     status: myDrop.ADDED, 
        //                     accepted: true
        //                 };
        //                 // Call the default addedfile event handler
        //                 myDrop.emit("addedfile", mockFile);

        //                 // Emit the thumbnail event to trigger Dropzone thumbnail creation
        //                 myDrop.emit("thumbnail", mockFile, fileUrl);

        //                 // Call the complete event to mark the file as complete
        //                 myDrop.emit("complete", mockFile);

        //                 // Push the mockFile to the files array
        //                 myDrop.files.push(mockFile);
        //             }
        //         }    
        //         this.on("thumbnail", function(file) {
        //             // Add style to the thumbnail image
        //             file.previewElement.querySelector("[data-dz-thumbnail]").style.maxWidth = "100%";
        //             file.previewElement.querySelector("[data-dz-thumbnail]").style.maxHeight = "100%";
        //         });
        //     }
        // });








                tinymce.init({
                    selector: '.summernote',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                    tinycomments_mode: 'embedded',
                    tinycomments_author: 'Author name',
                    mergetags_list: [
                        { value: 'First.Name', title: 'First Name' },
                        { value: 'Email', title: 'Email' },
                    ]
                });

                $('.tags').tagify();


                // $("#category").on('change',function(e){
                //     var models=@json($models);
                //     $("#model").empty();
                //     var user_comments = models.filter(p => p.category == $(this).val());
                //     console.log(user_comments)
                //     $("#model").append(
                //         '<option value="">Select Model</option>'
                //     );
                //     for (let index = 0; index < user_comments.length; index++) {
                //     const element = user_comments[index];
                //     $("#model").append(
                //         '<option value='+element.id+'>'+element.model+'</option>'
                //     )
                // }
                // })
            });
        })(jQuery);

        // function previewImage(event) {
        //     var reader = new FileReader();
        //     reader.onload = function(){
        //         var output = document.getElementById('view_img');
        //         output.src = reader.result;
        //     }

        //     reader.readAsDataURL(event.target.files[0]);
        // };
    </script>
@endpush


