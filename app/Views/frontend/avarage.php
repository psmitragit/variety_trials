<?= $this->extend('frontend/layouts/app') ?>
<?= $this->section('title') ?>
<?= $crop['name'] . " Average" ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .btn-color-change {
        background: #FEFAE0 !important;
        color: #000 !important;
        border: 1px solid #FEFAE0 !important;
    }

    .btn-color-change:hover {
        background: #48702D !important;
        color: #fff !important;
        border: 1px solid #fff !important;
    }

    .custom-h {
        height: 28px;
        padding-top: 1px !important;
        padding-bottom: 0 !important;
        display: flex;
        align-items: center;
    }

    #dataTable_filter {
        display: none;
    }
</style>
<style>
    .loading-dots {
        display: inline-flex;
        gap: 5px;
    }

    .loading-dots span {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #007bff;
        animation: blink 1.4s infinite ease-in-out both;
    }

    .loading-dots span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .loading-dots span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes blink {

        0%,
        80%,
        100% {
            opacity: 0;
        }

        40% {
            opacity: 1;
        }
    }

    #loader {
        position: fixed;
        top: 50%;
        left: 50%;
        z-index: 99;
    }
</style>
<div id="loader" class="d-none">
    <div class="loading-dots">
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<div class="row">
    <input type="hidden" name="_token" value="<?= csrf_hash() ?>">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="  bg-gradient-primary shadow-primary border-radius-lg pt-3 pb-2">
                    <div class="row px-3 align-items-center">
                        <h3 class="col-md-9 text-white text-capitalize ps-3"><?= $crop['name'] . " Average" ?></h3>
                    </div>
                </div>


            </div>
            <div class="card-body px-3 pb-2 search-filter">
                <!-- <button class="btn btn-outline-dark d-md-none mb-3" id="openMobileFilter">
                    <i class="fas fa-filter me-2"></i> Filters
                </button> -->
                <!-- Mobile Filter Modal -->
                <!-- <div class="modal fade" id="mobileFilterModal" tabindex="-1">
                    <div class="modal-dialog modal-fullscreen-sm-down">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Filters</h5>
                                <button type="button" class="btn closeBtn" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-xmark"></i></button>
                            </div>
                            <div class="modal-body" id="mobileFilterContainer">
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="container my-4">
                    <div class="d-flex align-items-center justify-content-between" style="max-width: 300px;">
                        <label for="toggleSwitch" class="mb-0">Average View</label>

                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" id="toggleSwitch" />
                        </div>

                        <label for="toggleSwitch" class="mb-0">Location View</label>
                    </div>
                </div>

                <div>
                    <div class="row g-4 mb-3">
                        <!-- Filter By -->
                        <div class="col-md-6">
                            <div class="w-100 mb-3">
                                <select id="sYears" class="form-select mb-3 px-3 select2 filter-input" multiple data-placeholder="Select Year(s)">
                                    <?php foreach ($years as $s) : ?>
                                        <option value="<?= $s['year'] ?>"><?= $s['year'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="w-100 mb-3">
                                <select id="sVarieties" class="form-select mb-3 px-3 select2 filter-input" multiple data-placeholder="Select Variety(s)">
                                    <?php foreach ($varieties as $v) : ?>
                                        <option value="<?= $v['code'] ?>"><?= $v['short_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="table-content" style="overflow: auto;">
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3" id="pagination">
                        <div id="pagination-info" class="text-muted small"></div>
                        <div id="pagination-links"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="showMap">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Locations</h5>
                    <button type="button" class="btn-close text-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="material-icons opacity-10">clear</i></button>
                </div>
                <div class="modal-body">
                    <div id="map" class="cropLocationMap"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showDataCustomModal" tabindex="-1" aria-labelledby="sitemapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content text-white">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="showDataCustomModalLabel">Trials Data</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="my-3 d-flex justify-content-center">
                    <button class="btn btn-success m-auto" style="max-width: 250px;" data-bs-dismiss="modal" aria-label="Close">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?= $this->endSection() ?>

    <?= $this->section('custom-js') ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let currentOrder = '';
            let currentDir = 'asc';

            $(document).on('click', 'th.sortable', function() {
                const field = $(this).data('field');

                if (currentOrder === field) {
                    currentDir = currentDir === 'asc' ? 'desc' : 'asc';
                } else {
                    currentOrder = field;
                    currentDir = 'asc';
                }

                $('th.sortable .sort-icon').removeClass('text-primary');
                const icons = $(this).find('.sort-icon');
                if (currentDir === 'asc') {
                    icons.first().addClass('text-primary');
                } else {
                    icons.last().addClass('text-primary');
                }

                getVarietyData(1, currentOrder, currentDir);
            });

            $('#sVarieties').on('change', function() {
                getVarietyData();
            });
            $('#sYears').on('change', function() {
                getVarietyData();
            });
            $('#toggleSwitch').on('change', function() {
                window.location.href = "<?= base_url() . $crop['slug'] ?>/location";
            });
        });


        function getVarietyData(page = 1, order = '', dir = 'asc') {
            order = order.trim().toLowerCase() === "maturity (dap)" ? 'maturity_dap' : order;
            $.ajax({
                url: '<?= base_url('get-avarage') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: $('input[name="_token"]').val(),
                    years: $('#sYears').val(),
                    varieties: $('#sVarieties').val(),
                    crop_id: "<?= $crop['id'] ?>",
                    per_page: 10,
                    page: page,
                    order_by: order,
                    order_dir: dir
                },
                success: function(res) {
                    $('#table-content').html(res.html);
                    generatePagination(res.total_records, res.per_page, res.current_page, res.order_by, res.order_dir);
                },
                error: function(xhr, status, error) {

                },
                beforeSend: function() {
                    $('#loader').removeClass('d-none');
                },
                complete: function() {
                    $('#loader').addClass('d-none');
                }
            });
        }

        function generatePagination(total, perPage, currentPage, orderBy, orderDir) {
            const totalPages = Math.ceil(total / perPage);
            const startEntry = (currentPage - 1) * perPage + 1;
            let endEntry = currentPage * perPage;
            if (endEntry > total) endEntry = total;

            $('#pagination-info').text(`Showing ${startEntry} to ${endEntry} of ${total.toLocaleString()} entries`);

            let html = '<nav><ul class="pagination mb-0">';

            html += `<li class="page-item ${currentPage == 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="getVarietyData(${currentPage - 1}, '${orderBy}', '${orderDir}')"><i class="ti ti-angle-double-left"></i></a>
                    </li>`;

            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 4);

            if (startPage > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="getVarietyData(1, '${orderBy}', '${orderDir}')">1</a></li>`;
                if (startPage > 2) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                html += `<li class="page-item ${i == currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="getVarietyData(${i}, '${orderBy}', '${orderDir}')">${i}</a>
                        </li>`;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
                html += `<li class="page-item"><a class="page-link" href="#" onclick="getVarietyData(${totalPages}, '${orderBy}', '${orderDir}')">${totalPages}</a></li>`;
            }

            // Next button
            html += `<li class="page-item ${currentPage == totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="getVarietyData(${currentPage + 1}, '${orderBy}', '${orderDir}')"><i class="ti ti-angle-double-right"></i></a>
                    </li>`;

            html += '</ul></nav>';

            $('#pagination-links').html(html);
        }

        document.addEventListener('DOMContentLoaded', function() {
            getVarietyData();

            let headering = [
                "Variety", 
                "Year",
                <?php foreach ($numericFilters as $l) : ?> "<?= addslashes($l) ?>",
                <?php endforeach; ?>
            ];

            console.log(headering);

            $('#table-content').on('click', 'table tbody td', function(e) {
                $('.current_selected_tr').removeClass('current_selected_tr');
                let parent = $(this).parent('tr');
                parent.addClass('current_selected_tr');
                let tds = $('.current_selected_tr td');

                let html = '<table class="table table-striped"><tbody>';
                let index = 0;

                tds.each(function() {
                    const value = $(this).text();
                    html += `<tr>
                        <td>${headering[index]}</td>
                        <td>${value}</td>
                    </tr>`;
                    index++;
                });

                html += '</tbody></table>';
                $('#showDataCustomModal .modal-body').html(html);
                $('#showDataCustomModal').modal('show');
            });
        });
    </script>
    <?= $this->endSection() ?>