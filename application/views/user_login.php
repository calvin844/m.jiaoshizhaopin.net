<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/user_login.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/jq-1.11.1.js"></script>
        <script src="<?= VIEW_PATH; ?>js/jquery.validate.min.js"></script>
        <title>用户登录</title>
    </head>

    <body>
        <div class="logo_box">
            <a href="/">
                <img src="<?= VIEW_PATH; ?>images/logo.png" width="40%" />
            </a>
        </div>
        <div class="form_box">
            <form id="Form1" action="login_save" method="post">
                <div class="username">
                    <input type="text" name="account" id="account" placeholder="请输入邮箱/用户名" />
                </div>
                <div class="password">
                    <input type="password" name="password" id="password" placeholder="请输入密码" />
                </div>
                <input type="hidden" name="redirect_url" value="<?= $redirect_url ?>" />
                <input type="hidden" name="login_type" value="2" />
                <input type="submit" class="submit" value="登录" />
                <div class="clear"></div>
            </form>
        </div>
        <div class="other_box">
            <a class="left" href="/user/get_pass">忘记密码？</a>
            <a class="right" href="/user/reg">立即注册</a>
        </div>
        <div class="clear"></div>
        <div class="other_login">
            <a href="/user/login_code">手机号登录</a>
        </div>
        <script>
            $("input").focus(function () {
                $("label.error").hide();
            });
            $("#Form1").validate({
                onsubmit: true,
                onkeyup: false,
                onfocusout: false,
                focusInvalid: false,
                rules: {
                    account: {
                        required: true,
                        nomobile: true
                    },
                    password: {
                        required: true,
                        minlength: 6,
                        maxlength: 20
                    }
                },
                messages: {
                    account: {
                        required: "请填写用户名"
                    },
                    password: {
                        required: "请填写密码",
                        minlength: jQuery.format("密码不能小于{0}个字符"),
                        maxlength: jQuery.format("密码不能大于{0}个字符")
                    }
                },
                errorPlacement: function (error, element) {
                    if (element.is(":radio"))
                        error.appendTo(element.parent().next().next());
                    else if (element.is(":checkbox"))
                        error.appendTo(element.next());
                    else
                        error.appendTo($('.form_box'));
                }
            });
            //中文字两个字节
            jQuery.validator.addMethod("byteRangeLength", function (value, element, param) {
                var length = value.length;
                for (var i = 0; i < value.length; i++) {
                    if (value.charCodeAt(i) > 127) {
                        length++;
                    }
                }
                return this.optional(element) || (length >= param[0] && length <= param[1]);
            }, "用户名需在3-18个字节之间");
            jQuery.validator.addMethod("nomobile", function (value, element) {
                var tel = /^(1)\d{10}$/;
                var $cstr = true;
                if (tel.test(value))
                    $cstr = false;
                return $cstr || this.optional(element);
            }, "用户名不能是手机号");
        </script>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>