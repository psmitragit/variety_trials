<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        VarietyTrials Administration
    </title>
    <link rel="stylesheet" href="<?= base_url('backend') ?>/css/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="<?= base_url('backend') ?>/images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <h2 class="logo fw-bold"><span>Variety</span><span class="text-primary">Trials</span></h2>
                            </div>
                            <h6 class="fw-light">Sign in to continue.</h6>
                            <div class="text-danger mt-3">
                                <?= session()->has('error') ? session()->getFlashdata('error') : "" ?>
                                <?= validation_list_errors() ?>
                            </div>
                            <div class="text-success mt-3">
                                <?= session()->has('success') ? session()->getFlashdata('success') : "" ?>
                            </div>
                            <?= form_open(base_url('admin/login'), ['class' => 'pt-3', 'method' => 'post']) ?>
                            <div class="form-group">
                                <input type="text" name="email" class="form-control form-control-lg" placeholder="Username ...">
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control form-control-lg" placeholder="Password ...">
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                            </div>
                            <?= form_close() ?>
                            <div class="my-2 d-flex justify-content-end align-items-center">
                                <a href="<?= base_url('forgot-password') ?>" class="auth-link text-black">Forgot password?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
</body>

</html>