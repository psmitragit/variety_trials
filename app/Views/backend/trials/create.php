<?= $this->extend('backend/layouts/app') ?>


<?= $this->section('custom-css'); ?>
<style>
    .custommargin.form-group {
        height: fit-content;
        margin-top: 35px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title  pt-3 pb-3">
                    <?= !empty($trial) ? "Edit" : "Create" ?> Trial
                </h4>
            </div>
            <div class="card-body">
                <div class="my-2 text-danger">
                    <?= validation_list_errors() ?>
                </div>
                <?= form_open('', ['method' => 'post', 'class' => 'validate']) ?>

                <input type="hidden" name="id" value="<?= !empty($trial) ? $trial['id'] : 0 ?>">
                <div class="row">
                    <div class="form-group col-md-6 mb-3">
                        <label for="trial">Trial Name (Just for uniquely identify each trials) <sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" id="trial" name="name" placeholder="2025 Full Corn Trial" value="<?= old('name') ?? $trial['name'] ?? "" ?>" required>
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="crop_id">Crop <sup class="text-danger">*</sup></label>
                        <?php $crop_id = old('crop_id') ?? $trial['crop_id'] ?? 0; ?>
                        <select name="crop_id" id="crop_id" class="select2 form-control" required>
                            <option value="" selected disabled>---Select----</option>
                            <?php foreach (get_crops() as $l) : ?>
                                <option value="<?= $l['id'] ?>" <?= $l['id'] == $crop_id ? "selected" : "" ?>>
                                    <?= $l['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="trial_type">Trial Type <sup class="text-danger">*</sup></label>
                        <select name="trial_type" id="trial_type" class="select2 form-control" required>
                            <option value="" selected disabled>---Select----</option>
                            <?php foreach ($types as $type) : ?>
                                <option value="<?= $type['id'] ?>" <?= $trial && $trial['trial_type_id'] == $type['id'] ? "selected" : "" ?>>
                                    <?= $type['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="year">Year <sup class="text-danger">*</sup></label>
                        <input type="text" class="form-control" id="year" name="year" value="<?= old('year') ?? $trial['year'] ?? "" ?>" placeholder="2025" required>
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="treatment_group">Treatment group <sup class="text-danger">*</sup></label>
                        <select name="treatment_group" id="treatment_group" class="select2 form-control" required>
                            <option value="" selected disabled>---Select----</option>
                            <?php foreach ($treatments as $l) : ?>
                                <option value="<?= $l['group'] ?>" <?= $selected_tretment == $l['group'] ? "selected" : "" ?>>
                                    <?= $l['group'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php if (!empty($trial) && !empty($trialLocations)) : ?>
                        <div class="row col-12" id="repeterappend">
                            <?php foreach ($trialLocations as $tk => $tl) : ?>
                                <div class="row col-12 repeter-repete">
                                    <div class="form-group col-md-2 mb-3">
                                        <label for="locid0">Assign State <sup class="text-danger">*</sup></label>
                                        <select class="select2 assign_state form-control" placeholder="Assign Locations" required>
                                            <option value="" disabled selected>--- Select ----</option>
                                            <?php foreach (get_states() as $l) : ?>
                                                <option value="<?= $l['code'] ?>" <?= $tl['state_code'] == $l['code'] ? "selected" : ""; ?>>
                                                    <?= $l['name'] ?? $l['code'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3 mb-3">
                                        <label for="locid0">Assign Locations <sup class="text-danger">*</sup></label>
                                        <select name="locids[<?= $tk; ?>]" id="locid<?= $tk; ?>" class="select2 locations form-control" placeholder="Assign Locations" required>
                                            <option value="" disabled selected>--- Select ----</option>
                                            <?php foreach ($locations as $l) : ?>
                                                <option value="<?= $l['id'] ?>" <?= $tl['location_id'] == $l['id'] ? "selected" : ""; ?>>
                                                    <?= $l['code'] . " - " . $l['location'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3 mb-3">
                                        <label for="harvest_date">Harvest Date</label>
                                        <input type="date" class="form-control" id="harvest_date<?= $tk; ?>" name="harvest_date[<?= $tk; ?>]" value="<?= $tl['harvest_date'] ?? ""; ?>" placeholder=" Harvest Date">
                                    </div>
                                    <div class="form-group col-md-3 mb-3">
                                        <label for="planting_date">Planting Date</label>
                                        <input type="date" class="form-control" id="planting_date<?= $tk; ?>" name="planting_date[<?= $tk; ?>]" value="<?= $tl['planting_date'] ?? ""; ?>" placeholder=" Planting Date">
                                    </div>
                                    <?php if ($tk > 0) : ?>
                                        <div class="col-md-1 mb-3 custommargin form-group">
                                            <a href="javascript:void(0)" class="btn remove-location btn-danger mb-0 btn-sm">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="row col-12" id="repeterappend">
                            <div class="row col-12 repeter-repete">
                                <div class="form-group col-md-2 mb-3">
                                    <label for="locid0">Assign State <sup class="text-danger">*</sup></label>
                                    <select class="select2 assign_state form-control" placeholder="Assign State" required>
                                        <option value="" disabled selected>Select</option>
                                        <?php foreach (get_states() as $l) : ?>
                                            <option value="<?= $l['code'] ?>">
                                                <?= $l['name'] ?? $l['code'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 mb-3">
                                    <label for="locid0">Assign Locations <sup class="text-danger">*</sup></label>
                                    <select name="locids[0]" id="locid0" class="select2 locations form-control" placeholder="Assign Locations" required>
                                        <option value="" disabled selected>--- Select ----</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 mb-3">
                                    <label for="harvest_date">Harvest Date</label>
                                    <input type="date" class="form-control" id="harvest_date0" name="harvest_date[0]" value="" placeholder=" Harvest Date">
                                </div>
                                <div class="form-group col-md-3 mb-3">
                                    <label for="planting_date">Planting Date</label>
                                    <input type="date" class="form-control" id="planting_date0" name="planting_date[0]" placeholder=" Planting Date">
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>


                    <div class="col-md-12 mb-5">
                        <a href="javascript:void(0)" class="btn btn-sm btn-primary btn-labeled" id="addMore">
                            <span class="me-1"><i class="ti ti-plus btn-label"></i></span> Add Location
                        </a>
                    </div>


                    <?php if (!empty($trial)) : ?>
                        <div class="form-group col-md-6">
                            <label for="is_approved">Status <sup class="text-danger">*</sup></label>
                            <select name="status" id="is_approved" class="form-control">
                                <option value="1" <?= $trial['status'] == 0 ? "selected" : "" ?>>Active</option>
                                <option value="0" <?= $trial['status'] == 0 ? "selected" : "" ?>>In Active</option>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <a href="<?= base_url('admin/trials') ?>" class="btn btn-light">Cancel</a>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('custom-js') ?>
<script>
    $(function() {
        $('#crop_id').on('change', function() {
            let data = {
                '_token': "<?= csrf_hash() ?>",
                'crop_id': $(this).val()
            }
            $.ajax({
                url: "<?= base_url('admin/trials/get_trial_type_by_crop') ?>",
                type: 'post',
                dataType: 'json',
                data,
                success: function(res) {
                    if (res.status) {
                        $('#trial_type').empty();
                        $('#trial_type').append(`<option value="" selected disabled>---Select----</option>`);
                        res.types.forEach(element => {
                            $('#trial_type').append(`<option value="${element.id}">${element.name}</option>`);
                        });
                    }
                }
            })
        })


        //Validation
        $.validator.addMethod("sameValues", function(value, element, params) {
            let fields = $(params);
            let thisVal = $(element).val();
            let valArr = [];
            fields.each((i, ele) => {
                valArr.push($(ele).val())
            })
            count = valArr.filter(num => num === thisVal).length;

            return count > 1 ? false : true;
        }, "Location already assigned.");

        $.validator.addClassRules('locations', {
            sameValues: ".locations"
        })

        $(".validate").validate();



        let i = 999999999;

        $("#addMore").on("click", function() {
            var clonedDiv = `<div class="row col-12 repeter-repete">
                                <div class="form-group col-md-2 mb-3">
                                    <label for="locid0">Assign State <sup class="text-danger">*</sup></label>
                                    <select class="select2 assign_state form-control" placeholder="Assign State" required>
                                        <option value="" disabled selected>Select</option>
                                        <?php foreach (get_states() as $l) : ?>
                                            <option value="<?= $l['code'] ?>">
                                                <?= $l['name'] ?? $l['code'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    </div>
                            <div class="form-group col-md-3 mb-3">
                                <label for="locid${i}">Assign Locations <sup class="text-danger">*</sup></label>
                                <select name="locids[${i}]" id="locid${i}" class="select2 locations form-control" placeholder="Assign Locations" required>
                                    <option value="" disabled selected>--- Select ----</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 mb-3">
                                <label for="harvest_date">Harvest Date</label>
                                <input type="date" class="form-control" id="harvest_date${i}" name="harvest_date[${i}]" value="" placeholder=" Harvest Date">
                            </div>
                            <div class="form-group col-md-3 mb-3">
                                    <label for="planting_date">Planting Date</label>
                                    <input type="date" class="form-control" id="planting_date${i}" name="planting_date[${i}]" placeholder=" Planting Date">
                                </div>
                            <div class="col-md-1 mb-3 custommargin form-group">
                                <a href="javascript:void(0)" class="btn remove-location btn-danger mb-0 btn-sm">
                                    <i class="ti ti-trash"></i>
                                </a>
                            </div>
                        </div>`;
            $("#repeterappend").append(clonedDiv);
            $('.select2').off();
            initializeSelect2()
            i++;
        });

        $("#repeterappend").on("click", ".remove-location", function() {
            $(this).closest(".repeter-repete").remove();
        });


        $(document).on('change', '.assign_state', function() {
            let data = {
                '_token': "<?= csrf_hash() ?>",
                'state': $(this).val()
            };
            let location = $(this).parents('.repeter-repete').find('.locations');
            location.html('<option value="" disabled selected>--- Select ----</option>');
            $.ajax({
                url: "<?= base_url('admin/location/get_location_by_state') ?>",
                type: 'post',
                dataType: 'json',
                data,
                success: function(res) {
                    if (res.status) {
                        res.locations.forEach(val => {
                            location.append(`<option value="${val.id}">${val.code} - ${val.location}</option>`);
                        })
                    }
                }
            })
        })
    })
</script>

<?= $this->endSection() ?>