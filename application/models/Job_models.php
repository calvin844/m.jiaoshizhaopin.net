<?php

class Job_models extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    //删除职位搜索索引
    public function del_job_search_by_id($id) {
        $this->db->where('pid', $id);
        $this->db->delete('jobs_contact');
        $this->db->where('cp_jobid', $id);
        $this->db->delete('promotion');
        $this->db->where('id', $id);
        $this->db->delete('jobs_search_hot');
        $this->db->where('id', $id);
        $this->db->delete('jobs_search_key');
        $this->db->where('id', $id);
        $this->db->delete('jobs_search_rtime');
        $this->db->where('id', $id);
        $this->db->delete('jobs_search_stickrtime');
        $this->db->where('id', $id);
        $this->db->delete('jobs_search_wage');
        $this->db->where('id', $id);
        $this->db->delete('jobs_search_tag');
        $this->db->where('jobsid', $id);
        $this->db->delete('view_jobs');
    }

    //根据ID和用户ID删除职位相关信息
    public function del_job_other_by_id_uid($id, $uid) {
        $this->db->where('pid', $id);
        $this->db->delete('jobs_contact');
        $this->db->where('cp_jobid', $id);
        $this->db->delete('promotion');
        $this->db->where('uid', $uid);
        $this->db->where('id', $id);
        $this->db->delete('jobs_search_hot');
        $this->db->where('uid', $uid);
        $this->db->where('id', $id);
        $this->db->delete('jobs_search_key');
        $this->db->where('uid', $uid);
        $this->db->where('id', $id);
        $this->db->delete('jobs_search_rtime');
        $this->db->where('uid', $uid);
        $this->db->where('id', $id);
        $this->db->delete('jobs_search_scale');
        $this->db->where('uid', $uid);
        $this->db->where('id', $id);
        $this->db->delete('jobs_search_stickrtime');
        $this->db->where('uid', $uid);
        $this->db->where('id', $id);
        $this->db->delete('jobs_search_wage');
        $this->db->where('uid', $uid);
        $this->db->where('id', $id);
        $this->db->delete('jobs_search_tag');
        $this->db->where('jobsid', $id);
        $this->db->delete('view_jobs');
    }

    //删除职位简章共同索引相关信息
    public function del_article_jobs_index_by_id($id) {
        $this->db->where('type', 'jobs');
        $this->db->where_in('p_id', $id);
        $this->db->delete('jiaoshi_article_jobs_index');
    }

    //添加职位简章共同索引相关信息
    public function add_article_jobs_index_by_id($job) {
        $in['parent_id'] = 'company_id';
        $in['p_id'] = $job['id'];
        $in['type'] = 'jobs';
        $in['topclass'] = $job['topclass'];
        $in['category'] = $job['category'];
        $in['subclass'] = $job['subclass'];
        $in['district'] = $job['district'];
        $in['sdistrict'] = $job['sdistrict'];
        $in['addtime'] = $job['addtime'];
        $in['refreshtime'] = $job['refreshtime'];
        $this->db->insert('jiaoshi_article_jobs_index', $in);
    }

    //根据职位详细添加职位搜索索引
    public function add_job_search_by_job($job) {
        $searchtab['id'] = $job['id'];
        $searchtab['uid'] = $job['uid'];
        $searchtab['subsite_id'] = $job['subsite_id'];
        $searchtab['recommend'] = $job['recommend'];
        $searchtab['emergency'] = $job['emergency'];
        $searchtab['nature'] = $job['nature'];
        $searchtab['sex'] = $job['sex'];
        $searchtab['topclass'] = $job['topclass'];
        $searchtab['category'] = $job['category'];
        $searchtab['subclass'] = $job['subclass'];
        $searchtab['trade'] = $job['trade'];
        $searchtab['district'] = $job['district'];
        $searchtab['sdistrict'] = $job['sdistrict'];
        $searchtab['street'] = $job['street'];
        $searchtab['education'] = $job['education'];
        $searchtab['experience'] = $job['experience'];
        $searchtab['wage'] = $job['wage'];
        $searchtab['refreshtime'] = $job['refreshtime'];
        $searchtab['scale'] = $job['scale'];
        //--
        $this->db->insert('jobs_search_wage', $searchtab);
        $this->db->insert('jobs_search_scale', $searchtab);
        //--
        $searchtab['map_x'] = $job['map_x'];
        $searchtab['map_y'] = $job['map_y'];
        $this->db->insert('jobs_search_rtime', $searchtab);
        unset($searchtab['map_x'], $searchtab['map_y']);
        //--
        $searchtab['stick'] = $job['stick'];
        $this->db->insert('jobs_search_stickrtime', $searchtab);
        unset($searchtab['stick']);
        //--
        $searchtab['click'] = $job['click'];
        $this->db->insert('jobs_search_hot', $searchtab);
        unset($searchtab['click']);
        //--
        $searchtab['key'] = $job['key'];
        $searchtab['map_x'] = $job['map_x'];
        $searchtab['map_y'] = $job['map_y'];
        $searchtab['likekey'] = $job['jobs_name'] . ',' . $job['companyname'];
        $this->db->insert('jobs_search_key', $searchtab);
        unset($searchtab);
        $tag = explode('|', $job['tag']);
        $tagindex = 1;
        $tagsql['tag1'] = $tagsql['tag2'] = $tagsql['tag3'] = $tagsql['tag4'] = $tagsql['tag5'] = 0;
        if (!empty($tag) && is_array($tag)) {
            foreach ($tag as $v) {
                $vid = explode(',', $v);
                $tagsql['tag' . $tagindex] = intval($vid[0]);
                $tagindex++;
            }
        }
        $tagsql['id'] = $job['id'];
        $tagsql['uid'] = $job['uid'];
        $tagsql['subsite_id'] = $job['subsite_id'];
        $tagsql['topclass'] = $job['topclass'];
        $tagsql['category'] = $job['category'];
        $tagsql['subclass'] = $job['subclass'];
        $tagsql['district'] = $job['district'];
        $tagsql['sdistrict'] = $job['sdistrict'];
        $this->db->insert('jobs_search_tag', $tagsql);
    }

    //获取搜索结果职位ID集
    public function get_jobs_id_arr($function, $where = "", $key = "", $limit = 0, $offset = 0, $order_by = 'refreshtime DESC', $recommend = 0, $emergency = 0) {
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($key)) {
            $this->db->like('likekey', $key);
        }
        if (!empty($order_by)) {
            $this->db->order_by($order_by);
        }
        if ($emergency > 0) {
            $this->db->order_by('emergency', 'DESC');
        }
        if ($recommend > 0) {
            $this->db->order_by('recommend', 'DESC');
        }
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get($function);
        $result = $query->result_array();
        return $result;
    }

    //职位总数
    public function get_jobs_total($where = "") {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->from('jobs');
        return $this->db->count_all_results();
    }

    //按关键字搜索总数
    public function get_jobs_search_key_total($where = "", $key) {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->like('jobs_search_key.likekey', $key);
        $this->db->from('jobs_search_key');
        return $this->db->count_all_results();
    }

    //按刷新时间搜索总数
    public function get_jobs_search_stickrtime_total($where = "") {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->from('jobs_search_stickrtime');
        return $this->db->count_all_results();
    }

    //获取职位列表
    public function get_job_list($where = "", $tmp = 0) {
        $result = "";
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get('jobs');
        $useful_jobs = $query->result_array();
        $this->db->where($where);
        $query = $this->db->get('jobs_tmp');
        $tmp_jobs = $query->result_array();
        if (!empty($useful_jobs) || !empty($tmp_jobs)) {
            $result = $tmp == 0 ? $useful_jobs : array_merge($useful_jobs, $tmp_jobs);
        }
        return $result;
    }

    //根据复数地区和职位类别获取职位列表
    public function get_job_list_in_district_category($where = "", $limit = 10, $order_by = 'refreshtime DESC', $overtime = 0) {
        if (!empty($where)) {
            foreach ($where as $k => $v) {
                $this->db->where_in($k, $v);
            }
        }
        if (!($overtime > 0)) {
            $this->db->where("deadline >", time());
        }
        $this->db->order_by($order_by);
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        $query = $this->db->get('jobs');
        $result = $query->result_array();
        return $result;
    }

    //根据ID获取职位
    public function get_all_job_by_id($id, $tmp = 1) {
        $return = "";
        $this->db->where('id', $id);
        $query = $this->db->get('jobs');
        $jobs = $query->row_array();
        $this->db->where('id', $id);
        $query2 = $this->db->get('jobs_tmp');
        $jobs_tmp = $query2->row_array();
        if (!empty($jobs)) {
            $jobs['tmp'] = 0;
            $return = $jobs;
        } elseif (!empty($jobs_tmp) && $tmp != 0) {
            $jobs_tmp['tmp'] = 1;
            $return = $jobs_tmp;
        }
        return $return;
    }

    //根据ID和用户ID获取有效职位
    public function get_job_by_id_uid($id, $uid) {
        $this->db->where('uid', $uid);
        $this->db->where('id', $id);
        $query = $this->db->get('jobs');
        return $query->row_array();
    }

    //根据ID和用户ID获取无效职位
    public function get_job_tmp_by_id_uid($id, $uid) {
        $this->db->where('uid', $uid);
        $this->db->where('id', $id);
        $query = $this->db->get('jobs_tmp');
        return $query->row_array();
    }

    //点击量+1
    public function add_mobile_click($job_id) {
        $this->db->select('click,mobile_click');
        $this->db->where('id', $job_id);
        $query = $this->db->get('jobs');
        if ($query->num_rows() == 0) {
            $this->db->select('click,mobile_click');
            $this->db->where('id', $job_id);
            $query = $this->db->get('jobs_tmp');
        }
        $job = $query->row_array();
        $data = array('mobile_click' => ($job['mobile_click'] + 1), 'click' => ($job['click'] + 1));
        $this->db->where('id', $job_id);
        $this->db->update('jobs', $data);
    }

    //添加用户查看职位的记录
    public function add_view_job($uid, $job_id) {
        $this->db->where('uid', $uid);
        $this->db->where('jobsid', $job_id);
        $query = $this->db->get('view_jobs');
        $result = $query->row_array();
        if (!empty($result)) {
            $this->db->where('id', $result['id']);
            $data = array('addtime' => time());
            $this->db->update('view_jobs', $data);
            $data = array('interview_addtime' => time(), 'personal_look' => 2);
            $this->db->where('resume_uid', $uid);
            $this->db->where('jobs_id', $job_id);
            $this->db->update('company_interview', $data);
        } else {
            $data = array('uid' => $uid, 'jobsid' => $job_id, 'addtime' => time());
            $this->db->insert('view_jobs', $data);
        }
    }

    //获取职位联系方式
    public function get_job_contact_by_job_id($job_id) {
        $this->db->where('pid', $job_id);
        $query = $this->db->get('jobs_contact');
        $result = $query->row_array();
        return $result;
    }

    function get_jobs_in($job_ids) {
        $this->db->where_in('id', $job_ids);
        $query = $this->db->get('jobs');
        return $query->result_array();
    }

    //根据ID和用户ID删除无效职位
    public function del_job_tmp_by_id_uid($id, $uid) {
        $this->db->where('uid', $uid);
        $this->db->where('id', $id);
        $this->db->delete('jobs_tmp');
    }

    //根据ID和用户ID删除有效职位
    public function del_job_by_id_uid($id, $uid) {
        $this->db->where('uid', $uid);
        $this->db->where('id', $id);
        $this->db->delete('jobs');
    }

    //根据用户ID和添加方式更新有效职位
    public function update_job_add_mode_by_uid_add_mode($uid, $add_mode, $setmeal_jobs) {
        $this->db->where('uid', $uid);
        $this->db->where('add_mode', $add_mode);
        $this->db->update('jobs', $setmeal_jobs);
    }

    //根据用户ID和添加方式更新无效职位
    public function update_job_tmp_add_mode_by_uid_add_mode($uid, $add_mode, $setmeal_jobs) {
        $this->db->where('uid', $uid);
        $this->db->where('add_mode', $add_mode);
        $this->db->update('jobs_tmp', $setmeal_jobs);
    }

    //根据用户ID和添加方式更新猎头职位
    public function update_hunter_jobs_add_mode_by_uid_add_mode($uid, $add_mode, $setmeal_jobs) {
        $this->db->where('uid', $uid);
        $this->db->where('add_mode', $add_mode);
        $this->db->update('hunter_jobs', $setmeal_jobs);
    }

    //设置职位暂停状态
    public function set_jobs_display_by_id($uid, $id_arr, $display = 1) {
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id_arr);
        $this->db->update('jobs', array('display' => $display));
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id_arr);
        $this->db->update('jobs_tmp', array('display' => $display));
    }

    function refresh_jobs($id, $uid) {
        $time = time();
        $jobs_arr = $this->get_jobs_in($id);
        foreach ($jobs_arr as $ja) {
            $r_jobs_arr[] = $ja['id'];
        }
        $this->db->where('uid', $uid);
        $this->db->update('company_profile', array('refreshtime' => $time));
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id);
        $this->db->update('jobs', array('refreshtime' => $time));
        $this->db->where('type', 'jobs');
        $this->db->where_in('p_id', $r_jobs_arr);
        $this->db->update('jiaoshi_article_jobs_index', array('refreshtime' => $time));
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id);
        $this->db->update('jobs_tmp', array('refreshtime' => $time));
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id);
        $this->db->update('jobs_search_hot', array('refreshtime' => $time));
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id);
        $this->db->update('jobs_search_key', array('refreshtime' => $time));
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id);
        $this->db->update('jobs_search_rtime', array('refreshtime' => $time));
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id);
        $this->db->update('jobs_search_scale', array('refreshtime' => $time));
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id);
        $this->db->update('jobs_search_stickrtime', array('refreshtime' => $time));
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id);
        $this->db->update('jobs_search_wage', array('refreshtime' => $time));
    }

    function delete_jobs($id, $uid) {
        $time = time();
        $jobs_arr = $this->get_jobs_in($id);
        foreach ($jobs_arr as $ja) {
            $r_jobs_arr[] = $ja['id'];
        }
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id);
        $this->db->delete('jobs');
        $this->db->where('type', 'jobs');
        $this->db->where_in('p_id', $r_jobs_arr);
        $this->db->delete('jiaoshi_article_jobs_index');
        $this->db->where_in('pid', $id);
        $this->db->delete('jobs_contact');
        $this->db->where_in('cp_jobid', $id);
        $this->db->delete('promotion');
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id);
        $this->db->delete('jobs_tmp');
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id);
        $this->db->delete('jobs_search_hot');
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id);
        $this->db->delete('jobs_search_key');
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id);
        $this->db->delete('jobs_search_rtime');
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id);
        $this->db->delete('jobs_search_scale');
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id);
        $this->db->delete('jobs_search_stickrtime');
        $this->db->where('uid', $uid);
        $this->db->where_in('id', $id);
        $this->db->delete('jobs_search_wage');
        $this->db->where_in('jobsid', $id);
        $this->db->delete('view_jobs');
    }

    function get_job_audit_reason($id) {
        $this->db->where('jobs_id', $id);
        $this->db->order_by('id DESC');
        $query = $this->db->get('audit_reason');
        $result = $query->row_array();
        return $result;
    }

}
