<?php if(false): ?>
<link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/FileUpload/bootstrap.min.css') ?>">
<link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/FileUpload/bootstrap-image-gallery.min.css') ?>">
<link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/FileUpload/jquery.fileupload-ui.css') ?>">
<script src="<?php echo $view['assets']->getUrl('js/jQuery/jquery.ui.widget.js') ?>"></script>
<script src="http://blueimp.github.com/JavaScript-Templates/tmpl.min.js"></script>
<script src="http://blueimp.github.com/JavaScript-Load-Image/load-image.min.js"></script>
<script src="//<?php echo $view['assets']->getUrl('js/jQuery/FileUpload/jquery.iframe-transport.js') ?>"></script>
<script src="//<?php echo $view['assets']->getUrl('js/jQuery/FileUpload/jquery.fileupload.js') ?>"></script>
<script src="//<?php echo $view['assets']->getUrl('js/jQuery/FileUpload/jquery.fileupload-ui.js') ?>"></script>
<script src="<?php echo $view['assets']->getUrl('js/jQuery/FileUpload/jquery.xdr-transport.js') ?>"></script>
<?php endif; ?>
<?php if ($test): ?>
    <h1 id="qunit-header">jQuery File Upload Plugin Test</h1>
    <h2 id="qunit-banner"></h2>
    <div id="qunit-testrunner-toolbar"></div>
    <h2 id="qunit-userAgent"></h2>
    <ol id="qunit-tests"></ol>
    <div id="qunit-fixture">
        <form id="fileupload" action="/upload.php" method="POST" enctype="multipart/form-data">
            <div class="fileupload-buttonbar">
                <div class="progressbar fileupload-progressbar"><div style="width:0%;"></div></div>
                <span class="fileinput-button">
                    <span>Add files...</span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="start">Start upload</button>
                <button type="reset" class="cancel">Cancel upload</button>
                <button type="button" class="delete">Delete selected</button>
                <input type="checkbox" class="toggle">
            </div>
            <table class="zebra-striped"><tbody class="files"></tbody></table>
        </form>
    </div>
    <script src="http://code.jquery.com/qunit/git/qunit.js"></script>
    <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/FileUpload/qunit.css') ?>">
    <script src="<?php echo $view['assets']->getUrl('js/jQuery/FileUpload/test/test.js') ?>"></script>
<?php else: ?>
    <style type="text/css">
        .page-header {
            background-color: #f5f5f5;
            padding: 80px 20px 10px;
            margin: 0 -20px 20px;
            border: 1px solid #DDD;
            -webkit-border-radius: 0 0 6px 6px;
            -moz-border-radius: 0 0 6px 6px;
            border-radius: 0 0 6px 6px;
        }
    </style>
    <form id="fileupload" action="" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="span16 fileupload-buttonbar">
                <div class="progressbar fileupload-progressbar"><div style="width:0%;"></div></div>
                <input type="hidden" name="d" value="categorie/rifiuti">
                <span class="btn success fileinput-button">
                    <span>Seleziona file</span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="btn primary start">Carica file</button>
                <button type="reset" class="btn info cancel">Annulla caricamento</button>
                <button type="button" class="btn danger delete">Cancella file selezionati</button>
                <input type="checkbox" class="toggle">
            </div>
        </div>
        <br>
        <div class="row">
            <div class="span16">
                <table class="zebra-striped"><tbody class="files"></tbody></table>
            </div>
        </div>
    </form>
<?php endif; ?>
<script id="template-upload" type="text/html">
{% for (var i=0, files=o.files, l=files.length, file=files[0]; i<l; file=files[++i]) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name">{%=file.name%}</td>
        <td class="size">{%=o.formatFileSize(file.size)%}</td>
        {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label important">Error</span> {%=fileUploadErrors[file.error] || file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td class="progress"><div class="progressbar"><div style="width:0%;"></div></div></td>
            <td class="start">{% if (!o.options.autoUpload) { %}<button class="btn primary">Carica</button>{% } %}</td>
        {% } else { %}
            <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}<button class="btn info">Annulla</button>{% } %}</td>
    </tr>
{% } %}
</script>
<script id="template-download" type="text/html">
{% for (var i=0, files=o.files, l=files.length, file=files[0]; i<l; file=files[++i]) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
            <td></td>
            <td class="name">{%=file.name%}</td>
            <td class="size">{%=o.formatFileSize(file.size)%}</td>
            <td class="error" colspan="2"><span class="label important">Errore</span> {%=fileUploadErrors[file.error] || file.error%}</td>
        {% } else { %}
            <td class="preview">{% if (file.thumbnail_url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" rel="gallery"><img src="{%=file.thumbnail_url%}"></a>
            {% } %}</td>
            <td class="name">
                <a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}">{%=file.name%}</a>
            </td>
            <td class="size">{%=o.formatFileSize(file.size)%}</td>
            <td colspan="2"></td>
        {% } %}
        <td class="delete">
            <button class="btn danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">Cancella</button>
            <input type="checkbox" name="delete" value="1">
        </td>
    </tr>
{% } %}
</script>
<?php if (!$test): ?>
    <?php if (false): ?>
        <script src="<?php echo $view['assets']->getUrl('js/jQuery/FileUpload/application.js') ?>"></script>
    <?php endif; ?>
    <script src="<?php echo $view['assets']->getUrl('js/jQuery/FileUpload/jquery.xdr-transport.js') ?>"></script>
<?php endif; ?>
<script id="template-download" type="text/javascript">
    $('#fileupload').fileupload({
        dataType: 'json',
        url: '/upload.php',
        autoUpload: true,
        minFileSize: 2048
    });
    $('#fileupload')
    .bind('fileuploaddone', function (e, data) {
        response = JSON.parse(data.jqXHR.responseText).last();
        $('#'+imageFieldId).val(response.url);
        $('#'+imageFieldThumbnail).html('<img src="'+response.thumbnail_url+'" alt="preview" />');
    })
    .bind('fileuploaddrop', function (e, data) {$('#fileupload').css('background-color', '#ffffff')})
    .bind('fileuploaddragover', function (e) {$('#fileupload').css('background-color', '#ffeeaa')});
</script>
