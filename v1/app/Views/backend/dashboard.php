<?= $this->extend('backend/layouts/app'); ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="d-sm-flex align-items-center justify-content-between border-bottom">
            </div>
            <div class="row my-5 text-white">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info rounded-3">
                        <div class="inner px-4 py-3">
                            <h3><?= count(get_crops()) ?></h3>
                            <p>Crops</p>
                        </div>
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="75" height="75" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);">
                                <path d="m22 3.41-.12-1.26-1.2.4a13.84 13.84 0 0 1-6.41.64 11.87 11.87 0 0 0-6.68.9A7.23 7.23 0 0 0 3.3 9.5a9 9 0 0 0 .39 4.58 16.6 16.6 0 0 1 1.18-2.2 9.85 9.85 0 0 1 4.07-3.43 11.16 11.16 0 0 1 5.06-1A12.08 12.08 0 0 0 9.34 9.2a9.48 9.48 0 0 0-1.86 1.53 11.38 11.38 0 0 0-1.39 1.91 16.39 16.39 0 0 0-1.57 4.54A26.42 26.42 0 0 0 4 22h2a30.69 30.69 0 0 1 .59-4.32 9.25 9.25 0 0 0 4.52 1.11 11 11 0 0 0 4.28-.87C23 14.67 22 3.86 22 3.41z"></path>
                            </svg>
                        </div>
                        <a href="<?= base_url('admin/crop') ?>" class="small-box-footer">More info <i class="ti ti-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-success rounded-3">
                        <div class="inner p-3">
                            <h3><?= $totalVariety ?></h3>
                            <p>Varieties</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="<?= base_url('admin/variety') ?>" class="small-box-footer">More info <i class="ti ti-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-warning rounded-3">
                        <div class="inner p-3">
                            <h3><?= $totalLocation ?></h3>
                            <p>Locations</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="<?= base_url('admin/location') ?>" class="small-box-footer">More info <i class="ti ti-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-danger rounded-3">
                        <div class="inner p-3">
                            <h3><?= $totalTrial ?></h3>
                            <p>Trials</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="<?= base_url('admin/trials') ?>" class="small-box-footer">More info <i class="ti ti-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>