<!DOCTYPE html>
<html lang="en">
<?= $this->include('backend/inc/head') ?>

<body>
    <div class="container-scroller">
        <?= $this->include('backend/inc/navbar') ?>
        <div class="container-fluid page-body-wrapper">
            <?= $this->include('backend/inc/sidebar') ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <?= $this->renderSection('content') ?>
                </div>
                <!-- content-wrapper ends -->
                <?= $this->include('backend/inc/footer') ?>
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <?= $this->include('backend/inc/foot') ?>
    <?= $this->include('notification') ?>
    <!-- End custom js for this page-->
    <?= $this->renderSection('custom-js') ?>
</body>

</html>