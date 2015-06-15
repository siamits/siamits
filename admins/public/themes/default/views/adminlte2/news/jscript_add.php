<?php
Theme::asset()->add('dataTables-bootstrap', 'public/themes/adminlte2/plugins/iCheck/all.css');
Theme::asset()->add('uploadify-css', 'public/themes/adminlte2/plugins/uploadify/uploadify.css');
Theme::asset()->add('ace-css', 'public/themes/adminlte2/plugins/css/ace.thumbnails.css');
Theme::asset()->add('tagsinput-css', 'public/themes/adminlte2/plugins/jQuery-Tags-Input-master/src/jquery.tagsinput.css');
Theme::asset()->add('tagsinput-css2', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/start/jquery-ui.css');

Theme::asset()->container('footer')->add('jquery-ui.min', 'http://code.jquery.com/ui/1.11.2/jquery-ui.min.js');
Theme::asset()->container('footer')->add('bootbox', 'public/themes/adminlte2/plugins/bootbox/bootbox.min.js');
Theme::asset()->container('footer')->add('validate', 'public/themes/adminlte2/plugins/jQuery/jquery.validate.min.js');
Theme::asset()->container('footer')->add('uploadify', 'public/themes/adminlte2/plugins/uploadify/jquery.uploadify.min.js');
Theme::asset()->container('footer')->add('ckeditor', 'public/themes/adminlte2/plugins/ckeditor2/ckeditor.js');
Theme::asset()->container('footer')->add('zclip', 'public/themes/adminlte2/plugins/zclip/jquery.zclip.js');
Theme::asset()->container('footer')->add('tagsinput', 'public/themes/adminlte2/plugins/jQuery-Tags-Input-master/src/jquery.tagsinput.js');
?>

<script type="text/javascript">
    var num_images = 0;

    $(function () {

        var url_to_autocomplete_api = '<?php echo URL::to("news/tags");?>';
    
        $('#tags').tagsInput({
            'autocomplete_url': url_to_autocomplete_api,
            'height':'100px',
            'width':'100%',
            'interactive':true,
            'defaultText':'add a tag',
            'delimiter': [','],
            'removeWithBackspace' : true,
            'minChars' : 0,
            'maxChars' : 0, //if not provided there is no limit
            'placeholderColor' : '#666666'
        });

        //bootstrap WYSIHTML5 - text editor
        CKEDITOR.config.extraPlugins = 'toolbar';
        CKEDITOR.replace( 'description', {
            // Reset toolbar settings, so full toolbar will be generated automatically.
            toolbar: null,
            toolbarGroups: null,
            removeButtons: null,
            height: 400
        } );

        $('.copy-link-wrap').zclip({
            path: '<?php echo URL::to("public");?>/themes/adminlte2/plugins/zclip/ZeroClipboard.swf',
            copy:function(){return $(this).attr('data');},
            afterCopy:function(){
                console.log('Copy Success');
                // bootbox.dialog({
                //   message: "Data in clipboard! '"+$(this).attr('data')+"'",
                //   buttons: {
                //     success: {
                //       label: "ตกลง",
                //       className: "btn-small btn-primary"
                //     }
                //   }
                // });
            }
        });

        $("#frm_add_news").validate({
            ignore: [],
            errorElement: 'span',
            errorClass: 'text-red',
            focusInvalid: true,
            rules: {
                title: "required",
                sub_description: "required",
                description: "required",
                position: "required",
                status: "required",
                tags: "required",
                type: "required",
                images: "required",
                category_id: "required",
            },
            messages: {
                image: "This field is required",
                sub_description: "This field is required",
                description: "This field is required",
                position: "This field is required",
                status: "This field is required",
                tags: "This field is required",
                type: "This field is required",
                images: "This field is required",
                category_id: "This field is required",
            }
        });

        <?php $timestamp = time();?>
        $('#file_upload').uploadify({
            'formData'     : {
                'ids':       '<?php echo $ids;?>',
                'member_id': '<?php echo $member_id;?>',
                'cate':      '<?php echo $cate;?>',
                'cate_id':   '<?php echo $cate_id;?>', //2=news
                'timestamp': '<?php echo $timestamp;?>',
                'token':     '<?php echo md5("unique_salt" . $timestamp);?>'
            },
            'removeCompleted' : true,
            //'debug'    : true,
            'multi'    : true,
            'swf'      : '<?php echo URL::to("public/themes/adminlte2");?>/plugins/uploadify/uploadify.swf',
            'uploader' : '<?php echo URL::to("news/uploads");?>',
            'onUploadSuccess' : function(file, data, response) {
                var data      = jQuery.parseJSON(data);
                var url       = data.url;
                var code      = data.code;
                var member_id = data.member_id;
                var extension = data.extension;

                var url_img = '<?php echo URL::to("public");?>/uploads/'+member_id+'/news/'+code+'.'+extension;

                $('#images').val('<?php echo $ids;?>');
                var img_upload_tag = ''+
                '<div id="'+code+'"><ul class="ace-thumbnails">'+
                    '<li>'+
                        '<a href="javascript:void(0)">'+
                            '<img alt="150x150" src="'+ url +'" width="150" height="150">'+
                        '</a>'+
                        '<div class="tools tools-bottom">'+
                            '<a href="javascript:void(0)" onclick="return image_delete(\''+code+'\', \''+member_id+'\', \''+extension+'\');" title="Delete">'+
                                '<i class="fa fa-fw fa-trash-o"></i>'+
                            '</a>'+
                            '<a class="copy-link-wrap" href="javascript:void(0)" title="Copy Link" data="'+url_img+'">'+
                                '<i class="fa fa-fw fa-link"></i>'+
                            '</a>'+
                        '</div>'+
                    '</li>'+
                '</ul><input type="hidden" name="images_arr['+code+']" value="'+code+'">'+
                '</div>';

                $("#show_image_upload").append(img_upload_tag);

                $('.copy-link-wrap').zclip({
                    path: '<?php echo URL::to("public");?>/themes/adminlte2/plugins/zclip/ZeroClipboard.swf',
                    copy:function(){return $(this).attr('data');},
                    afterCopy:function(){
                        console.log('Copy Success');
                        // bootbox.dialog({
                        //   message: "Data in clipboard! '"+$(this).attr('data')+"'",
                        //   buttons: {
                        //     success: {
                        //       label: "ตกลง",
                        //       className: "btn-small btn-primary"
                        //     }
                        //   }
                        // });
                    }
                });

                num_images++;
                //alert('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);
            },
            'onUploadError' : function(file, errorCode, errorMsg, errorString) {
                alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
            } 
        });
    });

    function image_delete(code, member_id, extension){
        bootbox.confirm("คุณต้องการลบหรือไม่", function(result) {
            if(result){
                $.ajax({
                    type: "GET",
                    url: "<?php echo URL::to('news/deleteimages');?>",
                    data: 'code='+code+'&member_id='+member_id+'&extension='+extension,
                    dataType: "json",
                    success: function(data) {
                        
                        if(data.status_code == '0')
                        {
                            num_images--;
                            if(num_images < 0){
                                $('#images').val('');
                            }

                            console.log("Success");
                            $('#'+code).remove();
                        }else{
                            bootbox.dialog({
                              message: "Can't delete this item!",
                              buttons: {
                                success: {
                                  label: "ตกลง",
                                  className: "btn-small btn-primary"
                                }
                              }
                            });
                            console.log(data);
                        }
                        $('.order_up_down').attr("disabled", false);
                        processing = false;
                    },
                    error: function(){
                        bootbox.dialog({
                          message: "Can't delete this item!",
                          buttons: {
                            success: {
                              label: "ตกลง",
                              className: "btn-small btn-primary"
                            }
                          }
                        });
                        onsole.log("Unsuccess");
                    }
                });
            }
        });
    }

    // function onAddTag(tag) {
    //     alert("Added a tag: " + tag);
    // }
    // function onRemoveTag(tag) {
    //     alert("Removed a tag: " + tag);
    // }

    // function onChangeTag(input,tag) {
    //     alert("Changed a tag: " + tag);
    // }
</script>
