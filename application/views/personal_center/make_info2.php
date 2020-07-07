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
        <title>完善信息(2/3)</title>
    </head>
    <body id="make_info">
        <script>
            $(document).ready(function() {
                $("#basic_form").validate({
                    ignore: "",
                    rules: {
                        district: {
                            required: true
                        },
                        intention_jobs: {
                            required: true
                        }
                    },
                    messages: {
                        district: {
                            required: "请选择期望地区"
                        },
                        intention_jobs: {
                            required: "请选择期望职位"
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
            });
        </script>
        <p class="step_box"><span>2</span>/3</p>
        <p class="tips_box">这里有过去和你期望的未来</p>
        <form id="basic_form" action="/personal_center/make_info2_save" method="post">
            <div class="item_box">
                <ul>
                    <li>
                        <label>工作经验</label>
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
                    <li>
                        <label>工作性质</label>
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
                        <label>期望地区</label>
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
                                    $('span#district_span').val(sd);
                                }
                            });
                        </script>
                    </li>
                    <li>
                        <label>期望薪资</label>
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
                        <label>期望职位</label>
                        <div class="right">
                            <span id="job_type_span">请选择您的期望职位</span>
                            <i></i>
                        </div>
                        <input id="intention_jobs_input" type="hidden" name="intention_jobs" value=""/>
                        <script type="text/javascript">
                            var mobileSelect3 = new MobileSelect({
                                trigger: '#job_type_span',
                                title: '期望职位',
                                wheels: [
                                    {data: <?= $job_types_categories ?>}
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
                                    $('input#intention_jobs_input').val(d);
                                }
                            });
                        </script>
                        <!--
                        <i></i>
                        <div class="txt_box">
                            <span>请选择期望职位</span>
                            <input type="hidden" name="intention_jobs" value="" />
                        </div>
                        -->
                    </li>
                </ul>
                <input class="next" type="submit" value="" />
            </div>
        </form> 
        <div id="select_box" data-item="">
            <div class="select">
                <div class="title">
                    <p></p>
                    <i class="close"></i>
                </div>
                <ul></ul>
            </div>
            <div class="bg"></div>
        </div>
        <div id="select_box2">
            <div class="select">
                <ul class="left">
                    <?php foreach ($parent_job_types as $pjy): ?>
                        <li id="parent_job_types<?= $pjy['id'] ?>" data-data1="<?= $pjy['id'] ?>" class="<?php if (in_array($pjy['id'], $intention_category_list)): ?>hover<?php endif; ?>"><p><?= $pjy['categoryname'] ?></p></li>
                    <?php endforeach; ?>
                </ul>
                <?php foreach ($job_types as $jt_k => $jt_v): ?>
                    <ul id="job_types<?= $jt_k ?>" class="right" >
                        <?php foreach ($jt_v as $jt_v): ?>
                            <li data-data1="<?= $jt_v['id'] ?>" data-data2="<?= $jt_v['parentid'] ?>" class="<?php if (in_array($jt_v['id'], $intention_subclass_list)): ?>hover<?php endif; ?>"><p><?= $jt_v['categoryname'] ?></p></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            </div>
            <div class="bg"></div>
        </div>
        <script>
            $('#select_box').on('click', 'li', function() {
                var item = $('#select_box').attr('data-item');
                var layer = $('#' + item).attr('data-layer');
                if (layer == '0') {
                    var data_id = $(this).attr('data-data1');
                    var data_cn = $(this).find('p').html();
                    $('#' + item).attr('data-layer', '1');
                    show_select('选择城市', 'get_city/' + data_id, item);
                } else {
                    var data_id = $('#' + item).find('input[name=' + item + ']').attr('value') + "." + $(this).attr('data-data1');
                    var data_cn = $('#' + item).find('input[name=' + item + '_cn]').attr('value') + "/" + $(this).find('p').html();
                    $('#' + item).attr('data-layer', '0');
                    $('#select_box').hide();
                }
                $('#' + item + ' div.txt_box').find('span').html(data_cn);
                $('#' + item).parent().find('input[name=' + item + ']').attr('value', data_id);
                $('#' + item).parent().find('input[name=' + item + '_cn]').attr('value', data_cn);
            })

            $('#select_box2 ul.right:first').show();

            $('#select_box2 ul.left li').click(function() {
                $('#select_box2 ul.left li').removeClass('hover');
                $(this).addClass('hover');
                $('#select_box2 ul.right li').removeClass('hover');
                $('#select_box2 ul.right').hide();
                var pid = $(this).attr('data-data1');
                $('#select_box2 ul#job_types' + pid).show();
            })

            $('#select_box2 ul.right li').click(function() {
                $('#select_box2 ul.right li').removeClass('hover');
                $(this).addClass('hover');
                var pid = $(this).attr('data-data2');
                var sid = $(this).attr('data-data1');
                var str = $(this).find('p').html();
                $('#intention_jobs').parent().find('input[name=intention_jobs]').attr('value', pid + "." + sid);
                $('#select_box2').hide();
                $('#intention_jobs .txt_box span').html(str);
            })
        </script>
        <script src="<?= VIEW_PATH; ?>js/common.js" type='text/javascript' language="javascript"></script>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
