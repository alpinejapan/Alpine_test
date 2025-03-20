@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Model List') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Model List') }}</h3>
    <a href="{{route('admin.dashboard')}}"><p class="crancy-header__text">{{ __('translate.Models') }} >> {{ __('translate.Model List') }}</p></a>
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
                                    <div class="crancy-customer-filter__single crancy-customer-filter__single--csearch d-flex items-center justify-between create_new_btn_box">
                                        <div class="crancy-header__form crancy-header__form--customer create_new_btn_inline_box  d-flex  justify-between">
                                            <h4 class="crancy-product-card__title">{{ __('translate.Model List') }}</h4>
                                            <div class="d-flex gap-3">
                                            <a href="{{ route('admin.models.create') }}" class="crancy-btn text-nowrap"><span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                    <path d="M8 1V15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                    <path d="M1 8H15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                </svg>
                                                </span> {{ __('translate.Create New') }}</a>
                                            <button class="crancy-btn delete_danger_btn" id="delete-model">Delete</button>  
                                            </div>  
                                        </div>
                                    </div>
                                </div>

                                <!-- crancy Table -->
                                <div id="crancy-table__main_wrapper" class=" dt-bootstrap5 no-footer">

                                    <table class="crancy-table__main crancy-table__main-v3 no-footer" id="dataTable">
                                        <!-- crancy Table Head -->
                                        <thead class="crancy-table__head">
                                            <tr>

                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                   <input type="checkbox" name="" id="masterCheckbox" class="form-control">
                                                </th>

                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.ID') }}
                                                </th>

                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Category') }}
                                                </th>

                                                <th class="crancy-table__column-2 crancy-table__h2 sorting">
                                                    {{ __('translate.Model') }}
                                                </th>

                                                <th class="crancy-table__column-3 crancy-table__h3 sorting">
                                                    {{ __('translate.Image') }}
                                                </th>
                                                <th class="crancy-table__column-3 crancy-table__h3 sorting">
                                                    {{ __('translate.Actions') }}
                                                </th>

                                            </tr>
                                        </thead>
                                        <!-- crancy Table Body -->
                                        <tbody class="crancy-table__body">
                                        @foreach ($models_cars as $index => $models)
                                                <tr class="odd">

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                         <input type="checkbox" name="" id="" data-id="{{$models->id}}" class="form-control td-checkbox-class">
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ ++$index }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ !empty($models->getBrand) ? $models->getBrand->name :'--' }}</h4>
                                                    </td>

                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                        <h4 class="crancy-table__product-title">{{ $models->model }}</h4>
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                    <img src="{{ asset('Cars/' . $models->image) }}"  width="100" height="100" alt="Product Image" class="common-image">
                                                    </td>
                                                    <td class="crancy-table__column-2 crancy-table__data-2">
                                                    <a href="{{ route('admin.models.edit', ['model' => $models->id] ) }}" title="{{ __('translate.Edit') }}" style="color:grey;"><i class="fas fa-edit"></i></a>
                                                        <a onclick="itemDeleteConfrimation({{ $models->id }})" href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal" title="{{ __('translate.Delete') }}" ><i class="fas fa-trash"></i></a>
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
        "use strict"
        function itemDeleteConfrimation(id){
            var deleteUrl = "{{ route('admin.models.destroy', ':id') }}";
            deleteUrl = deleteUrl.replace(':id', id);
            document.getElementById("item_delect_confirmation").setAttribute("action", deleteUrl);
        }


        document.addEventListener('DOMContentLoaded', function() {
            // Select the master checkbox and the checkboxes in the table rows
            const masterCheckbox = document.querySelector('#masterCheckbox');
            const rowCheckboxes = document.querySelectorAll('.td-checkbox-class');
            const deleteButton = document.querySelector('#delete-model');

            // Add event listener to the master checkbox
            masterCheckbox.addEventListener('change', function() {
                // Set all row checkboxes to the state of the master checkbox
                rowCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = masterCheckbox.checked;
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
                fetch('/delete-models', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ ids: selectedIds })
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
                            $("#masterCheckbox").prop('checked',false);
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

});

        


    </script>
@endpush
