@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Edit Imported Data') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Edit Imported Data') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Imports') }} >> {{ __('translate.Imported List') }} >> {{ __('translate.Edit Imported Data') }}</p>
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
                            <form action="{{ route('admin.import.update',$commissions->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-12 mg-top-30">
                                        <!-- Product Card -->
                                        <div class="crancy-product-card">
                                            <div class="create_new_btn_inline_box">
                                                <h4 class="crancy-product-card__title">{{ __('translate.Edit Imported Data') }}</h4>

                                                <a href="{{ route('admin.commission') }}" class="crancy-btn "><i class="fa fa-list"></i> {{ __('translate.Imported List') }}</a>
                                            </div>
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Date') }}</label>
                                                        <input class="crancy__item-input" type="text" name="date" id="date" value="{{$commissions->date}}">
                                                        @error('date')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Bid') }}</label>
                                                        <input class="crancy__item-input" type="text" name="bid" id="bid" value="{{$commissions->bid}}">
                                                        @error('bid')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Auction Reference') }}</label>
                                                        <input class="crancy__item-input" type="text" name="auct_ref" id="auct_ref" value="{{$commissions->auct_ref}}">
                                                        @error('auct_ref')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>                                        
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Auction site') }}</label>
                                                        <input class="crancy__item-input" type="text" name="auction_system" id="auction_system" value="{{$commissions->auction_system}}">
                                                        @error('auction_system')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Date and time') }}</label>
                                                        <input class="crancy__item-input" type="text" name="datetime" id="datetime" value="{{$commissions->datetime}}">
                                                        @error('datetime')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Commission') }}</label>
                                                        <input class="crancy__item-input" type="text" name="commission_value" id="commission_value" value="{{$commissions->commission_value}}">
                                                        @error('commission_value')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>                                        
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Company') }}</label>
                                                        <input class="crancy__item-input" type="text" name="company" id="company" value="{{$commissions->company}}">
                                                        @error('company')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Company English') }}</label>
                                                        <input class="crancy__item-input" type="text" name="company_en" id="company_en" value="{{$commissions->company_en}}">
                                                        @error('company_en')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Company Reference') }}</label>
                                                        <input class="crancy__item-input" type="text" name="company_ref" id="company_ref" value="{{$commissions->company_ref}}">
                                                        @error('company_ref')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>                                        
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Model') }}</label>
                                                        <input class="crancy__item-input" type="text" name="model_name" id="model_name" value="{{$commissions->model_name}}">
                                                        @error('model_name')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Model English') }}</label>
                                                        <input class="crancy__item-input" type="text" name="model_name_en" id="model_name_en" value="{{$commissions->model_name_en}}">
                                                        @error('model_name_en')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Model Name Reference') }}</label>
                                                        <input class="crancy__item-input" type="text" name="model_name_ref" id="model_name_ref" value="{{$commissions->model_name_ref}}">
                                                        @error('model_name_ref')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>                                        
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Model Year') }}</label>
                                                        <input class="crancy__item-input" type="text" name="model_year" id="model_year" value="{{$commissions->model_year}}">
                                                        @error('model_year')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Model Year English') }}</label>
                                                        <input class="crancy__item-input" type="text" name="model_year_en" id="model_year_en" value="{{$commissions->model_year_en}}">
                                                        @error('model_year_en')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Model Details') }}</label>
                                                        <input class="crancy__item-input" type="text" name="model_details_en" id="model_details_en" value="{{$commissions->model_details_en}}">
                                                        @error('model_details_en')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>                                        
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Model Grade') }}</label>
                                                        <input class="crancy__item-input" type="text" name="model_grade" id="model_grade" value="{{$commissions->model_grade}}">
                                                        @error('model_grade')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Model Grade English') }}</label>
                                                        <input class="crancy__item-input" type="text" name="model_grade_en" id="model_grade_en" value="{{$commissions->model_grade_en}}">
                                                        @error('model_grade_en')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.AWD Number') }}</label>
                                                        <input class="crancy__item-input" type="text" name="awd_num" id="awd_num" value="{{$commissions->awd_num}}">
                                                        @error('awd_num')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>                                        
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Model Type') }}</label>
                                                        <input class="crancy__item-input" type="text" name="model_type" id="model_type" value="{{$commissions->model_type}}">
                                                        @error('model_type')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Model Type English') }}</label>
                                                        <input class="crancy__item-input" type="text" name="model_type_en" id="model_type_en" value="{{$commissions->model_type_en}}">
                                                        @error('model_type_en')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.SW Type') }}</label>
                                                        <input class="crancy__item-input" type="text" name="sw_type" id="sw_type" value="{{$commissions->sw_type}}">
                                                        @error('sw_type')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>                                        
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Mileage') }}</label>
                                                        <input class="crancy__item-input" type="text" name="mileage" id="mileage" value="{{$commissions->mileage}}">
                                                        @error('mileage')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Mileage English') }}</label>
                                                        <input class="crancy__item-input" type="text" name="mileage_en" id="mileage_en" value="{{$commissions->mileage_en}}">
                                                        @error('mileage_en')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Mileage Number') }}</label>
                                                        <input class="crancy__item-input" type="text" name="mileage_num" id="mileage_num" value="{{$commissions->mileage_num}}">
                                                        @error('mileage_num')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>                                        
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Inspection') }}</label>
                                                        <input class="crancy__item-input" type="text" name="inspection" id="inspection" value="{{$commissions->inspection}}">
                                                        @error('inspection')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Inspection English') }}</label>
                                                        <input class="crancy__item-input" type="text" name="inspection_en" id="inspection_en" value="{{$commissions->inspection_en}}">
                                                        @error('inspection_en')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Special') }}</label>
                                                        <input class="crancy__item-input" type="text" name="is_special" id="is_special" value="{{$commissions->is_special}}">
                                                        @error('is_special')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>                                        
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Equipment') }}</label>
                                                        <input class="crancy__item-input" type="text" name="equipment" id="equipment" value="{{$commissions->equipment}}">
                                                        @error('equipment')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Equipment English') }}</label>
                                                        <input class="crancy__item-input" type="text" name="equipment_en" id="equipment_en" value="{{$commissions->equipment_en}}">
                                                        @error('equipment_en')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Transmission') }}</label>
                                                        <input class="crancy__item-input" type="text" name="transmission" id="transmission" value="{{$commissions->transmission}}">
                                                        @error('transmission')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>                                        
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Transmission English') }}</label>
                                                        <input class="crancy__item-input" type="text" name="transmission_en" id="transmission_en" value="{{$commissions->transmission_en}}">
                                                        @error('transmission_en')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Displacement') }}</label>
                                                        <input class="crancy__item-input" type="text" name="displacement" id="displacement" value="{{$commissions->displacement}}">
                                                        @error('displacement')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Displacement Number') }}</label>
                                                        <input class="crancy__item-input" type="text" name="displacement_num" id="displacement_num" value="{{$commissions->displacement_num}}">
                                                        @error('displacement_num')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>                                        
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Start Price') }}</label>
                                                        <input class="crancy__item-input" type="text" name="start_price" id="start_price" value="{{$commissions->start_price}}">
                                                        @error('start_price')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Start Price Number') }}</label>
                                                        <input class="crancy__item-input" type="text" name="start_price_num" id="start_price_num" value="{{$commissions->start_price_num}}">
                                                        @error('start_price_num')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.End Price') }}</label>
                                                        <input class="crancy__item-input" type="text" name="end_price" id="end_price" value="{{$commissions->end_price}}">
                                                        @error('end_price')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>                                        
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Auction site') }}</label>
                                                        <input class="crancy__item-input" type="text" name="end_price_num" id="end_price_num" value="{{$commissions->end_price_num}}">
                                                        @error('end_price_num')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Color') }}</label>
                                                        <input class="crancy__item-input" type="text" name="color" id="color" value="{{$commissions->color}}">
                                                        @error('color')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Color English') }}</label>
                                                        <input class="crancy__item-input" type="text" name="color_en" id="color_en" value="{{$commissions->color_en}}">
                                                        @error('color_en')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>                                        
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Color Reference') }}</label>
                                                        <input class="crancy__item-input" type="text" name="color_ref" id="color_ref" value="{{$commissions->color_ref}}">
                                                        @error('color_ref')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Score') }}</label>
                                                        <input class="crancy__item-input" type="text" name="scores" id="scores" value="{{$commissions->scores}}">
                                                        @error('scores')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Score English') }}</label>
                                                        <input class="crancy__item-input" type="text" name="scores_en" id="scores_en" value="{{$commissions->scores_en}}">
                                                        @error('scores_en')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>                                        
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Result') }}</label>
                                                        <input class="crancy__item-input" type="text" name="result" id="result" value="{{$commissions->result}}">
                                                        @error('result')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Result Reference') }}</label>
                                                        <input class="crancy__item-input" type="text" name="result_ref" id="result_ref" value="{{$commissions->result_ref}}">
                                                        @error('result_ref')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Vin') }}</label>
                                                        <input class="crancy__item-input" type="text" name="vin" id="vin" value="{{$commissions->vin}}">
                                                        @error('vin')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>                                        
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Service Data') }}</label>
                                                        <input class="crancy__item-input" type="text" name="service_data" id="service_data" value="{{$commissions->service_data}}">
                                                        @error('service_data')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Parsed Data') }}</label>
                                                        <input class="crancy__item-input" type="text" name="parsed_data" id="parsed_data" value="{{$commissions->parsed_data}}">
                                                        @error('parsed_data')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Parsed Data English') }}</label>
                                                        <input class="crancy__item-input" type="text" name="parsed_data_en" id="parsed_data_en" value="{{$commissions->parsed_data_en}}">
                                                        @error('parsed_data_en')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>                                    
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Parsed Data Russia') }}</label>
                                                        <input class="crancy__item-input" type="text" name="parsed_data_ru" id="parsed_data_ru" value="{{$commissions->parsed_data_ru}}">
                                                        @error('parsed_data_ru')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Download Time') }}</label>
                                                        <input class="crancy__item-input" type="text" name="download_time" id="download_time" value="{{$commissions->download_time}}">
                                                        @error('download_time')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Pictures Downloaded') }}</label>
                                                        <input class="crancy__item-input" type="text" name="pics_downloaded" id="pics_downloaded" value="{{$commissions->pics_downloaded}}">
                                                        @error('pics_downloaded')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>    
                                    
                                                        
                                            <div class="row mg-top-30">
                                                <div class="col-4">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Pictures') }}</label>
                                                        <textarea name="pictures" id="pictures">
                                                            {{$commissions->pictures}}
                                                        </textarea>
                                                        {{-- <input class="crancy__item-input" type="text" name="pictures" id="pictures" value="{{$commissions->pictures}}"> --}}
                                                        @error('pictures')
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
                                <!-- End Dashboard Inner -->
                            </form>
                        </div>
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
                output.src = "{{ asset('uploads/website-images/logo2-2024-09-11-06-13-21-1394.png') }}";
            }

            reader.readAsDataURL(event.target.files[0]);
        };
    </script>
@endpush


