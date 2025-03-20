@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Vehicle Enquiry') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Vehicle Enquiry') }}</h3>
    <a href="{{route('admin.dashboard')}}"><p class="crancy-header__text">{{ __('translate.Dashboard') }} >> {{ __('translate.Vehicle Enquiry') }}</p></a>
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

                                <div class="crancy-customer-filter">
                                    <div class="crancy-customer-filter__single crancy-customer-filter__single--csearch">
                                        <div class="crancy-header__form crancy-header__form--customer">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Vehicle En`quiry') }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <form  action="{{ route('admin.vehicle-enquiry') }}" method="GET" id="" novalidate>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="">
                                            <label class="crancy__item-label">{{ __('translate.Start Date') }}</label>
                                            <input class="crancy__item-input" type="date" name="start_year" id="start_year" value="{{ $filters['start_year'] ?? '' }}">     
                                        </div>
                                        <div class="">
                                            <label class="crancy__item-label">{{ __('translate.Make') }}</label>
                                                <select name="make" id="make" class="crancy__item-input">
                                                    <option value="">Choose Brand</option>
                                                    @foreach($brands as $brand)
                                                      <option value="{{$brand->slug}}" {{ ($filters['make'] ?? '') == $brand->slug ? 'selected' : '' }}>{{$brand->brand_name}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="">  
                                            <label class="crancy__item-label">{{ __('translate.Model') }}</label>
                                            <select name="model" id="model" class="crancy__item-input"  data-selected="{{ $filters['model'] ?? '' }}">
                                                <option value="">Choose Model</option>
                                            </select>
                                        </div>
                                        <div class="">
                                            <label class="crancy__item-label">{{ __('translate.End Date') }}</label>
                                            <input class="crancy__item-input" type="date" name="end_year" id="end_year"  value="{{ $filters['end_year'] ?? '' }}">
                                        </div>
                                        <div class="col-md-2">
                                            <button  class="crancy-btn mg-top-25" style="margin-left:10px;" type="submit" id="yearBtn">{{ __('translate.Search') }}</button>
                                        </div>
                                    </div>
                                </form>    


                                <!-- crancy Table -->
                                <div id="crancy-table__main_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">

                                    <table class="crancy-table__main-v3 no-footer" id="dataTable">
                                        <!-- crancy Table Head -->
                                        <thead class="crancy-table__head">
                                            <tr>    
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Name') }}
                                                </th>

                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Email') }}
                                                </th>

                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Phone') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Brand') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Model') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Url') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Commission') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Delivery Charge') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Total Price') }}
                                                </th>
                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Created') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <!-- crancy Table Body -->
                                        <tbody class="crancy-table__body">
                                            @foreach ($vehicle_enquiry as $index => $vehicle)
                                                <tr class="odd">
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ html_decode($vehicle->name) }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ html_decode($vehicle->email) }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ html_decode($vehicle->phone) }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ html_decode(isset($vehicle->make) ? $vehicle->make : '--' ) }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ html_decode(isset($vehicle->model) ? $vehicle->model : '--') }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ html_decode(isset($vehicle->url_link) ? $vehicle->url_link : '--') }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ html_decode(isset($vehicle->commission) ? '$'.$vehicle->commission : '$0' ) }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ html_decode(isset($vehicle->delivery_charge) ? $vehicle->delivery_charge : '$0') }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ html_decode(isset($vehicle->total_car_price) ? $vehicle->total_car_price : '$0') }}</h4>
                                                    </td>



                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $vehicle->created_at->format('h:iA, d F Y') }}</h4>
                                                    </td>
                                                </tr>
                                            @endforeach    

                                        </tbody>
                                        <!-- End crancy Table Body -->
                                    </table>
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
@endsection

@push('js_section')
    <script>
         $(()=>{
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
         });

         //   add dynamic class for data table
        $("#dataTable").parent().addClass("overflow-auto");

        if($("#make").val()!=""){
            append_model_val($("#make").val());
        }
        
         });
        "use strict"
        function itemDeleteConfrimation(id){
            $("#item_delect_confirmation").attr("action",'{{ url("admin/delete-contact-message/") }}'+"/"+id)
        }
        $('#make').on('change',function(){
            append_model_val($(this).val());
        })

        function append_model_val(brand){
            $.ajax({
                url:"{{route('admin.get-enquiry-brands')}}",
                type:"POST",
                datatype:"JSON",
                data:{"brand":brand},
                beforeSend:function(response){
                    console.log("loading")
                },
                success:function(data){
                    const selectedModel = "{{ $filters['model'] ?? '' }}";
                    var brands=data.message;
                    for (let index = 0; index < brands.length; index++) {
                        const element = brands[index];
                        $("#model").append(`
                            <option value="${element.model}" ${selectedModel == element.model ? 'selected' : ''}>
                                ${element.model}
                            </option>
                        `);
                    }

                }   
            })
        }
    </script>
@endpush
