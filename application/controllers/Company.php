<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Company extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('common_models');
        $this->load->model('company_models');
        $this->load->model('job_models');
    }

    //企业详细页
    public function detail() {
        $company_id = $this->input->get('company_id');
        $company = $this->company_models->get_company($company_id);

        if (!empty($company['nature_cn']) && !empty($company['scale_cn'])) {
            $company['info_str'] = $company['nature_cn'] . "&nbsp;|&nbsp;" . $company['scale_cn'];
        } else {
            $company['info_str'] = !empty($company['nature_cn']) ? $company['nature_cn'] : $company['scale_cn'];
        }
        $company_imgs = $this->company_models->get_company_imgs_by_cid($company_id, $audit = 1);
        $company_jobs_arr = $this->job_models->get_job_list(array('company_id' => $company['id']));
        foreach ($company_jobs_arr as $cj) {
            $cj['info_str'] = "";
            $wage_cn = explode("/", $cj['wage_cn']);
            $cj['wage_cn'] = $wage_cn[0];
            if ($cj['amount'] > 0) {
                $cj['info_str'].=$cj['amount'] . "人/";
            }
            if ($cj['education'] > 0 && $cj['education_cn'] != "不限") {
                $cj['info_str'].=$cj['education_cn'] . "/";
            }
            if ($cj['experience'] > 0 && $cj['experience_cn'] != "不限") {
                $cj['info_str'].=$cj['experience_cn'] . "/";
            }
            $cj['info_str'] = trim($cj['info_str'], "/");
            $company_jobs[] = $cj;
        }
        $data['company'] = $company;
        $data['company_jobs'] = $company_jobs;
        $data['company_imgs'] = $company_imgs;
        $keywords = $company['companyname'] . "," . $company['district_cn'] . "教师招聘," . $company['companyname'] . "招聘信息";
        $description = !empty($company['contents']) ? get_page_desc($company['contents']) : $company['companyname'];
        $data['seo_keywords'] = $keywords;
        $data['seo_description'] = $description;
        $this->load->view('/company/company_detail', $data);
    }

    //企业地图页
    public function map($company_id) {
        $company = $this->company_models->get_company($company_id);
        $data['company'] = $company;
        $this->load->view('/company/company_map', $data);
    }

}
