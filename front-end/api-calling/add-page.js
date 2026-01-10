// front-end/api-calling/add-page.js

document.addEventListener('DOMContentLoaded', () => {
    const addForm = document.querySelector('form');
    if (!addForm) return;

    const htmlBuffer = document.getElementById('htmlBuffer');
    const livePreview = document.getElementById('livePreview');
    const pageTitle = document.getElementById('pageTitle');

    if (htmlBuffer && livePreview) {
        htmlBuffer.addEventListener('input', () => {
            const content = htmlBuffer.value;
            if (content.trim()) {
                livePreview.innerHTML = content;
            } else {
                livePreview.innerHTML = '<div class="text-center opacity-50 py-5"><i class="bi bi-code-square display-1 d-block mb-3"></i><p>Awaiting Content Stream...</p></div>';
            }
        });
    }

    addForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Publishing...';

        const result = await createPage(data);

        if (result.status === 'success') {
            window.location.href = 'index.php?page_created=1';
        } else {
            alert('Error: ' + result.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
        }
    });
});
