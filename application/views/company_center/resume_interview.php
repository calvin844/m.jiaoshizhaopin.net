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
        <script src="<?= VIEW_PATH; ?>js/2019/mobileSelect.min.js" type='text/javascript' language="javascript"></script>
        <title>面试邀请</title>
    </head>

    <body id="interview">
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
                <li>
                    <a href="/company_center/resume_collect"><span>我的收藏</span></a>
                    <i></i>
                </li>
                <li class="hover">
                    <a href="/company_center/resume_interview"><span>面试邀请</span></a>
                    <i></i>
                </li>
                <!--
                <li>
                    <a href="/company_center/resume_apply"><span>悬赏简历</span></a>
                    <i></i>
                </li>
                -->
            </ul>
            <div class="more_do" data-state="0">
                <div class="box">
                    <div class="bg"></div>
                    <ul class="more_do_ul">
                        <li class="manage" data-m="0">
                            <i></i><span>管理</span>
                        </li>
                        <li class="jobs">
                            <i></i><span id="jobs_span">按职位筛选</span>
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
                        var mobileSelect3 = new MobileSelect({
                            trigger: '#jobs_span',
                            title: '选择职位',
                            wheels: [
                                {data: <?= $job_list ?>}
                            ],
                            position: [0],
                            ensureBtnText: '确定',
                            cancelBtnText: '取消',
                            callback: function(indexArr, data) {
                                var v = data[0].id;
                                window.location.href = '?job_id=' + v;
                            }
                        });</script>
                </div>
            </div>
            <div class="bg2"></div>
        </div>
        <div class="clear"></div>
        <form id="invitation_form" action="" method="post">
            <ul class="list">
                <?php foreach ($interview_list as $il): ?>
                    <li data-url="/resume/detail?resume_id=<?= $il['resume_id'] ?>">
                        <div class="left">
                            <input type="checkbox" name="did[]" value="<?= $il['did'] ?>" />
                        </div>
                        <div class="right">
                            <div class="header_box">
                                <img src="/data/photo/<?= $il['photo_img'] ?>" width="40" height="40" />
                                <?php if ($il['personal_look'] != 2): ?>
                                    <div class="no_look"></div>
                                <?php endif; ?>
                            </div>
                            <div class="info_box">
                                <div class="top">
                                    <p class="name"><?= $il['fullname'] ?></p>
                                    <span class="time"><?= date('Y/m/d H:i:s', $il['interview_addtime']) ?></span>
                                </div>
                                <div class="bottom">
                                    <p class="job"><?= $il['jobs_name'] ?></p>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </form>
        <?php if ($page_arr['totalpage'] > 1): ?>
            <div class="clear"></div>
            <div id="page_box" data-url="/company_center/resume_interview/" data-parameter="">
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
                $('#invitation_form').attr('action', '/company_center/del_invitation');
                $('#invitation_form').submit();
            })
        </script>
        <div class="clear"></div>
        <script src="<?= VIEW_PATH; ?>js/2019/common.js"></script>
        <?php $this->load->view('public/2019/company_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>