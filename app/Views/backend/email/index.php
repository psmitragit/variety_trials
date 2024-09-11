<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
                    <h4 class="card-title">Email Templates</h4>
                    <a href="<?= base_url('admin/email-template/create') ?>" class="btn btn-sm btn-labeled btn-primary"><span class="btn-label"><i class="ti ti-plus"></i></span>Add New</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Action</th>
                                <th>Template</th>
                                <th>Shortcode</th>
                                <th>Subject</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($templates as $k => $l) : ?>
                                <tr>
                                    <td><?= $k + 1 ?></td>
                                    <td>
                                        <a class="text-decoration-none text-warning" href="<?= base_url('admin/email-template/' . $l['id'] . '/edit') ?>"><i class="ti ti-pencil-alt" data-bs-toggle="tooltip" title="Edit"></i></a>&ensp;
                                        <a class="text-decoration-none text-danger confirmDelete" href="javascript:void(0)" data-href="<?= base_url('admin/email-template/' . $l['id'] . '/delete') ?>"><i class="ti ti-trash" data-bs-toggle="tooltip" title="Delete"></i></a>
                                    </td>
                                    <td><?= $l['name'] ?? "" ?></td>
                                    <td><?= $l['code'] ?? "" ?></td>
                                    <td><?= $l['subject'] ?? "" ?></td>
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