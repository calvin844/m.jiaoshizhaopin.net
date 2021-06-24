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
        <script src="<?= VIEW_PATH; ?>js/2019/ajaxfileupload.js" type='text/javascript' language="javascript"></script>
        <title>�ҵļ���</title>
    </head>
    <body id="resume">
        <ul class="list">
            <li class="basic">
                <div class="left">
                    <p><?= $resume['fullname'] ?></p>
                    <a href="/personal_center/edit_basic">�༭������Ϣ</a>
                </div>
                <div class="right">
                    <img src="/data/photo/<?= $resume['photo_img'] ?>" width="80" />
                    <i></i>
                    <input type="file" name="photo" id="photo" class="header_upload" onchange ="uploadFile()" />
                    <script>
                        function uploadFile() {
                            $('div#loading_box').show();
                            $.ajaxFileUpload({
                                url: '/personal_center/up_photo',
                                secureuri: false,
                                fileElementId: 'photo',
                                dataType: 'STRING',
                                success: function(data, status) {
                                    $('div.loading').hide();
                                    var f = data.substr(0, 1);
                                    if (f == "-") {
                                        switch (data) {
                                            case "-1":
                                                alert("�ϴ�ͼƬʧ�ܣ��ϴ�Ŀ¼������!");
                                                break;
                                            case "-2":
                                                alert("�ϴ�ͼƬʧ�ܣ��ϴ�Ŀ¼�޷�д��!");
                                                break;
                                            case "-3":
                                                alert("�ϴ�ͼƬʧ�ܣ���ѡ����ļ��޷��ϴ�");
                                                break;
                                            case "-4":
                                                alert("�ϴ�ͼƬʧ�ܣ��ļ���С��������");
                                                break;
                                            case "-5":
                                                alert("�ϴ�ͼƬʧ�ܣ��ļ����ʹ���");
                                                break;
                                            case "-6":
                                                alert("�ϴ�ͼƬʧ�ܣ��ļ��ϴ�����");
                                                break;
                                            case "-7":
                                                alert("��ˢ��ҳ������ԣ�");
                                                break;
                                            case "-8":
                                                alert("���ϴ�ͼƬ��");
                                                break;
                                            default:
                                                break;
                                        }
                                        window.location.reload();
                                    } else {
                                        alert("�ϴ��ɹ���");
                                        window.location.reload();
                                    }
                                }
                            })
                        }
                    </script>
                </div>
            </li>
            <li class="intention">
                <label class="title">��ְ����</label>
                <div class="right">
                    <span><?= $intention_jobs ?></span>
                    <i></i>
                </div>
                <script>
                    $('li.intention .right').click(function() {
                        window.location.href = "/personal_center/edit_intention"
                    })
                </script>
            </li>
            <li class="education">
                <label class="title">��������</label>
                <a class="right" href="/personal_center/edit_education">���</a>
                <div class="clear"></div>
                <ul class="item">
                    <?php foreach ($educations as $e): ?>
                        <li data-id="<?= $e['id'] ?>">
                            <p class="school_name"><?= $e['school'] ?></p>
                            <p class="speciality"><?= $e['education_cn'] ?>/<?= $e['speciality'] ?></p>
                            <p class="time"><?= $e['startyear'] ?>-<?= $e['startmonth'] ?>~<?= $e['endyear'] ?>-<?= $e['endmonth'] ?></p>
                            <i></i>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <script>
                    $('li.education ul li').click(function() {
                        var eid = $(this).attr('data-id');
                        window.location.href = "/personal_center/edit_education/" + eid;
                    })
                </script>
            </li>
            <li class="work">
                <label class="title">��������</label>
                <a class="right" href="/personal_center/edit_work">���</a>
                <div class="clear"></div>
                <ul class="item">
                    <?php foreach ($works as $w): ?>
                        <li data-id="<?= $w['id'] ?>">
                            <p class="company_name"><?= $w['companyname'] ?></p>
                            <p class="jobs"><?= $w['jobs'] ?></p>
                            <p class="time"><?= $w['startyear'] ?>-<?= $w['startmonth'] ?>~<?= $w['endyear'] ?>-<?= $w['endmonth'] ?></p>
                            <?php if (!empty($w['achievements'])): ?>
                                <p class="achievements_title">����ְ��</p>
                                <p class="achievements">
                                    <?= nl2br($w['achievements']) ?>
                                </p>
                            <?php endif; ?>
                            <i></i>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <script>
                    $('li.work ul li').click(function() {
                        var wid = $(this).attr('data-id');
                        window.location.href = "/personal_center/edit_work/" + wid;
                    })
                </script>
            </li>
            <li class="training">
                <label class="title">��ѵ����</label>
                <a class="right" href="/personal_center/edit_training">���</a>
                <div class="clear"></div>
                <ul class="item">
                    <?php foreach ($trainings as $t): ?>
                        <li data-id="<?= $t['id'] ?>">
                            <p class="agency_name"><?= $t['agency'] ?></p>
                            <p class="course"><?= $t['course'] ?></p>
                            <p class="time"><?= $t['startyear'] ?>-<?= $t['startmonth'] ?>~<?= $t['endyear'] ?>-<?= $t['endmonth'] ?></p>
                            <?php if (!empty($t['description'])): ?>
                                <p class="description_title">��ѵ����</p>
                                <p class="description">
                                    <?= nl2br($t['description']) ?>
                                </p>
                            <?php endif; ?>
                            <i></i>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <script>
                    $('li.training ul li').click(function() {
                        var tid = $(this).attr('data-id');
                        window.location.href = "/personal_center/edit_training/" + tid;
                    })
                </script>
            </li>
            <li class="other">
                <label class="title">������Ϣ</label>
                <a class="right" href="/personal_center/edit_other">�༭</a>
                <div class="clear"></div>
                <ul class="item">

                    <?php if (!empty($tag)): ?>
                        <li>
                            <p class="tag_name">�س���ǩ</p>
                            <p class="tag"><?= $tag ?></p>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($resume['specialty'])): ?>
                        <li>
                            <p class="specialty_name">��������</p>
                            <p class="specialty"><?= nl2br($resume['specialty']) ?></p>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
            <li class="certificate">
                <label class="title">����֤��</label>
                <a class="right" href="/personal_center/edit_certificate">���</a>
                <div class="clear"></div>
                <ul class="item">
                    <?php foreach ($certificates as $c): ?>
                        <li>
                            <img src="/data/resume_certificate/<?= $c['path'] ?>" />
                            <p><?= $c['note'] ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        </ul>
	<div class="clear"></div>
	<div class="show_box">
		<a class="to_next" href="/resume/detail?resume_id=<?= $resume['id'] ?>">Ԥ������</a>
	</div>
        <div id="loading_box">
            <img src="<?= VIEW_PATH; ?>images/loading.gif" width="50" height="50" />
            <div class="clear"></div>
            <p>�����ϴ�</p>
            <div class="bg"></div>
        </div>
        <div class="clear"></div>
        <?php $this->load->view('public/2019/bottom_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
