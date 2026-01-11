// front-end/api-calling/edit-page.js

document.addEventListener('DOMContentLoaded', async () => {
    const editForm = document.getElementById('edit-page-form');
    const loadingIndicator = document.getElementById('loading-indicator');
    const editContent = document.getElementById('edit-content');
    const alertBox = document.getElementById('alert-container');
    
    const pageIdInput = document.getElementById('page-id');
    const titleInput = document.getElementById('pageTitle');
    const slugInput = document.getElementById('slug');
    const statusSelect = document.getElementById('status');
    const isMainCheck = document.getElementById('isMainCheck');
    const htmlBuffer = document.getElementById('htmlBuffer');
    const livePreview = document.getElementById('livePreview');

    if (!editForm) return;

    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    if (!id) {
        window.location.href = 'pages-list.php';
        return;
    }

    // 1. Fetch data from API
    try {
        const result = await getPageData(id);

        if (result.status === 'success') {
            const page = result.data;

            // Populate form
            pageIdInput.value = page.id;
            titleInput.value = page.title || '';
            slugInput.value = page.slug || '';
            statusSelect.value = page.status || 'draft';
            isMainCheck.checked = !!page.is_main;
            htmlBuffer.value = page.content || '';
            livePreview.innerHTML = page.content || '';

            // Show content
            loadingIndicator.style.display = 'none';
            editContent.style.display = 'flex';
        } else {
            throw new Error(result.message || "Page not found in registry.");
        }
    } catch (error) {
        loadingIndicator.style.display = 'none';
        alertBox.innerHTML = `
            <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger mono small">
                <i class="bi bi-exclamation-octagon me-2"></i> DATA_RETRIEVAL_ERROR: ${error.message}
                <div class="mt-3">
                    <a href="pages-list.php" class="btn btn-sm btn-danger rounded-pill px-3">Return to Registry</a>
                </div>
            </div>`;
    }

    // 2. Live Preview logic
    if (htmlBuffer && livePreview) {
        htmlBuffer.addEventListener('input', () => {
            livePreview.innerHTML = htmlBuffer.value;
        });
    }

    // 3. Handle Update Submission
    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        data.is_main = isMainCheck.checked;

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