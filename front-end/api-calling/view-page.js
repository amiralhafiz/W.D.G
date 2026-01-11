// front-end/api-calling/view-page.js

document.addEventListener('DOMContentLoaded', async () => {
    const container = document.getElementById('page-content-container');
    if (!container) return;

    // 1. Extract slug from URL (?slug=your-page-slug)
    const urlParams = new URLSearchParams(window.location.search);
    const slug = urlParams.get('slug');

    // 2. Client-side Check: If no slug provided
    if (!slug) {
        renderError(container, "404: NULL SLUG EXCEPTION", "No page identifier was provided in the protocol. Kindly create new page or perform some checking.");
        return;
    }

    try {
        // 3. Fetch data from back-end API
        // Assumes getPageBySlug(slug) is defined in pages-api.js
        const result = await getPageBySlug(slug);

        if (result.status === 'success') {
            // 4. Update Metadata
            document.title = `${result.data.title} | W.D.G`;
            const metaDesc = document.querySelector('meta[name="description"]');
            if (metaDesc) metaDesc.content = result.data.description || "";

            // 5. Inject Content
            container.innerHTML = result.data.content;
        } else {
            renderError(container, "404: NODE NOT FOUND", "The requested data node does not exist on the network.");
        }
    } catch (error) {
        console.error('Fetch error:', error);
        renderError(container, "500: CONNECTION FAILURE", "Unable to establish a link with the back-end API.");
    }
});

/**
 * Helper to render styled error messages
 */
function renderError(target, code, message) {
    target.innerHTML = `
        <div class="text-center py-5 animate-up">
            <h1 class="display-1 fw-bold text-danger opacity-25">${code}</h1>
            <p class="text-light mono mb-4">${message}</p>
            <a href="add-page.php" class="btn btn-outline-info rounded-pill px-4">
                <i class="bi bi-file-earmark me-2"></i>Create page now
            </a>
        </div>
    `;
}
