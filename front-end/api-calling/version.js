async function updateVersion() {
    try {
        const response = await fetch('../back-end/api/version.php');
        const data = await response.json();
        if (data.status === 'success') {
            document.getElementById('app-version').textContent = data.version;
        }
    } catch (error) {
        console.error('Version check failed', error);
    }
}
document.addEventListener('DOMContentLoaded', updateVersion);
