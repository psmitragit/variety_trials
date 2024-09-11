<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?= !empty($trial) ? "Edit" : "Create" ?> Trial</h4>
                <div class="my-2 text-danger">
                    <?= validation_list_errors() ?>
                </div>
                <?= form_open('', ['method' => 'post', 'class' => 'need-validation']) ?>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="crop_id">Crop</label>
                        <?php $crop_id = old('crop_id') ?? $trial['crop_id'] ?? 0; ?>
                        <select name="crop_id" id="crop_id" class="select2 form-control" required>
                            <option value="" selected disabled>---Select----</option>
                            <?php foreach (get_crops() as $l) : ?>
                                <option value="<?= $l['id'] ?>" <?= $l['id'] == $crop_id ? "selected" : "" ?>><?= $l['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="year">Year</label>
                        <input type="text" class="form-control" id="year" name="year" value="<?= old('year') ?? $trial['year'] ?? "" ?>" placeholder="Year" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="Program">Program</label>
                        <input type="text" class="form-control" id="Program" name="program" value="<?= old('program') ?? $trial['program'] ?? "" ?>" placeholder="Program" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="trial">Trial</label>
                        <input type="text" class="form-control" id="trial" name="trial" placeholder="Trial" value="<?= old('trial') ?? $trial['trial'] ?? "" ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="locid">Loc Id</label>
                        <?php $loc_id = old('locid') ?? $trial['location_code'] ?? 0; ?>
                        <select name="locid" id="locid" class="select2 form-control" required>
                            <option value="" selected disabled>---Select----</option>
                            <?php foreach ($locations as $l) : ?>
                                <option value="<?= $l['code'] ?>" <?= $l['code'] == $loc_id ? "selected" : "" ?>><?= $l['code'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location_code" name="location" placeholder="Location" required readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="state">State</label>
                        <input type="text" class="form-control" id="state" name="state" placeholder="State" required readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="variety_code">Variety ID</label>
                        <?php $v_id = old('variety') ?? $trial['variety_code'] ?? 0; ?>
                        <select name="variety" id="variety_code" class="form-control select2">
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
                    <div class="form-group col-md-6">
                        <label for="additional_name">Variety Additional</label>
                        <input type="text" class="form-control" id="additional_name" name="additional_name" value="<?= old('additional_name') ?? $variety['additional_name'] ?? "" ?>" placeholder="Variety Additional" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="herbicide">Herbicide Package</label>
                        <input type="text" class="form-control" id="herbicide" name="herbicide" value="<?= old('herbicide') ?? $variety['herbicide'] ?? "" ?>" placeholder="Herbicide Package" readonly>
                    </div>
                    <div class="col-md-12 row" id="variable_container">
                        <?php foreach ($variables as $k => $v) : ?>
                            <div class="col-md-6 form-group">
                                <label for="<?= $k ?>"><?= $k ?></label>
                                <input type="text" class="form-control" id="<?= $k ?>" name="variable[<?= $k ?>]" value="<?= $v ?>" placeholder="<?= $k ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (!empty($trial)) : ?>
                        <div class="form-group col-md-6">
                            <label for="is_approved">Status</label>
                            <select name="is_approved" id="is_approved" class="form-control">
                                <option value="1" <?= $trial['is_approved'] == 0 ? "selected" : "" ?>>Approved</option>
                                <option value="0" <?= $trial['is_approved'] == 0 ? "selected" : "" ?>>Unapproved</option>
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

                }
            },
            error: function(err) {
                console.log(err);
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
            },
            error: function(err) {
                console.log(err);
            }
        })
    }
    $(function() {
        loadLocation();
        loadVariety();

        $('#crop_id').on('change', function() {
            $('#variable_container').empty()
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
                        $('input[name=_token]').val(res.hash)
                        res.variables.forEach(ele => {
                            $('#variable_container').append(`
                                <div class="col-md-6 form-group">
                                    <label for="${ele.name}">${ele.name}</label>
                                    <input type="text" class="form-control" id="${ele.name}" name="variable[${ele.name}]" value="" placeholder="${ele.name}">
                                </div>
                            `);
                        })
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            })
        })

        $('#locid').on('change', function() {
            loadLocation()
        })


        $('#variety_code').on('change', function() {
            loadVariety();
        })
    })
</script>
<?= $this->endSection() ?>