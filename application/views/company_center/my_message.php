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
        <title>我的消息</title>
    </head>
    <body id="my_message">
        <div class="type_tab_box2">
            <ul class="tab_ul">
                <li class="<?php if ($new == 0): ?>hover<?php endif; ?>">
                    <a href="/company_center/my_message"><span>全部消息</span></a>
                    <i></i>
                </li>
                <li class="<?php if ($new == 1): ?>hover<?php endif; ?>">
                    <a href="/company_center/my_message?new=1"><span>未读消息</span></a>
                    <i></i>
                </li>
                <li class="<?php if ($new == 2): ?>hover<?php endif; ?>">
                    <a href="/company_center/my_message?new=2"><span>已读消息</span></a>
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
        <form id="message_form" action="" method="post">
            <ul class="list">
                <?php foreach ($message_list as $m): ?>
                    <li>
                        <div class="left">
                            <input name="list_id[]" type="checkbox"  value="<?= $m['pmid'] ?>" />
                        </div>
                        <div class="right">
                            <?php if ($m['new'] == 1): ?><i class="no_read"></i><?php endif; ?>
                            <div class="clear"></div>
                            <p><?= $m['message'] ?></p>
                            <div class="clear"></div>
                            <span><?= date('Y/m/d', $m['dateline']) ?></span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </form>
        <div class="clear"></div>
        <?php if ($page_arr['totalpage'] > 1): ?>
            <div class="clear"></div>
            <div id="page_box" data-url="/company_center/my_message/" data-parameter="?new=<?= $new ?>">
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
        <div class="clear"></div><div class="all_box">
            <input id="check_all_input" class="all_check" type="checkbox" />
            <span class="total">全选<br/><b id="check_total">0</b>/10</span>
            <div class="right">
                <input class="button" id="set_look" type="button" value="设为已读" />
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
            $('#set_look').click(function() {
                $('#check_all').prop("checked", false);
                $('#message_form').attr('action', '/company_center/message_set_look');
                $('#message_form').submit();
            })
            $('#delete').click(function() {
                $('#check_all').prop("checked", false);
                $('#message_form').attr('action', '/company_center/message_del');
                $('#message_form').submit();
            })
        </script>
        <div class="clear"></div>
        <script src="<?= VIEW_PATH; ?>js/2019/common.js"></script>
        <?php $this->load->view('public/2019/company_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>

