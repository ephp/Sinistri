<style type="text/css">
    #<?php echo $id ?>row, #<?php echo $field ?>_tmb {
        border: 1px dashed #999;
        padding: 5px;
        display: block;
        width: 945px;
        min-height: <?php echo $y ?>px;
        position: relative;
        float: left;
        margin: 0 0 10px;
        text-align: center;
        overflow: auto;
        background: #FFFFCC;
    }
    #<?php echo $id ?>row {
        background: #FFFFCC;
    }
</style>
<?php
// Questo DIV è quello che compare quando è stata caricata l'immagine
?>
<div id="cont_<?php echo $field ?>_tmb" class="dropzoneRef">
    <div id="<?php echo $field ?>_tmb" class="rounded-5">
    </div>
</div>
<?php
// Questo DIV è la progress bar
?>

<div class="row fileuploadrow <?php echo $id ?>_row rounded-5 dropzoneRef" id="<?php echo $id ?>row">
    <div class="fileupload-buttonbar">
        <input type="hidden" name="d" value="<?php echo $dir ?>">
        <input type="hidden" name="x" value="<?php echo $x ?>">
        <input type="hidden" name="y" value="<?php echo $y ?>">
        <input type="hidden" name="f" value="<?php echo $id ?>">
        <button type="reset" class="btn info cancel button-orange smaller" id="<?php echo $id ?>_bt_reset">Annulla</button>
    </div>
</div>
<div class="clearer"></div>
<div class="btn success fileinput-button left" id="<?php echo $id ?>_bt_adde">
    <button class="button-orange smaller left">Seleziona le immagini da caricare</button>
    <div class="progressbar fileupload-progressbar absolute" style="width: 140px; top: 0px; right: -150px;"><div id="progressbar" style="width:0%;"></div></div>
    <input type="file" name="files[]" multiple>
</div>
<?php
// Questo DIV contiene la tabella con lo stato di caricamento
?>
<div class="row fileuploadrow <?php echo $id ?>_row">
    <div class="no-display">
        <table class="zebra-striped"><tbody class="files" id="table_files_<?php echo $field ?>"></tbody></table>
    </div>
</div>

<script id="template-upload" type="text/html">
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
<script id="template-download" type="text/html">
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
<script type="text/javascript">  
    var n_file = 0;
    var drag_drop_form_id = '';
    $('#<?php echo $field ?>_delete').<?php echo $n > 0 ? 'show' : 'hide' ?>();
    $('.<?php echo $id ?>_row').<?php echo $n > 0 ? 'hide' : 'show' ?>();
//    $('#<?php echo $id ?>_bt_add').<?php echo $n > 0 ? 'hide' : 'show' ?>();
    $('#<?php echo $field ?>_tmb').<?php echo $n > 0 ? 'show' : 'hide' ?>();
    $('#<?php echo $id ?>').mouseover(function(){
        drag_drop_form_id = '<?php echo $id ?>';
    });
    $('#<?php echo $id ?>').fileupload({
        dataType: 'json',
        url: '/upload.php',
        autoUpload: true,
        dropZone: $('.dropzoneRef'),
        <?php if($mimetype): ?>acceptFileTypes: <?php echo $mimetype; ?>,<?php endif; ?>
        minFileSize: 1024,
        maxFileSize: 3145728,
        progressall: function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progressbar').css(
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
                                fancyAlert('Il file ' +file.name+' è troppo piccolo (max: 1Kb)');
                                break;
                            case 'maxFileSize':
                                fancyAlert('Il file ' +file.name+' è troppo grande (max: 2Mb)');
                                break;
                            case 'acceptFileTypes':
                                fancyAlert('Il file ' +file.name+' non è del tipo accettato (JPG, PNG, GIF)');
                                break;
                            default:
                                fancyAlert('Errore sul file ' +file.name+': '+that._hasError(file));
                                
                        }
                    }
                });
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
    $('#<?php echo $id ?>')
    .bind('fileuploaddone', function (e, data) {
        $('#progressbar').css(
            'width', '0%'
        );
        if(data.jqXHR.responseText) {
            response = JSON.parse(data.jqXHR.responseText).last();
        } else {
            response = data.result.last();
        }
        if(response.form_id == drag_drop_form_id) {
            $('#<?php echo $field ?>').val($('#<?php echo $field ?>').val()+','+response.url);
            $('#<?php echo $field ?>_tmb').append(addFoto(response.thumbnail_url, response.url, '', ''));
            $('#<?php echo $field ?>_tmb').show();
            $('#<?php echo $field ?>_delete').show();
//            $('#<?php echo $id ?>_bt_add').hide();
            delete_url = response.delete_url;
            $('.<?php echo $id ?>_row').hide();
            $('#<?php echo $id ?>_bt_reset').hide();
            $('#table_files_<?php echo $field ?>').html('');
            chiudiBaloonDidascaliaRef(n_file++);
        }
    })
    .bind('fileuploadfail', function (e, data) {
        $('#<?php echo $id ?>_bt_reset').hide();
//        $('#<?php echo $id ?>_bt_add').show();
        $('#<?php echo $field ?>_tmb').hide();
    })
    .bind('fileuploaddrop', function (e, data) {
        $('#<?php echo $id ?>row').css('background-color', '#efefef'); 
        $('#<?php echo $id ?>_bt_reset').show();
//        $('#<?php echo $id ?>_bt_add').hide();
    })
    .bind('fileuploaddragover', function (e) {
        $('#<?php echo $id ?>row').css('background-color', '#ffeeaa');
    });
    $('#<?php echo $id ?>_bt_reset').hide();
    
    //delete_url = <?php echo $delete ? "'{$delete}'" : "false" ?>;
    
    function cancella_img_<?php echo $field ?>(field) {
        img = field.closest('div').children('img').attr('src').replace(/\/thumbnails\//g,'/files/');
        id = field.closest('div').children('img').attr('id').from(5);
        foto_id = $('#foto_id_'+id).val();
        field.closest('div').remove();
        $('#<?php echo $field ?>').val($('#<?php echo $field ?>').val().remove(','+img));
        if($('#<?php echo $field ?>').val().trim() == '') {
            $('.<?php echo $id ?>_row').show();
            $('#<?php echo $id ?>_bt_add').show();
            $('#<?php echo $field ?>_tmb').hide();
        }
        chiudiBaloonDidascaliaRef('X_'+foto_id);
    }
    function didascalia_<?php echo $field ?>(n_file) {
        $('#foto_id').val('foto_'+n_file);
        $('#didascalia_id').val('didascalia_'+n_file);
        $('#didascalia').val($('#'+$('#didascalia_id').val()).val());
        apriBaloonDidascaliaRef($('#dida'+n_file));
    }
    
    function addFoto(thumbnail, file, didascalia, foto_id) {
        return  '<div class="box-light shadow left" style="padding: 5px; margin: 0 5px 5px 0;">'+
                    '<img src="'+thumbnail+'" alt="preview" height="100px;" id="foto_'+n_file+'" alt="'+didascalia+'" title="'+didascalia+'" />'+
                    '<input type="hidden" id="didascalia_'+n_file+'" name="didascalia['+n_file+']" value="'+didascalia+'">'+
                    '<input type="hidden" id="url_'+n_file+'" name="url_['+n_file+']" value="'+file+'">'+
                    '<input type="hidden" id="foto_id_'+n_file+'" name="foto_id_['+n_file+']" value="'+foto_id+'">'+
                    '<ul class="editing-tools button-light only-icon" style="top: 0px; left: 0px; display: block;">'+
                        '<li class="small first shadow">'+
                            '<a href="javascript:void(0);" onclick="didascalia_<?php echo $field; ?>('+n_file+');" id="dida'+n_file+'" class="<?php echo $field; ?>_edit" dx="-10" dy="22">'+
                                '<span class="edit">Modifica</span>'+
                            '</a>'+
                        '</li>'+
                        '<li class="small last shadow">'+
                            '<a href="javascript:void(0);" onclick="cancella_img_<?php echo $field; ?>($(this));" class="<?php echo $field; ?>_delete">'+
                                '<span class="annull">Elimina</span>'+
                            '</a>'+
                        '</li>'+
                    '</ul>'+
                '</div>';
    }
    
    var old_files = JSON.parse('<?php echo $tmb ?>');
    var old_fotos = JSON.parse('<?php echo $values ?>');
    var old_didas = JSON.parse('<?php echo $didascalie ?>');
    var old_id = JSON.parse('<?php echo $foto_id ?>');
    for(i = 0; i < old_files.length; i++) {
        $('#<?php echo $field ?>_tmb').append(addFoto(old_files[i], old_fotos[i], old_didas[i], old_id[i]));
        n_file++;
    }
    if(n_file > 0) {
        n_file++;
    }
</script>