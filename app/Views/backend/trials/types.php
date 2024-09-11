<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title pt-4 pb-4">Trial Types</h4>
                <?php if (isAllowed()) : ?>
                    <span class="text-danger"><?= validation_list_errors() ?></span>
                    <?= form_open(base_url('admin/trials/create-type'), ['method' => 'post', 'class' => 'need-validation type-form']) ?>
                    <input type="hidden" name="id" value="0">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="crop_id" class="form-label">Crop</label>
                            <select name="crop_id" id="crop_id" class="form-control form-select" required>
                                <option value="" disabled selected>Select Crop</option>
                                <?php foreach (get_crops() as $l) : ?>
                                    <option value="<?= $l['id'] ?>"><?= $l['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-5 form-group">
                            <label for="type" class="form-label">Type</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="" class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-danger cancel-edit">Cancel</button>
                            </div>
                        </div>
                    </div>
                    <?= form_close() ?>
                <?php endif; ?>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Crop</th>
                                <th>Type</th>
                                <?php if (isAllowed()) : ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($types as $k => $l) : ?>
                                <tr>
                                    <td><?= $k + 1 ?></td>
                                    <td><?= $l['crop_name'] ?></td>
                                    <td><?= $l['name'] ?></td>
                                    <?php if (isAllowed()) : ?>
                                        <td>
                                            <a class="text-decoration-none text-warning edit-type" data-crop="<?= $l['crop_id'] ?>" data-id="<?= $l['id'] ?>" data-type="<?= $l['name'] ?>" href="javascript:void(0)"><i class="ti ti-pencil-alt" data-bs-toggle="tooltip" title="Edit"></i></a>&ensp;
                                            <a class="text-decoration-none text-danger confirmDelete" href="javascript:void(0)" data-href=" <?= base_url('admin/trials/type/' . $l['id'] . '/delete') ?>"><i class="ti ti-trash" data-bs-toggle="tooltip" title="Delete"></i></a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('custom-js') ?>
<script>
    $(function() {
        $('#dataTable').DataTable({})
        $(document).on('click', '.edit-type', function() {
            $('.type-form input[name="id"]').val($(this).data('id'))
            $('.type-form select[name="crop_id"]').val($(this).data('crop'))
            $('.type-form input[name="name"]').val($(this).data('type'))
            $('html, body').animate({
                scrollTop: $("body").offset().top
            }, 500);
        })

        $('.cancel-edit').on('click', function() {
            $('.type-form input[name="id"]').val(0)
        })
    })
</script>
<?= $this->endSection() ?>