<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Trials</h4>

                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        Bulk Upload
                    </div>
                    <div class="col-md-7">
                        <?= form_open(base_url('admin/trials/bulk'), ['class' => 'need-validation', 'enctype' => 'multipart/form-data']) ?>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <select name="crop_id" id="" class="form-control select2" required>
                                    <option value="" selected disabled>---SELECT---</option>
                                    <?php foreach ($crops as $l) : ?>
                                        <option value="<?= $l['id'] ?>"><?= $l['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="file" class="form-control" name="bulk_file" accept=".csv" required>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-sm btn-primary"> <span class="me-1"><i class="ti ti-upload"></i></span> Upload</button>
                            </div>
                        </div>
                        <?= form_close() ?>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="<?= base_url('uploads/trial_demo.csv') ?>" class="btn btn-sm btn-info" title="Download csv structure for variety" download=""><span class="me-1"><i class="ti ti-download"></i></span> XL Format Download</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 text-center">
                        Export
                    </div>
                    <div class="col-md-9">
                        <?= form_open(base_url('admin/trials/export/csv'), ['class' => 'need-validation', 'enctype' => 'multipart/form-data']) ?>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <select name="crop_id" id="" class="form-control select2" required>
                                    <option value="" selected disabled>---Select---</option>
                                    <?php foreach ($crops as $l) : ?>
                                        <option value="<?= $l['id'] ?>"><?= $l['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="year" id="" class="form-control select2" required>
                                    <option value="0" selected>---All---</option>
                                    <?php for ($i = 2023; $i >= 2000; $i--) : ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-3 ">
                                <button type="submit" name="csv" class="btn btn-sm btn-primary"> <span class="me-2"><i class="ti ti-file"></i></span> CSV</button>
                            </div>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
                <?php if (isAllowed()) : ?>
                    <div class="row my-3">
                        <div class="col-md-12">
                            <?= form_open(base_url('admin/trials/copy')) ?>
                            <button class="btn btn-info">Copy Previous Year Data</button>
                            <?= form_close(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?= form_open() ?>
                <div class="row">
                    <div class="col-2">
                        <a href="<?= base_url('admin/trials/create') ?>" class="btn btn-sm btn-primary"><i class="ti ti-plus"></i>Add New</a>
                    </div>
                    <?php if (isAllowed()) : ?>
                        <div class="col-md-2">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="bulkApproveCollapse" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Bulk Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="bulkApproveCollapse">
                                    <button class="dropdown-item" formaction="<?= base_url('admin/trials/bulk-approve') ?>">Approve</button>
                                    <button class="dropdown-item" formaction="<?= base_url('admin/trials/bulk-delete') ?>">Delete</button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-1">
                        Filter:
                    </div>
                    <div class="col-md-2 form-group">
                        <select id="sStatus" class="form-control">
                            <option value="">All</option>
                            <option value="1">Approved</option>
                            <option value="0">Unapproved</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="checkAll"></th>
                                <th>Crop</th>
                                <th>Year</th>
                                <th>Program</th>
                                <th>Trial</th>
                                <th>State</th>
                                <th>Location</th>
                                <th>Variety</th>
                                <th>Brand</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
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
        const dataTable = $('#dataTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                url: '<?= base_url('admin/trials') ?>',
                data: {
                    _token: () => {
                        return $('input[name="_token"]').val()
                    },
                    status: () => $('#sStatus').val()
                }
            },

            'columns': [{
                    data: 'ids'
                }, {
                    data: 'crop'
                },
                {
                    data: 'year'
                },
                {
                    data: 'program'
                },
                {
                    data: 'trial'
                },
                {
                    data: 'state'
                },
                {
                    data: 'location'
                },
                {
                    data: 'variety'
                },
                {
                    data: 'brand'
                },
                {
                    data: 'status'
                },
                {
                    data: 'action'
                }
            ],
            drawCallback: function() {
                var api = this.api();
                $('input[name="_token"]').val(api.ajax.json().hash);
            },
            order: [
                [2, 'desc']
            ],
            columnDefs: [{
                'targets': [0, 10],
                'orderable': false,
            }]
        });

        $('#sStatus').on('change', function() {
            dataTable.ajax.reload()
        })

        $(document).on('click', '.approve_trial', function() {
            let item = $(this);
            $.ajax({
                url: "<?= base_url('admin/trials/approve') ?>",
                type: 'post',
                dataType: 'json',
                data: {
                    _token: $('input[name="_token"]').val(),
                    id: $(this).data('id')
                },
                success: function(res) {
                    if (res.status) {
                        if (res.value) {
                            item.text('Approved');
                            item.removeClass('badge-danger');
                            item.addClass('badge-success');
                            toastr.success('Trial Approved')
                        } else {
                            item.text('Unapproved');
                            item.removeClass('badge-success');
                            item.addClass('badge-danger');
                            toastr.success('Trial Unapproved')
                        }
                        item.text()
                    }
                    $('input[name="_token"]').val(res.hash);
                }
            })
        })

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
    })
</script>
<?= $this->endSection() ?>