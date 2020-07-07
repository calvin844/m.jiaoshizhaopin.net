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
        <script src="<?= VIEW_PATH; ?>js/2019/mobileSelect.min.js" type='text/javascript' language="javascript"></script>
        <title>完善信息(3/3)</title>
    </head>
    <body id="make_info">
        <p class="step_box"><span>3</span>/3</p>
        <p class="tips_box">在最新一次工作经历里等你</p>
        <form id="basic_form" action="/personal_center/make_info3_save" method="post">
            <div class="item_box">
                <ul>
                    <li>
                        <label>公司名称</label>
                        <input name="companyname" class="text" type="text" placeholder="请输入公司名称" />
                    </li>
                    <li>
                        <label>职位名称</label>
                        <input name="jobs" class="text" type="text" placeholder="请填写职位名称" />
                    </li>
                    <li>
                        <label>在职时间</label>
                        <i></i>
                        <div class="time_box">
                            <div class="right">
                                <span id="start_time_span">入职时间</span>
                                <span class="line"></span>
                                <span id="end_time_span">离职时间</span>
                            </div>
                            <input id="starttime_value" class="year" type="hidden" name="starttime" value="<?= date('Y') ?>.<?= date('n') ?>"/>
                            <input id="endtime_value" class="year" type="hidden" name="endtime" value="<?= date('Y') ?>.<?= date('n') ?>"/>
                            <script type="text/javascript">
                                var mobileSelect3 = new MobileSelect({
                                    trigger: '#start_time_span',
                                    title: '入职时间',
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
                                    title: '离职时间',
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
                <input type="hidden" id="to_resume" name="to_resume" value="<?php if (!$job_flag): ?>0<?php else: ?>1<?php endif; ?>"/>
                <?php if (!$job_flag): ?>
                    <input class="submit" type="submit" value="" />
                <?php endif; ?>
            </div>
        </form> 
        <p class="tip">*选填信息</p>
        <?php if ($job_flag): ?>
            <div class="submit_box">
                <input type="submit" class="back_job" value="返回投递简历" />
                <input type="submit" class="to_resume" value="继续完善简历" />
                <div class="resume_tip_box">
                    <p>继续完善简历，高薪职位随手可得！</p>
                    <i class="footer"></i>
                </div>
                <script>
                    $('input.back_job').click(function() {
                        $('input#to_resume').attr('value', '0');
                        $('form').submit();
                    })
                    $('input.to_resume').click(function() {
                        $('input#to_resume').attr('value', '1');
                        $('form').submit();
                    })
                </script>
            </div>
        <?php endif; ?>
        <script src="<?= VIEW_PATH; ?>js/common.js" type='text/javascript' language="javascript"></script>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
