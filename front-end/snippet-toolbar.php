<div class="card bg-black bg-opacity-25 border-secondary mb-3">
    <div class="card-header border-bottom border-secondary bg-transparent py-2 d-flex justify-content-between align-items-center">
        <span class="text-info mono small"><i class="bi bi-tools me-2"></i>EDITOR TOOLKIT</span>
        <span class="badge bg-dark border border-secondary text-info">HTML Mode</span>
    </div>
    <div class="card-body p-2 d-flex flex-wrap gap-2 align-items-center">

        <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-outline-secondary text-info fw-bold" onclick="wrapText('h1')">H1</button>
            <button type="button" class="btn btn-outline-secondary text-info fw-bold" onclick="wrapText('h2')">H2</button>
            <button type="button" class="btn btn-outline-secondary text-info fw-bold" onclick="wrapText('h3')">H3</button>
        </div>
        <div class="vr bg-secondary mx-1"></div>

        <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-outline-secondary text-light" title="Paragraph" onclick="wrapText('p')"><i class="bi bi-paragraph"></i></button>
            <button type="button" class="btn btn-outline-secondary text-light" title="Bold" onclick="wrapText('bold')"><i class="bi bi-type-bold"></i></button>
            <button type="button" class="btn btn-outline-secondary text-light" title="Italic" onclick="wrapText('italic')"><i class="bi bi-type-italic"></i></button>
            <button type="button" class="btn btn-outline-secondary text-warning" title="Blockquote" onclick="wrapText('blockquote')"><i class="bi bi-quote"></i></button>
        </div>
        <div class="vr bg-secondary mx-1"></div>

        <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-outline-secondary text-success" onclick="openSmartModal('link')"><i class="bi bi-link-45deg"></i> Link</button>
            <button type="button" class="btn btn-outline-secondary text-info" onclick="openSmartModal('button')"><i class="bi bi-ui-checks"></i> Btn</button>
            <button type="button" class="btn btn-outline-secondary text-light" title="Divider" onclick="insertDirect('hr')"><i class="bi bi-dash-lg"></i></button>
        </div>
        <div class="vr bg-secondary mx-1"></div>

        <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-outline-secondary text-light" onclick="wrapText('ul')"><i class="bi bi-list-ul"></i></button>
            <button type="button" class="btn btn-outline-secondary text-light" onclick="wrapText('ol')"><i class="bi bi-list-ol"></i></button>
            <button type="button" class="btn btn-outline-secondary text-light" onclick="wrapText('table')"><i class="bi bi-table"></i></button>
        </div>
        <div class="vr bg-secondary mx-1"></div>

        <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-outline-secondary text-warning" onclick="openSmartModal('image')"><i class="bi bi-card-image"></i></button>
            <button type="button" class="btn btn-outline-secondary text-danger" onclick="openSmartModal('video')"><i class="bi bi-youtube"></i></button>
        </div>
    </div>
</div>

<div class="modal fade" id="smartEditorModal" tabindex="-1" aria-hidden="true" style="z-index: 10000;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-light border-secondary shadow-lg">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-info mono" id="modalTitle">
                    <i class="bi bi-sliders me-2"></i>Configure
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3 d-none" id="field-text">
                    <label class="form-label small text-secondary fw-bold">DISPLAY TEXT / LABEL</label>
                    <input type="text" id="input-text" class="form-control bg-black bg-opacity-50 border-secondary text-white shadow-none" placeholder="e.g. Read More">
                </div>

                <div class="mb-3 d-none" id="field-url">
                    <label class="form-label small text-secondary fw-bold" id="label-url">DESTINATION URL</label>
                    <input type="text" id="input-url" class="form-control bg-black bg-opacity-50 border-secondary text-info shadow-none" placeholder="https://...">
                </div>

                <div class="mb-3 d-none" id="field-style">
                    <label class="form-label small text-secondary fw-bold">STYLE</label>
                    <select id="input-style" class="form-select bg-black bg-opacity-50 border-secondary text-white shadow-none">
                        <option value="info">Info (Neon Blue)</option>
                        <option value="success">Success (Green)</option>
                        <option value="warning">Warning (Yellow)</option>
                        <option value="danger">Danger (Red)</option>
                        <option value="light">Light (White)</option>
                        <option value="outline-info">Outline Blue</option>
                    </select>
                </div>

            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-info fw-bold px-4 rounded-pill" onclick="confirmInsert()">
                    <i class="bi bi-plus-lg me-1"></i> Insert
                </button>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/snippet-toolbar.js"></script>
