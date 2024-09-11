<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<style>
    .filters.alldilters select {
        border: 1px solid #00000045;
        margin-left: 5px;
        background: #e9ecef;
    }
</style>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
                    <h4 class="card-title">Treatments</h4>
                    <a href="<?= base_url('admin/treatment/create') ?>" class="btn btn-sm btn-labeled btn-primary"><span class="btn-label"><i class="ti ti-plus"></i></span>Add New</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-center">
                        Bulk Upload
                    </div>
                    <div class="col-md-7">
                        <?= form_open(base_url('admin/treatment/bulk'), ['class' => 'need-validation', 'enctype' => 'multipart/form-data']) ?>
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
                        <a href="<?= base_url('uploads/treatment_demo.csv') ?>" class="btn btn-sm btn-info" title="Download csv structure for Treatment" download=""><span class="me-1"><i class="ti ti-download"></i></span> XL Format Download</a>
                    </div>
                    <?php if (isAllowed()) : ?>
                        <div class="row">
                            <div class="export col-auto">
                                <a class="btn btn-primary" href="<?= base_url('admin/treatment/export') ?>?year=<?= $year ?>&state=<?= $state ?>&trial_type=<?= $trial_type ?>">Export</a>
                            </div>
                            <div class="filters alldilters col-auto">
                                <form id="filter-form" action="<?= base_url('admin/treatment') ?>" method="get">
                                    <select class="filter-box btn " name="state" id="">
                                        <option value="">Select State</option>
                                        <?php
                                        foreach ($states as $stateRow) {
                                        ?>
                                            <option <?= $stateRow['id'] == $state ? 'selected' : '' ?> value="<?= $stateRow['id'] ?>"><?= $stateRow['code'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <select class="filter-box btn " name="trial_type" id="">
                                        <option value="">Select Trial Type</option>
                                        <?php
                                        foreach ($trials as $trial) {
                                        ?>
                                            <option <?= $trial['id'] == $trial_type ? 'selected' : '' ?> value="<?= $trial['id'] ?>"><?= $trial['name'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <select class="filter-box btn " name="year" id="">
                                        <option value="">Select Year</option>
                                        <?php
                                        $currentYear = date("Y");
                                        for ($i = $currentYear; $i >= $currentYear - 20; $i--) {
                                        ?>
                                            <option <?= $year == $i ? 'selected' : '' ?> value="<?= $i ?>"><?= $i ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </form>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('admin/treatment') ?>" class="btn btn-danger">Clear Filter</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <?php

                                use App\Helpers\Helpers;

                                if (isAllowed()) : ?>
                                    <th>Action</th>
                                <?php endif; ?>
                                <?php if (isAllowed()) : ?>
                                    <th>Entry</th>
                                <?php endif; ?>
                                <th>Group</th>
                                <th>Crop</th>
                                <th>Assigned Variety ID</th>
                                <th>Entered Variety ID</th>
                                <th>Assigned Brand</th>
                                <th>Entered Brand</th>
                                <th>Year</th>
                                <th>State</th>
                                <th>Trial type</th>
                                <th>Herbicide</th>
                                <th>Insecticide</th>
                                <th>Relative Maturity</th>
                                <th>SDS</th>
                                <th>SCN</th>
                                <th>Refuge</th>
                                <th>Frogeye</th>
                                <th>Seed Treatment</th>
                                <th>Status</th>
                                <?php if (isAllowed()) : ?>
                                    <th>Added By</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($treatments as $k => $l) : ?>
                                <?php
                                $helper = new Helpers;
                                $varietiesByUser = json_encode($helper->varietiesByUserAndCrop($l['user_id'], $l['crop_id'], true));
                                // dd($l);
                                ?>
                                <tr>
                                    <td><?= $k + 1 ?></td>
                                    <?php if (isAllowed()) : ?>
                                        <td>
                                            <a class="text-decoration-none text-warning" href="<?= base_url('admin/treatment/' . $l['id'] . '/edit') ?>"><i class="ti ti-pencil-alt" data-bs-toggle="tooltip" title="Edit"></i></a>&ensp;
                                            <a class="text-decoration-none text-danger confirmDelete" href="javascript:void(0)" data-href=" <?= base_url('admin/treatment/' . $l['id'] . '/delete') ?>"><i class="ti ti-trash" data-bs-toggle="tooltip" title="Delete"></i></a>
                                        </td>
                                    <?php endif; ?>
                                    <?php if (isAllowed()) : ?>
                                        <td><?= !empty($l['name']) ? $l['name'] : '<span class="add_entry badge badge-info" data-id="' . $l['id'] . '" role="button">Add Entry</span>' ?></td>
                                    <?php endif; ?>
                                    <td><?= $l['group'] ?></td>
                                    <td><?= $l['crop_name'] ?></td>
                                    <td id="addVrBtn<?= $l['id'] ?>">
                                        <?php if (isAllowed()) : ?>
                                            <?= !empty($l['variety_id']) ? $l['variety_name'] : '<span class="add_variety badge badge-info" data-id="' . $l['id'] . '" data-var_ids="' . $varietiesByUser . '" role="button">Add Variety & Brand</span>' ?>
                                        <?php else : ?>
                                            <?= !empty($l['variety_id']) ? $l['variety_name'] : ''; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $l['user_entered_variety'] ?></td>
                                    <td id="brandVrBtn<?= $l['id'] ?>"><?= $l['variety_brand'] ?></td>
                                    <td><?= $l['user_entered_brand'] ?></td>
                                    <td><?= $l['year'] ?></td>
                                    <td><?= $l['state_code'] ?></td>
                                    <td><?= $l['trial_name'] ?></td>
                                    <td><?= $l['herbicide'] ?></td>
                                    <td><?= $l['insecticide'] ?></td>
                                    <td><?= $l['relative_maturity'] ?></td>
                                    <td><?= $l['sds'] ?></td>
                                    <td><?= $l['scn'] ?></td>
                                    <td><?= $l['refuge'] ?></td>
                                    <td><?= $l['frogeye'] ?></td>
                                    <td><?= $l['seed_treatment'] ?></td>
                                    <td><?= $l['is_approved'] ? '<span class="badge badge-success approve_treatment" data-bs-toggle="tooltip" data-id="' . $l['id'] . '" role="button">Approved</span>' : '<span class="badge badge-danger approve_treatment" data-id="' . $l['id'] . '" role="button">Unapproved</span>' ?></td>
                                    <?php if (isAllowed()) : ?>
                                        <td><?= $l['user_name'] ?? "" ?></td>
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

<!-- Modal Body -->
<div class="modal fade" id="varietyModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal" role="document">
        <div class="modal-content">
            <form action="<?= base_url('admin/treatment/add-variety-id') ?>" id="varietySelectForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">
                        Select Variety & Brand
                    </h5>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
                <div class="modal-body">
                    <?= csrf_field(); ?>
                    <input type="hidden" id='treId' name='treId'>
                    <input type="hidden" id='varCode' name='varCode'>
                    <select name="variety_id" id="varietySelectOptions" class='form-control'>
                        <option value="">Select</option>
                        <?php if (!empty($allVarieties)) : ?>
                            <?php foreach ($allVarieties as $variety) : ?>
                                <option value="<?= $variety['id'] ?>"><?= $variety['code'] ?> || <?= $variety['brand'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
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
                    url: "<?= base_url('admin/treatment/approve') ?>",
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
                    title: "Assign Entry",
                    showCancelButton: true,
                    confirmButtonText: '&ensp; Save &ensp;',
                    focusConfirm: false,
                    inputValidator: (value) => {
                        if (!value) {
                            return "You need to write entry!";
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
                            url: "<?= base_url('admin/treatment/add_entry') ?>",
                            type: 'post',
                            dataType: 'json',
                            data,
                            success: function(res) {
                                if (res.status) {
                                    button.replaceWith(result.value)
                                    toastr.success("Entry added")
                                }
                            }
                        })
                    }
                });
            })
            $(document).on('click', '.add_variety', function() {
                let id = $(this).data('id');
                let var_ids = $(this).data('var_ids');

                $('#treId').val(id);

                $('#varietySelectOptions option').each(function() {
                    let option = $(this);
                    let optionValue = option.val();

                    var_ids.forEach(function(var_id) {
                        if (var_id == optionValue) {
                            option.prop('disabled', false).show();
                        } else {
                            option.prop('disabled', true).hide();
                        }
                    });

                });
                $('#varietyModal').modal('show');
            });

            $(document).ready(function() {
                $('#varietySelectOptions').change(function() {
                    $('#varCode').val($(this).find('option:selected').text());
                });
            });

            $(document).ready(function(e) {
                $("#varietySelectForm").on('submit', (function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: $(this).attr('action'),
                        mimeType: "multipart/form-data",
                        contentType: false,
                        cache: false,
                        processData: false,
                        data: new FormData(this),
                        type: 'post',
                        success: function(res) {
                            var res = JSON.parse(res);
                            if (res.status) {
                                $('#addVrBtn' + res.btnId).html(res.varCode);
                                $('#brandVrBtn' + res.btnId).html(res.varBrand);
                                toastr.success('Updated')
                                $('#varietyModal').modal('hide');
                            }
                        },
                        error: function(res) {
                            toastr.error('Something went wrong.')
                        }
                    });
                }));
            });
            $('.filter-box').change(function() {
                $('#filter-form').submit();
            });
        })
    </script>
<?php endif; ?>
<script>
    $(function() {
        $('#dataTable').DataTable();
    })
</script>
<?= $this->endSection() ?>