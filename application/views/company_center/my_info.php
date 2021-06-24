<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/2019/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/company_center.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/mobileSelect.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/2019/jq-1.11.1.js"></script>
        <script src="<?= VIEW_PATH; ?>js/2019/jquery.validate.min.js" type='text/javascript' language="javascript"></script>
        <script src="<?= VIEW_PATH; ?>js/2019/mobileSelect.min.js" type='text/javascript' language="javascript"></script>
        <title>��ҵ��Ϣ</title>
    </head>
    <body id="my_info">
        <script>
            $(document).ready(function() {
                $("#basic_form").validate({
                    ignore: "",
                    rules: {
                        companyname: {
                            required: true
                        },
                        scale: {
                            required: true
                        },
                        registered: {
                            required: true
                        },
                        district: {
                            required: true
                        },
                        address: {
                            required: true
                        }
                    },
                    messages: {
                        companyname: {
                            required: "����д��ҵ����"
                        },
                        scale: {
                            required: "��ѡ����ҵ��ģ"
                        },
                        registered: {
                            required: "����дע���ʽ�"
                        },
                        district: {
                            required: "��ѡ���������ڵ���"
                        },
                        address: {
                            required: "����д��ϸ��ַ"
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
        <form id="info_form" action="/company_center/my_info_save" method="post">
            <ul class="edit_ul">
                <li>
                    <label class="left">��ҵ����</label>
                    <span class="left">�����</span>
                    <input class="right" type="text" name="companyname" value="<?= $company['companyname'] ?>" placeholder="��������ҵ����"/>
                </li>
                <li>
                    <label class="left">��ҵ����</label>
                    <span class="left">�����</span>
                    <div class="right">
                        <span id="nature_span"><?php if ($company['nature'] > 0): ?><?= $company['nature_cn'] ?><?php else: ?>��ѡ����ҵ����<?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="nature_input" type="hidden" name="nature" value="<?php if ($company['nature'] > 0): ?><?= $company['nature'] ?>|<?= $company['nature_cn'] ?><?php endif; ?>" />
                    <script type="text/javascript">
                        var mobileSelect3 = new MobileSelect({
                            trigger: '#nature_span',
                            title: '��ҵ����',
                            wheels: [
                                {data: <?= $company_type ?>}
                            ],
                            position: [0],
                            ensureBtnText: 'ȷ��',
                            cancelBtnText: 'ȡ��',
                            callback: function(indexArr, data) {
                                var v = data[0].id;
                                $('input#nature_input').val(v);
                            }
                        });
                    </script>
                </li>
                <li>
                    <label class="left">��ҵ��ģ</label>
                    <span class="left">�����</span>
                    <div class="right">
                        <span id="scale_span"><?php if ($company['scale'] > 0): ?><?= $company['scale_cn'] ?><?php else: ?>��ѡ����ҵ��ģ<?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="scale_input" type="hidden" name="scale" value="<?php if ($company['scale'] > 0): ?><?= $company['scale'] ?>|<?= $company['scale_cn'] ?><?php endif; ?>" />
                    <script type="text/javascript">
                        var mobileSelect3 = new MobileSelect({
                            trigger: '#scale_span',
                            title: '��ҵ��ģ',
                            wheels: [
                                {data: <?= $company_scale ?>}
                            ],
                            position: [0],
                            ensureBtnText: 'ȷ��',
                            cancelBtnText: 'ȡ��',
                            callback: function(indexArr, data) {
                                var v = data[0].id;
                                $('input#scale_input').val(v);
                            }
                        });
                    </script>
                </li>
                <li>
                    <label class="left">ע���ʽ���Ԫ��</label>
                    <span class="left">�����</span>
                    <input class="right" type="text" name="registered" value="<?= $company['registered'] ?>" placeholder="������ע���ʽ�"/>
                </li>
                <li>
                    <label class="left">���ڵ���</label>
                    <span class="left">�����</span>
                    <div class="right">
                        <span id="district_span"><?php if ($company['district'] > 0): ?><?= $company['district_cn'] ?><?php else: ?>��ѡ���������ڵ���<?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="district_input" type="hidden" name="district" value="<?= $company['district'] ?>"/>
                    <input id="district_cn_input" type="hidden" name="district_cn" value="<?= $company['district_cn'] ?>"/>
                    <script type="text/javascript">
                        var mobileSelect3 = new MobileSelect({
                            trigger: '#district_span',
                            title: '���ڵ���',
                            wheels: [
                                {data: <?= $district_categories ?>}
                            ],
                            position: [0, 0],
                            ensureBtnText: 'ȷ��',
                            cancelBtnText: 'ȡ��',
                            callback: function(indexArr, data) {
                                var d = data[0].id;
                                var sd = data[1].id;
                                var d_arr = d.split("|");
                                var sd_arr = sd.split("|");
                                d = d_arr[0] + "." + sd_arr[0];
                                sd = d_arr[1] + "/" + sd_arr[1];
                                if (sd_arr[0] > 0) {
                                    sd = d_arr[1] + "/" + sd_arr[1];
                                } else {
                                    sd = d_arr[1];
                                }
                                $('input#district_input').val(d);
                                $('input#district_cn_input').val(sd);
                            }
                        });
                    </script>
                </li>
                <li>
                    <label class="left">��ϸ��ַ</label>
                    <span class="left">�����</span>
                    <input class="right" type="text"  name="address" placeholder="����д������ϸ��ַ" value="<?= $company['address'] ?>">
                </li>
            </ul>
            <div class="clear"></div>
            <div class="submit_box">
                <input class="submit_big" type="submit" value="����" />
            </div>
        </form>
        <div class="clear"></div>
        <?php $this->load->view('public/2019/company_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
