<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
    <div class="container-fluid py-1 px-3 d-flex justify-content-between align-items-center">

        <!-- Left Section: Breadcrumbs -->
        <div class="navbar-left">
            <?php if (!(request()->getPath() == '/' || request()->getPath() == '')) : ?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="<?= site_url(); ?>">Home</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
                            <?= $this->renderSection('title') ?>
                        </li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0"><?= $this->renderSection('title') ?></h6>
                </nav>
            <?php endif; ?>
        </div>

        <!-- Right Section: Hamburger -->
        <div class="navbar-right ms-auto">
            <button class="btn hamburger-toggle d-xl-none" id="sidenavToggle">
                <i class="fas fa-bars fa-xl"></i>
            </button>
        </div>

    </div>
</nav>
<!-- End Navbar -->