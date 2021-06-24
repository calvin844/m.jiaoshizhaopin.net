<!doctype html>
<html>
    <head>
        <meta charset="gb2312">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
        <link href="<?= VIEW_PATH; ?>css/2019/base.css" type="text/css" rel="stylesheet">
        <link href="<?= VIEW_PATH; ?>css/2019/personal_center.css" type="text/css" rel="stylesheet">
        <script src="<?= VIEW_PATH; ?>js/2019/jq-1.11.1.js"></script>
        <script src="<?= VIEW_PATH; ?>js/2019/jquery.validate.min.js" type='text/javascript' language="javascript"></script>
        <script src="<?= VIEW_PATH; ?>js/2019/ajaxfileupload.js" type='text/javascript' language="javascript"></script>
        <title>个人中心-上传证书</title>
    </head>
    <body id="edit_certificate"><script>
        $(document).ready(function() {
            $("#certificate_form").validate({
                ignore: "",
                rules: {
                    note: {
                        required: true
                    },
                    path: {
                        required: true
                    }
                },
                messages: {
                    note: {
                        required: "请填写证书名"
                    },
                    path: {
                        required: "请上传证书"
                    }
                },
                showErrors: function(errorMap, errorList) {
                    this.defaultShowErrors();
                    setTimeout("$('label.error').remove()", 1500);
                },
                errorPlacement: function(error, element) {
                    if (element.is(":radio"))
                        error.appendTo(element.parent().next().next());
                    else if (element.is(":checkbox"))
                        error.appendTo(element.parent());
                    else
                        error.appendTo('form');
                }
            });
        });
        </script>
        <form id="certificate_form"  action="/personal_center/save_certificate" method="post" >
            <a class="back_index" href="/personal_center/resume">返回我的简历</a>
            <input class="top_upload" type="submit" value="提交" />
            <div class="clear"></div>
            <ul class="edit_ul">
                <li>
                    <label class="left">证书名</label>
                    <span class="left">（必填）</span>
                    <input class="right" type="text" name="note" placeholder="请输入证书名"/>
                </li>
                <li class="upload">
                    <img class="upload_box" src="<?= VIEW_PATH; ?>images/2019/upload_img.jpg" width="80" height="80" />
                    <input type="file" name="certificate" id="certificate" onchange ="uploadFile()" />
                    <input type="hidden" name="path" id="path" />
                </li>
            </ul>
        </form>
        <div class="clear"></div>
        <ul class="list">
            <?php foreach ($certificates as $c): ?>
                <li>
                    <div class="img_box">
                        <a href="/data/resume_certificate/<?= $c['path'] ?>">
                            <img src="/data/resume_certificate/<?= $c['path'] ?>" />
                        </a>
                        <div data-id="<?= $c['id'] ?>" class="del_box"></div>
                    </div>
                    <p class="name"><?= $c['note'] ?></p>
                    <?php if ($c['audit'] == 2): ?>
                        <p class="tip">（审核未通过请删除重新上传）</p>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <script>
            $(window).load(function() {
                $('.list .del_box').click(function() {
                    if (!confirm('确定删除？')) {
                        return false;
                    } else {
                        $.post('/personal_center/del_certificate', {id: $(this).attr('data-id')}, function() {
                            alert("删除成功！");
                            window.location.reload();
                        })
                    }
                })
            })
            function uploadFile() {
                $('div#loading_box').show();
                $.ajaxFileUpload({
                    url: '/personal_center/up_certificate',
                    secureuri: false,
                    fileElementId: 'certificate',
                    dataType: 'STRING',
                    success: function(data, status) {
                        $('div.loading').hide();
                        var f = data.substr(0, 1);
                        if (f == "-") {
                            switch (data) {
                                case "-1":
                                    $('label.error').html("上传图片失败：上传目录不存在!");
                                    return false;
                                    break;
                                case "-2":
                                    $('label.error').html("上传图片失败：上传目录无法写入!");
                                    return false;
                                    break;
                                case "-3":
                                    $('label.error').html("上传图片失败：你选择的文件无法上传");
                                    return false;
                                    break;
                                case "-4":
                                    $('label.error').html("上传图片失败：文件大小超过限制");
                                    return false;
                                    break;
                                case "-5":
                                    $('label.error').html("上传图片失败：文件类型错误！");
                                    return false;
                                    break;
                                case "-6":
                                    $('label.error').html("上传图片失败：文件上传出错！");
                                    return false;
                                    break;
                                case "-7":
                                    $('label.error').html("请刷新页面后再试！");
                                    return false;
                                    break;
                                case "-8":
                                    $('label.error').html("请上传图片！");
                                    return false;
                                    break;
                                case "-9":
                                    $('label.error').html("最多只能上传5张证书！");
                                    return false;
                                    break;
                                default:
                                    break;
                            }
                            $('label.error').show();
                            setTimeout("$('label.error').remove()", 1500);
                        } else {
                            alert("图片上传成功！请填写证书名后提交");
                            $('input#path').attr('value', data);
                            $('.upload_box').attr('src', '/data/resume_certificate/' + data);
                            $('.upload_box').css('width', '100px auto');
                        }
                        $('div#loading_box').hide();
                    }
                })
            }
        </script>
        <div id="loading_box">
            <img src="<?= VIEW_PATH; ?>images/loading.gif" width="50" height="50" />
            <div class="clear"></div>
            <p>正在上传</p>
            <div class="bg"></div>
        </div>
        <?php $this->load->view('public/2019/bottom_menu.php') ?>
        <div class="no_display"><script src="https://s4.cnzz.com/z_stat.php?id=511743&web_id=511743" language="JavaScript"></script></div>
    </body>
</html>
