<?= $this->extend('backend/layouts/app') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title pt-3 pb-3"><?= !empty($template) ? "Edit" : "Create" ?> Email Template</h4>
            </div>
            <div class="card-body">
                <div class="text-danger"><?= validation_list_errors(); ?></div>
                <?= form_open('', ['method' => 'post', 'class' => 'need-validation']) ?>
                <div class="row">
                    <input type="hidden" name="id" value="<?= $template['id'] ?? 0 ?>">
                    <div class="form-group col-md-6 pb-2">
                        <label for="tname">Template</label>
                        <input type="text" class="form-control" id="tname" name="name" value="<?= old('name') ?? $template['name'] ?? "" ?>" placeholder="Template name" required>
                    </div>
                    <div class="form-group col-md-6 pb-2">
                        <label for="shortcode">Short Code</label>
                        <input type="text" class="form-control" id="shortcode" name="code" value="<?= old('code') ?? $template['code'] ?? "" ?>" placeholder="Short Code" required>
                    </div>
                    <div class="form-group col-md-6 pb-2">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" value="<?= old('subject') ?? $template['subject'] ?? "" ?>" placeholder="Subject" required>
                    </div>
                    <div class="form-group col-md-6 pb-2">
                        <label for="placeholder">Placeholder</label>
                        <input type="text" class="form-control" id="placeholder" name="placeholder" value='<?= old('placeholder') ?? $template['placeholder'] ?? "" ?>' placeholder='["{a}","{b}"]'>
                    </div>
                    <div class="form-group col-12 pb-2">
                        <label for="content">Content</label>
                        <textarea id="content" name="content" class="form-control"><?= $template['content'] ?? "" ?></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <a href="<?= base_url('admin/email-template') ?>" class="btn btn-light">Cancel</a>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('custom-js') ?>
<script src="<?= base_url('backend') ?>/vendors/tinymce/tinymce.min.js"></script>
<script>
    $(function() {
        tinymce.init({
            selector: '#content',
            height: 300,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
                'bold italic forecolor backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | fullscreen code',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });
    })
</script>
<?= $this->endSection() ?>