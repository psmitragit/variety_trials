<?= $this->extend('backend/layouts/app') ?>


<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title pt-3 pb-3">Treatment Report</h4>
            </div>
            <div class="card-body custom-button-table">
                <div class="row mb-3">
                    <div class="cil-md-3">
                        <?php
                        $segment = request()->getUri()->getSegments();
                        $crop_id = !empty($segment['3']) ? $segment['3'] : false;
                        ?>
                        <select class="select2" onchange="window.location='/admin/report/treatment/'+this.value">
                            <option value="" selected disabled>---Select Crop---</option>
                            <?php foreach (get_crops() as $l) : ?>
                                <option value="<?= $l['id'] ?>" <?= $crop_id == $l['id'] ? "selected" : "" ?>><?= $l['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="table-responsive ">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th>Entry</th>
                                <th>Company</th>
                                <th>Variety/Hybrid</th>
                                <th>Treatment group</th>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($treatments as $k => $l) :  ?>
                                <tr>
                                    <td><?= $l['name'] ?? "" ?></td>
                                    <td><?= $l['brand'] ?? "" ?></td>
                                    <td><?= $l['short_name'] ?? "" ?></td>
                                    <td><?= $l['group'] ?></td>
                                    <td><?= $l['year'] ?></td>
                                    <td><?= $l['state_name'] ?></td>
                                    <td><?= $l['trial_name'] ?></td>
                                    <td><?= $l['herbicide'] ?></td>
                                    <td><?= $l['insecticide'] ?></td>
                                    <td><?= $l['relative_maturity'] ?></td>
                                    <td><?= $l['sds'] ?></td>
                                    <td><?= $l['scn'] ?></td>
                                    <td><?= $l['refuge'] ?></td>
                                    <td><?= $l['frogeye'] ?></td>
                                    <td><?= $l['seed_treatment'] ?></td>
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
    $('#dataTable').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'pageLength',
            'csvHtml5',
            'pdfHtml5'
        ]
    })
</script>
<?= $this->endSection() ?>