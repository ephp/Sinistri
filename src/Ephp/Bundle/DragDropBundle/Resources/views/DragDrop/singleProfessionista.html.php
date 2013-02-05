<style type="text/css">
    #<?php echo $id ?>row, #<?php echo $field ?>_tmb {
        /*border: 1px dashed #999;*/
        display: block;
        width: 150px;
        height: 150px;
        position: relative;
        text-align: center;
        background: #FFFFCC;
    }
    #<?php echo $id ?>row {
        background: #FFFFCC;
    }
</style>
<?php
// Questo DIV è quello che compare quando è stata caricata l'immagine
?>
<div id="cont_<?php echo $field ?>_tmb" class="dropzonePro">
    <div id="<?php echo $field ?>_tmb" class="left ">
        <?php if ($value): ?>
            <div style="width:150px;height:150px;overflow:hidden;" id="barno">
                <img id="img_prof" src="<?php echo $tmb ?: '/images/azienda_placeholder.jpg' ?>" alt="anteprima" class="thumb rounded-3" />
            </div>
        <?php endif; ?>
        <div style="position: absolute; top: 0; left: 0; width: 100px; height: 100px; background: #f00; display: none;" class="in-edit"></div>
    </div>
</div>
<?php
// Questo DIV è la progress bar
?>
<div class="row fileuploadrow <?php echo $id ?>_row rounded-5 img_azienda shadow dropzonePro" id="<?php echo $id ?>row">
    <div class="fileupload-buttonbar">
        <input type="hidden" name="d" value="<?php echo $dir ?>">
        <input type="hidden" name="x" value="<?php echo $x ?>">
        <input type="hidden" name="y" value="<?php echo $y ?>">
        <input type="hidden" name="f" value="<?php echo $id ?>">
        <button type="reset" class="btn info cancel button-orange smaller" id="<?php echo $id ?>_bt_reset">Annulla</button>
    </div>
</div>

<div class="btn success fileinput-button" style="position: absolute; top: 75px; left: 50%; margin-left: -65px; margin-top: -12px; z-index: 30;" id="<?php echo $id ?>_bt_adde">
    <button class="button-orange smaller shadow-rdopanel" style="width: 130px;">Carica una foto</button>
    <div class="clearer twenty"></div>
    <div class="progressbar fileupload-progressbar" style="width: 130px;"><div id="progressbar" style="width:0%;"></div></div>
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
                var drag_drop_form_id = '';
                $('#<?php echo $field ?>_delete').<?php echo $value ? 'show' : 'hide' ?>();
                $('.<?php echo $id ?>_row').<?php echo $value ? 'hide' : 'show' ?>();
                $('#<?php echo $id ?>_bt_add').<?php echo $value ? 'hide' : 'show' ?>();
                $('#<?php echo $field ?>_tmb').<?php echo $value ? 'show' : 'hide' ?>();
                $('#<?php echo $id ?>').mouseover(function(){
                    drag_drop_form_id = '<?php echo $id ?>';
                });
                $('#<?php echo $id ?>').fileupload({
                    dataType: 'json',
                    url: '/upload.php',
                    autoUpload: true,
                    dropZone: $('.dropzonePro'),
<?php if ($mimetype): ?>acceptFileTypes: <?php echo $mimetype; ?>,<?php endif; ?>
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
                                fancyAlert('La foto ' +file.name+' è troppo piccolo (max: 2Kb)');
                                break;
                            case 'maxFileSize':
                                fancyAlert('La foto ' +file.name+' è troppo grande (max: 5Mb)');
                                break;
                            case 'acceptFileTypes':
                                fancyAlert('La foto ' +file.name+' non è del tipo accettato (JPG, PNG, GIF)');
                                break;
                            default:
                                fancyAlert('Errore sulla foto ' +file.name+': '+that._hasError(file));                        }
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
        $('#progressbar').css(
            'width', '0%'
        );
                            if(data.jqXHR.responseText) {
                                response = JSON.parse(data.jqXHR.responseText).last();
                            } else {
                                response = data.result.last();
                            }
                                
                                
                                
                            
                               // alert(parseInt(response.dimensions[0]));
                               // alert(parseInt(response.dimensions[0]));
                            
                                if(parseInt(response.dimensions[0])  < 250 || parseInt(response.dimensions[1]) < 250 ) {
                                    alert("Le dimensioni minime sono 250 pixel");
                                    
                                    delete_url = response.delete_url;

                                    $.ajax({
                                        url: delete_url,
                                        type: 'DELETE',
                                        success: function(msg) {
                                            //alert(msg);
                                        }
                                    });
    
                                
                              //  return false;
                                
                                
                                }else{
                                
                                if(response.form_id == drag_drop_form_id) {
 
                                    $('#<?php echo $field ?>').val(response.url);
                                    $('#<?php echo $field ?>_tmb').html('<img src="'+response.avatar_url+'" alt="preview" />');
                                    $('#<?php echo $field ?>_tmb').show();
                                    $('#<?php echo $field ?>_delete').show();
                                    $('#<?php echo $id ?>_bt_add').hide();
                                    delete_url = response.delete_url;
                                    $('.<?php echo $id ?>_row').hide();
                                    $('#<?php echo $id ?>_bt_reset').hide();
                                    $('#table_files_<?php echo $field ?>').html('');
            
            
            
                                    $('#target_pt_presentazione').attr('src',response.url);
                                    $('.jcrop-holder img').attr('src',response.url);
            
            
                                    //$('.jcrop-holder').css('z-index', 500).css('height', 500);
                                    //            $('.jcrop-holder img').attr('src',response.url).css('width', 500).css('height', 500);
                                    $('#contenitore_foto').remove();
            
            

                                    $(".article").prepend("<div id='contenitore_foto'><img src='"+response.thumbnail_url+"' id='target_pt_presentazione'/></div>");

                                    //$('#preview_pt_presentazione').attr('src',response.avatar_url);
                                    $('#immagine_pt_presentazione').val(response.url);
                                    $('#outer_pt_presentazione').show();
                                    
                                    init_jcrop();
                                    
                                }
            
                                

            
                            }
                        })
                         .bind('fileuploadstart', function (e) {
                            
                           $('.button-orange').css({
                             opacity: 0.5
                           }).attr('disabled', true);
                           
                            $('.fileinput-button input').attr('disabled', true);

                         })
                        .bind('fileuploadfail', function (e, data) {
                            $('#<?php echo $id ?>_bt_reset').hide();
                            $('#<?php echo $id ?>_bt_add').show();
                            $('#<?php echo $field ?>_tmb').hide();
                        })
                        .bind('fileuploaddrop', function (e, data) {
                            $('#<?php echo $id ?>row').css('background-color', '#ffffff'); 
                            $('#<?php echo $id ?>_bt_reset').show();
                            $('#<?php echo $id ?>_bt_add').hide();
                        })
                        .bind('fileuploaddragover', function (e) {
                            $('#<?php echo $id ?>row').css('background-color', '#ffeeaa');
                        });
                        $('#<?php echo $id ?>_bt_reset').hide();
    
                        delete_url = <?php echo $delete ? "'{$delete}'" : "false" ?>;
    
                        function cancella_img_<?php echo $field ?>() {
                            if(delete_url != false) {
                                $.ajax({
                                    url: delete_url,
                                    success: function(){
                                        $('#<?php echo $field ?>').val('');
                                        $('#<?php echo $field ?>_tmb').html('');
                                        $('#<?php echo $field ?>_tmb').hide();
                                        $('.<?php echo $id ?>_row').show();
                                        $('#<?php echo $id ?>_bt_add').show();
                                        $('#<?php echo $field ?>_delete').hide();
                                    }        
                                });
                            }
                        }
            </script>
