<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Personal_center extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('common_models');
        $this->load->model('company_models');
        $this->load->model('user_models');
        $this->load->model('resume_models');
        $this->load->model('category_models');
        $this->load->model('personal_models');
        $this->load->model('article_models');
        $this->load->model('job_models');
        $this->load->model('wechat_models');
    }

    public function index() {
        $utype = $this->session->userdata('utype');
        if ($utype == '1') {
            echo '<script>window.location.href ="/company_center"</script>';
            exit;
        }
        $uid = $this->common_models->check_login();
        $resume = $this->resume_models->get_one_resume($uid);
        $resume['refreshtime'] = date("Y.m.d", $resume['refreshtime']);
        $data['resume'] = $resume;
        $this->load->view('/personal_center/personal_index', $data);
    }

    public function my_system() {
        $uid = $this->common_models->check_login();
        $this->load->view('/personal_center/my_system', $data);
    }

    public function my_resume() {
        $uid = $this->common_models->check_login();
        $this->load->view('/personal_center/my_resume', $data);
    }

    public function my_wechat() {
        $uid = $this->common_models->check_login();
        $user_info = $this->user_models->get_user_info_by_id($uid);
        $data['user_info'] = $user_info;
        $this->load->view('/personal_center/my_wechat', $data);
    }

    public function save_wechat() {
        $uid = $this->common_models->check_login();
        $wechat_img = $this->input->post('wechat_img');
        $wechat_name = $this->input->post('wechat_name');
        if (!empty($wechat_img) || !empty($wechat_name)) {
            $user = $this->user_models->get_user_info_by_id($uid);
            $host = get_base_site();
            if (!empty($wechat_img)) {
                $dir = "/data2/www/" . $host . "/data/pay_img/";
                unlink($dir . $user['wechat_img']);
                $up_arr['wechat_img'] = $wechat_img;
            }
            if (!empty($wechat_name)) {
                $up_arr['wechat_name'] = !empty($wechat_name) ? trim($wechat_name) : "";
            }
            $this->user_models->up_user_info_by_id($uid, $up_arr);
        }
        alert_to("保存成功！", '/personal_center');
    }

    public function resume() {
        $uid = $this->common_models->check_login();
        $resume = $this->resume_models->get_one_resume($uid);
        if (!empty($resume)) {
            $resume['photo_img'] = empty($resume['photo_img']) ? "no_photo.gif" : $resume['photo_img'];
            $data['resume'] = $resume;
            $intention_jobs = explode(",", $resume['intention_jobs']);
            $data['intention_jobs'] = $intention_jobs[0];
            $data['educations'] = $this->resume_models->get_resume_educations($uid);
            $data['works'] = $this->resume_models->get_resume_works($uid);
            $data['trainings'] = $this->resume_models->get_resume_trainings($uid);
            $data['certificates'] = $this->resume_models->get_resume_certificates($uid);
            $resume_tag = explode("|", $resume['tag']);
            foreach ($resume_tag as $rt) {
                $arr = explode(",", $rt);
                $tag_str .= $arr[1] . "、";
            }
            $data['tag'] = trim($tag_str, "、");
            $this->load->view('/personal_center/personal_resume', $data);
        } else {
            header('Location: /personal_center/edit_basic');
        }
    }

    public function up_photo() {
        $uid = $this->session->userdata('uid');
        !$_FILES['photo']['name'] ? exit('请上传图片！') : "";
        $resume_basic = $this->resume_models->get_one_resume($uid);
        if (empty($resume_basic['id'])) {
            $in['uid'] = $uid;
            $this->resume_models->add_resume($in);
        }
        $host = get_base_site();
        $photo_dir = "/data2/www/" . $host . "/data/photo/" . date("Y/m/d/");
        make_dir($photo_dir);
        $setsqlarr['photo_img'] = _asUpFiles($photo_dir, "photo", 5 * 1024, 'gif/jpg/bmp/png/jpeg', true);
        $dir = "/data2/www/" . $host . "/data/photo/";
        unlink($dir . $resume_basic['photo_img']);
        $setsqlarr['photo_img'] = date("Y/m/d/") . $setsqlarr['photo_img'];
        $this->resume_models->update_resume_by_uid($uid, $setsqlarr);
        exit($setsqlarr['photo_img']);
    }

    public function up_certificate() {
        $uid = $this->session->userdata('uid');
        $resume_certificate = $this->resume_models->get_resume_certificates($uid);
        if (count($resume_certificate) > 4) {
            exit("-9");
        }
        $resume_basic = $this->resume_models->get_one_resume($uid);
        if (empty($resume_basic['id'])) {
            $in['uid'] = $uid;
            $resume_basic['id'] = $this->resume_models->add_resume($in);
        }
        $host = get_base_site();
        $photo_dir = "/data2/www/" . $host . "/data/resume_certificate/" . date("Y/m/d/");
        make_dir($photo_dir);
        $setsqlarr['path'] = _asUpFiles($photo_dir, "certificate", 5 * 1024, 'gif/jpg/bmp/png/jpeg', true);
        $setsqlarr['path'] = date("Y/m/d/") . $setsqlarr['path'];
        exit($setsqlarr['path']);
    }

    public function up_wechat_img() {
        $uid = $this->session->userdata('uid');
        $resume_basic = $this->resume_models->get_one_resume($uid);
        if (empty($resume_basic['id'])) {
            $in['uid'] = $uid;
            $resume_basic['id'] = $this->resume_models->add_resume($in);
        }
        $host = get_base_site();
        $photo_dir = "/data2/www/" . $host . "/data/pay_img/" . date("Y/m/d/");
        make_dir($photo_dir);
        $setsqlarr['path'] = _asUpFiles($photo_dir, "wechat_img_input", 5 * 1024, 'gif/jpg/bmp/png/jpeg', true);
        $setsqlarr['path'] = date("Y/m/d/") . $setsqlarr['path'];
        exit($setsqlarr['path']);
    }

    public function make_info() {
        $uid = $this->common_models->check_login();
        $utype = $this->session->userdata('utype');
        if ($utype == '1') {
            echo '<script>window.location.href ="/company_center"</script>';
            exit;
        }
        $basic = $this->resume_models->get_one_resume($uid);
        $select_sex = array(array('id' => toGBK("1|男"), 'value' => toGBK("男")), array('id' => toGBK("2|女"), 'value' => toGBK("女")));
        $data['select_sex'] = json_encode($select_sex);
        $now_year = date('Y');
        for ($i = $now_year - 80; $i < $now_year; $i++) {
            $year_tmp['id'] = $i;
            $year_tmp['value'] = $i;
            $birthdate_year[] = $year_tmp;
        }
        $data['birthdate_year'] = json_encode($birthdate_year);
        $education_categories_tmp = $this->category_models->get_categories('QS_education');
        foreach ($education_categories_tmp as $e) {
            $e_tmp['id'] = toGBK($e['c_id'] . "|" . $e['c_name']);
            $e_tmp['value'] = toGBK($e['c_name']);
            $education_categories[] = $e_tmp;
        }
        $data['education_categories'] = json_encode($education_categories);
        for ($i = date('Y') - 50; $i < date('Y') + 51; $i++) {
            $i_arr['id'] = $i;
            $i_arr['value'] = $i;
            for ($i2 = 1; $i2 < 13; $i2++) {
                $i2_arr['id'] = $i2;
                $i2_arr['value'] = $i2;
                $i_arr['childs'][] = $i2_arr;
            }
            $select_time[] = $i_arr;
        }
        $data['select_time'] = json_encode($select_time);
        $user_info = $this->user_models->get_user_info_by_id($uid);
        $basic['telephone'] = !empty($basic['telephone']) ? $basic['telephone'] : $user_info['mobile'];
        $data['now_year'] = $now_year;
        $data['basic'] = $basic;
        $this->load->view('/personal_center/make_info', $data);
    }

    public function make_info_save() {
        $uid = $this->common_models->check_login();
        $error = "";
        $insert_id = 0;
        $in_data['uid'] = $uid;
        $fullname = $this->input->post('fullname');
        $in_data['fullname'] = !empty($fullname) ? trim($fullname) : $error = "请填写姓名";
        $in_data['title'] = $in_data['fullname'] . "的简历";
        $sex = $this->input->post('sex');
        $sex_arr = !empty($sex) ? explode('|', $sex) : $error = "请选择性别";
        $in_data['sex'] = intval($sex_arr[0]);
        $in_data['sex_cn'] = trim($sex_arr[1]);
        $in_data['birthdate'] = intval($this->input->post('birthdate')) > 0 ? intval($this->input->post('birthdate')) : $error = "请选择出生年份";
        $education = $this->input->post('education');
        $education_arr = !empty($education) ? explode('|', $education) : $error = "请选择学历";
        $in_data['education'] = intval($education_arr[0]);
        $in_data['education_cn'] = trim($education_arr[1]);
        $telephone = $this->input->post('telephone');
        $in_data['telephone'] = intval($telephone) > 0 ? intval($telephone) : $error = "请填写手机号码";
        $in_data['trade'] = 33;
        $in_data['trade_cn'] = '教育/培训';
        $in_data['display'] = 1;
        $in_data['display_name'] = 3;
        $in_data['audit'] = 2;
        $in_data['email_notify'] = 1;
        $in_data['is_mobile'] = 1;
        $in_data['addtime'] = $in_data['refreshtime'] = time();
        $basic = $this->resume_models->get_one_resume($uid);
        if (!empty($basic)) {
            unset($in_data['addtime']);
            $this->resume_models->update_resume_by_uid($uid, $in_data);
            $insert_id = intval($basic['id']);
        } else {
            $insert_id = $this->resume_models->add_resume($in_data);
        }
        !intval($insert_id) > 0 ? $error = "创建简历失败" : "";
        $wechat_name = $this->input->post('wechat_name');
        if (!empty($wechat_name)) {
            $member_data['wechat_name'] = $this->input->post('wechat_name');
            $this->user_models->up_user_info_by_id($uid, $member_data);
        }
        $in_data = "";
        $school = $this->input->post('school');
        $in_data['school'] = !empty($school) ? trim($school) : $error = "请填写学校名称";
        $speciality = $this->input->post('speciality');
        $in_data['speciality'] = !empty($speciality) ? trim($speciality) : $error = "请填写专业名称";
        $education = $this->input->post('education');
        $education_arr = !empty($education) ? explode('|', $education) : $error = "请选择学历名称";
        $in_data['education'] = intval($education_arr[0]);
        $in_data['education_cn'] = trim($education_arr[1]);
        $start_time = $this->input->post('starttime');
        $start_arr = explode(".", $start_time);
        $in_data['startyear'] = intval($start_arr[0]);
        $in_data['startmonth'] = intval($start_arr[1]);
        $end_time = $this->input->post('endtime');
        $end_arr = explode(".", $end_time);
        $in_data['endyear'] = intval($end_arr[0]);
        $in_data['endmonth'] = intval($end_arr[1]);
        $in_data['uid'] = $uid;
        $in_data['pid'] = $insert_id;
        $e_id = $this->resume_models->add_resume_education($in_data, $uid);
        if ($e_id == 0 || !empty($error)) {
            alert_to($error, '/personal_center/make_info');
        } else {
            header('Location:/personal_center/make_info2');
        }
    }

    public function make_wechat_info() {
        $uid = $this->common_models->check_login();
        $error = "";
        $wechat_name = $this->input->post('wechat_name');
        $wechat_img = $this->input->post('wechat_img');
        if (!empty($wechat_img) || !empty($wechat_name)) {
            $user = $this->user_models->get_user_info_by_id($uid);
            $host = get_base_site();
            if (!empty($wechat_img)) {
                $dir = "/data2/www/" . $host . "/data/pay_img/";
                unlink($dir . $user['wechat_img']);
                $up_arr['wechat_img'] = $wechat_img;
            }
            if (!empty($wechat_name)) {
                $up_arr['wechat_name'] = !empty($wechat_name) ? trim($wechat_name) : "";
            }
            $this->user_models->up_user_info_by_id($uid, $up_arr);
        } else {
            $error = "微信号和微信二维码至少填写一项！";
        }
        $job_id = $this->input->post('job_id');
        if (!empty($error)) {
            alert_to($error, '/');
        } else {
            header('Location:/');
        }
    }

    public function make_info2() {
        $uid = $this->common_models->check_login();
        $utype = $this->session->userdata('utype');
        if ($utype == '1') {
            echo '<script>window.location.href ="/company_center"</script>';
            exit;
        }
        $basic = $this->resume_models->get_one_resume($uid);
        $experience_categories_tmp = $this->category_models->get_categories('QS_experience');
        foreach ($experience_categories_tmp as $e) {
            $ex_tmp['id'] = toGBK($e['c_id'] . "|" . $e['c_name']);
            $ex_tmp['value'] = toGBK($e['c_name']);
            $experience_categories[] = $ex_tmp;
        }
        $data['experience_categories'] = json_encode($experience_categories);
        foreach ($parent_job_types as $pjt) {
            $job_types[$pjt['id']] = $this->category_models->get_job_types($pjt['id']);
        }
        $nature_categories_tmp = $this->category_models->get_categories('QS_jobs_nature');
        foreach ($nature_categories_tmp as $e) {
            $ex_tmp['id'] = toGBK($e['c_id'] . "|" . $e['c_name']);
            $ex_tmp['value'] = toGBK($e['c_name']);
            $nature_categories[] = $ex_tmp;
        }
        $data['nature_categories'] = json_encode($nature_categories);

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

        $parent_job_types = $this->category_models->get_parent_job_types();
        foreach ($parent_job_types as $pjt) {
            $p = array();
            $p['id'] = $pjt['id'] . "|" . toGBK($pjt['categoryname']);
            $p['value'] = toGBK($pjt['categoryname']);
            $job_types = $this->category_models->get_job_types($pjt['id']);
            foreach ($job_types as $s) {
                $sd['id'] = $s['id'] . "|" . toGBK($s['categoryname']);
                $sd['value'] = toGBK($s['categoryname']);
                $p['childs'][] = $sd;
            }
            $job_types_categories[] = $p;
        }
        $data['job_types_categories'] = json_encode($job_types_categories);

        $wage_categories_tmp = $this->category_models->get_categories('QS_wage');
        foreach ($wage_categories_tmp as $e) {
            $ex_tmp['id'] = toGBK($e['c_id'] . "|" . $e['c_name']);
            $ex_tmp['value'] = toGBK($e['c_name']);
            $wage_categories[] = $ex_tmp;
        }
        $data['wage_categories'] = json_encode($wage_categories);
        $data['basic'] = $basic;
        $this->load->view('/personal_center/make_info2', $data);
    }

    public function make_info2_save() {
        $uid = $this->common_models->check_login();
        $error = "";
        $insert_id = 0;
        $in_data['uid'] = $uid;
        $experience = $this->input->post('experience');
        $experience_arr = !empty($experience) ? explode('|', $experience) : $error = "请选择工作经验";
        $in_data['experience'] = intval($experience_arr[0]);
        $in_data['experience_cn'] = trim($experience_arr[1]);
        $nature = $this->input->post('nature');
        $nature_arr = !empty($nature) ? explode('|', $nature) : $error = "请选择工作性质";
        $in_data['nature'] = intval($nature_arr[0]);
        $in_data['nature_cn'] = trim($nature_arr[1]);
        $district = $this->input->post('district');
        $district = trim($district) ? explode('.', $district) : $error = "请选择工作地区";
        $in_data['district'] = intval($district[0]);
        $in_data['sdistrict'] = $district[1] > 0 ? intval($district[1]) : 0;
        $district_cn = $this->input->post('district_cn');
        $in_data['district_cn'] = trim($district_cn);
        $intention_jobs = $this->input->post('intention_jobs');
        empty($intention_jobs) ? $error = "请选择意向职位" : "";
        $i_arr = explode(".", $intention_jobs);
        $p_arr = $this->category_models->get_job_type($i_arr[0]);
        $s_arr = $this->category_models->get_job_type($i_arr[1]);
        $in_data['intention_jobs'] = $p_arr['categoryname'] . "-" . $s_arr['categoryname'];
        $wage = $this->input->post('wage');
        $wage_arr = !empty($wage) ? explode('|', $wage) : $error = "请选择期望薪资";
        $in_data['wage'] = intval($wage_arr[0]);
        $in_data['wage_cn'] = trim($wage_arr[1]);
        $in_data['refreshtime'] = $in_data['addtime'] = time();
        $in_data['audit'] = 2;
        $basic = $this->resume_models->get_one_resume($uid);
        if (intval($basic['id']) > 0) {
            unset($in_data['addtime']);
            $this->resume_models->update_resume_by_uid($uid, $in_data);
            $insert_id = intval($basic['id']);
        } else {
            $insert_id = $this->resume_models->add_resume($in_data);
        }
        $intention_flag = $this->add_resume_jobs($insert_id, $intention_jobs, $in_data['district'], $in_data['sdistrict']);
        !$intention_flag ? $error = "意向职位修改失败" : "";
        $this->common_models->write_memberslog($uid, 2, 1105, $this->session->userdata('username'), "修改了简历-求职意向");
        if ($insert_id == 0 || !empty($error)) {
            alert_to($error, '/personal_center/make_info2');
        } else {
            header('Location:/personal_center/make_info3');
        }
    }

    public function make_info3() {
        $uid = $this->common_models->check_login();
        $job_flag = $this->session->userdata('job_flag');
        $utype = $this->session->userdata('utype');
        if ($utype == '1') {
            echo '<script>window.location.href ="/company_center"</script>';
            exit;
        }
        $now_year = date('Y');
        for ($i = date('Y') - 50; $i < date('Y') + 51; $i++) {
            $i_arr['id'] = $i;
            $i_arr['value'] = $i;
            for ($i2 = 1; $i2 < 13; $i2++) {
                $i2_arr['id'] = $i2;
                $i2_arr['value'] = $i2;
                $i_arr['childs'][] = $i2_arr;
            }
            $select_time[] = $i_arr;
        }
        $data['select_time'] = json_encode($select_time);
        $data['job_flag'] = !empty($job_flag) ? intval($job_flag) : 0;
        $data['now_year'] = $now_year;
        $this->load->view('/personal_center/make_info3', $data);
    }

    public function make_info3_save() {
        $uid = $this->common_models->check_login();
        $to_resume = $this->input->post('to_resume');
        $error = "";
        $basic = $this->resume_models->get_one_resume($uid);
        empty($basic) ? $error = "请先创建简历" : "";
        $companyname = $this->input->post('companyname');
        $jobs = $this->input->post('jobs');
        $redirect_url = $to_resume ? "/personal_center/resume" : $this->session->userdata('reg_back');
        $redirect_url = strpos($redirect_url, "?") ? $redirect_url : $redirect_url . "?show_talent=1";
        if (!empty($companyname) && !empty($jobs)) {
            $in_data['companyname'] = trim($companyname);
            $in_data['jobs'] = trim($jobs);
            $start_time = $this->input->post('starttime');
            $start_arr = explode(".", $start_time);
            $in_data['startyear'] = intval($start_arr[0]);
            $in_data['startmonth'] = intval($start_arr[1]);
            $end_time = $this->input->post('endtime');
            $end_arr = explode(".", $end_time);
            $in_data['endyear'] = intval($end_arr[0]);
            $in_data['endmonth'] = intval($end_arr[1]);
            $in_data['uid'] = $uid;
            $in_data['pid'] = $basic['id'];
            $w_id = $this->resume_models->add_resume_work($in_data, $uid);
        }
        if (!empty($basic['fullname']) && intval($basic['experience']) > 0 && intval($basic['birthdate']) > 0 && intval($basic['education']) > 0 && intval($basic['telephone']) > 0 && intval($basic['education']) > 0 && intval($basic['district']) > 0 && !empty($basic['intention_jobs']) && intval($basic['wage']) > 0) {
            $this->resume_models->update_resume_by_uid($uid, array('audit' => 1));
        }
        alert_to('保存成功！', $redirect_url);
    }

    public function edit_basic() {
        $uid = $this->common_models->check_login();
        $utype = $this->session->userdata('utype');
        if ($utype == '1') {
            echo '<script>window.location.href ="/company_center"</script>';
            exit;
        }
        $basic = $this->resume_models->get_one_resume($uid);
        $select_sex = array(array('id' => toGBK("1|男"), 'value' => toGBK("男")), array('id' => toGBK("2|女"), 'value' => toGBK("女")));
        $data['select_sex'] = json_encode($select_sex);
        $select_marriage = array(array('id' => toGBK("1|未婚"), 'value' => toGBK("未婚")), array('id' => toGBK("2|已婚"), 'value' => toGBK("已婚")), array('id' => toGBK("3|保密"), 'value' => toGBK("保密")));
        $data['select_marriage'] = json_encode($select_marriage);
        $education_categories_tmp = $this->category_models->get_categories('QS_education');
        foreach ($education_categories_tmp as $e) {
            $e_tmp['id'] = toGBK($e['c_id'] . "|" . $e['c_name']);
            $e_tmp['value'] = toGBK($e['c_name']);
            $education_categories[] = $e_tmp;
        }
        $data['education_categories'] = json_encode($education_categories);
        $experience_categories_tmp = $this->category_models->get_categories('QS_experience');
        foreach ($experience_categories_tmp as $e) {
            $ex_tmp['id'] = toGBK($e['c_id'] . "|" . $e['c_name']);
            $ex_tmp['value'] = toGBK($e['c_name']);
            $experience_categories[] = $ex_tmp;
        }
        $data['experience_categories'] = json_encode($experience_categories);
        $user_info = $this->user_models->get_user_info_by_id($uid);
        $basic['telephone'] = !empty($basic['telephone']) ? $basic['telephone'] : $user_info['mobile'];
        $basic['email'] = !empty($basic['email']) ? $basic['email'] : $user_info['email'];
        $basic['height'] = !empty($basic['height']) && intval($basic['height']) > 0 ? $basic['height'] : "";
        $basic['residence_cn'] = !empty($basic['residence_cn']) && $basic['residence_cn'] != "/" ? $basic['residence_cn'] : "";
        $basic['householdaddress_cn'] = !empty($basic['householdaddress_cn']) && $basic['householdaddress_cn'] != "/" ? $basic['householdaddress_cn'] : "";
        $now_year = date('Y');
        for ($i = $now_year - 80; $i < $now_year; $i++) {
            $year_tmp['id'] = $i;
            $year_tmp['value'] = $i;
            $birthdate_year[] = $year_tmp;
        }
        $data['birthdate_year'] = json_encode($birthdate_year);

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
        $data['user_info'] = $user_info;
        $data['basic'] = $basic;
        $this->load->view('/personal_center/personal_basic', $data);
    }

    public function save_basic() {
        $uid = $this->common_models->check_login();
        $error = "";
        $insert_id = 0;
        $in_data['uid'] = $uid;
        $fullname = $this->input->post('fullname');
        $in_data['fullname'] = !empty($fullname) ? trim($fullname) : $error = "请填写姓名";
        $in_data['title'] = $in_data['fullname'] . "的简历";
        $sex = $this->input->post('sex');
        $sex_arr = !empty($sex) ? explode('|', $sex) : $error = "请选择性别";
        $in_data['sex'] = intval($sex_arr[0]);
        $in_data['sex_cn'] = trim($sex_arr[1]);
        $in_data['birthdate'] = intval($this->input->post('birthdate')) > 0 ? intval($this->input->post('birthdate')) : $error = "请选择出生年份";
        $education = $this->input->post('education');
        $education_arr = !empty($education) ? explode('|', $education) : $error = "请选择学历";
        $in_data['education'] = intval($education_arr[0]);
        $in_data['education_cn'] = trim($education_arr[1]);
        $experience = $this->input->post('experience');
        $experience_arr = !empty($experience) ? explode('|', $experience) : $error = "请选择工作经验";
        $in_data['experience'] = intval($experience_arr[0]);
        $in_data['experience_cn'] = trim($experience_arr[1]);
        $in_data['telephone'] = intval($this->input->post('telephone')) > 0 ? intval($this->input->post('telephone')) : $error = "请填写手机号";
        $email = $this->input->post('email');
        $in_data['email'] = !empty($email) ? trim($email) : $error = "请填写电子邮箱";
        $telephone = $this->input->post('telephone');
        $in_data['telephone'] = intval($telephone) > 0 ? intval($telephone) : $error = "请填写手机号码";
        $residence = $this->input->post('residence');
        $in_data['residence'] = !empty($residence) ? trim($residence) : "";
        $residence_cn = $this->input->post('residence_cn');
        $in_data['residence_cn'] = !empty($residence_cn) ? trim($residence_cn) : "";
        $in_data['height'] = intval($this->input->post('height')) > 0 ? intval($this->input->post('height')) : "";
        $householdaddress = $this->input->post('householdaddress');
        $in_data['householdaddress'] = !empty($householdaddress) ? trim($householdaddress) : "";
        $householdaddress_cn = $this->input->post('householdaddress_cn');
        $in_data['householdaddress_cn'] = !empty($householdaddress_cn) ? trim($householdaddress_cn) : "";
        $marriage = $this->input->post('marriage');
        $marriage_arr = !empty($marriage) ? explode('|', $marriage) : "";
        $in_data['trade'] = 33;
        $in_data['trade_cn'] = '教育/培训';
        $in_data['display'] = 1;
        $in_data['display_name'] = 3;
        $in_data['audit'] = 2;
        $in_data['email_notify'] = 1;
        $in_data['is_mobile'] = 1;
        $in_data['addtime'] = $in_data['refreshtime'] = time();
        if (!empty($marriage_arr)) {
            $in_data['marriage'] = intval($marriage_arr[0]);
            $in_data['marriage_cn'] = trim($marriage_arr[1]);
        }
        if (!empty($error)) {
            alert_to($error, '/personal_center/edit_basic');
        }
        $basic = $this->resume_models->get_one_resume($uid);
        if (!empty($basic)) {
            unset($in_data['addtime']);
            $this->resume_models->update_resume_by_uid($uid, $in_data);
            $insert_id = intval($basic['id']);
        } else {
            $insert_id = $this->resume_models->add_resume($in_data);
        }
        if ($insert_id == 0) {
            alert_to('保存失败！', '/personal_center/edit_basic');
        } else {
            $to_url = "/personal_center/edit_intention";
            alert_to('保存成功！', $to_url);
        }
    }

    public function edit_intention() {
        $uid = $this->common_models->check_login();
        $basic = $this->resume_models->get_one_resume($uid);
        $nature_categories_tmp = $this->category_models->get_categories('QS_jobs_nature');
        foreach ($nature_categories_tmp as $e) {
            $ex_tmp['id'] = toGBK($e['c_id'] . "|" . $e['c_name']);
            $ex_tmp['value'] = toGBK($e['c_name']);
            $nature_categories[] = $ex_tmp;
        }
        $data['nature_categories'] = json_encode($nature_categories);

        $wage_categories_tmp = $this->category_models->get_categories('QS_wage');
        foreach ($wage_categories_tmp as $e) {
            $ex_tmp['id'] = toGBK($e['c_id'] . "|" . $e['c_name']);
            $ex_tmp['value'] = toGBK($e['c_name']);
            $wage_categories[] = $ex_tmp;
        }
        $data['wage_categories'] = json_encode($wage_categories);

        $intention_list = $this->resume_models->get_resume_intention($uid);
        foreach ($intention_list as $il) {
            $intention_category_list[] = $il['category'];
            $intention_subclass_list[] = $il['subclass'];
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



        $parent_job_types = $this->category_models->get_parent_job_types();
        foreach ($parent_job_types as $pjt) {
            $p = array();
            $p['id'] = $pjt['id'] . "|" . toGBK($pjt['categoryname']);
            $p['value'] = toGBK($pjt['categoryname']);
            $t = $this->category_models->get_job_types($pjt['id']);
            foreach ($t as $t) {
                $jt['id'] = $t['id'] . "|" . toGBK($t['categoryname']);
                $jt['value'] = toGBK($t['categoryname']);
                $p['childs'][] = $jt;
            }
            $jobs_type[] = $p;
        }
        $data['jobs_type'] = json_encode($jobs_type);

        $data['basic'] = $basic;
        $data['intention_list'] = $intention_list;
        $data['intention_category_list'] = $intention_category_list;
        $data['intention_subclass_list'] = $intention_subclass_list;
        $this->load->view('/personal_center/personal_intention', $data);
    }

    public function save_intention() {
        $uid = $this->common_models->check_login();
        $error = "";
        $insert_id = 0;
        $in_data['uid'] = $uid;
        $nature = $this->input->post('nature');
        $nature_arr = !empty($nature) ? explode('|', $nature) : $error = "请选择工作性质";
        $in_data['nature'] = intval($nature_arr[0]);
        $in_data['nature_cn'] = trim($nature_arr[1]);
        $district = $this->input->post('district');
        $district = trim($district) ? explode('.', $district) : $error = "请选择工作地区";
        $in_data['district'] = intval($district[0]);
        $in_data['sdistrict'] = $district[1] > 0 ? intval($district[1]) : 0;
        $district_cn = $this->input->post('district_cn');
        $in_data['district_cn'] = trim($district_cn);
        $intention_jobs = $this->input->post('intention_jobs');
        if (!empty($intention_jobs)) {
            $intention_str = "";
            $intention_jobs = trim($intention_jobs, "|");
            $intention_jobs = explode("|", $intention_jobs);
            foreach ($intention_jobs as $ij) {
                $i_arr = explode(".", $ij);
                $p_arr = $this->category_models->get_job_type($i_arr[0]);
                $s_arr = $this->category_models->get_job_type($i_arr[1]);
                $intention_str .= $p_arr['categoryname'] . "-" . $s_arr['categoryname'] . ",";
            }
            $in_data['intention_jobs'] = trim($intention_str, ',');
        } else {
            $error = "请选择意向职位";
        }
        $wage = $this->input->post('wage');
        $wage_arr = !empty($wage) ? explode('|', $wage) : $error = "请选择期望薪资";
        $in_data['wage'] = intval($wage_arr[0]);
        $in_data['wage_cn'] = trim($wage_arr[1]);
        $in_data['refreshtime'] = $in_data['addtime'] = time();
        $in_data['audit'] = 2;
        if (!empty($error)) {
            alert_to($error, '/personal_center/edit_intention');
        }
        $basic = $this->resume_models->get_one_resume($uid);
        if (intval($basic['id']) > 0) {
            unset($in_data['addtime']);
            $this->resume_models->update_resume_by_uid($uid, $in_data);
            $insert_id = intval($basic['id']);
        } else {
            $insert_id = $this->resume_models->add_resume($in_data);
        }
        $intention_flag = $this->add_resume_jobs($insert_id, $intention_jobs, $in_data['district'], $in_data['sdistrict']);
        !$intention_flag ? $error = "意向职位修改失败" : "";
        $this->common_models->write_memberslog($uid, 2, 1105, $this->session->userdata('username'), "修改了简历-求职意向");
        if ($insert_id == 0 || !empty($error)) {
            alert_to($error, '/personal_center/edit_intention');
        } else {
            alert_to('保存成功！', '/personal_center/edit_education');
        }
    }

    public function add_resume_jobs($pid, $intention_jobs_id = array(), $district = 0, $sdistrict = 0) {
        $uid = $this->common_models->check_login();
        if (empty($intention_jobs_id)) {
            return FALSE;
        } else {
            $this->resume_models->del_resume_jobs($uid);
            if (!empty($intention_jobs_id)) {
                foreach ($intention_jobs_id as $a) {
                    $id_arr = explode(".", $a);
                    $in_data['pid'] = $pid;
                    $in_data['uid'] = $uid;
                    $in_data['topclass'] = 0;
                    $in_data['category'] = $id_arr[0];
                    $in_data['subclass'] = $id_arr[1];
                    $in_data['district'] = $district;
                    $in_data['sdistrict'] = $sdistrict;
                    $this->resume_models->add_resume_job($in_data, $uid);
                }
            } else {
                return FALSE;
            }
            return TRUE;
        }
    }

    public function edit_education($eid = 0) {
        $uid = $this->common_models->check_login();
        $educations_list = $this->resume_models->get_resume_educations($uid);
        if ($eid == 0 && count($educations_list) >= 6) {
            alert_to('教育经历不能超过6条！', '/personal_center/resume');
        }
        $education = intval($eid) > 0 ? $this->resume_models->get_resume_education($eid) : "";
        $education_categories_tmp = $this->category_models->get_categories('QS_education');
        foreach ($education_categories_tmp as $e) {
            $e_tmp['id'] = toGBK($e['c_id'] . "|" . $e['c_name']);
            $e_tmp['value'] = toGBK($e['c_name']);
            $education_categories[] = $e_tmp;
        }
        $data['education_categories'] = json_encode($education_categories);
        for ($i = date('Y') - 50; $i < date('Y') + 51; $i++) {
            $i_arr['id'] = $i;
            $i_arr['value'] = $i;
            for ($i2 = 1; $i2 < 13; $i2++) {
                $i2_arr['id'] = $i2;
                $i2_arr['value'] = $i2;
                $i_arr['childs'][] = $i2_arr;
            }
            $select_time[] = $i_arr;
        }
        $data['select_time'] = json_encode($select_time);
        $data['education'] = $education;
        $this->load->view('/personal_center/personal_education', $data);
    }

    public function save_education() {
        $uid = $this->common_models->check_login();
        $error = "";
        $basic = $this->resume_models->get_one_resume($uid);
        if (empty($basic)) {
            alert_to('请先创建简历', '/personal_center/edit_basic');
        }
        $school = $this->input->post('school');
        $in_data['school'] = !empty($school) ? trim($school) : $error = "请填写学校名称";
        $speciality = $this->input->post('speciality');
        $in_data['speciality'] = !empty($speciality) ? trim($speciality) : $error = "请填写专业名称";
        $education = $this->input->post('education');
        $education_arr = !empty($education) ? explode('|', $education) : $error = "请选择学历名称";
        $in_data['education'] = intval($education_arr[0]);
        $in_data['education_cn'] = trim($education_arr[1]);
        $in_data['startyear'] = intval($this->input->post('startyear'));
        $in_data['startmonth'] = intval($this->input->post('startmonth'));
        $in_data['endyear'] = intval($this->input->post('endyear'));
        $in_data['endmonth'] = intval($this->input->post('endmonth'));
        $e_id = intval($this->input->post('id'));
        if ($e_id > 0) {
            $this->resume_models->update_resume_education($e_id, $in_data, $uid);
        } else {
            $in_data['uid'] = $uid;
            $in_data['pid'] = $basic['id'];
            $e_id = $this->resume_models->add_resume_education($in_data, $uid);
        }
        if ($e_id == 0 || !empty($error)) {
            alert_to($error, '/personal_center/edit_education');
        } else {
            alert_to('保存成功！', '/personal_center/edit_work');
        }
    }

    public function del_education() {
        $uid = $this->common_models->check_login();
        $e_id = intval($this->input->post('id'));
        $this->resume_models->del_resume_education($e_id, $uid);
        alert_to('删除成功！', '/personal_center/resume');
    }

    public function edit_work($wid = 0) {
        $uid = $this->common_models->check_login();
        $work = intval($wid) > 0 ? $this->resume_models->get_resume_work($wid) : "";
        $data['work'] = $work;
        for ($i = date('Y') - 50; $i < date('Y') + 51; $i++) {
            $i_arr['id'] = $i;
            $i_arr['value'] = $i;
            for ($i2 = 1; $i2 < 13; $i2++) {
                $i2_arr['id'] = $i2;
                $i2_arr['value'] = $i2;
                $i_arr['childs'][] = $i2_arr;
            }
            $select_time[] = $i_arr;
        }
        $data['select_time'] = json_encode($select_time);
        $this->load->view('/personal_center/personal_work', $data);
    }

    public function save_work() {
        $uid = $this->common_models->check_login();
        $error = "";
        $basic = $this->resume_models->get_one_resume($uid);
        if (empty($basic)) {
            alert_to('请先创建简历', '/personal_center/edit_basic');
        }
        $companyname = $this->input->post('companyname');
        $in_data['companyname'] = !empty($companyname) ? trim($companyname) : $error = "请填写公司名称";
        $jobs = $this->input->post('jobs');
        $in_data['jobs'] = !empty($jobs) ? trim($jobs) : $error = "请填写职业名称";
        $in_data['startyear'] = intval($this->input->post('startyear'));
        $in_data['startmonth'] = intval($this->input->post('startmonth'));
        $in_data['endyear'] = intval($this->input->post('endyear'));
        $in_data['endmonth'] = intval($this->input->post('endmonth'));
        $achievements = $this->input->post('achievements');
        $in_data['achievements'] = !empty($achievements) ? trim($achievements) : "";
        $w_id = intval($this->input->post('id'));
        if ($w_id > 0) {
            $this->resume_models->update_resume_work($w_id, $in_data, $uid);
        } else {
            $in_data['uid'] = $uid;
            $in_data['pid'] = $basic['id'];
            $w_id = $this->resume_models->add_resume_work($in_data, $uid);
        }
        if ($w_id == 0 || !empty($error)) {
            alert_to($error, '/personal_center/edit_work');
        } else {
            alert_to('保存成功！', '/personal_center/edit_training');
        }
    }

    public function del_work() {
        $uid = $this->common_models->check_login();
        $w_id = intval($this->input->post('id'));
        $this->resume_models->del_resume_work($w_id, $uid);
        alert_to('删除成功！', '/personal_center/resume');
    }

    public function edit_training($tid = 0) {
        $uid = $this->common_models->check_login();
        $training = intval($tid) > 0 ? $this->resume_models->get_resume_training($tid) : "";
        $data['training'] = $training;
        for ($i = date('Y') - 50; $i < date('Y') + 51; $i++) {
            $i_arr['id'] = $i;
            $i_arr['value'] = $i;
            for ($i2 = 1; $i2 < 13; $i2++) {
                $i2_arr['id'] = $i2;
                $i2_arr['value'] = $i2;
                $i_arr['childs'][] = $i2_arr;
            }
            $select_time[] = $i_arr;
        }
        $data['select_time'] = json_encode($select_time);
        $this->load->view('/personal_center/personal_training', $data);
    }

    public function save_training() {
        $uid = $this->common_models->check_login();
        $error = "";
        $basic = $this->resume_models->get_one_resume($uid);
        if (empty($basic)) {
            alert_to('请先创建简历', '/personal_center/edit_basic');
        }
        $agency = $this->input->post('agency');
        $in_data['agency'] = !empty($agency) ? trim($agency) : $error = "请填写机构名称";
        $course = $this->input->post('course');
        $in_data['course'] = !empty($course) ? trim($course) : $error = "请填写课程名称";
        $in_data['startyear'] = intval($this->input->post('startyear'));
        $in_data['startmonth'] = intval($this->input->post('startmonth'));
        $in_data['endyear'] = intval($this->input->post('endyear'));
        $in_data['endmonth'] = intval($this->input->post('endmonth'));
        $description = $this->input->post('description');
        $in_data['description'] = !empty($description) ? trim($description) : "";
        $t_id = intval($this->input->post('id'));
        if ($t_id > 0) {
            $this->resume_models->update_resume_training($t_id, $in_data, $uid);
        } else {
            $in_data['uid'] = $uid;
            $in_data['pid'] = $basic['id'];
            $t_id = $this->resume_models->add_resume_training($in_data, $uid);
        }
        if ($t_id == 0 || !empty($error)) {
            alert_to($error, '/personal_center/edit_training');
        } else {
            alert_to('保存成功！', '/personal_center/edit_other');
        }
    }

    public function del_training() {
        $uid = $this->common_models->check_login();
        $t_id = intval($this->input->post('id'));
        $this->resume_models->del_resume_training($t_id, $uid);
        alert_to('删除成功！', '/personal_center/resume');
    }

    public function edit_other() {
        $uid = $this->common_models->check_login();
        $basic = $this->resume_models->get_one_resume($uid);
        $tag_category_tmp = $this->category_models->get_categories('QS_resumetag');
        foreach ($tag_category_tmp as $e) {
            $e_tmp['id'] = toGBK($e['c_id'] . "|" . $e['c_name']);
            $e_tmp['value'] = toGBK($e['c_name']);
            $tag_category[] = $e_tmp;
        }
        $data['tag_category'] = json_encode($tag_category);


        $tag_id_arr = "";
        if (!empty($basic['tag'])) {
            $tag = explode("|", $basic['tag']);
            foreach ($tag as $t) {
                $tag_arr = explode(",", $t);
                $tag_id_arr[] = $tag_arr[0];
            }
        }
        $data['tag'] = $tag;
        $data['tag_id_arr'] = $tag_id_arr;
        $specialty = str_replace("<br/>", "\n", $basic['specialty']);
        $data['specialty'] = $specialty;
        $this->load->view('/personal_center/personal_other', $data);
    }

    public function save_other() {
        $uid = $this->common_models->check_login();
        $basic = $this->resume_models->get_one_resume($uid);
        if (empty($basic)) {
            alert_to('请先创建简历', '/personal_center/edit_basic');
        }
        $tag = $this->input->post('tag');
        $in_data['tag'] = !empty($tag) ? trim($tag, "|") : "";
        $specialty = $this->input->post('specialty');
        $in_data['specialty'] = !empty($specialty) ? trim($specialty) : "";
        $in_data['audit'] = 2;
        $in_data['refreshtime'] = time();
        $this->resume_models->update_resume_by_uid($uid, $in_data);
        alert_to('保存成功！', '/personal_center/edit_certificate');
    }

    public function edit_certificate() {
        $uid = $this->common_models->check_login();
        $resume_certificate = $this->resume_models->get_resume_certificates($uid);
        $data['certificates'] = $resume_certificate;
        $this->load->view('/personal_center/personal_certificate', $data);
    }

    public function save_certificate() {
        $uid = $this->session->userdata('uid');
        $resume_certificate = $this->resume_models->get_resume_certificates($uid);
        if (count($resume_certificate) > 4) {
            alert_to('最多上传5个证书！', '/personal_center/edit_certificate');
        }
        $resume_basic = $this->resume_models->get_one_resume($uid);
        if (empty($resume_basic['id'])) {
            $in['uid'] = $uid;
            $resume_basic['id'] = $this->resume_models->add_resume($in);
        }
        $in_data['uid'] = $uid;
        $in_data['pid'] = $resume_basic['id'];
        $path = $this->input->post('path');
        $in_data['path'] = !empty($path) ? trim($path) : alert_to('请上传证书！', '/personal_center/edit_certificate');
        $note = $this->input->post('note');
        $in_data['note'] = !empty($note) ? trim($note) : alert_to('请填写证书名！', '/personal_center/edit_certificate');
        $in_data['audit'] = 0;
        $in_data['addtime'] = time();
        $add_id = $this->resume_models->add_resume_certificate($in_data);
        if ($add_id > 0) {
            alert_to('添加成功！', '/personal_center/resume');
        } else {
            alert_to('添加失败！', '/personal_center/edit_certificate');
        }
    }

    public function del_certificate() {
        $uid = $this->session->userdata('uid');
        $c_id = $this->input->post('id');
        $certificate = $this->resume_models->get_resume_certificate_by_id($c_id);
        $this->resume_models->del_resume_certificate($c_id, $uid);
        $host = get_base_site();
        $dir = "/data2/www/" . $host . "/data/resume_certificate/";
        unlink($dir . $certificate['path']);
        alert_to('删除成功！', '/personal_center/edit_certificate');
    }

    public function my_invitation($page = 1) {
        $uid = $this->common_models->check_login();
        $total = $this->personal_models->get_invitation_total_by_uid($uid);
        $page_arr = get_page_arr($total, $page);
        $invitation_list = $this->personal_models->get_invitation_by_uid($uid, $page_arr['page_num'], $page_arr['offset']);
        $data['invitation_list'] = $invitation_list;
        $data['page_arr'] = $page_arr;
        $this->load->view('/personal_center/my_invitation', $data);
    }

    public function set_look_invitation() {
        $id = intval($this->input->post('did'));
        $url = trim($this->input->post('url'));
        $uid = $this->common_models->check_login();
        $invitation = $this->personal_models->get_invitation_by_id($id);
        if ($invitation['resume_uid'] != intval($uid)) {
            exit('0');
        } else {
            $this->personal_models->set_look_invitation($id);
            exit($url);
        }
    }

    public function my_apply_jobs($page = 1) {
        $uid = $this->common_models->check_login();
        $total = $this->personal_models->get_apply_jobs_total_by_uid($uid);
        $page_arr = get_page_arr($total, $page);
        $apply_jobs_list = $this->personal_models->get_apply_jobs_by_uid($uid, $page_arr['page_num'], $page_arr['offset']);
        $data['apply_jobs_list'] = $apply_jobs_list;
        $data['page_arr'] = $page_arr;
        $this->load->view('/personal_center/my_apply_jobs', $data);
    }

    public function my_apply_article($page = 1) {
        $uid = $this->common_models->check_login();
        $total = $this->personal_models->get_apply_article_total_by_uid($uid);
        $page_arr = get_page_arr($total, $page);
        $apply_article_arr = $this->personal_models->get_apply_article_by_uid($uid, $page_arr['page_num'], $page_arr['offset']);
        foreach ($apply_article_arr as $a) {
            $article_job = $this->article_models->get_article_job_by_id($a['article_job_id']);
            $a['job_name'] = $article_job['job_name'];
            $apply_article_list[] = $a;
        }
        $data['apply_article_list'] = $apply_article_list;
        $data['page_arr'] = $page_arr;
        $this->load->view('/personal_center/my_apply_article', $data);
    }

    public function my_favorites_article($page = 1) {
        $uid = $this->common_models->check_login();
        $total = $this->personal_models->get_favorites_article_total_by_uid($uid);
        $page_arr = get_page_arr($total, $page);
        $favorites_article_arr = $this->personal_models->get_favorites_article_by_uid($uid, $page_arr['page_num'], $page_arr['offset']);
        foreach ($favorites_article_arr as $faa) {
            $article = $this->article_models->get_article_by_id($faa['article_id']);
            $faa['read'] = $article['click'];
            $favorites_article_list[] = $faa;
        }
        $data['favorites_article_list'] = $favorites_article_list;
        $data['page_arr'] = $page_arr;
        $this->load->view('/personal_center/my_favorites_article', $data);
    }

    public function my_favorites_article_del() {
        $uid = $this->common_models->check_login();
        $id_arr = $this->input->post('list_id');
        $del_num = $this->personal_models->del_favorites_article_in_id($id_arr, $uid);
        $tip_str = $del_num > 0 ? '删除成功' : '删除失败';
        alert_to($tip_str, '/personal_center/my_favorites_article');
    }

    public function my_favorites_jobs($page = 1) {
        $uid = $this->common_models->check_login();
        $total = $this->personal_models->get_favorites_jobs_total_by_uid($uid);
        $page_arr = get_page_arr($total, $page);
        $favorites_jobs_arr = $this->personal_models->get_favorites_jobs_by_uid($uid, $page_arr['page_num'], $page_arr['offset']);
        foreach ($favorites_jobs_arr as $fja) {
            $jobs = $this->job_models->get_all_job_by_id($fja['jobs_id']);
            $fja['companyname'] = $jobs['companyname'];
            $favorites_jobs_list[] = $fja;
        }
        $data['favorites_jobs_list'] = $favorites_jobs_list;
        $data['page_arr'] = $page_arr;
        $this->load->view('/personal_center/my_favorites_jobs', $data);
    }

    public function my_favorites_jobs_del() {
        $uid = $this->common_models->check_login();
        $id_arr = $this->input->post('list_id');
        $del_num = $this->personal_models->del_favorites_jobs_in_id($id_arr, $uid);
        $tip_str = $del_num > 0 ? '删除成功' : '删除失败';
        alert_to($tip_str, '/personal_center/my_favorites_jobs');
    }

    function send_wechat_resume_view_core($apply_id) {
        $apply_jobs = $this->personal_models->get_apply_jobs_by_id($apply_id);
        $user = $this->user_models->get_user_info_by_id($apply_jobs['personal_uid']);
        if (!empty($user['wechat_openid'])) {
            $template_id = 'kp63sZs91hshm7L17WmppBVBZhC2Fp1_xjUwIT2hsjM';
            $url = 'http://m.jiaoshizhaopin.net/personal_center/my_apply_jobs';
            $result = toGBK('已查看');
            $first = toGBK($user['username'] . "，您好！您投递的简历有新的反馈！");
            $remark = toGBK('点击进入简历中心');
            $companyname = toGBK($apply_jobs['company_name']);
            $time = date("Y-m-d H:i", time());
            $this->wechat_models->request_wechat_msg('template|2', $user['wechat_openid'], $template_id, $url, $first, $companyname, $time, $result, $remark);
        } elseif (!empty($user['mobile'])) {
            $mobile = intval($user['mobile']);
            $this->common_models->send_sms($mobile, "", "SMS_174650930");
            /*
              $text = "【教师招聘网】您的职位申请有新的反馈，请登录教师招聘网或关注教师招聘网公众号后进入“求职招聘-我的”查看";
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

    function send_wechat_interview_core($interview_id) {
        $interview = $this->personal_models->get_invitation_by_id($interview_id);
        $user = $this->user_models->get_user_info_by_id($interview['resume_uid']);
        $company = $this->company_models->get_company_by_uid($interview['company_uid']);
        $jobs = $this->job_models->get_all_job_by_id($interview['jobs_id']);
        if (!empty($user['wechat_openid'])) {
            $template_id = 'f_PA9fvSTIrWc2acgnJjDW3_t-b447MaStPPWnNqui8';
            $url = 'http://m.jiaoshizhaopin.net/job/detail?job_id=' . $interview['jobs_id'];
            $first = toGBK($user['username'] . "，您收到一份面试邀请。");
            $company_name = toGBK($company['companyname']);
            $job_name = toGBK($jobs['jobs_name']);
            $time = date("Y-m-d H:i", time());
            $note = toGBK($interview['notes']);
            $remark = toGBK('点击查看职位详情');
            $this->wechat_models->request_wechat_msg('template|3', $user['wechat_openid'], $template_id, $url, $first, $company_name, $job_name, $time, $note, $remark);
        } elseif (!empty($user['mobile'])) {
            $mobile = intval($user['mobile']);
            //$this->common_models->send_sms($mobile, "", "SMS_174685012");
            $text = "【教师招聘网】您收到一份职位初试邀请，请登录教师招聘网或关注教师招聘网公众号后进入“求职招聘-我的”查看";
            send_sms($mobile, $text);
        }
    }

}
