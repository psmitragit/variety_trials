<?= $this->extend('frontend/layouts/app') ?>
<?= $this->section('title') ?>
<?= $crop['name'] . " Location View" ?>
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
<style>
    .range_container {
        display: flex;
        flex-direction: column;
        margin: 12px 0px;
    }

    .sliders_control {
        position: relative;
        min-height: 15px;
    }

    .form_control {
        position: relative;
        display: flex;
        justify-content: space-between;
        font-size: 24px;
        color: #635a5a;
    }

    input[type=range]::-webkit-slider-thumb {
        -webkit-appearance: none;
        pointer-events: all;
        width: 24px;
        height: 24px;
        background-color: #fff;
        border-radius: 50%;
        box-shadow: 0 0 0 1px #C6C6C6;
        cursor: pointer;
    }

    input[type=range]::-moz-range-thumb {
        -webkit-appearance: none;
        pointer-events: all;
        width: 24px;
        height: 24px;
        background-color: #fff;
        border-radius: 50%;
        box-shadow: 0 0 0 1px #C6C6C6;
        cursor: pointer;
    }

    input[type=range]::-webkit-slider-thumb:hover {
        background: #f7f7f7;
    }

    input[type=range]::-webkit-slider-thumb:active {
        box-shadow: inset 0 0 3px #387bbe, 0 0 9px #387bbe;
        -webkit-box-shadow: inset 0 0 3px #387bbe, 0 0 9px #387bbe;
    }

    input[type="range"] {
        -webkit-appearance: none;
        appearance: none;
        height: 2px;
        width: 100%;
        position: absolute;
        background-color: #C6C6C6;
        pointer-events: none;
    }

    #fromSlider {
        height: 0;
        z-index: 1;
    }

    .form_control_container__time__input {
        border: 0px;
        font-size: 12px;
        color: #7b809a;
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
                        <h3 class="col-md-9 text-white text-capitalize ps-3"><?= $crop['name'] . " Location View" ?></h3>
                    </div>
                </div>


            </div>
            <div class="card-body px-3 pb-2 search-filter">

                <div class="container my-4">
                    <div class="d-flex align-items-center justify-content-between" style="max-width: 300px;">
                        <label for="toggleSwitch" class="mb-0">Average View</label>

                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" id="toggleSwitch" checked />
                        </div>

                        <label for="toggleSwitch" class="mb-0">Location View</label>
                    </div>
                </div>

                <div>
                    <div class="row g-4 mb-3">
                        <!-- Filter By -->
                        <div class="col-md-6">
                            <div class="w-100 mb-3">
                                <select id="sState" class="form-select mb-3 px-3 select2 filter-input" multiple data-placeholder="Select States(s)">
                                    <?php foreach ($states as $s) : ?>
                                        <option value="<?= $s['code'] ?>"><?= $s['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="w-100 mb-3">
                                <select id="sLocations" class="form-select mb-3 px-3 select2 filter-input filterTrials" multiple data-placeholder="Select Location(s)">
                                    <?php foreach ($locations as $s) : ?>
                                        <option value="<?= $s['location'] ?>"><?= $s['location'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="w-100 mb-3">
                                <select id="trial_types" class="form-select mb-3 px-3 select2 filter-input filterTrials" multiple data-placeholder="Select Trial(s)">
                                    <?php foreach ($trials as $s) : ?>
                                        <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="w-100 mb-3">
                                <select id="sVarieties" class="form-select mb-3 px-3 select2 filter-input filterTrials" multiple data-placeholder="Select Variety(s)">
                                    <?php foreach ($varieties as $v) : ?>
                                        <option value="<?= $v['code'] ?>"><?= $v['short_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="w-100 mb-3">
                                <select id="sTrait" class="form-select mb-3 px-3 select2 filter-input filterTrials">
                                    <option value="">Select a Trait</option>
                                    <?php foreach ($numericFilters as $v) : ?>
                                        <option value="<?= $v ?>"><?= $v ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php
                        $index = 0;
                        foreach ($numeric as $key => $value) {
                        ?>
                            <div class="col-md-3 range_wrapper" data-name="<?= $key ?>_range">
                                <label class="form-label fw-semibold"><?= $key ?>
                                    <span id="show_value_<?= $index ?>"></span>
                                </label>
                                <div class="range_container">
                                    <div class="sliders_control">
                                        <input class="fromSlider filter-range rangeInput" data-name="<?= $key ?>" type="range" value="<?= $value['min'] ?>" min="<?= $value['min'] ?>" max="<?= $value['max'] ?>" data-key="<?= $index ?>" data-type="<?= $key ?>" data-default="<?= $value['min'] ?>" />
                                        <input class="toSlider rangeInput" type="range" value="<?= $value['min'] ?>" min="<?= $value['min'] ?>" max="<?= $value['max'] ?>" data-key="<?= $index ?>" data-type="<?= $key ?>" data-default="<?= $value['min'] ?>"/>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-6 text-start">
                                            <label class="form-label fw-semibold m-0">Min</label>
                                            <input type="text" class="form-control fromInput rangeInput" value="<?= $value['min'] ?>" name="<?= $key ?>_min" data-default="<?= $value['min'] ?>" />
                                        </div>
                                        <div class="col-6 text-end">
                                            <label class="form-label fw-semibold m-0">Max</label>
                                            <input type="text" name="<?= $key ?>_max" class="form-control text-end toInput rangeInput" value="<?= $value['min'] ?>" data-default="<?= $value['min'] ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $index++;
                        }
                        ?>
                        <div class="col-md-6 col-lg-6 d-flex align-items-end gap-2 mt-3">
                            <button class="btn btn-success w-50" style="background: #4f772d !important;" id="show_trials">Show Trials</button>
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

    <?= $this->endSection() ?>

    <?= $this->section('custom-js') ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {});
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
            $('#sTrait').on('change', function(){
                showRangeSliders();
            });
            $('#show_trials').on('click', function() {
                getVarietyData();
            })
            showRangeSliders();
            function showRangeSliders() {
                $('.range_wrapper').addClass('d-none');
                $('.rangeInput').each(function() {
                    $(this).val($(this).data('default'));
                });
                let show = $('#sTrait').val();
                if (show) {
                    $(`div[data-name="${show}_range"]`).removeClass('d-none');
                }
            }

            $('#toggleSwitch').on('change', function() {
                window.location.href = "<?= base_url() . $crop['slug'] ?>/average";
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

        function getVarietyData(page = 1, order = "", dir = '') {
            $.ajax({
                url: '<?= base_url('get-location-data') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: $('input[name="_token"]').val(),
                    locations: $('#sLocations').val(),
                    trait_name: $('#sTrait').val(),
                    varieties: $('#sVarieties').val(),
                    trial_types: $('#trial_types').val(),
                    crop_id: "<?= $crop['id'] ?>",
                    veriables: getvariables(),
                    per_page: 10,
                    page: page,
                    order_by: order,
                    order_dir: dir
                },
                success: function(res) {
                    if (res.success == 1) {
                        $('#table-content').html(res.html);
                        generatePagination(res.total_records, res.per_page, res.current_page, res.order_by, res.order_dir);
                    } else if (res.success == 2) {
                        $('#table-content').html(res.html);
                        $('#pagination-links').html('');
                        $('#pagination-info').text('');
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

            html += `<li class="page-item ${currentPage == totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="getVarietyData(${currentPage + 1}, '${orderBy}', '${orderDir}')"><i class="ti ti-angle-double-right"></i></a>
                    </li>`;

            html += '</ul></nav>';

            $('#pagination-links').html(html);
        }

        document.addEventListener('DOMContentLoaded', function() {
            getVarietyData();
            $('#sState').on('change', function() {
                updateLocationSelect();
            })
            updateLocationSelect();

            $('#table-content').on('click', 'table tbody td', function(e) {
                $('.current_selected_tr').removeClass('current_selected_tr');
                let parent = $(this).parent('tr');
                parent.addClass('current_selected_tr');
                let tds = $('.current_selected_tr td');
                let headering = [
                    "Variety",
                    "Location",
                    $('#sTrait').val()
                ];

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
    </script>
    <?= $this->endSection() ?>