<div class="clear"></div>
<?php $var = explode('/', $_SERVER['REQUEST_URI']); ?>
<div id="bottom_menu">
    <ul>
        <li class="jobs<?= $var[1] == 'job' ? "_c" : "" ?>">
            <a title="职位" href="/job">
                <i></i>
                <span>职位</span>
            </a>
        </li>
        <li class="news<?= $var[1] == 'article' ? "_c" : "" ?>">
            <a title="简章" href="/article">
                <i></i>
                <span>简章</span>
            </a>
        </li>
        <li class="my<?= $var[1] == 'personal_center' ? "_c" : "" ?>">
            <a title="个人中心" href="/personal_center">
                <i></i>
                <span>我的</span>
            </a>
        </li>
    </ul>
</div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    $(function() {
        var url = location.href.split('#').toString();//url不能写死
        $.ajax({
            type: "get",
            url: "/welcome/wx_share",
            dataType: "json",
            async: false,
            data: {type: 'home', id: '', url: url},
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