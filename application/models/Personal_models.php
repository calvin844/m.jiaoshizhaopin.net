<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Personal_models extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    //根据用户ID获取个人面试邀请
    public function get_invitation_by_uid($uid, $limit = 0, $offset = 0) {
        $this->db->where('resume_uid', $uid);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('company_interview');
        $result = $query->result_array();
        return $result;
    }

    //根据ID获取面试邀请
    public function get_invitation_by_id($id) {
        $this->db->where('did', $id);
        $query = $this->db->get('company_interview');
        $result = $query->row_array();
        return $result;
    }

    //获取个人面试邀请总数
    public function get_invitation_total_by_uid($uid) {
        $this->db->where('resume_uid', $uid);
        $this->db->from('company_interview');
        $result = $this->db->count_all_results();
        return $result;
    }

    //更新面试邀请查看状态
    public function set_look_invitation($id, $look = 2) {
        $data['personal_look'] = $look;
        $this->db->where('did', $id);
        $this->db->update('company_interview', $data);
    }

    //根据ID获取个人申请职位
    public function get_apply_jobs_by_id($id) {
        $this->db->where('did', $id);
        $query = $this->db->get('personal_jobs_apply');
        $result = $query->row_array();
        return $result;
    }

    //根据用户ID获取个人申请职位
    public function get_apply_jobs_by_uid($uid, $limit = 0, $offset = 0) {
        $this->db->where('personal_uid', $uid);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('personal_jobs_apply');
        $result = $query->result_array();
        return $result;
    }

    //根据用户ID和职位ID获取个人申请职位
    public function get_apply_jobs_by_uid_jid($uid, $job_id) {
        $this->db->where('personal_uid', $uid);
        $this->db->where('jobs_id', $job_id);
        $query = $this->db->get('personal_jobs_apply');
        $result = $query->row_array();
        return $result;
    }

    //获取个人申请职位总数
    public function get_apply_jobs_total_by_uid($uid) {
        $this->db->where('personal_uid', $uid);
        $this->db->from('personal_jobs_apply');
        $result = $this->db->count_all_results();
        return $result;
    }

    //获取个人当天申请职位总数
    public function get_apply_jobs_today_total_by_uid($uid) {
        $uid = intval($uid);
        $now = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $this->db->where('personal_uid', $uid);
        $this->db->where('apply_addtime >', $now);
        $this->db->from('personal_jobs_apply');
        $result = $this->db->count_all_results();
        return $result;
    }

    //个人投递职位
    public function add_apply_jobs($data) {
        $this->db->insert('personal_jobs_apply', $data);
        $add_id = $this->db->insert_id();
        return $add_id;
    }

    //添加个人收藏简章
    public function add_favorites_article($data) {
        $this->db->insert('personal_favorite_articles', $data);
        $add_id = $this->db->insert_id();
        return $add_id;
    }

    //个人投递简章职位
    public function add_apply_article_jobs($data) {
        $this->db->insert('jiaoshi_article_apply', $data);
        $add_id = $this->db->insert_id();
        return $add_id;
    }

    //根据用户ID获取个人申请简章职位
    public function get_apply_article_by_uid($uid, $limit = 0, $offset = 0) {
        $this->db->where('personal_uid', $uid);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('apply_addtime DESC');
        $query = $this->db->get('jiaoshi_article_apply');
        $result = $query->result_array();
        return $result;
    }

    //根据用户ID和职位ID获取个人申请简章职位
    public function get_apply_article_by_uid_jid($uid, $article_job_id) {
        $this->db->where('personal_uid', $uid);
        $this->db->where('article_job_id', $article_job_id);
        $query = $this->db->get('jiaoshi_article_apply');
        $result = $query->row_array();
        return $result;
    }

    //获取个人申请简章职位总数
    public function get_apply_article_total_by_uid($uid) {
        $this->db->where('personal_uid', $uid);
        $this->db->from('jiaoshi_article_apply');
        $result = $this->db->count_all_results();
        return $result;
    }

    //获取个人收藏简章总数
    public function get_favorites_article_total_by_uid($uid) {
        $this->db->where('personal_uid', $uid);
        $this->db->from('personal_favorite_articles');
        $result = $this->db->count_all_results();
        return $result;
    }

    //根据用户ID获取个人收藏简章
    public function get_favorites_article_by_uid($uid, $limit = 0, $offset = 0) {
        $this->db->where('personal_uid', $uid);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('personal_favorite_articles');
        $result = $query->result_array();
        return $result;
    }

    //根据用户ID和简章ID获取个人收藏简章
    public function get_favorites_article_by_uid_aid($uid, $article_id) {
        $this->db->where('personal_uid', $uid);
        $this->db->where('article_id', $article_id);
        $query = $this->db->get('personal_favorite_articles');
        $result = $query->row_array();
        return $result;
    }

    //根据ID删除个人收藏简章
    public function del_favorites_article_in_id($id_arr = "", $uid = 0) {
        $this->db->where('personal_uid', $uid);
        $this->db->where_in('id', $id_arr);
        $this->db->delete('personal_favorite_articles');
        return $this->db->affected_rows();
    }

    //添加个人收藏职位
    public function add_favorites_jobs($data) {
        $this->db->insert('personal_favorites', $data);
        $add_id = $this->db->insert_id();
        return $add_id;
    }

    //获取个人收藏职位总数
    public function get_favorites_jobs_total_by_uid($uid) {
        $this->db->where('personal_uid', $uid);
        $this->db->from('personal_favorites');
        $result = $this->db->count_all_results();
        return $result;
    }

    //根据用户ID和职位ID获取个人收藏职位
    public function get_favorites_jobs_by_uid_jid($uid, $job_id) {
        $this->db->where('personal_uid', $uid);
        $this->db->where('jobs_id', $job_id);
        $query = $this->db->get('personal_favorites');
        $result = $query->row_array();
        return $result;
    }

    //根据用户ID获取个人收藏职位
    public function get_favorites_jobs_by_uid($uid, $limit = 0, $offset = 0) {
        $this->db->where('personal_uid', $uid);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('personal_favorites');
        $result = $query->result_array();
        return $result;
    }

    //根据ID删除个人收藏职位
    public function del_favorites_jobs_in_id($id_arr = "", $uid = 0) {
        $this->db->where('personal_uid', $uid);
        $this->db->where_in('did', $id_arr);
        $this->db->delete('personal_favorites');
        return $this->db->affected_rows();
    }

    //根据用户ID获取个人屏蔽企业关键字
    public function get_shield_company_by_uid($uid) {
        $this->db->where('uid', $uid);
        $query = $this->db->get('personal_shield_company');
        $result = $query->result_array();
        return $result;
    }

}
