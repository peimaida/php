    $(function () {
        $('form').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            excluded:[":disabled"],
            fields: {
                post_title: {
                    message: '标题验证失败',
                    validators: {
                        notEmpty: {
                            message: '标题不能为空'
                        },
                    }
                },
                title_image: {
                    validators: {
                        callback: {
                            message: '首页封面图片不能为空',
                            callback:function(){
                                var txt = !$("#title_image_preshow").attr("src");
                                return !txt;
                            }
                        }
                    }
                },
                feature_image: {
                    validators: {
                        callback: {
                            message: '详情页图片不能为空',
                            callback:function(){
                                var txt = !$("#feature_image_preshow").attr("src");
                                return !txt;
                            }
                        }
                    }
                },

                post_content:{
                    validators:{
                        callback:{
                            message: '新闻内容不能为空',
                            callback:function(){
                            var txt = editor.getData();
                            return txt !="";
                        }
                        }
                    }
                },

                start_time: {
                    validators: {
                        callback: {
                            message: '公开日期不能为空',
                            callback:function(){
                                var begin = $("input.datepicker[name='start_time']").val();
                                return begin != "";
                            }
                        }
                    }
                },

                end_time: {
                    validators: {
                        callback: {
                            message: '结束日期不能为空',
                            callback:function(){
                                var end = $("input.datepicker[name='end_time']").val();
                                return end != "";
                            }
                        }
                    }
                }
            }
        });
    });

//验证图片尺寸大小
var _URL = window.URL || window.webkitURL;
$("#title_image").change(function(){
    var file,img,name;
    name = $("input[name='title_image_url']").val();
    if ((file = this.files[0])) {
      img = new Image();
      img.onload = function () {
        var wid = this.width;
        var height = this.height;
        var src = this.src;
        if(height != wid){
            alert("请上传正方形图片！");
            $("#title_image").val("");
            $("#title_image_preshow").attr("src","");
        }else{
            if(height<370 || wid < 370){
                alert("宽高不能小于370px！");
                $("#title_image").val("");
                $("#title_image_preshow").attr("src","");
                
            }else{
                $("#title_image_preshow").attr("src",src); 

            }
        }
        $('form').data('bootstrapValidator').updateStatus('title_image', 'NOT_VALIDATED',null)  
                    .validateField('title_image'); 

    };
    img.src = _URL.createObjectURL(file);
    }
})

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
        $('form').data('bootstrapValidator').updateStatus('feature_image', 'NOT_VALIDATED',null)  
                    .validateField('feature_image'); 

    };
    img.src = _URL.createObjectURL(file);
    }
})

editor.on("change",function(){
    $('form').data('bootstrapValidator').updateStatus('post_content', 'NOT_VALIDATED').validateField('post_content');  
})


$("input[name='post_title']").keyup(function(){
    var txt = $(this).val().length;
    if(txt > 16){
        $(".help-tips").show();
    }else{
        $(".help-tips").hide();
    }
})