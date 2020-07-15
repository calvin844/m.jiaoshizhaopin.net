<?php

class Article_models extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    //根据ID获取简章职位
    public function get_article_job_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('jiaoshi_article_jobs');
        return $query->row_array();
    }

    //根据ID获取简章分类
    public function get_article_category_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('article_category');
        return $query->row_array();
    }

    //根据ID获取简章
    public function get_article_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('article');
        return $query->row_array();
    }

    //根据ID获取简章职位
    public function get_article_jobs_by_id($article_job_id) {
        $this->db->where('id', $article_job_id);
        $query = $this->db->get('jiaoshi_article_jobs');
        return $query->row_array();
    }

    //根据ID获取简章
    public function get_article_jobs_by_article_id($article_id) {
        $this->db->where('article_id', $article_id);
        $query = $this->db->get('jiaoshi_article_jobs');
        return $query->result_array();
    }

    //根据复数地区获取简章列表
    public function get_article_list_in_district($where = "", $limit = 10) {
        if (!empty($where)) {
            foreach ($where as $k => $v) {
                if ($k != "subclass" && $k != "category") {
                    $this->db->where_in($k, $v);
                }
            }
        }
        $this->db->where('audit', "1");
        $this->db->where('type_id !=', "7");
        $this->db->where('is_display', "1");
        $this->db->order_by("refreshtime DESC");
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        $query = $this->db->get('article');
        $result = $query->result_array();
        return $result;
    }

    //点击量+1
    public function add_mobile_click($article_id) {
        $this->db->select('click,mobile_click');
        $this->db->where('id', $article_id);
        $query = $this->db->get('article');
        $article = $query->row_array();
        $data = array('mobile_click' => ($article['mobile_click'] + 1), 'click' => ($article['click'] + 1));
        $this->db->where('id', $article_id);
        $this->db->update('article', $data);
    }

    //按简章搜索结果总数
    public function get_article_total($where = "", $key = "") {
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($key)) {
            $this->db->like('title', $key);
        }
        $this->db->from('article');
        $result = $this->db->count_all_results();
        return $result;
    }

    //获取简章列表
    public function get_article_list($where = "", $key = "", $limit = 0, $offset = 0, $order_by = "refreshtime DESC") {
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($key)) {
            $this->db->like('title', $key);
        }
        if (!empty($order_by)) {
            $this->db->order_by($order_by);
        }
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('article');
        return $query->result_array();
    }

    function get_article_jobs_in($article_job_ids) {
        $this->db->where_in('id', $article_job_ids);
        $query = $this->db->get('jiaoshi_article_jobs');
        return $query->result_array();
    }

    function get_article_url($article_id) {
        $article = $this->get_article_by_id($article_id);
        $sdistrict_id = $article['sdistrict'] ? $article['sdistrict'] : $article['district'];

        $this->db->where('id', $sdistrict_id);
        $query = $this->db->get('category_district');
        $pinyin_res = $query->row_array();
        $this->db->where('id', $pinyin_res['parentid']);
        $query = $this->db->get('category_district');
        $parent_pinyin_res = $query->row_array();
        if (!empty($parent_pinyin_res)) {
            $url = "/" . $parent_pinyin_res['pinyin'] . "/" . $pinyin_res['pinyin'] . "/jobshow_" . $article['id'] . ".html";
        } else {
            $url = "/" . $pinyin_res['pinyin'] . "/jobshow_" . $article['id'] . ".html";
        }
        return $url;
    }

}
