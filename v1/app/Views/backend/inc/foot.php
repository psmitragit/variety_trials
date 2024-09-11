<!-- plugins:js -->
<script src="<?= base_url('backend') ?>/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="<?= base_url('backend') ?>/vendors/chart.js/Chart.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/progressbar.js/progressbar.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/jquery-validation/jquery.validate.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/toastr/toastr.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/select2/select2.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/datatables/datatables.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/datatables/dataTables.buttons.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/datatables/jszip.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/datatables/buttons.html5.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/datatables/dataTables.responsive.js"></script>

<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="<?= base_url('backend') ?>/js/off-canvas.js"></script>
<script src="<?= base_url('backend') ?>/js/hoverable-collapse.js"></script>
<script src="<?= base_url('backend') ?>/js/template.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="<?= base_url('backend') ?>/js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?= base_url('backend') ?>/js/dashboard.js"></script>
<script src="<?= base_url('backend') ?>/js/Chart.roundedBarCharts.js"></script>

<script>
    $(function() {
        $('[data-bs-toggle="tooltip"]').tooltip()
        $('.need-validation').each((i, ele) => {
            $(ele).validate();
        });
        $('.select2').each((i, ele) => {
            $(ele).select2();
        })
    })
</script>