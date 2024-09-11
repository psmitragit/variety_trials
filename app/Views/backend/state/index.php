<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row ">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
                    <h4 class="card-title">States</h4>
                </div>
            </div>
            <div class="card-body">
                <?php if (isAllowed()) : ?>
                    <div class="row  align-items-center mb-4">
                        <div class="col-md-2 text-center">
                            <span class="heading" id="form_heading">Create</span>
                        </div>
                        <div class="col-md-10">
                            <div class="text-danger"><?= validation_list_errors() ?></div>
                            <?= form_open(base_url('admin/location/states/save'), ['class' => 'need-validation', 'id' => "create_form",  'enctype' => 'multipart/form-data']) ?>
                            <input type="hidden" name="id" value="0">
                            <div class="form-group row mb-0 align-items-center">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="code" placeholder="Enter State Code" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="name" placeholder="Enter State Name" required>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn  btn-primary"><span class="me-1"><i class="ti ti-save"></i></span> Save</button>
                                    <button class="btn  btn-danger" type="reset"><span class="me-1"><i class="ti ti-back-right"></i></span> Cancel</button>
                                </div>
                            </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Action</th>
                                <th>State Code</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($states as $k => $l) : ?>
                                <tr>
                                    <td><?= $k + 1 ?></td>
                                    <td>
                                        <a class="text-decoration-none text-warning edit-state" href="javascript:void(0)" data-id="<?= $l['id'] ?>" data-code="<?= $l['code'] ?? "" ?>" data-name="<?= $l['name'] ?? "" ?>"><i class="ti ti-pencil-alt" data-bs-toggle="tooltip" title="Edit"></i></a>&ensp;
                                        <a class="text-decoration-none text-danger confirmDelete" href="javascript:void(0)" data-href=" <?= base_url('admin/location/states/' . $l['id'] . '/delete') ?>"><i class="ti ti-trash" data-bs-toggle="tooltip" title="Delete"></i></a>
                                    </td>
                                    <td><?= $l['code'] ?? "" ?></td>
                                    <td><?= $l['name'] ?? "" ?></td>
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
        $('#dataTable').DataTable();

        $(document).on('click', '.edit-state', function() {

            $('#form_heading').text('Edit')
            $('#create_form [name="id"]').val($(this).attr('data-id'))
            $('#create_form [name="code"]').val($(this).attr('data-code'))
            $('#create_form [name="name"]').val($(this).attr('data-name'))

            $(document).scrollTop("#form_heading")
        })

        $(document).on('click', '#create_form button[type="reset"]', function() {
            $('#create_form [name="id"]').val(0)
            $('#form_heading').text('Create')
        })
    })
</script>
<?= $this->endSection() ?>