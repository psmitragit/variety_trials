<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title pt-3 pb-3"><?= !empty($user) ? "Edit" : "Create" ?> Moderator</h4>
            </div>
            <div class="card-body">
                <div class="text-danger"><?= validation_list_errors(); ?></div>
                <?= form_open('', ['method' => 'post', 'class' => 'need-validation']) ?>
                <div class="row">
                    <input type="hidden" name="id" value="<?= $user['id'] ?? 0 ?>">
                    <div class="form-group col-md-6">
                        <?php $type = old('type') ?? $user['type'] ?? "" ?>
                        <label for="type">User Type</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="" disabled selected>---SELECT---</option>
                            <option value="0" <?= $type == "0" ? "selected" : "" ?>>Super Admin</option>
                            <option value="1" <?= $type == "1" ? "selected" : "" ?>>Staff</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="shortcode">Username</label>
                        <input type="text" class="form-control" id="shortcode" name="username" value="<?= old('username') ?? $user['username'] ?? "" ?>" placeholder="Username" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="user">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?? $user['name'] ?? "" ?>" placeholder="Name" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="user">Email</label>
                        <input type="text" class="form-control" id="email" name="email" value="<?= old('email') ?? $user['email'] ?? "" ?>" placeholder="Email" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="user">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= old('phone') ?? $user['phone'] ?? "" ?>" placeholder="Phone">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="university">University</label>
                        <input type="text" class="form-control" id="university" name="university" value="<?= old('university') ?? $user['university'] ?? "" ?>" placeholder="University">
                    </div>
                    <div class="form-group col-md-6">
                        <?php $state = old('state') ?? $user['state'] ?? "" ?>
                        <label for="state">State</label>
                        <select name="state" id="state" class="form-control">
                            <option value="">All State</option>
                            <?php foreach (get_states() as $l) : ?>
                                <option value="<?= $l['id']; ?>" <?= $l['id'] == $state ? "selected" : ""; ?>><?= $l['name'] ?? $l['code']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <?php $crops = old('crop') ?? explode(',', $user['crop']) ?? "" ?>
                        <label for="crop">crop</label>
                        <select name="crop[]" id="crop" class="form-control select2" multiple>
                            <option value="">All Crops</option>
                            <?php foreach (get_crops() as $l) : ?>
                                <option value="<?= $l['id']; ?>" <?= in_array($l['id'], $crops) ? "selected" : ""; ?>><?= $l['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <?php $status = old('status') ?? $user['status'] ?? "" ?>
                        <label for="status">User status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="" disabled selected>---SELECT---</option>
                            <option value="1" <?= $status == "1" ? "selected" : "" ?>>Approved</option>
                            <option value="0" <?= $status == "0" ? "selected" : "" ?>>Unapproved</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <a class="btn btn-light" href="<?= base_url('admin/user') ?>">Cancel</a>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>