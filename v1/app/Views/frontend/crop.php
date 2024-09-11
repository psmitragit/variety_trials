<?= $this->extend('frontend/layouts/app') ?>
<?= $this->section('title') ?>
<?= $crop['name'] . " Trials" ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="row">
    <input type="hidden" name="_token" value="<?= csrf_hash() ?>">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3"><?= $crop['name'] . " Trials" ?></h6>
                </div>
            </div>
            <div class="card-body px-2 pb-2">
                <div class="row pb-3">
                    <div class="col-md-2 form-group">
                        <select id="sYear" class="form-control select2 ps-4">
                            <option value="0">Select Year</option>
                            <?php for ($i = 2023; $i >= 2000; $i--) : ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <select id="sState" class="form-control select2 ps-4">
                            <option value="0">Select State</option>
                            <?php foreach ($states as $s) : ?>
                                <option value="<?= $s['code'] ?>"><?= strtoupper($s['code']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2 offset-2 form-group">
                        <button class="btn btn-primary" id="showMapBtn"><i class="material-icons opacity-10">place</i> View On Map</button>
                    </div>
                </div>
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 crop-table" id="dataTable">
                        <thead class="">
                            <tr>
                                <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Year</th>
                                <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">State</th>
                                <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Program</th>
                                <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Trial</th>
                                <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">LocID</th>
                                <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Location</th>
                                <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">VarietyID</th>
                                <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Brand</th>
                                <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Variety</th>
                                <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Variety Additional</th>
                                <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Herbicide Package</th>
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
                data: {
                    _token: () => {
                        return $('input[name="_token"]').val()
                    },
                    id: "<?= $crop['id'] ?>",
                    year: () => $('#sYear').val(),
                    state: () => $('#sState').val(),
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
                {
                    data: 'herbicide'
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

        $('#sYear').on('change', function() {
            dataTable.ajax.reload()
        })

        $('#sState').on('change', function() {
            dataTable.ajax.reload()
        })
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