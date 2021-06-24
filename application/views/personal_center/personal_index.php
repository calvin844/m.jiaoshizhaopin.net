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
        <title>个人中心</title>
    </head>
    <body id="index">
        <div class="info_box">
            <div class="top">
                <p><?= $resume['fullname'] ?></p>
                <img class="header" src="/data/photo/<?= $resume['photo_img'] ?>" width="80" height="80" />
                <img class="bg" src="<?= VIEW_PATH; ?>images/2019/personal_center/index_top_bg.png" width="100%" />
            </div>
            <div class="bottom">
                <div>
                    <span><?= $resume['complete_percent'] ?>%</span>
                    <p>完整度</p>
                </div>
                <i></i>
                <div>
                    <span><?= $resume['refreshtime'] ?></span>
                    <p>更新</p>
                </div>
                <i></i>
                <div>
                    <span><?= $resume['click'] ?></span>
                    <p>浏览</p>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="function_box">
            <ul>
                <li class="resume">
                    <a href="/personal_center/resume"><i></i><span>简历</span><b></b></a>
                </li>
                <li class="interview">
                    <a href="/personal_center/my_invitation"><i></i><span>面试邀请</span><b></b></a>
                </li>
                <li class="apply">
                    <a href="/personal_center/my_apply_jobs"><i></i><span>已申请职位</span><b></b></a>
                </li>
                <li class="collection">
                    <a href="/personal_center/my_favorites_jobs"><i></i><span>我的收藏</span><b></b></a>
                </li>
                <li class="system">
                    <a href="/personal_center/my_system"><i></i><span>系统设置</span><b></b></a>
                </li>
            </ul>
            <a class="logout" href="/user/logout">
                <i></i><span>退出登录</span>
            </a>
        </div>
        <?php if ($_GET['show_talent'] == 1): ?>
            <div class="clear"></div>
            <div class="talent_box">
                <div class="talent">
                    <img src="<?= VIEW_PATH; ?>images/2019/m_talent_applay.jpg" width="300" />
                    <a class="close" href="javascript:;"></a>
                </div>
                <div class="bg"></div>
                <script>
                    $('.talent_box .bg').click(function() {
                        $('.talent_box').hide();
                    })
                    $('.talent_box .close').click(function() {
                        $('.talent_box').hide();
                    })
                </script>
            </div>
        <?php endif; ?>
        <div class="clear"></div>
        <?php $this->load->view('public/2019/bottom_help.php') ?>
        <?php $this->load->view('public/2019/bottom_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>

</html>
