<?php

class Common_models extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->library('phpmailer');
        $this->load->library('aliyun_sms');
        $this->mem = new Memcache;
        $this->mem->connect("localhost", 11111);
    }

//获取系统配置
    function get_sys_config($name) {
        $this->db->where('name', $name);
        $query = $this->db->get('config');
        $return = $query->row_array();
        return $return['value'];
    }

//获取email配置
    function get_email_config() {
        $query = $this->db->get('mailconfig');
        $return_tmp = $query->result_array();
        foreach ($return_tmp as $r) {
            $return[$r['name']] = $r['value'];
        }
        return $return;
    }

//获取sms配置
    function get_sms_config($name) {
        $query = $this->db->get('sms_config');
        $return = $query->result_array();
        return $return;
    }

//写入日志
    function write_memberslog($uid, $utype, $type, $username, $str, $mode = 0, $op_type = 0, $op_type_cn = "", $op_used = "", $op_leave = "") {
        $online_ip = get_ip();
        $ip_address = convertip($online_ip);
        $data['log_uid'] = $uid;
        $data['log_username'] = $username;
        $data['log_utype'] = $utype;
        $data['log_type'] = $type;
        $data['log_addtime'] = time();
        $data['log_ip'] = $online_ip;
        $data['log_address'] = $ip_address;
        $data['log_value'] = $str;
        $data['log_mode'] = $mode;
        $data['log_op_type'] = $op_type;
        $data['log_op_type_cn'] = $op_type_cn;
        $data['log_op_used'] = $op_used;
        $data['log_op_leave'] = $op_leave;
        $this->db->insert('members_log', $data);
    }

//写入系统日志
    function write_syslog($type, $type_name, $str) {
        $online_ip = get_ip();
        $ip_address = convertip($online_ip);
        $l_page = addslashes($this->request_url());
        $str = addslashes($str);
        $data['l_type'] = $type;
        $data['l_type_name'] = $type_name;
        $data['l_time'] = time();
        $data['l_ip'] = $online_ip;
        $data['l_address'] = $ip_address;
        $data['l_page'] = $l_page;
        $data['l_str'] = $str;
        $this->db->insert('syslog', $data);
    }

//写入站内信
    function write_pmsnotice($touid, $toname, $message) {
        $data['message'] = trim($message);
        $data['msgtype'] = 1;
        $data['msgtouid'] = intval($touid);
        $data['msgtoname'] = trim($toname);
        $data['dateline'] = time();
        $data['replytime'] = time();
        $data['new'] = 1;
        $this->db->insert('pms', $data);
    }

//写入云片网短信发送记录表
    function write_yunpian_sms_log($data) {
        $this->db->insert('yunpian_sms_log', $data);
    }

    function request_url() {
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = $_SERVER['REQUEST_URI'];
        } else {
            if (isset($_SERVER['argv'])) {
                $url = $_SERVER['PHP_SELF'] . '?' . $_SERVER['argv'][0];
            } else {
                $url = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
            }
        }
        return urlencode($url);
    }

//获取单页面数据
    function get_explain($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('explain');
        return $query->row_array();
    }

//检查登录状态
    public function check_login($redirect_url = "") {
        $uid = $this->session->userdata('uid');
        $logout = $this->session->userdata('logout');
        $logout = $logout == 1 ? 1 : 0;
        $redirect_url = !empty($redirect_url) ? $redirect_url : $_SERVER['REQUEST_URI'];
        $this->session->set_userdata('redirect_url', $redirect_url);
        if (!(intval($uid) > 0) && is_wechat() && $logout == 0) {
            //header('Location:/user/login');
            $this->session->set_userdata('logout', "0");
            $str = $this->wechat_login(array('come' => "check_login"));
            header('Location:' . $str);
        } elseif (!(intval($uid) > 0) && !is_wechat()) {
            $this->session->set_userdata('logout', "0");
            header('Location:/user/login');
        } elseif (!(intval($uid) > 0) && is_wechat() && $logout == 1) {
            $this->session->set_userdata('logout', "0");
            header('Location:/user/login');
        } else {
            return intval($uid);
        }
    }

    //发送邮件
    public function smtp_mail($sendto_email, $subject, $body, $From = '', $FromName = '', $attachment_arr = array()) {
        $mail = new PHPMailer();
        $mailconfig = $this->get_email_config();
        if (strstr($mailconfig['smtpservers'], '|-_-|')) {
            $mailconfig['smtpservers'] = explode('|-_-|', $mailconfig['smtpservers']);
            $mailconfig['smtpusername'] = explode('|-_-|', $mailconfig['smtpusername']);
            $mailconfig['smtppassword'] = explode('|-_-|', $mailconfig['smtppassword']);
            $mailconfig['smtpfrom'] = explode('|-_-|', $mailconfig['smtpfrom']);
            $mailconfig['smtpport'] = explode('|-_-|', $mailconfig['smtpport']);
            $mailconfig['smtpnum'] = explode('|-_-|', $mailconfig['smtpnum']);
            $mailconfig['smtpupdatetime'] = explode('|-_-|', $mailconfig['smtpupdatetime']);
            for ($i = 0; $i < count($mailconfig['smtpservers']); $i++) {
                $mailconfigarray[] = array('smtpservers' => $mailconfig['smtpservers'][$i], 'smtpusername' => $mailconfig['smtpusername'][$i], 'smtppassword' => $mailconfig['smtppassword'][$i], 'smtpfrom' => $mailconfig['smtpfrom'][$i], 'smtpport' => $mailconfig['smtpport'][$i], 'smtpnum' => $mailconfig['smtpnum'][$i], 'smtpupdatetime' => $mailconfig['smtpupdatetime'][$i]);
            }
        } else {
            $mailconfigarray[] = $mailconfig;
        }
        foreach ($mailconfigarray as $m) {
            if (($m['smtpnum'] - 1) > 0) {
                if (empty($mc)) {
                    $m['smtpnum'] = $m['smtpnum'] - 1;
                    $mc = $m;
                }
            } else {
                if (time() > strtotime("+1 days", $m['smtpupdatetime'])) {
                    $m['smtpnum'] = 1000;
                    $m['smtpupdatetime'] = strtotime(date('Y-m-d'));
                    $mc = $m;
                    $m['smtpnum'] = $m['smtpnum'] - 1;
                }
            }
            $smtp_update_arr[] = $m;
        }
        if (empty($mc)) {
            $this->write_syslog(2, 'MAIL', "没有可用的邮箱！请更新系统缓存后检查邮件配置");
        }
        $mailconfig['smtpservers'] = $mc['smtpservers'];
        $mailconfig['smtpusername'] = $mc['smtpusername'];
        $mailconfig['smtppassword'] = $mc['smtppassword'];
        $mailconfig['smtpfrom'] = $mc['smtpfrom'];
        $mailconfig['smtpport'] = $mc['smtpport'];
        $From = $From ? $From : $mailconfig['smtpfrom'];
        $FromName = $FromName ? $FromName : $this->get_sys_config('site_name');
        if ($mailconfig['method'] == "1") {
            if (empty($mailconfig['smtpservers']) || empty($mailconfig['smtpusername']) || empty($mailconfig['smtppassword']) || empty($mailconfig['smtpfrom'])) {
                $this->write_syslog(2, 'MAIL', "邮件配置信息不完整");
                return false;
            }
            $mail->IsSMTP();
            $mail->Host = $mailconfig['smtpservers'];
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->Username = $mailconfig['smtpusername'];
            $mail->Password = $mailconfig['smtppassword'];
            $mail->Port = $mailconfig['smtpport'];
            $mail->From = $mailconfig['smtpfrom'];
            $mail->FromName = $FromName;
        } elseif ($mailconfig['method'] == "2") {
            $mail->IsSendmail();
        } elseif ($mailconfig['method'] == "3") {
            $mail->IsMail();
        }
        $mail->CharSet = 'gb2312';
        $mail->Encoding = "base64";
        $mail->AddReplyTo($From, $FromName);
        $mail->AddAddress($sendto_email, "");
        //$mail->addCC($From);
        $mail->IsHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = "text/html";
        if (!empty($attachment_arr)) {
            foreach ($attachment_arr as $a) {
                $mail->AddAttachment($a['path'], $a['name']); // 添加附件,并指定名称
            }
        }
        //var_dump($mail);
        $smtp_update['smtpservers'] = "";
        $smtp_update['smtpusername'] = "";
        $smtp_update['smtppassword'] = "";
        $smtp_update['smtpfrom'] = "";
        $smtp_update['smtpport'] = "";
        $smtp_update['smtpnum'] = "";
        $smtp_update['smtpupdatetime'] = "";
        foreach ($smtp_update_arr as $su) {
            $smtp_update['smtpservers'] .= $su['smtpservers'] . "|-_-|";
            $smtp_update['smtpusername'] .= $su['smtpusername'] . "|-_-|";
            $smtp_update['smtppassword'] .= $su['smtppassword'] . "|-_-|";
            $smtp_update['smtpfrom'] .= $su['smtpfrom'] . "|-_-|";
            $smtp_update['smtpport'] .= $su['smtpport'] . "|-_-|";
            $smtp_update['smtpnum'] .= $su['smtpnum'] . "|-_-|";
            $smtp_update['smtpupdatetime'] .= $su['smtpupdatetime'] . "|-_-|";
        }
        $smtp_update['smtpservers'] = trim($smtp_update['smtpservers'], "|-_-|");
        $smtp_update['smtpusername'] = trim($smtp_update['smtpusername'], "|-_-|");
        $smtp_update['smtppassword'] = trim($smtp_update['smtppassword'], "|-_-|");
        $smtp_update['smtpfrom'] = trim($smtp_update['smtpfrom'], "|-_-|");
        $smtp_update['smtpport'] = trim($smtp_update['smtpport'], "|-_-|");
        $smtp_update['smtpnum'] = trim($smtp_update['smtpnum'], "|-_-|");
        $smtp_update['smtpupdatetime'] = trim($smtp_update['smtpupdatetime'], "|-_-|");

        foreach ($smtp_update as $k => $v) {
            $this->db->where('name', $k);
            $this->db->update('mailconfig', array('value' => $v));
        }
        if ($mail->Send()) {
            return true;
        } else {
            $this->write_syslog(2, 'MAIL', $mail->ErrorInfo);
            return false;
        }
    }

    //微信直接登录
    public function wechat_login($state = array()) {
        $wx_unionid = $this->session->userdata('wx_unionid');
        if (empty($wx_unionid)) {
            $state_str = "123";
            if (!empty($state)) {
                $state_str = "";
                foreach ($state as $k => $v) {
                    $state_str .= $k . "||" . $v . "-|-";
                }
                $state_str = trim($state_str, "-|-");
            }
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . WX_ID . "&redirect_uri=http://m.jiaoshizhaopin.net/user/auth&response_type=code&scope=snsapi_userinfo&state=" . $state_str . "#wechat_redirect";
        } else {
            $url = "/user/login_save";
        }
        return $url;
    }

//发送短信通知
    function send_sms($mobile = 0, $code = "", $t_code = "SMS_174650962") {
        $mobile = intval($mobile);
        if (preg_match("/^(1)\d{10}$/", $mobile)) {
            $response = Aliyun_sms::sendSms($mobile, $code, $t_code);
            /*
              $text = toGBK($text);
              $ch = curl_init();
              $data = array('text' => $text, 'apikey' => YUNPIAN_APIKEY, 'mobile' => $mobile);
              curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
              curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              $json_data = curl_exec($ch);
              curl_close($ch);
              $json_data1 = json_decode($json_data, TRUE);
              if ($json_data1['code'] == 22) {
              exit("您验证太频繁了，请1小时后再试");
              }
             * 
             */
        } else {
            exit("手机号格式错误");
        }
    }

}
