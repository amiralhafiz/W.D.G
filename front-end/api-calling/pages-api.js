// front-end/api-calling/pages-api.js

const PAGES_API_URL = '../back-end/api/pages.php';

async function createPage(pageData) {
    try {
        const response = await fetch(`${PAGES_API_URL}?action=create`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(pageData)
        });
        return await response.json();
    } catch (error) {
        console.error('Error creating page:', error);
        return { status: 'error', message: error.message };
    }
}

async function updatePageData(pageData) {
    try {
        const response = await fetch(`${PAGES_API_URL}?action=update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(pageData)
        });
        return await response.json();
    } catch (error) {
        console.error('Error updating page:', error);
        return { status: 'error', message: error.message };
    }
}

async function getPageData(id) {
    try {
        const response = await fetch(`${PAGES_API_URL}?action=get&id=${id}`);
        return await response.json();
    } catch (error) {
        console.error('Error getting page:', error);
        return { status: 'error', message: error.message };
    }
}

async function getPageBySlug(slug) {
    try {
        const response = await fetch(`${PAGES_API_URL}?action=get_by_slug&slug=${slug}`);
        return await response.json();
    } catch (error) {
        console.error('Error getting page by slug:', error);
        return { status: 'error', message: error.message };
    }
}
