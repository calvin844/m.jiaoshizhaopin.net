<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajax_data extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('common_models', 'cm');
        $this->load->model('category_models', 'cam');
        $this->load->model('article_models');
    }

    public function get_provinces() {
        $str = "";
        $provinces = $this->cam->get_provinces();
        foreach ($provinces as $p) {
            $str .= $p['categoryname'] . "||" . $p['id'] . "-|-";
        }
        $str = trim($str, "-|-");
        echo $str;
    }

    public function get_city($parent_id) {
        $str = "";
        $provinces = $this->cam->get_cities($parent_id);
        foreach ($provinces as $p) {
            $str .= $p['categoryname'] . "||" . $p['id'] . "-|-";
        }
        $str = trim($str, "-|-");
        echo $str;
    }

    public function get_job_parent_type() {
        $str = "";
        $job_parent_type = $this->cam->get_parent_job_types();
        foreach ($job_parent_type as $jpt) {
            $str .= $jpt['categoryname'] . "||" . $jpt['id'] . "-|-";
        }
        $str = trim($str, "-|-");
        echo $str;
    }

    public function get_job_type($parent_id) {
        $str = "";
        $job_type = $this->cam->get_job_types($parent_id);
        foreach ($job_type as $jt) {
            $str .= $jt['categoryname'] . "||" . $jt['id'] . "||" . $jt['parentid'] . "-|-";
        }
        $str = trim($str, "-|-");
        echo $str;
    }

    public function get_article_job($article_id) {
        $str = "";
        $article_jobs = $this->article_models->get_article_jobs_by_article_id($article_id);
        foreach ($article_jobs as $aj) {
            $str .= $aj['job_name'] . "||" . $aj['id'] . "-|-";
        }
        $str = trim($str, "-|-");
        echo $str;
    }

}
