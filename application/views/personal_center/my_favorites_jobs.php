<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/2019/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/personal_center.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/2019/jq-1.11.1.js"></script>
        <title>我的收藏</title>
    </head>


    <body id="my_favorites_jobs">
        <div class="type_tab_box">
            <div class="jobs hover">
                <a href="/personal_center/my_favorites_jobs"><span>职位</span></a>
                <i></i>
            </div>
            <div class="article">
                <a href="/personal_center/my_favorites_article"><span>简章</span></a>
                <i></i>
            </div>
            <div class="manage" data-show="0"></div>
        </div>
        <div class="clear"></div>
        <form action="/personal_center/my_favorites_jobs_del" method="post">
            <ul class="list">
                <?php if (!empty($favorites_jobs_list)): ?>
                    <?php foreach ($favorites_jobs_list as $fsl): ?>
                        <li>
                            <div class="left">
                                <input name="list_id[]" type="checkbox" class="checkbox"  value="<?= $fsl['did'] ?>" />
                            </div>
                            <div class="right">
                                <a class="job_name" href="/job/detail?job_id=<?= $fsl['jobs_id'] ?>"><?= $fsl['jobs_name'] ?></a>
                                <span class="time"><?= date('Y-m-d', $fsl['addtime']) ?></span>
                                <div class="clear"></div>
                                <p class="company_name"><?= $fsl['companyname'] ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div id="empty_box">
                        <i></i>
                        <p>目前没有收藏的职位！<a href="/job">去收藏职位</a></p>
                    </div>
                <?php endif; ?>
            </ul>
            <?php if ($page_arr['totalpage'] > 1): ?>
                <div class="clear"></div>
                <div id="page_box" data-url="/personal_center/my_favorites_jobs/" data-parameter="">
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
                <input class="submit" type='submit' value="删除" />
                <div class="clear"></div>
            </div>
            <script>
                $('.type_tab_box .manage').click(function() {
                    var show = $(this).attr('data-show');
                    if (show == 0) {
                        $('ul.list li div.left').show();
                        $('ul.list li div.right').css('width', 'calc(100% - 35px)');
                        $('.all_box').show();
                        $(this).addClass("manage_hover");
                        $(this).attr('data-show', "1");
                    } else {
                        $('ul.list li div.left').hide();
                        $('ul.list li div.right').css('width', 'calc(100% - 20px)');
                        $('.all_box').hide();
                        $(this).removeClass("manage_hover");
                        $(this).attr('data-show', "0");
                    }
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
            </script>
        </form>
        <div class="clear"></div>
        <script src="<?= VIEW_PATH; ?>js/common.js"></script>
        <?php $this->load->view('public/2019/bottom_help.php') ?>
        <?php $this->load->view('public/2019/bottom_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
