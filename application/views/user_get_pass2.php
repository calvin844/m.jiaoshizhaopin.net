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
        <title>�һ�����</title>
    </head>

    <body>
        <div class="logo_box">
            <a href="/">
                <img src="<?= VIEW_PATH; ?>images/logo.png" width="40%" />
            </a>
        </div>
        <div class="form_box">
            <form id="Form1" action="/user/reset_pass" method="post">
                <div class="password">
                    <input id="password" name="password" type="text" placeholder="������������" />
                </div>
                <input type="hidden" name="uid" value="<?= $uid ?>"/>
                <input type="submit" class="submit" value="ȷ��" />
                <div class="clear"></div>
            </form>
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
                    password: {
                        required: true,
                        minlength: 6,
                        maxlength: 20
                    },
                    uid: {
                        required: true,
                        digits: true
                    }
                },
                messages: {
                    password: {
                        required: "����д����",
                        minlength: jQuery.format("��д����С��{0}���ַ�"),
                        maxlength: jQuery.format("��д���ܴ���{0}���ַ�")
                    },
                    uid: {
                        required: "�û���Ϣ����",
                        digits: "�û���Ϣ����"
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
        </script>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>








