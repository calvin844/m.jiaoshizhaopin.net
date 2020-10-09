<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Resume_models extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('splitword');
    }

    //只获取一条简历信息
    public function get_one_resume($uid) {
        $this->db->where('uid', $uid);
        $query = $this->db->get('resume');
        $result = $query->row_array();
        return $result;
    }

    public function get_resume_by_id($resume_id) {
        $this->db->where('id', $resume_id);
        $query = $this->db->get('resume');
        $result = $query->row_array();
        return $result;
    }

    public function add_resume($data) {
        $this->db->insert('resume', $data);
        $add_id = $this->db->insert_id();
        $this->update_complete_percent($data['uid']);
        return $add_id;
    }

    public function update_resume_by_uid($uid, $data) {
        $this->db->where('uid', $uid);
        $this->db->update('resume', $data);
        $this->update_complete_percent($data['uid']);
    }

    public function update_complete_percent($uid) {
        $percent = 0;
        $resume_basic = $this->get_one_resume($uid);
        $resume_education = $this->get_resume_educations($uid);
        $resume_work = $this->get_resume_works($uid);
        $resume_training = $this->get_resume_trainings($uid);
        $resume_tag = $resume_basic['tag'];
        $resume_specialty = $resume_basic['specialty'];
        $resume_photo = $resume_basic['photo_img'];
        if (!empty($resume_basic)) {
            $percent = $percent + 40;
        }
        if (!empty($resume_education)) {
            $percent = $percent + 15;
        }
        if (!empty($resume_work)) {
            $percent = $percent + 15;
        }
        if (!empty($resume_training)) {
            $percent = $percent + 15;
        }
        if (!empty($resume_tag)) {
            $percent = $percent + 5;
        }
        if (!empty($resume_specialty)) {
            $percent = $percent + 5;
        }
        if (!empty($resume_photo)) {
            $percent = $percent + 5;
        }
        $data = array('complete_percent' => $percent);
        $this->db->where('uid', $uid);
        $this->db->update('resume', $data);
        $this->sync_search($uid);
    }

    public function get_resume_educations($uid) {
        $this->db->where('uid', $uid);
        $query = $this->db->get('resume_education');
        return $query->result_array();
    }

    public function get_resume_works($uid) {
        $this->db->where('uid', $uid);
        $query = $this->db->get('resume_work');
        return $query->result_array();
    }

    public function get_resume_trainings($uid) {
        $this->db->where('uid', $uid);
        $query = $this->db->get('resume_training');
        return $query->result_array();
    }

    function add_resume_certificate($data) {
        $this->db->insert('resume_certificate', $data);
        $add_id = $this->db->insert_id();
        $this->update_complete_percent($data['uid']);
        return $add_id;
    }

    function get_resume_certificates($uid, $pid = 0, $audit = -1) {
        $this->db->where('uid', $uid);
        if ($pid > 0) {
            $this->db->where('pid', $pid);
        }
        if ($audit > -1) {
            $this->db->where('audit', $audit);
        }
        $query = $this->db->get('resume_certificate');
        return $query->result_array();
    }

    function get_resume_certificate_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('resume_certificate');
        return $query->row_array();
    }

    function del_resume_certificate($id, $uid) {
        $this->db->where('id', $id);
        $this->db->where('uid', $uid);
        $this->db->delete('resume_certificate');
    }

    function get_resume_intention($uid) {
        $this->db->where('uid', $uid);
        $query = $this->db->get('resume_jobs');
        $intention = $query->result_array();
        foreach ($intention as $r) {
            if ($r['topclass'] > 0) {
                $this->db->select('categoryname');
                $this->db->where('id', $r['topclass']);
                $query = $this->db->get('category_jobs');
                $topclass = $query->row_array();
                $r['topclass_cn'] = $topclass['categoryname'];
            }
            if ($r['category'] > 0) {
                $this->db->select('categoryname');
                $this->db->where('id', $r['category']);
                $query = $this->db->get('category_jobs');
                $category = $query->row_array();
                $r['category_cn'] = $category['categoryname'];
            }
            if ($r['subclass'] > 0) {
                $this->db->select('categoryname');
                $this->db->where('id', $r['subclass']);
                $query = $this->db->get('category_jobs');
                $subclass = $query->row_array();
            }
            $r['subclass_cn'] = !empty($subclass) ? $subclass['categoryname'] : "全部";
            if ($r['district'] > 0) {
                $this->db->select('categoryname');
                $this->db->where('id', $r['district']);
                $query = $this->db->get('category_district');
                $district = $query->row_array();
                $r['district_cn'] = $district['categoryname'];
            }
            if ($r['sdistrict'] > 0) {
                $this->db->select('categoryname');
                $this->db->where('id', $r['sdistrict']);
                $query = $this->db->get('category_district');
                $sdistrict = $query->row_array();
            }
            $r['sdistrict_cn'] = !empty($sdistrict) ? $sdistrict['categoryname'] : "";
            $result[] = $r;
        }
        return $result;
    }

    function get_key($resume) {
        $key = $resume['intention_jobs'] . $resume['specialty'];
        $key = $resume['fullname'] . " " . $this->splitword->extracttag($key);
        $key = str_replace(",", " ", $resume['intention_jobs']) . " " . $key . " " . $resume['education_cn'];
        $key = $this->splitword->pad($key);
        return $key;
    }

    function get_likekey($resume) {
        $likekey = $resume['intention_jobs'] . ',' . $resume['specialty'] . ',' . $resume['fullname'];
        return $likekey;
    }

    function sync_search($uid) {
        $this->db->where('uid', $uid);
        $query = $this->db->get('resume');
        $resume = $query->row_array();
        if (!empty($resume)) {
            $this->db->where('uid', $uid);
            $query = $this->db->get('resume_search_rtime');
            $resume_search_rtime = $query->row_array();
            $data = array('id' => $resume['id'], 'display' => $resume['display'], 'audit' => $resume['audit'], 'uid' => $uid, 'sex' => $resume['sex'], 'nature' => $resume['nature'],
                'marriage' => $resume['marriage'], 'experience' => $resume['experience'], 'district' => $resume['district'], 'sdistrict' => $resume['sdistrict'],
                'wage' => $resume['wage'], 'education' => $resume['education'], 'photo' => $resume['photo'], 'talent' => $resume['talent'], 'subsite_id' => $resume['subsite_id'], 'refreshtime' => time());
            if (!empty($resume_search_rtime)) {
                $this->db->where('uid', $uid);
                $this->db->update('resume_search_rtime', $data);
            } else {
                $this->db->insert('resume_search_rtime', $data);
            }

            $key = $this->get_key($resume);
            $likekey = $this->get_likekey($resume);
            $data['key'] = $key;
            $data['likekey'] = $likekey;

            $this->db->where('uid', $uid);
            $query = $this->db->get('resume_search_key');

            $resume_search_key = $query->row_array();
            if (!empty($resume_search_key)) {
                $this->db->where('uid', $uid);
                $this->db->update('resume_search_key', $data);
            } else {
                $this->db->insert('resume_search_key', $data);
            }

            $data = array('key' => $key);
            $this->db->where('uid', $uid);
            $this->db->update('resume', $data);
        }
    }

    function add_resume_job($data, $uid) {
        $this->db->insert('resume_jobs', $data);
        $add_id = $this->db->insert_id();
        $this->update_complete_percent($uid);
        return $add_id;
    }

    function del_resume_jobs($uid) {
        $this->db->where('uid', $uid);
        $this->db->delete('resume_jobs');
    }

    public function get_resume_education($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('resume_education');
        return $query->row_array();
    }

    public function update_resume_education($id, $data, $uid) {
        $this->db->where('id', $id);
        $this->db->update('resume_education', $data);
        $this->update_complete_percent($uid);
    }

    public function add_resume_education($data, $uid) {
        $this->db->insert('resume_education', $data);
        $add_id = $this->db->insert_id();
        $this->update_complete_percent($uid);
        return $add_id;
    }

    public function del_resume_education($id, $uid) {
        $this->db->where('id', $id);
        $this->db->where('uid', $uid);
        $this->db->delete('resume_education');
        $this->update_complete_percent($uid);
    }

    public function update_resume_work($id, $data, $uid) {
        $this->db->where('id', $id);
        $this->db->update('resume_work', $data);
        $this->update_complete_percent($uid);
    }

    public function add_resume_work($data, $uid) {
        $this->db->insert('resume_work', $data);
        $add_id = $this->db->insert_id();
        $this->update_complete_percent($uid);
        return $add_id;
    }

    public function get_resume_work($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('resume_work');
        return $query->row_array();
    }

    public function del_resume_work($id, $uid) {
        $this->db->where('id', $id);
        $this->db->where('uid', $uid);
        $this->db->delete('resume_work');
        $this->update_complete_percent($uid);
    }

    public function add_resume_training($data, $uid) {
        $this->db->insert('resume_training', $data);
        $add_id = $this->db->insert_id();
        $this->update_complete_percent($uid);
        return $add_id;
    }

    public function update_resume_training($id, $data, $uid) {
        $this->db->where('id', $id);
        $this->db->update('resume_training', $data);
        $this->update_complete_percent($uid);
    }

    public function get_resume_training($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('resume_training');
        return $query->row_array();
    }

    public function del_resume_training($id, $uid) {
        $this->db->where('id', $id);
        $this->db->where('uid', $uid);
        $this->db->delete('resume_training');
        $this->update_complete_percent($uid);
    }

    public function get_resume_attachment_by_uid($uid) {
        $this->db->where('uid', $uid);
        $query = $this->db->get('resume_attachment');
        return $query->row_array();
    }

    public function get_resume_list_by_intention($where = array()) {
        $this->db->where($where);
        $query = $this->db->get('resume_jobs');
        return $query->result_array();
    }

    public function get_resume_list_by_key($key) {
        $this->db->like('likekey', $key);
        $query = $this->db->get('resume_search_key');
        return $query->result_array();
    }

    public function get_resume_total($where = array(), $where_in = array()) {
        $this->db->where($where);
        if (!empty($where_in)) {
            $this->db->where_in('id', $where_in);
        }
        $this->db->from('resume');
        $result = $this->db->count_all_results();
        return $result;
    }

    public function get_resume_list($where = array(), $where_in = array(), $limit = 0, $offset = 0) {
        $this->db->where($where);
        if (!empty($where_in)) {
            $this->db->where_in('id', $where_in);
        }
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('refreshtime', 'desc');
        $query = $this->db->get('resume');
        return $query->result_array();
    }

    function get_user_resume_tpl_by_resume_id($resume_id) {
        $this->db->where('resume_id', $resume_id);
        $this->db->where('endtime >', time());
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('personal_resume_tpl');
        return $query->row_array();
    }

    function get_resume_tpl_by_tpl_id($tpl_id) {
        $this->db->where('tpl_id', $tpl_id);
        $this->db->where('tpl_type', 2);
        $this->db->where('tpl_display', 1);
        $query = $this->db->get('tpl');
        return $query->row_array();
    }

}
