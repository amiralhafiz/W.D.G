<!-- author : Amir Al-Hafiz -->
<footer class="footer mt-auto py-3 text-white fixed-bottom">
    <div class="container text-center">
        <?php echo " Free Software License Ⓓ " . date("Y") . " by W.D.G "; ?>
        <span id="app-version" class="ms-1 mono small opacity-75"></span>
    </div>
</footer>

<script>
async function updateVersion() {
    try {
        const response = await fetch('/back-end/api/version.php');
        const data = await response.json();
        if (data.status === 'success') {
            document.getElementById('app-version').textContent = data.version;
        }
    } catch (error) {
        console.error('Version check failed', error);
    }
}
document.addEventListener('DOMContentLoaded', updateVersion);
</script>
