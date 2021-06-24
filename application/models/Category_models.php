<?php

class Category_models extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->mem = new Memcache;
        $this->mem->connect("localhost", 11111);
    }

    public function get_district($district_id) {
        $this->db->where('id', $district_id);
        $query = $this->db->get('category_district');
        return $query->row_array();
    }

    public function get_district_pinyin($pinyin) {
        $this->db->where('pinyin', $pinyin);
        $query = $this->db->get('category_district');
        return $query->row_array();
    }

    public function get_hot_district($limit = 10) {
        $this->db->order_by('category_order', 'desc');
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        $query = $this->db->get('category_district');
        return $query->result_array();
    }

    public function get_provinces() {
        $this->db->select('id,parentid,categoryname,category_order,is_hot,pinyin');
        $this->db->where('parentid', 0);
        $this->db->order_by('category_order', 'desc');
        $query = $this->db->get('category_district');
        return $query->result_array();
    }

    public function get_cities($parent_district_id) {
        $this->db->select('id,parentid,categoryname,category_order,is_hot,pinyin');
        $this->db->where('parentid', $parent_district_id);
        $this->db->order_by('category_order', 'desc');
        $query = $this->db->get('category_district');
        return $query->result_array();
    }

    public function get_parent_job_types() {
        $this->db->select('id,parentid,categoryname,category_order');
        $this->db->where('parentid', 0);
        $this->db->order_by('category_order', 'desc');
        $query = $this->db->get('category_jobs');
        return $query->result_array();
    }

    public function get_job_types($parent_job_type_id) {
        $this->db->select('id,parentid,categoryname,category_order');
        $this->db->where('parentid', $parent_job_type_id);
        $this->db->order_by('category_order', 'desc');
        $query = $this->db->get('category_jobs');
        return $query->result_array();
    }

    public function get_job_type($job_type_id) {
        $this->db->select('id,parentid,categoryname,category_order');
        $this->db->where('id', $job_type_id);
        $query = $this->db->get('category_jobs');
        return $query->row_array();
    }

    public function get_categories($c_alias) {
        $this->db->where('c_alias', $c_alias);
        $query = $this->db->get('category');
        return $query->result_array();
    }

    public function get_categories_by_id($id) {
        $this->db->where('c_id', $id);
        $query = $this->db->get('category');
        return $query->row_array();
    }

    function get_all_categories($keywords) {
        $categories = $this->mem->get('all_job_categories');
        if (!$categories) {
            $this->db->select('id,parentid,categoryname,category_order');
            $this->db->where('parentid !=', 0);
            $this->db->order_by('category_order', 'desc');
            $query = $this->db->get('category_jobs');
            $categories = $query->result_array();
            $this->mem->set('all_job_categories', $categories, 0, 86400);
        }
        $key_categories = array();
        foreach ($keywords as $keyword) {
            foreach ($categories as $category) {
                $pos = stristr($category['categoryname'], $keyword);
                if ($pos) {
                    $key_categories[] = $category;
                }
            }
        }
        return $key_categories;
    }

    //根据名字获取地区
    public function get_district_by_name($district_cn) {
        $this->db->select('id,parentid,categoryname,category_order');
        $this->db->like('categoryname', $district_cn);
        $query = $this->db->get('category_district');
        return $query->row_array();
    }

    //获取简章分类
    public function get_article_category($where = "") {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get('article_category');
        return $query->result_array();
    }

    //根据ID获取简章分类
    public function get_article_category_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('article_category');
        return $query->row_array();
    }

}
