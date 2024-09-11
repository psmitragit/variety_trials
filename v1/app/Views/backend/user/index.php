<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h4 class="card-title">Moderators</h4>
                    <a href="<?= base_url('admin/user/create') ?>" class="btn btn-sm btn-primary"><i class="ti ti-plus"></i>Add New</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $k => $l) : ?>
                                <tr>
                                    <td><?= $k + 1 ?></td>
                                    <td><?= $l['username'] ?></td>
                                    <td><?= $l['name'] ?></td>
                                    <td><?= $l['email'] ?></td>
                                    <td><?= $l['phone'] ?></td>
                                    <td><?= $l['type'] == 0 ? "Super Admin" : "Staff" ?></td>
                                    <td><?= $l['status'] == 1 ? '<span class="badge badge-success ">Approved</span>' : '<span class="badge badge-danger">Unapproved</span>' ?></td>
                                    <td>
                                        <a class="text-decoration-none text-warning" href="<?= base_url('admin/user/' . $l['id'] . '/edit') ?>"><i class="ti ti-pencil-alt"></i></a>&ensp;
                                        <a class="text-decoration-none text-danger" href="<?= base_url('admin/user/' . $l['id'] . '/delete') ?>"><i class="ti ti-trash"></i></a>
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
<script>
    $(function() {
        $('#dataTable').DataTable();
    })
</script>
<?= $this->endSection() ?>