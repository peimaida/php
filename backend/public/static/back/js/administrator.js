    $(function () {
        $('form').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                username: {
                    message: '用户名验证失败',
                    validators: {
                        notEmpty: {
                            message: '用户名不能为空'
                        },
                        stringLength: {
                            min: 5,
                            max: 10,
                            message: '用户名长度必须在5到10位之间'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9_]+$/,
                            message: '用户名只能包含字母、数字和下划线'
                        }
                    }
                },
                password: {
                    message: '密码验证失败',
                    validators: {
                        notEmpty: {
                            message: '密码不能为空'
                        },
                        stringLength: {
                            min: 5,
                            max: 10,
                            message: '密码长度必须在5到10位之间'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9]+$/,
                            message: '密码只能包含字母、数字'
                        }
                    }
                },
                tel: {
                    message: '分机号验证失败',
                    validators: {
                        notEmpty: {
                            message: '分机号不能为空'
                        },
                        stringLength: {
                            min: 4,
                            max: 4,
                            message: '分机号长度为4位'
                        },
                        numeric: {
                            message: '分机只能输入数字'
                        }
                    }
                },
            }
        });
    });