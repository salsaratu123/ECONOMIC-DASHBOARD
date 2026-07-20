@extends('layouts.app')

@section('content')
<h2 class="mb-4"><i class="bi bi-globe"></i> Country Intelligence</h2>
<div class="row g-3">
    <div class="col-lg-5">@include('dashboard.rightpanel')</div>
    <div class="col-lg-7">@include('dashboard.map')</div>
</div>
@endsection
