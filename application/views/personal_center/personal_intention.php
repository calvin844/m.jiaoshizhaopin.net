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
        <title>个人中心-求职意向</title>
    </head>
    <body id="edit_intention">
        <script>
            $(document).ready(function() {
                $("#intention_form").validate({
                    ignore: "",
                    rules: {
                        intention_jobs: {
                            required: true
                        },
                        nature: {
                            required: true
                        },
                        wage: {
                            required: true
                        },
                        district_cn: {
                            required: true
                        }
                    },
                    messages: {
                        intention_jobs: {
                            required: "请选择期望职位"
                        },
                        nature: {
                            required: "请选择工作性质"
                        },
                        wage: {
                            required: "请选择期望薪资"
                        },
                        district_cn: {
                            required: "请选择期望地区"
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
        <form id="intention_form" action="/personal_center/save_intention" method="post" >
            <ul class="edit_ul">
                <li class="jobs">
                    <label class="left">期望职位</label>
                    <span class="left">（必填）</span>
                    <div class="right">
                        <span id="jobs_span">请选择（最多选择5个）</span>
                        <i></i>
                    </div>
                    <input type="hidden" id="jobs_input" name="intention_jobs" value="<?php foreach ($intention_list as $il): ?><?= $il['category'] ?>.<?= $il['subclass'] ?>|<?php endforeach; ?>" />
                    <script type="text/javascript">
                        var mobileSelect3 = new MobileSelect({
                            trigger: '#jobs_span',
                            title: '期望职位',
                            wheels: [
                                {data: <?= $jobs_type ?>}
                            ],
                            position: [0, 0],
                            ensureBtnText: '确定',
                            cancelBtnText: '取消',
                            callback: function(indexArr, data) {
                                var li_length = $("ul.jobs_ul li").length;
                                var f = 0;
                                if (li_length == 5) {
                                    alert('最多选择5个职位');
                                    return false;
                                } else {
                                    var category = data[0].id;
                                    var subclass = data[1].id;
                                    var category_arr = category.split('|');
                                    var subclass_arr = subclass.split('|');
                                    $('ul.jobs_ul li').each(function() {
                                        var li_c = $(this).attr('data-category');
                                        var li_s = $(this).attr('data-subclass');
                                        if (li_c == category_arr[0] && li_s == subclass_arr[0]) {
                                            alert('该职位已添加');
                                            f = 1;
                                        }
                                    })
                                    if (f == 0) {
                                        $('ul.jobs_ul').append('<li data-category="' + category_arr[0] + '" data-subclass="' + subclass_arr[0] + '"><span>' + category_arr[1] + '-' + subclass_arr[1] + '</span><i></i><div class="clear"></div></li>');
                                        make_jobs();
                                    }
                                }
                            }
                        });
                    </script>
                    <div class="clear"></div>
                    <ul class="jobs_ul">
                        <?php if (!empty($intention_list)): ?>
                            <?php foreach ($intention_list as $il): ?>
                                <li data-category="<?= $il['category'] ?>" data-subclass="<?= $il['subclass'] ?>">
                                    <span><?= $il['category_cn'] ?>-<?= $il['subclass_cn'] ?></span>
                                    <i></i>
                                    <div class="clear"></div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                    <script>
                        $('li.jobs ul.jobs_ul').on('click', 'li i', function() {
                            $(this).parent().remove();
                            make_jobs();
                        })
                        function make_jobs() {
                            var jobs_str = "";
                            $('ul.jobs_ul li').each(function() {
                                var c = $(this).attr('data-category');
                                var s = $(this).attr('data-subclass');
                                jobs_str = jobs_str + c + "." + s + "|";
                            })
                            $('input#jobs_input').val(jobs_str);
                        }
                    </script>
                </li>
                <li>
                    <label class="left">工作性质</label>
                    <span class="left">（必填）</span>
                    <div class="right">
                        <span id="nature_span"><?php if ($basic['nature'] > 0): ?><?= $basic['nature_cn'] ?><?php else: ?>请选择工作性质<?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="nature_input" type="hidden" name="nature" value="<?php if ($basic['nature'] > 0): ?><?= $basic['nature'] . "|" . $basic['nature_cn'] ?><?php endif; ?>" />
                    <script type="text/javascript">
                        var mobileSelect3 = new MobileSelect({
                            trigger: '#nature_span',
                            title: '工作性质',
                            wheels: [
                                {data: <?= $nature_categories ?>}
                            ],
                            position: [0],
                            ensureBtnText: '确定',
                            cancelBtnText: '取消',
                            callback: function(indexArr, data) {
                                var v = data[0].id;
                                $('input#nature_input').val(v);
                            }
                        });
                    </script>
                </li>
                <li>
                    <label class="left">期望薪资</label>
                    <span class="left">（必填）</span>
                    <div class="right">
                        <span id="wage_span"><?php if ($basic['wage'] > 0): ?><?= $basic['wage_cn'] ?><?php else: ?>请选择期望薪资<?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="wage_input" type="hidden" name="wage" value="<?php if ($basic['wage'] > 0): ?><?= $basic['wage'] . "|" . $basic['wage_cn'] ?><?php endif; ?>" />
                    <script type="text/javascript">
                        var mobileSelect3 = new MobileSelect({
                            trigger: '#wage_span',
                            title: '期望薪资',
                            wheels: [
                                {data: <?= $wage_categories ?>}
                            ],
                            position: [0],
                            ensureBtnText: '确定',
                            cancelBtnText: '取消',
                            callback: function(indexArr, data) {
                                var v = data[0].id;
                                $('input#wage_input').val(v);
                            }
                        });
                    </script>
                </li>
                <li>
                    <label class="left">期望地区</label>
                    <span class="left">（必填）</span>
                    <div class="right">
                        <span id="district_span"><?php if ($basic['district'] > 0): ?><?= $basic['district_cn'] ?><?php else: ?>请选择您的期望地区<?php endif; ?></span>
                        <i></i>
                    </div>
                    <input id="district_input" type="hidden" name="district" value="<?= $basic['district'] . "." . $basic['sdistrict'] ?>"/>
                    <input id="district_cn_input" type="hidden" name="district_cn" value="<?= $basic['district_cn'] ?>"/>
                    <script type="text/javascript">
                        var mobileSelect3 = new MobileSelect({
                            trigger: '#district_span',
                            title: '期望地区',
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
                                $('input#district_input').val(d);
                                $('input#district_cn_input').val(sd);
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
