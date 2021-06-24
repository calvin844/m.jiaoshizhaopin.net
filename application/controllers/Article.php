<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Article extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('ad_models');
        $this->load->model('common_models');
        $this->load->model('article_models');
        $this->load->model('category_models');
        $this->load->model('personal_models');
        $this->load->model('resume_models');
        $this->load->model('user_models');
        $this->load->model('statistics_models');
        $this->load->model('job_models');
    }

    //简章列表页
    public function index($page = 1) {
        $type_id = $_REQUEST['type_id'];
        $type_info = $this->category_models->get_article_category_by_id($type_id);
        $type_info = !empty($type_info) ? $type_info : array('id' => 0, 'parentid' => 0, 'categoryname' => '分类');
        if ($type_info['id'] > 0) {
            $where['type_id'] = $type_info['id'];
        }
        $district_info = $this->session->userdata['district_info'];
        $district_info = !empty($district_info) ? $district_info : array('id' => 0, 'parentid' => 0, 'categoryname' => '全国');
        if (!empty($district_info)) {
            if ($district_info['parentid'] > 0) {
                $where['sdistrict'] = $district_info['id'];
            } elseif ($district_info['id'] > 0) {
                $where['district'] = $district_info['id'];
            }
        }
        $where['is_display'] = 1;
        $where['type_id !='] = "7";
        $where['audit'] = '1';
        $key = $this->input->get('key');
        $key = trim($key);
        $total = $this->article_models->get_article_total($where, $key);
        $page_arr = get_page_arr($total, $page);
        $article_data = $this->article_models->get_article_list($where, $key, $page_arr['page_num'], $page_arr['offset']);
        foreach ($article_data as $al) {
            $al['url'] = $this->article_models->get_article_url($al['id']);
            $article_list[] = $al;
        }
        $district = $this->category_models->get_provinces();
        $article_category = $this->category_models->get_article_category();
        $ad = $this->ad_models->get_ad();
        $data['ad'] = $ad;
        $data['article_category'] = $article_category;
        $data['district'] = $district;
        $data['type_info'] = $type_info;
        $data['district_info'] = $district_info;
        $data['key'] = $key;
        $data['article_list'] = $article_list;
        $data['page_arr'] = $page_arr;
        $data['page_url'] = "/article/index/";
        $this->load->view('/article/article_list', $data);
    }

    //简章城市站列表页
    public function district_py($parent_py = "a", $district = "a", $page = 1) {
        $type_id = $_REQUEST['type_id'];
        $type_info = $this->category_models->get_article_category_by_id($type_id);
        $type_info = !empty($type_info) ? $type_info : array('id' => 0, 'parentid' => 0, 'categoryname' => '分类');
        if ($type_info['id'] > 0) {
            $where['type_id'] = $type_info['id'];
        }
        if (!empty($this->session->userdata['district_info'])) {
            header("Location: /article");
        }
        $district_py = $district != "a" ? $district : $parent_py;
        $district_data = !empty($district_py) ? $this->category_models->get_district_pinyin($district_py) : "";
        $district_info = !empty($district_data) ? $district_data : array('id' => 0, 'parentid' => 0, 'categoryname' => '全国');
        if (!empty($district_info)) {
            if ($district_info['parentid'] > 0) {
                $where['sdistrict'] = $district_info['id'];
            } elseif ($district_info['id'] > 0) {
                $where['district'] = $district_info['id'];
            }
        }
        $where['is_display'] = 1;
        $where['type_id !='] = "7";
        $where['audit'] = '1';
        $key = $this->input->get('key');
        $key = trim($key);
        $total = $this->article_models->get_article_total($where, $key);
        $page_arr = get_page_arr($total, $page);
        $article_data = $this->article_models->get_article_list($where, $key, $page_arr['page_num'], $page_arr['offset']);
        foreach ($article_data as $al) {
            $al['url'] = $this->article_models->get_article_url($al['id']);
            $article_list[] = $al;
        }
        $district = $this->category_models->get_provinces();
        $article_category = $this->category_models->get_article_category();
        $ad = $this->ad_models->get_ad();
        $data['ad'] = $ad;
        $data['article_category'] = $article_category;
        $data['district'] = $district;
        $data['type_info'] = $type_info;
        $data['district_info'] = $district_info;
        $data['key'] = $key;
        $data['article_list'] = $article_list;
        $data['page_arr'] = $page_arr;
        $data['page_url'] = "/article/district_py/" . $parent_py . "/" . $district . "/";
        $this->load->view('/article/article_list', $data);
    }

    //简章详细页
    public function detail() {
        $article_id = $this->input->get('article_id');
        $article = $this->article_models->get_article_by_id($article_id);
        if ($article['is_display'] != 1) {
            alert_to('内容已被删除！', '/article');
        }
        $this->article_models->add_mobile_click($article_id);
        $article_jobs = $this->article_models->get_article_jobs_by_article_id($article_id);
        $article['refreshtime_cn'] = parse_date($article['refreshtime']);
        $article_jobs = $this->article_models->get_article_jobs_by_article_id($article_id);
        foreach ($article_jobs as $aj) {
            $aj['amount'] = strpos($aj['amount'], "人") > 0 ? $aj['amount'] : $aj['amount'] . "人";
            $article['article_jobs'][] = $aj;
        }
        $article['endtime_cn'] = date('Y-m-d', $article['endtime']);
        $data['uid'] = $uid = $this->session->userdata('uid');
        $article_favorite = !empty($uid) ? $this->personal_models->get_favorites_article_by_uid_aid($uid, $article_id) : "";
        $data['article'] = $article;
        $data['article_favorite'] = $article_favorite;
        $jobs_type = $this->category_models->get_parent_job_types();
        $data['jobs_type'] = $jobs_type;
        if ($article['sdistrict'] > 0) {
            $where['sdistrict'] = $article['sdistrict'];
        } else {
            $where['district'] = $article['district'];
        }
        $other_jobs = $this->job_models->get_jobs_id_arr('jobs', $where, "", 4);
        $data['other_jobs'] = $other_jobs;
        $data['article_category'] = $this->article_models->get_article_category_by_id($article['type_id']);
        $keywords = $article['title'];
        if (!empty($article_jobs) && !empty($article['district_cn'])) {
            foreach ($article_jobs as $key => $j) {
                if ($key + 1 < 4) {
                    $keywords.=$article['district_cn'] . $j['job_name'] . "编制招聘,";
                }
            }
        } elseif (!empty($article['district_cn'])) {
            $keywords .= $article['district_cn'] . "编制招聘," . $article['district_cn'] . "教师招聘," . $article['district_cn'] . "老师招聘";
        }
        $description = get_page_desc($article['content']);
        $ad = $this->ad_models->get_ad();
        $data['ad'] = $ad;
        $data['seo_keywords'] = trim($keywords, ",");
        $data['seo_description'] = $description;
        $this->load->view('/article/article_detail', $data);
    }

    //收藏简章
    public function favorites_article() {
        $article_id = $this->input->post('article_id');
        if (empty($article_id)) {
            $article_id = $this->session->userdata('article_id');
        } else {
            $this->session->set_userdata('article_id', $article_id);
        }
        $uid = $this->common_models->check_login();
        //$article_id = $this->input->post('article_id');
        $user = $this->user_models->get_user_info_by_id($uid);
        if ($user['utype'] != '2') {
            alert_to("必须是个人会员才可以收藏简章！", '/article/detail?article_id=' . $article_id);
        }
        if ($user['status'] == '2') {
            alert_to("您的账号处于暂停状态，请联系管理员设为正常后进行操作！", '/article/detail?article_id=' . $article_id);
        }
        $article = $this->article_models->get_article_by_id($article_id);
        $personal_article_favorites = $this->personal_models->get_favorites_article_by_uid_aid($uid, $article_id);
        if (!empty($personal_article_favorites)) {
            alert_to("收藏失败，收藏夹中已经存在此简章", '/article/detail?article_id=' . $article_id);
        }
        $in_data['personal_uid'] = $uid;
        $in_data['article_id'] = $article_id;
        $in_data['title'] = $article['title'];
        $in_data['addtime'] = time();
        $in_id = $this->personal_models->add_favorites_article($in_data);
        intval($in_id) > 0 ? alert_to("收藏成功", '/article/detail?article_id=' . $article_id) : alert_to("收藏失败", '/article/detail?article_id=' . $article_id);
    }

    //取消收藏简章
    public function del_favorites_article() {
        $article_id = $this->input->post('article_id');
        if (empty($article_id)) {
            $article_id = $this->session->userdata('article_id');
        } else {
            $this->session->set_userdata('article_id', $article_id);
        }
        $uid = $this->common_models->check_login();
        $user = $this->user_models->get_user_info_by_id($uid);
        if ($user['utype'] != '2') {
            alert_to("必须是个人会员才可以取消收藏简章！", '/article/detail?article_id=' . $article_id);
        }
        if ($user['status'] == '2') {
            alert_to("您的账号处于暂停状态，请联系管理员设为正常后进行操作！", '/article/detail?article_id=' . $article_id);
        }
        $article = $this->article_models->get_article_by_id($article_id);
        $personal_article_favorites = $this->personal_models->get_favorites_article_by_uid_aid($uid, $article_id);
        if (empty($personal_article_favorites)) {
            alert_to("取消收藏失败，收藏夹中不存在此简章！", '/article/detail?article_id=' . $article_id);
        }
        $del_id = $this->personal_models->del_favorites_article_in_id($personal_article_favorites['id'], $uid);
        intval($del_id) > 0 ? alert_to("取消成功！", '/article/detail?article_id=' . $article_id) : alert_to("取消失败！", '/article/detail?article_id=' . $article_id);
    }

    //投递简章职位
    public function apply_article() {
        $article = $this->article_models->get_article_by_id($article_id);
        $article_jobs = $this->article_models->get_article_jobs_by_article_id($article_id);
        if (empty($article_jobs) || $article['endtime'] < time()) {
            exit("职位无效！");
        }
        foreach ($article_jobs as $aj) {
            $str .= $aj['job_name'] . "||" . $aj['id'] . "-|-";
        }
        $str = trim($str, "-|-");
        echo $str;
    }

    //简章职位详情页
    public function article_jobs() {
        $timestamp = time();
        $article_job_id = $this->input->get('article_job_id');
        $article_jobs = $this->article_models->get_article_jobs_by_id($article_job_id);
        $article = $this->article_models->get_article_by_id($article_jobs['article_id']);
        $article['url'] = $this->article_models->get_article_url($article['id']);
        $other_job = $this->article_models->get_article_jobs_by_article_id($article_jobs['article_id']);
        if (strstr($article['district_cn'], "/")) {
            $district_arr = explode("/", $article['district_cn']);
            $article['district_cn'] = $district_arr[0];
            $article['sdistrict_cn'] = $district_arr[1];
        } else {
            $article['sdistrict_cn'] = $article['district_cn'];
        }
        $sdistrict_jobs = $this->job_models->get_jobs_id_arr('jobs', array('sdistrict' => $article['sdistrict'], 'deadline >' => $timestamp), "", 6);
        if (count($sdistrict_jobs) < 6) {
            $district_jobs = $this->job_models->get_jobs_id_arr('jobs', array('district' => $article['district'], 'deadline >' => $timestamp), "", intval(6 - count($sdistrict_jobs)));
            if (!empty($sdistrict_jobs)) {
                $sdistrict_jobs = !empty($district_jobs) ? array_merge($sdistrict_jobs, $district_jobs) : $sdistrict_jobs;
            } else {
                $sdistrict_jobs = $district_jobs;
            }
        }

        if (count($sdistrict_jobs) < 6) {
            $add_article_jobs = "";
            $article_jobs_tmp = $this->article_models->get_article_jobs_by_sdistrict($article['sdistrict'], $job_id, intval(6 - count($sdistrict_jobs)));
            if (!empty($article_jobs_tmp)) {
                foreach ($article_jobs_tmp as $a) {
                    $a['article'] = $this->article_models->get_article_by_id($a['article_id']);
                    $add_article_jobs[] = $a;
                }
            }
            if (!empty($sdistrict_jobs)) {
                $sdistrict_jobs = !empty($add_article_jobs) ? array_merge($sdistrict_jobs, $add_article_jobs) : $sdistrict_jobs;
            } else {
                $sdistrict_jobs = $add_article_jobs;
            }
        }
        //var_dump($sdistrict_jobs);

        if ($article_jobs['subclass'] > 0) {
            $subclass_jobs = $this->job_models->get_jobs_id_arr('jobs', array('subclass' => $article_jobs['subclass'], 'deadline >' => $timestamp), "", 6);
            if (count($subclass_jobs) < 6) {
                $category_jobs = $this->job_models->get_jobs_id_arr('jobs', array('category' => $article_jobs['category'], 'deadline >' => $timestamp), "", intval(6 - count($subclass_jobs)));
                if (!empty($subclass_jobs)) {
                    $subclass_jobs = !empty($category_jobs) ? array_merge($subclass_jobs, $category_jobs) : $subclass_jobs;
                } else {
                    $subclass_jobs = $category_jobs;
                }
            }
            if (count($subclass_jobs) < 6) {
                $add_article_jobs = $article_jobs_tmp = "";
                $article_jobs_tmp = $this->article_models->get_article_jobs_by_subclass($article_jobs['sdistrict'], $job_id, intval(6 - count($subclass_jobs)));
                if (!empty($article_jobs_tmp)) {
                    foreach ($article_jobs_tmp as $a) {
                        $a['article'] = $this->article_models->get_article_by_id($a['article_id']);
                        $add_article_jobs[] = $a;
                    }
                }
                if (!empty($subclass_jobs)) {
                    $subclass_jobs = !empty($add_article_jobs) ? array_merge($subclass_jobs, $add_article_jobs) : $subclass_jobs;
                } else {
                    $subclass_jobs = $add_article_jobs;
                }
            }
        } else {
            $subclass_jobs = $this->job_models->get_jobs_id_arr('jobs', array('category' => $article_jobs['category'], 'deadline >' => $timestamp), "", 6);
            if (count($subclass_jobs) < 6) {
                $add_article_jobs = $article_jobs_tmp = "";
                $article_jobs_tmp = $this->article_models->get_article_jobs_by_category($article_jobs['category'], $job_id, intval(6 - count($subclass_jobs)));
                if (!empty($article_jobs_tmp)) {
                    foreach ($article_jobs_tmp as $a) {
                        $a['article'] = $this->article_models->get_article_by_id($a['article_id']);
                        $add_article_jobs[] = $a;
                    }
                }
                if (!empty($subclass_jobs)) {
                    $subclass_jobs = !empty($article_jobs) ? array_merge($subclass_jobs, $add_article_jobs) : $subclass_jobs;
                } else {
                    $subclass_jobs = $add_article_jobs;
                }
            }
        }


        $data['sdistrict_jobs'] = $sdistrict_jobs;
        $data['subclass_jobs'] = $subclass_jobs;
        $data['article'] = $article;
        $data['job'] = $article_jobs;
        $data['other_job'] = $other_job;
        $this->load->view('/article/article_job_detail', $data);
    }

    //投递简章职位
    public function apply_article_send() {
        $article_job_id = $this->input->post('article_job_id');
        if (empty($article_job_id)) {
            $article_job_id = $this->session->userdata('article_job_id');
        } else {
            $this->session->set_userdata('article_job_id', $article_job_id);
        }
        $uid = $this->common_models->check_login();
        $article_jobs = $this->article_models->get_article_job_by_id($article_job_id);
        $article = $this->article_models->get_article_by_id($article_jobs['article_id']);
        $user = $this->user_models->get_user_info_by_id($uid);
        if ($user['utype'] != '2') {
            alert_to("必须是个人会员才可以投递简章职位！", '/article/detail?article_id=' . $article['id']);
        }
        if ($user['status'] == '2') {
            alert_to("您的账号处于暂停状态，请联系管理员设为正常后进行操作！", '/article/detail?article_id=' . $article['id']);
        }
        $article_jobs = $this->article_models->get_article_job_by_id($article_job_id);
        $article = $this->article_models->get_article_by_id($article_jobs['article_id']);
        $resume = $this->resume_models->get_one_resume($uid);
        if (empty($resume) || $resume['audit'] != 1) {
            alert_to("您还没填写简历或简历没通过审核", '/personal_center/resume');
        }
        if (empty($article_jobs) || $article['endtime'] < time()) {
            alert_to("职位无效！", '/article/detail?article_id=' . $article['id']);
        }
        $personal_job_apply = $this->personal_models->get_apply_article_by_uid_jid($uid, $article_job_id);
        if (!empty($personal_job_apply)) {
            alert_to("您投递过此职位，不能重复投递！", '/article/detail?article_id=' . $article['id']);
        }
        if ($resume['display_name'] == "2") {
            $personal_fullname = "N" . str_pad($resume['id'], 7, "0", STR_PAD_LEFT);
        } elseif ($resume['display_name'] == "3") {
            $personal_fullname = cut_str($resume['fullname'], 1, 0, "**");
        } else {
            $personal_fullname = $resume['fullname'];
        }
        $in_data['resume_id'] = $resume['id'];
        $in_data['resume_name'] = $personal_fullname;
        $in_data['personal_uid'] = intval($uid);
        $in_data['article_id'] = $article_jobs['article_id'];
        $in_data['article_job_id'] = $article_job_id;
        $in_data['title'] = $article['title'];
        $in_data['apply_addtime'] = time();
        $in_data['personal_look'] = 1;
        $in_data['to_email'] = $article['email'];
        $in_data['is_mobile'] = 1;
        $in_id = $this->personal_models->add_apply_article_jobs($in_data);
        $this->statistics_models->add_statistics_all('article_apply');
        $this->statistics_models->add_statistics_day('article_apply');
        $mailconfig = $this->common_models->get_email_config();
        if ($mailconfig['set_applyjobs'] == "1") {
            $this->send_job_apply_email($uid, $article_jobs, $article);
        }
        $this->common_models->write_memberslog($uid, 2, 1301, $user['username'], "投递了简历，职位:" . $article_jobs['job_name']);
        intval($in_id) > 0 ? alert_to("投递成功！", '/article/detail?article_id=' . $article['id']) : alert_to("投递失败！", '/article/detail?article_id=' . $article['id']);
    }

    public function send_job_apply_email($uid, $article_jobs, $article) {
        $resume = $this->resume_models->get_one_resume($uid);
        $resume_work = $this->resume_models->get_resume_works($uid);
        $note = "";
        $from_email = $resume['email'];
        $email = $article['email'];
        $word_content = "";
        $attachment_arr = "";
        $main_domain = $this->common_models->get_sys_config('site_domain') . "/";
        $wap_domain = $this->common_models->get_sys_config('wap_domain') . "/";
        $age = intval($resume['birthdate']) > 0 ? date('Y', time()) - $resume['birthdate'] : "未填写";
        if (!empty($resume_work)) {
            foreach ($resume_work as $rw) {
                $word_content .= '<div style="width:100%;float:left;display:block;">';
                $word_content .= '<p>' . $rw['startyear'] . '.' . $rw['startmonth'] . '至' . $rw['endyear'] . '.' . $rw['endmonth'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $rw['jobs'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $rw['companyname'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $rw['achievements'] . '</p>';
                $word_content .= '</div>';
            }
        }
        $content = '<style>
				a.logo {
					background:url("' . $main_domain . 'templates/default/images/header_logo.png") no-repeat;
					display: block;
					width: 160px;
					height: 44px;
					margin: 10px 40px;
					float: left;
				}
				.jl_peo_infor_li {
					float: left;
					width: 48.1%;
					padding-right: 9px;
					font-size: 14px;
					height: 30px;
					line-height: 30px;
					color: #333;
					overflow: hidden;
					zoom: 1;
				}
				.jl_peo_name {
					margin-bottom: 15px;
					float: left;
					color: #333;
					font-size: 28px;
					font-family: "微软雅黑";
					width: 400px;
					height: 28px;
					line-height: 28px;
					overflow: hidden;
					zoom: 1;
				}
			</style>
			<div style="background:#226cd6;height:4px;width:100%;"></div>
			<div style="height:65px;background:#fff;">
				<a title="教师招聘网" class="logo" href="' . $main_domain . '"><img src="' . $main_domain . 'templates/default/images/header_logo.png" /></a>
				<div style="font-size:16px;padding:18px 0;">您收到一份来自“教师招聘网”的简历投递</div>
			</div>
			<div style="background:#f5f5f5;padding:40px;">
				<div style="border-radius:4px;background:#fff;padding:20px;">
					<p>教师招聘网，中国最大的教师招聘专业网站，立即<a href="' . $main_domain . 'user/reg.php">注册</a>免费发布教师职位。已有帐号，请<a href="' . $main_domain . 'user/login.php">登录</a>。</p>
					<div style="margin-top:20px;border-top:1px dashed #ddd;padding:20px 0;">
						<p>本简历投递来自：<a href="' . $main_domain . 'morejobs/jobshow_' . $article_jobs['article_id'] . '.html">' . $article['title'] . '</a></p>
						<p>投递职位：' . $article_jobs['job_name'] . '</p>
						<p>附加说明：' . $note . '</p>
					</div>
					<div style="border-top:1px dashed #ddd;padding:20px 0;display:inline-block;">
                                            <div style="width:100%;float:left;display:block;">
                                                    <div class="jl_peo_name">
                                                        ' . $resume['fullname'] . '
                                                    </div>
                                            </div>
                                            <div class="jl_peo_infor_li">
                                                    <label>年　　龄：</label><span>' . $age . '</span>
                                            </div>
                                            <div class="jl_peo_infor_li">
                                                    <label>性　　别：</label><span>' . $resume['sex_cn'] . '</span>
                                            </div>
                                            <div class="jl_peo_infor_li">
                                                    <label>最高学历：</label><span>' . $resume['education_cn'] . '</span>
                                            </div>
                                            <div class="jl_peo_infor_li">
                                                    <label>工作经验：</label><span>' . $resume['experience_cn'] . '</span>
                                            </div>
                                            <div title="33.107" class="jl_peo_infor_li">
                                                    <label>联系方式：</label><span>' . $resume['telephone'] . '</span>
                                            </div>
                                            <div title="33.107" class="jl_peo_infor_li">
                                                    <label>电子邮箱：</label><span>' . $resume['email'] . '</span>
                                            </div>
                                            <div class="jl_peo_infor_li">
                                                    <label>期望地区：</label><span>' . $resume['district_cn'] . '</span>
                                            </div>
                                            <div class="jl_peo_infor_li">
                                                    <label>期望薪酬：</label><span>' . $resume['wage_cn'] . '</span>
                                            </div>';

        if ($resume['default_resume'] == 1) {
            $content.='<div style="width:100%;float:left;display:block;"><p style="font-size:16px; color:red; font-weight:bold; text-align:left;">简历详情请下载附件查看</p></div>';
        } else {
            $content.='<div style="width:100%;float:left;display:block;">
                            <div class="jl_peo_infor_li"><b>工作经历</b></div>
                            <div class="jl_peo_infor_li"></div>
                            ' . $word_content . '
                        </div>
                        <div style="width:100%;float:left;display:block;">
                            <div class="jl_peo_infor_li"><b>自我介绍</b></div>
                            <div class="jl_peo_infor_li"></div>
                            <p style="width:100%;float:left;display:block;">
                            ' . $resume['specialty'] . '
                            </p>
                            <div class="jl_peo_infor_li"></div>
                        </div>
                        <div style="clear:both;"></div>';
        }
        $content .= '<div style="width:100%;float:left;display:block;">
                        <a href="' . $main_domain . 'ProfileBank/ShowResume.shtml?id=' . $resume['id'] . '" style="text-decoration:none;background:#F87C10;padding:12px 20px;color:#fff;margin-top:20px;">查看完整简历</a>
                    </div>
                    </div>
                    </div>
                    </div>';
        if ($resume['default_resume'] == 1) {
            $attachment = $this->resume_models->get_resume_attachment_by_uid($uid);
            $site = get_base_site();
            $attachment_arr[0]['path'] = '/data2/www/' . $site . $attachment['path'] . $attachment['file_name'];
            $attachment_arr[0]['name'] = $attachment['file_name'];
        }
        $success = $this->common_models->smtp_mail($email, $resume['fullname'] . '-应聘' . $article_jobs['job_name'], $content, $from_email, '教师招聘网', $attachment_arr);
    }

}
