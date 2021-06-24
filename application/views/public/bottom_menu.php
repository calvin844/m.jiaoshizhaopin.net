<div class="clear"></div>
<?php $var = explode('/', $_SERVER['REQUEST_URI']); ?>
<div id="bottom_menu">
    <ul>
        <li class="jobs<?= $var[1] == 'job' ? "_c" : "" ?>">
            <a title="ְλ" href="/job">
                <i></i>
                <span>ְλ</span>
            </a>
        </li>
        <li class="news<?= $var[1] == 'article' ? "_c" : "" ?>">
            <a title="����" href="/article">
                <i></i>
                <span>����</span>
            </a>
        </li>
        <li class="my<?= $var[1] == 'personal_center' ? "_c" : "" ?>">
            <a title="��������" href="/personal_center">
                <i></i>
                <span>�ҵ�</span>
            </a>
        </li>
    </ul>
</div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    $(function() {
        var url = location.href.split('#').toString();//url����д��
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
                        title: title, // �������
                        link: data.url, // ��������
                        imgUrl: data.imgurl, // ����ͼ��
                        success: function() {
                        },
                        cancel: function() {
                            // �û�ȡ�������ִ�еĻص�����
                        }
                    });
                    wx.onMenuShareAppMessage({
                        title: title, // �������
                        desc: desc, // ��������
                        link: data.url, // ��������
                        imgUrl: data.imgurl, // ����ͼ��
                        type: '', // ��������,music��video��link������Ĭ��Ϊlink
                        dataUrl: '', // ���type��music��video����Ҫ�ṩ�������ӣ�Ĭ��Ϊ��
                        success: function() {
                        },
                        cancel: function() {
                            // �û�ȡ�������ִ�еĻص�����
                        }
                    });
                    wx.onMenuShareQQ({
                        title: title, // �������
                        desc: desc, // ��������
                        link: data.url, // ��������
                        imgUrl: data.imgurl, // ����ͼ��
                        success: function() {
                        },
                        cancel: function() {
                            // �û�ȡ�������ִ�еĻص�����
                        }
                    });
                    wx.onMenuShareWeibo({
                        title: title, // �������
                        desc: desc, // ��������
                        link: data.url, // ��������
                        imgUrl: data.imgurl, // ����ͼ��
                        success: function() {
                        },
                        cancel: function() {
                            // �û�ȡ�������ִ�еĻص�����
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