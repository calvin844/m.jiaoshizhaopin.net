<?php

class User_models extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    //根据ID获取用户信息
    public function get_user_info_by_id($id) {
        $this->db->where('uid', $id);
        $query = $this->db->get('members');
        return $query->row_array();
    }

    //根据微信openID获取用户信息
    public function get_user_info_by_wxopenid($wxopenid) {
        $this->db->where('wx_openid', $wxopenid);
        $query = $this->db->get('members');
        return $query->row_array();
    }

    //根据微信unionID获取用户信息
    public function get_user_info_by_wxunionid($unionid) {
        $this->db->where('wx_unionid', $unionid);
        $query = $this->db->get('members');
        return $query->row_array();
    }

    //根据手机获取用户信息
    public function get_user_info_by_mobile($mobile) {
        $this->db->where('mobile', $mobile);
        $query = $this->db->get('members');
        return $query->row_array();
    }

    //根据用户名获取用户信息
    public function get_user_info_by_username($username) {
        $this->db->where('username', $username);
        $query = $this->db->get('members');
        return $query->row_array();
    }

    //根据email获取用户信息
    public function get_user_info_by_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('members');
        return $query->row_array();
    }

    //插入用户
    public function insert_members($data) {
        $data['platform'] = 'mobile';
        $this->db->insert('members', $data);
        return $this->db->insert_id();
    }

    //根据ID更新用户信息
    public function up_user_info_by_id($id, $data) {
        $this->db->where('uid', $id);
        $this->db->update('members', $data);
    }

    //更新用户密码
    public function up_user_password($id, $password) {
        $data['password'] = $password;
        $this->db->where('uid', $id);
        $this->db->update('members', $data);
    }

    //检测密码
    public function check_password($uid, $password, $session) {
        $this->db->where('uid', $uid);
        $this->db->where('password', $password);
        $query = $this->db->get('members');
        $user = $query->row_array();
        if (!empty($user)) {
            $this->db->where('uid', $uid);
            $data = array('session' => $session, 'last_login_time' => time());
            $this->db->update('members', $data);
            return True;
        } else {
            return False;
        }
    }

    //绑定用户微信openid
    public function bind_wechat($uid, $wx_unionid = "", $wx_openid = "") {
        $num = 0;
        if (!empty($wx_unionid) || !empty($wx_openid)) {
            $data = array('wx_unionid' => $wx_unionid, 'wechat_openid' => $wx_openid);
            $this->db->where('uid', $uid);
            $this->db->update('members', $data);
            $num = $this->db->affected_rows();
        }
        return $num;
    }

    //获取用户系统消息总数
    public function get_user_message_total($uid, $new = 0) {
        $where = "msgfromuid='" . $uid . "' OR msgtouid='" . $uid . "'";
        if ($new > 0) {
            $where .= " AND new='" . $new . "'";
        }
        $this->db->where($where);
        $this->db->from('pms');
        $result = $this->db->count_all_results();
        return $result;
    }

    //获取用户系统消息列表
    public function get_user_message_by_uid($uid, $new = 0, $limit = 0, $offset = 0) {
        $where = "msgfromuid='" . $uid . "' OR msgtouid='" . $uid . "'";
        if ($new > 0) {
            $where .= " AND new='" . $new . "'";
        }
        $this->db->where($where);
        if ($limit > 0 || $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('new ASC, pmid DESC');
        $query = $this->db->get('pms');
        $result = $query->result_array();
        return $result;
    }

    //设置系统消息查看状态
    public function set_message_look($did_arr, $look = 2) {
        $this->db->where_in('pmid', $did_arr);
        return $this->db->update('pms', array('new' => $look));
    }

    //删除系统消息
    public function message_del($did_arr, $uid) {
        $where = "(msgfromuid='" . $uid . "' OR msgtouid='" . $uid . "')";
        $this->db->where_in('pmid', $did_arr);
        $this->db->delete('pms');
    }

    //更新云片网短信记录
    public function up_yunpian_sms_back($mobile) {
        $data['back'] = 1;
        $this->db->where('phone', $mobile);
        $this->db->where('sms_type', 1);
        $this->db->order_by('addtime DESC');
        $this->db->limit(1);
        $this->db->update('yunpian_sms_log', $data);
    }

}
