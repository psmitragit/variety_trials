<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Variety</h4>
                <div class="row">
                    <div class="col-md-4">
                        Bulk Upload
                    </div>
                    <div class="col-md-8">
                        <?= form_open(base_url('admin/variety/bulk'), ['class' => 'need-validation', 'enctype' => 'multipart/form-data']) ?>
                        <div class="form-group row">
                            <div class="col-md-9">
                                <input type="file" class="form-control" name="bulk_file" accept=".csv" required>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary">Upload</button>
                            </div>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Variety ID</th>
                                <th>Brand</th>
                                <th>Variety</th>
                                <th>Variety Additional</th>
                                <th>Herbicide Package</th>
                                <th>Variety Original</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($varieties as $k => $l) : ?>
                                <tr>
                                    <td><?= $k + 1 ?></td>
                                    <td><?= $l['code'] ?></td>
                                    <td><?= $l['brand'] ?></td>
                                    <td><?= $l['short_name'] ?></td>
                                    <td><?= $l['additional_name'] ?></td>
                                    <td></td>
                                    <td><?= $l['name'] ?></td>
                                    <td>
                                        <a class="text-decoration-none" href="<?= base_url('admin/variety/' . $l['id'] . '/edit') ?>"><i class="ti ti-eye" title="View Variables"></i></a>&ensp;
                                        <a class="text-decoration-none" href="<?= base_url('admin/variety/' . $l['id'] . '/edit') ?>"><i class="ti ti-pencil-alt" title="Edit"></i></a>&ensp;
                                        <a class="text-decoration-none" href="<?= base_url('admin/variety/' . $l['id'] . '/delete') ?>"><i class="ti ti-trash" title="Delete"></i></a>
                                    </td>
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

<?= $this->endSection() ?>