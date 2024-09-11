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
            <div class="card-body px-3 pb-2">
                <div class="row pb-3">
                    <div class="col-md-12 mb-3">Filter By</div>
                    <div class="col-md-2 form-group mb-3">
                        <select id="sYear" class="form-control filter-input select2 ps-4">
                            <option value="0">Select Year</option>
                            <?php foreach ($years as $s) : ?>
                                <option value="<?= $s['year'] ?>"><?= $s['year'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <select id="sState" class="form-control filter-input select2 ps-4">
                            <option value="0">Select State</option>
                            <?php foreach ($states as $s) : ?>
                                <option value="<?= $s['code'] ?>"><?= strtoupper($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <select id="sBrand" class="form-control filter-input select2 ps-4">
                            <option value="0">Select Brand</option>
                            <?php foreach ($brands as $s) : ?>
                                <option value="<?= $s['name'] ?>"><?= strtoupper($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <select id="sVariety" class="form-control filter-input select2 ps-4">
                            <option value="0">Select Variety</option>
                            <?php foreach ($varieties as $s) : ?>
                                <option value="<?= $s['code'] ?>"><?= strtoupper($s['short_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <select id="sTrial" class="form-control filter-input select2 ps-4">
                            <option value="0">Select Trial</option>
                            <?php foreach ($trials as $s) : ?>
                                <option value="<?= $s['id'] ?>"><?= strtoupper($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <select id="sHerbicide" class="form-control filter-input select2 ps-4">
                            <option value="0">Select Herbicide</option>
                            <?php foreach ($herbicides as $s) : ?>
                                <option value="<?= $s['herbicide'] ?>"><?= strtoupper($s['herbicide']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php foreach ($varialeData as $k => $l) : ?>
                        <div class="col-md-2 form-group mb-3">
                            <select id="s<?= ucfirst($k); ?>" class="form-control select2 filter-input ps-4 filter-variables" data-type="<?= $k; ?>">
                                <option value="0">Select <?= ucfirst($k); ?></option>
                                <?php foreach ($l as $s) : ?>
                                    <option value="<?= $s ?>"><?= strtoupper($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endforeach; ?>

                    <div class="col-md-2 d-none">
                        <button class="btn btn-danger custom-h">Reset</button>
                    </div>
                </div>
                <!-- <div class="row text-center">
                    <div class="col-md-12">
                        <button  class="btn btn-danger">Reset</button>
                    </div>
                </div> -->
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 crop-table" id="dataTable">
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
                                <?php foreach ($variables as $l) : ?>
                                    <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"><?= $l['name'] ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody class="">

                        </tbody>
                    </table>
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

<?= $this->endSection() ?>

<?= $this->section('custom-js') ?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= env('GOOGLE_MAP_API_KEY') ?>&callback=initialMap" async defer></script>
<script>
    let coordinates = [];
    let markerName = [];

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
                <?php foreach ($variables as $l) : ?> {
                        data: '<?= $l['name'] ?>'
                    },
                <?php endforeach; ?>
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

        function getvariables() {
            let data = {}
            $('.filter-variables').each((i, v) => {
                data[$(v).attr('data-type')] = $(v).val()
            })
            return JSON.stringify(data);
        }
    })

    //Open Map
    $('#showMapBtn').on('click', function() {
        initMap();
        $('#showMap').modal('show');
    })

    // Initialize the map
    function initMap() {
        var myLatLng = coordinates.length > 0 ? coordinates[0] : {
            lat: 40.7128,
            lng: -74.0060
        };

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6,
            center: myLatLng
        });

        for (var i = 0; i < coordinates.length; i++) {
            var marker = new google.maps.Marker({
                position: coordinates[i],
                map: map,
                title: markerName[i]
            });
        }
    }


    //On page loadd map init
    function initialMap() {
        var myLatLng = coordinates.length > 0 ? coordinates[0] : {
            lat: 40.7128,
            lng: -74.0060
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

<?= $this->endSection() ?>