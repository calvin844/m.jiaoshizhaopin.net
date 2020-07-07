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
        <title>完善信息(1/3)</title>
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
                            required: "请填写姓名"
                        },
                        telephone: {
                            required: "请填写手机号码",
                            minlength: "手机号格式错误",
                            isMobile: "手机号格式错误"
                        },
                        school: {
                            required: "请填写学校名称"
                        },
                        speciality: {
                            required: "请填写专业名称"
                        },
                        starttime: {
                            required: "请选择入学时间"
                        },
                        endtime: {
                            required: "请选择毕业时间"
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
                }, "请正确填写您的手机号码");
            });
        </script>
        <p class="step_box"><span>1</span>/3</p>
        <p class="tips_box">我想好好认识你</p>
        <form id="basic_form" action="/personal_center/make_info_save" method="post">
            <div class="item_box">
                <ul>
                    <li>
                        <label>姓名</label>
                        <input name="fullname" class="text" type="text" placeholder="请输入您的姓名" value="<?= $basic['fullname'] ?>" />
                    </li>
                    <li>
                        <label>性别</label>
                        <div class="radio_box">
                            <span data-value="1|男" class="<?php if ($basic['sex'] == 1): ?>selected<?php endif; ?>">男</span>
                            <span data-value="2|女" class="<?php if ($basic['sex'] == 2): ?>selected<?php endif; ?>">女</span>
                            <input name="sex" type="hidden" value="<?php if ($basic['sex'] == 1): ?>1|男<?php else: ?>2|女<?php endif; ?>" />
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
                        <label>出生年份</label>
                        <div class="right">
                            <span id="birthdate_span"><?php if ($basic['birthdate'] > 0): ?><?= $basic['birthdate'] ?><?php else: ?><?= date('Y') - 18 ?><?php endif; ?></span>
                            <i></i>
                        </div>
                        <input id="birthdate_input" type="hidden" name="birthdate" value="<?php if ($basic['birthdate'] > 0): ?><?= $basic['birthdate'] ?><?php else: ?><?= date('Y') - 18 ?><?php endif; ?>" />
                        <script type="text/javascript">
                            var mobileSelect3 = new MobileSelect({
                                trigger: '#birthdate_span',
                                title: '出生年份',
                                wheels: [
                                    {data: <?= $birthdate_year ?>}
                                ],
                                position: [0],
                                ensureBtnText: '确定',
                                cancelBtnText: '取消',
                                callback: function(indexArr, data) {
                                    var v = data[0].id;
                                    $('input#birthdate_input').val(v);
                                }
                            });
                        </script>
                    </li>
                    <li>
                        <label>手机号码</label>
                        <input class="text" type="text" name="telephone" value="<?= $basic['telephone'] ?>" placeholder="请输入您的手机号" />
                    </li>
                    <li>
                        <label>最高学历</label>
                        <div class="right">
                            <span id="education_span"><?php if ($basic['education'] > 0): ?><?= $basic['education_cn'] ?><?php else: ?>请选择您的学历<?php endif; ?></span>
                            <i></i>
                        </div>
                        <input id="education_input" type="hidden" name="education" value="<?php if ($basic['education'] > 0): ?><?= $basic['education'] . "|" . $basic['education_cn'] ?><?php endif; ?>" />
                        <script type="text/javascript">
                            var mobileSelect3 = new MobileSelect({
                                trigger: '#education_span',
                                title: '最高学历',
                                wheels: [
                                    {data: <?= $education_categories ?>}
                                ],
                                position: [0],
                                ensureBtnText: '确定',
                                cancelBtnText: '取消',
                                callback: function(indexArr, data) {
                                    var v = data[0].id;
                                    $('input#education_input').val(v);
                                }
                            });
                        </script>
                    </li>
                    <li>
                        <label>微信号</label>
                        <input name="wechat_name" class="text" type="text" placeholder="请输入您的微信号" />
                    </li>
                    <li>
                        <label>学校</label>
                        <input class="text" type="text" name="school" placeholder="请输入学校名称" value=""/>
                    </li>
                    <li>
                        <label>专业</label>
                        <input class="text" type="text" name="speciality" placeholder="请输入专业名称" value=""/>
                    </li>
                    <li>
                        <label>在读时间</label>
                        <i></i>
                        <div class="time_box">
                            <div class="right">
                                <span id="start_time_span">开始时间</span>
                                <span class="line"></span>
                                <span id="end_time_span">结束时间</span>
                            </div>
                            <input id="starttime_value" class="year" type="hidden" name="starttime" value="<?= date('Y') ?>.<?= date('n') ?>"/>
                            <input id="endtime_value" class="year" type="hidden" name="endtime" value="<?= date('Y') ?>.<?= date('n') ?>"/>
                            <script type="text/javascript">
                                var mobileSelect3 = new MobileSelect({
                                    trigger: '#start_time_span',
                                    title: '开始时间',
                                    wheels: [
                                        {data: <?= $select_time ?>}
                                    ],
                                    position: [0, 0],
                                    ensureBtnText: '确定',
                                    cancelBtnText: '取消',
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
                                    title: '结束时间',
                                    wheels: [
                                        {data: <?= $select_time ?>}
                                    ],
                                    position: [0, 0],
                                    ensureBtnText: '确定',
                                    cancelBtnText: '取消',
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
