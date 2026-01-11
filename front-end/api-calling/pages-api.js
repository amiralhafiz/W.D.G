// front-end/api-calling/pages-api.js

const PAGES_API_URL = '../back-end/api/pages.php';

/**
 * Universal Fetch Helper to handle JSON and Network Errors
 */
async function apiRequest(url, options = {}) {
    try {
        const response = await fetch(url, options);
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP_${response.status}: ${errorText || 'Server Error'}`);
        }
        return await response.json();
    } catch (error) {
        console.error('API_REQUEST_ERROR:', error);
        return { status: 'error', message: error.message };
    }
}

async function createPage(pageData) {
    return await apiRequest(`${PAGES_API_URL}?action=create`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(pageData)
    });
}

async function updatePageData(pageData) {
    return await apiRequest(`${PAGES_API_URL}?action=update`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(pageData)
    });
}

async function getPageData(id) {
    const response = await apiRequest(`${PAGES_API_URL}?action=get&id=${id}`);
    // Extract data from the success wrapper if present
    if (response && response.status === 'success' && response.data) {
        return response;
    }
    // Handle the case where the API might return data directly or wrapped differently
    return response;
}

async function getPageBySlug(slug) {
    return await apiRequest(`${PAGES_API_URL}?action=get_by_slug&slug=${slug}`);
}

async function getNavPages() {
    return await apiRequest(`${PAGES_API_URL}?action=nav`);
}

async function getMainPage() {
    return await apiRequest(`${PAGES_API_URL}?action=get_main`);
}
