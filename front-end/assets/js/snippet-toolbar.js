// --- VARIABLES ---
let currentMode = null;
let smartModal = null;

// UI References
const containerText = document.getElementById('field-text');
const containerUrl = document.getElementById('field-url');
const containerStyle = document.getElementById('field-style');
const labelUrl = document.getElementById('label-url');
const modalTitle = document.getElementById('modalTitle');

// Input References
const inpText = document.getElementById('input-text');
const inpUrl = document.getElementById('input-url');
const inpStyle = document.getElementById('input-style');

// --- MOVE MODAL TO BODY ---
document.addEventListener("DOMContentLoaded", function() {
    const modalEl = document.getElementById('smartEditorModal');
    if(modalEl && document.body) {
        document.body.appendChild(modalEl);
    }
});

// --- UTILITIES ---
function getBuffer() {
    const buffer = document.getElementById('htmlBuffer');
    if (!buffer) {
        alert("Error: Editor textarea (#htmlBuffer) not found.");
        return null;
    }
    return buffer;
}

function updatePreview(buffer) {
    buffer.dispatchEvent(new Event('input'));
    buffer.focus();
}

// --- 1. DIRECT INSERTS ---
function insertDirect(type) {
    const buffer = getBuffer();
    if (!buffer) return;

    let snippet = '';
    if (type === 'hr') snippet = '<hr class="my-5 border-secondary opacity-25">\n';

    const start = buffer.selectionStart;
    const end = buffer.selectionEnd;
    buffer.setRangeText(snippet, start, end, 'end');
    updatePreview(buffer);
}

// --- 2. WRAPPERS ---
function wrapText(type) {
    const buffer = getBuffer();
    if (!buffer) return;

    const start = buffer.selectionStart;
    const end = buffer.selectionEnd;
    const selectedText = buffer.value.substring(start, end);

    let open = '', close = '', def = '';

    switch(type) {
        case 'h1': open = '<h1 class="display-4 text-info fw-bold">'; close = '</h1>\n'; def = 'Heading 1'; break;
        case 'h2': open = '<h2 class="text-light fw-light border-bottom border-secondary pb-2">'; close = '</h2>\n'; def = 'Heading 2'; break;
        case 'h3': open = '<h3 class="text-info fw-bold mt-4">'; close = '</h3>\n'; def = 'Heading 3'; break;
        case 'p':  open = '<p class="lead text-light">'; close = '</p>\n'; def = 'Content text...'; break;
        case 'bold': open = '<strong class="text-white">'; close = '</strong>'; def = 'Bold'; break;
        case 'italic': open = '<em>'; close = '</em>'; def = 'Italic'; break;
        case 'blockquote': open = '<figure class="text-end my-4 border-end border-info pe-3">\n <blockquote class="blockquote text-white">\n <p>'; close = '</p>\n </blockquote>\n <figcaption class="blockquote-footer text-info">Author</figcaption>\n</figure>\n'; def = 'Quote...'; break;
        case 'ul': open = '<ul class="text-light">\n <li>'; close = '</li>\n <li>Item</li>\n</ul>\n'; def = 'List Item'; break;
        case 'ol': open = '<ol class="text-light">\n <li>'; close = '</li>\n <li>Item</li>\n</ol>\n'; def = 'List Item'; break;
        case 'table': open = '<div class="table-responsive my-3">\n<table class="table table-dark border-secondary">\n <thead><tr><th>#</th><th>Header</th></tr></thead>\n <tbody>\n <tr><td>1</td><td>Data</td></tr>\n </tbody>\n</table>\n</div>\n'; def = ''; break;
    }

    const insertion = selectedText.length > 0 ? (open + selectedText + close) : (open + def + close);
    buffer.setRangeText(insertion, start, end, 'select');
    updatePreview(buffer);
}

// --- 3. OPEN MODAL ---
function openSmartModal(type) {
    currentMode = type;

    // Force check if bootstrap is loaded now that we included it above
    if (typeof bootstrap === 'undefined') {
        alert("Bootstrap is still loading... please wait a moment and try again.");
        return;
    }

    if (!smartModal) {
        smartModal = new bootstrap.Modal(document.getElementById('smartEditorModal'));
    }

    // Reset
    inpText.value = '';
    inpUrl.value = '';
    inpStyle.value = 'info';

    // Hide Fields
    containerText.classList.add('d-none');
    containerUrl.classList.add('d-none');
    containerStyle.classList.add('d-none');

    // Setup Fields
    if (type === 'button') {
        modalTitle.innerHTML = '<i class="bi bi-ui-checks me-2"></i>Configure Button';
        containerText.classList.remove('d-none');
        containerUrl.classList.remove('d-none');
        containerStyle.classList.remove('d-none');
        labelUrl.innerText = "DESTINATION URL";
        inpText.placeholder = "e.g. Read More";
    }
    else if (type === 'link') {
        modalTitle.innerHTML = '<i class="bi bi-link-45deg me-2"></i>Configure Link';
        containerText.classList.remove('d-none');
        containerUrl.classList.remove('d-none');
        labelUrl.innerText = "LINK URL";
        inpText.placeholder = "e.g. Click Here";
    }
    else if (type === 'image') {
        modalTitle.innerHTML = '<i class="bi bi-card-image me-2"></i>Insert Image';
        containerUrl.classList.remove('d-none');
        labelUrl.innerText = "IMAGE SOURCE URL";
        inpUrl.value = "https://source.unsplash.com/random/800x600";
    }
    else if (type === 'video') {
        modalTitle.innerHTML = '<i class="bi bi-youtube me-2"></i>Insert Video';
        containerUrl.classList.remove('d-none');
        labelUrl.innerText = "YOUTUBE EMBED URL";
        inpUrl.value = "https://www.youtube.com/embed/dQw4w9WgXcQ";
    }

    smartModal.show();
}

// --- 4. INSERT CONTENT ---
function confirmInsert() {
    const buffer = getBuffer();
    if (!buffer) return;

    let snippet = '';
    const url = inpUrl.value || '#';
    const text = inpText.value || 'Link';
    const style = inpStyle.value;

    if (currentMode === 'button') {
        snippet = `<a href="${url}" class="btn btn-${style} rounded-pill px-4 shadow-sm fw-bold text-uppercase my-2 text-decoration-none">${text}</a>`;
    }
    else if (currentMode === 'link') {
        snippet = `<a href="${url}" class="link-info text-decoration-none fw-bold">${text}</a>`;
    }
    else if (currentMode === 'image') {
        snippet = `<img src="${url}" class="img-fluid rounded shadow border border-secondary my-3 w-100" alt="Image">`;
    }
    else if (currentMode === 'video') {
        snippet = `<div class="ratio ratio-16x9 my-3 rounded overflow-hidden border border-secondary shadow">\n <iframe src="${url}" allowfullscreen></iframe>\n</div>`;
    }

    const start = buffer.selectionStart;
    const end = buffer.selectionEnd;
    buffer.setRangeText(snippet, start, end, 'end');

    smartModal.hide();
    updatePreview(buffer);
}
