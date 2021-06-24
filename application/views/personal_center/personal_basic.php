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
        <title>个人中心-基本信息</title>
    </head>
    <body id="edit_basic"><script>
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
                    email: {
                        required: true,
                        email: true
                    },
                    education: {
                        required: true
                    },
                    experience: {
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
                    email: {
                        required: "请填写电子邮箱",
                        email: "电子邮箱格式错误"
                    },
                    education: {
                        required: "请选择您的学历"
                    },
                    experience: {
                        required: "请选择您的工作经验"
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
            jQuery.validator.addMethod("isMobile", function(value, element) {
                var length = value.length;
                var mobile = /^(1)\d{10}$/;
                return this.optional(element) || (length == 11 && mobile.test(value));
            }, "请正确填写您的手机号码");
        });
        </script>
        <form id="basic_form" action="/personal_center/save_basic" method="post">
            <ul class="edit_ul">
                <li>
                    <label class="left">姓名</label>
                    <span class="left">（必填）</span>
                    <input class="right" type="text" placeholder="请填写您的姓名" name="fullname" value="<?= $basic['fullname'] ?>"  />
                </li>
                <li>
                    <label class="left">性别</label>
                    <span class="left">（必填）</span>
                    <div class="right">
                        <span id="sex_span"><?php if ($basic['sex'] == 1): ?>男<?php else: ?>女<?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="sex_input" type="hidden" name="sex" value="<?php if ($basic['sex'] == 1): ?>1|男<?php else: ?>2|女<?php endif; ?>" />
                    <script type="text/javascript">
                        var mobileSelect3 = new MobileSelect({
                            trigger: '#sex_span',
                            title: '性别',
                            wheels: [
                                {data: <?= $select_sex ?>}
                            ],
                            position: [0],
                            ensureBtnText: '确定',
                            cancelBtnText: '取消',
                            callback: function(indexArr, data) {
                                var v = data[0].id;
                                $('input#sex_input').val(v);
                            }
                        });
                    </script>
                </li>
                <li>
                    <label class="left">出生年份</label>
                    <span class="left">（必填）</span>
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
                    <label class="left">学历</label>
                    <span class="left">（必填）</span>
                    <div class="right">
                        <span id="education_span"><?php if ($basic['education'] > 0): ?><?= $basic['education_cn'] ?><?php else: ?>请选择您的学历<?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="education_input" type="hidden" name="education" value="<?php if ($basic['education'] > 0): ?><?= $basic['education'] . "|" . $basic['education_cn'] ?><?php endif; ?>" />
                    <script type="text/javascript">
                        var mobileSelect3 = new MobileSelect({
                            trigger: '#education_span',
                            title: '学历',
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
                    <label class="left">工作经验</label>
                    <span class="left">（必填）</span>
                    <div class="right">
                        <span id="experience_span"><?php if ($basic['experience'] > 0): ?><?= $basic['experience_cn'] ?><?php else: ?>请选择您的学历<?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="experience_input" type="hidden" name="experience" value="<?php if ($basic['experience'] > 0): ?><?= $basic['experience'] . "|" . $basic['experience_cn'] ?><?php endif; ?>" />
                    <script type="text/javascript">
                        var mobileSelect3 = new MobileSelect({
                            trigger: '#experience_span',
                            title: '工作经验',
                            wheels: [
                                {data: <?= $experience_categories ?>}
                            ],
                            position: [0],
                            ensureBtnText: '确定',
                            cancelBtnText: '取消',
                            callback: function(indexArr, data) {
                                var v = data[0].id;
                                $('input#experience_input').val(v);
                            }
                        });
                    </script>
                </li>
                </li>
                <li>
                    <label class="left">手机</label>
                    <span class="left">（必填）</span>
                    <input class="right" type="text" name="telephone" value="<?= $basic['telephone'] ?>" placeholder="请输入您的手机号"/>
                </li>
                <li>
                    <label class="left">邮箱</label>
                    <span class="left">（必填）</span>
                    <input class="right" type="text" name="email" value="<?= $basic['email'] ?>" placeholder="请输入您的电子邮箱"/>
                </li>
                <li>
                    <label class="left">现居住地</label>
                    <div class="right">
                        <span id="residence_span"><?php if ($basic['residence'] > 0): ?><?= $basic['residence_cn'] ?><?php else: ?>请选择您的现居住地<?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="residence_input" type="hidden" name="residence" value="<?= $basic['residence'] ?>"/>
                    <input id="residence_cn_input" type="hidden" name="residence_cn" value="<?= $basic['residence_cn'] ?>"/>
                    <script type="text/javascript">
                        var mobileSelect3 = new MobileSelect({
                            trigger: '#residence_span',
                            title: '现居住地',
                            wheels: [
                                {data: <?= $district_categories ?>}
                            ],
                            position: [0, 0],
                            ensureBtnText: '确定',
                            cancelBtnText: '取消',
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
                                $('input#residence_input').val(d);
                                $('input#residence_cn_input').val(sd);
                            }
                        });
                    </script>
                </li>
                <li>
                    <label class="left">身高</label>
                    <input class="right" type="text" name="height" value="<?= $basic['height'] ?>" placeholder="请输入您的身高"/>
                </li>
                <li>
                    <label class="left">籍贯</label>
                    <div class="right">
                        <span id="householdaddress_span"><?php if ($basic['householdaddress'] > 0): ?><?= $basic['householdaddress_cn'] ?><?php else: ?>请选择您的籍贯<?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="householdaddress_input" type="hidden" name="householdaddress" value="<?= $basic['householdaddress'] ?>"/>
                    <input id="householdaddress_cn_input" type="hidden" name="householdaddress_cn" value="<?= $basic['householdaddress_cn'] ?>"/>
                    <script type="text/javascript">
                        var mobileSelect3 = new MobileSelect({
                            trigger: '#householdaddress_span',
                            title: '籍贯',
                            wheels: [
                                {data: <?= $district_categories ?>}
                            ],
                            position: [0, 0],
                            ensureBtnText: '确定',
                            cancelBtnText: '取消',
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
                                $('input#householdaddress_input').val(d);
                                $('input#householdaddress_cn_input').val(sd);
                            }
                        });
                    </script>
                </li>
                <li>
                    <label class="left">婚姻状况</label>
                    <div class="right">
                        <span id="marriage_span"><?php if ($basic['marriage'] == 1): ?>未婚<?php elseif ($basic['marriage'] == 2): ?>已婚<?php else: ?>保密<?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="marriage_input" type="hidden" name="marriage" value="<?php if ($basic['marriage'] == 1): ?>1|未婚<?php elseif ($basic['marriage'] == 2): ?>2|已婚<?php else: ?>3|保密<?php endif; ?>" />
                    <script type="text/javascript">
                        var mobileSelect3 = new MobileSelect({
                            trigger: '#marriage_span',
                            title: '婚姻状况',
                            wheels: [
                                {data: <?= $select_marriage ?>}
                            ],
                            position: [0],
                            ensureBtnText: '确定',
                            cancelBtnText: '取消',
                            callback: function(indexArr, data) {
                                var v = data[0].id;
                                $('input#marriage_input').val(v);
                            }
                        });
                    </script>
                </li>
            </ul>
            <div class="clear"></div>
            <div class="submit_box">
                <input class="submit_big" type="submit" value="保存" />
            </div>
        </form>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
