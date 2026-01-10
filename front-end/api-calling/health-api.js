// front-end/api-calling/health-api.js

const HEALTH_API_URL = '../back-end/api/health.php';

async function getDbStatus() {
    try {
        const response = await fetch(HEALTH_API_URL);
        const data = await response.json();
        return data.db_status || 'Error';
    } catch (error) {
        console.error('Error fetching DB status:', error);
        return 'Error';
    }
}

// Automatically update elements with class 'db-status-text' or 'db-status-badge'
async function updateDbStatusUI() {
    const status = await getDbStatus();

    document.querySelectorAll('.db-status-text').forEach(el => {
        el.textContent = status;
    });

    document.querySelectorAll('.db-status-badge').forEach(el => {
        el.textContent = status;
        if (status === 'Connected') {
            el.classList.remove('bg-danger');
            el.classList.add('bg-success');
        } else {
            el.classList.remove('bg-success');
            el.classList.add('bg-danger');
        }
    });
}

document.addEventListener('DOMContentLoaded', updateDbStatusUI);
