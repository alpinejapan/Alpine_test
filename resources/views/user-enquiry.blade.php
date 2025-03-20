@extends('layout4')
@section('title')
    <title>{{ __('translate.Dashboard') }}</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endsection
@section('body-content')
@php
use Carbon\Carbon;
@endphp

<main>
<section class="inner-banner">
    <div class="inner-banner-img" style=" background-image: url({{ asset($breadcrumb) }}) ;"></div>
        <div class="container">
        <div class="col-lg-12">
            <div class="inner-banner-df">
                <h1 class="inner-banner-taitel">{{ __('translate.Dashboard') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('translate.Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('translate.Dashboard') }}</li>
                    </ol>
                </nav>
            </div>
            </div>
        </div>
    </section>
    <!-- banner-part-end -->

    <!-- dashboard-part-start -->
    <section class="dashboard">
        <div class="container">
            <div class="row">
                @include('profile.sidebar')
                <div class="col-lg-9">  
                <table id="example" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>URL</th>
                            <th>Commission</th>
                            <th>Delivery Charge</th>
                            <th>Total Price</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vehicle_enquiry as $enquiry)
                        @php
                        $carbonInstance = Carbon::parse($enquiry->created_at);
                        @endphp
                        <tr>
                            <td>{{$enquiry->make}}</td>
                            <td>{{$enquiry->model}}</td>
                            <td>{{$enquiry->url_link}}</td>
                            <td>{{$enquiry->comission}}</td>
                            <td>{{$enquiry->delivery_charge}}</td>
                            <td>{{$enquiry->total_car_price}}</td>
                            <td>{{$carbonInstance->format('Y-m-d') }}</td>
                        </tr>
                      @endforeach  
                    </tbody>
                </table>    
                </div>
            </div>
        </div>
        </div>
    </section>

    @include('profile.logout')
</main>    
@endsection

@push('js_section')
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script>
        (function($) {
            "use strict"
            $("#example").DataTable();
        })(jQuery);
    </script>
@endpush
