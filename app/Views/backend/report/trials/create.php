<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title pt-3 pb-3"><?= !empty($trialData) ? "Edit" : "Create" ?> Trial</h4>
            </div>
            <div class="card-body">
                <div class="my-2 text-danger">
                    <?= validation_list_errors() ?>
                </div>
                <?= form_open('', ['method' => 'post', 'class' => 'need-validation']) ?>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="crop_id">Crop</label>
                        <?php $crop_id = old('crop_id') ?? $trialData['crop_id'] ?? 0; ?>
                        <select name="crop_id" id="crop_id" class="select2 form-control" required>
                            <option value="" selected disabled>---Select----</option>
                            <?php foreach (get_crops() as $l) : ?>
                                <option value="<?= $l['id'] ?>" <?= $l['id'] == $crop_id ? "selected" : "" ?>><?= $l['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="trial_name_id">Trial Name</label>
                        <?php $trial_name_id = old('trial_name_id') ?? $trialData['trial_name_id'] ?? 0; ?>
                        <select name="program" id="trial_name_id" class="select2 form-control" required>
                            <option value="" selected disabled>---Select----</option>
                            <?php $trialTypeId = old('program') ?? $trialData['program'] ?? 0;
                            foreach ($trials as $l) : ?>
                                <option value="<?= $l['id']; ?>" <?= $l['id'] == $trialTypeId ? "selected" : ""; ?>><?= $l['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="year">Year</label>
                        <input type="text" class="form-control" id="year" name="year" value="<?= old('year') ?? $trialData['year'] ?? "" ?>" placeholder="Year" readonly required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="trial">Trial</label>
                        <input type="hidden" name="trial" id="trial_id" placeholder="Trial" value="<?= old('trial') ?? $trialData['trial'] ?? "" ?>" required>
                        <input type="text" class="form-control" id="trial" name="trial_name" placeholder="Trial" value="<?= old('trial_name') ?? $trialData['trial_type_name'] ?? "" ?>" readonly required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="locid">Site</label>
                        <?php $loc_id = old('locid') ?? $trialData['location_code'] ?? 0; ?>
                        <select name="locid" id="locid" class="select2 form-control" required>
                            <option value="" selected disabled>---Select----</option>
                            <?php foreach ($locations as $l) : ?>
                                <option value="<?= $l['code'] ?>" <?= $l['code'] == $loc_id ? "selected" : "" ?>><?= $l['code'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location_code" name="location" value="<?= old('location') ?? $location['location'] ?? ""; ?>" placeholder="Location" required readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="state">State</label>
                        <input type="text" class="form-control" id="state" name="state" placeholder="State" value="<?= old('state') ?? $location['state_code'] ?? ""; ?>" required readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="variety_code">Variety ID</label>
                        <?php $v_id = old('variety') ?? $trialData['variety_code'] ?? 0; ?>
                        <select name="variety" id="variety_code" class="form-control select2" required>
                            <option value="" selected disabled>---Selected----</option>
                            <?php foreach ($varieties as $l) : ?>
                                <option value="<?= $l['code'] ?>" <?= $l['code'] == $v_id ? "selected" : "" ?>><?= $l['code'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="brand">Brand</label>
                        <input type="text" class="form-control" id="brand" name="brand" value="<?= old('brand') ?? $variety['brand'] ?? "" ?>" placeholder="Brand" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="short_name">Variety</label>
                        <input type="text" class="form-control" id="short_name" name="short_name" value="<?= old('short_name') ?? $variety['short_name'] ?? "" ?>" placeholder="Variety" readonly>
                    </div>
                    <!-- <div class="form-group col-md-6">
                        <label for="additional_name">Variety Additional</label>
                        <input type="text" class="form-control" id="additional_name" name="additional_name" value="<?= old('additional_name') ?? $variety['additional_name'] ?? "" ?>" placeholder="Variety Additional" readonly>
                    </div> -->
                    <!-- <div class="form-group col-md-6">
                        <label for="entry">Treatment Entry</label>
                        <?php $entry = old('entry') ?? $trialData['entry'] ?? 0; ?>
                        <select name="entry" id="entry" class="form-control select2" required>
                            <option value="" selected disabled>---Selected----</option>
                            <?php foreach ($treatments as $l) : ?>
                                <option value="<?= $l['name'] ?>" <?= $l['name'] == $entry ? "selected" : "" ?>><?= $l['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div> -->
                    <div class="col-md-12 row" id="variable_container">
                        <?php foreach ($variables as $k => $v) : ?>
                            <div class="col-md-6 form-group">
                                <label for="<?= $k ?>"><?= $k ?></label>
                                <input type="text" class="form-control" id="<?= $k ?>" name="variable[<?= $k ?>]" value="<?= $v ?>" placeholder="<?= $k ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (!empty($trialData) && isAllowed()) : ?>
                        <div class="form-group col-md-6">
                            <label for="is_approved">Status</label>
                            <select name="is_approved" id="is_approved" class="form-control">
                                <option value="1" <?= $trialData['is_approved'] == 0 ? "selected" : "" ?>>Approved</option>
                                <option value="0" <?= $trialData['is_approved'] == 0 ? "selected" : "" ?>>Unapproved</option>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <a href="<?= base_url('admin/report/trials') ?>" class="btn btn-light">Cancel</a>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('custom-js') ?>
<script>
    function loadVariety() {
        $.ajax({
            url: "<?= base_url('admin/variety/get_single') ?>",
            type: 'post',
            dataType: 'json',
            data: {
                _token: $('input[name=_token]').val(),
                id: $('#variety_code').val()
            },
            success: function(res) {
                if (res.status) {
                    $('input[name=_token]').val(res.hash)
                    $('#brand').val(res.data.brand)
                    $('#short_name').val(res.data.short_name)
                    $('#additional_name').val(res.data.additional_name)
                    $('#herbicide').val(res.data.herbicide)

                    res.treatments.forEach(ele => {
                        $('#entry').append(`<option value="${ele.name}">${ele.name||ele.code}</option>`);
                    })
                }
            }
        })
    }

    function loadLocation() {
        $.ajax({
            url: "<?= base_url('admin/location/get_single') ?>",
            type: 'post',
            dataType: 'json',
            data: {
                _token: $('input[name=_token]').val(),
                id: $('#locid').val()
            },
            success: function(res) {
                if (res.status) {
                    $('input[name=_token]').val(res.hash)
                    $('#location_code').val(res.data.location)
                    $('#state').val(res.data.state_code)

                }
            }
        })
    }
    $(function() {
        // loadLocation();
        // loadVariety();

        $('#crop_id').on('change', function() {
            $('#variable_container').empty()
            resetSelect('#entry');
            $.ajax({
                url: "<?= base_url('admin/crop/get_variables') ?>",
                type: 'post',
                dataType: 'json',
                data: {
                    _token: $('input[name=_token]').val(),
                    id: $('#crop_id').val()
                },
                success: function(res) {
                    if (res.status) {
                        resetSelect('#locid')
                        resetSelect('#trial_name_id')
                        resetSelect('#variety_code')

                        $('input[name=_token]').val(res.hash)
                        res.variables.forEach(ele => {
                            $('#variable_container').append(`
                                <div class="col-md-6 form-group">
                                    <label for="${ele.name}">${ele.name}</label>
                                    <input type="text" class="form-control" id="${ele.name}" name="variable[${ele.name}]" value="" placeholder="${ele.name}">
                                </div>
                            `);
                        })

                        res.varieties.forEach(ele => {
                            $('#variety_code').append(`<option value="${ele.code}" >${ele.code}</option>`)
                        })


                        res.trials.forEach(ele => {
                            $('#trial_name_id').append(`<option value="${ele.id}" >${ele.name}</option>`)
                        })
                    }
                }
            })
        })

        function resetSelect(selector) {
            $(`${selector}`).empty();
            $(`${selector}`).append(`<option value="" selected disabled>---Select----</option>`);
        }


        $('#trial_name_id').on('change', function() {
            let data = {
                _token: "<?= csrf_hash(); ?>",
                id: $(this).val()
            };
            $.ajax({
                url: "<?= base_url('admin/trials/get_single') ?>",
                type: 'post',
                dataType: 'json',
                data,
                success: function(res) {
                    if (res.status) {
                        $('#year').val(res.trial.year);
                        $('#trial').val(res.trial.trial_type);
                        $('#trial_id').val(res.trial.trial_type_id);
                        resetSelect('#locid')
                        res.locations.forEach(ele => {
                            $('#locid').append(`<option value="${ele.code}">${ele.code}</option>`);
                        })
                    }
                }
            })
        })



        $('#locid').on('change', function() {
            loadLocation()
        })

        $('#variety_code').on('change', function() {
            resetSelect('#entry');
            loadVariety();
        })
    })
</script>
<?= $this->endSection() ?>