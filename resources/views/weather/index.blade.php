@extends('layouts.app')

@section('content')
<h2 class="mb-4"><i class="bi bi-cloud-sun"></i> Weather Monitoring</h2>

<div class="row g-3">
    <div class="col-md-4"><div class="summary-card"><div><small>Temperature</small><h2 id="temperature">--</h2></div></div></div>
    <div class="col-md-4"><div class="summary-card"><div><small>Wind</small><h2 id="wind">--</h2></div></div></div>
    <div class="col-md-4"><div class="summary-card"><div><small>Rain</small><h2 id="rain">--</h2></div></div></div>
</div>

<div class="card mt-4">
    <div class="card-header">Weather Chart</div>
    <div class="card-body"><canvas id="weatherChart"></canvas></div>
</div>
@endsection
