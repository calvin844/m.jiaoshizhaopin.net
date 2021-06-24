<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Job extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('ad_models');
        $this->load->model('common_models');
        $this->load->model('job_models');
        $this->load->model('company_models');
        $this->load->model('category_models');
        $this->load->model('personal_models');
        $this->load->model('resume_models');
        $this->load->model('user_models');
        $this->load->model('statistics_models');
        $this->load->model('wechat_models');
        $this->load->model('ad_models');
    }

    //职位列表页
    public function index($page = 1) {
        $job_type_data = array();
        $district = $this->input->get('district');
        $job_type = $this->input->get('job_type');
        $education = $this->input->get('education');
        $experience = $this->input->get('experience');
        $district_data = $this->category_models->get_provinces();
        if ($district > 0) {
            $district_info = $this->category_models->get_district($district);
        }
        if ($education > 0 && $education != 250) {
            $education_info = $this->category_models->get_categories_by_id($education);
        }
        if ($experience > 0) {
            $experience_info = $this->category_models->get_categories_by_id($experience);
        }
        //$district_info = !empty($district_info) ? $district_info : array('id' => 0, 'parentid' => 0, 'categoryname' => '全国');
        if (!empty($district_info)) {
            if ($district_info['parentid'] > 0) {
                $where = "sdistrict = " . $district_info['id'];
            } elseif ($district_info['id'] > 0) {
                $where = "district = " . $district_info['id'];
            } else {
                $where = "1";
            }
        } else {
            $where = "1";
        }
        if ($job_type > 0) {
            $job_type_data = $this->category_models->get_job_type($job_type);
            if ($job_type_data['parentid'] == 0) {
                $where .= " AND category = " . $job_type_data['id'];
            } else {
                $where .= " AND category = " . $job_type_data['parentid'];
                $where .= " AND subclass = " . $job_type_data['id'];
            }
        }
        $education > 0 ? $where .= " AND education = " . $education : "";
        $experience > 0 ? $where .= " AND experience = " . $experience : "";
        $where .= " AND id IN(SELECT id FROM `qs_jobs`)";
        $key = $this->input->get('key');
        $key = trim($key);
        $function = !empty($key) ? "jobs_search_key" : "jobs_search_stickrtime";
        $search_function = "get_" . $function;
        $total_function = $search_function . "_total";
        $total = $this->job_models->$total_function($where, $key);
        $page_arr = get_page_arr($total, $page);
        $job_arr = $this->job_models->get_jobs_id_arr($function, $where, $key, $page_arr['page_num'], $page_arr['offset'], 'refreshtime DESC', $recommend = 1, $emergency = 1);
        foreach ($job_arr as $a) {
            $job = $this->job_models->get_all_job_by_id($a['id']);
            $wage_cn = explode("/", $job['wage_cn']);
            $job['wage_cn'] = $wage_cn[0];
            !empty($job) ? $job_list[] = $job : "";
        }
        $data['education_data'] = $this->category_models->get_categories('QS_education');
        $data['experience_data'] = $this->category_models->get_categories('QS_experience');
        $data['parent_job_types'] = $this->category_models->get_parent_job_types();
        foreach ($data['parent_job_types'] as $pjt) {
            $job_types[$pjt['id']] = $this->category_models->get_job_types($pjt['id']);
        }
        $ad = $this->ad_models->get_ad();
        $data['ad'] = $ad;
        $data['job_types'] = $job_types;
        $data['district_data'] = $district_data;
        $data['district_info'] = $district_info;
        $data['education_info'] = $education_info;
        $data['experience_info'] = $experience_info;
        $data['job_type'] = $job_type_data;
        $data['education'] = $education;
        $data['experience'] = $experience;
        $data['key'] = $key;
        $data['job_list'] = $job_list;
        $data['page_arr'] = $page_arr;
        $this->load->view('/job/job_list', $data);
    }

    //职位详细页
    public function detail() {
        $this->session->set_userdata('job_flag', "1");
        $job_id = $this->input->get('job_id');
        $job = $this->job_models->get_all_job_by_id($job_id);
        $job['deadline_cn'] = date('Y-m-d', $job['deadline']);
        $job['tag_arr'] = "";
        if (!empty($job['tag'])) {
            $job_tag_arr = explode("|", $job['tag']);
            foreach ($job_tag_arr as $jta) {
                $arr = explode(",", $jta);
                $job['tag_arr'][] = $arr[1];
            }
        }
        if (!empty($job['wage_cn'])) {
            $wage_cn = explode("/", $job['wage_cn']);
            $job['wage_cn'] = $wage_cn[0];
        }
        $this->job_models->add_mobile_click($job_id);
        $company = $this->company_models->get_company($job['company_id']);
        $where['company_id'] = $job['company_id'];
        $where['id !='] = $job_id;
        $other_jobs_arr = $this->job_models->get_jobs_id_arr('jobs', $where, "", 5);
        foreach ($other_jobs_arr as $oj) {
            $wage_cn = explode("/", $oj['wage_cn']);
            $oj['wage_cn'] = $wage_cn[0];
            if ($oj['amount'] > 0) {
                $oj['info_str'] .= $oj['amount'] . '人/';
            }
            if ($oj['education_cn'] != "不限") {
                $oj['info_str'] .= $oj['education_cn'] . '/';
            }
            if ($oj['experience_cn'] != "不限") {
                $oj['info_str'] .= $oj['experience_cn'] . '/';
            }
            $oj['info_str'] = trim($oj['info_str'], "/");
            $other_jobs[] = $oj;
        }
        $uid = $this->session->userdata('uid');
        if (!empty($uid)) {
            $data['job_favorite'] = $this->personal_models->get_favorites_jobs_by_uid_jid($uid, $job_id);
            $this->job_models->add_view_job($uid, $job_id);
            $data['personal_job_apply'] = $this->personal_models->get_apply_jobs_by_uid_jid($uid, $job_id);
        }
        $ad = $this->ad_models->get_ad();
        $data['ad'] = $ad;
        $data['job'] = $job;
        $data['other_jobs'] = $other_jobs;
        $data['company'] = $company;
        $keywords = $job['jobs_name'] . "," . $job['companyname'] . "," . $job['companyname'] . "招聘," . $job['companyname'] . $job['jobs_name'] . "招聘";
        $description = !empty($job['contents']) ? get_page_desc($job['contents']) : $job['jobs_name'];
        $data['seo_keywords'] = $keywords;
        $data['seo_description'] = $description;
        $this->load->view('/job/job_detail', $data);
    }

    //收藏职位
    public function favorites_job() {
        $job_id = $this->input->post('job_id');
        if (empty($job_id)) {
            $job_id = $this->session->userdata('job_id');
        } else {
            $this->session->set_userdata('job_id', $job_id);
        }
        $alert_url = '/job/detail?job_id=' . $job_id;
        $uid = $this->common_models->check_login();
        $user = $this->user_models->get_user_info_by_id($uid);
        if ($user['utype'] != '2') {
            alert_to("必须是个人会员才可以收藏职位！", $alert_url);
        }
        if ($user['status'] == '2') {
            alert_to("您的账号处于暂停状态，请联系管理员设为正常后进行操作！", $alert_url);
        }
        $job = $this->job_models->get_all_job_by_id($job_id);
        $personal_job_favorites = $this->personal_models->get_favorites_jobs_by_uid_jid($uid, $job_id);
        if (!empty($personal_job_favorites)) {
            alert_to("收藏失败，收藏夹中已经存在此职位！", $alert_url);
        }
        $in_data['personal_uid'] = $uid;
        $in_data['jobs_id'] = $job_id;
        $in_data['jobs_name'] = $job['jobs_name'];
        $in_data['addtime'] = time();
        $in_id = $this->personal_models->add_favorites_jobs($in_data);
        intval($in_id) > 0 ? alert_to("收藏成功！", $alert_url) : alert_to("收藏失败！", $alert_url);
    }

    //取消收藏职位
    public function del_favorites_job() {
        $job_id = $this->input->post('job_id');
        if (empty($job_id)) {
            $job_id = $this->session->userdata('job_id');
        } else {
            $this->session->set_userdata('job_id', $job_id);
        }
        $alert_url = '/job/detail?job_id=' . $job_id;
        $uid = $this->common_models->check_login();
        $user = $this->user_models->get_user_info_by_id($uid);
        if ($user['utype'] != '2') {
            alert_to("必须是个人会员才可以取消收藏职位！", $alert_url);
        }
        if ($user['status'] == '2') {
            alert_to("您的账号处于暂停状态，请联系管理员设为正常后进行操作！", $alert_url);
        }
        $job = $this->job_models->get_all_job_by_id($job_id);
        $personal_job_favorites = $this->personal_models->get_favorites_jobs_by_uid_jid($uid, $job_id);
        if (empty($personal_job_favorites)) {
            alert_to("取消收藏失败，收藏夹中不存在此职位！", $alert_url);
        }
        $del_id = $this->personal_models->del_favorites_jobs_in_id($personal_job_favorites['did'], $uid);
        intval($del_id) > 0 ? alert_to("取消收藏成功！", $alert_url) : alert_to("取消收藏失败！", $alert_url);
    }

    //投递职位
    public function apply_job() {
        $job_id = $this->input->post('job_id');
        if (empty($job_id)) {
            $job_id = $this->session->userdata('job_id');
        } else {
            $this->session->set_userdata('job_id', $job_id);
        }
        $alert_url = '/job/detail?job_id=' . $job_id;
        $uid = $this->common_models->check_login();
        $user = $this->user_models->get_user_info_by_id($uid);
        if ($user['utype'] != '2') {
            alert_to("必须是个人会员才可以投递职位！", $alert_url);
        }
        if ($user['status'] == '2') {
            alert_to("您的账号处于暂停状态，请联系管理员设为正常后进行操作！", $alert_url);
        }
        $job = $this->job_models->get_all_job_by_id($job_id);
        $resume = $this->resume_models->get_one_resume($uid);
        if (empty($resume) || $resume['audit'] != 1) {
            alert_to("您还没有填写简历或者您的简历还没通过审核！", $alert_url);
        }
        $max_apply = $this->common_models->get_sys_config('apply_jobs_max');
        $personal_today_apply = $this->personal_models->get_apply_jobs_today_total_by_uid($uid);
        if ($max_apply - $personal_today_apply == 0 || $max_apply - $personal_today_apply < 0) {
            alert_to("您今天投简历数量已经超出最大限制！", $alert_url);
        }
        if ($job['tmp'] == 1 || $job['audit'] != 1 || $job['deadline'] < time()) {
            alert_to("职位无效！", $alert_url);
        }
        $personal_job_apply = $this->personal_models->get_apply_jobs_by_uid_jid($uid, $job_id);
        if (!empty($personal_job_apply)) {
            alert_to("您投递过此职位，不能重复投递！", $alert_url);
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
        $in_data['jobs_id'] = $job['id'];
        $in_data['jobs_name'] = $job['jobs_name'];
        $in_data['company_id'] = $job['company_id'];
        $in_data['company_name'] = $job['companyname'];
        $in_data['company_uid'] = $job['uid'];
        $in_data['resume_type'] = $resume['default_resume'];
        $in_data['notes'] = '';
        $in_data['apply_addtime'] = time();
        $in_data['personal_look'] = 1;
        $in_id = $this->personal_models->add_apply_jobs($in_data);
        $this->send_wechat_msg_apply_core($in_id);
        $ch = curl_init();
        // 设置URL和相应的选项
        curl_setopt($ch, CURLOPT_URL, "http://m.jiaoshizhaopin.net/company_center/send_wechat_apply_company_core/" . $in_id);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 抓取URL并把它传递给浏览器
        $data = curl_exec($ch);
        // 关闭cURL资源，并且释放系统资源
        curl_close($ch);
        $this->statistics_models->add_statistics_all('apply');
        $this->statistics_models->add_statistics_day('apply');
        $mailconfig = $this->common_models->get_email_config();
        $job_contact = $this->job_models->get_job_contact_by_job_id($job_id);
        if ($job['uid'] > 0) {
            $comuser = $this->user_models->get_user_info_by_id($job['uid']);
            if ($mailconfig['set_applyjobs'] == "1" && $comuser['email_audit'] == "1" && $job_contact['notify'] == "1") {
                $this->send_job_apply_email($uid, $job_id);
            }
            $main_domain = $this->common_models->get_sys_config('main_domain');
            $jobs_url = $main_domain . 'job/' . $job_id . '.html';
            $resume_url = $main_domain . 'ProfileBank/ShowResume.shtml?id=' . $resume['id'];
            $message = $personal_fullname . "申请了您发布的职位：<a href=\"" . $jobs_url . "\" target=\"_blank\">" . $job['jobs_name'] . "</a>,<a href=\"" . $resume_url . "\" target=\"_blank\">点击查看</a>";
            $this->common_models->write_pmsnotice($job['uid'], $user['username'], $message);
        }
        $this->common_models->write_memberslog($uid, 2, 1301, $user['username'], "投递了简历，职位:" . $job['jobs_name']);
        intval($in_id) > 0 ? alert_to("投递成功！", $alert_url) : alert_to("投递失败！", $alert_url);
    }

    public function send_job_apply_email($uid, $job_id, $email = "") {
        $resume = $this->resume_models->get_one_resume($uid);
        $resume_work = $this->resume_models->get_resume_works($uid);
        $job = $this->job_models->get_all_job_by_id($job_id);
        $job['contact'] = $this->job_models->get_job_contact_by_job_id($job_id);
        $job_apply = $this->personal_models->get_apply_jobs_by_uid_jid($uid, $job_id);
        $note = $job_apply['notes'];
        $from_email = $resume['email'];
        $email = empty($email) ? $job['contact']['email'] : $email;
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
						<p>本简历投递来自：<a href="' . $main_domain . 'job/' . $job['id'] . '.html">' . $job['jobs_name'] . '</a></p>
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

        if ($job_apply['resume_type'] == 1) {
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
        $content .= '
							<div style="width:100%;float:left;display:block;">
                                                            <a href="' . $main_domain . 'user/company/company_recruitment.php?act=apply_jobs" style="text-decoration:none;background:#F87C10;padding:12px 20px;color:#fff;margin-top:20px;">查看更多简历</a>
                                                        </div>
					</div>
				</div>
			</div>
			
			';
        if ($job_apply['resume_type'] == 1) {
            $attachment = $this->resume_models->get_resume_attachment_by_uid($uid);
            $site = get_base_site();
            $attachment_arr[0]['path'] = '/data2/www/' . $site . $attachment['path'] . $attachment['file_name'];
            $attachment_arr[0]['name'] = $attachment['file_name'];
        }
        $success = $this->common_models->smtp_mail($email, $resume['fullname'] . '-应聘' . $job['jobs_name'], $content, $from_email, '教师招聘网', $attachment_arr);
    }

    function send_wechat_msg_apply_core($apply_id) {
        $apply_jobs = $this->personal_models->get_apply_jobs_by_id($apply_id);
        $user = $this->user_models->get_user_info_by_id($apply_jobs['personal_uid']);
        if (!empty($user['wechat_openid'])) {
            $template_id = 'r-ZgDujR5PKqTUsY5A-MNJ__N9gEJv3POdAD5vvOviQ';
            $url = 'http://m.jiaoshizhaopin.net/personal_center/my_apply_jobs';
            $first = toGBK($user['username'] . "，您的简历已成功投递，坐等好消息吧！");
            $remark = toGBK('点击进入简历中心');
            $jobs_name = toGBK($apply_jobs['jobs_name']);
            $companyname = toGBK($apply_jobs['company_name']);
            $time = date("Y-m-d h:i", time());
            $this->wechat_models->request_wechat_msg('template', $user['wechat_openid'], $template_id, $url, $first, $jobs_name, $companyname, $time, $remark);
        } elseif (!empty($user['mobile'])) {
            $mobile = intval($user['mobile']);
            $this->common_models->send_sms($mobile, "", "SMS_174685006");
            /*
              $text = "【教师招聘网】您的职位申请已成功发送，请登录教师招聘网或关注教师招聘网公众号后进入“求职招聘-我的”查看";
              send_sms($mobile, $text);
              $sms_to_user = $this->user_models->get_user_info_by_mobile($mobile);
              $in['phone'] = $mobile;
              $in['utype'] = !empty($sms_to_user) ? $sms_to_user['utype'] : 0;
              $in['sms_type'] = 2;
              $in['back'] = 0;
              $in['addtime'] = time();
              $this->common_models->write_yunpian_sms_log($in);
             * 
             */
        }
    }

//自动换行
    function draw_txt_to($o_txt, $lenght) {
        $txt_tmp = iconv('gbk', 'utf-8', $o_txt);
        $strlen = iconv_strlen($txt_tmp, "UTF-8");
        if ($strlen > $lenght) {
            for ($i = 1; $i * $lenght < $strlen + $lenght; $i++) {
                $s = ($i - 1) * $lenght;
                $txt .= mb_substr($txt_tmp, $s, $lenght, "UTF-8") . "\n";
            }
        } else {
            $txt = $txt_tmp;
        }
        $strlen = $strlen > $lenght ? $lenght : $strlen;
        $txt_arr['txt'] = trim($txt);
        $txt_arr['strlen'] = $strlen;
        return $txt_arr;
    }

}
