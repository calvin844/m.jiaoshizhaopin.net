<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/2019/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/personal_center.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/2019/jq-1.11.1.js"></script>
        <script src="<?= VIEW_PATH; ?>js/2019/jquery.validate.min.js" type='text/javascript' language="javascript"></script>
        <script src="<?= VIEW_PATH; ?>js/2019/ajaxfileupload.js" type='text/javascript' language="javascript"></script>
        <title>��������-����΢����Ϣ</title>
    </head>



    <body id="system_wechat">
        <script>
            $(document).ready(function() {
                $("#wechat_form").validate({
                    ignore: "",
                    rules: {
                        wechat_name: {
                            required: true
                        }
                    },
                    messages: {
                        wechat_name: {
                            required: "����д����΢�ź�"
                        }
                    },
                    showErrors: function(errorMap, errorList) {
                        this.defaultShowErrors();
                        setTimeout("$('label.error').remove()", 1500);
                    },
                    errorPlacement: function(error, element) {
                        if (element.is(":radio"))
                            error.appendTo(element.parent().next().next());
                        else if (element.is(":checkbox"))
                            error.appendTo(element.parent());
                        else
                            error.appendTo('form');
                    }
                });
            });
        </script>
        <p class="tips">#�ö�ά�����ڿͷ����Ž��𼰺������ϵ</p>
        <form id="wechat_form" action="/personal_center/save_wechat" method="post">
            <ul class="wechat_ul">
                <li class="wechat_name">
                    <label class="left">΢�ź�</label>
                    <span class="left">������콱�ر���</span>
                    <input class="right" type="text" name="wechat_name" value="<?= $user_info['wechat_name'] ?>" placeholder="����������΢�ź�"/>
                </li>
                <li class="wechat_code_img">
                    <label class="left">΢�Ŷ�ά��</label>
                    <span class="left">��ѡ���������֪ͨ��</span>
                    <input class="right" type="file" id="wechat_img" name="wechat_img_input" onchange="uploadFile();"/>
                    <input type="hidden" id="wechat_img_path" name="wechat_img" value=""/>
                </li>
                <?php if (!empty($user_info['wechat_img'])): ?>
                    <li class="wechat_img">
                        <img src="/data/pay_img/<?= $user_info['wechat_img'] ?>" width="100" />
                    </li>
                <?php endif; ?>
            </ul>
            <div class="clear"></div>
            <div class="submit_box">
                <input class="submit" type="submit" value="����" />
            </div>
        </form>
        <div id="loading_box">
            <img src="<?= VIEW_PATH; ?>images/loading.gif" width="50" height="50" />
            <div class="clear"></div>
            <p>�����ϴ�</p>
            <div class="bg"></div>
        </div>
        <script>
            function uploadFile() {
                $('div#loading_box').show();
                $.ajaxFileUpload({
                    url: '/personal_center/up_wechat_img',
                    secureuri: false,
                    fileElementId: 'wechat_img',
                    dataType: 'STRING',
                    success: function(data, status) {
                        $('div.loading').hide();
                        var f = data.substr(0, 1);
                        if (f == "-") {
                            switch (data) {
                                case "-1":
                                    $('label.error').html("�ϴ�ͼƬʧ�ܣ��ϴ�Ŀ¼������!");
                                    return false;
                                    break;
                                case "-2":
                                    $('label.error').html("�ϴ�ͼƬʧ�ܣ��ϴ�Ŀ¼�޷�д��!");
                                    return false;
                                    break;
                                case "-3":
                                    $('label.error').html("�ϴ�ͼƬʧ�ܣ���ѡ����ļ��޷��ϴ�");
                                    return false;
                                    break;
                                case "-4":
                                    $('label.error').html("�ϴ�ͼƬʧ�ܣ��ļ���С��������");
                                    return false;
                                    break;
                                case "-5":
                                    $('label.error').html("�ϴ�ͼƬʧ�ܣ��ļ����ʹ���");
                                    return false;
                                    break;
                                case "-6":
                                    $('label.error').html("�ϴ�ͼƬʧ�ܣ��ļ��ϴ�����");
                                    return false;
                                    break;
                                case "-7":
                                    $('label.error').html("��ˢ��ҳ������ԣ�");
                                    return false;
                                    break;
                                case "-8":
                                    $('label.error').html("���ϴ�ͼƬ��");
                                    return false;
                                    break;
                                default:
                                    break;
                            }
                            $('label.error').show();
                            setTimeout("$('label.error').remove()", 1500);
                        } else {
                            alert("�ϴ��ɹ���������������Ϣ��������");
                            $('.wechat_img').show();
                            $('.wechat_img img').attr('src', '/data/pay_img/'+data);
                            $('#wechat_img_path').attr('value', data);
                            
                        }
                        $('div#loading_box').hide();
                    }
                })
            }
        </script>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
