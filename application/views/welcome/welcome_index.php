<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/2019/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/index.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/idangerous.swiper2.7.6.css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/animate.min.css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/2019/jq-1.11.1.js"></script>
        <script src="<?= VIEW_PATH; ?>js/2019/jquery.event.drag.js" type="text/javascript" language="javascript" ></script>
        <script src="<?= VIEW_PATH; ?>js/2019/jquery.touchSlider.js" type="text/javascript" language="javascript" ></script>
        <title>教师招聘网_国内优秀的教师招聘平台_<?= date("Y") ?>年最新教师招聘考试信息</title>
        <meta name="description" content="教师招聘网是国内大型教育人力资源专业网站!集网络招聘、高校毕业生就业服务、事业单位公共招聘信息发布等多项服务于一身。专注缔造高效，通过教师人才库为各用人单位提供更加精准的教师人才，让学校在最短的时间招到满意的教师！"/>
        <meta name="keywords" content="教师招聘,教师招聘网,英语教师招聘,美术教师招聘,中学教师招聘,中小学教师招聘,<?= date("Y") ?>年教师招聘,老师招聘,教师招聘网"/>
        <script type="text/javascript">
            $(document).ready(function() {

                $(".img_gallery").hover(function() {
                    $("#btn_prev,#btn_next").fadeIn()
                }, function() {
                    $("#btn_prev,#btn_next").fadeOut()
                });

                $dragBln = false;

                $(".main_img").touchSlider({
                    flexible: true,
                    speed: 200,
                    btn_prev: $("#btn_prev"),
                    btn_next: $("#btn_next"),
                    paging: $(".point a"),
                    counter: function(e) {
                        $(".point a").removeClass("on").eq(e.current - 1).addClass("on");//图片顺序点切换
                        $(".img_font span").hide().eq(e.current - 1).show();//图片文字切换
                    }
                });

                $(".main_img").bind("mousedown", function() {
                    $dragBln = false;
                });

                $(".main_img").bind("dragstart", function() {
                    $dragBln = true;
                });

                $(".main_img a").click(function() {
                    if ($dragBln) {
                        return false;
                    }
                });

                timer = setInterval(function() {
                    $("#btn_next").click();
                }, 5000);

                $(".img_gallery").hover(function() {
                    clearInterval(timer);
                }, function() {
                    timer = setInterval(function() {
                        $("#btn_next").click();
                    }, 5000);
                });

                $(".main_img").bind("touchstart", function() {
                    clearInterval(timer);
                }).bind("touchend", function() {
                    timer = setInterval(function() {
                        $("#btn_next").click();
                    }, 5000);
                });

            });
        </script>
    </head>
    <body id="index">
        <div class="ad">
            <?php if (!empty($ad['ws001'])): ?>
                <a href="http://www.jiaoshizhaopin.net/ad_count/index.php?ad_name=ws001&img=<?= $ad['ws001'][0]['img_path'] ?>&url=<?= $ad['ws001'][0]['img_url'] ?>" target="_blank"><img src="<?= $ad['ws001'][0]['img_path'] ?>" width="100%"/></a>
            <?php endif; ?>
        </div>
        <?php $this->load->view('public/2019/header.php') ?>
        <div id="content">
            <div class="top_box">
                <div class="search_box">
                    <form id="search_form" action="/job/index/" method="get">
                        <input class="key" type="search" name="key" placeholder="请输入关键字" value="<?= !empty($key) ? $key : "" ?>" />
                        <input class="submit" type="submit" value="搜索" />
                    </form>
                </div>
                <?php if (!empty($ad['m_home_104'])): ?>
                    <div class="ad_box">
                        <div class="img_gallery">
                            <div class="point">
                                <?php foreach ($ad['m_home_104'] as $k => $v): ?>
                                    <a href="#"><?= $k + 1 ?></a>
                                <?php endforeach; ?>
                            </div>
                            <div class="main_img">
                                <ul>
                                    <?php foreach ($ad['m_home_104'] as $k => $v): ?>
                                        <li><a href="http://www.jiaoshizhaopin.net/ad_count/index.php?ad_name=m_home_104|<?= $k ?>&img=<?= $v['img_path'] ?>&url=<?= $v['img_url'] ?>"><img class="img_1" src="<?= $v['img_path'] ?>" width="100%"/></a></li>
                                    <?php endforeach; ?>
                                </ul>
                                <a href="javascript:;" id="btn_prev"></a>
                                <a href="javascript:;" id="btn_next"></a>
                            </div>
                        </div>
                        <script>
                            var h = $(window).width() / 2.25;
                            $('.img_gallery').height(h);
                            $('.main_img').height(h);
                            $('.main_img ul').height(h);
                            $('.main_img ul li').height(h);
                        </script>
                    </div>
                <?php endif; ?> 
            </div>
            <div class="menu_box">
                <ul>
                    <li class="my">
                        <a title="个人中心" href="/personal_center">
                            <i></i>
                            <br />
                            <span>个人中心</span>
                        </a>
                    </li>
                    <li class="jobs">
                        <a title="最新职位" href="/job">
                            <i></i>
                            <br />
                            <span>最新职位</span>
                        </a>
                    </li>
                    <li class="article">
                        <a title="招聘简章" href="/article">
                            <i></i>
                            <br />
                            <span>招聘简章</span>
                        </a>
                    </li>
                    <li class="community">
                        <a title="教师社区" href="#">
                            <i></i>
                            <br />
                            <span>教师社区</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="clear"></div>
            <div class="emergency_box">
                <p class="box_title">急聘职位</p>
                <div class="swiper-container">
                    <a class="arrow-left" href="#"></a> 
                    <a class="arrow-right" href="#"></a>
                    <div class="swiper-wrapper">
                        <div class="swiper-slide slide1">
                            <?php foreach ($emergency as $key => $value): ?>
                                <?php if ($key < 2): ?>
                                    <div class="item">
                                        <div class="job_box">
                                            <a title="<?= $value['jobs_name'] ?>" href="/job/detail?job_id=<?= $value['id'] ?>"><?= strlen($value['jobs_name']) > 33 ? mb_substr($value['jobs_name'], 0, 20, "gb2312") . '...' : $value['jobs_name'] ?></a>
                                            <span><?= $value['wage_cn'] ?></span>
                                        </div>
                                        <div class="company_box">
                                            <a title="<?= $value['companyname'] ?>" href="/company/detail?company_id=<?= $value['company_id'] ?>">[<?= $value['district_cn'] ?>]&nbsp;<?= $value['companyname'] ?></a>
                                            <span><?= date('Y-m-d', $value['refreshtime']) ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-slide slide2">
                            <?php foreach ($emergency as $key => $value): ?>
                                <?php if ($key > 1 && $key < 4): ?>
                                    <div class="item">
                                        <div class="job_box">
                                            <a title="<?= $value['jobs_name'] ?>" href="/job/detail?job_id=<?= $value['id'] ?>"><?= strlen($value['jobs_name']) > 33 ? mb_substr($value['jobs_name'], 0, 20, "gb2312") . '...' : $value['jobs_name'] ?></a>
                                            <span><?= $value['wage_cn'] ?></span>
                                        </div>
                                        <div class="company_box">
                                            <a title="<?= $value['companyname'] ?>" href="/company/detail?company_id=<?= $value['company_id'] ?>">[<?= $value['district_cn'] ?>]&nbsp;<?= $value['companyname'] ?></a>
                                            <span><?= date('Y-m-d', $value['refreshtime']) ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-slide slide3">
                            <?php foreach ($emergency as $key => $value): ?>
                                <?php if ($key > 3 && $key < 6): ?>
                                    <div class="item">
                                        <div class="job_box">
                                            <a title="<?= $value['jobs_name'] ?>" href="/job/detail?job_id=<?= $value['id'] ?>"><?= strlen($value['jobs_name']) > 33 ? mb_substr($value['jobs_name'], 0, 20, "gb2312") . '...' : $value['jobs_name'] ?></a>
                                            <span><?= $value['wage_cn'] ?></span>
                                        </div>
                                        <div class="company_box">
                                            <a title="<?= $value['companyname'] ?>" href="/company/detail?company_id=<?= $value['company_id'] ?>">[<?= $value['district_cn'] ?>]&nbsp;<?= $value['companyname'] ?></a>
                                            <span><?= date('Y-m-d', $value['refreshtime']) ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="pagination"></div>
                </div>
            </div>	
            <div class="clear"></div>
            <div class="ad">
                <?php if (!empty($ad['m_home_103'])): ?>
                    <a href="http://www.jiaoshizhaopin.net/ad_count/index.php?ad_name=m_home_103&img=<?= $ad['m_home_103'][0]['img_path'] ?>&url=<?= $ad['m_home_103'][0]['img_url'] ?>" target="_blank"><img src="<?= $ad['m_home_103'][0]['img_path'] ?>" width="100%"/></a>
                <?php endif; ?>
            </div>
            <div class="clear"></div>
            <div class="jobs_box">
                <p class="box_title">最新教师招聘职位</p>
                <ul>
                    <?php foreach ($job_list as $k => $jl): ?>
                        <li>
                            <div class="job_box">
                                <a title="<?= $jl['jobs_name'] ?>" href="/job/detail?job_id=<?= $jl['id'] ?>"><?= strlen($jl['jobs_name']) > 39 ? mb_substr($jl['jobs_name'], 0, 13, "gb2312") . '...' : $jl['jobs_name'] ?></a>
                                <span><?= $jl['wage_cn'] ?></span>
                            </div>
                            <div class="company_box">
                                <a title="<?= $jl['companyname'] ?>" href="/company/detail?company_id=<?= $jl['company_id'] ?>">[<?= $jl['district_cn'] ?>]&nbsp;<?= $jl['companyname'] ?></a>
                                <span><?= date('Y-m-d', $jl['refreshtime']) ?></span>
                            </div>
                        </li>
                    <?php endforeach; ?>

                </ul>
                <div class="more_list">
                    <a title="教师招聘职位大全" href="/job"><span>更多职位推荐</span><i></i></a>
                </div>
            </div>
            <?php if (!empty($ad['m_home_105'])): ?>
                <div class="clear"></div>
                <div class="logo_ad_box">
                    <p class="box_title">品牌学校</p>
                    <ul>
                        <?php foreach ($ad['m_home_105'] as $k => $ad_5): ?>
                            <li>
                                <a href="http://www.jiaoshizhaopin.net/ad_count/index.php?ad_name=m_home_105|<?= $k ?>&img=<?= $ad_5['img_path'] ?>&url=<?= $ad_5['img_url'] ?>" target="_blank"><img src="<?= $ad_5['img_path'] ?>" width="100%"/></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <div class="clear"></div>
            <div class="article_box">
                <p class="box_title">最新教师招聘信息</p>
                <ul class="article_list">
                    <?php foreach ($article_list as $k => $al): ?>
                        <li>
                            <a data-bind="<?= strlen($al['title']) ?>" title="<?= $al['title'] ?>" href="/article/detail?article_id=<?= $al['id'] ?>"><?= strlen($al['title']) > 38 ? mb_substr($al['title'], 0, 22, "gb2312") . '...' : $al['title'] ?></a>
                            <div class="clear"></div>
                            <span class="left">阅读<?= $al['click'] ?></span>
                            <span class="right"><?= date('Y-m-d', $al['refreshtime']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="more_list">
                    <a title="公办编制教师招聘简章大全" href="/article"><span>更多招聘信息</span><i></i></a>
                </div>
            </div>
        </div>
        <?php $this->load->view('public/2019/bottom_help.php') ?>
        <?php $this->load->view('public/2019/bottom_menu.php') ?>
        <script src="<?= VIEW_PATH; ?>js/2019/idangerous.swiper2.7.6.min.js"></script>
        <script src="<?= VIEW_PATH; ?>js/2019/swiper.animate1.0.2.min.js"></script>
        <script>
                        var mySwiper = new Swiper('.swiper-container', {
                            pagination: '.pagination',
                            loop: true,
                            paginationClickable: true,
                            autoplay: 3000,
                            speed: 1,
                            //autoplayDisableOnInteraction : false,

                            onInit: function(swiper) { //Swiper2.x的初始化是onFirstInit
                                swiperAnimateCache(swiper); //隐藏动画元素 
                                swiperAnimate(swiper); //初始化完成开始动画
                            },
                            onSlideChangeEnd: function(swiper) {
                                swiperAnimate(swiper); //每个slide切换结束时也运行当前slide动画
                            }
                        })

                        $('.arrow-left').on('click', function(e) {
                            e.preventDefault()
                            mySwiper.swipePrev()
                        })
                        $('.arrow-right').on('click', function(e) {
                            e.preventDefault()
                            mySwiper.swipeNext()
                        })
        </script> 
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
