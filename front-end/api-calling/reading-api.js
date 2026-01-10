/**
 * READING-API.JS
 * Secured Core Logic for System Logs and Members Directory
 * Version: 2.0 (Security Enhanced)
 */

// Global state for filtering
let currentSearch = '';         // For Logs
let currentMemberSearch = '';   // For Members

/**
 * 0. SECURITY UTILITIES
 */

// Prevents XSS by converting special characters to HTML entities
function escapeHTML(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// Basic input cleaning for search fields
function sanitizeQuery(str) {
    return str.replace(/[<>"{}$]/g, '').trim();
}

/**
 * 1. SHARED DASHBOARD UTILITIES
 */
async function updateDashboardStats() {
    const endpoints = {
        'users-count': '../back-end/api/users.php?action=count',
        'logs-count': '../back-end/api/loggers.php?action=count'
    };

    for (const [id, url] of Object.entries(endpoints)) {
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('API_UNAVAILABLE');
            const result = await response.json();

            const element = document.getElementById(id);
            if (element && result.status === 'success') {
                // Securely set text without parsing HTML
                element.textContent = result.count;
            }
        } catch (error) {
            const element = document.getElementById(id);
            if (element) element.textContent = 'ERR';
        }
    }
}

/**
 * 2. SYSTEM LOGS LOGIC
 */
async function fetchLogs(search = '', page = 1) {
    currentSearch = sanitizeQuery(search);
    const tbody = document.getElementById('logs-table-body');
    if (!tbody) return;

    tbody.innerHTML = `<tr><td colspan="3" class="text-center py-5"><div class="spinner-border text-dark mb-2"></div><div class="text-dark mono small opacity-50">NEXT PAGE...</div></td></tr>`;

    try {
        const response = await fetch(`../back-end/api/loggers.php?action=list&search=${encodeURIComponent(currentSearch)}&page=${page}`);
        const result = await response.json();
        if (result.status === 'success') {
            renderLogs(result.data);
            renderPagination(result.pagination, 'logs');
        }
    } catch (error) {
        tbody.innerHTML = `<tr><td colspan="3" class="text-center py-5 mono"><i class="bi bi-exclamation-octagon d-block mb-2 fs-1 text-danger blink-red"></i><div class="text-danger fw-bold">CRITICAL: CONNECTION ERROR</div></td></tr>`;
    }
}

function renderLogs(logs) {
    const tbody = document.getElementById('logs-table-body');
    if (!logs || logs.length === 0) {
        tbody.innerHTML = `<tr><td colspan="3" class="text-center py-5 mono"><i class="bi bi-exclamation-octagon d-block mb-2 fs-1 text-danger blink-red"></i><div class="text-danger fw-bold">CRITICAL: NO LOGS MATCH</div></td></tr>`;
        return;
    }

    tbody.innerHTML = logs.map((log, index) => `
        <tr class="log-row" style="animation-delay: ${index * 0.05}s">
            <td class="ps-4 mono small opacity-75 text-center">${escapeHTML(log.created_at)}</td>
            <td class="text-center"><span class="badge rounded-pill px-3 py-2 ${getBadgeClass(log.activity)} mono small">${escapeHTML(log.activity.toUpperCase())}</span></td>
            <td class="pe-4 mono small opacity-75">${escapeHTML(log.details)}</td>
        </tr>`).join('');
}

function getBadgeClass(activity) {
    const act = String(activity).toLowerCase();
    if (act.includes('delete')) return 'bg-danger shadow-danger';
    if (act.includes('create')) return 'bg-success shadow-success';
    if (act.includes('update')) return 'bg-warning text-dark shadow-warning';
    return 'bg-secondary';
}

/**
 * 3. MEMBERS DIRECTORY LOGIC
 */
async function fetchMembers(search = '', page = 1) {
    currentMemberSearch = sanitizeQuery(search);

    const tbody = document.getElementById('members-table-body');
    const mobileContainer = document.getElementById('members-mobile-view');
    if (!tbody && !mobileContainer) return;

    const loader = `<tr><td colspan="5" class="text-center py-5"><div class="spinner-border text-dark mono small opacity-50 mb-2"></div><div class="text-dark mono small opacity-50">NEXT PAGE...</div></td></tr>`;
    if (tbody) tbody.innerHTML = loader;

    try {
        const response = await fetch(`../back-end/api/users.php?action=list&search=${encodeURIComponent(currentMemberSearch)}&page=${page}`);
        const result = await response.json();
        if (result.status === 'success') {
            renderMembersTable(result.data);
            renderMembersMobile(result.data);
            renderPagination(result.pagination, 'members');
        }
    } catch (error) {
        if (tbody) tbody.innerHTML = `<tr><td colspan="5" class="text-center py-5 text-danger mono">CRITICAL: REGISTRY OFFLINE</td></tr>`;
    }
}

function renderMembersTable(users) {
    const tbody = document.getElementById('members-table-body');
    if (!tbody) return;

    if (!users || users.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-5 mono"><i class="bi bi-exclamation-octagon d-block mb-2 fs-1 text-danger blink-red"></i><div class="text-danger fw-bold">CRITICAL: NO MEMBERS FOUND</div></td></tr>`;
        return;
    }

    tbody.innerHTML = users.map((user, index) => {
        const safeUser = escapeHTML(user.user) + ' - ' + escapeHTML(user.fullname);
        return `
        <tr style="animation-delay: ${index * 0.05}s" class="log-row">
            <td class="ps-4 mono small opacity-50 text-center">${safeUser.substring(0, 8)}</td>
            <td class="mono small opacity-75"><strong>${escapeHTML(user.fullname || 'Unknown')}</strong></td>
            <td class="mono small opacity-75 text-center">${escapeHTML(user.phonenumber)}</td>
            <td class="mono small opacity-75 text-center">${escapeHTML(user.email)}</td>
            <td class="pe-4 text-center">
                <a href="edit-member.php?user=${encodeURIComponent(user.user)}" class="btn btn-sm btn-outline-primary border-0 rounded-circle"><i class="bi bi-pencil-square"></i></a>
                <a href="delete.php?user=${encodeURIComponent(user.user)}" class="btn btn-sm btn-outline-danger border-0 rounded-circle ms-2" onclick="return confirm('DESTROY RECORD: ${safeUser}?')"><i class="bi bi-trash"></i></a>
            </td>
        </tr>`}).join('');
}

function renderMembersMobile(users) {
    const container = document.getElementById('members-mobile-view');
    if (!container) return;

    if (!users || users.length === 0) {
        container.innerHTML = `<div class="alert bg-black bg-opacity-25 border-danger border-opacity-25 mono text-center text-danger"><i class="bi bi-exclamation-octagon d-block mb-2 fs-1 text-danger blink-red"></i>CRITICAL: NO MEMBERS FOUND</div>`;
        return;
    }

    container.innerHTML = users.map((user, index) => {
        const safeUser = escapeHTML(user.user) + ' - ' + escapeHTML(user.fullname);
        return `
        <div class="card glass-card border-0 mb-3 log-row shadow-sm" style="animation-delay: ${index * 0.1}s">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="text-white mb-0">${escapeHTML(user.fullname || 'Unknown')}</h5>
                        <span class="text-warning mono small opacity-50">#${safeUser.substring(0, 8)}</span>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3">ACTIVE</span>
                </div>
                <div class="mb-3 text-light small mono">
                    <div><i class="bi bi-telephone me-2"></i>${escapeHTML(user.phonenumber)}</div>
                    <div><i class="bi bi-envelope me-2"></i>${escapeHTML(user.email)}</div>
                </div>
                <div class="d-flex gap-2">
                    <a href="edit-member.php?user=${encodeURIComponent(user.user)}" class="btn btn-primary flex-fill fw-bold rounded-3">EDIT</a>
                    <a href="delete.php?user=${encodeURIComponent(user.user)}" class="btn btn-outline-danger flex-fill fw-bold rounded-3" onclick="return confirm('DESTROY RECORD: ${safeUser}?')">DELETE</a>
                </div>
            </div>
        </div>`}).join('');
}

/**
 * 4. UI HELPERS (Filtering & Resetting)
 */
function handleKeyup(event, type) {
    const inputId = type === 'logs' ? 'logSearch' : 'memberSearch';
    const resetBtnId = type === 'logs' ? 'resetSearch' : 'resetMemberSearch';
    const inputEl = document.getElementById(inputId);
    if (!inputEl) return;

    const val = inputEl.value;
    const resetBtn = document.getElementById(resetBtnId);

    if (resetBtn) {
        val.length > 0 ? resetBtn.classList.remove('d-none') : resetBtn.classList.add('d-none');
    }
    if (event.key === 'Enter') {
        type === 'logs' ? applyFilter() : applyMemberFilter();
    }
}

function applyFilter() {
    const input = document.getElementById('logSearch');
    currentSearch = sanitizeQuery(input.value);
    fetchLogs(currentSearch, 1);
}

function applyMemberFilter() {
    const input = document.getElementById('memberSearch');
    currentMemberSearch = sanitizeQuery(input.value);
    fetchMembers(currentMemberSearch, 1);
}

function resetFilter(type) {
    if (type === 'logs') {
        document.getElementById('logSearch').value = '';
        currentSearch = '';
        document.getElementById('resetSearch')?.classList.add('d-none');
        fetchLogs('', 1);
    } else {
        document.getElementById('memberSearch').value = '';
        currentMemberSearch = '';
        document.getElementById('resetMemberSearch')?.classList.add('d-none');
        fetchMembers('', 1);
    }
}

/**
 * 5. UNIFIED PAGINATION
 */
function renderPagination(pagination, type) {
    const isLogs = type === 'logs';
    const containerId = isLogs ? 'pagination-container' : 'member-pagination-container';
    const container = document.getElementById(containerId);

    if (!container || !pagination || pagination.total_pages <= 1) {
        if (container) container.innerHTML = '';
        return;
    }

    const fetchFunc = isLogs ? 'fetchLogs' : 'fetchMembers';
    const currentVar = isLogs ? currentSearch : currentMemberSearch;
    const safeSearch = escapeHTML(currentVar).replace(/'/g, "\\'");

    let html = '<ul class="pagination justify-content-center">';

    // Previous Button
    html += `<li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                <button class="page-link glass-pagination" onclick="${fetchFunc}('${safeSearch}', ${pagination.current_page - 1})"><i class="bi bi-chevron-left"></i></button>
             </li>`;

    // Numeric Pages
    for (let i = 1; i <= pagination.total_pages; i++) {
        const active = i === pagination.current_page ? 'active' : '';
        html += `<li class="page-item ${active}">
                    <button class="page-link glass-pagination ${active}" onclick="${fetchFunc}('${safeSearch}', ${i})">${i}</button>
                 </li>`;
    }

    // Next Button
    html += `<li class="page-item ${pagination.current_page === pagination.total_pages ? 'disabled' : ''}">
                <button class="page-link glass-pagination" onclick="${fetchFunc}('${safeSearch}', ${pagination.current_page + 1})"><i class="bi bi-chevron-right"></i></button>
             </li>`;

    html += '</ul>';
    container.innerHTML = html;
}

/**
 * 6. INITIALIZATION
 */
document.addEventListener('DOMContentLoaded', () => {
    // Generate a secure-ish session visual ID
    const sessionElement = document.getElementById('session-id');
    if (sessionElement) {
        try {
            const buffer = new Uint8Array(4);
            window.crypto.getRandomValues(buffer);
            sessionElement.textContent = Array.from(buffer)
                .map(b => b.toString(16).padStart(2, '0')).join('').toUpperCase();
        } catch (e) {
            sessionElement.textContent = "AUTH_ACTIVE";
        }
    }

    updateDashboardStats();

    if (document.getElementById('logs-table-body')) fetchLogs();
    if (document.getElementById('members-table-body') || document.getElementById('members-mobile-view')) fetchMembers();
});
