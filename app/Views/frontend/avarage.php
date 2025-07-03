<?= $this->extend('frontend/layouts/app') ?>
<?= $this->section('title') ?>
<?= $crop['name'] . " Average" ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="<?= base_url('frontend/css/crop-style.css') ?>" rel="stylesheet">
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
                        <div class="col-md-9">
                            <h3 class="text-white text-capitalize ps-3">
                                <?= $crop['name'] . " Average" ?>
                            </h3>
                        </div>
                        <div class="col-md-3 d-flex justify-content-md-end">
                            <a href="<?= base_url($crop['slug'] . "/average-map") ?>" class="btn btn-primary" style="background: #2a3127 !important;">
                                <i class="fa-solid fa-arrow-left me-2"></i> Go back
                            </a>
                        </div>
                    </div>
                </div>


            </div>
            <div class="card-body px-3 pb-2 search-filter">
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
                    <h3>
                        Site Selection
                    </h3>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="row">
                                <label for="" class="selection_label">Environment</label>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">
                                        Avg. temp
                                    </label>
                                    <div class="range_container">
                                        <div class="sliders_control">
                                            <input class="fromSlider filter-range2" data-name="avarage_temparature" type="range" value="0" min="<?= $locationResult['min_temp'] ?>" max="<?= $locationResult['max_temp'] ?>" data-key="9999" data-type="avg_temp" />
                                            <input class="toSlider" type="range" value="<?= $locationResult['min_temp'] ?>" min="<?= $locationResult['min_temp'] ?>" max="<?= $locationResult['max_temp'] ?>" data-key="999" data-type="avg_temp" />
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-6 text-start">
                                                <label class="form-label fw-semibold m-0">Min</label>
                                                <input type="text" class="form-control fromInput" value="<?= $locationResult['min_temp'] ?>" name="avarage_temparature_min" />
                                            </div>
                                            <div class="col-6 text-end">
                                                <label class="form-label fw-semibold m-0">Max</label>
                                                <input type="text" name="avarage_temparature_max" class="form-control text-end toInput" value="<?= $locationResult['min_temp'] ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">
                                        Avg. precip
                                    </label>
                                    <div class="range_container">
                                        <div class="sliders_control">
                                            <input class="fromSlider filter-range2" data-name="avarage_percipitation" type="range" value="0" min="<?= $locationResult['min_precip'] ?>" max="<?= $locationResult['max_precip'] ?>" data-key="9999" data-type="avg_percip" />
                                            <input class="toSlider" type="range" value="<?= $locationResult['min_precip'] ?>" min="<?= $locationResult['min_precip'] ?>" max="<?= $locationResult['max_precip'] ?>" data-key="1000" data-type="avg_percip" />
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-6 text-start">
                                                <label class="form-label fw-semibold m-0">Min</label>
                                                <input type="text" class="form-control fromInput" value="<?= $locationResult['min_precip'] ?>" name="avarage_percipitation_min" />
                                            </div>
                                            <div class="col-6 text-end">
                                                <label class="form-label fw-semibold m-0">Max</label>
                                                <input type="text" name="avarage_percipitation_max" class="form-control text-end toInput" value="<?= $locationResult['min_precip'] ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label for="" class="selection_label">Trial</label>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        Production Practice
                                    </label>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="w-100 mb-3">
                                                <select id="production_practice" class="form-select mb-3 px-3 select2 filter-input" multiple data-placeholder="Select Production Practice">
                                                    <option value="0">Full-Season</option>
                                                    <option value="1">Double-Crop</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        Water Management
                                    </label>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="w-100 mb-3">
                                                <select id="waterManagement" class="form-select mb-3 px-3 select2 filter-input filterTrials" multiple data-placeholder="Select Water Management">
                                                    <option value="0">Irrigated</option>
                                                    <option value="1">Non-Irrigated</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="row">
                                <label for="" class="selection_label">Location</label>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        State(s)
                                    </label>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="w-100 mb-3">
                                                <select id="sState" class="form-select mb-3 px-3 select2 filter-input" multiple data-placeholder="Select States(s)">
                                                    <?php foreach ($states as $s) : ?>
                                                        <option value="<?= $s['code'] ?>"><?= $s['name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        Year(s)
                                    </label>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="w-100 mb-3">
                                                <select id="sYears" class="form-select mb-3 px-3 select2 filter-input filterTrials" multiple data-placeholder="Select Year(s)">
                                                    <?php foreach ($years as $s) : ?>
                                                        <option value="<?= $s['year'] ?>"><?= $s['year'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        Location(s)
                                    </label>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="w-100 mb-3">
                                                <select id="sLocations" class="form-select mb-3 px-3 select2 filter-input filterTrials" multiple data-placeholder="Select Location(s)">
                                                    <?php foreach ($selected_locations_names as $s) : ?>
                                                        <option value="<?= $s ?>" selected><?= $s ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3>
                        Variety Data
                    </h3>
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <label for="" class="selection_label">Variety</label>
                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold">
                                        Brand(s)
                                    </label>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="w-100 mb-3">
                                                <select id="sBrands" class="form-select mb-3 px-3 select2 filter-input filterTrials" multiple data-placeholder="Select Brand(s)">
                                                    <?php foreach ($brand as $v) : ?>
                                                        <option value="<?= $v ?>"><?= $v ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold">
                                        Variety(s)
                                    </label>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="w-100 mb-3">
                                                <select id="sVarieties" class="form-select mb-3 px-3 select2 filter-input filterTrials" multiple data-placeholder="Select Variety(s)">
                                                    <?php foreach ($varieties as $v) : ?>
                                                        <option value="<?= $v['code'] ?>"><?= $v['short_name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold">
                                        Maturity(s)
                                    </label>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="w-100 mb-3">
                                                <select id="trial_types" class="form-select mb-3 px-3 select2 filter-input filterTrials" multiple data-placeholder="Select Maturity(s)">
                                                    <?php foreach ($trials as $s) : ?>
                                                        <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold">
                                        Herbicide(s)
                                    </label>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="w-100 mb-3">
                                                <select id="herbicides" class="form-select mb-3 px-3 select2 filter-input filterTrials" multiple data-placeholder="Select Herbicide(s)">
                                                    <?php foreach ($herbicides as $s) : ?>
                                                        <option value="<?= $s ?>"><?= $s ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="form-label fw-semibold">
                                        Insecticide(s)
                                    </label>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="w-100 mb-3">
                                                <select id="insecticides" class="form-select mb-3 px-3 select2 filter-input filterTrials" multiple data-placeholder="Select insecticide(s)">
                                                    <?php foreach ($insecticides as $s) : ?>
                                                        <option value="<?= $s ?>"><?= $s ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mb-3">
                        <?php
                        $index = 0;
                        foreach ($numeric as $key => $value) {
                        ?>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold"><?= $key ?>
                                    <span id="show_value_<?= $index ?>"></span>
                                </label>
                                <div class="range_container">
                                    <div class="sliders_control">
                                        <input class="fromSlider filter-range" data-name="<?= $key ?>" type="range" value="<?= $value['min'] ?>" min="<?= $value['min'] ?>" max="<?= $value['max'] ?>" data-key="<?= $index ?>" data-type="<?= $key ?>" />
                                        <input class="toSlider" type="range" value="<?= $value['min'] ?>" min="<?= $value['min'] ?>" max="<?= $value['max'] ?>" data-key="<?= $index ?>" data-type="<?= $key ?>" />
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-6 text-start">
                                            <label class="form-label fw-semibold m-0">Min</label>
                                            <input type="text" class="form-control fromInput" value="<?= $value['min'] ?>" name="<?= $key ?>_min" />
                                        </div>
                                        <div class="col-6 text-end">
                                            <label class="form-label fw-semibold m-0">Max</label>
                                            <input type="text" name="<?= $key ?>_max" class="form-control text-end toInput" value="<?= $value['min'] ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $index++;
                        }
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 d-flex align-items-end gap-2 mt-3">
                            <button class="btn btn-success w-50" style="background: #4f772d !important;" id="show_trials">Apply Filters</button>
                            <button class="btn btn-success w-50" id="displayColumn" style="background: #4f772d !important;" id="show_trials">Display Column</button>
                            <button class="btn btn-success w-50" style="background: #4f772d !important;" id="downloadCsv">Download CSV</button>
                            <button class="btn btn-outline-secondary w-50 d-none">Quick Picks</button>
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

    <div class="modal fade" id="showColumnModal" tabindex="-1">
        <div class="modal-dialog modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Check/Uncheck Checkbox to Show/Hide Columns</h5>
                    <button type="button" class="btn closeBtn" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-xmark"></i></button>
                </div>
                <div class="modal-body">
                    <?php
                    foreach ($numericFilters as $key => $v) {
                        $safeClass = preg_replace('/[^a-zA-Z0-9_-]/', '_', $v);
                    ?>
                        <div class="form-group mb-1">
                            <input type="checkbox" value="<?= $safeClass ?>" name="show_hide_field_checkbox" class="show_hide_field_checkbox" id="<?= $safeClass ?>_checkbox">
                            <label for="<?= $safeClass ?>_checkbox"><?= $v ?></label>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?= $this->endSection() ?>

    <?= $this->section('custom-js') ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let preselectedLocations = <?= json_encode($selected_locations_names) ?>;
            $('#sLocations').val(preselectedLocations).trigger('change');
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

            $('.filterTrials').on('change', function() {
                getVarietyData();
            });
            $('#show_trials').on('click', function() {
                getVarietyData();
            })
            $('#toggleSwitch').on('change', function() {
                const params = new URLSearchParams(window.location.search);
                const cropSlug = "<?= $crop['slug'] ?>";
                const baseUrl = "<?= base_url() ?>";
                const locationParams = params.getAll('location[]');

                let url = `${baseUrl}${cropSlug}/location`;
                if (locationParams.length > 0) {
                    const queryString = locationParams.map(loc => `location[]=${encodeURIComponent(loc)}`).join('&');
                    url += '?' + queryString;
                }
                window.location.href = url;
            });

            $('#displayColumn').on('click', function() {
                $('#showColumnModal').modal('show');
            });

            $('.show_hide_field_checkbox').prop('checked', true);

            $('.show_hide_field_checkbox').on('change', function() {
                updateColumnVisibility();
            });

            $('#dataTable').on('draw.dt', function() {
                updateColumnVisibility();
            });

            $('#downloadCsv').on('click', function() {
                getVarietyData(1, '', 'asc', 1);
            });
        });


        function getVarietyData(page = 1, order = '', dir = 'asc', csv = 0) {
            order = order.trim().toLowerCase() === "maturity (dap)" ? 'maturity_dap' : order;
            $.ajax({
                url: '<?= base_url('get-average') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: $('input[name="_token"]').val(),
                    years: $('#sYears').val(),
                    locations: $('#sLocations').val(),
                    trial_types: $('#trial_types').val(),
                    varieties: $('#sVarieties').val(),
                    state: $('#sState').val(),
                    crop_id: "<?= $crop['id'] ?>",
                    veriables: getvariables(),
                    environment: getEnvironmentData(),
                    herbicides: $('#herbicides').val(),
                    insecticides: $('#insecticides').val(),
                    brand: $('#sBrands').val(),
                    per_page: 10,
                    page: page,
                    order_by: order,
                    order_dir: dir,
                    csv: csv
                },
                success: function(res) {
                    if (csv > 0) {
                        const link = document.createElement('a');
                        link.href = res.download_url;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    } else {
                        $('#table-content').html(res.html);
                        generatePagination(res.total_records, res.per_page, res.current_page, res.order_by, res.order_dir);
                        updateColumnVisibility();
                        updateDropDowns(res);
                    }
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

        function updateDropDowns(res) {
            updateSelectWithPreservedSelection('#production_practice', res.production_pratice_option_html);
            updateSelectWithPreservedSelection('#waterManagement', res.water_management_option_html);
            updateSelectWithPreservedSelection('#sBrands', res.brand_option_html);
            updateSelectWithPreservedSelection('#sVarieties', res.variety_option_html);
            updateSelectWithPreservedSelection('#trial_types', res.maturity_option_html);
            updateSelectWithPreservedSelection('#herbicides', res.herbicide_option_html);
            updateSelectWithPreservedSelection('#insecticides', res.insecticide_option_html);
        }

        function updateSelectWithPreservedSelection(selector, newOptionsHtml) {
            const $select = $(selector);
            const currentVals = $select.val();
            $select.html(newOptionsHtml);
            if (currentVals && currentVals.length > 0) {
                const validSelections = currentVals.filter(val => $select.find(`option[value="${val}"]`).length > 0);
                $select.val(validSelections);
            }

            $select.trigger('change.select2');
        }

        function getEnvironmentData() {
            let data = {};
            $('.filter-range2').each((i, v) => {
                let name = $(v).data('name');
                let min = $(`input[name="${name}_min"]`).val();
                let max = $(`input[name="${name}_max"]`).val();

                if (parseFloat(min) != parseFloat(max)) {
                    data[name] = [min, max]
                }
            });
            data['production_pratice'] = $('#production_practice').val();
            data['water_management'] = $('#waterManagement').val();
            return JSON.stringify(data);
        }

        function getvariables() {
            let data = {}
            $('.filter-range').each((i, v) => {
                let name = $(v).data('name');
                let min = $(`input[name="${name}_min"]`).val();
                let max = $(`input[name="${name}_max"]`).val();

                if (parseFloat(min) != parseFloat(max)) {
                    data[name] = [min, max]
                }
            });
            return JSON.stringify(data);
        }

        function generatePagination(total, perPage, currentPage, orderBy, orderDir) {
            const totalPages = Math.ceil(total / perPage);
            const startEntry = (currentPage - 1) * perPage + 1;
            let endEntry = currentPage * perPage;
            if (endEntry > total) endEntry = total;

            $('#pagination-info').text(`Showing ${startEntry} to ${endEntry} of ${total.toLocaleString()} entries`);

            let html = '<nav><ul class="pagination mb-0">';

            html += `<li class="page-item ${currentPage == 1 ? 'disabled' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="getVarietyData(${currentPage - 1}, '${orderBy}', '${orderDir}')"><i class="ti ti-angle-double-left"></i></a>
                    </li>`;

            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, currentPage + 4);

            if (startPage > 1) {
                html += `<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="getVarietyData(1, '${orderBy}', '${orderDir}')">1</a></li>`;
                if (startPage > 2) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                html += `<li class="page-item ${i == currentPage ? 'active' : ''}">
                            <a class="page-link" href="javascript:void(0);" onclick="getVarietyData(${i}, '${orderBy}', '${orderDir}')">${i}</a>
                        </li>`;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
                html += `<li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="getVarietyData(${totalPages}, '${orderBy}', '${orderDir}')">${totalPages}</a></li>`;
            }

            // Next button
            html += `<li class="page-item ${currentPage == totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="getVarietyData(${currentPage + 1}, '${orderBy}', '${orderDir}')"><i class="ti ti-angle-double-right"></i></a>
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

            $('#sState').val('').trigger('change');
            $('#sState').on('change', function() {
                updateLocationSelect();
            });

            //range 
            document.querySelectorAll('.range_container').forEach(container => {
                const fromSlider = container.querySelector('.fromSlider');
                const toSlider = container.querySelector('.toSlider');
                const fromInput = container.querySelector('.fromInput');
                const toInput = container.querySelector('.toInput');

                if (!fromSlider || !toSlider || !fromInput || !toInput) return;

                function getParsed(fromEl, toEl) {
                    return [parseFloat(fromEl.value), parseFloat(toEl.value)];
                }

                function fillSlider(from, to, sliderColor, rangeColor, controlSlider) {
                    const rangeDistance = parseFloat(to.max) - parseFloat(to.min);
                    const fromPosition = from.value - to.min;
                    const toPosition = to.value - to.min;
                    controlSlider.style.background = `linear-gradient(
                    to right,
                    ${sliderColor} 0%,
                    ${sliderColor} ${(fromPosition)/(rangeDistance)*100}%,
                    ${rangeColor} ${(fromPosition)/(rangeDistance)*100}%,
                    ${rangeColor} ${(toPosition)/(rangeDistance)*100}%,
                    ${sliderColor} ${(toPosition)/(rangeDistance)*100}%,
                    ${sliderColor} 100%)`;
                }

                function setToggleAccessible(currentTarget, toSlider) {
                    if (Number(currentTarget.value) <= 0) {
                        toSlider.style.zIndex = 2;
                    } else {
                        toSlider.style.zIndex = 0;
                    }
                }

                function controlFromSlider() {
                    const [from, to] = getParsed(fromSlider, toSlider);
                    fillSlider(fromSlider, toSlider, '#C6C6C6', '#73a942', toSlider);
                    if (from > to) {
                        fromSlider.value = to;
                        fromInput.value = to;
                    } else {
                        fromInput.value = from;
                    }
                }

                function controlToSlider() {
                    const [from, to] = getParsed(fromSlider, toSlider);
                    fillSlider(fromSlider, toSlider, '#C6C6C6', '#73a942', toSlider);
                    if (from <= to) {
                        toSlider.value = to;
                        toInput.value = to;
                    } else {
                        toInput.value = from;
                        toSlider.value = from;
                    }
                    setToggleAccessible(toSlider, toSlider);
                }

                function controlFromInput() {
                    const [from, to] = getParsed(fromInput, toInput);
                    fillSlider(fromInput, toInput, '#C6C6C6', '#73a942', toSlider);
                    if (from > to) {
                        fromSlider.value = to;
                        fromInput.value = to;
                    } else {
                        fromSlider.value = from;
                    }
                }

                function controlToInput() {
                    const [from, to] = getParsed(fromInput, toInput);
                    fillSlider(fromInput, toInput, '#C6C6C6', '#73a942', toSlider);
                    if (from <= to) {
                        toSlider.value = to;
                        toInput.value = to;
                    } else {
                        toInput.value = from;
                    }
                    setToggleAccessible(toInput, toSlider);
                }

                // Initial render
                fillSlider(fromSlider, toSlider, '#C6C6C6', '#73a942', toSlider);
                setToggleAccessible(toSlider, toSlider);

                // Event bindings
                fromSlider.addEventListener('input', controlFromSlider);
                toSlider.addEventListener('input', controlToSlider);
                fromInput.addEventListener('input', controlFromInput);
                toInput.addEventListener('input', controlToInput);
            });
        });

        function updateLocationSelect() {
            let states = $('#sState').val();
            $.ajax({
                url: "<?= base_url('get-locations-by-state') ?>",
                type: 'POST',
                data: {
                    _token: () => {
                        return $('input[name="_token"]').val()
                    },
                    id: "<?= $crop['id'] ?>",
                    states: states
                },
                success: function(res) {
                    try {
                        res = JSON.parse(res);
                    } catch (error) {
                        console.log(error);
                        return;
                    }

                    if (res.location) {
                        $('#sLocations').empty();
                        $.each(res.location, function(index, loc) {
                            console.log(loc);
                            $('#sLocations').append(
                                $('<option>', {
                                    value: loc.location,
                                    text: loc.location
                                })
                            );
                        });
                        $('#sLocations').trigger('change');
                    }
                },
                beforeSend: function() {
                    $('#loader').removeClass('d-none');
                },
                complete: function() {
                    $('#loader').addClass('d-none');
                }
            })
        }

        function updateColumnVisibility() {
            $('.show_hide_field_checkbox').each(function() {
                const colName = $(this).val();
                const isVisible = $(this).is(':checked');
                const selector = `.${colName.replace(/\s+/g, '_')}_colummn_filter`;

                $(selector).css('display', isVisible ? '' : 'none');
            });
        }
    </script>
    <?= $this->endSection() ?>