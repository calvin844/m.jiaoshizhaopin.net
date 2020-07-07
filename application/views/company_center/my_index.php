<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/2019/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/company_center.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/2019/jq-1.11.1.js"></script>
        <script src="<?= VIEW_PATH; ?>js/2019/ajaxfileupload.js" type='text/javascript' language="javascript"></script>
        <title>我的</title>
    </head>
    <body id="my_index">
        <div class="top_box">
            <div class="box">
                <div class="logo_box">
                    <img src="/data/logo/<?= $company['logo'] ?>" width="150" />
                    <div class="right">
                        <p><?= $company['companyname'] ?></p>
                        <i></i>
                    </div>
                    <input type="file" class="up_logo" name="logo" id="logo" class="file_input" onchange ="uploadFile()"  />
                    <script>
                        function uploadFile() {
                            $('div#loading_box').show();
                            $.ajaxFileUpload({
                                url: '/company_center/up_logo',
                                secureuri: false,
                                fileElementId: 'logo',
                                dataType: 'STRING',
                                success: function(data, status) {
                                    $('div.loading').hide();
                                    var f = data.substr(0, 1);
                                    if (f == "-") {
                                        switch (data) {
                                            case "-1":
                                                alert("上传图片失败：上传目录不存在!");
                                                break;
                                            case "-2":
                                                alert("上传图片失败：上传目录无法写入!");
                                                break;
                                            case "-3":
                                                alert("上传图片失败：你选择的文件无法上传");
                                                break;
                                            case "-4":
                                                alert("上传图片失败：文件大小超过限制");
                                                break;
                                            case "-5":
                                                alert("上传图片失败：文件类型错误！");
                                                break;
                                            case "-6":
                                                alert("上传图片失败：文件上传出错！");
                                                break;
                                            case "-7":
                                                alert("请刷新页面后再试！");
                                                break;
                                            case "-8":
                                                alert("请上传图片！");
                                                break;
                                            default:
                                                break;
                                        }
                                        window.location.reload();
                                    } else {
                                        alert("上传成功！");
                                        window.location.reload();
                                    }
                                }
                            })
                        }
                    </script>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
                <ul class="function_list">
                    <li class="account">
                        <i></i><span>我的账号</span><i class="to_right"></i>
                    </li>
                    <li class="message">
                        <i></i><span>我的消息</span><i class="to_right"></i>
                    </li>
                    <li class="info">
                        <i></i><span>企业信息</span><i class="to_right"></i>
                    </li>
                </ul>
                <div class="logout_box">
                    <i></i><span>退出登录</span><i class="to_right"></i>
                </div>
                <script>
                    $('.function_list li').click(function() {
                        var c = $(this).attr('class');
                        window.location.href = '/company_center/my_' + c;
                    })
                    $('div.logout_box').click(function() {
                        window.location.href = '/user/logout';
                    })
                </script>
            </div>
        </div>
        <div class="clear"></div>
        <script src="<?= VIEW_PATH; ?>js/2019/common.js"></script>
        <?php $this->load->view('public/2019/company_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>