<?= $this->extend('frontend/layouts/app') ?>
<?= $this->section('title') ?>
Documents
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-3">
        <div class="my-3 px-auto py-2">Document Library</div>
        <div class="daterange-container dussc">
            <div class="ms-2 datepicker-input-wrapper">
                <input type="text" name="" id="yearRange" value="<?= date('Y', strtotime('-2 years')) . '-' . date('Y') ?>" readonly>
                <button class="btn btn-sm btn-success mb-0" id="confirmYear" type="button">GO</button>
            </div>
            <input type="text" name="from_year" id="from" class="d-none" value="<?= date('Y', strtotime('-2 years')) ?>" readonly>
            <input type="text" name="to_year" id="to" class="d-none" value="<?= date('Y') ?>" readonly>
            <div id="datepicker-container" class="d-flex">
                <div id="datepicker1"></div>
                <div id="datepicker2"></div>
            </div>
        </div>
        <div class="state-contaier">
            <select class="form-control select2" id="sState">
                <option value="">All State</option>
                <?php foreach ($states as $l) : ?>
                    <option value="<?= $l['code'] ?>"><?= $l['code'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <ul class="crop-container">
            <?php foreach (get_crops() as $l) : ?>
                <li data-id="<?= $l['id'] ?>" role="button" class="crop-li"><?= $l['name'] ?></li>
            <?php endforeach; ?>
        </ul>
        <div id="document-container">
        </div>
    </div>
    <div class="col-9" id="document-preview">
        <object class="objs" data="" type="">
            <embed src="" type="">
        </object>
    </div>
    <!-- <div class="col-10"></div> -->
</div>
<?= $this->endSection() ?>

<?= $this->section('custom-js') ?>
<script>
    $(function() {
        $('#from').datepicker({
            format: "yyyy",
            autoclose: false,
            minViewMode: "years",
            container: 'div#datepicker1',
            title: "From Year",
            setStartDate: "<?= date('Y', strtotime('-2 years')) ?>"
        }).on('changeDate', function(selected) {
            startDate = $("#from").val();
            $('#to').datepicker('setStartDate', startDate);
        }).on('hide', function() {
            if ($('#datepicker2 .datepicker').length) {
                $(this).datepicker('show')
            }
        })
        $('#to').datepicker({
            format: "yyyy",
            autoclose: false,
            minViewMode: "years",
            container: 'div#datepicker2',
            title: "To Year",
            setStartDate: "<?= date('Y') ?>"
        }).on('hide', function() {

        });


        $('#yearRange').on('click', function() {
            $('#to').datepicker('show');
            $('#from').datepicker('show');
            $('.datepicker').click(function(e) {
                $('#to').datepicker('show');
                $('#from').datepicker('show');
            })
        })

        $('#from').on('change', function() {
            let start = $(this).val()
            let end = $('#to').val()
            end = end == "" ? start : end
            end = Number(end) < Number(start) ? start : end
            $('#yearRange').val(`${start} - ${end}`)
        })
        $('#to').on('change', function() {
            let start = $('#from').val()
            let end = $(this).val()
            start = start == "" ? end : start
            $('#yearRange').val(`${start} - ${end}`)
        })


        $('.crop-li').on('click', function() {
            $('.crop-li').removeClass('text-primary active')
            $(this).addClass('text-primary active')
            generateDocumnts()
        })

        $('#confirmYear').on('click', generateDocumnts)
        $('#sState').on('change', generateDocumnts)

        function generateDocumnts() {
            $('#document-container').empty()
            setPdfUrl('')
            $.ajax({
                url: "<?= base_url('get-documents') ?>",
                type: "post",
                dataType: "json",
                data: {
                    _token: "<?= csrf_hash() ?>",
                    start: $('#from').val(),
                    end: $('#to').val(),
                    crop: $('.crop-li.active').data('id'),
                    state: $('#sState').val(),
                },
                success: function(res) {
                    if (res.status) {
                        res.documents.forEach(ele => {
                            $('#document-container').append(`<div class="fulldivs"><div data-url="${ele.url}" class="pdf-link" role="button"><div class="opentext"><div><i class="fa-regular fa-file-pdf"></i></div><div class="title-doc"> ${ele.title}<div class="sm-text-sixes">${ele.year} | ${ele.crop}</div></div></div></div><div class="ascx"><span class="copy" data-url="${ele.url}" role="button"><i class="fa-solid fa-link"></i></span> <a class="downld-link" href="${ele.url}" download><i class="fa-solid fa-download"></i></a></div></div>`);
                        });
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            })
        }

        $(document).on('click', '.copy', function(e) {
            let url = window.location.origin + '/' + $(this).data('url');
            $('body').append(`<input value="${url}" id="pdfLinkCopy">`);
            $('#pdfLinkCopy').select();
            if (document.execCommand('copy')) {
                toastr.success('Documnt url copied');
            }
            $('#pdfLinkCopy').remove()
        });

        $(document).on('click', '.pdf-link', function() {
            $('.pdf-link').removeClass('text-primary')
            $(this).addClass('text-primary')
            let url = $(this).data('url')
            setPdfUrl(url)
        })

        function setPdfUrl(url) {
            $('#document-preview embed').attr('src', '/' + url)
            $('#document-preview object').attr('data', '/' + url)
            url == "" ? $('#document-preview').hide() : $('#document-preview').show()
        }

    });
</script>
<?= $this->endSection() ?>