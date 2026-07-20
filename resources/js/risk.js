import { setText } from './utils';

export function updateRisk(risk) {
    const badge = document.getElementById('riskScore');
    if (!badge) {
        return;
    }

    const level = risk?.level ?? 'LOW';
    const color = risk?.badge ?? 'success';
    badge.textContent = level;
    badge.className = `badge bg-${color}`;
    setText('riskDetail', `Score ${risk?.score ?? 0} / 100`);
}
