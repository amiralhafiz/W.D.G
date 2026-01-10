/**
 * READING-API.JS
 * Secured Core Logic for Logs, Members, and Web Pages
 * Version: 3.2 (API-Driven Deletion)
 */

let currentSearch = '';
let currentMemberSearch = '';
let currentPagesSearch = '';
let universalModalInstance = null;

// --- 0. UTILITIES ---
function escapeHTML(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function sanitizeQuery(str) {
    return str.replace(/[<>"{}$]/g, '').trim();
}

/**
 * UNIVERSAL DESTRUCTION MODAL TRIGGER
 */
function openDestructionModal(title, targetUrl, label = 'TARGET IDENTITY', callback = null) {
    const modalEl = document.getElementById('universalDeleteModal');
    if (!modalEl) return;

    if (document.body && modalEl.parentNode !== document.body) {
        document.body.appendChild(modalEl);
    }

    // UI elements
    const titleEl = document.getElementById('universal-modal-title');
    const labelEl = document.getElementById('universal-modal-label');
    const mainTitleEl = document.getElementById('modal-main-title');
    const iconContainer = document.getElementById('modal-icon-container');
    const highlightBox = document.getElementById('modal-highlight-box');
    const subText = document.getElementById('modal-sub-text');
    const confirmBtn = document.getElementById('universal-btn-confirm');

    // Reset default styles (Deletion)
    mainTitleEl.textContent = "Permanently Delete?";
    iconContainer.innerHTML = '<i class="bi bi-trash3 text-danger" style="font-size: 4rem; opacity: 0.8;"></i>';
    highlightBox.className = "p-3 mx-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-3 mb-3";
    labelEl.className = "text-danger small mono opacity-75 d-block mb-1";
    confirmBtn.className = "btn btn-danger rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center";
    confirmBtn.innerHTML = '<i class="bi bi-radioactive me-2"></i> CONFIRM DELETION';
    subText.innerHTML = '<i class="bi bi-info-circle me-1"></i> This action is irreversible.';

    // Inject Data
    titleEl.textContent = title;
    labelEl.textContent = label;
    
    // Customize for Status Toggle
    if (callback && label.includes('STATUS')) {
        mainTitleEl.textContent = "Update Status?";
        iconContainer.innerHTML = '<i class="bi bi-arrow-repeat text-info" style="font-size: 4rem; opacity: 0.8;"></i>';
        highlightBox.className = "p-3 mx-3 bg-info bg-opacity-10 border border-info border-opacity-25 rounded-3 mb-3";
        labelEl.className = "text-info small mono opacity-75 d-block mb-1";
        confirmBtn.className = "btn btn-info rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center";
        confirmBtn.innerHTML = '<i class="bi bi-check-lg me-2"></i> CONFIRM CHANGE';
        subText.innerHTML = '<i class="bi bi-info-circle me-1"></i> Page will refresh to apply changes.';
    }

    // Handle confirm action
    const newBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
    
    if (callback) {
        newBtn.removeAttribute('href');
        newBtn.onclick = (e) => {
            e.preventDefault();
            callback();
            const inst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            inst.hide();
        };
    } else {
        newBtn.href = targetUrl;
        newBtn.onclick = null;
    }

    const inst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
    inst.show();
}

// --- 1. NAVIGATION & STATS LOGIC ---
async function fetchNavPages() {
    const navContainer = document.getElementById('dynamic-nav-links');
    if (!navContainer) return;
    try {
        const response = await fetch('../back-end/api/pages.php?action=nav');
        const result = await response.json();
        if (result.status === 'success') {
            let navHtml = '<li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>';
            result.data.forEach(page => {
                navHtml += `<li class="nav-item"><a class="nav-link" href="view-page.php?slug=${escapeHTML(page.slug)}">${escapeHTML(page.title)}</a></li>`;
            });
            navContainer.innerHTML = navHtml;
        }
    } catch (e) { console.error("Navigation load failed:", e); }
}

async function updateSystemStats() {
    try {
        const [userRes, logRes, pageRes] = await Promise.all([
            fetch('../back-end/api/users.php?action=count').then(r => r.json()),
            fetch('../back-end/api/loggers.php?action=count').then(r => r.json()),
            fetch('../back-end/api/pages.php?action=count').then(r => r.json())
        ]);
        if (userRes.status === 'success') document.getElementById('users-count').textContent = userRes.count;
        if (logRes.status === 'success') document.getElementById('logs-count').textContent = logRes.count;
        if (pageRes.status === 'success') document.getElementById('pages-count').textContent = pageRes.count;
        const statusBadge = document.querySelector('.db-status-badge');
        if (statusBadge) {
            statusBadge.classList.replace('bg-dark', 'bg-success');
            statusBadge.textContent = 'DB: CONNECTED';
        }
    } catch (e) {
        const statusBadge = document.querySelector('.db-status-badge');
        if (statusBadge) {
            statusBadge.classList.replace('bg-success', 'bg-danger');
            statusBadge.textContent = 'DB: OFFLINE';
        }
    }
}

// --- 2. SYSTEM LOGS LOGIC ---
async function fetchLogs(search = '', page = 1) {
    currentSearch = sanitizeQuery(search);
    const tbody = document.getElementById('logs-table-body');
    if (!tbody) return;
    tbody.innerHTML = `<tr><td colspan="3" class="text-center py-5"><div class="spinner-border text-dark mb-2"></div><div class="text-dark mono small opacity-50">LOADING...</div></td></tr>`;
    try {
        const response = await fetch(`../back-end/api/loggers.php?action=list&search=${encodeURIComponent(currentSearch)}&page=${page}`);
        const result = await response.json();
        if (result.status === 'success') {
            renderLogs(result.data);
            renderPagination(result.pagination, 'logs');
        }
    } catch (e) { tbody.innerHTML = `<tr><td colspan="3" class="text-center py-5 text-danger mono">CONNECTION ERROR</td></tr>`; }
}

function renderLogs(logs) {
    const tbody = document.getElementById('logs-table-body');
    if (!logs || logs.length === 0) {
        tbody.innerHTML = `<tr><td colspan="3" class="text-center py-5 text-danger mono">NO LOGS FOUND</td></tr>`;
        return;
    }
    tbody.innerHTML = logs.map((log, index) => `
        <tr class="log-row" style="animation-delay: ${index * 0.05}s">
            <td class="ps-4 mono small opacity-75 text-center">${escapeHTML(log.created_at)}</td>
            <td class="text-center"><span class="badge rounded-pill px-3 py-2 ${getBadgeClass(log.activity)} mono small">${escapeHTML(log.activity.toUpperCase())}</span></td>
            <td class="pe-4 mono small opacity-75">${escapeHTML(log.details)}</td>
        </tr>`).join('');
}

function getBadgeClass(act) {
    act = String(act).toLowerCase();
    if (act.includes('delete')) return 'bg-danger shadow-danger';
    if (act.includes('create')) return 'bg-success shadow-success';
    if (act.includes('update')) return 'bg-warning text-dark shadow-warning';
    return 'bg-secondary';
}

// --- 3. MEMBERS DIRECTORY LOGIC ---
async function fetchMembers(search = '', page = 1) {
    currentMemberSearch = sanitizeQuery(search);
    const tbody = document.getElementById('members-table-body');
    const mobileContainer = document.getElementById('members-mobile-view');
    if (!tbody && !mobileContainer) return;
    if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5"><div class="spinner-border text-dark mb-2"></div><div class="text-dark mono small opacity-50">LOADING...</div></td></tr>`;
    try {
        const response = await fetch(`../back-end/api/users.php?action=list&search=${encodeURIComponent(currentMemberSearch)}&page=${page}`);
        const result = await response.json();
        if (result.status === 'success') {
            renderMembersTable(result.data);
            renderMembersMobile(result.data);
            renderPagination(result.pagination, 'members');
        }
    } catch (e) { if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-danger mono">REGISTRY OFFLINE</td></tr>`; }
}

async function deleteMember(user) {
    try {
        const response = await fetch(`../back-end/api/users.php?action=delete&user=${encodeURIComponent(user)}`);
        const result = await response.json();
        if (result.status === 'success') location.reload();
    } catch (e) { console.error("Delete Error:", e); }
}

function renderMembersTable(users) {
    const tbody = document.getElementById('members-table-body');
    if (!tbody) return;
    if (!users || users.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-danger mono">NO MEMBERS FOUND</td></tr>`;
        return;
    }
    tbody.innerHTML = users.map((user, index) => {
        const safeUser = escapeHTML(user.user);
        const safeName = safeUser + ' - ' + escapeHTML(user.fullname || 'Unknown');
        const status = user.status || 'active';
        const statusClass = status === 'active' ? 'bg-success' : 'bg-danger';
        return `
        <tr style="animation-delay: ${index * 0.05}s" class="log-row">
            <td class="ps-4 mono small opacity-50 text-center">${safeUser.substring(0, 8)}</td>
            <td class="mono small opacity-75"><strong>${safeName}</strong></td>
            <td class="mono small opacity-75 text-center">
                <button onclick="toggleMemberStatus('${user.user}', '${status}', event)" class="badge rounded-pill border-0 ${statusClass} shadow-sm px-3 py-2 cursor-pointer">
                    ${status.toUpperCase()}
                </button>
            </td>
            <td class="mono small opacity-75 text-center">${escapeHTML(user.phonenumber)}</td>
            <td class="mono small opacity-75 text-center">${escapeHTML(user.email)}</td>
            <td class="pe-4 text-center">
                <a href="edit-member.php?user=${encodeURIComponent(user.user)}" class="btn btn-sm btn-outline-primary border-0"><i class="bi bi-pencil-square"></i></a>
                <button type="button" class="btn btn-sm btn-outline-danger border-0 ms-2"
                    onclick="openDestructionModal('${safeName}', '#', 'USER IDENTITY', () => deleteMember('${user.user}'))">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>`}).join('');
}

function renderMembersMobile(users) {
    const container = document.getElementById('members-mobile-view');
    if (!container) return;
    if (!users || users.length === 0) {
        container.innerHTML = `<div class="alert bg-black bg-opacity-25 border-danger text-center text-danger">NO MEMBERS FOUND</div>`;
        return;
    }
    container.innerHTML = users.map((user, index) => {
        const safeUser = escapeHTML(user.user);
        const safeName = safeUser + ' - ' + escapeHTML(user.fullname || 'Unknown');
        const status = user.status || 'active';
        const statusClass = status === 'active' ? 'bg-success' : 'bg-danger';
        return `
        <div class="card glass-card border-0 mb-3 log-row shadow-sm" style="animation-delay: ${index * 0.1}s">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div><h5 class="text-white mb-0">${safeName}</h5><span class="text-warning mono small opacity-50">#${safeUser.substring(0, 8)}</span></div>
                    <button onclick="toggleMemberStatus('${user.user}', '${status}', event)" class="badge rounded-pill border-0 ${statusClass} px-3 py-2">
                        ${status.toUpperCase()}
                    </button>
                </div>
                <div class="mb-3 text-light small mono">
                    <div><i class="bi bi-telephone me-2"></i>${escapeHTML(user.phonenumber)}</div>
                    <div><i class="bi bi-envelope me-2"></i>${escapeHTML(user.email)}</div>
                </div>
                <div class="d-flex gap-2">
                    <a href="edit-member.php?user=${encodeURIComponent(user.user)}" class="btn btn-primary flex-fill fw-bold rounded-3">EDIT</a>
                    <button type="button" class="btn btn-outline-danger flex-fill fw-bold rounded-3"
                        onclick="openDestructionModal('${safeName}', '#', 'USER IDENTITY', () => deleteMember('${user.user}'))">DELETE</button>
                </div>
            </div>
        </div>`}).join('');
}

// --- 4. WEB PAGES LOGIC ---
async function fetchPages(search = '', page = 1) {
    currentPagesSearch = sanitizeQuery(search);
    const tbody = document.getElementById('pages-table-body');
    const mobileContainer = document.getElementById('pages-mobile-view');
    if (!tbody && !mobileContainer) return;
    if (tbody) tbody.innerHTML = `<tr><td colspan="5" class="text-center py-5"><div class="spinner-border text-info mb-2"></div><div class="text-info mono small opacity-50">SYNCING PAGES...</div></td></tr>`;
    try {
        const response = await fetch(`../back-end/api/pages.php?action=list&search=${encodeURIComponent(currentPagesSearch)}&page=${page}`);
        const result = await response.json();
        if (result.status === 'success') {
            renderPagesTable(result.data);
            renderPagesMobile(result.data);
            renderPagination(result.pagination, 'pages');
        }
    } catch (e) { if (tbody) tbody.innerHTML = `<tr><td colspan="5" class="text-center py-5 text-danger mono">PAGES REGISTRY OFFLINE</td></tr>`; }
}

async function togglePageStatus(id, currentStatus, event) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    const row = event.target.closest('tr') || event.target.closest('.card-body');
    const title = row ? row.querySelector('h5, strong').textContent : 'this page';
    openDestructionModal(title, '#', `TARGET STATUS: ${newStatus.toUpperCase()}`, async () => {
        try {
            const response = await fetch(`../back-end/api/pages.php?action=toggle&id=${id}&status=${newStatus}`);
            const result = await response.json();
            if (result.status === 'success') location.reload();
        } catch (e) { console.error("Toggle Error:", e); }
    });
}

async function deletePage(id) {
    try {
        const response = await fetch(`../back-end/api/pages.php?action=delete&id=${id}`);
        const result = await response.json();
        if (result.status === 'success') location.reload();
    } catch (e) { console.error("Delete Error:", e); }
}

async function toggleMemberStatus(user, currentStatus, event) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    const row = event.target.closest('tr') || event.target.closest('.card-body');
    const title = row ? row.querySelector('h5, strong').textContent : 'this member';
    openDestructionModal(title, '#', `TARGET STATUS: ${newStatus.toUpperCase()}`, async () => {
        try {
            const response = await fetch(`../back-end/api/users.php?action=toggle&user=${encodeURIComponent(user)}&status=${newStatus}`);
            const result = await response.json();
            if (result.status === 'success') location.reload();
        } catch (e) { console.error("Member Toggle Error:", e); }
    });
}

function renderPagesTable(pages) {
    const tbody = document.getElementById('pages-table-body');
    if (!tbody) return;
    if (!pages || pages.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-5 text-danger mono">NO PAGES FOUND</td></tr>`;
        return;
    }
    tbody.innerHTML = pages.map((p, index) => {
        const safeTitle = escapeHTML(p.title);
        const statusClass = p.status === 'active' ? 'bg-success' : 'bg-danger';
        return `
        <tr style="animation-delay: ${index * 0.05}s" class="log-row">
            <td class="ps-4 mono small opacity-75"><strong>${safeTitle}</strong></td>
            <td class="mono small opacity-75">/${escapeHTML(p.slug)}</td>
            <td class="text-center">
                <button onclick="togglePageStatus(${p.id}, '${p.status}', event)" class="badge rounded-pill border-0 ${statusClass} shadow-sm px-3 py-2 cursor-pointer">
                    ${p.status.toUpperCase()}
                </button>
            </td>
            <td class="text-center mono small opacity-75">${escapeHTML(p.created_at)}</td>
            <td class="pe-4 text-center">
                <a href="view-page.php?slug=${escapeHTML(p.slug)}" class="btn btn-sm btn-outline-dark border-0" title="View"><i class="bi bi-eye"></i></a>
                <a href="edit-page.php?id=${p.id}" class="btn btn-sm btn-outline-primary border-0 ms-2" title="Edit"><i class="bi bi-pencil-square"></i></a>
                <button type="button" class="btn btn-sm btn-outline-danger border-0 ms-2"
                    onclick="openDestructionModal('${safeTitle}', '#', 'PAGE CONTENT', () => deletePage(${p.id}))">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>`}).join('');
}

function renderPagesMobile(pages) {
    const container = document.getElementById('pages-mobile-view');
    if (!container) return;
    if (!pages || pages.length === 0) {
        container.innerHTML = `<div class="alert bg-black bg-opacity-25 border-info text-center text-info mono">NO PAGES FOUND</div>`;
        return;
    }
    container.innerHTML = pages.map((p, index) => {
        const safeTitle = escapeHTML(p.title);
        const statusClass = p.status === 'active' ? 'bg-success' : 'bg-danger';
        return `
        <div class="card glass-card border-0 mb-3 log-row shadow-sm" style="animation-delay: ${index * 0.1}s">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div><h5 class="text-white mb-0">${safeTitle}</h5><span class="text-info mono small opacity-50">/${escapeHTML(p.slug)}</span></div>
                    <button onclick="togglePageStatus(${p.id}, '${p.status}', event)" class="badge rounded-pill border-0 ${statusClass} px-3 py-2">
                        ${p.status.toUpperCase()}
                    </button>
                </div>
                <div class="mb-3 text-light small mono">
                    <div class="opacity-75"><i class="bi bi-calendar3 me-2"></i>${escapeHTML(p.created_at)}</div>
                </div>
                <div class="d-flex gap-2">
                    <a href="view-page.php?slug=${escapeHTML(p.slug)}" class="btn btn-outline-light flex-fill fw-bold rounded-3">VIEW</a>
                    <a href="edit-page.php?id=${p.id}" class="btn btn-primary flex-fill fw-bold rounded-3">EDIT</a>
                    <button type="button" class="btn btn-outline-danger flex-fill fw-bold rounded-3"
                        onclick="openDestructionModal('${safeTitle}', '#', 'PAGE CONTENT', () => deletePage(${p.id}))">DELETE</button>
                </div>
            </div>
        </div>`}).join('');
}

// --- 5. SEARCH & PAGINATION HELPERS ---
function handleKeyup(event, type) {
    const id = type === 'logs' ? 'logSearch' : (type === 'members' ? 'memberSearch' : 'pageSearch');
    const btn = type === 'logs' ? 'resetSearch' : (type === 'members' ? 'resetMemberSearch' : 'resetPageSearch');
    const inputEl = document.getElementById(id);
    if (!inputEl) return;
    const val = inputEl.value;
    const el = document.getElementById(btn);
    if(el) val.length > 0 ? el.classList.remove('d-none') : el.classList.add('d-none');
    if (event.key === 'Enter') {
        if (type === 'logs') applyFilter();
        else if (type === 'members') applyMemberFilter();
        else applyPageFilter();
    }
}
function applyFilter() { fetchLogs(document.getElementById('logSearch').value, 1); }
function applyMemberFilter() { fetchMembers(document.getElementById('memberSearch').value, 1); }
function applyPageFilter() { fetchPages(document.getElementById('pageSearch').value, 1); }
function resetFilter(type) {
    if (type === 'logs') { document.getElementById('logSearch').value = ''; document.getElementById('resetSearch')?.classList.add('d-none'); fetchLogs('', 1); }
    else if (type === 'members') { document.getElementById('memberSearch').value = ''; document.getElementById('resetMemberSearch')?.classList.add('d-none'); fetchMembers('', 1); }
    else { document.getElementById('pageSearch').value = ''; document.getElementById('resetPageSearch')?.classList.add('d-none'); fetchPages('', 1); }
}
function renderPagination(pg, type) {
    let containerId = '';
    if (type === 'logs') containerId = 'pagination-container';
    else if (type === 'members') containerId = 'member-pagination-container';
    else if (type === 'pages') containerId = 'page-pagination-container';
    const container = document.getElementById(containerId);
    if (!container || !pg || pg.total_pages <= 1) { if(container) container.innerHTML = ''; return; }
    const func = type === 'logs' ? 'fetchLogs' : (type === 'members' ? 'fetchMembers' : 'fetchPages');
    const search = type === 'logs' ? currentSearch : (type === 'members' ? currentMemberSearch : currentPagesSearch);
    const safeS = escapeHTML(search).replace(/'/g, "\\'");
    let html = '<ul class="pagination justify-content-center">';
    html += `<li class="page-item ${pg.current_page === 1 ? 'disabled' : ''}"><button class="page-link glass-pagination" onclick="${func}('${safeS}', ${pg.current_page - 1})"><i class="bi bi-chevron-left"></i></button></li>`;
    for (let i = 1; i <= pg.total_pages; i++) { html += `<li class="page-item ${i === pg.current_page ? 'active' : ''}"><button class="page-link glass-pagination ${i === pg.current_page ? 'active' : ''}" onclick="${func}('${safeS}', ${i})">${i}</button></li>`; }
    html += `<li class="page-item ${pg.current_page === pg.total_pages ? 'disabled' : ''}"><button class="page-link glass-pagination" onclick="${func}('${safeS}', ${pg.current_page + 1})"><i class="bi bi-chevron-right"></i></button></li></ul>`;
    container.innerHTML = html;
}

// --- 6. INITIALIZATION ---
document.addEventListener('DOMContentLoaded', () => {
    const sess = document.getElementById('session-id');
    if (sess) {
        try {
            const b = new Uint8Array(4);
            window.crypto.getRandomValues(b);
            sess.textContent = Array.from(b).map(x => x.toString(16).padStart(2,'0')).join('').toUpperCase();
        } catch(e){ sess.textContent = "AUTH_ACTIVE"; }
    }
    fetchNavPages();
    updateSystemStats();
    if (document.getElementById('members-table-body')) fetchMembers();
    if (document.getElementById('pages-table-body')) fetchPages();
    if (document.getElementById('logs-table-body')) fetchLogs();
});
