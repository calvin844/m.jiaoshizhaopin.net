<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/2019/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/resume_css/<?= $tpl_dir ?>/resume.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/2019/jq-1.11.1.js"></script>
        <title><?= $resume['name'] ?>的简历</title>
    </head>

    <body id="resume_detail">
        <div class="resume_box">
            <div class="base_box">
                <div class="left">
                    <h2 class="name"><?= $resume['name'] ?></h2>
                    <span class="district"><?= !empty($resume['residence_cn']) ? $resume['residence_cn'] : "" ?></span>
                    <div class="clear"></div>
                    <p class="base_str"><?= !empty($resume['experience_cn']) ? $resume['experience_cn'] : "未填写" ?>工作经验 | <?= !empty($resume['education_cn']) ? $resume['education_cn'] : "未填写学历" ?> | <?= $resume['age'] ?>岁 | <?= !empty($resume['marriage_cn']) ? $resume['marriage_cn'] : "保密" ?></p>
                    <?php if ($show == 1): ?>
                        <div class="clear"></div>
                        <div class="phone">
                            <i></i><p><?= $resume['telephone'] ?></p>
                        </div>
                        <div class="clear"></div>
                        <div class="email">
                            <i></i><p><?= $resume['email'] ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="right">
                    <img src="<?= $resume['photosrc'] ?>" width="90" />
                    <i class="<?php if ($resume['sex'] == 1): ?>man<?php else: ?>woman<?php endif; ?>"></i>
                </div>
            </div>
            <div class="clear"></div>
            <div class="intention_box">
                <p class="box_title">求职意向</p>
                <div class="box">
                    <?php if (!empty($resume['nature_cn'])): ?>
                        <div class="nature">
                            <i></i>
                            <span><?= $resume['nature_cn'] ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($resume['district_cn'])): ?>
                        <div class="district">
                            <i></i>
                            <span><?= $resume['district_cn'] ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($resume['wage_cn'])): ?>
                        <div class="wage">
                            <i></i>
                            <span><?= $resume['wage_cn'] ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($resume['intention_jobs'])): ?>
                        <div class="clear"></div>
                        <ul class="jobs_ul">
                            <?php foreach ($resume['intention_jobs'] as $i): ?>
                                <li><?= $i ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!empty($resume['education_list'])): ?>
                <div class="clear"></div>
                <div class="education_box">
                    <p class="box_title">教育经历</p>
                    <div class="clear"></div>
                    <?php foreach ($resume['education_list'] as $re): ?>
                        <div class="box">
                            <p class="school"><?= $re['school'] ?></p>
                            <div class="clear"></div>
                            <p class="speciality"><?= $re['education_cn'] ?>/<?= $re['speciality'] ?></p>
                            <div class="clear"></div>
                            <p class="time"><?= $re['startyear'] ?>-<?= $re['startmonth'] ?>~<?= $re['endyear'] ?>-<?= $re['endmonth'] ?></p>
                            <div class="clear"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($resume['work_list'])): ?>
                <div class="clear"></div>
                <div class="work_box">
                    <p class="box_title">工作经历</p>
                    <div class="clear"></div>
                    <?php foreach ($resume['work_list'] as $rw): ?>
                        <div class="box">
                            <p class="company"><?= $rw['companyname'] ?></p>
                            <div class="clear"></div>
                            <p class="jobs"><?= $rw['jobs'] ?></p>
                            <div class="clear"></div>
                            <p class="time"><?= $rw['startyear'] ?>-<?= $rw['startmonth'] ?>~<?= $rw['endyear'] ?>-<?= $rw['endmonth'] ?></p>
                            <div class="clear"></div>
                            <?php if (!empty($rw['achievements'])): ?>
                                <p class="achievements_title">工作职责</p>
                                <div class="clear"></div>
                                <p class="achievements"><?= nl2br($rw['achievements']) ?></p>
                            <?php endif; ?>
                            <div class="clear"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($resume['training_list'])): ?>
                <div class="clear"></div>
                <div class="training_box">
                    <p class="box_title">培训经历</p>
                    <div class="clear"></div>
                    <?php foreach ($resume['training_list'] as $rt): ?>
                        <div class="box">
                            <p class="agency"><?= $rt['agency'] ?></p>
                            <div class="clear"></div>
                            <p class="course"><?= $rt['course'] ?></p>
                            <div class="clear"></div>
                            <p class="time"><?= $rt['startyear'] ?>-<?= $rt['startmonth'] ?>~<?= $rt['endyear'] ?>-<?= $rt['endmonth'] ?></p>
                            <div class="clear"></div>
                            <?php if (!empty($rt['description'])): ?>
                                <p class="description_title">培训内容</p>
                                <div class="clear"></div>
                                <p class="description"><?= nl2br($rt['description']) ?></p>
                                <div class="clear"></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($resume['tag']) || !empty($resume['specialty'])): ?>
                <div class="clear"></div>
                <div class="other_box">
                    <p class="box_title">其他信息</p>
                    <?php if (!empty($resume['tag'])): ?>
                        <ul class="tag_ul">
                            <?php foreach ($resume['tag'] as $t): ?>
                                <li><?= $t ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="clear"></div>
                    <?php endif; ?>
                    <div class="clear"></div>
                    <?php if (!empty($resume['specialty'])): ?>
                        <p class="specialty_title">自我描述</p>
                        <div class="clear"></div>
                        <p class="specialty"><?= nl2br($resume['specialty']) ?></p>
                    <?php endif; ?>
                    <div class="clear"></div>
                </div>
            <?php endif; ?>
            <?php if (!empty($resume['certificate_list'])): ?>
                <div class="clear"></div>
                <div class="certificate_box">
                    <p class="box_title">已有证书</p>
                    <ul class="list">
                        <?php foreach ($resume['certificate_list'] as $rc): ?>
                            <li>
                                <div class="img_box">
                                    <a href="/data/resume_certificate/<?= $rc['path'] ?>">
                                        <img src="/data/resume_certificate/<?= $rc['path'] ?>" />
                                    </a>
                                </div>
                                <p><?= $rc['note'] ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <div class="clear"></div>
        </div>
        <?php $this->load->view('public/2019/bottom_help_home.php') ?>
        <?php if ($utype == 1): ?>
            <div class="do_box">
                <form id="do_form" action="" method="post">
                    <input type="button" class="invitation" value="面试邀请" />
                    <div class="right">
                        <input type="hidden" name="did" value="<?= $resume['id'] ?>" />
                        <input class="down" type="button" value="下载" />
                        <input class="collect" type="button" value="收藏" />
                    </div>
                </form>
            </div>
            <div class="interview_box">
                <form action="/company_center/resume_to_interview" method="post">
                    <input type="hidden" id="job_total" value="<?= $job_total ?>" />
                    <input type="hidden" name="did" value="<?= $resume['id'] ?>" />
                    <div class="box">
                        <label>邀请职位：</label>
                        <select name="jobs_id">
                            <?php foreach ($job_list as $jl): ?>
                                <option value="<?= $jl['id'] ?>"><?= $jl['jobs_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="clear"></div>
                        <label>备注：</label>
                        <div class="clear"></div>
                        <textarea name="notes"></textarea>
                        <div class="clear"></div>
                        <input class="submit" type="submit" value="确定" />
                    </div>
                </form>
                <div class="bg"></div>
            </div>
            <script>
                $('.do_box .down').click(function() {
                    $('#do_form').attr('action', '/company_center/resume_to_down');
                    $('#do_form').submit();
                })
                $('.do_box .collect').click(function() {
                    $('#do_form').attr('action', '/company_center/resume_to_collect/1');
                    $('#do_form').submit();
                })
                $('.do_box .invitation').click(function() {
                    var job_total = $('#job_total').val();
                    if (job_total < 1) {
                        alert('您没有有效的职位');
                        return false;
                    }
                    $('#resume_detail').css('overflow', 'hidden');
                    $('.do_box').hide();
                    $('.interview_box').show();
                })
                $('.interview_box .bg').click(function() {
                    $('#resume_detail').removeAttr('style');
                    $('.do_box').show();
                    $('.interview_box').hide();
                });
            </script>
        <?php endif; ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
