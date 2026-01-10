// front-end/api-calling/edit-page-logic.js

document.addEventListener('DOMContentLoaded', async () => {
    const editForm = document.querySelector('form');
    if (!editForm) return;

    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    if (!id) {
        window.location.href = 'pages-list.php';
        return;
    }

    const htmlBuffer = document.getElementById('htmlBuffer');
    const livePreview = document.getElementById('livePreview');

    if (htmlBuffer && livePreview) {
        htmlBuffer.addEventListener('input', () => {
            livePreview.innerHTML = htmlBuffer.value || '<div class="text-center opacity-50 py-5"><p>Awaiting Content Stream...</p></div>';
        });
    }

    // Fetch initial data
    const result = await getPageData(id);
    if (result.status === 'success') {
        const page = result.data;
        document.getElementById('pageTitle').value = page.title;
        document.querySelector('input[name="slug"]').value = page.slug;
        document.querySelector('select[name="status"]').value = page.status;
        htmlBuffer.value = page.content;
        livePreview.innerHTML = page.content;
    } else {
        window.location.href = 'pages-list.php';
    }

    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());
        data.id = id;

        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Updating...';

        const updateResult = await updatePageData(data);

        if (updateResult.status === 'success') {
            window.location.href = 'pages-list.php?updated=1';
        } else {
            alert('Error: ' + updateResult.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;
        }
    });
});
