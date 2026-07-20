<nav class="navbar navbar-expand-lg bg-white shadow-sm rounded-4 px-4 py-3 mb-4 border border-light">
    <div class="container-fluid">
        <div class="row w-100 align-items-center g-3">
            <div class="col-lg-5">
                <div class="input-group border rounded-3 overflow-hidden">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                    <input id="countrySearch" type="text" class="form-control border-0 px-2" placeholder="Cari nama atau kode ISO negara...">
                </div>
            </div>
            <div class="col-lg-3">
                <select id="countrySelect" class="form-select border rounded-3 fw-medium text-secondary">
                    <option value="IDN" selected>Loading countries...</option>
                </select>
            </div>
            <div class="col-lg-2 text-center">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold" id="liveStatus">
                    LIVE ANALYTICS
                </span>
            </div>
            <div class="col-lg-2 text-end d-flex align-items-center justify-content-end gap-2">
                <button class="btn btn-light border btn-sm p-2 rounded-3" id="darkModeToggle" type="button" title="Dark mode">
                    <i class="bi bi-moon-stars text-secondary"></i>
                </button>
                <div class="btn-group">
                    <button class="btn btn-light border btn-sm p-2" id="exportPdfBtn" type="button" title="Export PDF">
                        <i class="bi bi-file-earmark-pdf text-danger"></i>
                    </button>
                    <button class="btn btn-light border btn-sm p-2" id="exportExcelBtn" type="button" title="Export Excel">
                        <i class="bi bi-file-earmark-spreadsheet text-success"></i>
                    </button>
                </div>
                <div class="position-relative ms-2">
                    <i class="bi bi-bell text-secondary fs-5"></i>
                </div>
            </div>
        </div>
    </div>
</nav>