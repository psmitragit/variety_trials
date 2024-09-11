<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
                    <h4 class="card-title">Crops</h4>
                    <?php if (isAllowed()) : ?>
                        <a href="<?= base_url('admin/crop/create') ?>" class="btn btn-sm btn-labeled btn-primary"><span class="btn-label"><i class="ti ti-plus"></i></span>Add New</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">

                <?php /*if (isAllowed()) : ?>
                    <div class="row">
                        <div class="col-md-2 text-center">
                            Bulk Upload
                        </div>
                        <div class="col-md-7">
                            <?= form_open(base_url('admin/crop/bulk'), ['class' => 'need-validation', 'enctype' => 'multipart/form-data']) ?>
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
                            <a href="<?= base_url('uploads/crop_demo.csv') ?>" class="btn btn-sm btn-info " title="Download csv structure for crop" download=""> <span class="me-1"><i class="ti ti-download"></i></span> XL Format Download</a>
                        </div>
                    </div>
                <?php endif; */ ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <?php if (isAllowed()) : ?>
                                    <th>Action</th>
                                <?php endif; ?>
                                <th>Crop Name</th>
                                <th>Variables</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($crops as $k => $l) : ?>
                                <tr>
                                    <td><?= $k + 1 ?></td>
                                    <?php if (isAllowed()) : ?>
                                        <td>
                                            <!-- <a class="text-decoration-none" href="<?= base_url('admin/crop/' . $l['id'] . '/edit') ?>"><i class="ti ti-eye" title="View Variables"></i></a>&ensp; -->
                                            <a class="text-decoration-none text-warning" href="<?= base_url('admin/crop/' . $l['id'] . '/edit') ?>"><i class="ti ti-pencil-alt" data-bs-toggle="tooltip" title="Edit"></i></a>&ensp;
                                            <a class="text-decoration-none text-danger confirmDelete" href="javascript:void(0)" data-href="<?= base_url('admin/crop/' . $l['id'] . '/delete') ?>"><i class="ti ti-trash" data-bs-toggle="tooltip" title="Delete"></i></a>
                                        </td>
                                    <?php endif; ?>
                                    <td><?= $l['name'] ?></td>
                                    <td><?= $l['variables'] ?></td>
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
        // $('#dataTable').DataTable({
        //     dom: 'Bfrtip',
        //     buttons: [
        //         'pageLength',
        //         'csvHtml5',
        //         'pdfHtml5'
        //     ]
        // });
    })
</script>
<?= $this->endSection() ?>