let weatherChart = null;
let economyChart = null;
let map = null;
let marker = null;

let selectedCountry = "Indonesia";

document.addEventListener("DOMContentLoaded", () => {

    initializeMap();

    initializeCountrySelect();

    loadDashboard();

});

function initializeCountrySelect() {

    const countrySelect = document.getElementById("countrySelect");

    if (!countrySelect) return;

    countrySelect.addEventListener("change", function () {

        selectedCountry = this.value;

        loadCountry();

    });

}

async function loadDashboard() {

    try {

        const response = await fetch("/api/dashboard");

        const data = await response.json();

        updateSummary(data);

        updateCharts(data);

        updateRightPanel(data);

        updateMap(data);

    } catch (error) {

        console.error(error);

    }

}

// ==============================
// SUMMARY
// ==============================

function updateSummary(data){

    setValue(
        "temperature",
        data.weather.current.temperature_2m + " °C"
    );

    setValue(
        "wind",
        data.weather.current.wind_speed_10m + " km/h"
    );

    setValue(
        "rain",
        data.weather.current.rain + " mm"
    );

    setValue(
        "exchange",
        "Rp " +
        Number(data.exchange.IDR)
        .toLocaleString("id-ID")
    );

    setValue(
        "gdp",
        "$" +
        (
            data.economy.gdp.value /
            1000000000000
        ).toFixed(2)
        +" T"
    );

    setValue(
        "inflation",
        data.economy
            .inflation
            .value
            .toFixed(2)
        +" %"
    );

}

// ==============================
// RIGHT PANEL
// ==============================

function updateRightPanel(data){

    setValue(
        "countryGDP",
        "$"+
        (
            data.economy.gdp.value/
            1000000000000
        ).toFixed(2)+" T"
    );

    setValue(
        "countryInflation",
        data.economy
        .inflation
        .value
        .toFixed(2)+" %"
    );

    setValue(
        "panelTemp",
        data.weather.current.temperature_2m+" °C"
    );

    setValue(
        "panelWind",
        data.weather.current.wind_speed_10m+" km/h" 
    );

    setValue(
        "panelRain",
        data.weather.current.rain+" mm"
    );

    setValue(
        "panelExchange",
        "Rp "+
        Number(data.exchange.IDR)
        .toLocaleString("id-ID")
    );

}

// ==============================
// MAP
// ==============================

function initializeMap(){

    const mapElement=document.getElementById("map");

    if(!mapElement){

        return;

    }

    map=L.map("map").setView(
        [20,0],
        2
    );

    L.tileLayer(

        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",

        {

            attribution:"© OpenStreetMap"

        }

    ).addTo(map);

}

function updateMap(data){

    if(!map){

        return;

    }

    map.eachLayer(layer=>{

        if(layer instanceof L.Marker){

            map.removeLayer(layer);

        }

    });

    const marker=L.marker(
        [-6.2,106.8]
    ).addTo(map);

    marker.bindPopup(`

        <b>🇮🇩 Indonesia</b>

        <hr>

        Temperature :
        ${data.weather.current.temperature_2m} °C

        <br>

        Wind :
        ${data.weather.current.wind_speed_10m} km/h

        <br>

        Rain :
        ${data.weather.current.rain} mm

        <br>

        GDP :
        $${(
            data.economy.gdp.value/
            1000000000000
        ).toFixed(2)} T

        <br>

        Inflation :
        ${data.economy.inflation.value.toFixed(2)}%

    `);

}

// ==============================
// CHART
// ==============================

function updateCharts(data){

    buildWeatherChart(data);

    buildEconomyChart(data);

}

function buildWeatherChart(data){

    const canvas=document.getElementById("weatherChart");

    if(!canvas){

        return;

    }

    if(weatherChart){

        weatherChart.destroy();

    }

    weatherChart=new Chart(

        canvas,

        {

            type:"bar",

            data:{

                labels:[
                    "Temperature",
                    "Wind",
                    "Rain"
                ],

                datasets:[{

                    label:"Weather",

                    data:[

                        data.weather.current.temperature_2m,

                        data.weather.current.wind_speed_10m,

                        data.weather.current.rain

                    ]

                }]

            }

        }

    );

}

function buildEconomyChart(data){

    const canvas=document.getElementById("economyChart");

    if(!canvas){

        return;

    }

    if(economyChart){

        economyChart.destroy();

    }

    economyChart=new Chart(

        canvas,

        {

            type:"bar",

            data:{

                labels:[

                    "GDP",

                    "Inflation",

                    "Population"

                ],

                datasets:[{

                    label:"Economy",

                    data:[

                        data.economy.gdp.value,

                        data.economy.inflation.value,

                        data.economy.population.value

                    ]

                }]

            }

        }

    );

}

// ==============================
// HELPER
// ==============================

function setValue(id,value){

    const element=document.getElementById(id);

    if(element){

        element.innerHTML=value;

    }

}