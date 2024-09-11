<?= $this->extend('frontend/layouts/app') ?>
<?= $this->section('title') ?>
<?= $crop['name'] . " Documents" ?>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="row">
    <input type="hidden" name="_token" value="<?= csrf_hash() ?>">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3"><?= $crop['name'] . " Documents" ?></h6>
                </div>
            </div>
            <div class="card-body px-2 pb-2">
                <div class="row pb-3">
                    <div class="col-md-2 form-group">
                        <select id="sYear" class="form-control ps-4 select2">
                            <option value="0">Select Year</option>
                            <?php for ($i = 2023; $i >= 2000; $i--) : ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-2 form-group">
                        <select id="sState" class="form-control ps-4 select2">
                            <option value="">Select State</option>
                            <?php foreach ($states as $s) : ?>
                                <option value="<?= $s['code'] ?>"><?= $s['code'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Year</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">Title</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-center">State</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Download</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('custom-js') ?>
<script>
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
                url: '<?= base_url('get-downloads') ?>',
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
                    data: 'title'
                },
                {
                    data: 'state'
                },
                {
                    data: 'url'
                },

            ],
            drawCallback: function() {
                var api = this.api();
                $('input[name="_token"]').val(api.ajax.json().hash);
            },
            order: [
                [0, 'desc']
            ],
            responsive: true,
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
</script>
<?= $this->endSection() ?>