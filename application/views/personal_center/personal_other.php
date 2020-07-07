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
        <title>个人中心-其他信息</title>
    </head>

    <body id="edit_other">
        <form action="/personal_center/save_other" method="post" >
            <ul class="edit_ul">
                <li class="tags">
                    <label class="left">特长标签</label>
                    <div class="right">
                        <span id="tags_span">请选择（最多选择5个）</span>
                        <i></i>
                    </div>
                    <input type="hidden" id="tags_input" name="tag" value="<?php foreach ($tag as $t): ?><?= $t ?>|<?php endforeach; ?>" />
                    <script type="text/javascript">
                        var mobileSelect3 = new MobileSelect({
                            trigger: '#tags_span',
                            title: '特长标签',
                            wheels: [
                                {data: <?= $tag_category ?>}
                            ],
                            position: [0, 0],
                            ensureBtnText: '确定',
                            cancelBtnText: '取消',
                            callback: function(indexArr, data) {
                                var li_length = $("ul.tags_ul li").length;
                                var f = 0;
                                if (li_length == 5) {
                                    alert('最多选择5个标签');
                                    return false;
                                } else {
                                    var tid = data[0].id;
                                    var tid_arr = tid.split('|');
                                    $('ul.tags_ul li').each(function() {
                                        var li_c = $(this).attr('data-id');
                                        if (li_c == tid_arr[0]) {
                                            alert('该标签已添加');
                                            f = 1;
                                        }
                                    })
                                    if (f == 0) {
                                        $('ul.tags_ul').append('<li data-id="' + tid_arr[0] + '"><span>' + tid_arr[1] + '</span><i></i><div class="clear"></div></li>');
                                        make_tags();
                                    }
                                }
                            }
                        });
                    </script>
                    <div class="clear"></div>
                    <ul class="tags_ul">
                        <?php if (!empty($tag)): ?>
                            <?php foreach ($tag as $t3): ?>
                                <?php $t_arr = explode(",", $t3); ?>
                                <li data-id="<?= $t_arr[0] ?>">
                                    <span><?= $t_arr[1] ?></span>
                                    <i></i> 
                                    <div class="clear"></div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </li>
                <li>
                    <label class="left">自我描述</label>
                    <div class="clear"></div>
                    <textarea name="specialty"  placeholder="请输入自我描述"><?= $specialty ?></textarea>
                </li>
            </ul>
            <div class="clear"></div>
            <div class="submit_box">
                <a class="to_next" href="/personal_center/edit_certificate">跳过此项</a>
                <input class="submit" type="submit" value="保存" />
            </div>
        </form>
        <script>
            function make_tags() {
                var jobs_str = "";
                $('ul.tags_ul li').each(function() {
                    var t = $(this).attr('data-id');
                    var t_str = $(this).find('span').html();
                    jobs_str = jobs_str + t + "," + t_str + "|";
                })
                $('input#tags_input').val(jobs_str);
            }
            $('.tags ul.tags_ul').on('click', 'li i', function() {
                $(this).parent().remove();
                make_tags();
            })
        </script>
        <?php $this->load->view('public/2019/bottom_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>

</html>
