<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/2019/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/company.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/2019/jq-1.11.1.js"></script>
        <script type="text/javascript" src="<?= VIEW_PATH; ?>js/2019/jquery.event.drag-1.5.min.js"></script>
        <script type="text/javascript" src="<?= VIEW_PATH; ?>js/2019/jquery.touchSlider.js"></script>
        <title><?= $company['companyname'] ?></title>
        <meta name="keywords" content="<?= $seo_keywords ?>"/>
        <meta name="description" content="<?= $seo_description ?>"/>
    </head>

    <body id="company_detail">
        <script type="text/javascript">
            $(document).ready(function() {

                $(".main_visual").hover(function() {
                    $("#btn_prev,#btn_next").fadeIn()
                }, function() {
                    $("#btn_prev,#btn_next").fadeOut()
                });

                $dragBln = false;

                $(".main_image").touchSlider({
                    flexible: true,
                    speed: 200,
                    btn_prev: $("#btn_prev"),
                    btn_next: $("#btn_next"),
                    paging: $(".flicking_con a"),
                    counter: function(e) {
                        $(".flicking_con a").removeClass("on").eq(e.current - 1).addClass("on");
                    }
                });

                $(".main_image").bind("mousedown", function() {
                    $dragBln = false;
                });

                $(".main_image").bind("dragstart", function() {
                    $dragBln = true;
                });

                $(".main_image a").click(function() {
                    if ($dragBln) {
                        return false;
                    }
                });

                timer = setInterval(function() {
                    $("#btn_next").click();
                }, 5000);

                $(".main_visual").hover(function() {
                    clearInterval(timer);
                }, function() {
                    timer = setInterval(function() {
                        $("#btn_next").click();
                    }, 5000);
                });

                $(".main_image").bind("touchstart", function() {
                    clearInterval(timer);
                }).bind("touchend", function() {
                    timer = setInterval(function() {
                        $("#btn_next").click();
                    }, 5000);
                });
            });

        </script>
        <?php $this->load->view('public/2019/header.php') ?>
        <div class="top">
            <img class="bg_img" src="<?= VIEW_PATH; ?>images/2019/company_top_bg.png" width="100%" />
            <div class="info_box">
                <div class="logo_box">
                    <img alt="<?= $company['companyname'] ?>" class="logo" src="<?= !empty($company['logo']) ? "/data/logo/" . $company['logo'] : VIEW_PATH . "images/2019/no_logo.png" ?>" height="50" />
                </div>
                <div class="name_box">
                    <h1><?= $company['companyname'] ?></h1>
                    <p><?php if (!empty($company['info_str'])): ?><?= $company['info_str'] ?><?php endif; ?></p>
                    <?php if (!empty($company['website'])): ?>
                        <p><?= $company['website'] ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="middle">
            <div class="tab">
                <p class="content_tab hover"><i></i><span>公司介绍</span></p>
                <p class="jobs_tab"><i></i><span>在招职位</span></p>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <div class="content">
                <p class="box_title"><i></i>公司简介</p>
                <?php if (!empty($company['contents']) && $company['user_status'] == 1 && $company['audit'] == 1): ?>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td><?= nl2br($company['contents']) ?></td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif; ?>
                <?php if (!empty($company['address'])): ?>
                    <p class="box_title"><i></i>公司地址</p>
                    <div class="address" <?php if ($company['map_open'] > 0 && !empty($company['qq_map_x']) && !empty($company)): ?>onclick="window.location.href = '/company/map/<?= $company['id'] ?>'"<?php endif; ?>>
                        <i></i>
                        <span><?= $company['address'] ?></span>
                        <?php if ($company['map_open'] > 0 && !empty($company['qq_map_x']) && !empty($company)): ?>
                            <a href="/company/map/<?= $company['id'] ?>"></a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($company_imgs) && $company['user_status'] == 1 && $company['audit'] == 1): ?>
                    <p class="box_title"><i></i>公司风采</p>
                    <div class="img_box">
                        <div class="main_visual">
                            <div class="main_image">
                                <ul>
                                    <?php foreach ($company_imgs as $ci): ?>
                                        <li>
                                            <a title="<?= $ci['title'] ?>" href="/data/companyimg/original/<?= $ci['img'] ?>">
                                                <img alt="<?= $ci['title'] ?>" src="/data/companyimg/original/<?= $ci['img'] ?>" />
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <a href="javascript:;" id="btn_prev"></a>
                                <a href="javascript:;" id="btn_next"></a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="jobs">
                <ul>
                    <?php if (!empty($company_jobs) && $company['user_status'] == 1 && $company['audit'] == 1): ?>
                        <?php foreach ($company_jobs as $cj): ?>
                            <li onclick="window.location.href = '/job/detail?job_id=<?= $cj['id'] ?>'">
                                <a class="left" title="<?= $cj['jobs_name'] ?>" href="/job/detail?job_id=<?= $cj['id'] ?>"><?= strlen($cj['jobs_name']) > 24 ? mb_substr($cj['jobs_name'], 0, 12, "gb2312") . '...' : $cj['jobs_name'] ?></a>
                                <span class="right"><?= $cj['wage_cn'] ?></span>
                                <div class="clear"></div>
                                <p><?= $cj['info_str'] ?></p>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <script>
                $('.tab .jobs_tab').click(function() {
                    $('.middle .jobs_tab').addClass('hover');
                    $('.middle .content_tab').removeClass('hover');
                    $('.middle .jobs').show();
                    $('.middle .content').hide();
                });
                $('.tab .content_tab').click(function() {
                    $('.middle .content_tab').addClass('hover');
                    $('.middle .jobs_tab').removeClass('hover');
                    $('.middle .content').show();
                    $('.middle .jobs').hide();
                });
                // 打开信息窗口  
            </script>
        </div>
        <?php $this->load->view('public/2019/bottom_help_home.php') ?>
        <script src="<?= VIEW_PATH; ?>js/common.js"></script>
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
                        data: {type: 'company', id: '<?= $company['id'] ?>', url: url},
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
