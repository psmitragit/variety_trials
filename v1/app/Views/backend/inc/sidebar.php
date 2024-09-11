<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item <?= request()->getPath() == "admin" ? "active" : "" ?>">
            <a class="nav-link" href="<?= base_url('admin') ?>">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <?php if (isAllowed()) : ?>
            <li class="nav-item nav-category">Crops</li>
            <?php foreach (get_crops() as $k => $l) : ?>
                <li class="nav-item <?= request()->getPath() == "admin/crop/" . $l['id'] . "/trials" ? "active" : "" ?>">
                    <a class="nav-link" data-bs-toggle="collapse" href="#crop<?= $k ?>" aria-expanded="false" aria-controls="ui-basic">
                        <i class="menu-icon mdi mdi-floor-plan"></i>
                        <span class="menu-title"><?= $l['name'] ?></span>
                        <i class="menu-arrow"></i>
                    </a>

                    <div class="collapse <?= request()->getPath() == "admin/crop/" . $l['id'] . "/trials" ? "show" : "" ?>" id="crop<?= $k ?>">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link <?= request()->getPath() == "admin/crop/" . $l['id'] . "/trials" ? "active" : "" ?>" href="<?= base_url('admin/crop/' . $l['id'] . '/trials') ?>">Trials</a></li>
                        </ul>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
        <li class="nav-item nav-category">Management</li>
        <?php $segment = request()->getUri()->getSegments(); ?>
        <li class="nav-item <?= !empty($segment[1]) && $segment[1] == "crop" && empty($segment[3]) ? "active" : "" ?>">
            <a class="nav-link" data-bs-toggle="collapse" href="#crop" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-floor-plan"></i>
                <span class="menu-title">Manage Crops</span>
                <i class="menu-arrow"></i>
            </a>


            <div class="collapse <?= !empty($segment[1]) && $segment[1] == "crop" && empty($segment[3])  ? "show" : "" ?>" id="crop">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item <?= request()->getPath() == "admin/crop" ? "active" : "" ?>"> <a class="nav-link" href="<?= base_url('admin/crop') ?>">View All</a></li>
                    <?php if (isAllowed()) : ?>
                        <li class="nav-item <?= request()->getPath() == "admin/crop/create" ? "active" : "" ?>"> <a class="nav-link" href="<?= base_url('admin/crop/create') ?>">Create</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </li>
        <li class="nav-item <?= request()->getUri()->getSegment(2) == "location" ? "active" : "" ?>">
            <a class="nav-link" data-bs-toggle="collapse" href="#location" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-floor-plan"></i>
                <span class="menu-title">Manage Locations</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?= request()->getUri()->getSegment(2) == "location" ? "show" : "" ?>" id="location">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link <?= request()->getPath() == "admin/location" ? "active" : "" ?>" href="<?= base_url('admin/location') ?>">View All</a></li>
                    <?php if (isAllowed()) : ?>
                        <li class="nav-item"> <a class="nav-link <?= request()->getPath() == "admin/location/create" ? "active" : "" ?>" href="<?= base_url('admin/location/create') ?>">Create</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </li>
        <li class="nav-item <?= request()->getUri()->getSegment(2) == "variety" ? "active" : "" ?>">
            <a class="nav-link" data-bs-toggle="collapse" href="#variety" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-floor-plan"></i>
                <span class="menu-title">Manage Variety</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?= request()->getUri()->getSegment(2) == "variety" ? "show" : "" ?>" id="variety">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link <?= request()->getPath() == "admin/variety" ? "active" : "" ?>" href="<?= base_url('admin/variety') ?>">View All</a></li>
                    <?php if (isAllowed()) : ?>
                        <li class="nav-item"> <a class="nav-link <?= request()->getPath() == "admin/variety/create" ? "active" : "" ?>" href="<?= base_url('admin/variety/create') ?>">Create</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </li>
        <li class="nav-item <?= request()->getUri()->getSegment(2) == "trials" ? "active" : "" ?>">
            <a class="nav-link" data-bs-toggle="collapse" href="#trials" aria-expanded="false" aria-controls="ui-basic">
                <i class="menu-icon mdi mdi-floor-plan"></i>
                <span class="menu-title">Manage Trials</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse <?= request()->getUri()->getSegment(2) == "trials" ? "show" : "" ?>" id="trials">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link <?= request()->getPath() == "admin/trials" ? "active" : "" ?>" href="<?= base_url('admin/trials') ?>">View All</a></li>
                    <li class="nav-item"> <a class="nav-link <?= request()->getPath() == "admin/trials/create" ? "active" : "" ?>" href="<?= base_url('admin/trials/create') ?>">Create</a></li>
                </ul>
            </div>
        </li>
        <?php if (isAllowed()) : ?>
            <li class="nav-item <?= request()->getPath() == "admin/uploads" ? "active" : "" ?>">
                <a class="nav-link" href="<?= base_url('admin/uploads') ?>" aria-expanded="false">
                    <i class="menu-icon mdi mdi-floor-plan"></i>
                    <span class="menu-title">Manage PDF Uploads</span>
                </a>
            </li>

            <li class="nav-item <?= request()->getUri()->getSegment(2) == "user" ? "active" : "" ?>">
                <a class="nav-link" data-bs-toggle="collapse" href="#user" aria-expanded="false" aria-controls="ui-basic">
                    <i class="menu-icon mdi mdi-floor-plan"></i>
                    <span class="menu-title">Manage Users</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse <?= request()->getUri()->getSegment(2) == "user" ? "show" : "" ?>" id="user">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link <?= request()->getPath() == "admin/user" ? "active" : "" ?>" href="<?= base_url('admin/user') ?>">View All</a></li>
                        <li class="nav-item"> <a class="nav-link <?= request()->getPath() == "admin/user/create" ? "active" : "" ?>" href="<?= base_url('admin/user/create') ?>">Create</a></li>
                    </ul>
                </div>
            </li>
        <?php endif; ?>
    </ul>
</nav>