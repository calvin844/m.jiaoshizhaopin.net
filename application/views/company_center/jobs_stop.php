<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/2019/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/company_center.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/2019/jq-1.11.1.js"></script>
        <title>已暂停的职位</title>
    </head>
    <body id="company_jobs">
        <div class="type_tab_box2">
            <ul class="tab_ul">
                <li>
                    <a href="/company_center/jobs_release"><span>发布中</span></a>
                    <i></i>
                </li>
                <li>
                    <a href="/company_center/jobs_audit"><span>审核中</span></a>
                    <i></i>
                </li>
                <li class="hover">
                    <a href="/company_center/jobs_stop"><span>已暂停</span></a>
                    <i></i>
                </li>
                <li>
                    <a href="/company_center/jobs_nopass"><span>未通过</span></a>
                    <i></i>
                </li>
            </ul>
            <div class="more_do">
                <input id="edit_list" data-state="0" type="button" class="manage" />
            </div>
            <script>
                $('#edit_list').click(function() {
                    var state = $(this).attr('data-state');
                    if (state == 0) {
                        $(this).addClass('hover');
                        $(this).attr('data-state', '1');
                        $('ul li div.left').show();
                        $('ul li div.right').css('width', 'calc(100% - 40px)');
                        $('.all_box').show();
                    } else {
                        $(this).removeClass('hover');
                        $(this).attr('data-state', '0');
                        $('ul li div.right').removeAttr('style');
                        $('ul li div.left').hide();
                        $('.all_box').hide();
                    }
                })
            </script>
        </div>
        <div class="clear"></div>
        <form id="stop_form" action="" method="post">
            <ul class="list">
                <?php foreach ($stop_list as $sl): ?>
                    <li data-url="/job/detail?job_id=<?= $sl['id'] ?>">
                        <div class="left">
                            <input type="checkbox" name="did[]" value="<?= $sl['id'] ?>" />
                        </div>
                        <div class="right">
                            <p class="job_name"><?= $sl['jobs_name'] ?></p>
                            <div class="clear"></div>
                            <p class="info">应聘：<?= $sl['resume_total'] ?> | 浏览：<?= $sl['click'] ?></p>
                            <span class="date"><?= date("Y/m/d", $sl['refreshtime']) ?></span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </form>
        <?php if ($page_arr['totalpage'] > 1): ?>
            <div class="clear"></div>
            <div id="page_box" data-url="/company_center/jobs_stop/" data-parameter="">
                <div class="per <?php if ($page_arr['now_page'] == 1): ?>per_disable<?php endif; ?>">
                    <a class="<?php if ($page_arr['now_page'] == 1): ?>left_disable<?php endif; ?>" data-page="<?= $page_arr['per_page'] ?>"><i></i><span>上一页</span></a>
                </div>
                <div class="cur">
                    <span>第<?= $page_arr['now_page'] ?>页</span><i></i>
                    <select>
                        <?php for ($i = $page_arr['start_page']; $i < $page_arr['end_page'] + 1; $i++): ?>
                            <option <?php if ($i == $page_arr['now_page']): ?>selected="selected"<?php endif; ?> value="<?= $i ?>">第<?= $i ?>页</option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="next  <?php if ($page_arr['now_page'] == $page_arr['totalpage']): ?>next_disable<?php endif; ?>">
                    <a class="<?php if ($page_arr['now_page'] == $page_arr['totalpage']): ?>right_disable<?php endif; ?>" data-page="<?= $page_arr['next_page'] ?>"><span>下一页</span><i></i></a>
                </div>
            </div>
            <div class="clear"></div>
        <?php endif; ?>
        <div class="clear"></div>
        <div class="all_box">
            <input id="check_all_input" class="all_check" type="checkbox" />
            <span class="total">全选<br/><b id="check_total">0</b>/10</span>
            <div class="right">
                <input id="start" class="button" type="button" value="开始" />
                <input id="delete" class="submit" type="submit" value="删除" />
            </div>
            <div class="clear"></div>
        </div>
        <script>
            $('ul.list li div.right').click(function() {
                var url = $(this).parent().attr('data-url');
                window.location.href = url;
            })
            $('#check_all_input').click(function() {
                if ($('#check_all_input').hasClass('checked')) {
                    $('#check_all_input').removeClass('checked');
                    $('ul.list li input').removeClass('checked');
                    $('ul.list li input').removeAttr('checked');
                } else {
                    $('#check_all_input').addClass('checked');
                    $('ul.list li input').addClass('checked');
                    $('ul.list li input').attr('checked', 'checked');
                }
                check_total();
            })
            $('ul.list li input').click(function() {
                check_total();
            })
            function check_total() {
                var total = 0;
                $('ul.list li input').each(function() {
                    if ($(this).is(':checked')) {
                        total = total + 1;
                    }
                })
                $('#check_total').html(total)
            }
            $('#start').click(function() {
                $('#check_all').prop("checked", false);
                $('#stop_form').attr('action', '/company_center/job_to_stop');
                $('#stop_form').submit();
            })
            $('#delete').click(function() {
                $('#check_all').prop("checked", false);
                $('#stop_form').attr('action', '/company_center/job_to_delete');
                $('#stop_form').submit();
            })
        </script>
        <div class="clear"></div>
        <script src="<?= VIEW_PATH; ?>js/2019/common.js"></script>
        <?php $this->load->view('public/2019/bottom_help.php') ?>
        <?php $this->load->view('public/2019/company_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
