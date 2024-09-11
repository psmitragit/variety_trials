<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title pt-3 pb-3">Manage Uploads</h4>
            </div>
            <div class="card-body">
                <div>
                    <?= form_open(base_url('admin/uploads/create'), ['class' => 'need-validation', 'enctype' => 'multipart/form-data']) ?>
                    <div class="form-group row align-items-end">
                        <div class="col-md-4">
                            <label for="cropId">Select Crop</label>
                            <select name="crop_id" id="cropId" class="form-control select2" required>
                                <option value="" selected disabled>SELECT</option>
                                <?php foreach (get_crops() as $l) : ?>
                                    <option value="<?= $l['id'] ?>"><?= $l['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="year">Select Year</label>
                            <select name="year" id="year" class="form-control select2" required>
                                <option value="" selected disabled>SELECT</option>
                                <?php for ($i = date('Y'); $i >= 2000; $i--) : ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="state_code">Select State</label>
                            <select name="state_code" id="state_code" class="form-control select2" required>
                                <option value="" selected disabled>SELECT</option>
                                <?php foreach ($states as $l) : ?>
                                    <option value="<?= $l['code'] ?>"><?= $l['code'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mt-4">
                            <label for="year">File Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-4 mt-4">
                            <label for="uploadPDF">Select PDF</label>
                            <input type="file" id="uploadPDF" class="form-control" name="upload" accept=".pdf" required>
                        </div>
                        <div class="col-md-1 mt-4">
                            <label>&ensp;</label>
                            <button class="btn  btn-primary">Save</button>
                        </div>
                    </div>
                    <?= form_close() ?>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Action</th>
                                <th>Name</th>
                                <th>Crop</th>
                                <th>Year</th>
                                <th>State</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($uploads as $k => $l) : ?>
                                <tr>
                                    <td><?= $k + 1 ?></td>
                                    <td>
                                        <a class="text-decoration-none" href="<?= base_url($l['url']) ?>" target="_blank"><i class="ti ti-eye" data-bs-toggle="tooltip" title="View PDF"></i></a>&ensp;
                                        <!-- <a class="text-decoration-none text-warning" href="<?= base_url('admin/uploads/' . $l['id'] . '/edit') ?>"><i class="ti ti-pencil-alt" title="Edit"></i></a>&ensp; -->
                                        <a class="text-decoration-none text-danger confirmDelete" href="javascript:void(0)" data-href="<?= base_url('admin/uploads/' . $l['id'] . '/delete') ?>"><i class="ti ti-trash" data-bs-toggle="tooltip" title="Delete"></i></a>
                                    </td>
                                    <td><?= $l['title'] ?></td>
                                    <td><?= $l['crop'] ?></td>
                                    <td><?= $l['year'] ?></td>
                                    <td><?= $l['state_code'] ?></td>
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
    })
</script>
<?= $this->endSection() ?>