<?= $this->extend('frontend/layouts/app') ?>
<?= $this->section('title') ?>
<?= $crop['name'] . " Trials" ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>

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
<div class="row">
    <input type="hidden" name="_token" value="<?= csrf_hash() ?>">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="  bg-gradient-primary shadow-primary border-radius-lg pt-3 pb-2">
                    <div class="row px-3 align-items-center">
                        <h3 class="col-md-9 text-white text-capitalize ps-3"><?= $crop['name'] . " Trials" ?></h3>
                        <div class="col-md-3 text-end form-group">
                            <button class="btn  btn-secondary btn-color-change" id="showMapBtn"><i class="material-icons opacity-10">place</i> View On Map</button>
                        </div>
                    </div>
                </div>


            </div>
            <div class="card-body px-3 pb-2 search-filter">
                <!-- <div class="row pb-3"> -->
                <!-- <div class="col-md-12 mb-3">Filter By</div> -->
                <!-- <div class="col-md-2 form-group mb-3">
                        <select id="sYear" class="form-control filter-input select2 ps-4">
                            <option value="0">Select Year</option>
                            <?php
                            $insertedYear = [];
                            ?>
                            <?php foreach ($years as $s) : ?>
                                <?php
                                if (in_array($s['year'], $insertedYear)) {
                                    continue;
                                } else {
                                    $insertedYear[] = $s['year'];
                                }
                                ?>
                                <option value="<?= $s['year'] ?>"><?= $s['year'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <select id="sState" class="form-control filter-input select2 ps-4">
                            <option value="0">Select State</option>
                            <?php
                            $insertedState = [];
                            ?>
                            <?php foreach ($states as $s) : ?>
                                <?php
                                if (in_array($s['name'], $insertedState)) {
                                    continue;
                                } else {
                                    $insertedState[] = $s['name'];
                                }
                                ?>
                                <option value="<?= $s['code'] ?>"><?= strtoupper($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <select id="sBrand" class="form-control filter-input select2 ps-4">
                            <option value="0">Select Brand</option>
                            <?php
                            $insertedBrand = [];
                            ?>
                            <?php foreach ($brands as $s) : ?>
                                <?php
                                if (in_array($s['name'], $insertedBrand)) {
                                    continue;
                                } else {
                                    $insertedBrand[] = $s['name'];
                                }
                                ?>
                                <option value="<?= $s['name'] ?>"><?= strtoupper($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <select id="sVariety" class="form-control filter-input select2 ps-4">
                            <option value="0">Select Variety</option>
                            <?php
                            $insertedVariety = [];
                            ?>
                            <?php foreach ($varieties as $s) : ?>
                                <?php
                                if (in_array(strtoupper($s['short_name']), $insertedVariety)) {
                                    continue;
                                } else {
                                    $insertedVariety[] = strtoupper($s['short_name']);
                                }
                                ?>
                                <option value="<?= $s['code'] ?>"><?= strtoupper($s['short_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <select id="sTrial" class="form-control filter-input select2 ps-4">
                            <option value="0">Select Trial</option>
                            <?php
                            $insertedTrials = [];
                            ?>
                            <?php foreach ($trials as $s) : ?>
                                <?php
                                if (in_array($s['name'], $insertedTrials)) {
                                    continue;
                                } else {
                                    $insertedTrials[] = $s['name'];
                                }
                                ?>
                                <option value="<?= $s['id'] ?>"><?= strtoupper($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div> -->
                <!-- <?php foreach ($varialeData as $k => $l) : ?>
                        <div class="col-md-2 form-group mb-3">
                            <select id="s<?= ucfirst($k); ?>" class="form-control select2 filter-input ps-4 filter-variables" data-type="<?= $k; ?>">
                                <option value="0">Select <?= ucfirst($k); ?></option>
                                <?php foreach ($l as $s) : ?>
                                    <option value="<?= $s ?>"><?= strtoupper($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endforeach; ?> -->
                <!-- <div class="col-md-2 d-none">
                        <button class="btn btn-danger custom-h">Reset</button>
                    </div> -->
                <!-- </div> -->

                <button class="btn btn-outline-dark d-md-none mb-3" id="openMobileFilter">
                    <i class="fas fa-filter me-2"></i> Filters
                </button>
                <!-- Mobile Filter Modal -->
                <div class="modal fade" id="mobileFilterModal" tabindex="-1">
                    <div class="modal-dialog modal-fullscreen-sm-down">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Filters</h5>
                                <button type="button" class="btn closeBtn" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-xmark"></i></button>
                            </div>
                            <div class="modal-body" id="mobileFilterContainer">
                                <!-- dynamic filter section -->
                            </div>
                        </div>
                    </div>
                </div>

                <div id="mainFilterContainer" class="d-none d-md-block">
                    <div class="row g-4 mb-3">
                        <!-- Filter By -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Filter By</label>
                            <div class="w-100 mb-3">
                                <select id="sYear" class="form-select mb-3 px-3 select2 filter-input" data-placeholder="Select Year(s)" multiple>
                                    <?php foreach ($years as $s) : ?>
                                        <option value="<?= $s['year'] ?>"><?= $s['year'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="w-100 mb-3">
                                <select id="sState" class="form-select mb-3 px-3 select2 filter-input" data-placeholder="Select State(s)" multiple>
                                    <?php foreach ($states as $s) : ?>
                                        <option value="<?= $s['code'] ?>"><?= strtoupper($s['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="w-100 mb-3">
                                <select id="sLocation" class="form-select mb-3 px-3 select2 filter-input" data-placeholder="Select Location(s)" multiple>
                                </select>
                            </div>

                            <div class="w-100 mb-3">
                                <select id="sBrand" class="form-select mb-3 px-3 select2 filter-input" multiple data-placeholder="Select Brand(s)">
                                    <?php foreach ($brands as $s) : ?>
                                        <option value="<?= $s['name'] ?>"><?= strtoupper($s['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="w-100 mb-3">
                                <select id="sVariety" class="form-select mb-3 px-3 select2 filter-input" multiple data-placeholder="Select Variety(s)">
                                    <?php foreach ($varieties as $s) : ?>
                                        <option value="<?= $s['code'] ?>"><?= strtoupper($s['short_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="w-100 mb-3">
                                <select id="sTrial" class="form-select mb-3 px-3 select2 filter-input">
                                    <option value="0">Select Trial</option>
                                    <?php foreach ($trials as $s) : ?>
                                        <option value="<?= $s['id'] ?>"><?= strtoupper($s['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <?php
                            $allOverIndex = 0;
                            foreach ($other as $key => $value) {
                                $array = $varialeData[$value] ?? [];
                            ?>
                                <div class="w-100 mb-3">
                                    <select id="variable_<?= $allOverIndex++ ?>" class="form-select mb-3 px-3 select2 filter-input filter-variables" data-type="<?= $value ?>" <?= in_array($value, $multiselect) ? 'multiple data-placeholder="Select ' . $value . '(s)"' : '' ?>>
                                        <?php
                                        if (!in_array($value, $multiselect)) {
                                        ?>
                                            <option value="">Select <?= $value ?></option>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        foreach ($array as $k => $v) {
                                        ?>
                                            <option value="<?= $v ?>"><?= $v ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            <?php
                            }
                            ?>
                        </div>

                        <!-- Agronomic Traits -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Agronomic Traits</label>
                            <?php
                            foreach ($trait as $key => $value) {
                                $array = $varialeData[$value] ?? [];
                            ?>
                                <div class="w-100 mb-3">
                                    <select id="variable_<?= $allOverIndex++ ?>" class="form-select mb-3 px-3 select2 filter-input filter-variables" data-type="<?= $value ?>" <?= in_array($value, $multiselect) ? 'multiple data-placeholder="Select ' . $value . '(s)"' : '' ?>>
                                        <?php
                                        if (!in_array($value, $multiselect)) {
                                        ?>
                                            <option value="">Select <?= $value ?></option>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        foreach ($array as $k => $v) {
                                        ?>
                                            <option value="<?= $v ?>"><?= $v ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="w-100 mb-3">
                                <select id="production_practice" class="form-select mb-3 px-3 select2 filter-input filter-variables" data-type="production_pratice" multiple data-placeholder="Select Production Practice(s)">
                                    <option value="0">Full-Season</option>
                                    <option value="1">Double-Crop</option>
                                </select>
                            </div>
                            <div class="w-100 mb-3">
                                <select id="water_management" class="form-select mb-3 px-3 select2 filter-input filter-variables" data-type="water_management" multiple data-placeholder="Select Water Management(s)">
                                    <option value=""></option>
                                    <option value="0">Irrigated </option>
                                    <option value="1">Non-Irrigated </option>
                                </select>
                            </div>
                        </div>

                        <!-- Numeric -->
                        <div class="col-md-6">
                            <div class="row">
                                <?php
                                $index = 0;
                                foreach ($numeric as $key => $value) {
                                ?>
                                    <div class="col-md-6 position-relative">
                                        <label class="form-label fw-semibold"><?= $key ?> <span id="show_value_<?= $index ?>">- <?= $value['min'] ?></span></label>

                                        <input type="range"
                                            class="form-range filter-range" data-type="<?= $key ?>"
                                            min="<?= $value['min'] ?>"
                                            max="<?= $value['max']  + 1 ?>"
                                            value="<?= $value['min'] ?>"
                                            data-key="<?= $index ?>" step="0.1" data-min="<?= $value['min'] ?>" />

                                        <div class="d-flex justify-content-between text-muted small">
                                            <span><?= $value['min'] ?></span>
                                            <span><?= $value['max'] ?></span>
                                        </div>
                                    </div>
                                <?php
                                    $index++;
                                }
                                ?>
                                <div class="col-md-6 position-relative">
                                    <label class="form-label fw-semibold">Average Temparature <span id="show_value_avarage_temparature">- <?= $min_temp ?></span></label>

                                    <input type="range"
                                        class="form-range filter-range" data-type="avarage_temparature"
                                        min="<?= $min_temp ?>"
                                        max="<?= $max_temp + 1 ?>"
                                        value="<?= $min_temp ?>"
                                        data-key="avarage_temparature" step="0.1" data-min="<?= $min_temp ?>" />

                                    <div class="d-flex justify-content-between text-muted small">
                                        <span><?= $min_temp ?></span>
                                        <span><?= $max_temp ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6 position-relative">
                                    <label class="form-label fw-semibold">Average Percipitation <span id="show_value_avarage_percipitation">- <?= $min_precip ?></span></label>

                                    <input type="range"
                                        class="form-range filter-range" data-type="avarage_percipitation"
                                        min="<?= $min_precip ?>"
                                        max="<?= $max_precip + 1 ?>"
                                        value="<?= $min_precip ?>"
                                        data-key="avarage_percipitation" step="0.1" data-min="<?= $min_precip ?>" />

                                    <div class="d-flex justify-content-between text-muted small">
                                        <span><?= $min_precip ?></span>
                                        <span><?= $max_precip ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12 d-flex align-items-end gap-2 mt-3">
                                    <button class="btn btn-success w-50" style="background: #4f772d !important;" id="show_trials">Show Trials</button>
                                    <button class="btn btn-outline-secondary w-50 d-none">Quick Picks</button>
                                </div>
                            </div>
                        </div>

                        <!-- Management -->
                        <div class="col-md-6">
                            <?php
                                if(count($management) > 0){
                                    ?>
                                    <label class="form-label fw-semibold">Management</label>
                                    <?php
                                }
                            ?>
                            <?php
                            foreach ($management as $key => $value) {
                                $array = $varialeData[$value] ?? [];
                            ?>
                                <div class="w-100 mb-3">
                                    <select id="variable_<?= $allOverIndex++ ?>" class="form-select mb-3 px-3 select2 filter-input filter-variables" data-type="<?= $value ?>" <?= in_array($value, $multiselect) ? 'multiple data-placeholder="Select ' . $value . '(s)"' : '' ?>>
                                        <?php
                                        if (!in_array($value, $multiselect)) {
                                        ?>
                                            <option value="">Select <?= $value ?></option>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        foreach ($array as $k => $v) {
                                        ?>
                                            <option value="<?= $v ?>"><?= $v ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            <?php
                            }
                            ?>
                        </div>

                        <div class="col-12">
                            <div class="position-relative">
                                <div class="position-absolute search-variety-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <input type="text" id="search_keyword" placeholder="Search by variety, brand, or location…" class="form-control variety-search">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="row text-center">
                    <div class="col-md-12">
                        <button  class="btn btn-danger">Reset</button>
                    </div>
                </div> -->
                <div>
                    <div class="table-responsive p-0" id="scrollableTable">
                        <table class="table align-items-center mb-0 crop-table" id="dataTable" style="margin-top: 45px !important;">
                            <thead class="">
                                <tr>
                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Year</th>
                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">State</th>
                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Entry</th>
                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Trial</th>
                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">LocID</th>
                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Location</th>
                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">VarietyID</th>
                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Brand</th>
                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Variety</th>
                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Variety Additional</th>
                                    <?php foreach ($variables as $l) :
                                        $safeClass = preg_replace('/[^a-zA-Z0-9_-]/', '_', $l);
                                    ?>
                                        <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 <?= $safeClass ?>_colummn_filter"><?= $l ?></th>
                                    <?php endforeach; ?>
                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Average Temparature
                                    </th>
                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Average Percipitation
                                    </th>
                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Production Pratice
                                    </th>
                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Water Management
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="">

                            </tbody>
                        </table>
                    </div>
                    <div class="custom-scrollbar-container" id="customScrollbar">
                        <div class="custom-scrollbar-thumb" id="customThumb"></div>
                    </div>
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
                foreach ($variables as $key => $v) {
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
<script src="https://maps.googleapis.com/maps/api/js?key=<?= env('GOOGLE_MAP_API_KEY') ?>&callback=initialMap" async defer></script>
<script>
    let coordinates = [];
    let markerName = [];

    function toggleColumns(dt) {
        $('.show_hide_field_checkbox').each(function() {
            const columnClass = $(this).val();
            const isChecked = $(this).is(':checked');

            dt.columns().every(function(index) {
                const header = $(dt.column(index).header());
                if (header.hasClass(columnClass + '_colummn_filter')) {
                    dt.column(index).visible(isChecked);
                }
            });
        });
    }

    function newexportaction(e, dt, button, config) {
        var self = this;
        var oldStart = dt.settings()[0]._iDisplayStart;
        dt.one('preXhr', function(e, s, data) {
            data.start = 0;
            data.length = 2147483647;
            dt.one('preDraw', function(e, settings) {
                if (button[0].className.indexOf('buttons-copy') >= 0) {
                    $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                    $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                        $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                        $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                    $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                        $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                        $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                    $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                        $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                        $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                } else if (button[0].className.indexOf('buttons-print') >= 0) {
                    $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                }
                dt.one('preXhr', function(e, s, data) {
                    settings._iDisplayStart = oldStart;
                    data.start = oldStart;
                });
                setTimeout(dt.ajax.reload, 0);
                return false;
            });
            dt.one('xhr', function(e, settings, json, xhr) {
                $('input[name="_token"]').val(json.hash)
                coordinates = json.coordinates
                markerName = json.pointname
            });
        });
        dt.ajax.reload();
    };


    $(function() {
        const dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            serverMethod: 'post',
            ajax: {
                url: '<?= base_url('get-trials') ?>',
                dataType: 'json',
                data: {
                    _token: () => {
                        return $('input[name="_token"]').val()
                    },
                    id: "<?= $crop['id'] ?>",
                    year: () => $('#sYear').val(),
                    state: () => $('#sState').val(),
                    brand: () => $('#sBrand').val(),
                    variety: () => $('#sVariety').val(),
                    trial: () => $('#sTrial').val(),
                    herbicide: () => $('#sHerbicide').val(),
                    location: () => $('#sLocation').val(),
                    variables: () => getvariables()
                }
            },

            columns: [{
                    data: 'year'
                },
                {
                    data: 'state'
                },
                {
                    data: 'program'
                },
                {
                    data: 'trial'
                },
                {
                    data: 'loc_id'
                },
                {
                    data: 'location'
                },
                {
                    data: 'variety_id'
                },
                {
                    data: 'brand'
                },
                {
                    data: 'variety'
                },
                {
                    data: 'variety_additional'
                },
                <?php foreach ($variables as $l) : $safeClass = preg_replace('/[^a-zA-Z0-9_-]/', '_', $l); ?> {
                        data: '<?= $l ?>',
                        className: '<?= $safeClass ?>_colummn_filter'
                    },
                <?php endforeach; ?> {
                    data: 'avarage_temparature'
                },
                {
                    data: 'avarage_percipitation'
                },
                {
                    data: 'production_pratice'
                },
                {
                    data: 'water_management'
                },
            ],
            drawCallback: function() {
                var api = this.api();
                $('input[name="_token"]').val(api.ajax.json().hash);
                coordinates = api.ajax.json().coordinates;
                markerName = api.ajax.json().pointname;
            },
            order: [
                [0, 'desc']
            ],
            // responsive: true,
            language: {
                "paginate": {
                    "previous": '<i class="ti ti-angle-double-left"></i>',
                    "next": '<i class="ti ti-angle-double-right"></i>'
                }
            },
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                {
                    "extend": 'csv',
                    "text": 'CSV',
                    "titleAttr": 'CSV',
                    "action": newexportaction
                },
            ]
        });

        $('.filter-input').on('change', function() {
            dataTable.ajax.reload()
        })

        $('#show_trials').on('click', function() {
            dataTable.ajax.reload()
        });

        $('#search_keyword').on('input', function() {
            const value = $(this).val();
            $('#dataTable_filter input[type="search"]').val(value).trigger('input');
            dataTable.ajax.reload();
        });

        function getvariables() {
            let data = {}
            $('.filter-variables').each((i, v) => {
                if ($(v).val() != '') {
                    data[$(v).attr('data-type')] = $(v).val()
                }
            })
            $('.filter-range').each((i, v) => {
                if (parseFloat($(v).val()) > parseFloat($(v).attr('data-min'))) {
                    data[$(v).attr('data-type')] = $(v).val()
                }
            });
            return JSON.stringify(data);
        }
    })

    //Open Map
    $('#showMapBtn').on('click', function() {
        initMap();
        $('#showMap').modal('show');
    })

    function initMap() {
        var myLatLng = coordinates.length > 0 ? coordinates[0] : {
            lat: 40.712776,
            lng: -74.005974
        };

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6,
            center: myLatLng
        });

        var marker, i;
        var infowindow = new google.maps.InfoWindow({
            content: ''
        });

        for (i = 0; i < coordinates.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(coordinates[i]['lat'], coordinates[i]['lng']),
                map: map
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent(markerName[i]);
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }
    }


    function initialMap() {
        var myLatLng = coordinates.length > 0 ? coordinates[0] : {
            lat: 40.712776,
            lng: -74.005974
        };

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6,
            center: myLatLng
        });
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: "New York"
        });
    }
</script>
<script>
    const scrollContainer = document.getElementById('scrollableTable');
    const customScrollbar = document.getElementById('customScrollbar');
    const customThumb = document.getElementById('customThumb');

    function updateThumb() {
        const containerWidth = scrollContainer.clientWidth;
        const contentWidth = scrollContainer.scrollWidth;
        const scrollLeft = scrollContainer.scrollLeft;

        const scrollbarWidth = customScrollbar.clientWidth;
        const thumbWidth = Math.max((containerWidth / contentWidth) * scrollbarWidth, 30);

        const maxThumbLeft = scrollbarWidth - thumbWidth;
        const thumbLeft = (scrollLeft / (contentWidth - containerWidth)) * maxThumbLeft;

        customThumb.style.width = thumbWidth + 'px';
        customThumb.style.left = thumbLeft + 'px';
    }

    scrollContainer.addEventListener('scroll', updateThumb);

    window.addEventListener('resize', updateThumb);

    updateThumb();

    let isDragging = false;
    let startX;
    let startScrollLeft;

    customThumb.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.pageX;
        startScrollLeft = scrollContainer.scrollLeft;
        document.body.style.userSelect = 'none';
    });

    document.addEventListener('mouseup', () => {
        isDragging = false;
        document.body.style.userSelect = 'auto';
    });

    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        const dx = e.pageX - startX;
        const contentWidth = scrollContainer.scrollWidth;
        const containerWidth = scrollContainer.clientWidth;
        const scrollableWidth = contentWidth - containerWidth;
        const scrollbarWidth = customScrollbar.clientWidth;
        const maxThumbLeft = scrollbarWidth - customThumb.clientWidth;
        const scrollChange = (dx / maxThumbLeft) * scrollableWidth;
        scrollContainer.scrollLeft = Math.min(Math.max(startScrollLeft + scrollChange, 0), scrollableWidth);
    });

    const headering = [
        "Year",
        "State",
        "Entry",
        "Trial",
        "LocID",
        "Location",
        "VarietyID",
        "Brand",
        "Variety",
        "Variety Additional",
        <?php foreach ($variables as $l) : ?> "<?= addslashes($l) ?>",
        <?php endforeach; ?>
        "Average Temparature",
        "Average Percipitation","Production Pratice",
        "Water Management"
    ];

    $('#dataTable').on('click', 'tbody td', function(e) {
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

    $('input[type="range"]').on('input', function() {
        $('#show_value_' + $(this).data('key')).html('- ' + $(this).val());
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const filterContainer = document.getElementById("mainFilterContainer");
        const mobileTarget = document.getElementById("mobileFilterContainer");
        const openMobileFilterBtn = document.getElementById("openMobileFilter");

        let isInMobile = false;

        function moveFilterIfMobile() {
            const isMobile = window.innerWidth < 768;
            if (isMobile && !isInMobile) {
                mobileTarget.appendChild(filterContainer.firstElementChild);
                isInMobile = true;
            } else if (!isMobile && isInMobile) {
                filterContainer.appendChild(mobileTarget.firstElementChild);
                isInMobile = false;
            }
        }


        openMobileFilterBtn.addEventListener("click", function() {
            const modal = new bootstrap.Modal(document.getElementById("mobileFilterModal"));
            modal.show();
        });

        moveFilterIfMobile();
        window.addEventListener("resize", moveFilterIfMobile);

        //add display column
        const insertInterval = setInterval(() => {
            const $dtButtons = $('.dt-buttons');
            if ($dtButtons.length > 0) {
                $dtButtons.append(
                    `<button class="dt-button custom-button-for-datatable" type="button" id="show-column-button" onClick="showColumnButtonClicked()">
                            <span>Display Column</span>
                        </button>`
                );
                clearInterval(insertInterval);
            }
        }, 100);

        $('.show_hide_field_checkbox').prop('checked', true);

        function updateColumnVisibility() {
            $('.show_hide_field_checkbox').each(function() {
                const colName = $(this).val();
                const isVisible = $(this).is(':checked');
                const selector = `.${colName.replace(/\s+/g, '_')}_colummn_filter`;

                $(selector).css('display', isVisible ? '' : 'none');
            });
        }

        $('.show_hide_field_checkbox').on('change', function() {
            updateColumnVisibility();
        });

        $('#dataTable').on('draw.dt', function() {
            updateColumnVisibility();
        });

        $('#sState').on('change', function() {
            updateLocationSelect();
        })
        updateLocationSelect();

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
                        $('#sLocation').empty();
                        $.each(res.location, function(index, loc) {
                            console.log(loc);
                            $('#sLocation').append(
                                $('<option>', {
                                    value: loc.location,
                                    text: loc.location
                                })
                            );
                        });
                        $('#sLocation').trigger('change');
                    }
                }
            })
        }
    });

    function showColumnButtonClicked() {
        $('#showColumnModal').modal('show');
    }
</script>
<?= $this->endSection() ?>