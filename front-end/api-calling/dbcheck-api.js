// Override the default health behavior for this page to show more detail
async function updateDetailedDbStatus() {
    try {
        const response = await fetch('../back-end/api/health.php');
        const result = await response.json();
        const status = result.db_status;
        const message = result.status === 'success'
            ? 'Successfully established encrypted connection to Database Node.'
            : 'Protocol Failure: ' + (result.message || 'Unknown error');

        document.querySelector('.db-status-text').textContent = status.toUpperCase();
        document.querySelector('.db-status-message').textContent = message;

        const navStatus = document.querySelector('.db-status-text-nav');
        navStatus.textContent = status === 'Connected' ? 'SYSTEM ONLINE' : 'SYSTEM OFFLINE';
        navStatus.className = 'nav-link ' + (status === 'Connected' ? 'text-success' : 'text-danger');

        const bar = document.querySelector('.db-status-bar');
        bar.className = 'w-100 db-status-bar ' + (status === 'Connected' ? 'bg-success' : 'bg-danger');

        const pulse = document.querySelector('.db-status-pulse');
        pulse.className = 'status-pulse ' + (status === 'Connected' ? 'pulse-green' : 'pulse-red');

        document.querySelector('.db-status-icon-loading').classList.add('d-none');
        if (status === 'Connected') {
            document.querySelector('.db-status-icon-success').classList.remove('d-none');
            document.querySelector('.db-enter-btn').classList.remove('d-none');
        } else {
            document.querySelector('.db-status-icon-error').classList.remove('d-none');
            document.querySelector('.db-retry-btn').classList.remove('d-none');
        }
    } catch (error) {
        console.error('Health check failed', error);
    }
}
document.addEventListener('DOMContentLoaded', () => {
    updateDetailedDbStatus();
    setInterval(updateDetailedDbStatus, 1000);
});
