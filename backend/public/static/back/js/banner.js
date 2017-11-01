    $(function () {
        $('form').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                banner_title: {
                    message: '图片名称验证失败',
                    validators: {
                        notEmpty: {
                            message: '图片名称不能为空'
                        },
                    }
                },

                url:{
                    validators:{
                        callback:{
                            message:'URL地址不正确',
                            callback:function(){
                                var txt = $("input[name='url']").val();
                                var match = 'cn.m.misumi-ec.com';
                                return txt.indexOf(match) == -1;
                            }

                        }
                    }
                },

                feature_image: {
                    validators: {
                        callback: {
                            message: 'banner图片不能为空',
                            callback:function(){
                                var txt = $("#feature_image").val();
                                return txt != "";
                            }
                        }
                    }
                },

                start_time: {
                    validators: {
                        callback: {
                            message: '公开日期不能为空',
                            callback:function(value, validator,$field){
                                var begin = $("input.datepicker[name='start_time']").val();
                                validator.updateStatus('start_time', 'VALID');
                                return begin != "";
                            }
                        }
                    }
                },

                end_time: {
                    validators: {
                        callback: {
                            message: '结束日期不能为空',
                            callback:function(value, validator,$field){
                                var end = $("input.datepicker[name='end_time']").val();
                                return end != "";
                                validator.updateStatus('end_time', 'VALID');
                            }
                        }
                    }
                }
            }
        });
    });

//验证图片尺寸大小
var _URL = window.URL || window.webkitURL;
$("#feature_image").change(function(){
    var file, img;
    if ((file = this.files[0])) {
      img = new Image();
      img.onload = function () {
        var wid = this.width;
        var height = this.height;
        var src = this.src;
        if(wid/height != 750/300){
            alert("请上传比例为750*300的图片！");
            $("#feature_image").val("");
            $("#feature_image_preshow").attr("src",""); 
        }else{
            if(height<300 || wid < 750){
                alert("图片宽度不能小于750px，高度不能小于300px！");
                $("#feature_image").val("");
                $("#feature_image_preshow").attr("src",""); 
            }else{
                $("#feature_image_preshow").attr("src",src);
            }
        }
        $('form').data('bootstrapValidator')  
                    .updateStatus('feature_image', 'NOT_VALIDATED')  
                    .validateField('feature_image'); 

    };
    img.src = _URL.createObjectURL(file);
    }
})

$(function () {
    $("input.datepicker[name='start_time']").on("dp.change", function (e) {
        $("input.datepicker[name='end_time']").data("DateTimePicker").minDate(e.date);
        $('form[name=banners_create_form]').data('bootstrapValidator')  
                    .updateStatus('start_time', 'NOT_VALIDATED',null)  
                    .validateField('start_time');  

    });
    $("input.datepicker[name='end_time']").on("dp.change", function (e) {
        $("input.datepicker[name='start_time']").data("DateTimePicker").maxDate(e.date);
        $('form[name=banners_create_form]').data('bootstrapValidator')  
                    .updateStatus('end_time', 'NOT_VALIDATED',null)  
                    .validateField('end_time');  
    });
});

$("input[name='banner_title']").keyup(function(){
    var txt = $(this).val().length;
    if(txt > 16){
        $(".help-tips").show();
    }else{
        $(".help-tips").hide();
    }
})
