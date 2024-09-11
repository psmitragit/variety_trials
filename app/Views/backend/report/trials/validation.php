<?php
$headerLength = sizeof($expectedHeaders);
$start = 0;
$end = $headerLength - 1;
?>
<table class="table table-responsive table-hover table-stripped table-bordered vtable">
    <thead class="position-sticky top-0 start-0 bg-white">
        <tr>
            <th>#</th>
            <?php foreach ($expectedHeaders as $l) : ?>
                <th><?= $l; ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($validatedData as $vd) : ?>
            <tr class="<?= !empty($vd['error']) ? 'bg-danger' : ''; ?>">

                <td>
                    <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="<ul><?= $vd['error'] ?></ul>">
                        <span class="mdi mdi-information i-22"></span>
                    </span>
                </td>

                <?php for ($i = 0; $i < $headerLength; $i++) : ?>
                    <td><?= $vd[$i] ?? ""; ?></td>
                <?php endfor; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>