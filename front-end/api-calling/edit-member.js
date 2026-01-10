// front-end/api-calling/edit-member.js

document.addEventListener('DOMContentLoaded', async () => {
    const editForm = document.getElementById('edit-member-form');
    if (!editForm) return;

    const userId = document.getElementById('form-user-id').value;
    const result = await getMember(userId);
    
    const loadingIndicator = document.getElementById('loading-indicator');
    
    if (result.status === 'success') {
        document.getElementById('display-user-id').textContent = userId.substring(0, 8) + '...';
        document.getElementById('fullname').value = result.data.fullname;
        document.getElementById('phonenumber').value = result.data.phonenumber;
        document.getElementById('email').value = result.data.email;
        
        if (loadingIndicator) loadingIndicator.style.display = 'none';
        editForm.style.display = 'block';
    } else {
        window.location.href = 'members.php';
    }

    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Updating...';

        const updateResult = await updateMember(data);
        
        if (updateResult.status === 'success') {
            window.location.href = 'members.php';
        } else {
            const alertContainer = document.getElementById('alert-container');
            if (alertContainer) {
                alertContainer.innerHTML = `
                    <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger animate-up mono small" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> ERROR: ${updateResult.message}
                    </div>
                `;
            }
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
        }
    });
});
