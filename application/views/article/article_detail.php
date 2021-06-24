<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/2019/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/article.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/2019/jq-1.11.1.js" type="text/javascript" language="javascript" ></script>
        <title><?= $article['title'] ?></title>
        <meta name="keywords" content="<?= $seo_keywords ?>"/>
        <meta name="description" content="<?= $seo_description ?>"/>
    </head>

    <body id="article_detail">
        <?php $this->load->view('public/2019/header.php') ?>
        <div class="ad">
            <?php if (!empty($ad['m_page_302'])): ?>
                <a href="http://www.jiaoshizhaopin.net/ad_count/index.php?ad_name=m_page_302&img=<?= $ad['m_page_302'][0]['img_path'] ?>&url=<?= $ad['m_page_302'][0]['img_url'] ?>" target="_blank"><img src="<?= $ad['m_page_302'][0]['img_path'] ?>" width="100%"/></a>
            <?php endif; ?>
        </div>
        <div class="title_box">
            <h1><?= $article['title'] ?></h1>
            <div class="info">
                <div class="left">
                    <label><?= $article['endtime_cn'] ?>��ֹ</label><span><?php if ($article['endtime'] > time()): ?>����Ƹ�У�<?php else: ?>����Ƹ������<?php endif; ?></span>
                    <label>�Ķ�</label><span><?= $article['click'] ?></span>
                </div>
                <div class="right">
                    <label><?= $article['refreshtime_cn'] ?></label>
                </div>
            </div>
        </div>
        <div class="content_box">
            <?php if (!empty($other_jobs)): ?>
                <div class = "other_jobs_box">
                    <div class = "title_box">
                        <div class = "line"></div>
                        <p class = "title">���ְλ�Ƽ�</p>
                    </div>
                    <ul>
                        <?php foreach ($other_jobs as $oj): ?>
                            <li onclick="window.location.href = '/job/detail?job_id=<?= $oj['id'] ?>'">
                                <a class="left" href="/job/detail?job_id=<?= $oj['id'] ?>" title ="<?= $oj['district_cn'] ?><?= $oj['jobs_name'] ?>��Ƹ"><?= $oj['jobs_name'] ?></a>
                                <span class = "right"><?= $oj['companyname'] ?></span>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif; ?>
            <div class="clear"></div>
            <div class="article_content_box">
                <p class="box_title"><i></i>��������</p>
                <div class="clear"></div>
                <div class="box_content">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td><?= ($article['content']) ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <?php if (!empty($article['article_jobs'])): ?>
                        <p class="box_title"><i></i>��Ƹְλ</p>
                        <ul class="box_jobs">
                            <?php foreach ($article['article_jobs'] as $article_job): ?>
                                <li><p><a href="/article/article_jobs?article_job_id=<?= $article_job['id'] ?>"><?= $article_job['job_name'] ?>��<?= $article_job['amount'] ?>��</a></p></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif ?>
                </div>
                <div class="clear"></div>
                <div class="show_more">
                    <span>չ������</span><i></i>
                </div>
                <script>
                    $('.article_content_box').on('click', '.show_more', function() {
                        $('.box_content').css('height', 'auto');
                        $(this).find('span').html('�۵�����');
                        $(this).removeClass('show_more');
                        $(this).addClass('hide_more');
                    })
                    $('.article_content_box').on('click', '.hide_more', function() {
                        $('.box_content').css('height', '220px');
                        $(this).find('span').html('չ������');
                        $(this).removeClass('hide_more');
                        $(this).addClass('show_more');
                    })
                </script>
            </div>
        </div>
        <div class="clear"></div>
        <div class="join_box">
            <p class="box_title"><i></i>������Ϣ</p>
            <?php if (!empty($uid)): ?>
                <div class="contact_box">
                    <ul>
                        <?php if (!empty($article['district_cn'])): ?>
                            <li>
                                <b>������</b><span><?= $article['district_cn'] ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($article['join_way'])): ?>
                            <li>
                                <b>������ʽ��</b><span><?= $article['join_way'] ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($article['email'])): ?>
                            <li>
                                <b>��ʽ���ݣ�</b><span><?= $article['email'] ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if ($article['contact'] != "��"): ?>
                            <li>
                                <b>��ϵ�ˣ�</b><span><?= $article['contact'] ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if ($article['telephone'] != "��"): ?>
                            <li>
                                <b>�绰��</b><span><?= $article['telephone'] ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if ($article['site'] != "?"): ?>
                            <li>
                                <b>��ַ��</b><span><?= $article['site'] ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if ($article['address'] != "��"): ?>
                            <li>
                                <b>��ַ��</b><span><?= $article['address'] ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if ($article['remarks'] != "��"): ?>
                            <li>
                                <b>��ע��</b><span><?= $article['remarks'] ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($article['attachment'])): ?>
                            <li>
                                <b>������</b><span>��ʹ�õ��԰�������Ӧ������</span>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <div class="code_img_box">
                        <?php $d_arr = explode("/", $article['district_cn']); ?>
                        <p>����<?= $d_arr[1] ?>��ʦȦ����50����ʦһ��ɳ�<br/>�����������ʹ�����������ϡ�ѧ��֪ʶ��ɨ��ά��ר�����ʴ����㡣</p>
                        <img src="<?= VIEW_PATH; ?>images/service_code_img.jpg" width="77" height="77" />
                    </div>
                </div>
            <?php else: ?>
                <div class="login_box">
                    <p>��Ѳ鿴������ʽ������&nbsp;<a title="��¼"  href="/user/check_login?url=/article/detail?article_id=<?= $article['id'] ?>">��½</a>&nbsp;��&nbsp;<a title="ע��" href="/user/reg">ע��</a></p>
                </div>
            <?php endif; ?>
        </div>
        <div class="clear"></div>
        <div class="jobs_type_box">
            <div class="title_box">
                <div class="line"></div>
                <p class="title">����ְλ</p>
            </div>
            <ul>
                <?php foreach ($jobs_type as $jt): ?>
                    <li>
                        <a class="left" href="/job/index/?job_type=<?= $jt['id'] ?>" title="<?= $jt['categoryname'] ?>��ʦ��Ƹ"><?= $jt['categoryname'] ?>��ʦ��Ƹ</a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clear"></div>
            <div class="more_jobs">
                <a href="/job"><span>����ְλ</span><i></i></a>
            </div>
        </div>
        <div class="ad">
            <?php if (!empty($ad['m_page_301'])): ?>
                <a href="http://www.jiaoshizhaopin.net/ad_count/index.php?ad_name=m_page_301&img=<?= $ad['m_page_301'][0]['img_path'] ?>&url=<?= $ad['m_page_301'][0]['img_url'] ?>" target="_blank"><img src="<?= $ad['m_page_301'][0]['img_path'] ?>" width="100%"/></a>
            <?php endif; ?>
        </div>
        <?php $this->load->view('public/2019/bottom_help_home.php') ?>
        <div class="delivery_box" data-id="<?= $article['id'] ?>">
            <div class="service">
                <a href="/welcome/service">
                    <i></i>
                </a>
            </div>
            <div id="shareBtn" class="share">
                <i></i>
                <span>����</span>
            </div>
            <div class="favorites <?php if (!empty($article_favorite)): ?>hover<?php endif; ?>" data-favorites="<?php if (!empty($article_favorite)): ?>1<?php else: ?>0<?php endif; ?>">
                <i></i>
                <span>�ղ�</span>
            </div>
            <div class="delivery <?php if (empty($article['email']) || $article['join_way'] != "����" || $article['endtime'] < time()): ?>disable<?php endif; ?>">
                <input id="delivery_job" type="button" onclick="show_select('ѡ��ְλ', 'get_article_job/<?= $article['id'] ?>', 'delivery_job')" value="<?php if (empty($article['email']) || $article['join_way'] != "����" || $article['endtime'] < time()): ?>��֧��Ͷ��<?php else: ?>Ͷ�ݼ���<?php endif; ?>"/>
            </div>
        </div>
        <form id="article_form" action="" method="post">
            <input type="hidden" name="article_id" value="<?= $article['id'] ?>" />
            <input type="hidden" name="article_job_id" id="article_job_id" value="" />
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
                        var article_id = $('.delivery_box').attr('data-id');
                        var del = $(this).attr('data-delivery');
                        var str = ''
                        if (del == '0') {
                            $.post('/article/apply_article', {article_id: article_id}, function(result) {
                                if (result.indexOf('-|-') == -1) {
                                    alert(result);
                                    location.reload();
                                } else {
                                    var li_str = "";
                                    var r_arr = result.split('-|-');
                                    $.each(r_arr, function(key, val) {
                                        var data_arr = val.split('||');
                                        var li_data_str = "";
                                        var li_data_num = 1;
                                        $.each(data_arr, function(k, v) {
                                            if (k > 0) {
                                                li_data_str = li_data_str + " data-data" + li_data_num + "='" + v + "'"
                                                li_data_num++;
                                            }
                                        })
                                        li_str = li_str + "<li " + li_data_str + "><p>" + data_arr[0] + "</p></li>"
                                    });
                                    $('#select_box ul').html(li_str);
                                    $('#select_box').show();
                                    $('#select_box .bg').show();
                                    $('#select_box .select').show();
                                    $('body').css('overflow', 'hidden');
                                }
                            })
                        }
                    })
        </script>

        <div id="select_box" data-item="">
            <div class="select">
                <div class="title">
                    <p></p>
                    <i class="close"></i>
                </div>
                <ul></ul>
            </div>
            <div class="bg"></div>
            <script>
                $('#select_box').on('click', 'li', function() {
                    var jid = $(this).attr('data-data1');
                    $('#article_form #article_job_id').val(jid);
                    $('#article_form').attr('action', '/article/apply_article_send');
                    $('#article_form').submit();
                    /*
                     $.post('/article/apply_article_send', {article_job_id: jid}, function(result) {
                     if (result == 'login') {
                     window.location.href = '/user/login';
                     } else if (result == 'resume-err') {
                     alert('����û����д�����������ļ�����ûͨ����ˣ�');
                     window.location.href = '/personal_center/resume';
                     } else {
                     alert(result);
                     location.reload();
                     }
                     })
                     **/
                })
            </script>
        </div>
        <script src="<?= VIEW_PATH; ?>js/common.js"></script>
        <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
        <script>
                $(function() {
                    var url = location.href.split('#').toString(); //url����д��
                    var article_id = $('.delivery_box').attr('data-id');
                    $.ajax({
                        type: "get",
                        url: "/welcome/wx_share",
                        dataType: "json",
                        async: false,
                        data: {type: 'article', id: article_id, url: url},
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
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
