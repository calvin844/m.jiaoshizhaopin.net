<?php

class User_models extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    //����ID��ȡ�û���Ϣ
    public function get_user_info_by_id($id) {
        $this->db->where('uid', $id);
        $query = $this->db->get('members');
        return $query->row_array();
    }

    //����΢��openID��ȡ�û���Ϣ
    public function get_user_info_by_wxopenid($wxopenid) {
        $this->db->where('wx_openid', $wxopenid);
        $query = $this->db->get('members');
        return $query->row_array();
    }

    //����΢��unionID��ȡ�û���Ϣ
    public function get_user_info_by_wxunionid($unionid) {
        $this->db->where('wx_unionid', $unionid);
        $query = $this->db->get('members');
        return $query->row_array();
    }

    //�����ֻ���ȡ�û���Ϣ
    public function get_user_info_by_mobile($mobile) {
        $this->db->where('mobile', $mobile);
        $query = $this->db->get('members');
        return $query->row_array();
    }

    //�����û�����ȡ�û���Ϣ
    public function get_user_info_by_username($username) {
        $this->db->where('username', $username);
        $query = $this->db->get('members');
        return $query->row_array();
    }

    //����email��ȡ�û���Ϣ
    public function get_user_info_by_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('members');
        return $query->row_array();
    }

    //�����û�
    public function insert_members($data) {
        $data['platform'] = 'mobile';
        $this->db->insert('members', $data);
        return $this->db->insert_id();
    }

    //����ID�����û���Ϣ
    public function up_user_info_by_id($id, $data) {
        $this->db->where('uid', $id);
        $this->db->update('members', $data);
    }

    //�����û�����
    public function up_user_password($id, $password) {
        $data['password'] = $password;
        $this->db->where('uid', $id);
        $this->db->update('members', $data);
    }

    //�������
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

    //���û�΢��openid
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

    //��ȡ�û�ϵͳ��Ϣ����
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

    //��ȡ�û�ϵͳ��Ϣ�б�
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

    //����ϵͳ��Ϣ�鿴״̬
    public function set_message_look($did_arr, $look = 2) {
        $this->db->where_in('pmid', $did_arr);
        return $this->db->update('pms', array('new' => $look));
    }

    //ɾ��ϵͳ��Ϣ
    public function message_del($did_arr, $uid) {
        $where = "(msgfromuid='" . $uid . "' OR msgtouid='" . $uid . "')";
        $this->db->where_in('pmid', $did_arr);
        $this->db->delete('pms');
    }

    //������Ƭ�����ż�¼
    public function up_yunpian_sms_back($mobile) {
        $data['back'] = 1;
        $this->db->where('phone', $mobile);
        $this->db->where('sms_type', 1);
        $this->db->order_by('addtime DESC');
        $this->db->limit(1);
        $this->db->update('yunpian_sms_log', $data);
    }

}
