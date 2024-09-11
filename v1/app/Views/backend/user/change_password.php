<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Change Password</h4>
                <div class="text-danger"><?= validation_list_errors(); ?></div>
                <?= form_open('', ['method' => 'post', 'class' => 'need-validation']) ?>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="old">Old Password</label>
                        <input type="text" class="form-control" id="old" name="old" value="" placeholder="Old Password" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="new">New Password</label>
                        <input type="text" class="form-control" id="new" name="new" placeholder="New Password" minlength="8" maxlength="16" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="confirm">Confirm Password</label>
                        <input type="text" class="form-control" id="confirm" name="confirm" minlength="8" maxlength="16" placeholder="Confirm Password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <button class="btn btn-light" type="button">Cancel</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>