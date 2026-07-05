@extends('layouts.app')

@section('content')

<h2 class="mb-4">
💰 Economy Dashboard
</h2>

<div class="row">

    <div class="col-md-4">

        <div class="card">

            <div class="card-body text-center">

                <h5>GDP</h5>

                <h3 id="gdp">--</h3>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card">

            <div class="card-body text-center">

                <h5>Inflation</h5>

                <h3 id="inflation">--</h3>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card">

            <div class="card-body text-center">

                <h5>Population</h5>

                <h3 id="population">--</h3>

            </div>

        </div>

    </div>

</div>
<script>

async function loadEconomy(){

    const response = await fetch('/api/economy');

    const data = await response.json();

    console.log(data);

    document.getElementById("gdp").innerHTML =
        Number(data.gdp.value).toLocaleString();

    document.getElementById("inflation").innerHTML =
        data.inflation.value.toFixed(2)+" %";

    document.getElementById("population").innerHTML =
        Number(data.population.value).toLocaleString();

}

loadEconomy();

</script>

@endsection