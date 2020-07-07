<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/2019/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/resume.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/mobileSelect.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/2019/jq-1.11.1.js"></script>
        <script src="<?= VIEW_PATH; ?>js/2019/mobileSelect.min.js" type='text/javascript' language="javascript"></script>
        <title>人才搜索</title>
    </head>

    <body id="resume_list">
        <div class="top">
            <div class="keyword_box">
                <div class="keyword">
                    <form id="resume_search" action="" method="get">
                        <input name="key" type="text"class="keyword" placeholder="请输入关键字" value="<?= $key ?>" />
                        <input id="district" name="district" type="hidden" value="<?= $district['id'] ?>" />
                        <input id="job_type" name="job_type" type="hidden" value="<?= $job_type['id'] ?>" />
                        <input id="education" name="education" type="hidden" value="<?= $education ?>" />
                        <input id="experience" name="experience" type="hidden" value="<?= $experience ?>" />
                        <input id="resume_photo" name="resume_photo" type="hidden" value="<?= $resume_photo ?>" />
                        <input id="resume_type" name="resume_type" type="hidden" value="<?= $resume_type ?>" />
                        <input type="submit" class="keyword_submit" value="" />    
                    </form>
                </div>
                <div class="manage">
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
            <div class="conditions_box">
                <ul>
                    <li>
                        <span id="district_span" class="<?php if (!empty($district['categoryname'])): ?>hover<?php endif; ?>"><?php if (!empty($district['categoryname'])): ?><?= $district['categoryname'] ?><?php else: ?>地区<?php endif; ?></span>
                        <script type="text/javascript">
                            var mobileSelect = new MobileSelect({
                                trigger: '#district_span',
                                title: '地区',
                                wheels: [
                                    {data: <?= $district_categories ?>}
                                ],
                                position: [0, 0],
                                ensureBtnText: '确定',
                                cancelBtnText: '取消',
                                callback: function(indexArr, data) {
                                    var d = data[0].id;
                                    var sd = data[1].id;
                                    var d_arr = d.split("|");
                                    var sd_arr = sd.split("|");
                                    if (sd_arr[0] > 0) {
                                        d = sd_arr[0];
                                    } else {
                                        d = d_arr[0];
                                    }
                                    $('input#district').val(d);
                                    $('#resume_search').submit();
                                }
                            });
                        </script>
                    </li>
                    <li>
                        <span id="jobs_span" class="<?php if (!empty($job_type['categoryname'])): ?>hover<?php endif; ?>"><?php if (!empty($job_type['categoryname'])): ?><?= $job_type['categoryname'] ?><?php else: ?>职位<?php endif; ?></span>
                        <script type="text/javascript">
                            var mobileSelect2 = new MobileSelect({
                                trigger: '#jobs_span',
                                title: '职位',
                                wheels: [
                                    {data: <?= $jobs_categories ?>}
                                ],
                                position: [0, 0],
                                ensureBtnText: '确定',
                                cancelBtnText: '取消',
                                callback: function(indexArr, data) {
                                    var d = data[0].id;
                                    var sd = data[1].id;
                                    var d_arr = d.split("|");
                                    var sd_arr = sd.split("|");
                                    if (sd_arr[0] > 0) {
                                        d = sd_arr[0];
                                    } else {
                                        d = d_arr[0];
                                    }
                                    $('input#job_type').val(d);
                                    $('#resume_search').submit();
                                }
                            });
                        </script>
                    </li>
                    <li>
                        <span id="education_span" class="<?php if ($education_data['c_id'] > 0): ?>hover<?php endif; ?>"><?php if (!empty($education_data['c_name'])): ?><?= $education_data['c_name'] ?><?php else: ?>学历<?php endif; ?></span>
                        <script type="text/javascript">
                            var mobileSelect3 = new MobileSelect({
                                trigger: '#education_span',
                                title: '学历',
                                wheels: [
                                    {data: <?= $education_categories ?>}
                                ],
                                position: [0],
                                ensureBtnText: '确定',
                                cancelBtnText: '取消',
                                callback: function(indexArr, data) {
                                    var v = data[0].id;
                                    $('input#education').val(v);
                                    $('#resume_search').submit();
                                }
                            });
                        </script>
                    </li>
                    <li>
                        <span id="experience_span" class="<?php if ($experience_data['c_id'] > 0): ?>hover<?php endif; ?>"><?php if (!empty($experience_data['c_name'])): ?><?= $experience_data['c_name'] ?><?php else: ?>经验<?php endif; ?></span>
                        <script type="text/javascript">
                            var mobileSelect4 = new MobileSelect({
                                trigger: '#experience_span',
                                title: '经验',
                                wheels: [
                                    {data: <?= $experience_categories ?>}
                                ],
                                position: [0],
                                ensureBtnText: '确定',
                                cancelBtnText: '取消',
                                callback: function(indexArr, data) {
                                    var v = data[0].id;
                                    $('input#experience').val(v);
                                    $('#resume_search').submit();
                                }
                            });
                        </script>
                    </li>
                    <li>
                        <span id="other_span" class="<?php if (!empty($other_data)): ?>hover<?php endif; ?>">其他</span>
                        <script type="text/javascript">
                            var mobileSelect3 = new MobileSelect({
                                trigger: '#other_span',
                                title: '其他',
                                wheels: [
                                    {data: <?= $talent_categories ?>},
                                    {data: <?= $photo_categories ?>}
                                ],
                                position: [0, 0],
                                ensureBtnText: '确定',
                                cancelBtnText: '取消',
                                callback: function(indexArr, data) {
                                    var t = data[0].id;
                                    var p = data[1].id;
                                    $('input#resume_type').val(t);
                                    $('input#resume_photo').val(p);
                                    $('#resume_search').submit();
                                }
                            });
                        </script>
                    </li>
                </ul>
            </div>
        </div>
        <div class="clear"></div>

        <form id="list_form" action="/company_center/resume_to_collect/1" method="post">
            <ul class="list">
                <?php foreach ($resume_list as $rl): ?>
                    <li data-url="/resume/detail?resume_id=<?= $rl['id'] ?>">
                        <div class="left">
                            <input name="did[]" type="checkbox"  value="<?= $rl['id'] ?>" />
                        </div>
                        <div class="right">
                            <div class="top">
                                <p><?= $rl['name'] ?><?php if ($rl['talent'] == 2): ?><i class="vip"></i><?php endif; ?></p>
                                <span><?= date('Y/m/d', $rl['refreshtime']) ?></span>
                            </div>
                            <div class="bottom">
                                <div class="header">
                                    <img src="/data/photo/<?= $rl['photo_img'] ?>" width="60" height="60" />
                                </div>
                                <div class="info">
                                    <p class="info"><?= $rl['district_cn'] ?>&nbsp;|&nbsp;<?= $rl['education_cn'] ?>&nbsp;|&nbsp;<?= $rl['experience_cn'] ?></p>
                                    <p class="jobs"><span>期望职位：</span><?= $rl['intention_jobs'] ?></p>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </form>
        <?php if ($page_arr['totalpage'] > 1): ?>
            <div class="clear"></div>
            <div id="page_box" data-url="/resume/index/" data-parameter="key=<?= $key ?>&district=<?= $district['id'] ?>&job_type=<?= $job_type['id'] ?>&education=<?= $education ?>&experience=<?= $experience ?>&resume_photo=<?= $resume_photo ?>&resume_type=<?= $resume_type ?>">
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
            <div class="right">
                <input id="collect" class="submit" type="submit" value="收藏" />
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
            $('#collect').click(function() {
                $('#check_all').prop("checked", false);
                $('#list_form').submit();
            })
        </script>
        <script src="<?= VIEW_PATH; ?>js/2019/common.js"></script>
        <?php $this->load->view('public/2019/bottom_help.php') ?>
        <?php $this->load->view('public/2019/company_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>