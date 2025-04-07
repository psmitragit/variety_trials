<?= $this->extend('frontend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="d-sm-flex align-items-center justify-content-between border-bottom">
            </div>
            <div class="row my-5 text-white">
                <?php foreach (get_crops() as $l): ?>
                    <div class="col-lg-4 col-6 mb-3">
                        <div class="small-box bg-secondary rounded-3">
                            <div class="inner px-4 py-3">
                                <h3>
                                    <?= $l['name'] ?>
                                </h3>
                            </div>
                            <div class="icon text-center">
                                <!-- <svg xmlns="http://www.w3.org/2000/svg" width="75" height="75" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);">
                                    <path d="m22 3.41-.12-1.26-1.2.4a13.84 13.84 0 0 1-6.41.64 11.87 11.87 0 0 0-6.68.9A7.23 7.23 0 0 0 3.3 9.5a9 9 0 0 0 .39 4.58 16.6 16.6 0 0 1 1.18-2.2 9.85 9.85 0 0 1 4.07-3.43 11.16 11.16 0 0 1 5.06-1A12.08 12.08 0 0 0 9.34 9.2a9.48 9.48 0 0 0-1.86 1.53 11.38 11.38 0 0 0-1.39 1.91 16.39 16.39 0 0 0-1.57 4.54A26.42 26.42 0 0 0 4 22h2a30.69 30.69 0 0 1 .59-4.32 9.25 9.25 0 0 0 4.52 1.11 11 11 0 0 0 4.28-.87C23 14.67 22 3.86 22 3.41z"></path>
                                </svg> -->
                            </div>
                            <div class="text-center">
                                <a class="doc-dashboard" href="<?= base_url($l['slug'] . '/trials') ?>">Trials</a><a
                                    href="<?= base_url($l['slug'] . '/documents') ?>" class="title-dashboard">Documents</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="col-lg-4 col-6 mb-3">
                    <div class="small-box bg-customs-color rounded-3">
                        <div class="inner px-4 py-3">
                            <h3>Documents</h3>
                        </div>
                        <div class="icon ">
                            <!-- <svg xmlns="http://www.w3.org/2000/svg" width="75" height="75" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);">
                                    <path d="m22 3.41-.12-1.26-1.2.4a13.84 13.84 0 0 1-6.41.64 11.87 11.87 0 0 0-6.68.9A7.23 7.23 0 0 0 3.3 9.5a9 9 0 0 0 .39 4.58 16.6 16.6 0 0 1 1.18-2.2 9.85 9.85 0 0 1 4.07-3.43 11.16 11.16 0 0 1 5.06-1A12.08 12.08 0 0 0 9.34 9.2a9.48 9.48 0 0 0-1.86 1.53 11.38 11.38 0 0 0-1.39 1.91 16.39 16.39 0 0 0-1.57 4.54A26.42 26.42 0 0 0 4 22h2a30.69 30.69 0 0 1 .59-4.32 9.25 9.25 0 0 0 4.52 1.11 11 11 0 0 0 4.28-.87C23 14.67 22 3.86 22 3.41z"></path>
                                </svg> -->
                        </div>
                        <div class="text-center">
                            <a href="<?= base_url('documents') ?>" class="title-dashboard">All Crops</a>
                        </div>
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

<script src="https://maps.googleapis.com/maps/api/js?key=<?= env('GOOGLE_MAP_API_KEY') ?>&libraries=maps,marker&callback=initialMap" async defer></script>
<script>
    // window.addEventListener('load', function() {
        console.log('Test');


        //Open Map
        window.helloWorld = () => {
            initMap();
            $('#showMap').modal('show');
        }

        let coordinates = [{
                lat: 40.735657, // Newark, NJ
                lng: -74.172363
            },
            {
                lat: 40.717754, // Jersey City, NJ
                lng: -74.043143
            },
            {
                lat: 39.952583, // Philadelphia, PA
                lng: -75.165222
            },
            {
                lat: 41.765804, // Hartford, CT
                lng: -72.673372
            },
            {
                lat: 42.360081, // Boston, MA
                lng: -71.058884
            },
            {
                lat: 39.290386, // Baltimore, MD
                lng: -76.612190
            },
            {
                lat: 38.907192, // Washington, D.C.
                lng: -77.036873
            }
        ];

        let markerName = [
            "Newark, NJ",
            "Jersey City, NJ",
            "Philadelphia, PA",
            "Hartford, CT",
            "Boston, MA",
            "Baltimore, MD",
            "Washington, D.C."
        ];



        // Initialize the map
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


        //On page loadd map init
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

    // });
</script>
<?= $this->endSection() ?>