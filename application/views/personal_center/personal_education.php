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
        <script src="<?= VIEW_PATH; ?>js/2019/mobileSelect.min.js" type='text/javascript' language="javascript"></script>
        <script src="<?= VIEW_PATH; ?>js/2019/jq-1.11.1.js"></script>
        <script src="<?= VIEW_PATH; ?>js/2019/jquery.validate.min.js" type='text/javascript' language="javascript"></script>
        <title>��������</title>
    </head>
    <body id="edit_education">
        <script>
            $(document).ready(function() {
                $("#education_form").validate({
                    ignore: "",
                    rules: {
                        school: {
                            required: true
                        },
                        speciality: {
                            required: true
                        },
                        education: {
                            required: true
                        },
                        startyear: {
                            required: true
                        },
                        startmonth: {
                            required: true
                        },
                        endyear: {
                            required: true
                        },
                        endmonth: {
                            required: true
                        }
                    },
                    messages: {
                        school: {
                            required: "����дѧУ����"
                        },
                        speciality: {
                            required: "����дרҵ����"
                        },
                        education: {
                            required: "��ѡ��ѧ��"
                        },
                        startyear: {
                            required: "��ѡ��ʼ���"
                        },
                        startmonth: {
                            required: "��ѡ��ʼ�·�"
                        },
                        endyear: {
                            required: "��ѡ��������"
                        },
                        endmonth: {
                            required: "��ѡ������·�"
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
        <?php if (!empty($education['id'])): ?>
            <form action="/personal_center/del_education" method="post">
                <input type="hidden" name="id" value="<?= $education['id'] ?>"/>
                <input class="top_del" type="button" value="ɾ��" />
            </form>
            <script>
                $('.top_del').click(function() {
                    if (confirm('ȷ��ɾ����')) {
                        $(this).parent().submit();
                    } else {
                        return FALSE;
                    }
                })
            </script>
            <div class="clear"></div>
        <?php endif; ?>
        <form id="education_form" action="/personal_center/save_education" method="post">
            <ul class="edit_ul">
                <li>
                    <label class="left">ѧУ����</label>
                    <span class="left">�����</span>
                    <input class="right" type="text" name="school" placeholder="������ѧУ����" value="<?= $education['school'] ?>"/>
                </li>
                <li>
                    <label class="left">רҵ����</label>
                    <span class="left">�����</span>
                    <input class="right" type="text" name="speciality" placeholder="������רҵ����" value="<?= $education['speciality'] ?>"/>
                </li>
                <li>
                    <label class="left">ѧ��</label>
                    <span class="left">�����</span>
                    <div class="right">
                        <span id="education_span"><?php if ($education['education'] > 0): ?><?= $education['education_cn'] ?><?php else: ?>��ѡ������ѧ��<?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="education_input" type="hidden" name="education" value="<?php if ($education['education'] > 0): ?><?= $education['education'] . "|" . $education['education_cn'] ?><?php endif; ?>" />
                    <script type="text/javascript">
                        var mobileSelect3 = new MobileSelect({
                            trigger: '#education_span',
                            title: 'ѧ��',
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
                    <label class="left">��ʼʱ��</label>
                    <span class="left">�����</span>
                    <div class="right">
                        <span id="start_time_span"><?php if (!empty($education['startyear'])): ?><?= $education['startyear'] ?><?php else: ?><?= date('Y') ?><?php endif; ?>-<?php if (!empty($education['startmonth'])): ?><?= $education['startmonth'] ?><?php else: ?><?= date('n') ?><?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="startyear" class="year" type="hidden" name="startyear" value="<?php if (!empty($education['startyear'])): ?><?= $education['startyear'] ?><?php else: ?><?= date('Y') ?><?php endif; ?>"/>
                    <input id="startmonth" class="month" type="hidden" name="startmonth" value="<?php if (!empty($education['startmonth'])): ?><?= $education['startmonth'] ?><?php else: ?><?= date('n') ?><?php endif; ?>"/>
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
                                $('input#startyear').val(y);
                                $('input#startmonth').val(m);
                            }
                        });
                    </script>
                </li>
                <li>
                    <label class="left">����ʱ��</label>
                    <span class="left">�����</span>
                    <div class="right">
                        <span id="end_time_span"><?php if (!empty($education['endyear'])): ?><?= $education['endyear'] ?><?php else: ?><?= date('Y') ?><?php endif; ?>-<?php if (!empty($education['endmonth'])): ?><?= $education['endmonth'] ?><?php else: ?><?= date('n') ?><?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="endyear" class="year" type="hidden" name="endyear" value="<?php if (!empty($education['endyear'])): ?><?= $education['endyear'] ?><?php else: ?><?= date('Y') ?><?php endif; ?>"/>
                    <input id="endmonth" class="month" type="hidden" name="endmonth" value="<?php if (!empty($education['endmonth'])): ?><?= $education['endmonth'] ?><?php else: ?><?= date('n') ?><?php endif; ?>"/>
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
                                $('input#endyear').val(y);
                                $('input#endmonth').val(m);
                            }
                        });
                    </script>
                </li>
            </ul>
            <div class="clear"></div>
            <div class="submit_box">
                <input type="hidden" name="id" value="<?php if (!empty($education['id'])): ?><?= $education['id'] ?><?php else: ?>0<?php endif; ?>"/>
                <?php if (!empty($education['id'])): ?>
                    <input class="submit_big" type="submit" value="����" />
                <?php else: ?>
                    <a class="to_next" href="/personal_center/edit_work">��������</a>
                    <input class="submit" type="submit" value="����" />
                <?php endif; ?>
            </div>
        </form>
        <?php $this->load->view('public/2019/bottom_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
