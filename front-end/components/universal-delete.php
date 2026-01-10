<div class="modal fade" id="universalDeleteModal" tabindex="-1" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-black bg-opacity-75 border border-secondary shadow-lg text-light backdrop-blur">
            <div class="modal-header border-bottom border-secondary bg-transparent">
                <h5 class="modal-title text-info mono small fw-bold">
                    <i class="bi bi-shield-exclamation me-2"></i>CONFIRM ACTION
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3" id="modal-icon-container">
                    <i class="bi bi-trash3 text-danger" style="font-size: 4rem; opacity: 0.8;"></i>
                </div>
                <h4 class="fw-light text-white mb-3" id="modal-main-title">Permanently Delete?</h4>
                <div class="p-3 mx-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-3 mb-3" id="modal-highlight-box">
                    <span class="text-danger small mono opacity-75 d-block mb-1" id="universal-modal-label">TARGET ITEM</span>
                    <h3 class="text-white fw-bold mono mb-0 text-break" id="universal-modal-title">
                        LOADING...
                    </h3>
                </div>
                <p class="small text-secondary mb-0 mono" id="modal-sub-text">
                    <i class="bi bi-info-circle me-1"></i> This action is irreversible.
                </p>
            </div>
            <div class="modal-footer border-top border-secondary bg-transparent justify-content-center gap-3">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold small text-light" data-bs-dismiss="modal">
                    ABORT
                </button>
                <a href="#" id="universal-btn-confirm" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center">
                    <i class="bi bi-radioactive me-2"></i> CONFIRM ACTION
                </a>
            </div>
        </div>
    </div>
</div>
