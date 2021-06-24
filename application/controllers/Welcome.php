<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Welcome extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('wx_jssdk');
        $this->load->model('common_models');
        $this->load->model('wechat_models');
        $this->load->model('category_models');
        $this->load->model('article_models');
        $this->load->model('company_models');
        $this->load->model('job_models');
        $this->load->model('ad_models');
    }

    function index() {
        $this->wechat_models->get_wechat_base_token();
        $district_info = !empty($_SESSION['district_info']) ? $this->session->userdata['district_info'] : "";
        $district_info = !empty($district_info) ? $district_info : array('id' => 0, 'parentid' => 0, 'categoryname' => '全国');

        if ($district_info['id'] > 0) {
            $district_info['parentid'] > 0 ? $where['sdistrict'] = $district_info['id'] : $where['district'] = $district_info['id'];
        }
        $where['is_display'] = 1;
        $where['type_id !='] = "7";
        $where['audit'] = '1';
        $article_list = $this->article_models->get_article_list($where, "", 10);
        $data['article_list'] = $article_list;

        $where = "id IN(SELECT id FROM `qs_jobs`)";
        if ($district_info['id'] > 0) {
            $where .= $district_info['parentid'] > 0 ? " AND sdistrict = " . $district_info['id'] : " AND district = " . $district_info['id'];
        }
        $job_arr = $this->job_models->get_jobs_id_arr("jobs_search_stickrtime", $where, "", 10);
        foreach ($job_arr as $a) {
            $job = $this->job_models->get_all_job_by_id($a['id']);
            $wage_cn = explode("/", $job['wage_cn']);
            $job['wage_cn'] = $wage_cn[0];
            !empty($job) ? $job_list[] = $job : "";
        }
        $data['job_list'] = $job_list;

        $where = "id IN(SELECT id FROM `qs_jobs`) AND emergency =1";
        if ($district_info['id'] > 0) {
            $where .= $district_info['parentid'] > 0 ? " AND sdistrict = " . $district_info['id'] : " AND district = " . $district_info['id'];
        }
        $emergency_job_arr = $this->job_models->get_jobs_id_arr("jobs_search_stickrtime", $where, "", 6);
        foreach ($emergency_job_arr as $a) {
            $job = $this->job_models->get_all_job_by_id($a['id']);
            $wage_cn = explode("/", $job['wage_cn']);
            $job['wage_cn'] = $wage_cn[0];
            !empty($job) ? $emergency_job_list[] = $job : "";
        }
        $data['emergency'] = $emergency_job_list;

        $ad = $this->ad_models->get_ad();
        $data['ad'] = $ad;
        $data['district_info'] = $district_info;
        $this->load->view('/welcome/welcome_index', $data);
    }

    function refresh_wechat_base_token() {
        $this->wechat_models->get_wechat_base_token(1);
    }

    function service() {
        $this->load->view('/welcome/welcome_service');
    }

    function select_district_save() {
        $district_flag = $this->input->post('district');
        $district_info = intval($district_flag) > 0 ? $this->category_models->get_district($district_flag) : array('id' => 0, 'parentid' => 0, 'categoryname' => '全国');
        $this->session->set_userdata(array("district_info" => $district_info));
    }

    function click_kdls() {
        $this->session->set_userdata(array("kdls_window" => 1));
    }

    function wx_share() {
        $type = $_GET['type'];
        $id = $_GET['id'];
        $url = $_GET['url'];
        $signPackage = $this->wx_jssdk->getSignPackage($url);
        $share_data['appid'] = $signPackage['appId'];
        $share_data['noncestr'] = $signPackage['nonceStr'];
        $share_data['timestamp'] = $signPackage['timestamp'];
        $share_data['url'] = $signPackage['url'];
        $share_data['signature'] = $signPackage['signature'];
        $share_data['rawString'] = $signPackage['rawString'];
        switch ($type) {
            case 'article':
                $article = $this->article_models->get_article_by_id($id);
                $share_data['title'] = toGBK($article['title'] . "_教师招聘网");
                $des = get_page_desc($article['content']);
                $share_data['des'] = toGBK($des);
                $share_data['imgurl'] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . VIEW_PATH . 'images/logo2.png';
                break;
            case 'article_job':
                $article_job = $this->article_models->get_article_jobs_by_id($id);
                $article = $this->article_models->get_article_by_id($article_job['article_id']);
                $share_data['title'] = toGBK($article_job['job_name'] . "_教师招聘网");
                $des = get_page_desc($article['content']);
                $share_data['des'] = toGBK($des);
                $share_data['imgurl'] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . VIEW_PATH . 'images/logo2.png';
                break;
            case 'job':
                $job = $this->job_models->get_all_job_by_id($id);
                $company = $this->company_models->get_company($job['company_id']);
                $share_data['title'] = toGBK($job['jobs_name'] . "_教师招聘网");
                $des = get_page_desc($job['contents']);
                $share_data['des'] = toGBK($des);
                $share_data['imgurl'] = !empty($company['logo']) ? 'http://' . $_SERVER['HTTP_HOST'] . "/data/logo/" . $company['logo'] : 'http://' . $_SERVER['HTTP_HOST'] . '/' . VIEW_PATH . 'images/logo2.png';
                break;
            case 'company':
                $company = $this->company_models->get_company($id);
                $share_data['title'] = toGBK($company['companyname'] . "_教师招聘网");
                $des = get_page_desc($company['contents']);
                $share_data['des'] = toGBK($des);
                $share_data['imgurl'] = !empty($company['logo']) ? 'http://' . $_SERVER['HTTP_HOST'] . "/data/logo/" . $company['logo'] : 'http://' . $_SERVER['HTTP_HOST'] . '/' . VIEW_PATH . 'images/logo2.png';
                break;
            default:
                $share_data['title'] = toGBK("教师招聘网_" . date("Y") . "年最新教师招聘考试信息");
                $share_data['des'] = toGBK("教师招聘网是国内大型教育人力资源专业网站！集网络招聘、高校毕业生就业服务、事业单位公共招聘信息发布等多项服务于一身。专注缔造高效，通过教师人才库为各用人单位提供更加精准的教师人才，让学校在最短的时间招到满意的教师！");
                $share_data['imgurl'] = 'http://' . $_SERVER['HTTP_HOST'] . '/' . VIEW_PATH . 'images/logo2.png';
                break;
        }
        echo json_encode($share_data);
    }

}
