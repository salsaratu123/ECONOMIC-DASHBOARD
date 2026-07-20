<div class="row mt-4 g-3">
    <div class="col-xl-6 col-md-12">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-graph-up-arrow text-primary me-2"></i> GDP & Population Trend</h6>
                <span class="text-muted small">World Bank API</span>
            </div>
            <div class="card-body px-4 pb-4 pt-2">
                <div style="position: relative; height: 260px;">
                    <canvas id="economyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-12">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-dark"><i class="bi bi-currency-exchange text-success me-2"></i> Currency & Exchange Volatility</h6>
                <span class="text-muted small">ExchangeRate API</span>
            </div>
            <div class="card-body px-4 pb-4 pt-2">
                <div style="position: relative; height: 260px;">
                    <canvas id="exchangeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>