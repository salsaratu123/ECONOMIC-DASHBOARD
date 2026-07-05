@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4">
        💱 Exchange Rate Dashboard
    </h2>

    <div class="row g-4">

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>USD</h6>
                    <h2 id="usd">-</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>IDR</h6>
                    <h2 id="idr">-</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>EUR</h6>
                    <h2 id="eur">-</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6>JPY</h6>
                    <h2 id="jpy">-</h2>
                </div>
            </div>
        </div>

    </div>

</div>

<script src="{{ Vite::asset('resources/js/exchange.js') }}"></script>

@endsection