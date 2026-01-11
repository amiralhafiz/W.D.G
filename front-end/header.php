<nav class="navbar navbar-expand-lg navbar-dark bg-transparent border-bottom border-secondary border-opacity-25 sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase tracking-wider" href="index.php">
            <i class="bi bi-cpu-fill me-2 text-info"></i>W.D.G
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto" id="dynamic-nav-links"></ul>

            <div class="d-flex align-items-center ms-auto">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear-fill me-1"></i> Settings
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end bg-black bg-opacity-75 border-secondary" aria-labelledby="settingsDropdown">
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center" href="members.php">
                                    <span><i class="bi bi-people me-2"></i>Members</span>
                                    <span class="badge bg-primary rounded-pill ms-2"><span id="users-count">...</span></span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center" href="logs.php">
                                    <span><i class="bi bi-journal-text me-2"></i>Logs</span>
                                    <span class="badge bg-warning text-dark rounded-pill ms-2"><span id="logs-count">...</span></span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider border-secondary"></li>
                            <li><h6 class="dropdown-header text-uppercase text-light-50 small">Page Management</h6></li>
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center" href="pages-list.php">
                                    <span><i class="bi bi-list-ul me-2"></i>Manage Pages</span>
                                    <span class="badge bg-info text-dark rounded-pill ms-2"><span id="pages-count">...</span></span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="add-page.php">
                                    <i class="bi bi-plus-circle me-2"></i> Create New
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item d-flex align-items-center ms-lg-3">
                        <span class="badge bg-success rounded-pill db-status-badge" id="db-status">
                            DB: CHECKING...
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
