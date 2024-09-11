<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title pt-3 pb-3"><?= !empty($location) ? "Edit" : "Create" ?> Location</h4>
            </div>
            <div class="card-body">
                <div class="text-danger"><?= validation_list_errors(); ?></div>
                <?= form_open('', ['method' => 'post', 'class' => 'need-validation']) ?>
                <div class="row">
                    <input type="hidden" name="id" value="<?= $location['id'] ?? 0 ?>">
                    <div class="form-group col-md-6 pb-2">
                        <label for="shortcode">Loc Id </label>
                        <input type="text" class="form-control" data-bs-toggle="tooltip" title="Please add 2 letters of state followed by an underscore (_) and then the site name like AR_NET." id="shortcode" name="code" value="<?= old('code') ?? $location['code'] ?? "" ?>" placeholder="AR_NET" required>
                    </div>
                    <div class="form-group col-md-6 pb-2">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location" name="location" value="<?= old('location') ?? $location['location'] ?? "" ?>" placeholder="Location" required>
                    </div>
                    <div class="form-group col-md-6 pb-2">
                        <label for="farm">Management</label>
                        <input type="text" class="form-control" id="farm" name="farm" value="<?= old('farm') ?? $location['farm'] ?? "" ?>" placeholder="Management">
                    </div>
                    <div class="form-group col-md-6 pb-2">
                        <label for="state">State</label>
                        <!-- <input type="text" class="form-control" id="state" name="state_code" placeholder="State"> -->
                        <?php $state = old('state_code') ?? $location['state_code'] ?? ""; ?>
                        <select name="state_code" id="state" class="form-control select2" required>
                            <option value="" disabled selected>---Select---</option>
                            <?php foreach ($states as $l) : ?>
                                <option value="<?= $l['code'] ?>" <?= $l['code'] == $state ? "selected" : "" ?>><?= $l['name'] ?? $l['code'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6 pb-2">
                        <label for="city">City</label>
                        <?php $city = old('city_code') ?? $location['city_code'] ?? ""; ?>
                        <!-- <input type="text" class="form-control" id="city" name="city_code" placeholder="City"> -->
                        <select name="city_code" class="form-control select2" id="city" required>
                            <option value="" disabled selected>---Select---</option>
                            <?php foreach ($cities as $l) : ?>
                                <option value="<?= $l['code'] ?>" <?= $l['code'] == $city ? "selected" : "" ?>><?= $l['code'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6 pb-2">
                        <label for="lat">Latitute</label>
                        <input type="text" class="form-control" id="lat" name="lat" value="<?= old('lat') ?? $location['lat'] ?? "" ?>" placeholder="Latitute">
                    </div>
                    <div class="form-group col-md-6 pb-2">
                        <label for="long">Longititute</label>
                        <input type="text" class="form-control" id="long" name="long" value="<?= old('long') ?? $location['long'] ?? "" ?>" placeholder="Longititute">
                    </div>
                    <div class="form-group col-md-6 pb-2">
                        <label for="soil_type">Soil Type</label>
                        <input type="text" class="form-control" id="soil_type" name="soil_type" placeholder="Soil Type">
                    </div>
                    <div class="form-group col-md-6 pb-2">
                        <label for="irrigation">Irrigation</label>
                        <select name="irrigation" id="irrigation" class="form-control">
                            <option value="">---SELECT----</option>
                            <option value="Irrigated" <?= ($location['irrigation'] ?? "") == "Irrigated" ? "selected" : ""; ?>>Irrigated</option>
                            <option value="Non Irrigated" <?= ($location['irrigation'] ?? "") == "Non Irrigated" ? "selected" : ""; ?>>Non Irrigated</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <a href="<?= base_url('admin/location') ?>" class="btn btn-light">Cancel</a>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('custom-js') ?>
<script>
    $(function() {
        $('#state').on('change', function() {
            $('#city').empty();
            $('#city').append(`<option value="" disabled selected>---Select---</option>`);
            $.ajax({
                url: "<?= base_url('admin/location/get-city') ?>",
                type: 'post',
                dataType: 'json',
                data: {
                    _token: $('input[name="_token"]').val(),
                    state: $('#state').val()
                },
                success: function(res) {
                    $('input[name="_token"]').val(res.hash)
                    if (res.status) {
                        res.data.forEach(value => {
                            $('#city').append(`<option value="${value.code}">${value.code}</option>`);
                        })
                    }
                },
                error: function(err) {
                    console.log(err);
                }

            })
        })
    })
</script>
<?= $this->endSection() ?>