<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="<?= base_url() ?>">
            <h2 class="logo fw-bold"><span class="text-white">Variety</span><span class="text-primary">Trials</span></h2>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white <?= request()->getPath() == "/" || request()->getPath() == '' ? "active bg-gradient-primary" : "" ?>" href="<?= base_url() ?>">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Home</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Featured Trial Groups</h6>
            </li>
            <?php
            $segment = request()->getUri()->getSegments();
            foreach (get_crops() as $k => $l) : ?>
                <li class="nav-item">
                    <a class="nav-link text-white <?= !empty($segment[0]) && $segment[0] == $l['slug'] ? "active bg-gradient-primary" : "" ?>" data-bs-toggle="collapse" href="#crop<?= $k ?>">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">grass</i>
                        </div>
                        <span class="nav-link-text ms-1"><?= $l['name'] ?></span>
                    </a>
                    <div class="collapse <?= !empty($segment[0]) && $segment[0] == $l['slug'] ? "show" : "" ?>" id="crop<?= $k ?>">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link <?= request()->getPath() == $l['slug'] . "/trials" ? "active bg-gradient-secondary" : "" ?>" href="<?= base_url($l['slug'] . "/trials") ?>">Trials</a></li>
                            <li class="nav-item"> <a class="nav-link <?= request()->getPath() == $l['slug'] . "/documents" ? "active" : "" ?>" href="<?= base_url($l['slug'] . "/documents") ?>">Documents</a></li>
                        </ul>
                    </div>
                </li>
            <?php endforeach; ?>

            <li class="nav-item">
                <a class="nav-link text-white <?= request()->getPath() == "documents" ? "active bg-gradient-primary" : "" ?>" href="<?= base_url('documents') ?>">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">auto_stories</i>
                    </div>
                    <span class="nav-link-text ms-1">Documents</span>
                </a>
            </li>
        </ul>
    </div>
</aside>