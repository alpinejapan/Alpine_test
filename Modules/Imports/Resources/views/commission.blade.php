@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Imported List') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Imported List') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Imports') }} >> {{ __('translate.Imported List') }}</p>
@endsection

@section('body-content')
    <!-- crancy Dashboard -->
    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <div class="crancy-table crancy-table--v3 mg-top-30">
                                @if(count($commissions) > 0)
                                    <!-- <div class="d-flex justify-content-between align-items-start">
                                   
                                            <div class="d-flex justify-content-start align-items-center">
                                                <div class="me-3">
                                                    <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Commission')." ( $ )" }} * </label>
                                                        <input class="crancy__item-input" type="text" name="commission" id="commission">
                                                        @error('commission')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <button class="crancy-btn mg-top-25" type="button" id="comissionBtn">{{ __('translate.Submit') }}</button>
                                                </div>
                                                <form action="{{ route('admin.commission') }}" method="GET" id="importedListForm" novalidate>
                                                <div class="">
                                                    <label class="crancy__item-label">{{ __('translate.Year Of Made') }}</label>
                                                    <input class="crancy__item-input" type="text" name="year" id="year">
                                                    <button class="crancy-btn mg-top-25" style="margin-left:10px;" type="submit" id="yearBtn">{{ __('translate.Search') }}</button>
                                                </div>
                                                </form>
                                            </div>
                                        <div>
                                            <button class="crancy-btn delete_danger_btn" id="delete-model">Delete</button>  
                                        </div>
                                    </div> -->

                                    <div class="container" style="padding:0px">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Commission')." ( $ )" }} * </label>
                                                        <input class="crancy__item-input" type="text" name="commission" id="commission">
                                                        @error('commission')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="" style="padding-top:10px">
                                                    <button class="crancy-btn mg-top-25" type="button" id="comissionBtn">{{ __('translate.Submit') }}</button>
                                                </div>
                                            </div>
                                            <div class="col-md-2 ms-4">
                                                <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Shipping')." ( $ )" }} * </label>
                                                        <input class="crancy__item-input" type="text" name="shipping" id="shipping">
                                                        @error('Shipping')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="" style="padding-top:10px">
                                                    <button class="crancy-btn mg-top-25" type="button" id="shippingBtn">{{ __('translate.Submit') }}</button>
                                                </div>
                                            </div>

                                            <div class="col-md-5 d-flex justify-content-end align-items-center">
                                                <div>
                                                    <button class="crancy-btn delete_danger_btn" id="delete-model">Delete</button>  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Marine Insurance')." ( $ )" }} * </label>
                                                        <input class="crancy__item-input" type="text" name="marine_insurance" id="marine_insurance">
                                                        @error('marine_insurance')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="" style="padding-top:10px">
                                                    <button class="crancy-btn mg-top-25" type="button" id="MarineInsuranceBtn">{{ __('translate.Submit') }}</button>
                                                </div>
                                            </div>
                                            <div class="col-md-2 ms-4">
                                                <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Inland Inspection')." ( $ )" }} * </label>
                                                        <input class="crancy__item-input" type="text" name="inland_inspection" id="inland_inspection">
                                                        @error('inland_inspection')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="" style="padding-top:10px">
                                                    <button class="crancy-btn mg-top-25" type="button" id="InlandInspectionBtn">{{ __('translate.Submit') }}</button>
                                                </div>
                                            </div>

                                            <div class="col-md-5 d-flex justify-content-end align-items-center">
                                                <div>
                                                    <button class="crancy-btn delete_danger_btn" id="delete-model">Delete</button>  
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top:40px">
                                            <!-- <div class="col-md-4">
                                                <div class="crancy__item-form--group w-100 h-100">
                                                        <label class="crancy__item-label">{{ __('translate.Commission')." ( $ )" }} * </label>
                                                        <input class="crancy__item-input" type="text" name="commission" id="commission">
                                                        @error('commission')
                                                            <div style="color: red;">{{ $message }}</div>
                                                        @enderror
                                                </div>
                                            </div> -->
<!-- 
                                            <div class="col-md-1">
                                                <div class="" style="padding-top:10px">
                                                    <button class="crancy-btn mg-top-25" type="button" id="comissionBtn">{{ __('translate.Submit') }}</button>
                                                </div>
                                            </div> -->

                                            <!-- <div class="col-md-1">

                                            </div> -->

                                            <div class="col-md-10">
                                                <form style="padding-top:5px" action="{{ route('admin.commission') }}" method="GET" id="importedListForm" novalidate>
                                                   <div class="row"> <div class="col-lg-4">
                                                        <label class="crancy__item-label">{{ __('translate.Year Of Made') }}</label>
                                                        <input class="crancy__item-input" type="text" name="year" id="year" value="{{ request('year', '') }}">
                                                        
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label class="crancy__item-label">{{ __('translate.Make') }}</label>
                                                        <input class="crancy__item-input" type="text" name="make" id="make" value="{{ request('make', '') }}">
                                                        
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label class="crancy__item-label">{{ __('translate.Model') }}</label>
                                                        <input class="crancy__item-input" type="text" name="model" id="model" value="{{ request('model', '') }}">
                                                        
                                                    </div>
</div>
                                                </form>
                                            </div>

                                            <div class="col-md-1" style="padding-top:12px;margin-right:-5px">
                                                <button  class="crancy-btn mg-top-25" style="margin-left:10px;" type="submit" id="yearBtn">{{ __('translate.Search') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    

                                @endif
                                <div class="crancy-customer-filter">
                                    <div class="crancy-customer-filter__single crancy-customer-filter__single--csearch d-flex items-center justify-between create_new_btn_box">
                                        <div class="crancy-header__form crancy-header__form--customer create_new_btn_inline_box">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Imported List') }}</h4>
                                        </div>
                                    </div>
                                </div>

                                <!-- crancy Table -->
                                <div id="crancy-table__main_wrapper" class="dt-bootstrap5 no-footer overflow-auto">

                                    <!-- <table class="crancy-table__main crancy-table__main-v3  no-footer" id="dataTable"> -->
                                    <table class="crancy-table__main crancy-table__main-v3  no-footer" id="commission_datatable">
                                        <!-- crancy Table Head -->
                                        <thead class="crancy-table__head">
                                            <tr>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    <input type="checkbox" name="" id="masterCheckbox" class="form-control">
                                                </th>
                                                <th class="crancy-table__column-3 crancy-table__h3 sorting">
                                                    {{ __('translate.Action') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Serial') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Top Sell') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.New Arrival') }}
                                                </th>

                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Date and time') }}
                                                </th>

                                                <th class="crancy-table__column-2 crancy-table__h2 sorting" >
                                                    {{ __('translate.Auction site') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting" >
                                                    {{ __('translate.Photo') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting" >
                                                    {{ __('translate.Model') }}
                                                </th>

                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Year') }}
                                                </th>

                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Commission') }}
                                                </th>
                                                
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Transmission') }}
                                                </th>

                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Color') }}
                                                </th>

                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Bid') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Auction Reference') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Company Reference') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Model Name Reference') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Model Details') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Model Grade') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Model Grade') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Milleage') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Milleage Number') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Inspection') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Equipment') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.AWD Number') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.SW Type') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Truck Reference') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Special') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Displacement') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Displacement Number') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Start Price') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Start Price Number') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.End Price') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.End Price Number') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Color Reference') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Result') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Result Reference') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Vin') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Service Data') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Score') }}
                                                </th>

                                            </tr>
                                        </thead>
                                        <!-- crancy Table Body -->
                                        <tbody class="crancy-table__body">
                                            @foreach ($commissions as $index => $commission)
                                                <tr class="odd">
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                         <input type="checkbox" name="" id="" data-id="{{$commission->id}}" class="form-control td-checkbox-class">
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <div class="dropdown">
                                                            <button class="crancy-btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                                {{ __('translate.Action') }}
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                                <li>
                                                                    <a href="{{ route('admin.import.edit', $commission->id) }}" class=" dropdown-item">
                                                                        <i class="fas fa-edit"></i> {{ __('translate.Edit') }}
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ ++$index }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                    <input type="checkbox" class="form-control top-sell-checkbox-class" data-id="{{$commission->id}}"
                                                     {{$commission->top_sell =='1' ? 'checked' : ''}} >
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                    <input type="checkbox" class="form-control new-arrival-checkbox-class" data-id="{{$commission->id}}"
                                                     {{$commission->new_arrival =='1' ? 'checked' : ''}} >
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->datetime }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->auction_system }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <img src="{{ $commission->sorted_image_first }}" loading="lazy" width="100" height="100" alt="Product Image" class="common-image">
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $is_english ? $commission->model_name_en : $commission->model_name }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $is_english ? $commission->model_year_en : $commission->model_year }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->commission_value == "NULL" ? "-" : "$ ".$commission->commission_value }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $is_english ? $commission->transmission_en : $commission->transmission }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $is_english ? $commission->color_en : $commission->color }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->bid }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->auct_ref }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->auct_ref }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->company_ref }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->model_name_ref }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->model_details_en }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $is_english ? $commission->model_grade_en : $commission->model_grade }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $is_english ? $commission->mileage_en : $commission->mileage }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->mileage_num }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $is_english ? $commission->inspection_en : $commission->inspection }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $is_english ? $commission->equipment_en : $commission->equipment }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->awb_num }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->sw_type }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->truck_ref }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->is_special == 1 ? "Yes" : "No"  }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->displacement  }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->displacement_num  }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->start_price  }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->start_price_num  }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->end_price  }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->end_price_num  }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->color_ref  }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->result  }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->result_ref  }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->vin  }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $commission->service_data  }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $is_english ? $commission->scores_en : $commission->scores }}</h4>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                        <!-- End crancy Table Body -->
                                    </table>
                                    {{ $commissions->links() }}
                                </div>
                                <!-- End crancy Table -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End crancy Dashboard -->

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('translate.Delete Confirmation') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('translate.Are you realy want to delete this item?') }}</p>
                </div>
                <div class="modal-footer">
                    <form action="" id="item_delect_confirmation" class="delet_modal_form" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('translate.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('translate.Yes, Delete') }}</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">{{ __('translate.Delete Confirmation') }}</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <p>{{ __('translate.Are you realy want to delete this item?') }}</p>
                  </div>
                  <div class="modal-footer">
                      <form action="" id="item_delect_confirmation" class="delet_modal_form" method="POST">
                          @csrf
                          @method('DELETE')
  
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('translate.Close') }}</button>
                          <button type="submit" class="btn btn-primary">{{ __('translate.Yes, Delete') }}</button>
  
                      </form>
                  </div>
              </div>
          </div>
      </div>

@endsection

@push('js_section')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#yearBtn").on("click", function(e) {
            // Allow the form to submit naturally for the year search
            $("#importedListForm").submit();
        });

        $("#comissionBtn").on('click', function() {
            var rowCheckboxes = document.querySelectorAll('.td-checkbox-class');
            const selectedIds = Array.from(rowCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.getAttribute('data-id')); 
                
            if($("#masterCheckbox").is(':checked')){
                updateAllCommission();
            } else if((!$("#masterCheckbox").is(':checked') && selectedIds.length > 0 )) {
                updateSelectedComission(selectedIds);
            } else {
                toastr.error("Error", "Please check the Checkbox.");
            }    
        });
        $("#shippingBtn").on('click', function() {
       
            var rowCheckboxes = document.querySelectorAll('.td-checkbox-class');
            const selectedIds = Array.from(rowCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.getAttribute('data-id')); 
                
            if($("#masterCheckbox").is(':checked')){
                updateAllShipping();
            } else if((!$("#masterCheckbox").is(':checked') && selectedIds.length > 0 )) {
                updateSelectedShipping(selectedIds);
            } else {
                toastr.error("Error", "Please check the Checkbox.");
            }    
            
        });
        $("#MarineInsuranceBtn").on('click', function() {
       
            var rowCheckboxes = document.querySelectorAll('.td-checkbox-class');
            const selectedIds = Array.from(rowCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.getAttribute('data-id')); 
                
            if($("#masterCheckbox").is(':checked')){
                updateAllInsurance();
            } else if((!$("#masterCheckbox").is(':checked') && selectedIds.length > 0 )) {
                updateSelectedInsurance(selectedIds);
            } else {
                toastr.error("Error", "Please check the Checkbox.");
            }    
            
        });
        $("#InlandInspectionBtn").on('click', function() {
            var rowCheckboxes = document.querySelectorAll('.td-checkbox-class');
            const selectedIds = Array.from(rowCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.getAttribute('data-id')); 
            if($("#masterCheckbox").is(':checked')){
                updateAllInland();
            } else if((!$("#masterCheckbox").is(':checked') && selectedIds.length > 0 )) {
                updateSelectedInland(selectedIds);
            } else {
                toastr.error("Error", "Please check the Checkbox.");
            }    
            
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
         
         // Select the master checkbox and the checkboxes in the table rows
         const masterCheckbox = document.querySelector('#masterCheckbox');
         const rowCheckboxes = document.querySelectorAll('.td-checkbox-class');
        //  const rowTopSaleCheckbox = document.querySelectorAll('.top-sell-checkbox-class');

         const deleteButton = document.querySelector('#delete-model');
         // const topSellButton = document.querySelector('#top-sell-change');
         const topSellButtons = document.querySelectorAll('.top-sell-checkbox-class');
         const newArrivalButtons = document.querySelectorAll('.new-arrival-checkbox-class');

         // Add event listener to the master checkbox
         masterCheckbox.addEventListener('change', function() {
             // Set all row checkboxes to the state of the master checkbox
             rowCheckboxes.forEach(function(checkbox) {
                 checkbox.checked = masterCheckbox.checked;
             });
         });


         topSellButtons.forEach(function(topSellButton) {
                topSellButton.addEventListener('change', function() {
                // Determine if the checkbox is checked
                var check = this.checked ? 1 : 0;

                // Use jQuery to get the data-id
                var selectedId = $(this).data('id');

                $.ajax({
                    url: "{{ route('admin.change_top_sell') }}",
                    type: "POST",
                    data: { selectedIds: selectedId, check: check },
                    beforeSend: function(data) {
                        console.log("loading");
                    },
                    success: function(response) {
                        console.log(response);
                    }
                });
            });
});
newArrivalButtons.forEach(function(topSellButton) {
                topSellButton.addEventListener('change', function() {
                // Determine if the checkbox is checked
                var check = this.checked ? 1 : 0;

                // Use jQuery to get the data-id
                var selectedId = $(this).data('id');

                $.ajax({
                    url: "{{ route('admin.change_new_arrivals') }}",
                    type: "POST",
                    data: { selectedIds: selectedId, check: check },
                    beforeSend: function(data) {
                        console.log("loading");
                    },
                    success: function(response) {
                        console.log(response);
                    }
                });
            });
});


         // Handle delete button click
         deleteButton.addEventListener('click', function() {
             // Gather selected IDs
             const selectedIds = Array.from(rowCheckboxes)
                 .filter(checkbox => checkbox.checked)
                 .map(checkbox => checkbox.getAttribute('data-id'));

             if (selectedIds.length > 0) {
                 // Confirm deletion
                 if (confirm('Are you sure you want to delete the selected records?')) {


                     //Send AJAX request to delete records
                     fetch("{{ route('admin.import.bulk-delete') }}", {
                             method: 'POST',
                             headers: {
                                 'Content-Type': 'application/json',
                                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                     .getAttribute('content')
                             },
                             body: JSON.stringify({
                                 ids: selectedIds
                             })
                         })
                         .then(response => response.json())
                         .then(data => {
                             // Handle response
                             if (data.success) {
                                 // Remove deleted rows from the table
                                 rowCheckboxes.forEach(checkbox => {
                                     if (checkbox.checked) {
                                         checkbox.closest('tr').remove();
                                     }
                                     $("#masterCheckbox").prop('checked', false);
                                 });
                                 alert('Selected records deleted successfully.');
                             } else {
                                 alert('Error deleting records.');
                             }
                         })
                         .catch(error => {
                             console.error('Error:', error);
                         });
                 }
             } else {
                 alert('No records selected.');
             }
         });

         $('.td-checkbox-class').on('change',function(){
                if ($("#masterCheckbox").is(':checked')) {
                    $("#masterCheckbox").prop('checked',false);
                    rowCheckboxes.forEach(function(checkbox) {
                            checkbox.checked = false; // Uncheck each row checkbox
                    });

                }   
            })

     });


    function updateAllCommission(){
        swal({
            title: "Are you sure?",
            text: "This will Change All commission value!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    // url: "{{route('admin.store-all-comission')}}",
                    url: "{{route('admin.update-values')}}",
                    type: "POST", // Use POST for this AJAX call
                    data: {
                        value: $("#commission").val(),
                        type: "commission",
                        _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                    },
                    success: function(response) {
                        if(response.success == true) {
                            toastr.success("Success", response.message);
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        toastr.error("Error", "An error occurred while processing your request.");
                    }
            });
            } else {
              toastr.info("Your post is safe!");
            }
        });
    }

    function updateAllShipping(){
        swal({
            title: "Are you sure?",
            text: "This will Change All Shipping value!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    // url: "{{route('admin.store-all-shipping')}}",
                    url: "{{route('admin.update-values')}}",
                    type: "POST", // Use POST for this AJAX call
                    data: {
                        value: $("#shipping").val(),
                        type: "shipping",
                        _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                    },
                    success: function(response) {
                        if(response.success == true) {
                            toastr.success("Success", response.message);
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        toastr.error("Error", "An error occurred while processing your request.");
                    }
            });
            } else {
              toastr.info("Your post is safe!");
            }
        });
    }   

    function updateAllInsurance(){
        swal({
            title: "Are you sure?",
            text: "This will Change All Shipping value!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    // url: "{{route('admin.store-all-shipping')}}",
                    url: "{{route('admin.update-values')}}",
                    type: "POST", // Use POST for this AJAX call
                    data: {
                        value: $("#marine_insurance").val(),
                        type: "marine_insurance",
                        _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                    },
                    success: function(response) {
                        if(response.success == true) {
                            toastr.success("Success", response.message);
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        toastr.error("Error", "An error occurred while processing your request.");
                    }
            });
            } else {
              toastr.info("Your post is safe!");
            }
        });
    }   
    function updateAllInland(){
        swal({
            title: "Are you sure?",
            text: "This will Change All Shipping value!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    // url: "{{route('admin.store-all-shipping')}}",
                    url: "{{route('admin.update-values')}}",
                    type: "POST", // Use POST for this AJAX call
                    data: {
                        value: $("#inland_inspection").val(),
                        type: "inland_inspection",
                        _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                    },
                    success: function(response) {
                        if(response.success == true) {
                            toastr.success("Success", response.message);
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        toastr.error("Error", "An error occurred while processing your request.");
                    }
            });
            } else {
              toastr.info("Your post is safe!");
            }
        });
    }   

    function updateSelectedComission(selectedIds){
        $.ajax({
            url: "{{route('admin.store-car-comission')}}",
            type: "POST", // Use POST for this AJAX call
            data: {
                selectedIds: selectedIds,
                commission: $("#commission").val(),
                _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
            },
            success: function(response) {
                if(response.success == true) {
                    toastr.success("Success", response.message);
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                toastr.error("Error", "An error occurred while processing your request.");
            }
        });
    }
    function updateSelectedShipping(selectedIds){
    
        $.ajax({
            url: "{{route('admin.store-car-shipping')}}",
            type: "POST", // Use POST for this AJAX call
            data: {
                selectedIds: selectedIds,
                commission: $("#shipping").val(),
                _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
            },
            success: function(response) {
                if(response.success == true) {
                    toastr.success("Success", response.message);
                    // location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                toastr.error("Error", "An error occurred while processing your request.");
            }
        });
    }
    function updateSelectedInsurance(selectedIds){
    
        $.ajax({
            url: "{{route('admin.store-car-insurance')}}",
            type: "POST", // Use POST for this AJAX call
            data: {
                selectedIds: selectedIds,
                commission: $("#marine_insurance").val(),
                _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
            },
            success: function(response) {
                if(response.success == true) {
                    toastr.success("Success", response.message);
                    // location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                toastr.error("Error", "An error occurred while processing your request.");
            }
        });
    }

    function updateSelectedInland(selectedIds){
    
        $.ajax({
            url: "{{route('admin.store-car-inland')}}",
            type: "POST", // Use POST for this AJAX call
            data: {
                selectedIds: selectedIds,
                commission: $("#inland_inspection").val(),
                _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
            },
            success: function(response) {
                if(response.success == true) {
                    toastr.success("Success", response.message);
                    // location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                toastr.error("Error", "An error occurred while processing your request.");
            }
        });
    }

</script>
@endpush