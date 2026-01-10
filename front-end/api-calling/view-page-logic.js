// front-end/api-calling/view-page-logic.js

document.addEventListener('DOMContentLoaded', async () => {
    const container = document.querySelector('.container.animate-up');
    if (!container) return;

    const urlParams = new URLSearchParams(window.location.search);
    const slug = urlParams.get('slug');
    if (!slug) {
        container.innerHTML = '<h1>404 Page Not Found</h1>';
        return;
    }

    const result = await getPageBySlug(slug);
    if (result.status === 'success') {
        document.title = result.data.title + ' | W.D.G';
        const metaDesc = document.querySelector('meta[name="description"]');
        if (metaDesc) metaDesc.content = result.data.description;
        container.innerHTML = result.data.content;
    } else {
        container.innerHTML = '<h1>404 Page Not Found</h1>';
    }
});
