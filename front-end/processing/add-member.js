document.getElementById('add-member-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    const submitBtn = e.target.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';

    const result = await addMember(data);

    if (result.status === 'success') {
        document.getElementById('alert-container').innerHTML = `
            <div class="alert alert-success bg-success bg-opacity-10 border-success border-opacity-25 text-success animate-up mono small" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> DATA INFILTRATION SUCCESSFUL
            </div>
        `;
        e.target.reset();
        // Update counts
        if (typeof updateCounts === 'function') updateCounts();
    } else {
        document.getElementById('alert-container').innerHTML = `
            <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger animate-up mono small" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> ERROR: ${result.message}
            </div>
        `;
    }

    submitBtn.disabled = false;
    submitBtn.innerHTML = '<i class="bi bi-plus-circle me-2"></i> Register Member';
});
