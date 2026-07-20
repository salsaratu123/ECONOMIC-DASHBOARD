@extends('layouts.app')

@section('content')
<h2 class="mb-4"><i class="bi bi-currency-exchange"></i> Exchange Rate Dashboard</h2>

<div class="row g-3">
    <div class="col-md-3"><div class="summary-card"><div><small>USD</small><h2 id="usd">--</h2></div></div></div>
    <div class="col-md-3"><div class="summary-card"><div><small>IDR</small><h2 id="idr">--</h2></div></div></div>
    <div class="col-md-3"><div class="summary-card"><div><small>EUR</small><h2 id="eur">--</h2></div></div></div>
    <div class="col-md-3"><div class="summary-card"><div><small>JPY</small><h2 id="jpy">--</h2></div></div></div>
</div>

<div class="card mt-4">
    <div class="card-header">Exchange Chart</div>
    <div class="card-body"><canvas id="exchangeChart"></canvas></div>
</div>
@endsection
