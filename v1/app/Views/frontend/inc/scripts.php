<script src="<?= base_url('backend') ?>/js/jquery.min.js" type="text/javascript"></script>
<script src="<?= base_url('frontend') ?>/js/core/popper.min.js"></script>
<script src="<?= base_url('frontend') ?>/js/core/bootstrap.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/jquery-validation/jquery.validate.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/toastr/toastr.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/select2/select2.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/datatables/datatables.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/datatables/dataTables.buttons.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/datatables/jszip.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/datatables/buttons.html5.min.js"></script>
<script src="<?= base_url('backend') ?>/vendors/datatables/dataTables.responsive.js"></script>

<script src="<?= base_url('frontend') ?>/js/main.js"></script>

<script src="<?= base_url('frontend') ?>/js/plugins/perfect-scrollbar.min.js"></script>
<script src="<?= base_url('frontend') ?>/js/plugins/smooth-scrollbar.min.js"></script>
<script src="<?= base_url('frontend') ?>/js/plugins/chartjs.min.js"></script>
<script src="<?= base_url('frontend') ?>/js/github-buttons.js"></script>
<script src="<?= base_url('frontend') ?>/js/material-dashboard.min.js?v=3.1.0"></script>
<?= $this->renderSection('custom-js') ?>
<?= $this->include('notification') ?>