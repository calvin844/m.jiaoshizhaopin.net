<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Personal_models extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    //�����û�ID��ȡ������������
    public function get_invitation_by_uid($uid, $limit = 0, $offset = 0) {
        $this->db->where('resume_uid', $uid);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('company_interview');
        $result = $query->result_array();
        return $result;
    }

    //����ID��ȡ��������
    public function get_invitation_by_id($id) {
        $this->db->where('did', $id);
        $query = $this->db->get('company_interview');
        $result = $query->row_array();
        return $result;
    }

    //��ȡ����������������
    public function get_invitation_total_by_uid($uid) {
        $this->db->where('resume_uid', $uid);
        $this->db->from('company_interview');
        $result = $this->db->count_all_results();
        return $result;
    }

    //������������鿴״̬
    public function set_look_invitation($id, $look = 2) {
        $data['personal_look'] = $look;
        $this->db->where('did', $id);
        $this->db->update('company_interview', $data);
    }

    //����ID��ȡ��������ְλ
    public function get_apply_jobs_by_id($id) {
        $this->db->where('did', $id);
        $query = $this->db->get('personal_jobs_apply');
        $result = $query->row_array();
        return $result;
    }

    //�����û�ID��ȡ��������ְλ
    public function get_apply_jobs_by_uid($uid, $limit = 0, $offset = 0) {
        $this->db->where('personal_uid', $uid);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('personal_jobs_apply');
        $result = $query->result_array();
        return $result;
    }

    //�����û�ID��ְλID��ȡ��������ְλ
    public function get_apply_jobs_by_uid_jid($uid, $job_id) {
        $this->db->where('personal_uid', $uid);
        $this->db->where('jobs_id', $job_id);
        $query = $this->db->get('personal_jobs_apply');
        $result = $query->row_array();
        return $result;
    }

    //��ȡ��������ְλ����
    public function get_apply_jobs_total_by_uid($uid) {
        $this->db->where('personal_uid', $uid);
        $this->db->from('personal_jobs_apply');
        $result = $this->db->count_all_results();
        return $result;
    }

    //��ȡ���˵�������ְλ����
    public function get_apply_jobs_today_total_by_uid($uid) {
        $uid = intval($uid);
        $now = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $this->db->where('personal_uid', $uid);
        $this->db->where('apply_addtime >', $now);
        $this->db->from('personal_jobs_apply');
        $result = $this->db->count_all_results();
        return $result;
    }

    //����Ͷ��ְλ
    public function add_apply_jobs($data) {
        $this->db->insert('personal_jobs_apply', $data);
        $add_id = $this->db->insert_id();
        return $add_id;
    }

    //��Ӹ����ղؼ���
    public function add_favorites_article($data) {
        $this->db->insert('personal_favorite_articles', $data);
        $add_id = $this->db->insert_id();
        return $add_id;
    }

    //����Ͷ�ݼ���ְλ
    public function add_apply_article_jobs($data) {
        $this->db->insert('jiaoshi_article_apply', $data);
        $add_id = $this->db->insert_id();
        return $add_id;
    }

    //�����û�ID��ȡ�����������ְλ
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

    //�����û�ID��ְλID��ȡ�����������ְλ
    public function get_apply_article_by_uid_jid($uid, $article_job_id) {
        $this->db->where('personal_uid', $uid);
        $this->db->where('article_job_id', $article_job_id);
        $query = $this->db->get('jiaoshi_article_apply');
        $result = $query->row_array();
        return $result;
    }

    //��ȡ�����������ְλ����
    public function get_apply_article_total_by_uid($uid) {
        $this->db->where('personal_uid', $uid);
        $this->db->from('jiaoshi_article_apply');
        $result = $this->db->count_all_results();
        return $result;
    }

    //��ȡ�����ղؼ�������
    public function get_favorites_article_total_by_uid($uid) {
        $this->db->where('personal_uid', $uid);
        $this->db->from('personal_favorite_articles');
        $result = $this->db->count_all_results();
        return $result;
    }

    //�����û�ID��ȡ�����ղؼ���
    public function get_favorites_article_by_uid($uid, $limit = 0, $offset = 0) {
        $this->db->where('personal_uid', $uid);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('personal_favorite_articles');
        $result = $query->result_array();
        return $result;
    }

    //�����û�ID�ͼ���ID��ȡ�����ղؼ���
    public function get_favorites_article_by_uid_aid($uid, $article_id) {
        $this->db->where('personal_uid', $uid);
        $this->db->where('article_id', $article_id);
        $query = $this->db->get('personal_favorite_articles');
        $result = $query->row_array();
        return $result;
    }

    //����IDɾ�������ղؼ���
    public function del_favorites_article_in_id($id_arr = "", $uid = 0) {
        $this->db->where('personal_uid', $uid);
        $this->db->where_in('id', $id_arr);
        $this->db->delete('personal_favorite_articles');
        return $this->db->affected_rows();
    }

    //��Ӹ����ղ�ְλ
    public function add_favorites_jobs($data) {
        $this->db->insert('personal_favorites', $data);
        $add_id = $this->db->insert_id();
        return $add_id;
    }

    //��ȡ�����ղ�ְλ����
    public function get_favorites_jobs_total_by_uid($uid) {
        $this->db->where('personal_uid', $uid);
        $this->db->from('personal_favorites');
        $result = $this->db->count_all_results();
        return $result;
    }

    //�����û�ID��ְλID��ȡ�����ղ�ְλ
    public function get_favorites_jobs_by_uid_jid($uid, $job_id) {
        $this->db->where('personal_uid', $uid);
        $this->db->where('jobs_id', $job_id);
        $query = $this->db->get('personal_favorites');
        $result = $query->row_array();
        return $result;
    }

    //�����û�ID��ȡ�����ղ�ְλ
    public function get_favorites_jobs_by_uid($uid, $limit = 0, $offset = 0) {
        $this->db->where('personal_uid', $uid);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get('personal_favorites');
        $result = $query->result_array();
        return $result;
    }

    //����IDɾ�������ղ�ְλ
    public function del_favorites_jobs_in_id($id_arr = "", $uid = 0) {
        $this->db->where('personal_uid', $uid);
        $this->db->where_in('did', $id_arr);
        $this->db->delete('personal_favorites');
        return $this->db->affected_rows();
    }

    //�����û�ID��ȡ����������ҵ�ؼ���
    public function get_shield_company_by_uid($uid) {
        $this->db->where('uid', $uid);
        $query = $this->db->get('personal_shield_company');
        $result = $query->result_array();
        return $result;
    }

}
