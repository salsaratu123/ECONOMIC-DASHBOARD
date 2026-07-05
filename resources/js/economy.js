async function loadEconomy() {

    const response = await fetch('/api/economy');

    const data = await response.json();

    document.getElementById('gdp').innerHTML =
        data.gdp ? Number(data.gdp.value).toLocaleString() : "-";

    document.getElementById('inflation').innerHTML =
        data.inflation ? data.inflation.value + " %" : "-";

    document.getElementById('population').innerHTML =
        data.population ? Number(data.population.value).toLocaleString() : "-";

}

loadEconomy();