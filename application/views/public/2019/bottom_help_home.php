<div class="clear"></div>
<div id="bottom_help_box">
    <a title="教师招聘网首页" href="/">首页</a>
    <i></i>
    <a title="教师招聘网电脑版" href="http://www.jiaoshizhaopin.net?is_wap=1">电脑版</a>
    <i></i>
    <a title="教师招聘网公众号" href="<?= VIEW_PATH; ?>images/2019/jszp_wechat_img.jpg">公众号</a>
    <i></i>
    <a title="教师招聘网客服" href="/welcome/service">教师小助</a>
    <p class="copyright">Copyright&nbsp;&copy;&nbsp;2007-<?= date("Y") ?> jiaoshizhaopin.net</p>
</div>
<div id="back_top"></div>
<script>
    //设置滚回顶部方法
    $("#back_top").click(function() {
        $("body,html").animate({scrollTop: 0}, 500);
        //$("#back_top").animate({opacity: 0}, 500);
        return false;
    })

    $(window).scroll(function() {
        //获取当前滚动条的高度
        var h = $(document).scrollTop();
        if (h >= 10) {
            $("#back_top").css("opacity", "1");
        } else {
            $("#back_top").css("opacity", "0");
        }
    })
</script>