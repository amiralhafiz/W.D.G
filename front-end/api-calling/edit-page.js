// front-end/api-calling/edit-page.js

document.addEventListener('DOMContentLoaded', async () => {
    const editForm = document.getElementById('edit-page-form');
    const loader = document.getElementById('loading-indicator');
    const alertBox = document.getElementById('alert-container');
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    if (!editForm) return;

    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    // 1. Redirect if no ID provided in URL
    if (!id) {
        window.location.href = 'index.php';
        return;
    }

    // 2. Fetch data and populate UI
    try {
        const result = await getPageData(id);

        if (result.status === 'success') {
            const page = result.data;

            // Map API data to HTML form elements
            document.getElementById('page-id').value = page.id;
            titleInput.value = page.title;
            slugInput.value = page.slug;
            document.getElementById('description').value = page.description || '';
            document.getElementById('content').value = page.content || '';

            // Ensure status is included in form or passed
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = page.status || 'draft';
            editForm.appendChild(statusInput);

            // Hide loader and reveal form
            loader.classList.add('d-none');
            editForm.style.display = 'block';
        } else {
            throw new Error(result.message || "Page not found in registry.");
        }
    } catch (error) {
        // Handle retrieval failure by showing error in the UI
        loader.classList.add('d-none');
        alertBox.innerHTML = `
            <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger mono small">
                <i class="bi bi-exclamation-octagon me-2"></i> DATA_RETRIEVAL_ERROR: ${error.message}
                <div class="mt-3">
                    <a href="index.php" class="btn btn-sm btn-danger rounded-pill px-3">Return to Dashboard</a>
                </div>
            </div>`;
    }

    // 3. Optional: Auto-slug generation logic
    titleInput.addEventListener('input', () => {
        // Only auto-generate if the slug field is currently empty or matches a derived slug
        const generatedSlug = titleInput.value.toLowerCase().trim().replace(/[^a-z0-9\-]/g, '-').replace(/-+/g, '-');
        // Uncomment below if you want real-time slug suggestion:
        // slugInput.value = generatedSlug;
    });

    // 4. Handle Update Submission
    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        // Ensure ID is explicitly included
        data.id = id;

        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> SYNCING_CHANGES...';

        const updateResult = await updatePageData(data);

        if (updateResult.status === 'success') {
            window.location.href = 'index.php?updated=1';
        } else {
            alertBox.innerHTML = `
                <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger mono small">
                    <i class="bi bi-shield-exclamation me-2"></i> UPDATE_FAILED: ${updateResult.message}
                </div>`;

            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
            // Scroll to alert
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
