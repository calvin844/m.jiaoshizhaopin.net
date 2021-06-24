<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/user_get_pass.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/jq-1.11.1.js"></script>
        <script src="<?= VIEW_PATH; ?>js/jquery.validate.min.js"></script>
        <title>找回密码</title>
    </head>

    <body>
        <div class="logo_box">
            <a href="/">
                <img src="<?= VIEW_PATH; ?>images/logo.png" width="40%" />
            </a>
        </div>
        <div class="form_box">
            <form id="Form1" action="/user/get_pass2" method="post">
                <div class="mobile">
                    <input id="mobile" name="mobile" type="text" placeholder="请输入手机号" />
                </div>
                <div class="code">
                    <input id="code" name="code" type="text" placeholder="请输入验证码" />
                    <button id="send_code" class="send_code">获取验证码</button>
                    <script>
                        $('#send_code').click(function() {
                            $.get("/user/send_code/" + $('#mobile').val() + "/<?= $sms_back_code ?>/3", function(data) {
                                if (data != "") {
                                    alert(data);
                                } else {
                                    timer($('#send_code'));
                                }
                            })
                        })
                        var countdown = 60;
                        var t;
                        function timer(val) {
                            t = setInterval(function() {
                                settime(val)
                            }, 1000)
                        }
                        function settime(val) {
                            if (countdown == 0) {
                                clearInterval(t)
                                val.removeAttr("disabled");
                                val.removeClass("disabled");
                                val.html("获取验证码");
                                countdown = 60;
                            } else {
                                val.attr("disabled", true);
                                val.addClass("disabled");
                                val.html("重新获取（" + countdown + "s）");
                                countdown--;
                            }
                        }
                    </script>
                </div>
                <input type="submit" class="submit" value="找回密码" />
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
                rules: {
                    mobile: {
                        required: true,
                        digits: true,
                        minlength: 11,
                        is_mobile: true,
                        remote: {
                            url: "/user/check_mobile_exists",
                            type: "post",
                            data: {
                                "mobile": function() {
                                    return $("#mobile").val()
                                }}
                        }
                    },
                    code: {
                        required: true,
                        digits: true,
                        minlength: 4,
                        maxlength: 4,
                        remote: {
                            url: "/user/check_code",
                            type: "post",
                            data: {
                                "mobile": function() {
                                    return $("#mobile").val()
                                },
                                "code": function() {
                                    return $("#code").val()
                                }}
                        }
                    }
                },
                messages: {
                    mobile: {
                        required: "请填写手机号",
                        minlength: "手机号格式错误",
                        digits: "手机号格式错误",
                        remote: jQuery.format("用户不存在或者格式错误")
                    },
                    code: {
                        required: "请填写短信验证码",
                        digits: "短信验证码格式错误",
                        minlength: "短信验证码格式错误",
                        maxlength: "短信验证码格式错误",
                        remote: jQuery.format("短信验证码错误")
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
            jQuery.validator.addMethod("is_mobile", function(value, element) {
                var tel = /^(1)\d{10}$/;
                var $cstr = false;
                if (tel.test(value))
                    $cstr = true;
                return $cstr || this.optional(element);
            }, "手机号格式错误");
        </script>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>








