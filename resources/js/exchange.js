async function loadExchange() {

    const response = await fetch('/api/exchange');
    const data = await response.json();

    document.getElementById('usd').innerHTML = data.USD;

    document.getElementById('idr').innerHTML =
        Number(data.IDR).toLocaleString('id-ID', {
            maximumFractionDigits: 2
        });

    document.getElementById('eur').innerHTML =
        data.EUR.toFixed(4);

    document.getElementById('jpy').innerHTML =
        data.JPY.toFixed(2);
}

loadExchange();