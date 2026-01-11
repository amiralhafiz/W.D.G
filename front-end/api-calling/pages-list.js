// front-end/api-calling/pages-list.js

document.addEventListener('DOMContentLoaded', async () => {
    const tableBody = document.getElementById('pages-table-body');
    const mobileView = document.getElementById('pages-mobile-view');
    const sessionIdSpan = document.getElementById('session-id');
    
    if (sessionIdSpan) {
        sessionIdSpan.textContent = Math.random().toString(36).substring(2, 10).toUpperCase();
    }

    async function loadPages(search = '', page = 1) {
        try {
            const result = await getPages(search, page);
            if (result.status === 'success') {
                renderTable(result.data);
                renderMobile(result.data);
                renderPagination(result.pagination);
            }
        } catch (error) {
            console.error('Error loading pages:', error);
        }
    }

    function renderTable(pages) {
        if (!tableBody) return;
        tableBody.innerHTML = '';
        
        pages.forEach(page => {
            const tr = document.createElement('tr');
            tr.className = 'log-row';
            
            const isMain = page.is_main;
            const isActive = page.status === 'active';
            
            // Logic: 
            // if page active -> disable main page status (cannot be changed if already active?)
            // user said: "if page status is active disable the main page status, else if page status inactive enable main page status to be choose for other page"
            // Wait, usually we want to set ACTIVE pages as main. 
            // User: "if page status is active disable the main page status" -> Maybe means if it's already active, we don't show the toggle?
            // "else if page status inactive enable main page status to be choose for other page" -> If inactive, allow choosing?
            // That sounds reversed. Let me re-read:
            // "if page status is active disable the main page status" -> maybe they mean if it is ALREADY the main page and active?
            // Let's follow the user's literal words:
            const mainStatusHtml = isMain 
                ? `<span class="badge bg-info shadow-success fw-bold">MAIN PAGE</span>` 
                : `<button onclick="handleSetMain(${page.id})" class="btn btn-sm btn-outline-info opacity-50" ${isActive ? 'disabled' : ''}>SET AS MAIN</button>`;

            tr.innerHTML = `
                <td class="ps-4 fw-bold text-info">${page.title}</td>
                <td class="mono small opacity-75">${page.slug}</td>
                <td class="text-center">
                    <span class="badge ${isActive ? 'bg-success shadow-success' : 'bg-secondary'} px-3 rounded-pill">
                        ${page.status.toUpperCase()}
                    </span>
                    <div class="mt-1 small">
                        ${mainStatusHtml}
                    </div>
                </td>
                <td class="text-center mono small opacity-50">${new Date(page.created_at).toLocaleDateString()}</td>
                <td class="pe-4 text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="edit-page.php?id=${page.id}" class="btn btn-sm btn-outline-warning border-0"><i class="bi bi-pencil-square"></i></a>
                        <button onclick="confirmDelete(${page.id}, '${page.title}')" class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash3"></i></button>
                    </div>
                </td>
            `;
            tableBody.appendChild(tr);
        });
    }

    function renderPagination(pagination) {
        const container = document.getElementById('page-pagination-container');
        if (!container || !pagination) return;
        
        const { current_page, total_pages } = pagination;
        if (total_pages <= 1) {
            container.innerHTML = '';
            return;
        }

        let html = '<ul class="pagination justify-content-center">';
        for (let i = 1; i <= total_pages; i++) {
            html += `
                <li class="page-item">
                    <button class="page-link glass-pagination ${i === current_page ? 'active' : ''}" 
                            onclick="loadPages('', ${i})">${i}</button>
                </li>
            `;
        }
        html += '</ul>';
        container.innerHTML = html;
    }

    function renderMobile(pages) {
        if (!mobileView) return;
        mobileView.innerHTML = '';
        
        pages.forEach(page => {
            const card = document.createElement('div');
            card.className = 'card glass-card border-0 mb-3 animate-up';
            
            const isMain = page.is_main;
            const isActive = page.status === 'active';
            
            const mainStatusHtml = isMain 
                ? `<span class="badge bg-info shadow-success fw-bold">MAIN PAGE</span>` 
                : `<button onclick="handleSetMain(${page.id})" class="btn btn-sm btn-outline-info opacity-50" ${isActive ? 'disabled' : ''}>SET AS MAIN</button>`;

            card.innerHTML = `
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="text-info mb-0">${page.title}</h5>
                        <span class="badge ${isActive ? 'bg-success' : 'bg-secondary'} rounded-pill">${page.status.toUpperCase()}</span>
                    </div>
                    <p class="mono small opacity-50 mb-3">${page.slug}</p>
                    <div class="mb-3">${mainStatusHtml}</div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="edit-page.php?id=${page.id}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil-square me-1"></i>EDIT</a>
                        <button onclick="confirmDelete(${page.id}, '${page.title}')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3 me-1"></i>PURGE</button>
                    </div>
                </div>
            `;
            mobileView.appendChild(card);
        });
    }

    window.handleSetMain = async (id) => {
        if (confirm('Designate this page as the primary dashboard node?')) {
            const res = await setMainPage(id);
            if (res.status === 'success') {
                loadPages();
            } else {
                alert(res.message || 'Failed to update main page');
            }
        }
    };

    // Initial load
    loadPages();
});

function handleKeyup(event, type) {
    if (event.key === 'Enter') {
        applyPageFilter();
    }
}

function applyPageFilter() {
    const search = document.getElementById('pageSearch').value;
    // We need to expose loadPages or use a global event
    window.location.search = `?search=${encodeURIComponent(search)}`;
}
