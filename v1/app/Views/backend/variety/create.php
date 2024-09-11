<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?= !empty($variety) ? "Edit" : "Create" ?> Variety</h4>
                <div class="text-danger"><?= validation_list_errors() ?></div>
                <?= form_open('', ['method' => 'post', 'class' => 'need-validation', 'id' => 'varietyForm']) ?>
                <input type="hidden" name="id" value="<?= $variety['id'] ?? 0 ?>">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="shortcode">Variety ID</label>
                        <input type="text" class="form-control" id="shortcode" value="<?= old('code') ?? $variety['code'] ?? "" ?>" name="code" placeholder="Variety ID" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="brand">Brand</label>
                        <?php $brand = old('brand') ?? $variety['brand'] ?? ""; ?>
                        <select name="brand" class="form-control select2" id="brand" required>
                            <option value="" disabled selected>---Select---</option>
                            <?php foreach ($brands as $l) : ?>
                                <option value="<?= $l['name'] ?>" <?= $l['name'] == $brand ? "selected" : "" ?>><?= $l['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="short_name">Variety</label>
                        <input type="text" class="form-control" id="short_name" name="short_name" value="<?= old('short_name') ?? $variety['short_name'] ?? "" ?>" placeholder="Variety" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="additional_name">Variety Additional</label>
                        <input type="text" class="form-control" id="additional_name" name="additional_name" value="<?= old('additional_name') ?? $variety['additional_name'] ?? "" ?>" placeholder="Variety Additional">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="name">Variety Original</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?? $variety['name'] ?? "" ?>" placeholder="Variety Original">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="herbicide">Herbicide Package</label>
                        <input type="text" class="form-control" id="herbicide" name="herbicide" value="<?= old('herbicide') ?? $variety['herbicide'] ?? "" ?>" placeholder="Herbicide Package">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <a class="btn btn-light" href="<?= base_url('admin/variety') ?>">Cancel</a>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>