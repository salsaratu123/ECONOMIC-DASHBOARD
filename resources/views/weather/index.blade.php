@extends('layouts.app')

@section('content')

<h2 class="mb-4">🌦 Weather Monitoring</h2>

<div class="row g-4">

    <div class="col-md-4">

        <div class="card">

            <div class="card-body text-center">

                <h5>Temperature</h5>

                <h1 id="temperature">--</h1>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card">

            <div class="card-body text-center">

                <h5>Wind</h5>

                <h1 id="wind">--</h1>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card">

            <div class="card-body text-center">

                <h5>Rain</h5>

                <h1 id="rain">--</h1>

            </div>

        </div>

    </div>

</div>

<div class="card mt-4">

    <div class="card-header">

        Weather Chart

    </div>

    <div class="card-body">

        <canvas id="weatherChart"></canvas>

    </div>

</div>

@endsection