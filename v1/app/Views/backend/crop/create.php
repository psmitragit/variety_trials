<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?= !empty($crop) ? "Edit" : "Create" ?> Crop</h4>
                <div class="text-danger"><?= validation_list_errors() ?></div>
                <?= form_open('', ['method' => 'post', 'class' => 'need-validation', 'id' => 'varietyForm']) ?>
                <input type="hidden" name="id" value="<?= $crop['id'] ?? 0 ?>">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="shortcode">Crop Name</label>
                        <input type="text" class="form-control" id="shortcode" value="<?= old('name') ?? $crop['name'] ?? "" ?>" name="name" placeholder="Crop Name" required>
                    </div>
                    <div class="mb-4 col-md-12">
                        <p class="fw-bold">Variables</p>
                        <div id="varibale-container" class="mt-2 row">
                            <?php foreach ($variables as $k => $v) : ?>
                                <div class="col-md-6 var-container">
                                    <div class="row form-group">
                                        <div class="col-md-10"><input type="text" class="form-control" name="v_title[<?= $v['id'] ?? $k ?>]" value="<?= $v['name'] ?? "" ?>" placeholder="Enter variable name..." required></div>
                                        <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm delete"><i class="ti ti-trash"></i></button></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary" id="addMore"><span class="me-1"><i class="ti ti-plus"></i></span>Add variable</button>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <a href="<?= base_url('admin/crop') ?>" class="btn btn-danger">Cancel</a>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>


<?= $this->section('custom-js') ?>
<script>
    $(function() {

        let i = "<?= !empty($variables) ? 999999 : 0 ?>";
        $('#addMore').click(function() {
            $('#varibale-container').append(`<div class="col-md-6 var-container"><div class="row form-group">
                                <div class="col-md-10"><input type="text" class="form-control" name="v_title[${i}]" placeholder="Enter variable name..." required></div>
                                <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm delete"><i class="ti ti-trash"></i></button></div>
                            </div></div>`);
            i++;
        })
        $(document).on('click', '.delete', function() {
            $(this).parents('.var-container').remove();
        })
    })
</script>
<?= $this->endSection() ?>