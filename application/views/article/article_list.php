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
        <script src="<?= VIEW_PATH; ?>js/2019/jquery.event.drag.js" type="text/javascript" language="javascript" ></script>
        <script src="<?= VIEW_PATH; ?>js/2019/jquery.touchSlider.js" type="text/javascript" language="javascript" ></script>
        <title>简章</title>
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

    <body id="article_list">
        <?php $this->load->view('public/2019/header.php') ?>
        <div id="content">
            <div class="top_box">
                <div class="search_box">
                    <form id="search_form" action="<?= $page_url ?>" method="get">
                        <input class="key" type="search" name="key" placeholder="请输入关键字" value="<?= !empty($key) ? $key : "" ?>" />
                        <input type="hidden" id="type_id" name="type_id" value="<?= !empty($type_id) ? $type_id : "" ?>" />
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
                                    <?php foreach ($ad['m_home_104'] as $ad): ?>
                                        <li><a href="http://www.jiaoshizhaopin.net/ad_count/index.php?ad_name=m_home_104&img=<?= $ad['img_path'] ?>&url=<?= $ad['img_url'] ?>"><img class="img_1" src="<?= $ad['img_path'] ?>" width="100%"/></a></li>
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
                <div class="select_box">
                    <div data-type="district" id="district_bt" class="select_bt">
                        <span><?= $district_info['id'] != 0 ? $district_info['categoryname'] : "地区" ?></span>
                        <i></i>
                    </div>
                    <div data-type="category" id="category_bt" class="select_bt">
                        <span><?= $type_info['categoryname'] ?></span>
                        <i></i>
                    </div>
                    <div data-type="district" id="district_menu" class="item_box no_display">
                        <div class="title_box">
                            <p>省份</p>
                        </div>
                        <div class="options_box district">
                            <ul>
                                <li data-data="0">全国</li>
                                <?php foreach ($district as $d): ?>
                                    <li data-data="<?= $d['id'] ?>"><?= $d['categoryname'] ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="clear"></div>
                        <div class="title_box sdistrict_title">
                            <p>城市</p>
                        </div>
                        <div class="options_box sdistrict">
                            <ul></ul>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div data-type="category" id="category_menu" class="item_box no_display">
                        <div class="title_box">
                            <p>分类</p>
                        </div>
                        <div class="options_box son_bt">
                            <ul>
                                <li data-data="0">不限</li>
                                <?php foreach ($article_category as $ac): ?>
                                    <?php if ($ac['id'] != 7): ?>
                                        <li data-data="<?= $ac['id'] ?>"><?= $ac['categoryname'] ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="select_bg"></div>
                <script>
                    $('.district li').click(function() {
                        var d_id = $(this).attr('data-data');
                        if (d_id > 0) {
                            $.get('/ajax_data/get_city/' + d_id, function(result) {
                                var li_str = "<li data-data=" + d_id + ">全部</li>";
                                var r_arr = result.split('-|-');
                                $.each(r_arr, function(key, val) {
                                    var data_arr = val.split('||');
                                    li_str = li_str + "<li data-data='" + data_arr[1] + "'>" + data_arr[0] + "</li>"
                                });
                                $('.sdistrict ul').html(li_str);
                                $('.sdistrict').show();
                                $("html, body").animate({scrollTop: $('.sdistrict').offset().top}, 1000)
                            })
                        } else {
                            $.post('/welcome/select_district_save', {district: d_id}, function(result) {
                                window.location.href = "/article";
                            })
                        }
                    })
                    $('.sdistrict').on('click', 'ul li', function() {
                        var d_id = $(this).attr('data-data');
                        $.post('/welcome/select_district_save', {district: d_id}, function(result) {
                            window.location.href = "/article";
                        })
                    })
                    $('#category_menu').on('click', 'ul li', function() {
                        var t_id = $(this).attr('data-data');
                        $('#search_form #type_id').val(t_id);
                        $('#search_form').submit();
                    })
                    $('.top_box .select_bt').click(function() {
                        $('.top_box .select_bt').removeClass('hover');
                        $(this).addClass('hover');
                        var type = $(this).attr('data-type');
                        if ($('#' + type + '_menu').is(':hidden')) {
                            $('.item_box').hide();
                            $('#' + type + '_menu').show();
                            $('.select_bg').show();
                        } else {
                            $('.item_box').hide();
                            $('.select_bg').hide();
                        }
                    })
                    $('.top_box .item_box li').click(function() {
                        $(this).parent().find('li').removeClass('hover');
                        $(this).addClass('hover');
                    })
                    $('.top_box .item_box .son_bt li').click(function() {
                        $('.top_box .select_bt').removeClass('hover');
                        var type = $(this).parents('.item_box').attr('data-type');
                        var txt = $(this).html();
                        $('#' + type + '_bt').find('span').html(txt);
                        $('.item_box').hide();
                        $('.select_bg').hide();
                    })
                    $('.select_bg').click(function() {
                        $('.item_box').hide();
                        $('.select_bg').hide();
                    });
                </script>
            </div>
            <div class="clear"></div>
            <div class="list_box">
                <ul>
                    <?php foreach ($article_list as $al): ?>
                        <li onclick="window.location.href = '<?= $al['url'] ?>'">
                            <h2><a href="<?= $al['url'] ?>" title="<?= $al['title'] ?>"><?= strlen($al['title']) > 33 ? mb_substr($al['title'], 0, 20, "gb2312") . '...' : $al['title'] ?></a></h2>
                            <div class="clear"></div>
                            <span class="left">阅读<?= $al['click'] ?></span>
                            <span class="right"><?= date('Y-m-d', $al['refreshtime']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php if ($page_arr['totalpage'] > 1): ?>
                    <div class="clear"></div>
                    <div id="page_box" data-url="<?= $page_url ?>" data-parameter="district_id=<?= !empty($district_info) ? $district_info['id'] : "" ?>&type_id=<?= !empty($type_info) ? $type_info['id'] : "" ?>&key=<?= !empty($key) ? $key : "" ?>">
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
            </div>
        </div>
        <div class="clear"></div>
        <script src="<?= VIEW_PATH; ?>js/common.js"></script>
        <?php $this->load->view('public/2019/bottom_help.php') ?>
        <?php $this->load->view('public/2019/bottom_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
