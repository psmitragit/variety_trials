<?= $this->extend('frontend/layouts/app') ?>
<?= $this->section('title') ?>
<?= "Select from Map" ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<input type="hidden" name="_token" value="<?= csrf_hash() ?>">
<div class="row p-3">
    <div class="col-12">
        <div class="form-control form-wrapper">
            <label for="sState" class="s_state_label"><?= $crop['name'] ?></label>
            <div class="mt-1 mb-3">
                <select id="sState" class="form-select mb-3 px-3 select2 filter-input" multiple data-placeholder="Select States(s)">
                    <?php foreach ($states as $s) : ?>
                        <option value="<?= $s['code'] ?>"><?= $s['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mt-1 mb-3">
                <select id="sLocation" class="form-select mb-3 px-3 select2 filter-input" multiple data-placeholder="Select Location(s)">
                    <?php foreach ($locations as $value) : ?>
                        <option value="<?= addslashes($value['code']) ?>"><?= addslashes($value['location']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mt-1 mb-3">
                <p>
                    <i class="me-2 fas fa-circle-question"></i><strong>Instruction:</strong> Click the map marker to select a location. To remove a selected location, simply click the map marker again. You can also choose a location from the dropdown above. After selecting and reviewing your location, click "Next" to proceed.
                </p>
            </div>

        </div>
    </div>
    <div class="col-12 mt-4 d-none" id="map-loader-wrapper">
        <div id="loading" class="map-loader d-flex justify-content-center align-items-center">
            <div class="loader"></div>
        </div>
    </div>
    <div class="col-12 mt-4 map-wrapper" id="map-wrapper">
        <div id="map"></div>
    </div>

    <div class="col-12 col-lg-10 mt-4" id="selected_locations">
        <ul id="selected_location_list" class="list-unstyled mb-0"></ul>
    </div>
    <div class="col-12 col-lg-2 mt-4 d-flex justify-content-md-end flex-wrap " style="gap: 1px;" id="next">
        <input type="hidden" name="selected_locations" id="selected_locations_input" value="">
        <button class="btn btn-primary" style="height: 40px; width: 90px;" id="nextBtn">Next</button>
        <button class="btn btn-secondary" id="clearMapBtn" style="height: 40px; width: 90px;">Clear</button>
    </div>
</div>
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="sitemapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content text-white">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="showDataCustomModalLabel">Validation Error</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p style="color:black;">You can select up to 10 locations only. Please remove others to search.</p>
            </div>
            <div class="my-3 d-flex justify-content-center">
                <button class="btn btn-success m-auto" style="max-width: 250px;" data-bs-dismiss="modal" aria-label="Close">Close</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('custom-js') ?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= env('GOOGLE_MAP_API_KEY') ?>&libraries=drawing,geometry&callback=initMap" async defer></script>
<script>
    let coordinates = [
        <?php foreach ($locations as $key => $value): ?> {
                lat: <?= $value['lat'] ?>,
                lng: <?= $value['long'] ?>,
                location: "<?= addslashes($value['location']) ?>",
                code: "<?= addslashes($value['code']) ?>"
            },
        <?php endforeach; ?>
    ];

    function initMap() {
        const myLatLng = coordinates.length ? {
            lat: coordinates[0].lat,
            lng: coordinates[0].lng
        } : {
            lat: 40.712776,
            lng: -74.005974
        };
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6,
            center: myLatLng
        });
        const drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: null,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: ['rectangle', 'polygon']
            },
            rectangleOptions: {
                fillColor: '#ffff00',
                fillOpacity: 0.2,
                strokeWeight: 2,
                clickable: false,
                editable: false,
                draggable: false
            },
            polygonOptions: {
                fillColor: '#00ff00',
                fillOpacity: 0.2,
                strokeWeight: 2,
                clickable: false,
                editable: false,
                draggable: false
            }
        });
        drawingManager.setMap(map);

        const infowindow = new google.maps.InfoWindow();

        coordinates.forEach((coord) => {
            const marker = new google.maps.Marker({
                position: {
                    lat: coord.lat,
                    lng: coord.lng
                },
                map,
                icon: '<?= base_url("frontend/img/map/red.png") ?>'
            });
            coord.marker = marker;
            coord.selected = false;

            marker.addListener("click", () => {
                toggleMarkerSelection(coord)

                infowindow.setContent(coord.location);
                infowindow.open(marker.getMap(), marker)
            });
        });

        google.maps.event.addListener(drawingManager, 'rectanglecomplete', function(rect) {
            applyShapeSelection(rect);
            rect.setMap(null);
        });

        google.maps.event.addListener(drawingManager, 'polygoncomplete', function(poly) {
            applyShapeSelection(poly);
            poly.setMap(null);
        });
    }

    function toggleMarkerSelection(coord) {
        coord.selected = !coord.selected;
        coord.marker.setIcon(coord.selected ? '<?= base_url("frontend/img/map/green.png") ?>' : '<?= base_url("frontend/img/map/red.png") ?>');
        coord.selected ? addToSelectedList(coord.location, coord.code) : removeFromSelectedList(coord.location, coord.code);
    }

    function applyShapeSelection(shape) {
        coordinates.forEach(coord => {
            let inside = false;

            if (shape instanceof google.maps.Rectangle) {
                inside = shape.getBounds().contains(coord.marker.getPosition());
            } else if (shape instanceof google.maps.Polygon) {
                inside = google.maps.geometry.poly.containsLocation(coord.marker.getPosition(), shape);
            }

            if (inside && !coord.selected) {
                toggleMarkerSelection(coord);
            }
        });
    }

    function addToSelectedList(locationName, locationCode) {
        const ul = document.getElementById('selected_location_list');
        const li = document.createElement('li');
        li.textContent = locationName;
        li.dataset.location = locationCode;
        ul.appendChild(li);
        let locationvalue = $('#sLocation').val();
        if ($.inArray(locationCode, locationvalue) < 0) {
            locationvalue.push(locationCode);
            $('#sLocation').val(locationvalue).trigger('change');
        }
        updateHiddenInput();
    }

    function removeFromSelectedList(locationName, locationCode) {
        const ul = document.getElementById('selected_location_list');
        const items = ul.querySelectorAll('li');
        items.forEach(li => {
            if (li.dataset.location === locationCode) {
                ul.removeChild(li);
            }
        });
        let locationvalue = $('#sLocation').val();
        let index = $.inArray(locationCode, locationvalue);
        if (index >= 0) {
            locationvalue.splice(index, 1);
            $('#sLocation').val(locationvalue).trigger('change');
        }
        updateHiddenInput();
    }

    function updateHiddenInput() {
        const ul = document.getElementById('selected_location_list');
        const selected = Array.from(ul.querySelectorAll('li')).map(li => li.getAttribute('data-location'));
        document.getElementById('selected_locations_input').value = selected.join('~');
    }

    function addUpdateSelectedList() {
        $('#selected_location_list').html('');
        $('#selected_locations_input').val('');
        $.each($('#sLocation').val(), function(e, val) {
            let location = $('#sLocation').find('option[value="' + val + '"]').text();
            addToSelectedList(location, val);
        });
        coordinates.forEach((coord) => {
            coord.selected = false;
            coord.marker.setIcon('<?= base_url("frontend/img/map/red.png") ?>');
        });
        coordinates.forEach((coord) => {
            if ($('#selected_locations_input').val().indexOf(coord.code) >= 0) {
                coord.selected = true;
                coord.marker.setIcon('<?= base_url("frontend/img/map/green.png") ?>');
            }
        });
    }

    window.addEventListener('load', function() {
        $('#sState').val('').trigger('change');
        $('#sState').on('change', function() {
            updateLocationSelect();
        })

        $('#sLocation').on('change', function() {
            addUpdateSelectedList();
        });

        function updateLocationSelect() {
            let states = $('#sState').val();
            $.ajax({
                url: "<?= base_url('get-location-data-by-state') ?>",
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
                    let html = '';
                    if (res.location) {
                        coordinates = [];
                        $.each(res.location, function(index, loc) {
                            coordinates.push({
                                lat: parseFloat(loc.lat),
                                lng: parseFloat(loc.long),
                                location: loc.location,
                                code: loc.code
                            });
                            html += `<option value="${loc.code}">${loc.location}</option>`;
                        });
                        initMap();
                        // $('#sLocation').html(html).trigger('change');
                    }
                },
                beforeSend: function() {
                    addLoader();
                },
                complete: function() {
                    removeLoader();
                },
            })
        }

        function addLoader() {
            $('#map-loader-wrapper').removeClass('d-none');
            $('#map-wrapper').addClass('d-none');
        }

        function removeLoader() {
            $('#map-loader-wrapper').addClass('d-none');
            $('#map-wrapper').removeClass('d-none');
        }

        $('#clearMapBtn').on('click', function() {
            coordinates.forEach(coord => {
                if (coord.selected) {
                    coord.selected = false;
                    coord.marker.setIcon('<?= base_url("frontend/img/map/red.png") ?>');
                }
            });
            $('#selected_location_list').html('');
            $('#selected_locations_input').val('');
        })

        $('#nextBtn').on('click', function() {
            let locations = $('#selected_locations_input').val().split("~");
            // let baseUrl = "<?= base_url($crop['slug'] . '/average') ?>";
            let baseUrl = "<?= $url ?>";

            const index = baseUrl.indexOf('location');
            if (index >= 0 && locations.length > 10) {
                $('#alertModal').modal('show');
                return false;
            }
            let params = new URLSearchParams();
            locations.forEach(loc => {
                if (loc.trim()) {
                    params.append('location[]', loc.trim());
                }
            });
            let finalUrl = baseUrl + '?' + params.toString();
            window.location.href = finalUrl;
        });
    });
</script>
<?= $this->endSection() ?>