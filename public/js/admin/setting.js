
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







});