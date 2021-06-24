<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/user_reg.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/jq-1.11.1.js"></script>
        <script src="<?= VIEW_PATH; ?>js/jquery.validate.min.js"></script>
        <title>���˺�</title>
    </head>

    <body>
        <div class="logo_box">
            <a href="/">
                <img src="<?= VIEW_PATH; ?>images/logo.png" width="40%" />
            </a>
        </div>
        <div class="form_box">
            <form id="Form1">
                <div class="username" data-bind="<?= $this->session->userdata['member_type']; ?>">
                    <input placeholder="�û�����Ӣ�ġ����ֺ��»���" type="text" id="username" name="username" />
                </div>
                <div class="email">
                    <input placeholder="����д����" type="text" id="email" name="email" />
                </div>
                <div class="password">
                    <input placeholder="������6-20λ����" type="text" id="password" name="password" />
                </div>
                <input type="submit" class="submit" value="ע��" />
                <div class="clear"></div>
                <p class="agreement">ע�������ͬ��<a href="/user/agreement">����ʦ��Ƹ���û�����Э�顷</a></p>
                <div class="clear"></div>
            </form>
        </div>
        <script>
            $("input").focus(function() {
                $("label.error").hide();
            });
            $("#Form1").validate({
                onsubmit: true,
                onkeyup: false,
                onfocusout: false,
                focusInvalid: false,
                submitHandler: function(form) {
                    $.post("/user/reg2_save", {
                        "username": $("#username").val(),
                        "email": $("#email").val(),
                        "password": $("#password").val()
                    },
                    function(data, textStatus) {
                        if (data.indexOf('err') > -1) {
                            var arr = data.split('-');
                            $("#username").attr("value", "");
                            $("#email").attr("value", "");
                            $("#password").attr("value", "");
                            alert(arr[1]);
                        } else {
                            window.location.href = data;
                        }
                    })
                    //$(form).ajaxSubmit();
                },
                rules: {
                    username: {
                        required: true,
                        userName: true,
                        nomobile: true,
                        byteRangeLength: [3, 18],
                        remote: {
                            url: "/user/check_username",
                            type: "post",
                            data: {
                                "username": function() {
                                    return $("#username").val()
                                }}
                        }
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: "/user/check_email",
                            type: "post",
                            data: {
                                "email": function() {
                                    return $("#email").val()
                                }}
                        }
                    },
                    password: {
                        required: true,
                        minlength: 6,
                        maxlength: 20
                    }
                },
                messages: {
                    username: {
                        required: "����д�û���",
                        remote: jQuery.format("�û����Ѿ����ڻ��߰���������")
                    },
                    password: {
                        required: "����д����",
                        minlength: jQuery.format("���벻��С��{0}���ַ�"),
                        maxlength: jQuery.format("���벻�ܴ���{0}���ַ�")
                    },
                    email: {
                        required: "����д��������",
                        email: jQuery.format("���������ʽ����"),
                        remote: jQuery.format("email�Ѿ�����")
                    }
                },
                errorPlacement: function(error, element) {
                    if (element.is(":radio"))
                        error.appendTo(element.parent().next().next());
                    else if (element.is(":checkbox"))
                        error.appendTo(element.next());
                    else
                        error.appendTo($('.form_box'));
                }
            });
            //�����������ֽ�
            jQuery.validator.addMethod("byteRangeLength", function(value, element, param) {
                var length = value.length;
                for (var i = 0; i < value.length; i++) {
                    if (value.charCodeAt(i) > 127) {
                        length++;
                    }
                }
                return this.optional(element) || (length >= param[0] && length <= param[1]);
            }, "�û�������3-18���ֽ�֮��");
            //�ַ���֤
            jQuery.validator.addMethod("userName", function(value, element) {
                return this.optional(element) || /^[\w]+$/.test(value);
            }, "�û���ֻ����Ӣ�ġ����ֺ��»���");
            jQuery.validator.addMethod("nomobile", function(value, element) {
                var tel = /^(1)\d{10}$/;
                var $cstr = true;
                if (tel.test(value))
                    $cstr = false;
                return $cstr || this.optional(element);
            }, "�û����������ֻ���");
        </script>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>