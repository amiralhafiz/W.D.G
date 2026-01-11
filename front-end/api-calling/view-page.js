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
            // Check if any active pages exist
            const navResult = await getNavPages();
            
            if (navResult.status === 'success' && navResult.data && navResult.data.length > 0) {
                // Pages exist, show welcome content instead of error
                renderWelcome(container);
            } else {
                // No active pages found
                renderError(container, "404: NULL SLUG EXCEPTION", "No page identifier was provided in the protocol. Kindly create new page or perform some checking.");
            }
        } catch (error) {
            console.error('Nav check error:', error);
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
 * Helper to render welcome message when pages exist but no slug is selected
 */
function renderWelcome(target) {
    target.innerHTML = `
        <div class="text-center py-5 animate-up">
            <div class="mb-4">
                <i class="bi bi-cpu-fill display-1 text-info opacity-50"></i>
            </div>
            <h1 class="fw-light text-light mb-3">Welcome to W.D.G Dashboard</h1>
            <p class="text-light mono opacity-75 mb-4">SYSTEM_READY // Awaiting Node Selection</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="pages-list.php" class="btn btn-outline-info rounded-pill px-4">
                    <i class="bi bi-list-ul me-2"></i>Manage Pages
                </a>
                <a href="add-page.php" class="btn btn-info rounded-pill px-4 fw-bold">
                    <i class="bi bi-plus-circle me-2"></i>Create New
                </a>
            </div>
        </div>
    `;
}

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