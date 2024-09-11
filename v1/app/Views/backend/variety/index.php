<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Variety</h4>
                <?php if (isAllowed()) : ?>
                    <div class="row">
                        <div class="col-md-2">
                            Bulk Upload
                        </div>
                        <div class="col-md-7">
                            <?= form_open(base_url('admin/variety/bulk'), ['class' => 'need-validation', 'enctype' => 'multipart/form-data']) ?>
                            <div class="form-group row">
                                <div class="col-md-9">
                                    <input type="file" class="form-control" name="bulk_file" accept=".csv" required>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-sm btn-primary"><span class="me-1"><i class="ti ti-upload"></i></span> Upload</button>
                                </div>
                            </div>
                            <?= form_close() ?>
                        </div>
                        <div class="col-md-3 text-end">
                            <a href="<?= base_url('uploads/variety_demo.csv') ?>" class="btn btn-sm btn-info" title="Download csv structure for variety" download=""><span class="me-1"><i class="ti ti-download"></i></span> XL Format Download</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-body">

                <?= form_open() ?>
                <?php if (isAllowed()) : ?>
                    <div class="row mb-3">
                        <div class="col-2">
                            <a href="<?= base_url('admin/variety/create') ?>" class="btn btn-sm btn-primary"><i class="ti ti-plus"></i>Add New</a>
                        </div>
                        <div class="col-md-2">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="bulkApproveCollapse" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Bulk Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="bulkApproveCollapse">
                                    <button class="dropdown-item" formaction="<?= base_url('admin/variety/bulk-delete') ?>">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <?php if (isAllowed()) : ?>
                                    <th><input type="checkbox" id="checkAll"></th>
                                <?php else : ?>
                                    <th> #</th>
                                <?php endif; ?>
                                <th>Variety ID</th>
                                <th>Brand</th>
                                <th>Variety</th>
                                <th>Variety Additional</th>
                                <th>Herbicide Package</th>
                                <th>Variety Original</th>
                                <?php if (isAllowed()) : ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($varieties as $k => $l) : ?>
                                <tr>
                                    <?php if (isAllowed()) : ?>
                                        <td><input type="checkbox" name="ids[]" class="selectId" value="<?= $l['id'] ?>"></td>
                                    <?php else : ?>
                                        <td><?= $k + 1 ?></td>
                                    <?php endif; ?>
                                    <td><?= $l['code'] ?></td>
                                    <td><?= $l['brand'] ?></td>
                                    <td><?= $l['short_name'] ?></td>
                                    <td><?= $l['additional_name'] ?></td>
                                    <td></td>
                                    <td><?= $l['name'] ?></td>
                                    <?php if (isAllowed()) : ?>
                                        <td>
                                            <a class="text-decoration-none text-warning" href="<?= base_url('admin/variety/' . $l['id'] . '/edit') ?>"><i class="ti ti-pencil-alt" title="Edit"></i></a>&ensp;
                                            <a class="text-decoration-none text-danger" href="<?= base_url('admin/variety/' . $l['id'] . '/delete') ?>"><i class="ti ti-trash" title="Delete"></i></a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('custom-js') ?>
<script>
    $(function() {
        //Check All
        $('#checkAll').click(function() {
            if ($(this).is(':checked')) {
                $('.selectId').prop('checked', true)
            } else {
                $('.selectId').prop('checked', false)
            }
        })
        //single check
        $(document).on('click', '.selectId', function() {
            let count = 0;
            $('.selectId').each(function(i, e) {
                if (!$(e).is(':checked')) {
                    count++
                }
                if (count > 0) {
                    $('#checkAll').prop('checked', false)
                } else {
                    $('#checkAll').prop('checked', true)
                }
            })
        })



        $('#dataTable').DataTable({
            order: [
                [2, 'desc']
            ],
            columnDefs: [{
                'targets': [0],
                'orderable': false,
            }]
        });
    })
</script>
<?= $this->endSection() ?>