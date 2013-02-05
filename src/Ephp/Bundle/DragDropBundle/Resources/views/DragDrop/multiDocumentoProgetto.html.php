<style type="text/css">
    #<?php echo $id ?>row, #<?php echo $field ?>_tmb {
        /*border: 1px dashed #999;*/
        /*padding: 5px;*/
        display: block;
        width: 30px;
        height: 25px;
        position: relative;
        float: left;
        margin: 0;
        text-align: center;
        overflow: auto;
        background: transparent url(/images/icone/drop_folder.png) no-repeat center center;
    }
    #<?php echo $id ?>row {
        background: transparent url(/images/icone/drop_folder.png) no-repeat center center;
    }
</style>
<?php
// Questo DIV è la progress bar
?>

<div class="row fileuploadrow <?php echo $id ?>_row rounded-5 dropzoneDoc margin-right-10 left" id="<?php echo $id ?>row">
    <div class="fileupload-buttonbar">
        <input type="hidden" name="d" value="<?php echo $dir ?>">
        <input type="hidden" name="x" value="<?php echo $x ?>">
        <input type="hidden" name="y" value="<?php echo $y ?>">
        <input type="hidden" name="f" value="<?php echo $id ?>">
        <button type="reset" class="btn info cancel button-orange smaller" id="<?php echo $id ?>_bt_reset">Annulla</button>
    </div>
</div>
<div class="btn success fileinput-button left relative" id="<?php echo $id ?>_bt_adde">
    <button class="button-orange smaller no-margin left" id="btn_form_progetto" type="button">Seleziona i documenti che vuoi caricare</button>
    <div class="progressbar fileupload-progressbar absolute" style="width: 140px; top: 0px; right: -150px;"><div id="progressbar_allegati" style="width:0%;"></div></div>
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
    $('#<?php echo $id ?>_bt_reset').hide();
    $('#<?php echo $id ?>').mouseover(function(){
        drag_drop_form_id = '<?php echo $id ?>';
    });
    $('#<?php echo $id ?>').fileupload({
        dataType: 'json',
        url: '/upload.php',
        autoUpload: true,
        dropZone: $('.dropzoneDoc'),
        <?php if($mimetype): ?>acceptFileTypes: <?php echo $mimetype; ?>,<?php endif; ?>
        minFileSize: 1024,
        maxFileSize: 7340032,
        progressall: function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progressbar_allegati').css(
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
                                fancyAlert('Il documento ' +file.name+' è troppo piccolo (max: 2Kb)');
                                resetAddButton();
                                break;
                            case 'maxFileSize':
                                fancyAlert('Il documento ' +file.name+' è troppo grande (max: 5Mb)');
                                resetAddButton();
                                break;
                            case 'acceptFileTypes':
                                fancyAlert('Il documento ' +file.name+' non è del tipo accettato (Acrobat Pdf, Office Word, OpenOffice Writer)');
                                resetAddButton();
                                break;
                            default:
                                fancyAlert('Errore sul documento ' +file.name+': '+that._hasError(file));     
                                resetAddButton();
                            }
                    }
                });
                $('#<?php echo $id ?>_bt_reset').hide();
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
        $('#progressbar_allegati').css(
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
        if(response.form_id == drag_drop_form_id) {
            $('#<?php echo $field ?>').val($('#<?php echo $field ?>').val()+','+response.url);
            addDocumento(response);
            $('#<?php echo $id ?>_bt_reset').hide();
            delete_url = response.delete_url;
            $('#table_files_<?php echo $field ?>').html('');
            n_file++;
        }
    })
    .bind('fileuploadstart', function (e) {
        $('#load_documenti').css({
            opacity: 1
        }).attr('disabled', false).html("Attendere");
        $('.fileinput-button input').attr('disabled', false);
    })
    .bind('fileuploadfail', function (e, data) {
        $('#<?php echo $id ?>_bt_reset').hide();
        resetAddButton();
//        $('#<?php echo $id ?>_bt_add').show();
    })
    .bind('fileuploaddrop', function (e, data) {
        $('#<?php echo $id ?>row').css('background-color', '#efefef');
        $('#<?php echo $id ?>_bt_reset').show();
        $('#allegati_drag_drop').hide();
    })
    .bind('fileuploaddragover', function (e) {
        $('#<?php echo $id ?>row').css('background-color', '#ffeeaa');
        $('#allegati_drag_drop').show();
    });
    
    //delete_url = <?php echo $delete ? "'{$delete}'" : "false" ?>;
    function addDocumento(data) {
        //alert(serialize(data));
        ul = $("#file_caricati").addClass('default_ul');
        input = $("<input type ='text' name='documento["+n_file+"][name]' />").val(data.name).addClass('small');
        hidden = $("<input type ='hidden' name='documento["+n_file+"][file]' />").val(data.url);
        hidden2 = $("<input type ='hidden' name='documento["+n_file+"][size]' />").val(data.size);
        hidden3 = $("<input type ='hidden' name='documento["+n_file+"][type]' />").val(data.type);
        hidden4 = $("<input type ='hidden' name='documento["+n_file+"][delete_url]' />").val(data.delete_url);
        
        if((data.type).startsWith('image')) {            
            radio = $("<p><input type ='radio' name='documento[copertina]'  value=\""+data.url+"\"/>Copertina Progetto</p>");
        }else{
            radio = $("<p><input type ='radio' name='documento[copertina]'  value=\""+data.url+"\"/>Copertina Progetto</p>").hide();
        }
        cancellazione = "<div class=\"close-box-main hover shadow rounded-10\" id=\""+n_file+"\" onclick='remove(\""+data.delete_url+"\",\""+data.name+"\",\""+n_file+"\",\""+data.url+"\")'>Elimina</div>";
        li = $("<li id=\"allegatoProgetto_"+n_file+"\" class=\"box-light padding-10 margin-bottom-10 rounded-5 gradient-lightmedium relative\"></li>").append(cancellazione).append('<h4 class="margin-bottom-3">'+data.name+'</h4>').append(input).append('<span class="note">Modifica il nome(facoltativo)</small>').append(radio).append(hidden).append(hidden2).append(hidden3).append(hidden4);
        
        //alert(li);
        
        ul.append(li);
	resetAddButton();
        //salvaProgetto(data);
    }
    var re_form_static = 'documento_[0-9]+_static';

    function formStaticFind(testo) {
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

    function resetAddButton() {
        $('#load_documenti').css({
            opacity: 1
        }).attr('disabled', false).html("Seleziona i documenti che vuoi caricare");
        $('.fileinput-button input').attr('disabled', false);
    }
</script>