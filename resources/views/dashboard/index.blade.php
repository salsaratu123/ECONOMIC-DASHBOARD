@extends('layouts.app')

@section('content')

@include('dashboard.navbar')

@include('dashboard.summary')

<div class="row mt-4 g-4">
    <div class="col-lg-8">
        @include('dashboard.map')
        @include('dashboard.charts')
    </div>

    <div class="col-lg-4">
        @include('dashboard.rightpanel')
        @include('dashboard.news')
    </div>
</div>

@include('dashboard.shipment')

@endsection