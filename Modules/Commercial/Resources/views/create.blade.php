@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Create Commercial') }}</title>
@endsection

@section('body-header') 
    <h3 class="crancy-header__title m-0">{{ __('translate.Create Commercial') }}</h3>
    <a href="{{route('admin.dashboard')}}"><p class="crancy-header__text">{{ __('translate.Commercial') }} >> {{ __('translate.Create Commercial') }}</p></a>
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
                            <form action="{{ route('admin.commercial.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <!-- Product Card -->
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ __('translate.Create Commercial') }}</h4>

                                                <a href="{{ route('admin.categories.index') }}" class="crancy-btn "><i class="fa fa-list"></i> {{ __('translate.Commercial List') }}</a>
                                            </div>


                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Category') }} * </label>
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
                                                </div>
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
                                                              <option value="{{$brand->id}}">{{$brand->slug}}</option>
                                                           @endforeach  
                                                        </select>
                                                        @error('maker')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div> 
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Year Of Made') }} </label>
                                                        <input class="crancy__item-input" type="text" name="year_of_made" id="year_of_made">
                                                        @error('year_of_made')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mg-top-30">
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
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Chassis number') }}  </label>
                                                        <input class="crancy__item-input" type="text" name="chassis_number" id="chassis_number">
                                                        @error('chassis_number')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Serial Number') }} </label>
                                                        <input class="crancy__item-input" type="text" name="serial_number" id="serial_number">
                                                        @error('serial_number')
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
                                                        <label class="crancy__item-label">{{ __('translate.Kilometers') }} </label>
                                                        <input class="crancy__item-input" type="text" name="kilometers" id="kilometers">
                                                        @error('kilometers')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Engine type') }} </label>
                                                        <input class="crancy__item-input" type="text" name="engine_type" id="engine_type">
                                                        @error('engine_type')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div> 
                                            </div>
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Transmission type') }} </label>
                                                        <input class="crancy__item-input" type="text" name="transmission_type" id="transmission_type">
                                                        @error('transmission_type')
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
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Dimensions') }}  </label>
                                                        <input class="crancy__item-input" type="text" name="dimensions" id="dimensions">
                                                        @error('dimensions')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>   
                                            </div>
                                            <div class="row mg-top-30">
                                                
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Price($)') }} </label>
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
                                            <div class="row mg-top-30">
                                               
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Remarks') }}  </label>
                                                        <textarea class="crancy__item-input crancy__item-textarea summernote"  name="remarks" id="remarks">
                                                            {{ old('remarks') }}
                                                        </textarea>
                                                        @error('remarks')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Sell Points') }}  </label>
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
                                                        <label class="crancy__item-label">{{__('North America market')}} </label>
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
                                                        <label class="crancy__item-label">{{__('Russia market')}} </label>
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
                
                $("#category").on('change',function(e){
                    var models=@json($models);
                    $("#model").empty();
                    var user_comments = models.filter(p => p.category == $(this).val());
                    console.log(user_comments)
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


