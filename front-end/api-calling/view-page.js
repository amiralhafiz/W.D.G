// front-end/api-calling/view-page.js

document.addEventListener('DOMContentLoaded', async () => {
    const container = document.getElementById('page-content-container');
    if (!container) return;

    // 1. Extract slug from URL (?slug=your-page-slug)
    const urlParams = new URLSearchParams(window.location.search);
    const slug = urlParams.get('slug');

    // 2. Logic for when no slug is provided
    if (!slug) {
        try {
            const mainResult = await getMainPage();

            if (mainResult.status === 'success' && mainResult.data) {
                const page = mainResult.data;
                document.title = `${page.title} | W.D.G`;
                const metaDesc = document.querySelector('meta[name="description"]');
                if (metaDesc) metaDesc.content = page.description || "";
                container.innerHTML = page.content;
            } else if (mainResult.error_code === 'NULL_SLUG') {
                renderError(container, "404: NULL SLUG EXCEPTION", "No page identifier was provided in the protocol. Kindly create new page or perform some checking.");
            } else if (mainResult.error_code === 'NO_MAIN') {
                renderError(container, "404: NO MAIN PAGE", "Active pages exist, but none have been designated as the Main Page. Please configure a main page in the dashboard.");
            } else {
                renderError(container, "404: NULL SLUG EXCEPTION", "No page identifier was provided in the protocol. Kindly create new page or perform some checking.");
            }
        } catch (error) {
            console.error('Main page check error:', error);
            renderError(container, "500: CONNECTION FAILURE", "Unable to establish a link with the back-end API.");
        }
        return;
    }

    try {
        // 3. Fetch data from back-end API
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
