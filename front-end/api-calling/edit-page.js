// front-end/api-calling/edit-page.js

document.addEventListener('DOMContentLoaded', async () => {
    const editForm = document.getElementById('edit-page-form');
    // const loader = document.getElementById('loading-indicator'); // Loader no longer used with SSR
    const alertBox = document.getElementById('alert-container');
    const titleInput = document.getElementById('pageTitle');
    const slugInput = document.getElementById('slug');
    const htmlBuffer = document.getElementById('htmlBuffer');
    const livePreview = document.getElementById('livePreview');

    if (!editForm) return;

    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    // 1. Redirect if no ID provided in URL
    if (!id) {
        window.location.href = 'index.php';
        return;
    }

    // 2. Live Preview logic for edit mode
    if (htmlBuffer && livePreview) {
        htmlBuffer.addEventListener('input', () => {
            livePreview.innerHTML = htmlBuffer.value;
        });
    }

    // 3. Optional: Auto-slug generation logic
    titleInput.addEventListener('input', () => {
        const generatedSlug = titleInput.value.toLowerCase().trim().replace(/[^a-z0-9\-]/g, '-').replace(/-+/g, '-');
        // Only update slug if it was empty or manually requested? 
        // For now, let's keep it manual or simple
    });

    // 4. Handle Update Submission
    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> SYNCING_CHANGES...';

        try {
            const updateResult = await updatePageData(data);

            if (updateResult.status === 'success') {
                window.location.href = 'pages-list.php?updated=1';
            } else {
                throw new Error(updateResult.message || 'Unknown update error');
            }
        } catch (error) {
            alertBox.innerHTML = `
                <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger mono small">
                    <i class="bi bi-shield-exclamation me-2"></i> UPDATE_FAILED: ${error.message}
                </div>`;

            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
