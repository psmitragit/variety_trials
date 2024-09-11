<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Moderators</h4>
                    <a href="<?= base_url('admin/user/create') ?>" class="btn btn-sm btn-labeled btn-primary"><span class="btn-label"><i class="ti ti-plus"></i></span>Add New</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Action</th>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Type</th>
                                <th>Crops</th>
                                <th>University</th>
                                <th>State</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $k => $l) : ?>
                                <tr>
                                    <td><?= $k + 1 ?></td>
                                    <td>
                                        <a class="text-decoration-none text-warning" href="<?= base_url('admin/user/' . $l['id'] . '/edit') ?>"><i class="ti ti-pencil-alt" data-bs-toggle="tooltip" title="Edit"></i></a>&ensp;
                                        <a class="text-decoration-none text-danger confirmDelete" href="javascript:void(0)" data-href="<?= base_url('admin/user/' . $l['id'] . '/delete') ?>"><i class="ti ti-trash" data-bs-toggle="tooltip" title="Delete"></i></a>
                                    </td>
                                    <td><?= $l['username'] ?></td>
                                    <td><?= $l['name'] ?></td>
                                    <td><?= $l['email'] ?></td>
                                    <td><?= $l['phone'] ?></td>
                                    <td><?= $l['type'] == 0 ? "Super Admin" : "Staff" ?></td>
                                    <td><?= get_crops_by_id_string($l['crop'] ?? "") ?? "---"; ?></td>
                                    <td><?= $l['university'] ?? "---"; ?></td>
                                    <td><?= $l['state_name'] ?? "---"; ?></td>
                                    <td><?= $l['status'] == 1 ? '<span class="badge badge-success ">Approved</span>' : '<span class="badge badge-danger">Unapproved</span>' ?></td>
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