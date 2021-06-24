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
        <title>�ҵ��˺�</title>
    </head>

    <body id="my_account">
        <div class="top_box">
            <div class="setmeal_box">
                <p class="name"><?= $setmeal['setmeal_name'] ?></p>
                <div class="date_box">
                    <?php if ($setmeal['endtime'] == 0): ?>
                        <span>������</span>
                    <?php else: ?>
                        <span><?= date("Y-m-d", $setmeal['starttime']) ?></span>
                        <i></i>
                        <span><?= date("Y-m-d", $setmeal['endtime']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <ul class="count_list">
            <li>
                <label>������Ƹְλ</label>
                <span><?= $setmeal['jobs_ordinary'] ?>/<?= $setmeal_rule['jobs_ordinary'] ?></span>
            </li>
            <li>
                <label>���ظ߼��˲ż���</label>
                <span><?= $setmeal['download_resume_senior'] ?>/<?= $setmeal_rule['download_resume_senior'] ?></span>
            </li>
            <li>
                <label>����߼��˲�������</label>
                <span><?= $setmeal['interview_senior'] ?>/<?= $setmeal_rule['interview_senior'] ?></span>
            </li>
            <li>
                <label>������ͨ�˲ż���</label>
                <span><?= $setmeal['download_resume_ordinary'] ?>/<?= $setmeal_rule['download_resume_ordinary'] ?></span>
            </li>
            <li>
                <label>������ͨ�˲�������</label>
                <span><?= $setmeal['interview_ordinary'] ?>/<?= $setmeal_rule['interview_ordinary'] ?></span>
            </li>
            <li>
                <label>�˲ſ�����</label>
                <span><?= $setmeal['talent_pool'] ?>/<?= $setmeal_rule['talent_pool'] ?></span>
            </li>
            <li>
                <label>ְλ�ƹ�-�Ƽ�</label>
                <span><?= $setmeal['recommend_num'] ?>/<?= $setmeal_rule['recommend_num'] ?></span>
            </li>
            <li>
                <label>ְλ�ƹ�-����</label>
                <span><?= $setmeal['emergency_num'] ?>/<?= $setmeal_rule['emergency_num'] ?></span>
            </li>
            <li>
                <label>ְλ�ƹ�-�ö�</label>
                <span><?= $setmeal['stick_num'] ?>/<?= $setmeal_rule['stick_num'] ?></span>
            </li>
        </ul>
        <div class="clear"></div>
        <img src="<?= VIEW_PATH; ?>images/2019/company_center/setmeal_img.png" width="100%" />
        <div class="clear"></div>
        <script src="<?= VIEW_PATH; ?>js/2019/common.js"></script>
        <?php $this->load->view('public/2019/bottom_help.php') ?>
        <?php $this->load->view('public/2019/company_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>