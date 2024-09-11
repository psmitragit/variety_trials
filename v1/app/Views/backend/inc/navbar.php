<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div>
            <a class="navbar-brand brand-logo" href="<?= base_url('admin') ?>">
                <h2 class="logo fw-bold"><span>Variety</span><span class="text-primary">Trials</span></h2>
            </a>
            <a class="navbar-brand brand-logo-mini" href="<?= base_url('admin') ?>">
                <h2 class="logo fw-bold bg-primary text-white px-2 py-1 rounded-circle">V</h2>
            </a>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
            <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                <h2 class="welcome-text">Welcome, <span class="text-black fw-bold"><?= auth_admin()['name'] ?></span></h2>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <img class="img-xs rounded-circle" src="<?= base_url('backend') ?>/images/faces/placeholder-face.png" alt="Profile image"> </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <div class="dropdown-header text-center">
                        <img class="img-md rounded-circle" src="<?= base_url('backend') ?>/images/faces/placeholder-face.png" alt="Profile image">
                        <p class="mb-1 mt-3 font-weight-semibold"><?= auth_admin()['name'] ?></p>
                        <p class="fw-light text-muted mb-0"><?= auth_admin()['email'] ?></p>
                    </div>
                    <a class="dropdown-item" href="<?= base_url('admin/change-password') ?>"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> Change Password <span class="badge badge-pill badge-danger">1</span></a>
                    <a class="dropdown-item" href="<?= base_url('admin/logout') ?>"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>