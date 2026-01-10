// front-end/api-calling/add-page.js

document.addEventListener('DOMContentLoaded', () => {
    const addForm = document.getElementById('add-page-form');
    if (!addForm) return;

    const htmlBuffer = document.getElementById('htmlBuffer');
    const livePreview = document.getElementById('livePreview');
    const pageTitle = document.getElementById('pageTitle');

    // 1. Live Preview Logic
    if (htmlBuffer && livePreview) {
        htmlBuffer.addEventListener('input', () => {
            const content = htmlBuffer.value;
            if (content.trim()) {
                livePreview.innerHTML = content;
            } else {
                livePreview.innerHTML = `
                    <div class="text-center opacity-50 py-5">
                        <i class="bi bi-code-square display-1 d-block mb-3"></i>
                        <p>Awaiting Content Stream...</p>
                    </div>`;
            }
        });
    }

    // 2. Form Submission Logic
    addForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        // 3. Generate Slug (replacing the previous PHP logic)
        data.slug = data.title
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\-]/g, '-') // replace non-alphanumeric with hyphen
            .replace(/-+/g, '-');         // remove consecutive hyphens

        data.status = 'active'; // Default status for new pages

        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Publishing...';

        try {
            // Assume createPage(data) is defined in pages-api.js
            const result = await createPage(data);

            if (result.status === 'success') {
                window.location.href = 'index.php?page_created=1';
            } else {
                alert('Protocol Failure: ' + result.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
        } catch (error) {
            console.error('Submission Error:', error);
            alert('Fatal Error: Connection to Page Node lost.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
        }
    });
});
