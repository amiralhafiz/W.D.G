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
    switch(type) {
        case 'hr': snippet = '<hr class="my-5 border-secondary opacity-25">\n'; break;
        case 'container': snippet = '<div class="container my-5">\n    <!-- Content here -->\n</div>\n'; break;
        case 'row': snippet = '<div class="row">\n    <div class="col-md-6">\n        <!-- Column 1 -->\n    </div>\n    <div class="col-md-6">\n        <!-- Column 2 -->\n    </div>\n</div>\n'; break;
        case 'carousel': snippet = `<div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=1200&h=400&fit=crop" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=1200&h=400&fit=crop" class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
  </button>
</div>\n`; break;
        case 'accordion': snippet = `<div class="accordion" id="accordionExample">
  <div class="accordion-item bg-dark text-light border-secondary">
    <h2 class="accordion-header">
      <button class="accordion-button bg-black text-info border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
        Accordion Item #1
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        Content for item 1.
      </div>
    </div>
  </div>
</div>\n`; break;
        case 'card': snippet = `<div class="card bg-dark text-light border-secondary shadow">
  <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?w=400&h=250&fit=crop" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title text-info">Card Title</h5>
    <p class="card-text small text-secondary">Some quick example text.</p>
    <a href="#" class="btn btn-outline-info btn-sm">Go somewhere</a>
  </div>
</div>\n`; break;
        case 'nav-tabs': snippet = `<ul class="nav nav-tabs border-secondary mb-3" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active bg-transparent text-info border-secondary" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab">Home</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link bg-transparent text-secondary border-transparent" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab">Profile</button>
  </li>
</ul>
<div class="tab-content text-light" id="myTabContent">
  <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel">Home content.</div>
  <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel">Profile content.</div>
</div>\n`; break;
        case 'alert': snippet = `<div class="alert alert-info border-0 border-start border-4 border-info shadow-sm bg-dark text-light" role="alert">
  <i class="bi bi-info-circle-fill me-2 text-info"></i> A simple info alert—check it out!
</div>\n`; break;
        case 'modal': snippet = `<!-- Modal Trigger -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Launch Modal</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-secondary">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">Modal body text...</div>
      <div class="modal-footer border-secondary">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>\n`; break;
        case 'navbar': snippet = `<nav class="navbar navbar-expand-lg navbar-dark bg-black border-bottom border-secondary">
  <div class="container-fluid">
    <a class="navbar-brand text-info fw-bold" href="#">BRAND</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Features</a></li>
      </ul>
    </div>
  </div>
</nav>\n`; break;
    }

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
        case 'table': open = '<div class="table-responsive my-3">\n<table class="table table-dark table-hover border-secondary">\n <thead class="bg-black text-info"><tr><th>#</th><th>Header</th></tr></thead>\n <tbody>\n <tr><td>1</td><td>Data</td></tr>\n </tbody>\n</table>\n</div>\n'; def = ''; break;
    }

    const insertion = selectedText.length > 0 ? (open + selectedText + close) : (open + def + close);
    buffer.setRangeText(insertion, start, end, 'select');
    updatePreview(buffer);
}

// --- 3. OPEN MODAL ---
function openSmartModal(type) {
    currentMode = type;

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
        inpUrl.value = "https://images.unsplash.com/photo-1557683316-973673baf926?w=800&fit=crop";
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
        snippet = `<a href="${url}" class="btn btn-${style} rounded-pill px-4 shadow-sm fw-bold text-uppercase my-2 text-decoration-none d-inline-block">${text}</a>`;
    }
    else if (currentMode === 'link') {
        snippet = `<a href="${url}" class="link-${style} text-decoration-none fw-bold">${text}</a>`;
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
