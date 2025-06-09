<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
                    <h4 class="card-title">Trials</h4>
                    <a href="<?= base_url('admin/trials/create') ?>" class="btn btn-sm btn-labeled btn-primary"><span class="btn-label"><i class="ti ti-plus"></i></span>Add New</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-center">
                        Bulk Upload
                    </div>
                    <div class="col-md-7">
                        <?= form_open(base_url('admin/trials/bulk'), ['class' => 'need-validation', 'enctype' => 'multipart/form-data']) ?>
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
                        <a href="<?= base_url('uploads/trials_demo.csv') ?>" class="btn btn-sm btn-info" title="Download csv structure for Trial" download=""><span class="me-1"><i class="ti ti-download"></i></span> XL Format Download</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Action</th>
                                <th>Name</th>
                                <th>Treatment group</th>
                                <th>Year</th>
                                <th>Crop</th>
                                <th>Type</th>
                                <th>Locations</th>
                                <th>Average Temparature</th>
                                <th>Average Percipitation</th>
                                <th>Production Pratice</th>
                                <th>Water Management</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($trials as $k => $l) : ?>
                                <tr>
                                    <td><?= $k + 1 ?></td>
                                    <td>
                                        <a class="text-decoration-none text-warning" href="<?= base_url('admin/trials/' . $l['id'] . '/edit') ?>"><i class="ti ti-pencil-alt" data-bs-toggle="tooltip" title="Edit"></i></a>&ensp;
                                        <a class="text-decoration-none text-danger confirmDelete" href="javascript:void(0)" data-href=" <?= base_url('admin/trials/' . $l['id'] . '/delete') ?>"><i class="ti ti-trash" data-bs-toggle="tooltip" title="Delete"></i></a>
                                    </td>
                                    <td><?= $l['name'] ?></td>
                                    <td><?= $l['treatment_group'] ?></td>
                                    <td><?= $l['year'] ?></td>
                                    <td><?= $l['crop_name'] ?? "" ?></td>
                                    <td><?= $l['trial_type'] ?? "" ?></td>
                                    <td><?= $l['location_names'] ?? "" ?></td>
                                    <td>
                                        <?php
                                        if (!empty($l['avarage_temparatures'])) {
                                            $explodedPratices = explode(',', $l['avarage_temparatures']);
                                            $implodeData = [];
                                            foreach ($explodedPratices as $v) {
                                                $implodeData[] = $v . '°F';
                                            }
                                            echo implode(', ', $implodeData);
                                        } else {
                                        ?>
                                            -
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (!empty($l['avarage_percipitations'])) {
                                            $explodedPratices = explode(',', $l['avarage_percipitations']);
                                            $implodeData = [];
                                            foreach ($explodedPratices as $v) {
                                                $implodeData[] = $v . 'inches';
                                            }
                                            echo implode(', ', $implodeData);
                                        } else {
                                        ?>
                                            -
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($l['production_pratices'] != '') {
                                            $explodedPratices = explode(',', $l['production_pratices']);
                                            $implodeData = [];
                                            foreach ($explodedPratices as $v) {
                                                if ($v == 1) {
                                                    $implodeData[] = 'Double-Crop';
                                                } else {
                                                    $implodeData[] = 'Full-Season';
                                                }
                                            }
                                            echo implode(', ', $implodeData);
                                        } else {
                                        ?>
                                            -
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($l['water_managements'] != '') {
                                            $explodedManagements = explode(',', $l['water_managements']);
                                            $implodeData = [];
                                            foreach ($explodedManagements as $v) {
                                                if ($v == 1) {
                                                    $implodeData[] = 'Non-Irrigated';
                                                } else {
                                                    $implodeData[] = 'Irrigated';
                                                }
                                            }
                                            echo implode(', ', $implodeData);
                                        } else {
                                        ?>
                                            -
                                        <?php
                                        }
                                        ?>
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

<div class="modal fade" id="failed_insert_model" tabindex="-1" aria-labelledby="failed_insert_modelLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen mt-0">
        <div class="modal-content">
            <div class="modal-header" style="padding-top: 15px;padding-bottom:15px">
                <h5 class="modal-title">Below Data Failed to insert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <?php if (!empty(session()->getFlashdata('trial_bulk_insert_err'))): ?>
                    <?= session()->getFlashdata('trial_bulk_insert_err'); ?>
                <?php endif; ?>
            </div>
            <div class="modal-footer py-0 px-5">
                <button type="button" class="m-0  btn btn-sm btn-danger" data-bs-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('custom-js') ?>
<script>
    $(function() {
        $('#dataTable').DataTable()
    })
</script>
<?php
if (!empty(session()->getFlashdata('trial_bulk_insert_err'))) {
?>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            $('#failed_insert_model').modal('show');
        });
    </script>
<?php
}
?>
<?= $this->endSection() ?>