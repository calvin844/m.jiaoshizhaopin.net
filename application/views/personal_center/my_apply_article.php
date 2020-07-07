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
        <title>已申请的职位</title>
    </head>
    <body id="my_apply_jobs">
        <div class="type_tab_box">
            <div class="jobs">
                <a href="/personal_center/my_apply_jobs"><span>企业职位</span></a>
                <i></i>
            </div>
            <div class="article hover">
                <a href="/personal_center/my_apply_article"><span>简章职位</span></a>
                <i></i>
            </div>
        </div>
        <div class="clear"></div>
        <ul class="list">
            <?php if (!empty($apply_article_list)): ?>
                <?php foreach ($apply_article_list as $aal): ?>
                    <li>
                        <a class="job_name" href="/article/detail?article_id=<?= $aal['article_id'] ?>"><?= !empty($aal['job_name']) ? $aal['job_name'] : "简章职位" ?></a>
                        <span class="time"><?= date('Y-m-d', $aal['apply_addtime']) ?></span>
                        <p class="p_name"><?= $aal['title'] ?></p>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <div id="empty_box">
                    <i></i>
                    <p>目前没有已申请的简章职位！<a href="/article">去申请招聘简章职位</a></p>
                </div>
            <?php endif; ?>
        </ul>
        <script>
            $('ul.list li').click(function() {
                var url = $(this).find('a').attr('href');
                window.location.href = url;
            })
        </script>
        <?php if ($page_arr['totalpage'] > 1): ?>
            <div class="clear"></div>
            <div id="page_box" data-url="/personal_center/my_apply_article/" data-parameter="">
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
        <script src="<?= VIEW_PATH; ?>js/common.js"></script>
        <?php $this->load->view('public/2019/bottom_help.php') ?>
        <?php $this->load->view('public/2019/bottom_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
