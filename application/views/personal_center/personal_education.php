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
        <title>教育经历</title>
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
                            required: "请填写学校名称"
                        },
                        speciality: {
                            required: "请填写专业名称"
                        },
                        education: {
                            required: "请选择学历"
                        },
                        startyear: {
                            required: "请选择开始年份"
                        },
                        startmonth: {
                            required: "请选择开始月份"
                        },
                        endyear: {
                            required: "请选择结束年份"
                        },
                        endmonth: {
                            required: "请选择结束月份"
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
                <input class="top_del" type="button" value="删除" />
            </form>
            <script>
                $('.top_del').click(function() {
                    if (confirm('确定删除？')) {
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
                    <label class="left">学校名称</label>
                    <span class="left">（必填）</span>
                    <input class="right" type="text" name="school" placeholder="请输入学校名称" value="<?= $education['school'] ?>"/>
                </li>
                <li>
                    <label class="left">专业名称</label>
                    <span class="left">（必填）</span>
                    <input class="right" type="text" name="speciality" placeholder="请输入专业名称" value="<?= $education['speciality'] ?>"/>
                </li>
                <li>
                    <label class="left">学历</label>
                    <span class="left">（必填）</span>
                    <div class="right">
                        <span id="education_span"><?php if ($education['education'] > 0): ?><?= $education['education_cn'] ?><?php else: ?>请选择您的学历<?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="education_input" type="hidden" name="education" value="<?php if ($education['education'] > 0): ?><?= $education['education'] . "|" . $education['education_cn'] ?><?php endif; ?>" />
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
                    <label class="left">开始时间</label>
                    <span class="left">（必填）</span>
                    <div class="right">
                        <span id="start_time_span"><?php if (!empty($education['startyear'])): ?><?= $education['startyear'] ?><?php else: ?><?= date('Y') ?><?php endif; ?>-<?php if (!empty($education['startmonth'])): ?><?= $education['startmonth'] ?><?php else: ?><?= date('n') ?><?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="startyear" class="year" type="hidden" name="startyear" value="<?php if (!empty($education['startyear'])): ?><?= $education['startyear'] ?><?php else: ?><?= date('Y') ?><?php endif; ?>"/>
                    <input id="startmonth" class="month" type="hidden" name="startmonth" value="<?php if (!empty($education['startmonth'])): ?><?= $education['startmonth'] ?><?php else: ?><?= date('n') ?><?php endif; ?>"/>
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
                                $('input#startyear').val(y);
                                $('input#startmonth').val(m);
                            }
                        });
                    </script>
                </li>
                <li>
                    <label class="left">结束时间</label>
                    <span class="left">（必填）</span>
                    <div class="right">
                        <span id="end_time_span"><?php if (!empty($education['endyear'])): ?><?= $education['endyear'] ?><?php else: ?><?= date('Y') ?><?php endif; ?>-<?php if (!empty($education['endmonth'])): ?><?= $education['endmonth'] ?><?php else: ?><?= date('n') ?><?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="endyear" class="year" type="hidden" name="endyear" value="<?php if (!empty($education['endyear'])): ?><?= $education['endyear'] ?><?php else: ?><?= date('Y') ?><?php endif; ?>"/>
                    <input id="endmonth" class="month" type="hidden" name="endmonth" value="<?php if (!empty($education['endmonth'])): ?><?= $education['endmonth'] ?><?php else: ?><?= date('n') ?><?php endif; ?>"/>
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
                    <input class="submit_big" type="submit" value="保存" />
                <?php else: ?>
                    <a class="to_next" href="/personal_center/edit_work">跳过此项</a>
                    <input class="submit" type="submit" value="保存" />
                <?php endif; ?>
            </div>
        </form>
        <?php $this->load->view('public/2019/bottom_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
