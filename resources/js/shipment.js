export function updateShipments(shipments) {
    const target = document.getElementById('shipmentTable');
    if (!target) {
        return;
    }

    const rows = shipments.length ? shipments : [];

    if (!rows.length) {
        target.innerHTML = `
            <tr>
                <td colspan="6">
                    <div class="empty-state">No shipment records available.</div>
                </td>
            </tr>
        `;
        return;
    }
    target.innerHTML = rows.map((shipment) => `
        <tr>
            <td>${shipment.container_number}</td>
            <td>${shipment.origin_port}<br><small>${shipment.origin_country}</small></td>
            <td>${shipment.destination_port}<br><small>${shipment.destination_country}</small></td>
            <td>${formatDate(shipment.eta)}</td>
            <td><span class="badge bg-primary">${shipment.status}</span></td>
            <td><span class="badge bg-${riskColor(shipment.risk_level)}">${shipment.risk_level}</span></td>
        </tr>
    `).join('');
}

function riskColor(level) {
    return level === 'HIGH' ? 'danger' : (level === 'MEDIUM' ? 'warning' : 'success');
}

function formatDate(value) {
    return value ? new Date(value).toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: 'numeric' }) : '-';
}
