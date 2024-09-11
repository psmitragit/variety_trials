<?= $this->extend('backend/layouts/app') ?>
<?= $this->section('custom-css'); ?>
<style>
    .title-cols {
        font-size: 20px;
        margin-bottom: 20px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title pt-3 pb-3">Search Treatment</h4>
            </div>
            <div class="card-body custom-button-table">
                <div class="row mb-3 ">
                    <?= form_open('', ['class' => "col-md-6 mx-auto", 'method' => 'get']); ?>
                    <div class="form-group d-flex">
                        <input type="text" name="entry" class="form-control w-75" placeholder="Enter ..." value="<?= $entry ?? ""; ?>" required>&ensp;
                        <button class="btn  btn-primary "><i class="ti ti-search"></i></button>
                    </div>
                    <?= form_close(); ?>
                </div>
                <div>
                    <?php if (empty($treatment)) : ?>
                        <h4 class="text-danger text-center">No Treatment Found</h4>
                    <?php else : ?>
                        <div class="col-10 mx-auto">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5 class="title-cols">Crop Details</h5>
                                    <p>
                                        <strong>Crop:</strong>
                                        <?= $treatment['crop']; ?>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="title-cols">
                                        <?= $treatment['crop'] == 'Corn' ? "Hybrid" : "Variety" ?> Details
                                    </h5>
                                    <p>
                                        <strong><?= $treatment['crop'] == 'Corn' ? "Hybrid" : "Variety" ?>:</strong>
                                        <?= $treatment['variety_name']; ?>
                                    </p>
                                    <p>
                                        <strong><?= $treatment['crop'] == 'Corn' ? "Hybrid" : "Variety" ?> Code:</strong>
                                        <?= $treatment['short_name']; ?>
                                    </p>
                                    <p>
                                        <strong><?= $treatment['crop'] == 'Corn' ? "Hybrid" : "Variety" ?> Additional Name:</strong>
                                        <?= $treatment['variety_name']; ?>
                                    </p>
                                    <p>
                                        <strong><?= $treatment['crop'] == 'Corn' ? "Hybrid" : "Variety" ?> Brand/Company:</strong>
                                        <?= $treatment['brand']; ?>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="title-cols">Treatment Details </h5>
                                    <p> <strong>Entry:</strong>
                                        <?= $treatment['name']; ?>
                                    </p>
                                    <p> <strong>Herbicide:</strong>
                                        <?= $treatment['herbicide']; ?>
                                    </p>
                                    <p> <strong>Insecticide:</strong>
                                        <?= $treatment['insecticide']; ?>
                                    </p>
                                    <p> <strong>Mat:</strong>
                                        <?= $treatment['mat']; ?>
                                    </p>
                                    <p> <strong>Seed Treatment:</strong>
                                        <?= $treatment['seed_treatment']; ?>
                                    </p>
                                    <?php if ($treatment['crop'] == 'Corn') : ?>
                                        <p> <strong>Refuge:</strong>
                                            <?= $treatment['refuge'] ?? "No"; ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>