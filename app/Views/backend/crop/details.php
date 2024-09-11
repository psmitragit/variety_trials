<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header pt-4 pb-4">
                <h4 class="card-title mb-0"><?= $crop['name'] ?> Trials Data</h4>

               
            </div>
            <div class="card-body">

            <div class="row align-items-center ">
                    <div class="col-md-2 text-center">
                        Bulk Upload
                    </div>
                    <div class="col-md-7">
                        <?= form_open(base_url('admin/report/trials/bulk'), ['class' => 'need-validation', 'enctype' => 'multipart/form-data', 'id' => 'bulkImport']) ?>
                        <div class="form-group row mb-0 align-items-center">
                            <input type="hidden" name="crop_id" value="<?= $crop['id'] ?>">
                            <div class="col-md-9">
                                <input type="file" class="form-control" name="bulk_file" accept=".csv" required>
                            </div>
                            <div class="col-md-3">
                                <button class="btn  btn-sm btn-primary btn-labeled mb-2 mb-sm-0"><span class="me-1"><i class="ti ti-upload btn-label"></i></span> Upload</button>
                            </div>
                        </div>
                        <?= form_close() ?>
                    </div>
                    <div class="col-md-3 text-end">
                        <?= form_open(base_url('admin/report/trials/format-download'), ['method' => 'post']); ?>
                        <input type="hidden" name="crop_id" value="<?= $crop['id'] ?>">
                        <button type="submit" class="mb-2 mb-sm-0 btn btn-sm btn-info btn-labeled" data-bs-toggle="tooltip" title="Download csv structure for variety"><i class="ti ti-download btn-label"></i></span> XL Format Download </button>
                        <?= form_close(); ?>
                    </div>
                </div>
                <div class="row my-3">
                    <h6>Variables</h6>
                    <?php if (empty($variables)) : ?>
                        <div>
                            <span class="text-danger">No variables added</span>
                            &ensp;&ensp;<a href="<?= base_url("admin/crop/{$crop['id']}/edit") ?>" class="btn btn-sm btn-primary">Add Variables</a>
                        </div>
                    <?php endif; ?>
                    <?php foreach ($variables as $l) : ?>
                        <div class="col-md-auto mb-2"><span class="badge badge-warning"><?= $l['name'] ?></span></div>
                    <?php endforeach; ?>

                </div>
                <?= form_open() ?>
                <div class="row align-items-center mt-3 mb-3">
                    <div class="col-2">
                        <a href="<?= base_url('admin/report/trials/create') ?>" class="mb-0 btn btn-sm btn-primary btn-labeled"><i class="ti ti-plus btn-label"></i>Add New</a>
                    </div>
                    <?php if (isAllowed()) : ?>
                        <div class="col-2">
                            <div class="dropdown">
                                <button class="mb-0 btn btn-secondary dropdown-toggle" type="button" id="bulkApproveCollapse" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Bulk Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="bulkApproveCollapse">
                                    <button class="dropdown-item mb-0" formaction="<?= base_url('admin/report/trials/bulk-approve') ?>">Approve</button>
                                    <button class="dropdown-item mb-0" formaction="<?= base_url('admin/report/trials/bulk-delete') ?>">Delete</button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-1">
                        Filter:
                    </div>
                    <div class="col-md-2 form-group mb-0">
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


<div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen mt-0">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="validationModalLabel">Validation Result</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <?= form_open(base_url('admin/report/trials/import-process'), ['id' => 'modalImportForm']); ?>
                <input type="hidden" name="crop_id">
                <input type="hidden" name="data">
                <input type="hidden" name="header">
                <input type="hidden" name="return">
                <button type="submit" class="btn btn-primary">Import</button>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('custom-js') ?>
<script>
    $(function() {
        let csrf = "<?= csrf_hash() ?>";
        const dataTable = $('#dataTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                url: '<?= base_url('admin/report/trials') ?>',
                data: {
                    id: "<?= $crop['id'] ?>",
                    _token: () => {
                        return $('input[name="_token"]').val()
                    },
                    status: () => $('#sStatus').val()
                }
            },

            'columns': [{
                    data: 'ids'
                },
                {
                    data: 'crop'
                },
                {
                    data: 'year'
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
                'targets': [0, 9],
                'orderable': false,
            }]
        });
        $('#sStatus').on('change', function() {
            dataTable.ajax.reload()
        })


        $(document).on('click', '.approve_trial', function() {
            let item = $(this);
            $.ajax({
                url: "<?= base_url('admin/report/trials/approve') ?>",
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

        //bulkImport
        $(document).on('submit', '#bulkImport', function(e) {
            e.preventDefault();
            let data = new FormData(e.target)

            $.ajax({
                url: e.target.action,
                type: 'post',
                dataType: 'json',
                data,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status) {
                        $('#validationModal .modal-body').html(res.html);
                        $('#validationModal').modal('show');
                        $('#modalImportForm input[name="crop_id"]').val(res.crop_id)
                        $('#modalImportForm input[name="data"]').val(res.data)
                        $('#modalImportForm input[name="header"]').val(res.header)
                        $('#modalImportForm input[name="return"]').val(res.return)
                    } else {
                        toastr.error(res.error)
                    }
                }
            })


        })
    })
</script>
<?= $this->endSection() ?>