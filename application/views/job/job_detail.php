<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/2019/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/job.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/2019/jq-1.11.1.js"></script>
        <title><?= $job['jobs_name'] ?></title>
        <meta name="keywords" content="<?= $seo_keywords ?>"/>
        <meta name="description" content="<?= $seo_description ?>"/>
    </head>

    <body id="job_detail">
        <?php $this->load->view('public/2019/header.php') ?>
        <?php if (!empty($ad['m_page_401'])): ?>
            <div class="clear"></div>
            <a href="http://www.jiaoshizhaopin.net/ad_count/index.php?ad_name=m_page_401&img=<?= $ad['m_page_401'][0]['img_path'] ?>&url=<?= $ad['m_page_401'][0]['img_url'] ?>" target="_blank"><img src="http://www.jiaoshizhaopin.net<?= $ad['m_page_401'][0]['img_path'] ?>" width="100%"/></a>
            <div class="clear"></div>
        <?php endif; ?>
        <div class="title_box">
            <h1><?= $job['jobs_name'] ?></h1>
            <div class="wage">
                <p><?= $job['wage_cn'] ?></p>
            </div>
            <div class="info">
                <ul>
                    <li class="district"><i></i><span><?= $job['district_cn'] ?></span></li>
                    <li class="experience"><i></i><span><?= $job['experience_cn'] ?></span></li>
                    <?php if ($job['education_cn'] != "不限"): ?>
                        <li class="education"><i></i><span><?= $job['education_cn'] ?></span></li>
                    <?php endif; ?>
                    <?php if ($job['amount'] > 0): ?>
                        <li class="amount"><i></i><span><?= $job['amount'] ?>人</span></li>
                    <?php endif; ?>
                </ul>
                <div class="clear"></div>
                <div class="click"><p><?= $job['click'] ?>人阅读</p><p><?= $job['deadline_cn'] ?>截止</p></div>
            </div>
        </div>
        <div class="content_box">
            <div class="company_box" onclick="window.location.href = '/company/detail?company_id=<?= $job['company_id'] ?>'">
                <div class="left">
                    <div class="logo">
                        <?php if (empty($company['logo'])): ?>
                            <img alt="<?= $job['companyname'] ?>" src="<?= VIEW_PATH; ?>images/2019/no_logo.png" />
                        <?php else: ?>
                            <img alt="<?= $job['companyname'] ?>" src="/data/logo/<?= $company['logo'] ?>" />
                        <?php endif; ?>
                    </div>
                </div>
                <div class="company">
                    <h2><?= $job['companyname'] ?></h2>
                    <p><?= !empty($company['nature_cn']) ? $company['nature_cn'] : '性质未填写' ?>&nbsp;|&nbsp;<?= !empty($company['scale_cn']) ? $company['scale_cn'] : '规模未填写' ?>&nbsp;|&nbsp;<?= !empty($company['district_cn']) ? $company['district_cn'] : '地区未填写' ?></p>
                </div>
                <div class="more">
                    <a title="<?= $job['companyname'] ?>" href="/company/detail?company_id=<?= $job['company_id'] ?>"></a>
                </div>
            </div>
            <div class="clear"></div>
            <ul class="tag_box">
                <?php foreach ($job['tag_arr'] as $t): ?>
                    <li><p><?= $t ?></p></li>
                <?php endforeach; ?>
            </ul>
            <div class="clear"></div>
            <div class="job_content_box">
                <p class="box_title"><i></i>职位详情</p>
                <div class="clear"></div>
                <div class="box_content">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td><?= nl2br($job['contents']) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if (!empty($other_jobs)): ?><div class="other_jobs_box">			
                    <div class="other_title_box">
                        <div class="line"></div>
                        <p class="title">其他职位推荐</p>
                    </div>
                    <ul>
                        <?php foreach ($other_jobs as $ot): ?>
                            <li onclick="window.location.href = '/job/detail?job_id=<?= $ot['id'] ?>'">
                                <div class="job_box">
                                    <h3 class="left"><a title="<?= $ot['companyname'] ?><?= $ot['jobs_name'] ?>招聘" href="/job/detail?job_id=<?= $ot['id'] ?>"><?= strlen($ot['jobs_name']) > 20 ? mb_substr($ot['jobs_name'], 0, 10, "gb2312") . '...' : $ot['jobs_name'] ?></a></h3>
                                    <span class="right"><?= $ot['wage_cn'] ?></span>
                                </div>
                                <div class="clear"></div>
                                <p><?= $ot['info_str'] ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="clear"></div>
                    <div class="more_jobs">
                        <a title="更多职位推荐" href="/job"><span>更多职位推荐</span><i></i></a>
                    </div>
                </div>
            <?php endif; ?>
            <div class="clear"></div>
        </div>
        <?php if (!empty($ad['m_page_402'])): ?>
            <div class="clear"></div>
            <a href="http://www.jiaoshizhaopin.net/ad_count/index.php?ad_name=m_page_402&img=<?= $ad['m_page_402'][0]['img_path'] ?>&url=<?= $ad['m_page_402'][0]['img_url'] ?>" target="_blank"><img src="http://www.jiaoshizhaopin.net<?= $ad['m_page_402'][0]['img_path'] ?>" width="100%"/></a>
            <div class="clear"></div>
        <?php endif; ?>
        <?php $this->load->view('public/2019/bottom_help_home.php') ?>
        <div data-id="<?= $job['id'] ?>" class="delivery_box">
            <div class="service">
                <a href="/welcome/service">
                    <i></i>
                </a>
            </div>
            <div id="shareBtn" class="share">
                <i></i>
                <span>分享</span>
            </div>
            <div class="favorites <?php if (!empty($job_favorite)): ?>hover<?php endif; ?>" data-favorites="<?php if (!empty($job_favorite)): ?>1<?php else: ?>0<?php endif; ?>">
                <i></i>
                <span>收藏</span>
            </div>
            <div class="delivery <?php if (!empty($personal_job_apply)): ?>disable<?php endif; ?>" data-delivery="<?php if (!empty($personal_job_apply)): ?>1<?php else: ?>0<?php endif; ?>">
                <input type="button" value="<?php if (!empty($personal_job_apply)): ?>已投递<?php else: ?>投递简历<?php endif; ?>"/>
            </div>
        </div>
        <form id="job_form" action="" method="post">
            <input type="hidden" name="job_id" value="<?= $job['id'] ?>" />
        </form>
        <script>
            $('.delivery_box .favorites').click(function() {
                var fav = $(this).attr('data-favorites');
                var str = ''
                if (fav == '1') {
                    str = 'del_'
                }
                $('#job_form').attr('action', '/job/' + str + 'favorites_job');
                $('#job_form').submit();
            })
            $('.delivery_box .delivery').click(function() {
                var del = $(this).attr('data-delivery');
                var str = ''
                if (del == '0') {
                    $('#job_form').attr('action', '/job/apply_job');
                    $('#job_form').submit();
                }
            })
        </script>
        <script src="<?= VIEW_PATH; ?>js/soshm.min.js"></script>
        <script>
            document.getElementById('shareBtn').addEventListener('click', function() {
                soshm.popIn({
                    title: '<?= $job['jobs_name'] ?>',
                    sites: ['weixin', 'weixintimeline', 'weibo', 'qzone', 'tqq', 'qq']
                });
            }, false);
        </script>
        <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
        <script>
            $(function() {
                var url = location.href.split('#').toString();//url不能写死
                var job_id = $('.delivery_box').attr('data-id');
                $.ajax({
                    type: "get",
                    url: "/welcome/wx_share",
                    dataType: "json",
                    async: false,
                    data: {type: 'job', id: job_id, url: url},
                    success: function(data) {
                        wx.config({
                            appId: data.appid,
                            timestamp: data.timestamp,
                            nonceStr: data.noncestr,
                            signature: data.signature,
                            jsApiList: [
                                'onMenuShareTimeline',
                                'onMenuShareAppMessage',
                                'onMenuShareQQ',
                                'onMenuShareWeibo'
                            ]
                        });
                        wx.ready(function() {
                            var desc = data.des;
                            var title = data.title;
                            wx.onMenuShareTimeline({
                                title: title, // 分享标题
                                link: data.url, // 分享链接
                                imgUrl: data.imgurl, // 分享图标
                                success: function() {
                                },
                                cancel: function() {
                                    // 用户取消分享后执行的回调函数
                                }
                            });
                            wx.onMenuShareAppMessage({
                                title: title, // 分享标题
                                desc: desc, // 分享描述
                                link: data.url, // 分享链接
                                imgUrl: data.imgurl, // 分享图标
                                type: '', // 分享类型,music、video或link，不填默认为link
                                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                                success: function() {
                                },
                                cancel: function() {
                                    // 用户取消分享后执行的回调函数
                                }
                            });
                            wx.onMenuShareQQ({
                                title: title, // 分享标题
                                desc: desc, // 分享描述
                                link: data.url, // 分享链接
                                imgUrl: data.imgurl, // 分享图标
                                success: function() {
                                },
                                cancel: function() {
                                    // 用户取消分享后执行的回调函数
                                }
                            });
                            wx.onMenuShareWeibo({
                                title: title, // 分享标题
                                desc: desc, // 分享描述
                                link: data.url, // 分享链接
                                imgUrl: data.imgurl, // 分享图标
                                success: function() {
                                },
                                cancel: function() {
                                    // 用户取消分享后执行的回调函数
                                }
                            });
                            wx.error(function(res) {
                                alert(res.errMsg);
                            });
                        });
                    },
                    error: function(xhr, status, error) {
                        //alert(status);
                        //alert(xhr.responseText);
                    }
                })
            })
        </script>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
