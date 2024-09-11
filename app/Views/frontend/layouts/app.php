<!DOCTYPE html>
<html lang="en">

<?= $this->include('frontend/inc/head') ?>

<body class="g-sidenav-show  bg-gray-200">
    <?= $this->include('frontend/inc/sidebar') ?>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <?= $this->include('frontend/inc/navbar') ?>
        <div class="container-fluid py-4">
            <?= $this->renderSection('content') ?>
        </div>
        <?= $this->include('frontend/inc/footer') ?>
    </main>
    <?= $this->include('frontend/inc/scripts') ?>
</body>

</html>