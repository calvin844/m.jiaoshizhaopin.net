<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/2019/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/personal_center.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/mobileSelect.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/2019/jq-1.11.1.js"></script>
        <script src="<?= VIEW_PATH; ?>js/2019/jquery.validate.min.js" type='text/javascript' language="javascript"></script>
        <script src="<?= VIEW_PATH; ?>js/2019/mobileSelect.min.js" type='text/javascript' language="javascript"></script>
        <title>������Ϣ(1/3)</title>
    </head>
    <body id="make_info">
        <script>
            $(document).ready(function() {
                $("#basic_form").validate({
                    ignore: "",
                    rules: {
                        fullname: {
                            required: true
                        },
                        telephone: {
                            required: true,
                            minlength: 11,
                            isMobile: true
                        },
                        school: {
                            required: true
                        },
                        speciality: {
                            required: true
                        },
                        starttime: {
                            required: true
                        },
                        endtime: {
                            required: true
                        }
                    },
                    messages: {
                        fullname: {
                            required: "����д����"
                        },
                        telephone: {
                            required: "����д�ֻ�����",
                            minlength: "�ֻ��Ÿ�ʽ����",
                            isMobile: "�ֻ��Ÿ�ʽ����"
                        },
                        school: {
                            required: "����дѧУ����"
                        },
                        speciality: {
                            required: "����дרҵ����"
                        },
                        starttime: {
                            required: "��ѡ����ѧʱ��"
                        },
                        endtime: {
                            required: "��ѡ���ҵʱ��"
                        }
                    },
                    showErrors: function(errorMap, errorList) {
                        this.defaultShowErrors();
                        setTimeout("$('label.error').remove()", 2000);
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
                jQuery.validator.addMethod("isMobile", function(value, element) {
                    var length = value.length;
                    var mobile = /^(1)\d{10}$/;
                    return this.optional(element) || (length == 11 && mobile.test(value));
                }, "����ȷ��д�����ֻ�����");
            });
        </script>
        <p class="step_box"><span>1</span>/3</p>
        <p class="tips_box">����ú���ʶ��</p>
        <form id="basic_form" action="/personal_center/make_info_save" method="post">
            <div class="item_box">
                <ul>
                    <li>
                        <label>����</label>
                        <input name="fullname" class="text" type="text" placeholder="��������������" value="<?= $basic['fullname'] ?>" />
                    </li>
                    <li>
                        <label>�Ա�</label>
                        <div class="radio_box">
                            <span data-value="1|��" class="<?php if ($basic['sex'] == 1): ?>selected<?php endif; ?>">��</span>
                            <span data-value="2|Ů" class="<?php if ($basic['sex'] == 2): ?>selected<?php endif; ?>">Ů</span>
                            <input name="sex" type="hidden" value="<?php if ($basic['sex'] == 1): ?>1|��<?php else: ?>2|Ů<?php endif; ?>" />
                            <script>
                                $('.radio_box span').click(function() {
                                    $(this).parent().find('span').removeClass('selected');
                                    $(this).addClass('selected');
                                    var v = $(this).attr('data-value');
                                    $(this).parent().find('input').val(v);
                                })
                            </script>
                        </div>
                    </li>
                    <li>
                        <label>�������</label>
                        <div class="right">
                            <span id="birthdate_span"><?php if ($basic['birthdate'] > 0): ?><?= $basic['birthdate'] ?><?php else: ?><?= date('Y') - 18 ?><?php endif; ?></span>
                            <i></i>
                        </div>
                        <input id="birthdate_input" type="hidden" name="birthdate" value="<?php if ($basic['birthdate'] > 0): ?><?= $basic['birthdate'] ?><?php else: ?><?= date('Y') - 18 ?><?php endif; ?>" />
                        <script type="text/javascript">
                            var mobileSelect3 = new MobileSelect({
                                trigger: '#birthdate_span',
                                title: '�������',
                                wheels: [
                                    {data: <?= $birthdate_year ?>}
                                ],
                                position: [0],
                                ensureBtnText: 'ȷ��',
                                cancelBtnText: 'ȡ��',
                                callback: function(indexArr, data) {
                                    var v = data[0].id;
                                    $('input#birthdate_input').val(v);
                                }
                            });
                        </script>
                    </li>
                    <li>
                        <label>�ֻ�����</label>
                        <input class="text" type="text" name="telephone" value="<?= $basic['telephone'] ?>" placeholder="�����������ֻ���" />
                    </li>
                    <li>
                        <label>���ѧ��</label>
                        <div class="right">
                            <span id="education_span"><?php if ($basic['education'] > 0): ?><?= $basic['education_cn'] ?><?php else: ?>��ѡ������ѧ��<?php endif; ?></span>
                            <i></i>
                        </div>
                        <input id="education_input" type="hidden" name="education" value="<?php if ($basic['education'] > 0): ?><?= $basic['education'] . "|" . $basic['education_cn'] ?><?php endif; ?>" />
                        <script type="text/javascript">
                            var mobileSelect3 = new MobileSelect({
                                trigger: '#education_span',
                                title: '���ѧ��',
                                wheels: [
                                    {data: <?= $education_categories ?>}
                                ],
                                position: [0],
                                ensureBtnText: 'ȷ��',
                                cancelBtnText: 'ȡ��',
                                callback: function(indexArr, data) {
                                    var v = data[0].id;
                                    $('input#education_input').val(v);
                                }
                            });
                        </script>
                    </li>
                    <li>
                        <label>΢�ź�</label>
                        <input name="wechat_name" class="text" type="text" placeholder="����������΢�ź�" />
                    </li>
                    <li>
                        <label>ѧУ</label>
                        <input class="text" type="text" name="school" placeholder="������ѧУ����" value=""/>
                    </li>
                    <li>
                        <label>רҵ</label>
                        <input class="text" type="text" name="speciality" placeholder="������רҵ����" value=""/>
                    </li>
                    <li>
                        <label>�ڶ�ʱ��</label>
                        <i></i>
                        <div class="time_box">
                            <div class="right">
                                <span id="start_time_span">��ʼʱ��</span>
                                <span class="line"></span>
                                <span id="end_time_span">����ʱ��</span>
                            </div>
                            <input id="starttime_value" class="year" type="hidden" name="starttime" value="<?= date('Y') ?>.<?= date('n') ?>"/>
                            <input id="endtime_value" class="year" type="hidden" name="endtime" value="<?= date('Y') ?>.<?= date('n') ?>"/>
                            <script type="text/javascript">
                                var mobileSelect3 = new MobileSelect({
                                    trigger: '#start_time_span',
                                    title: '��ʼʱ��',
                                    wheels: [
                                        {data: <?= $select_time ?>}
                                    ],
                                    position: [0, 0],
                                    ensureBtnText: 'ȷ��',
                                    cancelBtnText: 'ȡ��',
                                    callback: function(indexArr, data) {
                                        var y = data[0].id;
                                        var m = data[1].id;
                                        $('#start_time_span').html(y + '-' + m)
                                        $('input#starttime_value').val(y + "." + m);
                                    }
                                });
                            </script>
                            <script type="text/javascript">
                                var mobileSelect3 = new MobileSelect({
                                    trigger: '#end_time_span',
                                    title: '����ʱ��',
                                    wheels: [
                                        {data: <?= $select_time ?>}
                                    ],
                                    position: [0, 0],
                                    ensureBtnText: 'ȷ��',
                                    cancelBtnText: 'ȡ��',
                                    callback: function(indexArr, data) {
                                        var y = data[0].id;
                                        var m = data[1].id;
                                        $('#end_time_span').html(y + '-' + m)
                                        $('input#endtime_value').val(y + "." + m);
                                    }
                                });
                            </script>
                        </div>
                    </li>
                </ul>
                <input class="next" type="submit" value="" />
            </div>
        </form> 
        <script src="<?= VIEW_PATH; ?>js/common.js" type='text/javascript' language="javascript"></script>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
