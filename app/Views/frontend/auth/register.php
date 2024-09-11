<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('frontend') ?>/img/apple-icon.png">
    <link rel="icon" type="image/png" href="<?= base_url('frontend') ?>/img/favicon.png">
    <title>
        VarietyTrials | Registration
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="<?= base_url('frontend') ?>/css/nucleo-icons.css" rel="stylesheet" />
    <link href="<?= base_url('frontend') ?>/css/nucleo-svg.css" rel="stylesheet" />

    <!-- CSS Files -->
    <link id="pagestyle" href="<?= base_url('frontend') ?>/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
    <style>
        label.is-invalid {
            display: inline-block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #ffb300 !important;
        }

        .form-control.is-invalid {
            border-color: #ffb300 !important;
            padding-right: unset !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='rgb(255, 179, 0)' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='rgb(255, 179, 0)' stroke='none'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 1rem 1rem !important;
        }

        .drop-down-custom {
            list-style: none;
            padding-left: 12px;
            position: absolute;
            z-index: 5;
            background: #fff;
            width: calc(100% - 44px);
            border-top: 3px solid #de2668;
            padding-top: 8px;
            box-shadow: 0px 2px 6px #a8a5a5;
            border-radius: 5px;
        }

        .drop-down-custom li {
            padding: 0px 10px;
        }

        .drop-down-custom li:hover {
            background: #5a5a5a;
            color: #fff;
        }
        #cross-div {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 16px;
  height: 16px;
  position: absolute;
  right: -5px;
  background: #dc2365;
  color: #fff;
  border-radius: 50%;
  top: -10px;
  font-size: 8px;
  cursor: pointer;
}


#cross-div:hover {
    background: #5e1a32;

}
    </style>
</head>

<body class="">
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url('<?= base_url('frontend') ?>/img/illustrations/illustration-signup.jpg'); background-size: cover;">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
                            <?php if (session()->has('success')) : ?>
                                <div class="text-center">
                                    <h5>
                                        <?= session()->getFlashdata('success'); ?>
                                    </h5>
                                </div>
                            <?php else : ?>
                                <div class="card card-plain">
                                    <div class="card-header">
                                        <h4 class="font-weight-bolder text-center">Sign Up</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-danger my-3">
                                            <?= session()->has('error') ? session()->getFlashdata('error') : "" ?>
                                            <?= validation_list_errors() ?>
                                        </div>
                                        <?= form_open('', ["role" => "form", 'method' => "post", 'class' => 'need-validation']); ?>
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control" required>
                                        </div>
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" required>
                                        </div>
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">University</label>
                                            <input type="text" name="university" class="form-control" required>
                                        </div>

                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">State</label>
                                            <input type="text" id="state-text-field" name="xccxvbv" class="form-control" required readonly>
                                            <input type="hidden" name="state" id="hiddenfield">
                                        </div>

                                        <ul class="drop-down-custom d-none" id="dropdown">
                                            <?php foreach ($states as $l) : ?>
                                                <li value="<?= $l['id']; ?>" data-value="<?= $l['code']; ?>">
                                                    <?= $l['code']; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>


                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Crop</label>
                                            <input type="text" id="state-text-field2" name="fgf" class="form-control" required readonly>
                                            <input type="hidden" name="crop" id="hiddenfield2">
                                        </div>

                                        <ul class="drop-down-custom d-none" id="dropdown2">
                                            <div class="cross" id="cross-div">X</div>
                                            <?php foreach ($crops as $l) : ?>
                                                <li value="<?= $l['id']; ?>" data-value=" <?= $l['name']; ?>">
                                                    <?= $l['name']; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>


                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" required>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">Sign
                                                Up</button>
                                        </div>
                                        <?= form_close(); ?>
                                    </div>
                                    <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                        <p class="mb-2 text-sm mx-auto">
                                            Already have an account?
                                            <a href="<?= base_url('admin/login'); ?>" class="text-primary text-gradient font-weight-bold">Sign in</a>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>


    <script src="<?= base_url('backend') ?>/js/jquery.min.js" type="text/javascript"></script>
    <script src="<?= base_url('backend') ?>/vendors/jquery-validation/jquery.validate.min.js"></script>
    <script src="<?= base_url('frontend') ?>/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="<?= base_url('frontend') ?>/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="<?= base_url('frontend') ?>/js/material-dashboard.min.js?v=3.1.0"></script>
    <script>
        $(function() {
            $('.need-validation').validate({
                errorClass: "is-invalid",
            });
        })
    </script>

    <script>
        $(document).ready(function() {
            const textField = $('#state-text-field');
            const hiddenField = $('#hiddenfield');
            const dropdown = $('#dropdown');

            textField.click(function() {
                dropdown.toggleClass('d-none');
            });
            dropdown.on('click', 'li', function() {
                const selectedValue = $(this).attr('value');
                const selectedDataValue = $(this).attr('data-value');

                hiddenField.val(selectedValue);
                textField.parent('.input-group').addClass('is-filled');
                textField.val(selectedDataValue).trigger('change');
                dropdown.addClass('d-none');
            });
            $(document).click(function(event) {
                if (!dropdown.is(event.target) && !textField.is(event.target)) {
                    dropdown.addClass('d-none');
                }
            });
        });

        $(document).ready(function() {
    const cropTextField = $('#state-text-field2');
    const hiddenCropField = $('#hiddenfield2');
    const cropDropdown = $('#dropdown2');

    cropTextField.click(function() {
        cropDropdown.toggleClass('d-none');
    });

    cropDropdown.on('click', 'li', function() {
        const selectedValue = $(this).attr('value');
        const selectedDataValue = $(this).attr('data-value');

        // Toggle the selection status
        $(this).toggleClass('selected');

        // Get all selected values and update the hidden field
        const selectedValues = cropDropdown.find('li.selected').map(function() {
            return $(this).attr('value');
        }).get();
        
        hiddenCropField.val(selectedValues.join(','));

        // Update the text field with selected values
        const selectedTexts = cropDropdown.find('li.selected').map(function() {
            return $(this).attr('data-value');
        }).get();
        cropTextField.val(selectedTexts.join(', '));

        cropTextField.parent('.input-group').addClass('is-filled');
    });

    $("#cross-div").click(function(e) {
        cropDropdown.addClass('d-none');
    });

    $(document).click(function(event) {
        if (!cropDropdown.is(event.target) && !cropTextField.is(event.target) && !cropDropdown.has(event.target).length) {
            cropDropdown.addClass('d-none');
        }
    });
});

    </script>



</body>

</html>