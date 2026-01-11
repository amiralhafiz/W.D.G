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

            const isMain = parseInt(page.is_main) === 1;
            const isActive = page.status === 'active';

            // Allow setting main only if the page is active
            const mainStatusHtml = isMain
                ? `<span class="badge bg-primary shadow-success fw-bold">MAIN PAGE</span>`
                : `<button onclick="handleSetMain(${page.id})" class="btn btn-sm ${isActive ? 'btn-outline-primary' : 'btn-warning disabled'} opacity-75">SET AS MAIN</button>`;

            tr.innerHTML = `
                <td class="ps-4 fw-bold">${page.title}</td>
                <td class="mono small">/${page.slug}</td>
                <td class="text-center mono small opacity-75">
                    <span class="badge ${isActive ? 'bg-success shadow-success' : 'bg-secondary'} px-3 rounded-pill">
                        ${page.status.toUpperCase()}
                    </span>
                    <div class="mt-1 small">
                        ${mainStatusHtml}
                    </div>
                </td>
                <td class="text-center mono small">${new Date(page.created_at).toLocaleDateString()}</td>
                <td class="pe-4 text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="edit-page.php?id=${page.id}" class="btn btn-sm btn-outline-primary border-0"><i class="bi bi-pencil-square"></i></a>
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

            const isMain = parseInt(page.is_main) === 1;
            const isActive = page.status === 'active';

            const mainStatusHtml = isMain
                ? `<span class="badge bg-info shadow-success fw-bold">MAIN PAGE</span>`
                : `<button onclick="handleSetMain(${page.id})" class="btn btn-sm ${isActive ? 'btn-outline-info' : 'btn-warning disabled'} opacity-75">SET AS MAIN</button>`;

            card.innerHTML = `
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="text-info mb-0">${page.title}</h5>
                        <span class="badge ${isActive ? 'bg-success' : 'bg-secondary'} rounded-pill">${page.status.toUpperCase()}</span>
                    </div>
                    <p class="text-light mono small opacity-50 mb-3">${page.slug}</p>
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

    window.confirmDelete = (id, title) => {
        const modalElement = document.getElementById('universalDeleteModal');
        let modal = bootstrap.Modal.getInstance(modalElement);
        if (!modal) modal = new bootstrap.Modal(modalElement);

        // Reset modal to default state for delete
        document.getElementById('modal-icon-container').innerHTML = '<i class="bi bi-trash3 text-danger" style="font-size: 4rem; opacity: 0.8;"></i>';
        const confirmBtn = document.getElementById('universal-btn-confirm');
        confirmBtn.className = 'btn btn-danger rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center';
        confirmBtn.innerHTML = '<i class="bi bi-radioactive me-2"></i> CONFIRM ACTION';

        document.getElementById('universal-modal-title').textContent = title;
        document.getElementById('modal-main-title').textContent = 'Permanently Purge?';
        document.getElementById('universal-modal-label').textContent = 'TARGET PAGE';

        confirmBtn.onclick = async (e) => {
            e.preventDefault();
            try {
                const res = await apiRequest(`${PAGES_API_URL}?action=delete&id=${id}`);
                if (res.status === 'success') {
                    modal.hide();
                    loadPages();
                } else {
                    alert(res.message || 'Failed to delete page');
                }
            } catch (error) {
                console.error('Delete error:', error);
            }
        };
        modal.show();
    };

    window.handleSetMain = async (id) => {
        const modalElement = document.getElementById('universalDeleteModal');
        let modal = bootstrap.Modal.getInstance(modalElement);
        if (!modal) modal = new bootstrap.Modal(modalElement);

        const row = Array.from(tableBody.querySelectorAll('tr')).find(tr => tr.innerHTML.includes(`handleSetMain(${id})`));
        const pageTitle = row?.querySelector('td:first-child')?.textContent || 'This Page';

        document.getElementById('universal-modal-title').textContent = pageTitle;
        document.getElementById('modal-main-title').textContent = 'Set as Main Page?';
        document.getElementById('universal-modal-label').textContent = 'TARGET NODE';
        document.getElementById('modal-icon-container').innerHTML = '<i class="bi bi-cpu text-info" style="font-size: 4rem; opacity: 0.8;"></i>';

        const confirmBtn = document.getElementById('universal-btn-confirm');
        confirmBtn.className = 'btn btn-info rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center';
        confirmBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> DESIGNATE MAIN';

        confirmBtn.onclick = async (e) => {
            e.preventDefault();
            const res = await setMainPage(id);
            if (res.status === 'success') {
                modal.hide();
                loadPages();
            } else {
                alert(res.message || 'Failed to update main page');
            }
        };
        modal.show();
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
