
$(function () {
    if ($('#page_content').length) {
        CKEDITOR.replace('page_content', {
            height: '410px',
            'extraPlugins': 'imgbrowse',
            'filebrowserImageBrowseUrl': base_url + '/public/plugins/ckeditor/plugins/imgbrowse/imgbrowse.html?imgroot=uploads/ck_editor_files/',
            "filebrowserImageUploadUrl": base_url + "/public/plugins/ckeditor/plugins/imgupload/iaupload.php?imgroot=uploads/ck_editor_files/"
        });

    }

    $("#banner_aeFrm").validate({
    });

    $("#fbanner_aeFrm").validate({
        rules: {
            img: {sizeCheck: "1"}
        },
        messages: {
            img: {accept: "Only JPG/PNG Allowed", sizeCheck: "Image Size must be less than 1 MB"}
        },
        submitHandler: function (form) {
            var btn = $('#fbanner_aeFrm button[name="submit"]').loading('set');
            $.ajax({
                url: $(form).attr('data-url'),
                dataType: "json",
                type: "POST",
                data: new FormData($('#fbanner_aeFrm')[0]),
                contentType: false,
                cache: false,
                processData: false,
                success: function (d) {
                    if (d.sts == 'success') {
                        window.location.reload();
                    } else if (d.sts == 'error') {
                        $.alert({title: 'Sorry!', content: d.msg});
                    }
                }
            }).always(function () {
                btn.loading('reset');
            });
            return false;
        }
    });

    $("#pageForm").validate({
        ignore: [],
        rules: {
            page_content: {
                required: function () {
                    CKEDITOR.instances.page_content.updateElement();
                }
            }
        },
        messages: {
            page_content: {required: "Please enter Page Content"}
        }
    });

    $("#applicatin_typeForm").validate({
        rules: {
            amount: {required: true, currency: 'amount'}
        }
    });

    $("#arrivalPortForm").validate({});



    $("#blog_form").validate({
        submitHandler: function (form) {
            var btn = $('#blog_form button[name="save_blog"]').loading('set');
            $.ajax({
                url: $(form).attr('data-url'),
                dataType: "json",
                type: "POST",
                data: new FormData($('#blog_form')[0]),
                contentType: false,
                cache: false,
                processData: false,
                success: function (d) {
                    if (d.sts == 'success') {
                        $.alert({title: 'Congratulations!', content: d.msg, confirm: function () {
                                window.location.href = d.url;
                            }});
                    } else if (d.sts == 'error') {
                        $.alert({title: 'Sorry!', content: d.msg});
                    }
                }
            }).always(function () {
                btn.loading('reset');
            });
            return false;
        }
    });

    $("#blog_content_form").validate({
        ignore: [],
        rules: {
            page_content: {
                required: function () {
                    CKEDITOR.instances.page_content.updateElement();
                }
            }
        },
        messages: {
            page_content: {required: "Please enter Page Content"}
        },
        submitHandler: function (form) {
            var btn = $('#blog_content_form button[name="save_blog"]').loading('set');
            for (var i in CKEDITOR.instances) {
                CKEDITOR.instances[i].updateElement()
            }
            $.ajax({
                url: $(form).attr('data-url'),
                dataType: "json",
                type: "POST",
                data: new FormData($('#blog_content_form')[0]),
                contentType: false,
                cache: false,
                processData: false,
                success: function (d) {
                    if (d.sts == 'success') {
                        $.alert({title: 'Congratulations!', content: d.msg, confirm: function () {
                                window.location.href = d.url;
                            }});
                    } else if (d.sts == 'error') {
                        $.alert({title: 'Sorry!', content: d.msg});
                    }
                }
            }).always(function () {
                btn.loading('reset');
            });
            return false;
        }
    });

    $('#blog_form #blog_name').keyup(function () {
        if ($('#blog_form #blog_id').val() == '') {
            $('#blog_form #slug').val($('#blog_form #blog_name').val().trim().replace(/ /g, "-").replace(/[^0-9a-zA-Z-_]+/g, '').toLowerCase());
        }
    });


    $('#add_img').click(function () {
        
    });



//    $('#add_img').click(function () {
//        var content_box_count = parseInt($('#content_data_no').val()) + parseInt(1);
//        var img_content = '<div class="form-group content_box_count" id="img_content_' + content_box_count + '"><label class="col-sm-2 control-label require">Blog Image</label><div class="col-sm-8"><input type="file" name="blog_img[' + content_box_count + ']" class="view_photo mt10" accept="image/.jpe,.jpg,.jpeg,.png" required="" label-name="Banner" /></div><div class="col-sm-2"><button class="btn bg-red btn-sm btn-flat del_content_img_box" data-content_id="' + content_box_count + '" data-title="Delete Blog Image?">Delete Blog Image<i class="fa fa-trash ml20"></i></button></div></div>';
//        $('#content_box').append(img_content);
//        $('#content_data_no').val(content_box_count)
//    });

//    $('#add_content').click(function () {
//        var content_box_count = parseInt($('#content_data_no').val()) + parseInt(1);
//        var textarea_id = 'blog_content_' + content_box_count;
//        var text_content = '<div class="form-group content_box_count" id="img_content_' + content_box_count + '"><label class="col-sm-2 control-label require">Blog Content</label><div class="col-sm-8"><textarea name="page_content[' + content_box_count + ']" required="" id="' + textarea_id + '" label-name="Page Content"></textarea></div><div class="col-sm-2"><button class="btn bg-red btn-sm btn-flat del_content_img_box" data-content_id="' + content_box_count + '" data-title="Delete Blog Image?">Delete Blog Image<i class="fa fa-trash ml20"></i></button></div></div>';
//        $('#content_box').append(text_content);
//        $('#content_data_no').val(content_box_count)
//        CKEDITOR.replace(textarea_id, {
//            height: '200px',
//            uiColor: '#9AB8F3',
//            toolbar: 'Basic',
//            toolbar_Basic: [['newplugin', 'Bold', 'Italic', 'Underline', 'button-pre', 'abbr', 'Source', 'blockquote', 'inserthtml'], ['PasteText', 'SpellChecker'], ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'], ['Link', 'Unlink', 'Anchor'], ['Styles', 'Format', 'Font', 'FontSize']]
//        });
//    });
 
//    if ($('.ckeditor').length) {
//        CKEDITOR.replaceClass = 'ckeditor';
//        CKEDITOR.config.toolbar = "Basic";
//        CKEDITOR.config.toolbar_Basic = [['newplugin', 'Bold', 'Italic', 'Underline', 'button-pre', 'abbr', 'Source', 'blockquote', 'inserthtml'], ['PasteText', 'SpellChecker'], ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'], ['Link', 'Unlink', 'Anchor'], ['Styles', 'Format', 'Font', 'FontSize']] ;
//    }
    





    $(document).on('click', '.del_content_img_box', function () {
        var i = $(this).attr('data-content_id');
        $('#img_content_' + i).remove();
    });
});