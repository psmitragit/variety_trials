<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title pt-3 pb-3"><?= !empty($treatment) ? "Edit" : "Create" ?> Treatment</h4>
            </div>
            <div class="card-body">
                <div class="text-danger"><?= validation_list_errors() ?></div>
                <?= form_open('', ['method' => 'post', 'class' => 'need-validation', 'id' => 'treatmentForm']) ?>
                <input type="hidden" name="id" value="<?= $treatment['id'] ?? 0 ?>">
                <div class="row">
                    <?php if (isAllowed()) : ?>
                        <div class="form-group col-md-6 mb-3">
                            <label for="treatment_name">Entry Code</label>
                            <input type="text" class="form-control" id="treatment_name" value="<?= old('entry') ?? $treatment['name'] ?? "" ?>" name="entry" placeholder="Treatment Name" required>
                        </div>
                    <?php endif; ?>
                    <div class="form-group col-md-6 mb-3">
                            <label for="group">Group</label>
                            <input type="text" class="form-control" id="group" value="<?= old('group') ?? $treatment['group'] ?? "" ?>" name="group" placeholder="Treatment Group" required>
                        </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="crop_id">Crop <sup class="text-danger">*</sup></label>
                        <select name="crop_id" id="crop_id" class="form-select form-control select2" required>
                            <option value="" disabled selected>----Select Crop----</option>
                            <?php $crop_id = old('crop_id') ?? $treatment['crop_id'] ?? 0;
                            foreach (get_crops() as $crop) : ?>
                                <option value="<?= $crop['id'] ?>" <?= $crop['id'] == $crop_id ? "selected" : "" ?>><?= $crop['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if (isAllowed()) : ?>
                        <div class="form-group col-md-6 mb-3">
                            <label for="variety_id">Variety <sup class="text-danger">*</sup></label>
                            <select name="variety_id" id="variety_id" class="form-select form-control select2" required>
                                <option value="" disabled selected>----Select Variety----</option>
                                <?php foreach ($varieties as $variety) : ?>
                                    <option value="<?= $variety['id'] ?>" <?= $variety['id'] == $treatment['variety_id'] ? "selected" : "" ?>><?= $variety['short_name'] ?? $variety['code'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    <div class="form-group col-md-6 mb-3">
                        <label for="">Trial Type <sup class="text-danger">*</sup></label>
                        <select name="trial_type_id" id="trial_type_id" class="form-select form-control select2" required>
                            <option value="" disabled selected>----Select Trial Type----</option>
                            <?php $trial_type_id = old('trial_type_id') ?? $treatment['trial_type_id'] ?? 0;
                            foreach ($trialTypes as $trialType) : ?>
                                <option data-crop_id="<?= $trialType['crop_id'] ?>" value="<?= $trialType['id'] ?>" <?= $trialType['id'] == $trial_type_id ? "selected" : "" ?>><?= $trialType['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="year">Year</label>
                        <input type="number" class="form-control" id="year" value="<?= old('year') ?? $treatment['year'] ?? "" ?>" name="year" placeholder="Year">
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="">State <sup class="text-danger">*</sup></label>
                        <select name="state" id="state" class="form-select form-control select2" required>
                            <option value="" disabled selected>----Select State----</option>
                            <?php $state = old('state') ?? $treatment['state'] ?? 0;
                            foreach ($allStates as $singleState) : ?>
                                <option value="<?= $singleState['id'] ?>" <?= $singleState['id'] == $state ? "selected" : "" ?>><?= $singleState['code'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- <div class="form-group col-md-6 mb-3">
                        <label for="treatment_mat">Mat</label>
                        <input type="text" class="form-control" id="treatment_mat" value="<?= old('treatment_mat') ?? $treatment['mat'] ?? "" ?>" name="treatment_mat" placeholder="Mat">
                    </div> -->
                    <div class="form-group col-md-6 mb-3">
                        <label for="herbicide">Herbicide</label>
                        <input type="text" class="form-control" id="herbicide" value="<?= old('herbicide') ?? $treatment['herbicide'] ?? "" ?>" name="herbicide" placeholder="Herbicide">
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="insecticide">Insecticide</label>
                        <input type="text" class="form-control" id="insecticide" value="<?= old('insecticide') ?? $treatment['insecticide'] ?? "" ?>" name="insecticide" placeholder="Insecticide">
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="relative_maturity">Relative Maturity</label>
                        <input type="text" class="form-control" id="relative_maturity" value="<?= old('relative_maturity') ?? $treatment['relative_maturity'] ?? "" ?>" name="relative_maturity" placeholder="Relative Maturity">
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="frogeye">Frogeye</label>
                        <input type="text" class="form-control" id="frogeye" value="<?= old('frogeye') ?? $treatment['frogeye'] ?? "" ?>" name="frogeye" placeholder="Frogeye">
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="sds">SDS</label>
                        <input type="text" class="form-control" id="sds" value="<?= old('sds') ?? $treatment['sds'] ?? "" ?>" name="sds" placeholder="SDS">
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="scn">SCN</label>
                        <input type="text" class="form-control" id="scn" value="<?= old('scn') ?? $treatment['scn'] ?? "" ?>" name="scn" placeholder="SCN">
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="refuge">Refuge (Only for corn)</label>
                        <select class="form-control" id="refuge" value="<?= old('refuge') ?? $treatment['refuge'] ?? "" ?>" name="refuge">
                            <option value="" selected>----Select----</option>
                            <option value="Y">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="seed_treatment">Seed Treatment</label>
                        <input type="text" class="form-control" id="seed_treatment" value="<?= old('seed_treatment') ?? $treatment['seed_treatment'] ?? "" ?>" name="seed_treatment" placeholder="Seed Treatment">
                    </div>
                    <?php if (!empty($treatment)) : ?>
                        <div class="form-group col-md-6 ">
                            <label for="is_approved">Status <sup class="text-danger">*</sup></label>
                            <select name="is_approved" id="is_approved" class="form-control">
                                <option value="1" <?= $treatment['is_approved'] == 0 ? "selected" : "" ?>>Approved</option>
                                <option value="0" <?= $treatment['is_approved'] == 0 ? "selected" : "" ?>>Unapproved</option>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <a href="<?= base_url('admin/treatment') ?>" class="btn btn-danger">Cancel</a>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('custom-js') ?>
<script>
    $(function() {
        $(document).on('change', '#crop_id', function() {
            let data = {
                '_token': "<?= csrf_hash() ?>",
                'crop_id': $(this).val()
            }
            $.ajax({
                url: "<?= base_url('admin/variety/get_variety_by_crop') ?>",
                type: 'post',
                dataType: 'json',
                data,
                success: function(res) {
                    if (res.status) {
                        $('#variety_id').empty();
                        $('#variety_id').append('<option value="" disabled selected>----Select Variety----</option>');

                        res.varieties.forEach(element => {
                            $('#variety_id').append(`<option value="${element.id}">${element.name||element.code}</option>`);
                        });
                    }
                }
            })
        })

        function checkTrialType() {
            let val = $('#crop_id').val();
            $('#trial_type_id option').each(function() {
                let option = $(this);
                let cropId = option.data('crop_id');

                if (cropId == val) {
                    option.prop('disabled', false).show();
                } else {
                    option.prop('disabled', true).hide();
                }
            });
        }
        $(document).ready(function() {
            checkTrialType();
        });
        $('#crop_id').change(function() {
            checkTrialType();
        });
    })
</script>
<?= $this->endSection() ?>