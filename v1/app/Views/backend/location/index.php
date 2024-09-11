<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Locations</h4>
                <?php if (isAllowed()) : ?>
                    <div class="row">
                        <div class="col-md-2 text-center">
                            Bulk Upload
                        </div>
                        <div class="col-md-7">
                            <?= form_open(base_url('admin/location/bulk'), ['class' => 'need-validation', 'enctype' => 'multipart/form-data']) ?>
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
                            <a href="<?= base_url('uploads/location_demo.csv') ?>" class="btn btn-sm btn-info" title="Download csv structure for variety" download=""><span class="me-1"><i class="ti ti-download"></i></span> XL Format Download</a>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Loc Id</th>
                                <th>Location</th>
                                <th>Station/Farm</th>
                                <th>City</th>
                                <th>State</th>
                                <?php if (isAllowed()) : ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($locations as $k => $l) : ?>
                                <tr>
                                    <td><?= $k + 1 ?></td>
                                    <td><?= $l['code'] ?></td>
                                    <td><?= $l['location'] ?></td>
                                    <td><?= $l['farm'] ?></td>
                                    <td><?= $l['city_code'] ?></td>
                                    <td><?= $l['state_code'] ?></td>
                                    <?php if (isAllowed()) : ?>
                                        <td>
                                            <a class="text-decoration-none text-warning" href="<?= base_url('admin/location/' . $l['id'] . '/edit') ?>"><i class="ti ti-pencil-alt"></i></a>&ensp;
                                            <a class="text-decoration-none text-danger" href=" <?= base_url('admin/location/' . $l['id'] . '/delete') ?>"><i class="ti ti-trash"></i></a>
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