<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title py-3">Trial Data</h4>
                <div class="row align-items-center mb-4">
                    <div class="col-md-2 text-center">
                        Bulk Upload
                    </div>
                    <div class="col-md-7">
                        <?= form_open(base_url('admin/report/trials/bulk'), ['class' => 'need-validation', 'enctype' => 'multipart/form-data', 'id' => 'bulkImport']) ?>
                        <div class="form-group row mb-0 align-items-center">
                            <div class="col-md-4">
                                <select name="crop_id" id="" class="form-control select2" required>
                                    <option value="" selected disabled>---SELECT---</option>
                                    <?php foreach ($crops as $l) : ?>
                                        <option value="<?= $l['id'] ?>"><?= $l['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="file" class="form-control" name="bulk_file" accept=".csv" required>
                            </div>
                            <div class="col-md-1 ">
                                <button class="btn  btn-sm btn-primary btn-labeled" data-bs-toggle="tooltip" title="Import CSV"> <span class=" btn-label"><i class="ti ti-upload"></i></span>Import</button>
                            </div>
                        </div>
                        <?= form_close() ?>
                    </div>
                    <div class="col-md-3">
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#formatDownloadModal" class="btn btn-sm btn-info btn-labeled ms-5" title="Download csv structure for variety" download=""><span class="me-1"><i class="ti ti-download btn-label"></i></span> XL Format Download</a>
                    </div>
                </div>
                <div class="row mb-4 align-items-center ">
                    <div class="col-md-2 text-center">
                        Export
                    </div>
                    <div class="col-md-9">
                        <?= form_open(base_url('admin/report/trials/export/csv'), ['method' => 'post', 'class' => 'need-validation', 'enctype' => 'multipart/form-data']) ?>
                        <div class="form-group row mb-0 align-items-center">
                            <div class="col-md-4">
                                <select name="crop_id" id="" class="form-control select2" required>
                                    <option value="" selected disabled>---Select Crop---</option>
                                    <?php foreach ($crops as $l) : ?>
                                        <option value="<?= $l['id'] ?>"><?= $l['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="year" id="" class="form-control select2" required>
                                    <option value="0" selected>---All Year---</option>
                                    <?php for ($i = 2023; $i >= 2000; $i--) : ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status" id="" class="form-control select2" required>
                                    <option value="-1" selected>---ALL---</option>
                                    <option value="1">Approved</option>
                                    <option value="0">Not Approved</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" name="csv" data-bs-toggle="tooltip" title="Export CSV" class="btn btn-sm btn-primary btn-labeled"> <span class="me-2 btn-label"><i class="ti ti-file"></i></span> CSV</button>
                            </div>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
                <?php if (isAllowed()) : ?>
                    <div class="row my-3">
                        <div class="col-md-12">
                            <?= form_open(base_url('admin/report/trials/copy')) ?>
                            <button class="btn btn-sm btn-info btn-labeled"><i class="ti ti-clipboard btn-label"></i>Copy Previous Year Data</button>
                            <?= form_close(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?= form_open() ?>
                <div class="row">
                    <div class="col-2">
                        <a href="<?= base_url('admin/report/trials/create') ?>" class="btn btn-sm btn-primary btn-labeled"><i class="ti ti-plus btn-label"></i>Add New</a>
                    </div>
                    <?php if (isAllowed()) : ?>
                        <div class="col-md-2">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="bulkApproveCollapse" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Bulk Action
                                </button>
                                <div class="dropdown-menu" aria-labelledby="bulkApproveCollapse">
                                    <button class="dropdown-item" formaction="<?= base_url('admin/report/trials/bulk-approve') ?>">Approve</button>
                                    <button class="dropdown-item" formaction="<?= base_url('admin/report/trials/bulk-delete') ?>">Delete</button>
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
                                <th>Action</th>
                                <th>Crop</th>
                                <th>Treatment group</th>
                                <th>Treatment</th>
                                <th>Year</th>
                                <th>Trial ID</th>
                                <th>Trial type</th>
                                <th>State</th>
                                <th>Location</th>
                                <th>Variety</th>
                                <th>Brand</th>
                                <th>Status</th>
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

<div class="modal fade" id="formatDownloadModal" tabindex="-1" aria-labelledby="formatDownloadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <?= form_open(base_url('admin/report/trials/format-download'), ['class' => "modal-content need-validation"]); ?>
        <div class="modal-header">
            <h5 class="modal-title" id="formatDownloadModalLabel">Xl Format for TrialData</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="crop_" class="form-label">Select Crop</label>
                <select name="crop_id" id="crop_" class="form-select form-control" required>
                    <option value="" disabled selected>---Select---</option>
                    <?php foreach (get_crops() as $l) : ?>
                        <option value="<?= $l['id'] ?>"><?= $l['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-labeled btn-sm" id="dismissModal"><i class="ti ti-download btn-label"></i> Download</button>
        </div>
        <?= form_close(); ?>
    </div>
</div>


<div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen mt-0">
        <div class="modal-content">
            <div class="modal-header" style="padding-top: 15px;padding-bottom:15px">
                <h5 class="modal-title" id="validationModalLabel">Validation Result</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">

            </div>
            <div class="modal-footer py-0 px-5">
                <button type="button" class="m-0  btn btn-sm btn-danger" data-bs-dismiss="modal">Cancel</button>
                <?= form_open(base_url('admin/report/trials/import-process'), ['id' => 'modalImportForm']); ?>
                <input type="hidden" name="crop_id">
                <input type="hidden" name="data">
                <input type="hidden" name="header">
                <input type="hidden" name="return">
                <button type="submit" class="m-0 btn btn-sm btn-primary">Import</button>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
<div class="loader d-none">
    <div class="line"></div>
</div>

<?= $this->endSection() ?>

<?= $this->section('custom-js') ?>
<script>
    $(function() {
        $("#dismissModal").on('click', function() {
            $('#formatDownloadModal').modal('hide');
        })
        const dataTable = $('#dataTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                url: '<?= base_url('admin/report/trials') ?>',
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
                    data: 'action'
                }, {
                    data: 'crop'
                },{
                    data: 'treatment_group'
                },{
                    data: 'treatment'
                },
                {
                    data: 'year'
                },
                {
                    data: 'trial_id'
                },
                {
                    data: 'trial_type'
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
            $('.loader').removeClass('d-none')
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
                    $('.loader').addClass('d-none')
                }
            })


        })
    })
</script>
<?= $this->endSection() ?>