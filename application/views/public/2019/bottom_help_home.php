<div class="clear"></div>
<div id="bottom_help_box">
    <a title="��ʦ��Ƹ����ҳ" href="/">��ҳ</a>
    <i></i>
    <a title="��ʦ��Ƹ�����԰�" href="http://www.jiaoshizhaopin.net?is_wap=1">���԰�</a>
    <i></i>
    <a title="��ʦ��Ƹ�����ں�" href="<?= VIEW_PATH; ?>images/2019/jszp_wechat_img.jpg">���ں�</a>
    <i></i>
    <a title="��ʦ��Ƹ���ͷ�" href="/welcome/service">��ʦС��</a>
    <p class="copyright">Copyright&nbsp;&copy;&nbsp;2007-<?= date("Y") ?> jiaoshizhaopin.net</p>
</div>
<div id="back_top"></div>
<script>
    //���ù��ض�������
    $("#back_top").click(function() {
        $("body,html").animate({scrollTop: 0}, 500);
        //$("#back_top").animate({opacity: 0}, 500);
        return false;
    })

    $(window).scroll(function() {
        //��ȡ��ǰ�������ĸ߶�
        var h = $(document).scrollTop();
        if (h >= 10) {
            $("#back_top").css("opacity", "1");
        } else {
            $("#back_top").css("opacity", "0");
        }
    })
</script>