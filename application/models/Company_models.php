<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Company_models extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('common_models');
        $this->load->model('job_models');
        $this->load->library('session');
    }

    public function add_company($data) {
        $this->db->insert('company_profile', $data);
        $add_id = $this->db->insert_id();
        return $add_id;
    }

    public function update_company_by_uid($uid, $data) {
        $this->db->where('uid', $uid);
        $this->db->update('company_profile', $data);
    }

    public function get_company($company_id) {
        $this->db->where('id', $company_id);
        $query = $this->db->get('company_profile');
        return $query->row_array();
    }

    public function get_company_by_uid($uid) {
        $this->db->where('uid', $uid);
        $query = $this->db->get('company_profile');
        return $query->row_array();
    }

    public function get_company_imgs_by_cid($cid, $audit = 0) {
        if ($audit > 0) {
            $this->db->where('audit', $audit);
        }
        $this->db->where('company_id', $cid);
        $query = $this->db->get('company_img');
        return $query->result_array();
    }

    //插入企业积分
    public function insert_members_points($uid) {
        $data['uid'] = $uid;
        $this->db->insert('members_points', $data);
        return $this->db->insert_id();
    }

    //插入企业套餐
    public function insert_members_setmeal($uid) {
        $data['uid'] = $uid;
        $this->db->insert('members_setmeal', $data);
        return $this->db->insert_id();
    }

    //根据用户ID更新企业套餐
    public function update_members_setmeal_by_uid($uid, $data) {
        $this->db->where('uid', $uid);
        $this->db->update('members_setmeal', $data);
    }

    //根据用户ID设置企业套餐
    public function set_members_setmeal_by_uid($uid, $data = array()) {
        foreach ($data as $k => $v) {
            $this->db->set($k, $v, FALSE);
        }
        $this->db->where('uid', $uid);
        $this->db->update('members_setmeal');
    }

    //根据用户ID获取企业套餐
    public function get_members_setmeal_by_uid($uid) {
        $this->db->where('uid', $uid);
        $query = $this->db->get('members_setmeal');
        return $query->row_array();
    }

    //获取企业注册规则
    public function get_points_rule() {
        $result = array();
        $this->db->where('utype', 1);
        $query = $this->db->get('members_points_rule');
        $arr = $query->result_array();
        foreach ($arr as $a) {
            $result[$a['name']]['title'] = $a['title'];
            $result[$a['name']]['type'] = $a['operation'];
            $result[$a['name']]['value'] = $a['value'];
        }
        return $result;
    }

    //更新企业积分
    public function update_members_points($uid, $data) {
        $this->db->where('uid', $uid);
        return $this->db->update('members_points', $data);
    }

    public function add_members_points_limit($data) {
        $this->db->insert('members_points_limit', $data);
    }

    public function update_members_points_limit($uid, $data) {
        $this->db->where('uid', $uid);
        return $this->db->update('members_points_limit', $data);
    }

    public function get_user_points($uid) {
        $this->db->where('uid', $uid);
        $query = $this->db->get('members_points');
        $points = $query->row_array();
        return $points['points'];
    }

    public function get_user_limit_points($uid) {
        $this->db->where('uid', $uid);
        $query = $this->db->get('members_points_limit');
        $points = $query->row_array();
        if (!empty($points['id'])) {
            $up_limit['points'] = $points['endtime'] > time() ? $points['points'] : 0;
            $this->db->where('uid', $uid);
            $this->db->update('members_points_limit', $up_limit);
            $points['points'] = $up_limit['points'];
        } else {
            $points = array();
        }
        return $points;
    }

    public function distribution_jobs_uid($uid) {
        $uid = intval($uid);
        $this->db->select('id');
        $this->db->where('uid', $uid);
        $query = $this->db->get('jobs');
        $jobs_arr = $query->result_array();
        $this->db->select('id');
        $this->db->where('uid', $uid);
        $query = $this->db->get('jobs_tmp');
        $jobs_tmp_arr = $query->result_array();
        if (!empty($jobs_tmp_arr)) {
            $list = array_merge($jobs_arr, $jobs_tmp_arr);
        } else {
            $list = !empty($jobs_arr) ? $jobs_arr : $jobs_tmp_arr;
        }
        foreach ($list as $l) {
            $id[] = $l['id'];
        }
        if (!empty($id)) {
            $this->distribution_jobs($id, $uid);
        }
    }

    public function distribution_jobs($id, $uid) {
        $time = time();
        $CI = & get_instance();
        if (!is_array($id)) {
            $id = array($id);
        }
        foreach ($id as $v) {
            $t1 = $CI->job_models->get_job_by_id_uid($v, $uid);
            $t2 = $CI->job_models->get_job_tmp_by_id_uid($v, $uid);
            if ((empty($t1) && empty($t2)) || (!empty($t1) && !empty($t2))) {
                continue;
            } else {
                $outdated_jobs = $CI->common_models->get_sys_config('outdated_jobs');
                $j = !empty($t1) ? $t1 : $t2;
                if (!empty($t1) && $j['audit'] == "1" && $j['display'] == "1" && $j['user_status'] == "1") {
                    if ($outdated_jobs == "1") {
                        if ($j['deadline'] > $time && ($j['setmeal_deadline'] == "0" || $j['setmeal_deadline'] > $time)) {
                            continue;
                        }
                    } else {
                        continue;
                    }
                } elseif (!empty($t2)) {
                    if ($j['audit'] != "1" || $j['display'] != "1" || $j['user_status'] != "1") {
                        continue;
                    } else {
                        if ($outdated_jobs == "1" && ($j['deadline'] < $time || ($j['setmeal_deadline'] < $time && $j['setmeal_deadline'] != "0"))) {
                            continue;
                        }
                    }
                }
                $j = array_map('addslashes', $j);
                if (!empty($t1)) {
                    $del_id_arr = $CI->job_models->get_job_by_id_uid($v, $uid);
                    $CI->job_models->del_job_tmp_by_id_uid($v, $uid);
                    $CI->job_models->del_job_by_id_uid($v, $uid);
                    if (!empty($del_id_arr['id'])) {
                        $CI->job_models->del_article_jobs_index_by_id($v);
                    }
                    if ($this->db->insert('jobs_tmp', $j)) {
                        $CI->job_models->del_job_other_by_id_uid($v, $uid);
                    } else {
                        $del_id_arr = $CI->job_models->get_job_tmp_by_id_uid($v, $uid);
                        $CI->job_models->del_job_tmp_by_id_uid($v, $uid);
                        $CI->job_models->del_job_by_id_uid($v, $uid);
                        if (!empty($del_id_arr['id'])) {
                            $CI->job_models->del_article_jobs_index_by_id($v);
                        }
                        if ($this->db->insert('jobs', $j)) {
                            $CI->job_models->add_job_search_by_job($j);
                        }
                    }
                } else {
                    $del_id_arr = $CI->job_models->get_job_tmp_by_id_uid($v, $uid);
                    $CI->job_models->del_job_tmp_by_id_uid($v, $uid);
                    $CI->job_models->del_job_by_id_uid($v, $uid);
                    if (!empty($del_id_arr['id'])) {
                        $CI->job_models->add_article_jobs_index_by_id($del_id_arr);
                    }
                    if ($this->db->insert('jobs', $j)) {
                        $CI->job_models->add_job_search_by_job($del_id_arr);
                    } else {
                        if (!empty($del_id_arr['id'])) {
                            $CI->job_models->del_article_jobs_index_by_id($del_id_arr);
                        }
                        if ($this->db->insert('jobs_tmp', $j)) {
                            $CI->job_models->del_job_search_by_id($v, $uid);
                        }
                    }
                }
            }
        }
    }

    //获取一条企业套餐
    public function get_setmeal_one($id) {
        $id = intval($id);
        $this->db->where('id', $id);
        $query = $this->db->get('setmeal');
        return $query->row_array();
    }

    //写入企业套餐日志
    public function write_setmeallog($uid, $username, $value, $type, $amount = '0.00', $is_money = '1', $log_mode = '1', $log_utype = '1') {
        $in['log_uid'] = $uid;
        $in['log_username'] = $username;
        $in['log_type'] = $type;
        $in['log_addtime'] = time();
        $in['log_value'] = $value;
        $in['log_amount'] = $amount;
        $in['log_ismoney'] = $is_money;
        $in['log_mode'] = $log_mode;
        $in['log_utype'] = $log_utype;
        $this->db->insert('members_charge_log', $in);
    }

    //按用户ID和职位ID获取职位申请列表
    public function get_resume_apply_by_uid_jid($uid, $job_id = 0, $limit = 0, $offset = 0) {
        if ($job_id > 0) {
            $this->db->where('jobs_id', $job_id);
        }
        $this->db->where('company_uid', $uid);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('personal_look', 'ASC');
        $this->db->order_by('apply_addtime', 'DESC');
        $query = $this->db->get('personal_jobs_apply');
        return $query->result_array();
    }

    //按用户ID和职位ID获取职位申请总数
    public function get_resume_apply_total_by_uid_jid($uid, $job_id = 0) {
        if ($job_id > 0) {
            $this->db->where('jobs_id', $job_id);
        }
        $this->db->where('company_uid', $uid);
        $this->db->from('personal_jobs_apply');
        $result = $this->db->count_all_results();
        return $result;
    }

    //按用户ID和简历ID获取职位申请
    public function get_resume_apply_by_uid_rid($uid, $resume_id = 0) {
        $this->db->where('resume_id', $resume_id);
        $this->db->where('company_uid', $uid);
        $query = $this->db->get('personal_jobs_apply');
        return $query->row_array();
    }

    //设置职位申请查看状态
    public function set_apply_look($did_arr, $look = 2) {
        $this->db->where_in('did', $did_arr);
        return $this->db->update('personal_jobs_apply', array('personal_look' => $look));
    }

    //删除职位申请
    public function del_apply($did_arr, $uid) {
        $this->db->where('company_uid', $uid);
        $this->db->where_in('did', $did_arr);
        $this->db->delete('personal_jobs_apply');
    }

    //按用户ID获取简历下载总数
    public function get_resume_down_total_by_uid($uid) {
        $this->db->where('company_uid', $uid);
        $this->db->from('company_down_resume');
        $result = $this->db->count_all_results();
        return $result;
    }

    //按用户ID获取简历下载列表
    public function get_resume_down_by_uid($uid, $limit = 0, $offset = 0) {
        $this->db->where('company_uid', $uid);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('down_addtime', 'DESC');
        $query = $this->db->get('company_down_resume');
        return $query->result_array();
    }

    //按ID获取下载简历信息
    public function get_resume_down_by_id($did) {
        $this->db->where('did', $did);
        $query = $this->db->get('company_down_resume');
        return $query->row_array();
    }

    //按用户ID和简历ID获取下载简历信息
    public function get_resume_down_by_uid_rid($uid, $rid) {
        $this->db->where('company_uid', $uid);
        $this->db->where('resume_id', $rid);
        $query = $this->db->get('company_down_resume');
        return $query->row_array();
    }

    //根据用户ID获取企业收藏简历总数
    public function get_resume_collect_total_by_uid($uid) {
        $this->db->where('company_uid', $uid);
        $this->db->from('company_favorites');
        $result = $this->db->count_all_results();
        return $result;
    }

    //按用户ID获取简历收藏列表
    public function get_resume_collect_by_uid($uid, $limit = 0, $offset = 0) {
        $this->db->where('company_uid', $uid);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('favoritesa_ddtime', 'DESC');
        $query = $this->db->get('company_favorites');
        return $query->result_array();
    }

    //按用户ID和简历ID获取收藏简历
    public function get_resume_collect_by_uid_rid($uid, $resume_id) {
        $this->db->where('company_uid', $uid);
        $this->db->where('resume_id', $resume_id);
        $query = $this->db->get('company_favorites');
        return $query->row_array();
    }

    //添加企业收藏简历
    public function add_resume_collect($in) {
        $this->db->insert('company_favorites', $in);
        return $this->db->insert_id();
    }

    //添加企业下载简历
    public function add_resume_down($in) {
        $this->db->insert('company_down_resume', $in);
        return $this->db->insert_id();
    }

    //删除下载简历
    public function del_down($did_arr, $uid) {
        $this->db->where('company_uid', $uid);
        $this->db->where_in('did', $did_arr);
        $this->db->delete('company_down_resume');
    }

    //删除收藏简历
    public function del_collect($did_arr, $uid) {
        $this->db->where('company_uid', $uid);
        $this->db->where_in('did', $did_arr);
        $this->db->delete('company_favorites');
    }

    //根据用户ID获取企业面试邀请总数
    public function get_resume_interview_total_by_uid($uid, $job_id = 0) {
        if ($job_id > 0) {
            $this->db->where('jobs_id', $job_id);
        }
        $this->db->where('company_uid', $uid);
        $this->db->from('company_interview');
        $result = $this->db->count_all_results();
        return $result;
    }

    //按用户ID获取面试邀请列表
    public function get_resume_interview_by_uid($uid, $job_id = 0, $limit = 0, $offset = 0) {
        if ($job_id > 0) {
            $this->db->where('jobs_id', $job_id);
        }
        $this->db->where('company_uid', $uid);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('interview_addtime', 'DESC');
        $query = $this->db->get('company_interview');
        return $query->result_array();
    }

    //按用户ID,职位ID,简历ID获取面试邀请信息
    public function get_resume_interview_by_uid_jid_rid($uid, $job_id = 0, $resume_id = 0) {
        if ($job_id > 0) {
            $this->db->where('jobs_id', $job_id);
        }
        if ($rid > 0) {
            $this->db->where('resume_id', $resume_id);
        }
        $this->db->where('company_uid', $uid);
        $query = $this->db->get('company_interview');
        return $query->row_array();
    }

    //删除面试邀请
    public function del_invitation($did_arr, $uid) {
        $this->db->where('company_uid', $uid);
        $this->db->where_in('did', $did_arr);
        $this->db->delete('company_interview');
    }

    //添加企业面试邀请
    public function add_invitation($in) {
        $this->db->insert('company_interview', $in);
        return $this->db->insert_id();
    }

    //根据用户ID获取企业有效职位总数
    public function get_jobs_release_total_by_uid($uid) {
        $this->db->where('uid', $uid);
        $this->db->from('jobs');
        $result = $this->db->count_all_results();
        return $result;
    }

    //根据用户ID获取企业待审核职位总数
    public function get_jobs_audit_total_by_uid($uid) {
        $this->db->where('uid', $uid);
        $this->db->where('audit', 2);
        $this->db->from('jobs_tmp');
        $result = $this->db->count_all_results();
        return $result;
    }

    //根据用户ID获取企业已暂停职位总数
    public function get_jobs_stop_total_by_uid($uid) {
        $this->db->where('uid', $uid);
        $this->db->where('display', '2');
        $this->db->from('jobs_tmp');
        $result = $this->db->count_all_results();
        return $result;
    }

    //根据用户ID获取企业不通过职位总数
    public function get_jobs_nopass_total_by_uid($uid) {
        $this->db->where('uid', $uid);
        $this->db->where('audit', 3);
        $this->db->from('jobs_tmp');
        $result = $this->db->count_all_results();
        return $result;
    }

    //按用户ID获取有效职位列表
    public function get_jobs_release_by_uid($uid, $limit = 0, $offset = 0) {
        $this->db->where('uid', $uid);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('refreshtime', 'DESC');
        $query = $this->db->get('jobs');
        return $query->result_array();
    }

    //按用户ID获取待审核职位列表
    public function get_jobs_audit_by_uid($uid, $limit = 0, $offset = 0) {
        $this->db->where('uid', $uid);
        $this->db->where('audit', 2);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('refreshtime', 'DESC');
        $query = $this->db->get('jobs_tmp');
        return $query->result_array();
    }

    //按用户ID获取已暂停职位列表
    public function get_jobs_stop_by_uid($uid, $limit = 0, $offset = 0) {
        $this->db->where('uid', $uid);
        $this->db->where('display', '2');
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('refreshtime', 'DESC');
        $query = $this->db->get('jobs_tmp');
        return $query->result_array();
    }

    //按用户ID获取不通过职位列表
    public function get_jobs_nopass_by_uid($uid, $limit = 0, $offset = 0) {
        $this->db->where('uid', $uid);
        $this->db->where('audit', 3);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('refreshtime', 'DESC');
        $query = $this->db->get('jobs_tmp');
        return $query->result_array();
    }

    //获取职位申请简历总数
    public function get_apply_jobs_total_by_cid_jid($cid, $jid) {
        $this->db->where('company_uid', $cid);
        $this->db->where('jobs_id', $jid);
        $this->db->from('personal_jobs_apply');
        $result = $this->db->count_all_results();
        return $result;
    }

    //获取企业当天刷新职位次数
    public function get_today_refresh_times($uid, $type) {
        $today = strtotime(date('Y-m-d'));
        $tomorrow = $today + 3600 * 24;
        $this->db->where('uid', $uid);
        $this->db->where('type', $type);
        $this->db->where('addtime >', $today);
        $this->db->where('addtime <', $tomorrow);
        $this->db->from('refresh_log');
        $result = $this->db->count_all_results();
        return $result;
    }

    //写入企业刷新职位日志
    public function write_refresh_log($uid, $type) {
        $in['uid'] = $uid;
        $in['type'] = $type;
        $in['addtime'] = time();
        $this->db->insert('refresh_log', $in);
    }

    //按用户ID获取简历查看记录
    public function get_view_log($uid, $resume_id) {
        $this->db->where('uid', $uid);
        $this->db->where('resumeid', $resume_id);
        $query = $this->db->get('view_resume');
        return $query->row_array();
    }

    //添加企业简历查看记录
    public function add_view_log($uid, $resume_id) {
        $setsqlarr['uid'] = $uid;
        $setsqlarr['resumeid'] = $resume_id;
        $setsqlarr['addtime'] = time();
        $this->db->insert('view_resume', $setsqlarr);
        $wheresqlarr = array('company_uid' => $uid, 'resume_id' => $resume_id);
        $this->db->where($wheresqlarr);
        $this->db->update('personal_jobs_apply', array('personal_look' => 2));
    }

}
