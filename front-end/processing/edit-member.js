document.addEventListener('DOMContentLoaded', async () => {
    const userId = '<?= htmlspecialchars($user) ?>';
    const result = await getMember(userId);

    if (result.status === 'success') {
        document.getElementById('display-user-id').textContent = userId.substring(0, 8) + '...';
        document.getElementById('fullname').value = result.data.fullname;
        document.getElementById('phonenumber').value = result.data.phonenumber;
        document.getElementById('email').value = result.data.email;

        document.getElementById('loading-indicator').style.display = 'none';
        document.getElementById('edit-member-form').style.display = 'block';
    } else {
        window.location.href = 'members.php';
    }
});

document.getElementById('edit-member-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Updating...';

    const result = await updateMember(data);

    if (result.status === 'success') {
        window.location.href = 'members.php';
    } else {
        document.getElementById('alert-container').innerHTML = `
            <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger animate-up mono small" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> ERROR: ${result.message}
            </div>
        `;
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-save me-2"></i> Push Changes';
    }
});
