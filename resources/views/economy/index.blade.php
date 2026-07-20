@extends('layouts.app')

@section('content')
<h2 class="mb-4"><i class="bi bi-graph-up-arrow"></i> Economy Dashboard</h2>

<div class="row g-3">
    <div class="col-md-4"><div class="summary-card"><div><small>GDP</small><h2 id="gdp">--</h2></div></div></div>
    <div class="col-md-4"><div class="summary-card"><div><small>Inflation</small><h2 id="inflation">--</h2></div></div></div>
    <div class="col-md-4"><div class="summary-card"><div><small>Population</small><h2 id="population">--</h2></div></div></div>
</div>

<div class="card mt-4">
    <div class="card-header">Economy Chart</div>
    <div class="card-body"><canvas id="economyChart"></canvas></div>
</div>
@endsection
