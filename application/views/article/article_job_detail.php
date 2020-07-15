<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/2019/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/article.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/2019/jq-1.11.1.js"></script>
        <title><?= $job['job_name'] ?></title>
        <meta name="keywords" content="<?= $job['job_name'] ?>招聘,<?= $article['sdistrict_cn'] ?>教师招聘,<?= $article['sdistrict_cn'] ?><?= $job['category_cn'] ?>招聘,<?= $article['sdistrict_cn'] ?>教师招聘网"/>
        <meta name="description" content="<?= $article['title'] ?>,<?= $article['sdistrict_cn'] ?>招聘<?= $job['job_name'] ?>"/>
    </head>

    <body id="article_job_detail">
        <?php $this->load->view('public/2019/header.php') ?>
        <?php if (!empty($ad['m_page_401'])): ?>
            <div class="clear"></div>
            <a href="http://www.jiaoshizhaopin.net/ad_count/index.php?ad_name=m_page_401&img=<?= $ad['m_page_401'][0]['img_path'] ?>&url=<?= $ad['m_page_401'][0]['img_url'] ?>" target="_blank"><img src="http://www.jiaoshizhaopin.net<?= $ad['m_page_401'][0]['img_path'] ?>" width="100%"/></a>
            <div class="clear"></div>
        <?php endif; ?>
        <div class="title_box">
            <h1><?= $job['job_name'] ?></h1>
            <div class="info">
                <ul>
                    <li class="district"><i></i><span><?= $article['district_cn'] ?></span></li>
                    <li class="amount"><i></i><span><?= $job['amount'] ?>人</span></li>
                </ul>
                <div class="clear"></div>
                <div class="click"><p><?= $article['click'] ?>人阅读</p><p><?= date("Y-m-d", $article['endtime']) ?>截止</p></div>
            </div>
        </div>
        <div class="content_box">
            <div class="article_box" onclick="window.location.href = '<?= $article['url'] ?>'">
                <div class="article">
                    <h2><?= $article['title'] ?></h2>
                </div>
                <div class="more">
                    <a title="<?= $article['title'] ?>" href="<?= $article['url'] ?>"></a>
                </div>
            </div>
            <div class="clear"></div>
            <div class="article_content_box">
                <p class="box_title"><i></i>简章内容</p>
                <div class="clear"></div>
                <div class="box_content">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td><?= $article['content'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if (!empty($other_job)): ?>
                <div class="other_jobs_box">			
                    <div class="other_title_box">
                        <div class="line"></div>
                        <p class="title">其他职位推荐</p>
                    </div>
                    <ul>
                        <?php foreach ($other_job as $ot): ?>
                            <li onclick="window.location.href = '/article/article_jobs?article_job_id=<?= $ot['id'] ?>'">
                                <div class="job_box">
                                    <h3 class="left"><a title="<?= $article['sdistrict_cn'] ?><?= $ot['category_cn'] ?>招聘" href="/article/article_jobs?article_job_id=<?= $ot['id'] ?>"><?= strlen($ot['job_name']) > 20 ? mb_substr($ot['job_name'], 0, 10, "gb2312") . '...' : $ot['job_name'] ?></a></h3>
                                    <span class="right"><?= date("Y-m-d", $article['endtime']) ?></span>
                                </div>
                                <div class="clear"></div>
                                <p><?= $article['district_cn'] ?>/<?= $article['sdistrict_cn'] ?>&nbsp;|&nbsp;<?= $ot['amount'] ?>人</p>
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
        <div class="delivery_box" data-id="<?= $article['id'] ?>">
            <div class="service">
                <a href="/welcome/service">
                    <i></i>
                </a>
            </div>
            <div id="shareBtn" class="share">
                <i></i>
                <span>分享</span>
            </div>
            <div class="favorites <?php if (!empty($article_favorite)): ?>hover<?php endif; ?>" data-favorites="<?php if (!empty($article_favorite)): ?>1<?php else: ?>0<?php endif; ?>">
                <i></i>
                <span>收藏</span>
            </div>
            <div class="delivery <?php if (empty($article['email']) || $article['join_way'] != "邮箱" || $article['endtime'] < time()): ?>disable<?php endif; ?>">
                <input id="delivery_job" type="button" onclick="show_select('选择职位', 'get_article_job/<?= $article['id'] ?>', 'delivery_job')" value="<?php if (empty($article['email']) || $article['join_way'] != "邮箱" || $article['endtime'] < time()): ?>不支持投递<?php else: ?>投递简历<?php endif; ?>"/>
            </div>
        </div>
        <form id="article_form" action="" method="post">
            <input type="hidden" name="article_id" value="<?= $article['id'] ?>" />
            <input type="hidden" name="article_job_id" id="article_job_id" value="<?= $job['id'] ?>" />
        </form>
        <script src="<?= VIEW_PATH; ?>js/soshm.min.js"></script>
        <script>
                    document.getElementById('shareBtn').addEventListener('click', function() {
                        soshm.popIn({
                            title: '<?= $article['title'] ?>',
                            sites: ['weixin', 'weixintimeline', 'weibo', 'qzone', 'tqq', 'qq']
                        });
                    }, false);
                    $('.delivery_box .favorites').click(function() {
                        var fav = $(this).attr('data-favorites');
                        var str = ''
                        if (fav == '1') {
                            str = 'del_'
                        }
                        $('#article_form').attr('action', '/article/' + str + 'favorites_article');
                        $('#article_form').submit();

                    })
                    $('.delivery_box .delivery').click(function() {
                        $('#article_form').attr('action', '/article/apply_article_send');
                        $('#article_form').submit();
                    })
        </script>
        <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
        <script>
                    $(function() {
                        var url = location.href.split('#').toString();//url不能写死
                        var job_id = $('#article_job_id').attr('value');
                        $.ajax({
                            type: "get",
                            url: "/welcome/wx_share",
                            dataType: "json",
                            async: false,
                            data: {type: 'article_job', id: job_id, url: url},
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
