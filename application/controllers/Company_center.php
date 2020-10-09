<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Company_center extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('user_models');
        $this->load->model('common_models');
        $this->load->model('category_models');
        $this->load->model('company_models');
        $this->load->model('resume_models');
        $this->load->model('job_models');
        $this->load->model('personal_models');
        $this->load->model('wechat_models');
    }

    public function index() {
        header('Location: /company_center/resume_apply');
    }

    public function my_info() {
        $uid = $this->common_models->check_login();
        $company = $this->company_models->get_company_by_uid($uid);
        $company_type_tmp = $this->category_models->get_categories('QS_company_type');
        foreach ($company_type_tmp as $e) {
            $e_tmp['id'] = toGBK($e['c_id'] . "|" . $e['c_name']);
            $e_tmp['value'] = toGBK($e['c_name']);
            $company_type[] = $e_tmp;
        }
        $data['company_type'] = json_encode($company_type);

        $company_scale_tmp = $this->category_models->get_categories('QS_scale');
        foreach ($company_scale_tmp as $e) {
            $e_tmp['id'] = toGBK($e['c_id'] . "|" . $e['c_name']);
            $e_tmp['value'] = toGBK($e['c_name']);
            $company_scale[] = $e_tmp;
        }

        $district_categories_tmp = $this->category_models->get_provinces();
        foreach ($district_categories_tmp as $dct) {
            $d = array();
            $d['id'] = $dct['id'] . "|" . toGBK($dct['categoryname']);
            $d['value'] = toGBK($dct['categoryname']);
            $sdistrict = $this->category_models->get_cities($dct['id']);
            foreach ($sdistrict as $s) {
                $sd['id'] = $s['id'] . "|" . toGBK($s['categoryname']);
                $sd['value'] = toGBK($s['categoryname']);
                $d['childs'][] = $sd;
            }
            $district_categories[] = $d;
        }
        $data['district_categories'] = json_encode($district_categories);
        $data['company_scale'] = json_encode($company_scale);
        $data['company'] = $company;
        $this->load->view('/company_center/my_info', $data);
    }

    public function my_info_save() {
        $uid = $this->common_models->check_login();
        $in_data['uid'] = $uid;
        $in_data['companyname'] = !empty($_POST['companyname']) ? trim($_POST['companyname']) : alert_to('请填写企业名称', "/company_center/my_info");
        $nature = !empty($_POST['nature']) ? trim($_POST['nature']) : alert_to('请选择企业性质', "/company_center/my_info");
        $nature_arr = explode("|", $nature);
        $in_data['nature'] = intval($nature_arr[0]);
        $in_data['nature_cn'] = trim($nature_arr[1]);
        $scale = !empty($_POST['scale']) ? trim($_POST['scale']) : alert_to('请选择企业规模', "/company_center/my_info");
        $scale_arr = explode("|", $scale);
        $in_data['scale'] = intval($scale_arr[0]);
        $in_data['scale_cn'] = trim($scale_arr[1]);
        $in_data['trade'] = 33;
        $in_data['trade_cn'] = "教育/培训";
        $in_data['registered'] = !empty($_POST['registered']) ? trim($_POST['registered']) : alert_to('请填写注册资金', "/company_center/my_info");
        $district = !empty($_POST['district']) ? trim($_POST['district']) : alert_to('请选择企业地区', "/company_center/my_info");
        $district_arr = explode(".", $district);
        $in_data['district'] = intval($district_arr[0]);
        $in_data['sdistrict'] = intval($district_arr[1]);
        $in_data['district_cn'] = !empty($_POST['district_cn']) ? trim($_POST['district_cn']) : alert_to('请选择企业地区', "/company_center/my_info");
        $in_data['address'] = !empty($_POST['address']) ? trim($_POST['address']) : alert_to('请填写详细地址', "/company_center/my_info");
        $in_data['audit'] = 2;
        $in_data['refreshtime'] = time();
        $company = $this->company_models->get_company_by_uid($uid);
        if (!empty($company)) {
            $this->company_models->update_company_by_uid($uid, $in_data);
        } else {
            $in_data['yellowpages'] = 1;
            $in_data['addtime'] = time();
            $this->company_models->add_company($in_data);
        }
        alert_to('保存成功！请等待管理员审核', "/company_center/my_info");
    }

    public function my_message($page = 1) {
        $uid = $this->common_models->check_login();
        $new = intval($_GET['new']) > 0 ? intval($_GET['new']) : 0;
        $message_total = $this->user_models->get_user_message_total($uid, $new);
        $page_arr = get_page_arr($message_total, $page);
        $message_list = $this->user_models->get_user_message_by_uid($uid, $new, $page_arr['page_num'], $page_arr['offset']);
        $data['new'] = $new;
        $data['page_arr'] = $page_arr;
        $data['message_list'] = $message_list;
        $this->load->view('/company_center/my_message', $data);
    }

    public function message_set_look() {
        $uid = $this->common_models->check_login();
        $look = intval($_POST['set_look']) == 1 ? 1 : 2;
        $did_arr = $_POST['list_id'];
        $this->user_models->set_message_look($did_arr, $look);
        alert_to('设置成功！', "history.go(-1);", 1);
    }

    public function message_del() {
        $uid = $this->common_models->check_login();
        $did_arr = $_POST['list_id'];
        $this->user_models->message_del($did_arr, $uid);
        alert_to('删除成功！', "history.go(-1);", 1);
    }

    public function my_account() {
        $uid = $this->common_models->check_login();
        $setmeal = $this->company_models->get_members_setmeal_by_uid($uid);
        if ($setmeal['endtime'] > 0) {
            $setmeal_endtime = sub_day($setmeal['endtime'], time());
        } else {
            $setmeal_endtime = "无限期";
        }
        $setmeal_rule = $this->company_models->get_setmeal_one($setmeal['setmeal_id']);
        $data['setmeal'] = $setmeal;
        $data['setmeal_rule'] = $setmeal_rule;
        $data['setmeal_endtime'] = $setmeal_endtime;
        $this->load->view('/company_center/my_account', $data);
    }

    public function my_index() {
        $uid = $this->common_models->check_login();
        $company = $this->company_models->get_company_by_uid($uid);
        $data['company'] = $company;
        $this->load->view('/company_center/my_index', $data);
    }

    public function up_logo() {
        $uid = $this->session->userdata('uid');
        !$_FILES['logo']['name'] ? exit('请上传图片！') : "";
        $company = $this->company_models->get_company_by_uid($uid);
        if (empty($company['id'])) {
            $in['uid'] = $uid;
            $this->company_models->add_company($in);
        }
        $host = get_base_site();
        $logo_dir = "/data2/www/" . $host . "/data/logo/" . date("Y/m/d/");
        make_dir($logo_dir);
        $setsqlarr['logo'] = _asUpFiles($logo_dir, "logo", 5 * 1024, 'gif/jpg/bmp/png/jpeg', true);
        $dir = "/data2/www/" . $host . "/data/logo/";
        unlink($dir . $company['logo']);
        $setsqlarr['logo'] = date("Y/m/d/") . $setsqlarr['logo'];
        $this->company_models->update_company_by_uid($uid, $setsqlarr);
        exit($setsqlarr['logo']);
    }

    public function resume_apply($page = 1) {
        $uid = $this->common_models->check_login();
        $job_id = intval($_GET['job_id']) > 0 ? intval($_GET['job_id']) : 0;
        $apply_total = $this->company_models->get_resume_apply_total_by_uid_jid($uid, $job_id);
        $page_arr = get_page_arr($apply_total, $page);
        $apply_data = $this->company_models->get_resume_apply_by_uid_jid($uid, $job_id, $page_arr['page_num'], $page_arr['offset']);
        foreach ($apply_data as $ad) {
            $apply_resume = $this->resume_models->get_one_resume($ad['personal_uid']);
            $ad['fullname'] = $apply_resume['fullname'];
            $ad['photo_img'] = $apply_resume['photo_img'];
            $apply_list[] = $ad;
        }
        $data['page_arr'] = $page_arr;
        $where = array('uid' => $uid);

        $job_list_tmp = $this->job_models->get_job_list($where, 1);
        $job_list = array(array('id' => 0, 'value' => toGBK('不限职位')));
        foreach ($job_list_tmp as $jlt) {
            $d = array();
            $d['id'] = $jlt['id'];
            $d['value'] = toGBK($jlt['jobs_name']);
            $job_list[] = $d;
        }

        $data['job_id'] = $job_id;
        $data['apply_list'] = $apply_list;
        $data['job_list'] = json_encode($job_list);
        $this->load->view('/company_center/resume_apply', $data);
    }

    public function resume_set_look() {
        $uid = $this->common_models->check_login();
        $look = intval($_POST['set_look']) == 1 ? 1 : 2;
        $did_arr = $_POST['did'];
        $this->company_models->set_apply_look($did_arr, $look);
        alert_to('设置成功！', "history.go(-1);", 1);
    }

    public function del_apply() {
        $uid = $this->common_models->check_login();
        $did_arr = $_POST['did'];
        foreach ($did_arr as $da) {
            $this->company_models->del_apply($da, $uid);
        }
        alert_to('删除成功！', "history.go(-1);", 1);
    }

    public function resume_down($page = 1) {
        $uid = $this->common_models->check_login();
        $down_total = $this->company_models->get_resume_down_total_by_uid($uid);
        $page_arr = get_page_arr($down_total, $page);
        $down_data = $this->company_models->get_resume_down_by_uid($uid, $page_arr['page_num'], $page_arr['offset']);
        foreach ($down_data as $dd) {
            $down_resume = $this->resume_models->get_resume_by_id($dd['resume_id']);
            $dd['fullname'] = $down_resume['fullname'];
            $dd['photo_img'] = $down_resume['photo_img'];
            $dd['education_cn'] = $down_resume['education_cn'];
            $dd['district_cn'] = $down_resume['district_cn'];
            $dd['experience_cn'] = $down_resume['experience_cn'];
            $dd['default_resume'] = $down_resume['default_resume'];
            $down_list[] = $dd;
        }
        $data['page_arr'] = $page_arr;
        $data['down_list'] = $down_list;
        $this->load->view('/company_center/resume_down', $data);
    }

    public function resume_to_collect($resume = 0) {
        $uid = $this->common_models->check_login();
        $did_arr = is_array($_POST['did']) ? $_POST['did'] : array($_POST['did']);
        $setmeal = $this->company_models->get_members_setmeal_by_uid($uid);
        foreach ($did_arr as $da) {
            $count_collect = $this->company_models->get_resume_collect_total_by_uid($uid);
            if ($count_collect >= $setmeal['talent_pool']) {
                alert_to('人才库已满！', "history.go(-1);", 1);
            }
            if ($resume == 0) {
                $down_data = $this->company_models->get_resume_down_by_id($da);
                $resume_id = $down_data['resume_id'];
            } else {
                $resume_id = $da;
            }
            $collect = $this->company_models->get_resume_collect_by_uid_rid($uid, $resume_id);
            if (!empty($collect)) {
                alert_to('该简历已收藏！', "history.go(-1);", 1);
            }
            $c_data['resume_id'] = $resume_id;
            $c_data['company_uid'] = $uid;
            $c_data['favoritesa_ddtime'] = time();
            $this->company_models->add_resume_collect($c_data);
        }
        alert_to('收藏成功！', "history.go(-1);", 1);
    }

    public function resume_to_down() {
        $uid = $this->common_models->check_login();
        $did_arr = is_array($_POST['did']) ? $_POST['did'] : array($_POST['did']);
        $setmeal = $this->company_models->get_members_setmeal_by_uid($uid);
        foreach ($did_arr as $da) {
            $resumeshow = $this->resume_models->get_resume_by_id($da);
            if ($resumeshow['talent'] == '2' && $setmeal['download_resume_senior'] < 1) {
                alert_to('您的高级简历下载数不足！', "history.go(-1);", 1);
            } elseif ($resumeshow['talent'] != '2' && $setmeal['download_resume_ordinary'] < 1) {
                alert_to('您的普通简历下载数不足！', "history.go(-1);", 1);
            }
            if ($resumeshow['display_name'] == "2") {
                $resumeshow['name'] = "N" . str_pad($resumeshow['resume_id'], 7, "0", STR_PAD_LEFT);
            } elseif ($resumeshow['display_name'] == "3") {
                $resumeshow['name'] = cut_str($resumeshow['fullname'], 1, 0, "**");
            } else {
                $resumeshow['name'] = $resumeshow['fullname'];
            }
            $down = $this->company_models->get_resume_down_by_uid_rid($uid, $resumeshow['id']);
            if (!empty($down)) {
                alert_to('该简历已下载！', "history.go(-1);", 1);
            }
            $company = $this->company_models->get_company_by_uid($uid);
            $in_down['resume_id'] = $da;
            $in_down['resume_uid'] = $resumeshow['uid'];
            $in_down['resume_name'] = $resumeshow['name'];
            $in_down['company_uid'] = $uid;
            $in_down['company_name'] = $company['companyname'];
            $in_down['down_addtime'] = time();
            $down_id = $this->company_models->add_resume_down($in_down);
            if (!$down_id > 0) {
                alert_to('下载简历失败！', "history.go(-1);", 1);
            }
            $action_txt = $resumeshow['talent'] == '2' ? "download_resume_senior" : "download_resume_ordinary";
            $this->company_models->set_members_setmeal_by_uid($uid, array($action_txt => $action_txt . '-1'));
            $setmeal = $this->company_models->get_members_setmeal_by_uid($uid);
            $resume_user = $this->user_models->get_user_info_by_id($resumeshow['uid']);
            $log_txt = $resumeshow['talent'] == '2' ? "高级" : "普通";
            $log_num = $resumeshow['talent'] == '2' ? 1005 : 1004;
            $this->common_models->write_memberslog($uid, 1, 9002, $_SESSION['username'], "下载了 " . $resume_user['username'] . " 发布的" . $log_txt . "简历,还可以下载 " . $setmeal[$action_txt] . " 份" . $log_txt . "简历", 2, $log_num, "下载" . $log_txt . "简历", "1", $setmeal[$action_txt]);
            $this->common_models->write_memberslog($uid, 1, 4001, $_SESSION['username'], "下载了 " . $resume_user['username'] . " 发布的简历");
            alert_to('下载成功！', "history.go(-1);", 1);
        }
    }

    public function del_down() {
        $uid = $this->common_models->check_login();
        $did_arr = $_POST['did'];
        if (!empty($did_arr)) {
            foreach ($did_arr as $da) {
                $this->company_models->del_down($da, $uid);
            }
            alert_to('删除成功！', "history.go(-1);", 1);
        } else {
            alert_to('没有选择简历！', "history.go(-1);", 1);
        }
    }

    public function resume_collect($page = 1) {
        $uid = $this->common_models->check_login();
        $collect_total = $this->company_models->get_resume_collect_total_by_uid($uid);
        $page_arr = get_page_arr($collect_total, $page);
        $collect_data = $this->company_models->get_resume_collect_by_uid($uid, $page_arr['page_num'], $page_arr['offset']);
        foreach ($collect_data as $cd) {
            $collect_resume = $this->resume_models->get_resume_by_id($cd['resume_id']);
            if ($collect_resume['display_name'] == "2") {
                $cd['fullname'] = "N" . str_pad($cd['resume_id'], 7, "0", STR_PAD_LEFT);
            } elseif ($collect_resume['display_name'] == "3") {
                $cd['fullname'] = cut_str($collect_resume['fullname'], 1, 0, "**");
            } else {
                $cd['fullname'] = $collect_resume['fullname'];
            }
            $cd['photo_img'] = $collect_resume['photo_img'];
            $cd['education_cn'] = $collect_resume['education_cn'];
            $cd['district_cn'] = $collect_resume['district_cn'];
            $cd['experience_cn'] = $collect_resume['experience_cn'];
            $cd['default_resume'] = $collect_resume['default_resume'];
            $collect_list[] = $cd;
        }
        $data['page_arr'] = $page_arr;
        $data['collect_list'] = $collect_list;
        $this->load->view('/company_center/resume_collect', $data);
    }

    public function del_collect() {
        $uid = $this->common_models->check_login();
        $did_arr = $_POST['did'];
        if (!empty($did_arr)) {
            foreach ($did_arr as $da) {
                $this->company_models->del_collect($da, $uid);
            }
            alert_to('删除成功！', "history.go(-1);", 1);
        } else {
            alert_to('没有选择简历！', "history.go(-1);", 1);
        }
    }

    public function resume_interview($page = 1) {
        $uid = $this->common_models->check_login();
        $job_id = intval($_GET['job_id']) > 0 ? intval($_GET['job_id']) : 0;
        $interview_total = $this->company_models->get_resume_interview_total_by_uid($uid);
        $page_arr = get_page_arr($interview_total, $page);
        $interview_data = $this->company_models->get_resume_interview_by_uid($uid, $job_id, $page_arr['page_num'], $page_arr['offset']);
        foreach ($interview_data as $id) {
            $interview_resume = $this->resume_models->get_resume_by_id($id['resume_id']);
            $id['fullname'] = $interview_resume['fullname'];
            $id['photo_img'] = $interview_resume['photo_img'];
            $interview_list[] = $id;
        }
        $where = array('uid' => $uid);
        $job_list = $this->job_models->get_job_list($where, 1);
        $data['job_id'] = $job_id;
        $data['job_list'] = $job_list;
        $data['page_arr'] = $page_arr;
        $data['interview_list'] = $interview_list;
        $this->load->view('/company_center/resume_interview', $data);
    }

    public function resume_to_interview() {
        $uid = $this->common_models->check_login();
        $rid = isset($_POST['did']) ? intval($_POST['did']) : alert_to('简历信息错误！', "history.go(-1);", 1);
        $jobs_id = isset($_POST['jobs_id']) ? intval($_POST['jobs_id']) : alert_to('职位信息错误！', "history.go(-1);", 1);
        $notes = isset($_POST['notes']) ? trim($_POST['notes']) : "";
        $setmeal = $this->company_models->get_members_setmeal_by_uid($uid);
        $o_interview = $this->company_models->get_resume_interview_by_uid_jid_rid($uid, $jobs_id, $rid);
        if (!empty($o_interview)) {
            alert_to('重复邀请！', "history.go(-1);", 1);
        }
        $jobs = $this->job_models->get_all_job_by_id($jobs_id);
        $resume = $this->resume_models->get_resume_by_id($rid);
        if ($resume['talent'] == '2' && $setmeal['interview_senior'] < 1) {
            alert_to('您的高级简历邀请数不足！', "history.go(-1);", 1);
        } elseif ($resume['talent'] != '2' && $setmeal['interview_ordinary'] < 1) {
            alert_to('您的普通简历邀请数不足！', "history.go(-1);", 1);
        }
        $in_interview['resume_id'] = $resume['id'];
        $in_interview['resume_addtime'] = $resume['addtime'];
        if ($resume['display_name'] == "2") {
            $in_interview['resume_name'] = "N" . str_pad($resume['id'], 7, "0", STR_PAD_LEFT);
        } elseif ($resume['display_name'] == "3") {
            $in_interview['resume_name'] = cut_str($resume['fullname'], 1, 0, "**");
        } else {
            $in_interview['resume_name'] = $resume['fullname'];
        }
        $in_interview['resume_uid'] = $resume['uid'];
        $in_interview['company_id'] = $jobs['company_id'];
        $in_interview['company_addtime'] = $jobs['company_addtime'];
        $in_interview['company_name'] = $jobs['companyname'];
        $in_interview['company_uid'] = $uid;
        $in_interview['jobs_id'] = $jobs['id'];
        $in_interview['jobs_name'] = $jobs['jobs_name'];
        $in_interview['jobs_addtime'] = $jobs['addtime'];
        $in_interview['notes'] = $notes;
        $in_interview['personal_look'] = 1;
        $in_interview['interview_addtime'] = time();
        $in_interview_id = $this->company_models->add_invitation($in_interview);
        $resume_user = $this->user_models->get_user_info_by_id($resume['uid']);
        $action_txt = $resumeshow['talent'] == '2' ? "interview_senior" : "interview_ordinary";
        $this->company_models->set_members_setmeal_by_uid($uid, array($action_txt => $action_txt . '-1'));
        $setmeal = $this->company_models->get_members_setmeal_by_uid($uid);
        $log_txt = $resumeshow['talent'] == '2' ? "高级" : "普通";
        $log_num = $resumeshow['talent'] == '2' ? 1007 : 1006;
        $this->common_models->write_memberslog($uid, 1, 9002, $_SESSION['username'], "邀请了 " . $resume_user['username'] . " 面试，还可以邀请" . $log_txt . "人才 " . $setmeal[$action_txt] . " 次", 2, $log_num, "邀请" . $log_txt . "人才面试", "1", $setmeal[$action_txt]);
        $this->common_models->write_memberslog($uid, 1, 6001, $_SESSION['username'], "邀请了 " . $resume_user['username'] . " 面试");
        $jobs_url = "http://www.jiaoshizhaopin.net/job/" . $jobs['id'] . ".html";
        $company_url = "http://www.jiaoshizhaopin.net/school/" . $jobs['company_id'] . ".html";
        $message = $jobs['companyname'] . '邀请您参加公司面试，面试职位：<a href="' . $jobs_url . '" target="_blank"> ' . $jobs['jobs_name'] . ' </a>，<a href="' . $company_url . '" target="_blank">点击查看公司详情</a>';
        $this->common_models->write_pmsnotice($resume['uid'], $resume_user['username'], $message);
        $company_user = $this->user_models->get_user_info_by_id($uid);
        $ch = curl_init();
        // 设置URL和相应的选项
        curl_setopt($ch, CURLOPT_URL, "http://m.jiaoshizhaopin.net/personal_center/send_wechat_interview_core/" . $in_interview_id);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 抓取URL并把它传递给浏览器
        $data = curl_exec($ch);
        // 关闭cURL资源，并且释放系统资源
        curl_close($ch);
        alert_to('邀请成功！', "history.go(-1);", 1);
    }

    public function job_to_stop($display = 1) {
        $uid = $this->common_models->check_login();
        $did_arr = $_POST['did'];
        if (!empty($did_arr)) {
            $this->job_models->set_jobs_display_by_id($uid, $did_arr, $display);
            $this->company_models->distribution_jobs($did_arr, $uid);
            $display_cn = $display == 1 ? "开始" : "暂停";
            $id_cn = implode(",", $did_arr);
            $this->common_models->write_memberslog($uid, 1, 2005, $_SESSION['username'], "将职位激活状态设为:" . $display_cn . ",职位ID为：" . $id_cn);
            alert_to('设置成功！', "history.go(-1);", 1);
        } else {
            alert_to('没有选择职位！', "history.go(-1);", 1);
        }
    }

    public function job_to_delete() {
        $uid = $this->common_models->check_login();
        $did_arr = $_POST['did'];
        if (!empty($did_arr)) {
            $this->job_models->delete_jobs($did_arr, $uid);
            $id_cn = implode(",", $did_arr);
            $this->common_models->write_memberslog($uid, 1, 2003, $_SESSION['username'], "删除职位（" . $id_cn . "）");
            alert_to('删除成功！', "history.go(-1);", 1);
        } else {
            alert_to('没有选择职位！', "history.go(-1);", 1);
        }
    }

    public function job_to_refresh() {
        $uid = $this->common_models->check_login();
        $did_arr = $_POST['did'];
        if (!empty($did_arr)) {
            $username = trim($_SESSION['username']);
            foreach ($did_arr as $da) {
                $jobs_info = $this->job_models->get_all_job_by_id($da);
                if ($jobs_info['deadline'] < time()) {
                    alert_to('已到期，请先延期！', "history.go(-1);", 1);
                }
                $setmeal = $this->company_models->get_members_setmeal_by_uid($uid);
                if (empty($setmeal)) {
                    alert_to('您还没有开通服务，请开通！', "/company_center/my_account");
                } elseif ($setmeal['endtime'] < time() && $setmeal['endtime'] <> "0") {
                    alert_to('您的服务已经到期，请重新开通！', "/company_center/my_account");
                } else {
                    $duringtime = time() - $jobs_info['refreshtime'];
                    $refresh_time = $this->company_models->get_today_refresh_times($uid, "1001");
                    if ($setmeal['refresh_jobs_time'] != 0 && ($refresh_time >= $setmeal['refresh_jobs_time'])) {
                        alert_to('每天最多只能刷新' . $setmeal['refresh_jobs_time'] . '次,您今天已超过最大刷新次数限制！', "history.go(-1);", 1);
                    } elseif ($setmeal['refresh_jobs_space'] > 0 && $duringtime <= $setmeal['refresh_jobs_space']) {
                        alert_to($setmeal['refresh_jobs_space'] . "分钟内不能重复刷新职位！", "history.go(-1);", 1);
                    }
                }
                $this->job_models->refresh_jobs($did_arr, $uid);
            }
            $this->common_models->write_memberslog($uid, 1, 2004, $username, "刷新职位");
            $this->company_models->write_refresh_log($uid, 1001);
            alert_to('刷新成功！', "history.go(-1);", 1);
        } else {
            alert_to('没有选择职位！', "history.go(-1);", 1);
        }
    }

    public function jobs_release($page = 1) {
        $uid = $this->common_models->check_login();
        $release_total = $this->company_models->get_jobs_release_total_by_uid($uid);
        $page_arr = get_page_arr($release_total, $page);
        $release_data = $this->company_models->get_jobs_release_by_uid($uid, $page_arr['page_num'], $page_arr['offset']);
        foreach ($release_data as $rd) {
            $resume_total = $this->company_models->get_apply_jobs_total_by_cid_jid($uid, $rd['id']);
            $rd['resume_total'] = $resume_total;
            $release_list[] = $rd;
        }
        $data['page_arr'] = $page_arr;
        $data['release_list'] = $release_list;
        $this->load->view('/company_center/jobs_release', $data);
    }

    public function jobs_audit($page = 1) {
        $uid = $this->common_models->check_login();
        $audit_total = $this->company_models->get_jobs_audit_total_by_uid($uid);
        $page_arr = get_page_arr($audit_total, $page);
        $audit_list = $this->company_models->get_jobs_audit_by_uid($uid, $page_arr['page_num'], $page_arr['offset']);
        $data['page_arr'] = $page_arr;
        $data['audit_list'] = $audit_list;
        $this->load->view('/company_center/jobs_audit', $data);
    }

    public function jobs_stop($page = 1) {
        $uid = $this->common_models->check_login();
        $stop_total = $this->company_models->get_jobs_stop_total_by_uid($uid);
        $page_arr = get_page_arr($stop_total, $page);
        $stop_data = $this->company_models->get_jobs_stop_by_uid($uid, $page_arr['page_num'], $page_arr['offset']);
        foreach ($stop_data as $sd) {
            $resume_total = $this->company_models->get_apply_jobs_total_by_cid_jid($uid, $sd['id']);
            $sd['resume_total'] = $resume_total;
            $stop_list[] = $sd;
        }
        $data['page_arr'] = $page_arr;
        $data['stop_list'] = $stop_list;
        $this->load->view('/company_center/jobs_stop', $data);
    }

    public function jobs_nopass($page = 1) {
        $uid = $this->common_models->check_login();
        $nopass_total = $this->company_models->get_jobs_nopass_total_by_uid($uid);
        $page_arr = get_page_arr($nopass_total, $page);
        $nopass_data = $this->company_models->get_jobs_nopass_by_uid($uid, $page_arr['page_num'], $page_arr['offset']);
        foreach ($nopass_data as $nd) {
            $reason = $this->job_models->get_job_audit_reason($nd['id']);
            $nd['reason'] = $reason['reason'];
            $nopass_list[] = $nd;
        }
        $data['page_arr'] = $page_arr;
        $data['nopass_list'] = $nopass_list;
        $this->load->view('/company_center/jobs_nopass', $data);
    }

    function send_wechat_apply_company_core($apply_id) {
        $apply_jobs = $this->personal_models->get_apply_jobs_by_id($apply_id);
        if ($apply_jobs['company_uid'] > 0) {
            $company = $this->user_models->get_user_info_by_id($apply_jobs['company_uid']);
            $resume = $this->resume_models->get_one_resume($apply_jobs['personal_uid']);
            $contact = $this->job_models->get_job_contact_by_job_id($apply_jobs['jobs_id']);
            if (!empty($company['wechat_openid'])) {
                $template_id = 'O1meiHO99yiu6-IcTooB3CLjSDAmri86LyLppJHo4Qk';
                $url = 'http://m.jiaoshizhaopin.net/company_center/resume_apply';
                $first = toGBK($company['username'] . "，您的职位“" . $apply_jobs['jobs_name'] . "”收到了新的简历投递");
                $companyname = toGBK($apply_jobs['company_name']);
                $resume_name = toGBK($resume['title']);
                $jobs_name = toGBK($apply_jobs['jobs_name']);
                $full_name = toGBK($resume['fullname']);
                $specialty = toGBK($resume['specialty']);
                $remark = toGBK('点击进入查看');
                $this->wechat_models->request_wechat_msg('template|4', $company['wechat_openid'], $template_id, $url, $first, $companyname, $resume_name, $jobs_name, $full_name, $specialty, $remark);
            } elseif (!empty($contact['telephone'])) {
                $mobile = intval($contact['telephone']);
                $this->common_models->send_sms($mobile, "", "SMS_174650924");
                /*
                  $text = "【教师招聘网】您收到新的职位申请，请尽快登录教师招聘网或关注教师招聘网公众号后进入“求职招聘-我的”进行处理";
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
    }

    function send_wechat_job_audit_core($jobs_id) {
        $jobs = $this->job_models->get_all_job_by_id($jobs_id);
        $company = $this->user_models->get_user_info_by_id($jobs['uid']);
        $contact = $this->job_models->get_job_contact_by_job_id($jobs_id);
        if (!empty($company['wechat_openid'])) {
            $template_id = 'OJrdhD0_280KoQxlFrN5c1tir-yl1E-NDRhu8Bo2MfA';
            if ($jobs['audit'] == 1) {
                $url = 'http://m.jiaoshizhaopin.net/company_center/jobs_release';
                $first = toGBK($company['username'] . "，恭喜您的职位“" . $jobs['jobs_name'] . "”已经通过审核");
                $audit_cn = toGBK("通过");
                $reason = toGBK("通过审核");
            } elseif ($jobs['audit'] == 3) {
                $url = 'http://m.jiaoshizhaopin.net/company_center/jobs_nopass';
                $first = toGBK($company['username'] . "，很抱歉，您的职位“" . $jobs['jobs_name'] . "”未能通过审核");
                $audit_cn = toGBK("不通过");
                $reason_data = $this->job_models->get_job_audit_reason($jobs_id);
                $reason = trim($reason_data['reason'], "原因：");
                $reason = toGBK($reason);
            }
            $jobs_name = toGBK($jobs['jobs_name']);
            $remark = toGBK('点击进入查看');
            $this->wechat_models->request_wechat_msg('template|5', $company['wechat_openid'], $template_id, $url, $first, $jobs_name, $audit_cn, $reason, $remark);
        } elseif (!empty($contact['telephone'])) {
            $mobile = intval($contact['telephone']);
            $this->common_models->send_sms($mobile, "", "SMS_174650925");
            /*
              $text = "【教师招聘网】您的职位审核有结果了，请登录教师招聘网或关注教师招聘网公众号后进入“求职招聘-我的”进行处理";
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

}
