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
        <title>我的收藏</title>
    </head>
    <body id="favorites">
        <div class="type_tab_box">
            <ul class="tab_ul">
                <li>
                    <a href="/company_center/resume_apply"><span>新收简历</span></a>
                    <i></i>
                </li>
                <li>
                    <a href="/company_center/resume_down"><span>我的下载</span></a>
                    <i></i>
                </li>
                <li class="hover">
                    <a href="/company_center/resume_collect"><span>我的收藏</span></a>
                    <i></i>
                </li>
                <li>
                    <a href="/company_center/resume_interview"><span>面试邀请</span></a>
                    <i></i>
                </li>
            </ul>
            <div class="more_do" data-state="0">
                <div class="box">
                    <div class="bg"></div>
                    <ul class="more_do_ul">
                        <li class="manage" data-m="0">
                            <i></i><span>管理</span>
                        </li>
                    </ul>
                    <script type="text/javascript">
                        $('.more_do').click(function() {
                            var s = $(this).attr('data-state');
                            if (s == 0) {
                                $(this).attr('data-state', '1');
                                $('div.box').show();
                            } else {
                                $(this).attr('data-state', '0');
                                $('div.box').hide();
                            }
                        })
                        $('.more_do_ul .manage').click(function() {
                            var m = $(this).attr('data-m');
                            if (m == 1) {
                                $(this).attr('data-m', '0');
                                $('ul li div.right').removeAttr('style');
                                $('ul li div.left').hide();
                                $('.all_box').hide();
                            } else {
                                $(this).attr('data-m', '1');
                                $('ul li div.left').show();
                                $('ul li div.right').css('width', 'calc(100% - 40px)');
                                $('.all_box').show();
                            }
                            $('div.box').removeAttr('style');
                        })
                    </script>
                </div>
            </div>
            <div class="bg2"></div>
        </div>
        <div class="clear"></div>
        <form id="collect_form" action="" method="post">
            <ul class="list">
                <?php foreach ($collect_list as $cl): ?>
                    <li data-url="/resume/detail?resume_id=<?= $cl['resume_id'] ?>">
                        <div class="left">
                            <input type="checkbox" name="did[]" value="<?= $cl['did'] ?>" />
                        </div>
                        <div class="right">
                            <?php if (!empty($cl['fullname'])): ?>
                                <div class="header_box">
                                    <img src="/data/photo/<?= $cl['photo_img'] ?>" width="40" height="40" />
                                </div>
                                <div class="info_box">
                                    <div class="top">
                                        <p class="name"><?= $cl['fullname'] ?></p>
                                        <span class="time"><?= date("Y/m/d H:i:s", $cl['favoritesa_ddtime']) ?></span>
                                    </div>
                                    <div class="bottom">
                                        <?php if ($cl['default_resume'] == 0): ?>
                                            <p class="info"><?= $cl['education_cn'] ?>&nbsp;|&nbsp;<?= $cl['district_cn'] ?>&nbsp;|&nbsp;<?= $cl['experience_cn'] ?></p>
                                        <?php else: ?>
                                            <p class="info">此为附件简历，请至电脑端下载查看</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="no_resume">简历信息不全或已被删除</p>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </form>
        <?php if ($page_arr['totalpage'] > 1): ?>
            <div class="clear"></div>
            <div id="page_box" data-url="/company_center/resume_collect/" data-parameter="">
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
        <?php endif; ?>
        <div class="all_box">
            <input id="check_all_input" class="all_check" type="checkbox" />
            <span class="total">全选<br/><b id="check_total">0</b>/10</span>
            <div class="right">
                <input class="submit" id="delete" type="submit" value="删除" />
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
            $('#delete').click(function() {
                $('#check_all').prop("checked", false);
                $('#collect_form').attr('action', '/company_center/del_collect');
                $('#collect_form').submit();
            })
        </script>
        <div class="clear"></div>
        <script src="<?= VIEW_PATH; ?>js/2019/common.js"></script>
        <?php $this->load->view('public/2019/company_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>