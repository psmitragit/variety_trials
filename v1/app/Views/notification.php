<script>
    toastr.options = {
        timeOut: 2000,
        "closeButton": true,
        "progressBar": true
    }
</script>
<?php
$success =  session()->getFlashdata('success');
if (!empty($success)) : ?>
    <script>
        toastr.success("<?= $success ?>");
    </script>
<?php endif;
$error =  session()->getFlashdata('error');
if (!empty($error)) : ?>
    <script>
        toastr.error("<?= $error ?>");
    </script>
<?php endif;
$info = session()->getFlashdata('info');
if (!empty($info)) : ?>
    <script>
        toastr.info("<?= $info ?>");
    </script>
<?php endif;
$warning = session()->getFlashdata('warning');
if (!empty($warning)) : ?>
    <script>
        toastr.warning("<?= $warning ?>");
    </script>
<?php endif; ?>