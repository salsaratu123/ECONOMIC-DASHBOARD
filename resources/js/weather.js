async function loadWeather(){

    const response = await fetch('/api/weather');
    const data = await response.json();

    const temp = data.current.temperature_2m;
    const wind = data.current.wind_speed_10m;
    const rain = data.current.rain;

    document.getElementById('temperature').innerHTML = temp + ' °C';
    document.getElementById('wind').innerHTML = wind + ' km/h';
    document.getElementById('rain').innerHTML = rain + ' mm';
}

loadWeather();