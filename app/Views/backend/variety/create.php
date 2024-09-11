<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title pb-3 pt-3"><?= !empty($variety) ? "Edit" : "Create" ?> Variety</h4>
            </div>
            <div class="card-body">
                <div class="text-danger"><?= validation_list_errors() ?></div>
                <?= form_open('', ['method' => 'post', 'class' => 'need-validation', 'id' => 'varietyForm']) ?>
                <input type="hidden" name="id" value="<?= $variety['id'] ?? 0 ?>">
                <div class="row">
                    <div class="form-group col-md-6 pb-2">
                        <label for="crop_id">Crop <sup class="text-danger">*</sup></label>
                        <select name="crop_id" id="crop_id" required class="form-control select2">
                            <option value="" disabled selected>Select Crop</option>
                            <?php foreach (get_crops() as $l) : ?>
                                <option value="<?= $l['id'] ?>" <?= (old('crop_id') ?? $variety['crop_id'] ?? "") == $l['id'] ? "selected" : "" ?>><?= $l['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if (isAllowed()) : ?>
                        <div class="form-group col-md-6 pb-2">
                            <label for="shortcode">Variety/Hybrid ID <sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control" id="shortcode" value="<?= old('code') ?? $variety['code'] ?? "" ?>" name="code" placeholder="Variety ID" required>
                        </div>
                    <?php endif; ?>
                    <div class="form-group col-md-6 pb-2">
                        <label for="brand">Brand <sup class="text-danger">*</sup></label>
                        <?php $brand = old('brand') ?? $variety['brand'] ?? ""; ?>
                        <select name="brand" class="form-control select2" id="brand" required>
                            <option value="" disabled selected>---Select---</option>
                            <?php foreach ($brands as $l) : ?>
                                <option value="<?= $l['name'] ?>" <?= $l['name'] == $brand ? "selected" : "" ?>><?= $l['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6 pb-2">
                        <label for="short_name">Variety/Hybrid <sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" id="short_name" name="short_name" value="<?= old('short_name') ?? $variety['short_name'] ?? "" ?>" placeholder="Variety" required>
                    </div>
                    <div class="form-group col-md-6 pb-2">
                        <label for="additional_name">Variety/Hybrid Additional</label>
                        <input type="text" class="form-control" id="additional_name" name="additional_name" value="<?= old('additional_name') ?? $variety['additional_name'] ?? "" ?>" placeholder="Variety Additional">
                    </div>
                    <!-- <div class="form-group col-md-6 pb-2">
                        <label for="name">Variety/Hybrid Original</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?? $variety['name'] ?? "" ?>" placeholder="Variety Original">
                    </div> -->
                    <?php if (!empty($variety)) : ?>
                        <div class="form-group col-md-6 ">
                            <label for="status">Status <sup class="text-danger">*</sup></label>
                            <select name="status" id="status" class="form-control">
                                <option value="1" <?= $variety['status'] == 0 ? "selected" : "" ?>>Approved</option>
                                <option value="0" <?= $variety['status'] == 0 ? "selected" : "" ?>>Unapproved</option>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <a class="btn btn-light" href="<?= base_url('admin/variety') ?>">Cancel</a>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>