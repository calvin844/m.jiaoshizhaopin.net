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
        <title>�û���¼</title>
    </head>

    <body>
        <div class="logo_box">
            <a href="/">
                <img src="<?= VIEW_PATH; ?>images/logo.png" width="40%" />
            </a>
        </div>
        <div class="form_box">
            <form id="Form1" action="/user/login_save" method="post" >
                <div class="mobile">
                    <input type="text" name="mobile" id="mobile" placeholder="�������ֻ���" />
                </div>
                <div class="code">
                    <input type="text" name="code" id="code"  placeholder="��������֤��" />
                    <button class="send_code" id="send_code">��ȡ��֤��</button>
                    <script>
                        $('#send_code').click(function() {
                            $.get("/user/send_code/" + $('#mobile').val() + "/<?= $sms_back_code ?>/2", function(data) {
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
                                val.html("��ȡ��֤��");
                                countdown = 60;
                            } else {
                                val.attr("disabled", true);
                                val.addClass("disabled");
                                val.html("���»�ȡ��" + countdown + "s��");
                                countdown--;
                            }
                        }
                    </script>
                </div>
                <input type="hidden" name="login_type" value="1" />
                <input type="submit" class="submit" value="��¼" />
                <div class="clear"></div>
            </form>
        </div>
        <div class="other_box">
            <a class="left" href="/user/get_pass">�������룿</a>
            <a class="right" href="/user/reg">����ע��</a>
        </div>
        <div class="clear"></div>
        <div class="other_login">
            <a href="/user/login">�˺������¼</a>
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
                        is_mobile: true
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
                        required: "����д�ֻ���",
                        minlength: "�ֻ��Ÿ�ʽ����",
                        digits: "�ֻ��Ÿ�ʽ����"
                    },
                    code: {
                        required: "����д������֤��",
                        digits: "������֤���ʽ����",
                        minlength: "������֤���ʽ����",
                        maxlength: "������֤���ʽ����",
                        remote: jQuery.format("������֤�����")
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
            }, "�ֻ��Ÿ�ʽ����");
        </script>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>