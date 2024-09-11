<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
                    <h4 class="card-title">Variety/Hybrid</h4>
                    <a href="<?= base_url('admin/variety/create') ?>" class="btn btn-sm btn-labeled btn-primary"><span class="btn-label"><i class="ti ti-plus"></i></span>Add New</a>

                </div>
            </div>
            <div class="card-body">
                <div class="row align-items-center mb-4">
                    <div class="col-md-2">
                        Bulk Upload
                    </div>
                    <div class="col-md-7">
                        <?= form_open(base_url('admin/variety/bulk'), ['class' => 'need-validation', 'enctype' => 'multipart/form-data']) ?>
                        <div class="form-group row mb-0 align-items-center">
                            <div class="col-md-9">
                                <input type="file" class="form-control" name="bulk_file" accept=".csv" required>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-sm btn-primary btn-labeled"><span class="me-1 btn-label"><i class="ti ti-upload"></i></span> Upload</button>
                            </div>
                        </div>
                        <?= form_close() ?>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="<?= auth_admin()['type'] > 0 ? base_url('uploads/staff_variety_demo.csv') : base_url('uploads/variety_demo.csv') ?>" class="btn btn-sm btn-info btn-labeled" title="Download csv structure for variety" download=""><span class="me-1 btn-label"><i class="ti ti-download"></i></span> XL Format Download</a>
                    </div>
                </div>
                <?= form_open() ?>
                <?php if (isAllowed()) : ?>
                    <div class="row mb-3">
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
                                <?php if (isAllowed()) : ?>
                                    <th>Action</th>
                                <?php endif; ?>
                                <th>ID</th>
                                <th>Crop</th>
                                <th>Brand</th>
                                <th>Variety/Hybrid</th>
                                <th>Additional</th>
                                <th>Status</th>
                                <?php if (isAllowed()) : ?>
                                    <th>Added By</th>
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
                                    <?php if (isAllowed()) : ?>
                                        <td>
                                            <a class="text-decoration-none text-warning" href="<?= base_url('admin/variety/' . $l['id'] . '/edit') ?>"><i class="ti ti-pencil-alt" data-bs-toggle="tooltip" title="Edit"></i></a>&ensp;
                                            <a class="text-decoration-none text-danger confirmDelete" href="javascript:void(0)" data-href="<?= base_url('admin/variety/' . $l['id'] . '/delete') ?>"><i class="ti ti-trash" data-bs-toggle="tooltip" title="Delete"></i></a>
                                        </td>
                                    <?php endif; ?>
                                    <?php if (isAllowed()) : ?>
                                        <td><?= !empty($l['code']) ? $l['code'] : '<span class="add_entry badge badge-info" data-id="' . $l['id'] . '" role="button">Add Variety ID</span>' ?></td>
                                    <?php else : ?>
                                        <td><?= $l['code'] ?? "" ?></td>
                                    <?php endif; ?>
                                    <td><?= $l['crop_name'] ?></td>
                                    <td><?= $l['brand'] ?></td>
                                    <td><?= $l['short_name'] ?></td>
                                    <td><?= $l['additional_name'] ?></td>
                                    <td><?= $l['status'] ? '<span class="badge badge-success approve_treatment" data-bs-toggle="tooltip" data-id="' . $l['id'] . '" role="button">Approved</span>' : '<span class="badge badge-danger approve_treatment" data-id="' . $l['id'] . '" role="button">Unapproved</span>' ?></td>
                                    <?php if (isAllowed()) : ?>
                                        <td><?= $l['user_name'] ?? "" ?></td>
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
<?php if (isAllowed()) : ?>
    <script>
        $(function() {
            $(document).on('click', '.approve_treatment', function() {
                let button = $(this),
                    id = $(this).data('id'),
                    message = "";
                let data = {
                    _token: "<?= csrf_hash() ?>",
                    id
                };
                $.ajax({
                    url: "<?= base_url('admin/variety/approve') ?>",
                    type: 'post',
                    dataType: 'json',
                    data,
                    success: function(res) {
                        if (res.status) {
                            if (!res.is_approved) {
                                button.replaceWith(`<span class="badge badge-danger approve_treatment" data-id="${id}" role="button">Unapproved</span>`);
                                message = "Treatment unapproved";
                            } else {
                                button.replaceWith(`<span class="badge badge-success approve_treatment" data-bs-toggle="tooltip" data-id="${id}" role="button">Approved</span>`);
                                message = "Treatment approved";
                            }
                            toastr.success(message)
                        }
                    }
                })
            })

            $(document).on('click', '.add_entry', function() {
                let button = $(this),
                    id = $(this).data('id');
                Swal.fire({
                    input: "text",
                    title: "Assign Variety ID",
                    showCancelButton: true,
                    confirmButtonText: '&ensp; Save &ensp;',
                    focusConfirm: false,
                    inputValidator: (value) => {
                        if (!value) {
                            return "You need to write Variety ID!";
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        let data = {
                            value: result.value,
                            _token: "<?= csrf_hash() ?>",
                            id
                        }
                        $.ajax({
                            url: "<?= base_url('admin/variety/add_entry') ?>",
                            type: 'post',
                            dataType: 'json',
                            data,
                            success: function(res) {
                                if (res.status) {
                                    button.replaceWith(result.value)
                                    toastr.success("Variety Code added")
                                }
                            }
                        })
                    }
                });
            })
        })
    </script>
<?php endif; ?>


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