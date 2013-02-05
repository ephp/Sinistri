<style type="text/css">
    #<?php echo $id ?>row, #<?php echo $field ?>_tmb {
        border: 1px dashed #999;
        padding: 5px;
        display: block;
        width: 493px;
        min-height: <?php echo $y ?>px;
        position: relative;
        float: left;
        margin: 10px 0;
        text-align: center;
        overflow: auto;
        background: #FFFFCC;
    }
    #<?php echo $id ?>row {
        background: #FFFFCC;
    }
</style>
<?php
// Questo DIV è la progress bar
?>
<div class="btn success fileinput-button left" id="<?php echo $id ?>_bt_adde" style="width: 62px;">
    <div class="progressbar fileupload-progressbar left absolute" style="bottom: -23px; left: 5px; width: 100px;"><div id="progressbar-img<?php echo $more ?>" style="width:0%;"></div></div>
    <button id="add-img-button" class="<?php echo $css['load'] ?>" style="<?php echo $css['loadstyle'] ?>" type="button">Aggiungi</button>
    <input type="file" name="filesImg<?php echo $more ?>[]" class="hover" style="width: 62px;" id="filesImg<?php echo $more ?>" multiple>
</div>
<button id="cancel-img-button-<?php echo $field ?>" onclick="resetProgressbar<?php echo $more ?>()" rel="hide_<?php echo $field ?>" class="<?php echo $css['x'] ?> rem_img" style="<?php echo $css['xstyle'] ?>" type="button"><span class="padding-left-15 remove_blue"></span></button>
<div class="clearer"></div>
<?php if($more > 0): ?>
<a href="javascript:void(0);" class="add_img no-display padding-5 block text-white" rel="show_<?php echo $field ?>" id="add-new-<?php echo $field ?>">+ aggiungi immagine <small>( rimanenti <?php echo $more ?>)</small></a>
<?php endif ?>
    <?php /*<button id="add-img-button" class="box-lightblue padding-3 gradient-light left rounded-3 no-margin margin-left-10 hover" style="width: 30px; left: -4px;" type="button"><span class="padding-left-15 add_blue"></span></button> */ ?>

<div class="no-display-important">
    <div id="immagini_drag_drop" class="no-display">
        <div class="row fileuploadrow <?php echo $id ?>_row rounded-5 dropzoneDoc" id="<?php echo $id ?>row">
            <div class="fileupload-buttonbar">
                <button type="reset" class="btn info cancel button-orange smaller" id="<?php echo $id.'_'.$field ?>_bt_reset">Annulla</button>
            </div>
        </div>
        <div class="clearer"></div>
        <?php
        // Questo DIV contiene la tabella con lo stato di caricamento
        ?>
        <div class="row fileuploadrow <?php echo $id ?>_row">
            <div class="no-display">
                <table class="zebra-striped"><tbody class="files" id="table_files_<?php echo $field ?>"></tbody></table>
            </div>
        </div>

        <script id="template-upload-img" type="text/html">
            {% for (var i=0, files=o.files, l=files.length, file=files[0]; i<l; file=files[++i]) { %}
                <tr class="template-upload fade" style="display:none">
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
        <script id="template-download-img" type="text/html">
            {% for (var i=0, files=o.files, l=files.length, file=files[0]; i<l; file=files[++i]) { %}
                <tr class="template-download fade" style="display:none">
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
    </div>
</div>
<script type="text/javascript">  
    var n_file = 0;
    var drag_drop_form_id = '';
    $('#<?php echo $id.'_'.$field ?>_bt_reset').hide();
    $('#filesImg<?php echo $more ?>').fileupload({
        dataType: 'json',
        url: '/upload.php?campo=filesImg<?php echo $more ?>',
        autoUpload: true,
        uploadTemplateId: 'template-upload-img',
        downloadTemplateId: 'template-download-img',
        dropZone: $('#<?php echo $field ?>'),
        <?php if($mimetype): ?>acceptFileTypes: <?php echo $mimetype; ?>,<?php endif; ?>
        minFileSize: 1024,
        maxFileSize: 1048576,
        progressall: function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progressbar-img<?php echo $more ?>').css(
                'width',
                progress + '%'
            );
        },
        add: function(e, data) {
            var that = $(this).data('fileupload'),
                files = data.files;
            that._adjustMaxNumberOfFiles(-files.length);
            data.isAdjusted = true;
            data.files.valid = data.isValidated = that._validate(files);
            if(!data.isValidated){
                n = 0;
                filesname = "",
                $.each(files, function (index, file) {
                    if(that._hasError(file)) {
                        switch(that._hasError(file)) {
                            case 'minFileSize':
                                fancyAlert('Il documento ' +file.name+' è troppo piccolo (max: 1Kb)');
                                resetAddButton();
                                break;
                            case 'maxFileSize':
                                fancyAlert('Il documento ' +file.name+' è troppo grande (max: 1Mb)');
                                resetAddButton();
                                break;
                            case 'acceptFileTypes':
                                fancyAlert('Il documento ' +file.name+' non è del tipo accettato (JPG, PNG, GIF)');
                                resetAddButton();
                                break;
                            default:
                                fancyAlert('Errore sul documento ' +file.name+': '+that._hasError(file));     
                                resetAddButton();
                            }
                    }
                });
                $('#<?php echo $id.'_'.$field ?>_bt_reset').hide();
            }
            data.context = that._renderUpload(files)
                .appendTo(that._files)
                .data('data', data);
            // Force reflow:
            data.context.addClass('in');
            if ((that.options.autoUpload || data.autoUpload) &&
                    data.isValidated) {
                data.submit();
            }
        }
    });
    $('#filesImg<?php echo $more ?>')
    .bind('fileuploaddone', function (e, data) {
        $('#progressbar-img<?php echo $more ?>').css(
            'width', '0%'
        );
        if(data.jqXHR.responseText) {
            response = JSON.parse(data.jqXHR.responseText).last();
        } else {
            response = data.result.last();
        }
        if(response.error) {
            fancyAlert('Il documento ' +response.name+' è corrotto o presenta dei problemi.<br>Prova a caricare una versione alternativa.');
            return false;
        }
        $('#<?php echo $field ?>').val(response.name);
        $('#<?php echo $id.'_'.$field ?>_bt_reset').hide();
        delete_url = response.delete_url;
        $('#table_files_<?php echo $field ?>').html('');
        n_file++;
        $("#add-new-<?php echo $field ?>").show();
        resetAddButton();
    })
    .bind('fileuploadfail', function (e, data) {
        $('#<?php echo $id.'_'.$field ?>_bt_reset').hide();
//        $('#<?php echo $id ?>_bt_add').show();
    })
    .bind('fileuploaddrop', function (e, data) {
        $('#<?php echo $id ?>row').css('background-color', '#efefef');
        $('#<?php echo $id.'_'.$field ?>_bt_reset').show();
        $('#immagini_drag_drop').hide();
    })
    .bind('fileuploaddragover', function (e) {
        $('#<?php echo $id ?>row').css('background-color', '#ffeeaa');
        $('#immagini_drag_drop').show();
    });
    
    //delete_url = <?php echo $delete ? "'{$delete}'" : "false" ?>;
    
    function removeNuvolaImmagine(url, file, id) {
        arr.remove(file);
        $('#allegato_'+id).remove();
        $.ajax({
            url: url,
            type: 'DELETE',
            success: function(msg) {
                alert(msg);
            }
        });
    }
    
    var re_form_static = 'documento_[0-9]+_static';

    function formStaticFindNuvolaImmagine(testo) {
        temp_testo = testo;
        output = new Array();
        re = new RegExp(re_form_static);
        m = re.exec(temp_testo);
        j = 0;
        while(m != null) {
            var s = "";
            for (i = 0; i < m.length; i++) {
                s = s + m[i];
            }
            output[j++] = s;
            temp_testo = temp_testo.toString().substr(m.index + m.length);
            m = re.exec(temp_testo);
        }
        return output;
    }
    
    function resetProgressbar<?php echo $more ?>(){
        $('#progressbar-img<?php echo $more ?>').css(
            'width', '0%'
        );
    }

    function resetAddButton() {
//        $('#load_documenti').css({
//            opacity: 1
//        }).attr('disabled', false).html("Seleziona i documenti che vuoi caricare");
//        $('.fileinput-button input').attr('disabled', false);
    }
</script>