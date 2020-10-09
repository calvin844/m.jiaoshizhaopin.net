<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Resume extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('common_models');
        $this->load->model('resume_models');
        $this->load->model('category_models');
        $this->load->model('personal_models');
        $this->load->model('company_models');
        $this->load->model('job_models');
    }

    //简历列表页
    public function index($page = 1) {
        $where = $where_in = $district_data = $job_type_data = $other = array();
        $district = $this->input->get('district');
        $job_type = $this->input->get('job_type');
        $education = $this->input->get('education');
        $experience = $this->input->get('experience');
        $resume_photo = $this->input->get('resume_photo');
        $resume_type = $this->input->get('resume_type');
        $key = $this->input->get('key');

        if ($district > 0) {
            $district_data = $this->category_models->get_district($district);
            $district_data['parentid'] == 0 ? $where['district'] = $district : $where['sdistrict'] = $district;
        }
        if ($job_type > 0) {
            $job_type_data = $this->category_models->get_job_type($job_type);
            $where['category'] = $job_type_data['parentid'] == 0 ? $job_type_data['id'] : $job_type_data['parentid'];
            $where['subclass'] = $job_type_data['parentid'] == 0 ? 0 : $job_type_data['id'];
        }
        if (!empty($where)) {
            $intention_resume = $this->resume_models->get_resume_list_by_intention($where);
            foreach ($intention_resume as $ir) {
                $where_in[] = $ir['pid'];
            }
            $where = array();
        }
        if ($education > 0) {
            $where['education'] = $education;
            $data['education_data'] = $this->category_models->get_categories_by_id($education);
        }
        if ($experience > 0) {
            $where['experience'] = $experience;
            $data['experience_data'] = $this->category_models->get_categories_by_id($experience);
        }
        if ($resume_photo == 1) {
            $other_data['photo'] = $resume_photo;
            $where['photo'] = 1;
            $where['photo_audit'] = 1;
            $where['photo_display'] = 1;
        }
        if ($resume_type == 1) {
            $other_data['talent'] = $resume_type;
            $where['talent'] = 2;
        }
        $data['other_data'] = $other_data;
        if (!empty($key)) {
            $key_resume = $this->resume_models->get_resume_list_by_key($key);
            foreach ($key_resume as $kr) {
                $key_resume_arr[] = $kr['id'];
            }
            if (!empty($where_in)) {
                $where_in_tmp = $where_in;
                $where_in = array();
                foreach ($where_in_tmp as $wit) {
                    if (in_array($wit, $key_resume_arr)) {
                        $where_in[] = $wit;
                    }
                }
            }
        }
        $where['display'] = 1;
        $where['audit'] = 1;
        $total = $this->resume_models->get_resume_total($where, $where_in);
        $page_arr = get_page_arr($total, $page);
        $resume_result = $this->resume_models->get_resume_list($where, $where_in, $page_arr['page_num'], $page_arr['offset']);
        foreach ($resume_result as $r) {
            if ($r['display_name'] == "2") {
                $r['name'] = "N" . str_pad($r['resume_id'], 7, "0", STR_PAD_LEFT);
            } elseif ($r['display_name'] == "3") {
                $r['name'] = cut_str($r['fullname'], 1, 0, "**");
            } else {
                $r['name'] = $r['fullname'];
            }
            $resume_list[] = $r;
        }
        $education_categories = array(array('id' => toGBK('0|不限'), 'value' => toGBK('不限')));
        $education_categories_tmp = $this->category_models->get_categories('QS_education');
        foreach ($education_categories_tmp as $e) {
            if ($e['c_name'] != "不限") {
                $e_tmp['id'] = toGBK($e['c_id'] . "|" . $e['c_name']);
                $e_tmp['value'] = toGBK($e['c_name']);
                $education_categories[] = $e_tmp;
            }
        }
        $data['education_categories'] = json_encode($education_categories);

        $experience_categories = array(array('id' => toGBK('0|不限'), 'value' => toGBK('不限')));
        $experience_categories_tmp = $this->category_models->get_categories('QS_experience');
        foreach ($experience_categories_tmp as $e) {
            if ($e['c_name'] != "不限") {
                $ex_tmp['id'] = toGBK($e['c_id'] . "|" . $e['c_name']);
                $ex_tmp['value'] = toGBK($e['c_name']);
                $experience_categories[] = $ex_tmp;
            }
        }
        $data['experience_categories'] = json_encode($experience_categories);

        $district_categories = array(array('id' => toGBK('0|不限'), 'value' => toGBK('不限'), 'childs' => array(array('id' => toGBK('0|不限'), 'value' => toGBK('不限')))));
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

        $jobs_type = array(array('id' => toGBK('0|不限'), 'value' => toGBK('不限'), 'childs' => array(array('id' => toGBK('0|不限'), 'value' => toGBK('不限')))));
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
        $data['jobs_categories'] = json_encode($jobs_type);
        $talent_categories = array(array('id' => 0, 'value' => toGBK('不限')), array('id' => 1, 'value' => toGBK('高级')));
        $data['talent_categories'] = json_encode($talent_categories);
        $photo_categories = array(array('id' => 0, 'value' => toGBK('不限')), array('id' => 1, 'value' => toGBK('有照片')));
        $data['photo_categories'] = json_encode($photo_categories);


        $data['parent_job_types'] = $this->category_models->get_parent_job_types();
        foreach ($data['parent_job_types'] as $pjt) {
            $job_types[$pjt['id']] = $this->category_models->get_job_types($pjt['id']);
        }
        $data['job_types'] = $job_types;
        $data['key'] = $key;
        $data['district'] = $district_data;
        $data['job_type'] = $job_type_data;
        $data['education'] = $education;
        $data['experience'] = $experience;
        $data['resume_photo'] = intval($resume_photo) > 0 ? intval($resume_photo) : 0;
        $data['resume_type'] = intval($resume_type) > 0 ? intval($resume_type) : 0;
        $data['resume_list'] = $resume_list;
        $data['page_arr'] = $page_arr;
        $this->load->view('/resume/resume_list', $data);
    }

    //简历详细页
    public function detail() {
        $apply_id = $this->input->get('apply_id');
        $resume_id = $this->input->get('resume_id');
        $down = $job_list = array();
        $show = 0;
        if ($apply_id > 0) {
            $apply = $this->personal_models->get_apply_jobs_by_id($apply_id);
            $resume_id = $apply['resume_id'];
            if (!empty($apply)) {
                $show = 1;
                $website = 'http://m.jiaoshizhaopin.net/personal_center/send_wechat_resume_view_core/' . $apply_id;
//                //dfopen($website);
                $ch = curl_init();
                // 设置URL和相应的选项
                curl_setopt($ch, CURLOPT_URL, $website);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                // 抓取URL并把它传递给浏览器
                curl_exec($ch);
                // 关闭cURL资源，并且释放系统资源
                curl_close($ch);
            }
        }
        $resume = $this->resume_models->get_resume_by_id($resume_id);
        $resume['intention_jobs'] = explode(",", $resume['intention_jobs']);
        $utype = $this->session->userdata('utype');
        if ($utype == 1) {
            $uid = $this->session->userdata('uid');
            $down = $this->company_models->get_resume_down_by_uid_rid($uid, $resume_id);
            $c_apply = $this->company_models->get_resume_apply_by_uid_rid($uid, $resume_id);
            $company = $this->company_models->get_company_by_uid($uid);
            $where['uid'] = $uid;
            $job_list = $this->job_models->get_job_list($where);
            $shield_key = $this->personal_models->get_shield_company_by_uid($resume['uid']);
            foreach ($shield_key as $key => $value) {
                if (!empty($value['comkeyword']) && stristr($company['companyname'], $value['comkeyword'])) {
                    alert_to('简历错误！', "history.go(-1);", 1);
                }
            }
            //检查是否查看过
            $check = $this->company_models->get_view_log($uid, $resume_id);
            if (empty($check)) {
                $this->company_models->add_view_log($uid, $resume_id);
            }
        }
        $resume['education_list'] = $this->resume_models->get_resume_educations($resume['uid']);
        $resume['work_list'] = $this->resume_models->get_resume_works($resume['uid']);
        $resume['training_list'] = $this->resume_models->get_resume_trainings($resume['uid']);
        $resume['age'] = $resume['birthdate'] > 0 ? date("Y") - $resume['birthdate'] : "";
        if ($resume['photo'] == "1" && !empty($resume['photo_img'])) {
            $resume['photosrc'] = "/data/photo/" . $resume['photo_img'];
        } else {
            $resume['photosrc'] = "/data/photo/" . "no_photo.gif";
        }
        if ($resume['tag']) {
            $tag = explode('|', $resume['tag']);
            $taglist = array();
            if (!empty($tag) && is_array($tag)) {
                foreach ($tag as $t) {
                    $tli = explode(',', $t);
                    $taglist[] = $tli[1];
                }
            }
            $resume['tag'] = $taglist;
        } else {
            $resume['tag'] = array();
        }
        $resume['refreshtime_cn'] = date('Y-m-d', $resume['refreshtime']);

        if (!empty($down) || !empty($c_apply)) {
            $show = 1;
            $resume['name'] = $resume['fullname'];
        } elseif ($show == 1) {
            $resume['name'] = $resume['fullname'];
        } elseif ($r['display_name'] == "2") {
            $resume['name'] = "N" . str_pad($resume['resume_id'], 7, "0", STR_PAD_LEFT);
        } elseif ($r['display_name'] == "3") {
            $resume['name'] = cut_str($resume['fullname'], 1, 0, "**");
        } else {
            $resume['name'] = $resume['fullname'];
        }
        if ($show == 1) {
            $resume['certificate_list'] = $this->resume_models->get_resume_certificates($resume['uid'], $resume['id'], 1);
        } else {
            $certificate_list = $this->resume_models->get_resume_certificates($resume['uid'], $resume['id'], 1);
            foreach ($certificate_list as $cl) {
                $cl['path'] = "no_certificate.jpg";
                $resume['certificate_list'][] = $cl;
            }
        }
        $utpl = $this->resume_models->get_user_resume_tpl_by_resume_id($resume['id']);
        if (!empty($utpl)) {
            $tpl = $this->resume_models->get_resume_tpl_by_tpl_id($utpl['tpl_id']);
            $tpl_dir = !empty($tpl['tpl_dir']) ? $tpl['tpl_dir'] : $this->common_models->get_sys_config('tpl_personal');
        } else {
            $tpl_dir = $this->common_models->get_sys_config('tpl_personal');
        }


        $data['tpl_dir'] = $tpl_dir;
        $data['show'] = $show;
        $data['resume'] = $resume;
        $data['utype'] = $utype;
        $data['job_list'] = $job_list;
        $data['job_total'] = !empty($job_list) ? count($job_list) : 0;
        $this->load->view('/resume/resume_detail', $data);
    }

}
