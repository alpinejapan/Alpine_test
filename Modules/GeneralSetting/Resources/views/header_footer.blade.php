@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Header and Footer Info') }}</title>
    
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Header and Footer Info') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Website Setup') }} >> {{ __('translate.Header and Footer Info') }}</p>
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
                            <form action="{{ route('admin.update-header-footer') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <!-- Product Card -->
                                        <div class="crancy-product-card">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Contact Information') }}</h4>

                                            <div class="row">

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Email') }} </label>
                                                        <input class="crancy__item-input" type="text" name="email" value="{{ $header_footer->email }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Phone') }} </label>
                                                        <input class="crancy__item-input" type="text" name="phone" value="{{ $header_footer->phone }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Address') }} </label>
                                                        <input class="crancy__item-input" type="text" name="address" value="{{ $header_footer->address }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.About us') }} </label>
                                                        <textarea class="crancy__item-input crancy__item-textarea" name="about_us" id="" cols="30" rows="5">{{ $header_footer->about_us }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Copyright') }} </label>
                                                        <input class="crancy__item-input" type="text" name="copyright" value="{{ $header_footer->copyright }}">
                                                    </div>
                                                </div>

                                            </div>

                                            <h4 class="crancy-product-card__title mg-top-30">{{ __('translate.Social Media') }}</h4>

                                            <div class="row">

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Facebook') }} </label>
                                                        <input class="crancy__item-input" type="text" name="facebook" value="{{ $header_footer->facebook }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Twitter') }} </label>
                                                        <input class="crancy__item-input" type="text" name="twitter" value="{{ $header_footer->twitter }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Linkedin') }} </label>
                                                        <input class="crancy__item-input" type="text" name="linkedin" value="{{ $header_footer->linkedin }}">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="crancy__item-form--group mg-top-form-20">
                                                        <label class="crancy__item-label">{{ __('translate.Instagram') }} </label>
                                                        <input class="crancy__item-input" type="text" name="instagram" value="{{ $header_footer->instagram }}">
                                                    </div>
                                                </div>

                                            </div>

                                            <button class="crancy-btn mg-top-25" type="submit">{{ __('translate.Update') }}</button>

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
