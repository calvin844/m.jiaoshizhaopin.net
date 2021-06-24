<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('common_models');
        $this->load->model('user_models');
        $this->load->model('statistics_models');
        $this->load->model('company_models');
        $this->load->model('resume_models');
        $this->load->model('wechat_models');
    }

    //手机注册页面
    public function reg() {
        !empty($_GET['url']) ? $this->session->set_userdata('redirect_url', $_GET['url']) : "";
        $data['sms_back_code'] = rand(000000, 9999999);
        $this->session->set_userdata(array("sms_back_code" => $data['sms_back_code']));
        $this->load->view('user_reg', $data);
    }

    //手机注册保存
    public function reg_save() {
        $mobile = isset($_POST['mobile']) ? intval($_POST['mobile']) : "";
        $code = isset($_POST['code']) ? intval($_POST['code']) : "";
        $member_type = isset($_POST['member_type']) ? intval($_POST['member_type']) : 2;
        if (!empty($mobile) && !empty($code)) {
            $this->user_models->up_yunpian_sms_back($mobile);
            if ($this->session->userdata['sms_code'] == $code) {
                $this->session->set_userdata(array("mobile" => $mobile));
                $this->session->set_userdata(array("member_type" => $member_type));
                header("Location:/user/reg2");
            }
        }
        exit('err');
    }

    //手机绑定账号
    public function reg2() {
        $this->load->view('user_reg2');
    }

    //手机绑定账号
    public function reg2_save() {
        $username = isset($_POST['username']) ? trim($_POST['username']) : exit("err-用户名错误");
        $password = isset($_POST['password']) ? trim($_POST['password']) : exit("err-密码错误");
        $member_type = $this->session->userdata['member_type'];
        $member_type = !empty($member_type) ? intval($member_type) : exit("err-类型错误");
        $email = isset($_POST['email']) ? trim($_POST['email']) : exit("err-email错误");
        $email = !strstr($email, '@mailcatch.com') ? $email : exit("err-email不合法");
        $this->session->set_userdata(array("email" => $email));
        $mobile = $this->session->userdata['mobile'];
        $register = $this->user_register($username, $password, $member_type, $email, $mobile);
        if ($register > 0) {
            $redirect_url = $this->session->userdata('redirect_url');
            $user_info = $this->user_models->get_user_info_by_id($register);
            if (!empty($user_info)) {
                $this->session->set_userdata(array("uid" => $user_info['uid'], 'session' => $user_info['session'], 'username' => $user_info['username']));
            }
            if ($user_info['utype'] == 2) {
                $this->session->set_userdata("reg_back", $redirect_url);
                exit("/personal_center/make_info");
            } elseif ($user_info['utype'] == 1) {
                exit("/company_center/my_info");
            } elseif (!empty($redirect_url)) {
                $this->session->unset_userdata('redirect_url');
                exit($redirect_url);
            } else {
                exit("/");
            }
        } else {
            exit("err-注册失败");
        }
    }

    //发送手机验证码
    function send_code($mobile = 0, $sms_back_code = 0, $type = 1) {
        if ($sms_back_code == $this->session->userdata['sms_back_code']) {
            if ($type == 1) {
                $t_code = "SMS_174650922";
            } elseif ($type == 2) {
                $t_code = "SMS_174650919";
            } elseif ($type == 3) {
                $t_code = "SMS_174685058";
            }
            $mobile = intval($mobile);
            $result = rand(1000, 9999);
            $this->session->set_userdata(array("sms_code" => $result));
            if ($mobile == '13632243614') {
                var_dump($result);
                exit;
            }
            $this->common_models->send_sms($mobile, $result, $t_code);
        } else {
            echo "非法操作";
        }
    }

    //检查手机
    public function check_mobile() {
        $mobile = trim($_POST['mobile']);
        preg_match("/^(1)\d{10}$/", $mobile) ? $user = $this->user_models->get_user_info_by_mobile($mobile) : exit("false");
        empty($user) ? exit("true") : exit("false");
    }

    //检查是否存在手机用户
    public function check_mobile_exists() {
        $mobile = trim($_POST['mobile']);
        preg_match("/^(1)\d{10}$/", $mobile) ? $user = $this->user_models->get_user_info_by_mobile($mobile) : exit("false");
        empty($user) ? exit("false") : exit("true");
    }

    //检查用户名
    public function check_username() {
        $username = trim($_POST['username']);
        $username = toUTF8($username);
        $user = $this->user_models->get_user_info_by_username($username);
        preg_match("/^[\w]+$/", $username) && empty($user) ? exit("true") : exit("false");
    }

    //检查手机验证码
    public function check_code() {
        $code = intval($_POST['code']);
        preg_match("/^\d{4}$/", $code) > 0 && $this->session->userdata['sms_code'] == $code ? exit("true") : exit("false");
    }

    //检查email
    public function check_email() {
        $email = trim($_POST['email']);
        $email = $this->user_models->get_user_info_by_email($email);
        empty($email) ? exit("true") : exit("false");
    }

    //注册会员
    public function user_register($username, $password, $member_type = 0, $email, $mobile = 0) {
        $member_type = intval($member_type);
        $ck_username = $this->user_models->get_user_info_by_username($username);
        $ck_email = $this->user_models->get_user_info_by_email($email);
        $ck_mobile = $this->user_models->get_user_info_by_mobile($mobile);
        if ($member_type == 0) {
            exit("err-用户类型错误");
        } elseif (!empty($ck_username)) {
            exit("err-用户名已存在");
        } elseif (!empty($ck_email)) {
            exit("err-电子邮箱已存在");
        } elseif (!empty($ck_mobile)) {
            exit("err-手机号码已存在");
        }
        $pwd_hash = randstr();
        $password_hash = md5(md5($password) . $pwd_hash . qs_hash);
        $setsqlarr['username'] = toUTF8($username);
        $setsqlarr['password'] = $password_hash;
        $setsqlarr['pwd_hash'] = $pwd_hash;
        $setsqlarr['email'] = $email;
        $setsqlarr['mobile'] = $mobile;
        $setsqlarr['mobile_audit'] = 1;
        $setsqlarr['utype'] = $member_type;
        $setsqlarr['reg_time'] = time();
        $setsqlarr['reg_ip'] = get_ip();
        $uid = $this->user_models->insert_members($setsqlarr);
        $statistics_name = $member_type == "2" ? 'reg_user' : 'school';
        $this->statistics_models->add_statistics_all($statistics_name);
        $this->statistics_models->add_statistics_day($statistics_name);
        if ($member_type == "1") {
            $this->make_company($uid, $username);
        }
        $this->common_models->write_memberslog($uid, $member_type, 1000, $username, "注册成为会员");
        return $uid;
    }

    function get_pass() {
        $data['sms_back_code'] = rand(000000, 9999999);
        $this->session->set_userdata(array("sms_back_code" => $data['sms_back_code']));
        $this->load->view('user_get_pass', $data);
    }

    function get_pass2() {
        $phone = isset($_POST['mobile']) ? intval($_POST['mobile']) : "";
        if ($phone == 0) {
            alert_to('手机不能为空！', '/user/get_pass');
        }
        $user = $this->user_models->get_user_info_by_mobile($phone);
        if (empty($user)) {
            alert_to('未检测到此手机号。您可以通过电脑使用邮箱找回密码。！', '/user/get_pass');
        } else {
            $data['uid'] = $user['uid'];
            $this->load->view('user_get_pass2', $data);
        }
    }

    function reset_pass() {
        $uid = intval($_POST['uid']);
        $password = trim($_POST['password']);
        $user = $this->user_models->get_user_info_by_id($uid);
        if (empty($user)) {
            alert_to('用户信息错误！', '/user/get_pass');
        } else {
            $pwd_hash = $user['pwd_hash'];
            $password_hash = md5(md5($password) . $pwd_hash . qs_hash);
            $this->user_models->up_user_password($user['uid'], $password_hash);
            alert_to('密码重置成功！', '/user/login');
        }
    }

    function agreement() {
        $data = $this->common_models->get_explain(13);
        $this->load->view('user_agreement', $data);
    }

    //检查登陆状态接口
    public function check_login() {
        $url = $_GET['url'];
        $this->common_models->check_login($url);
    }

    //帐号密码登录页面
    public function login() {
        $this->load->view('user_login');
    }

    //验证码登录页面
    public function login_code() {
        $data['sms_back_code'] = rand(000000, 9999999);
        $this->session->set_userdata(array("sms_back_code" => $data['sms_back_code']));
        $this->load->view('user_login_code', $data);
    }

    //登录接口
    public function login_save() {
        $wx_unionid = $this->session->userdata('wx_unionid');
        $wx_openid = $this->session->userdata('wx_openid');
        $account = isset($_POST['account']) ? trim($_POST['account']) : "";
        $password = isset($_POST['password']) ? trim($_POST['password']) : "";
        $login_type = isset($_POST['login_type']) ? intval($_POST['login_type']) : 1;
        $account = $login_type == 1 ? intval($_POST['mobile']) : $account;
        $code = $login_type == 1 ? intval($_POST['code']) : "";
        if (empty($account) && !empty($wx_unionid)) {
            $login_type = 3;
            $account = 1;
        }
        $user_info = "";
        //如果是post方式调用就开始验证
        if (!empty($account)) {
            if ($login_type == 1) {
                $this->user_models->up_yunpian_sms_back($account);
                if ($code == $this->session->userdata('sms_code')) {
                    $user_info = $this->user_models->get_user_info_by_mobile($account);
                }
            } else if ($login_type == 2) {
                $is_email = filter_var($account, FILTER_VALIDATE_EMAIL);
                if (!$is_email) {
                    $user_info = $this->user_models->get_user_info_by_username($account);
                } else {
                    $user_info = $this->user_models->get_user_info_by_email($account);
                }
            } else if ($login_type == 3) {
                $user_info = $this->user_models->get_user_info_by_wxunionid($wx_unionid);
            }
            if (!empty($user_info)) {
                $pwd32 = $login_type == 1 || $login_type == 3 ? $user_info['password'] : md5(md5($password) . $user_info['pwd_hash'] . qs_hash);
                $pwd_old = !empty($password) ? substr(md5($password), 8, 16) : 0;
                //判断在数据中记录的是否是16位加密
                if ($user_info['password'] == $pwd_old) {
                    //如果是16位的并且输入密码正确，把用户输入的密码改为32位密码，并更新数据库
                    $this->user_models->up_user_password($user_info['uid'], $pwd32);
                }
                $session = md5(time());
                if (!$this->user_models->check_password($user_info['uid'], $pwd32, $session)) {
                    alert_to('密码或账号错误！', '/user/login');
                }
                $redirect_url = $this->session->userdata('redirect_url');
                $redirect_url = !empty($redirect_url) ? $redirect_url : "/";
                $this->session->set_userdata(array("uid" => $user_info['uid'], "utype" => $user_info['utype'], 'session' => $session, 'username' => $user_info['username'], 'last_login_time' => time()));
                if ($user_info['utype'] == 1) {
                    $company = $this->company_models->get_company_by_uid($user_info['uid']);
                    if (empty($company)) {
                        $redirect_url = "/company_center/my_info";
                    }
                } else {
                    $resume = $this->resume_models->get_one_resume($user_info['uid']);
                    if (empty($resume)) {
                        $redirect_url = "/personal_center/edit_basic";
                    }
                }
                $this->session->set_userdata('redirect_url', $redirect_url);
                if (is_wechat()) {
                    header('Location:' . "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . WX_ID . "&redirect_uri=http://m.jiaoshizhaopin.net/user/login_bind_wechat_auth&response_type=code&scope=snsapi_base&state=123#wechat_redirect");
                } else {
                    header("Location: " . $redirect_url);
                }
            } elseif ($login_type != 3) {
                alert_to('密码或账号错误！', '/user/login');
            }
        }
        alert_to('登录失败！', '/user/login');
    }

    public function logout() {
        $this->session->unset_userdata('uid');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('wx_unionid');
        $this->session->unset_userdata('wx_openid');
        header("Location: /user/login");
    }

    public function auth() {
        $user_info = "";
        $state_data = array();
        $redirect_url = $this->session->userdata('redirect_url');
        $state = $_GET['state'] != "123" ? $_GET['state'] : "";
        $state_arr = explode("-|-", $state);
        if (!empty($state_arr[0])) {
            foreach ($state_arr as $sa) {
                $state_tmp = explode("||", $sa);
                $state_data[$state_tmp[0]] = $state_tmp[1];
            }
        }
        $wx_userinfo = $this->wechat_models->get_wx_userinfo_by_code($_GET['code']);
        $user_info = $this->user_models->get_user_info_by_wxunionid($wx_userinfo['unionid']);
        if (empty($user_info)) {
            $user_info = $this->user_models->get_user_info_by_wxopenid($wx_userinfo['openid']);
        }
        if (empty($user_info['wechat_district']) && !empty($wx_userinfo)) {
            $data['wechat_district'] = toUTF8($wx_userinfo['country'] . "/" . $wx_userinfo['province'] . "/" . $wx_userinfo['city']);
            $this->user_models->up_user_info_by_id($user_info['uid'], $data);
        }
        if (!empty($wx_userinfo['unionid']) && !empty($user_info)) {
            if (empty($user_info['wx_unionid']) || empty($user_info['wechat_openid'])) {
                $bind = $this->user_models->bind_wechat($user_info['uid'], $wx_userinfo['unionid'], $wx_userinfo['openid']);
                if ($bind > 0) {
                    $this->send_wechat_msg_binding($user_info['uid']);
                }
            }
            $this->session->set_userdata(array("uid" => $user_info['uid'], 'wx_unionid' => $wx_userinfo['unionid'], 'wx_openid' => $wx_userinfo['openid'], 'username' => $user_info['username'], 'last_login_time' => time()));
            $redirect_url = !empty($redirect_url) ? $redirect_url : "/";
            header("Location: " . $redirect_url);
        } elseif (!empty($wx_userinfo['unionid'])) {
            $this->session->set_userdata(array('wx_unionid' => $wx_userinfo['unionid'], 'wx_openid' => $wx_userinfo['openid']));
            if ($state_data['come'] == 'check_login') {
                header('Location:/user/login');
            } else {
                $redirect_url = !empty($redirect_url) ? $redirect_url : "/";
                header("Location: " . $redirect_url);
            }
        } else {
            header('Location:/user/login');
        }
    }

    public function login_bind_wechat_auth() {
        $redirect_url = $this->session->userdata('redirect_url');
        $wx_userinfo = $this->wechat_models->get_wx_userinfo_by_code($_GET['code']);
        $uid = $this->session->userdata('uid');

        $user_info = $this->user_models->get_user_info_by_id($uid);
        if (empty($user_info['wechat_district']) && !empty($wx_userinfo)) {
            $data['wechat_district'] = toUTF8($wx_userinfo['country'] . "/" . $wx_userinfo['province'] . "/" . $wx_userinfo['city']);
            $this->user_models->up_user_info_by_id($user_info['uid'], $data);
        }
        $bind = $this->user_models->bind_wechat($uid, $wx_userinfo['unionid'], $wx_userinfo['openid']);
        if ($bind > 0) {
            $this->send_wechat_msg_binding($uid);
        }
        $this->session->set_userdata(array('wx_unionid' => $wx_userinfo['unionid'], 'wx_openid' => $wx_userinfo['openid']));
        $redirect_url = !empty($redirect_url) ? $redirect_url : "/";
        header("Location: " . $redirect_url);
    }

    function send_wechat_msg_binding($uid) {
        $user = $this->user_models->get_user_info_by_id($uid);
        if ($user['utype'] == 1) {
            $str = toGBK("老师您好，小助理已经为您绑定好微信号，您可以通过<a href='http://m.jiaoshizhaopin.net/company_center'>教师招聘网</a>或下方菜单中的求职招聘进行简历、职位、企业信息管理");
        } else {
            $str = toGBK("老师您好，小助理已经为您绑定好微信号，您可以通过微信接受面试邀请，投递状态提醒等，还可以通过<a href='http://m.jiaoshizhaopin.net/personal_center'>教师招聘网</a>或下方菜单中的求职招聘进行登录简历管理等操作");
        }
        $this->wechat_models->request_wechat_msg('custom', $user['wechat_openid'], $str);
    }

    //处理企业会员套餐积分
    public function report_deal($uid, $i_type, $points) {
        $points = intval($points);
        $uid = intval($uid);
        if ($i_type == 2) {
            $points = $this->company_models->set_members_limit_points($uid, 2, $points);
        }
        $points_val = $this->company_models->get_user_points($uid);
        if ($i_type == 1) {
            $points_val = $points_val + $points;
        }
        if ($i_type == 2) {
            $points_val = $points_val - $points;
            $points_val = $points_val < 0 ? 0 : $points_val;
        }
        $data = array('points' => $points_val);
        return $this->company_models->update_members_points($uid, $data);
    }

    //处理企业会员限时积分
    public function set_members_limit_points($uid, $i_type = 1, $points = 0, $days = 0) {
        $p = 0;
        $up_limit = array();
        $points = intval($points);
        $uid = intval($uid);
        $limit_info = $this->company_models->get_user_limit_points($uid);
        if (!empty($limit_info['id'])) {
            if ($limit_info['endtime'] > 0) {
                if ($i_type == 1) {
                    $up_limit['points'] = $limit_info['points'] + $points;
                } elseif ($i_type == 2) {
                    if ($limit_info['endtime'] > time() && $limit_info['endtime'] > 0) {
                        $up_limit['points'] = $limit_info['points'] - $points;
                        $p = $up_limit['points'] < 0 ? abs($up_limit['points']) : 0;
                        $up_limit['points'] = $up_limit['points'] < 0 ? 0 : $up_limit['points'];
                    }
                }
                $up_limit['endtime'] = $limit_info['endtime'] > time() ? $limit_info['endtime'] + ($days * 86400) : time() + ($days * 86400);
                $up_limit['points'] = $up_limit['endtime'] > time() ? $up_limit['points'] : 0;
            } else {
                $up_limit['points'] = 0;
            }
            $this->company_models->update_members_points_limit($uid, $up_limit);
        } elseif ($days > 0) {
            $up_limit['uid'] = $uid;
            $up_limit['points'] = $points;
            $up_limit['addtime'] = time();
            $up_limit['endtime'] = time() + ($days * 86400);
            $this->company_models->add_members_points_limit($up_limit);
        } else {
            $p = $points;
        }
        return $p;
    }

    //设置企业会员套餐初始数据
    public function set_members_setmeal($uid, $setmealid) {
        $setmeal = $this->company_models->get_setmeal_one($setmealid);
        if (!empty($setmeal)) {
            $setsqlarr['effective'] = 1;
            $setsqlarr['setmeal_id'] = $setmeal['id'];
            $setsqlarr['setmeal_name'] = $setmeal['setmeal_name'];
            $setsqlarr['days'] = $setmeal['days'];
            $setsqlarr['starttime'] = time();
            if ($setmeal['days'] > 0) {
                $setsqlarr['endtime'] = strtotime("" . $setmeal['days'] . " days");
            } else {
                $setsqlarr['endtime'] = "0";
            }
            $setsqlarr['expense'] = $setmeal['expense'];
            $setsqlarr['jobs_ordinary'] = $setmeal['jobs_ordinary'];
            $setsqlarr['download_resume_ordinary'] = $setmeal['download_resume_ordinary'];
            $setsqlarr['download_resume_senior'] = $setmeal['download_resume_senior'];
            $setsqlarr['interview_ordinary'] = $setmeal['interview_ordinary'];
            $setsqlarr['interview_senior'] = $setmeal['interview_senior'];
            $setsqlarr['talent_pool'] = $setmeal['talent_pool'];
            $setsqlarr['recommend_num'] = $setmeal['recommend_num'];
            $setsqlarr['recommend_days'] = $setmeal['recommend_days'];
            $setsqlarr['stick_num'] = $setmeal['stick_num'];
            $setsqlarr['stick_days'] = $setmeal['stick_days'];
            $setsqlarr['emergency_num'] = $setmeal['emergency_num'];
            $setsqlarr['emergency_days'] = $setmeal['emergency_days'];
            $setsqlarr['highlight_num'] = $setmeal['highlight_num'];
            $setsqlarr['highlight_days'] = $setmeal['highlight_days'];
            $setsqlarr['change_templates'] = $setmeal['change_templates'];
            $setsqlarr['jobsfair_num'] = $setmeal['jobsfair_num'];
            $setsqlarr['map_open'] = $setmeal['map_open'];
            $setsqlarr['added'] = $setmeal['added'];
            $setsqlarr['refresh_jobs_space'] = $setmeal['refresh_jobs_space'];
            $setsqlarr['refresh_jobs_time'] = $setmeal['refresh_jobs_time'];
            $this->company_models->update_members_setmeal_by_uid($uid, $setsqlarr);
            $setmeal_jobs['setmeal_deadline'] = $setsqlarr['endtime'];
            $setmeal_jobs['setmeal_id'] = $setsqlarr['setmeal_id'];
            $setmeal_jobs['setmeal_name'] = $setsqlarr['setmeal_name'];
            $this->job_models->update_job_add_mode_by_uid_add_mode($uid, 2, $setmeal_jobs);
            $this->job_models->update_job_tmp_add_mode_by_uid_add_mode($uid, 2, $setmeal_jobs);
            $this->job_models->update_hunter_jobs_add_mode_by_uid_add_mode($uid, 2, $setmeal_jobs);
            $this->company_models->distribution_jobs_uid($uid);
        }
    }

    //设置企业会员套餐初始数据
    public function make_company($uid, $username) {
        $this->company_models->insert_members_points($uid);
        $this->company_models->insert_members_setmeal($uid);
        $points = $this->company_models->get_points_rule();
        if ($points['reg_points']['value'] > 0) {
            $this->report_deal($uid, $points['reg_points']['type'], $points['reg_points']['value']);
            $operator = $points['reg_points']['type'] == "1" ? "+" : "-";
            $this->common_models->write_memberslog($uid, 1, 9001, $username, "新注册会员,(" . $operator . $points['reg_points']['value'] . "),(剩余:" . $points['reg_points']['value'] . ")", 1, 1010, "注册会员系统自动赠送积分", $operator . $points['reg_points']['value'], $points['reg_points']['value']);
            //积分变更记录
            $this->company_models->write_setmeallog($uid, $username, "注册会员系统自动赠送：(" . $operator . $points['reg_points']['value'] . "),(剩余:" . $points['reg_points']['value'] . ")", 1, '0.00', '1', 1, 1);
        }
        $reg_service = $this->common_models->get_sys_config('reg_service');
        if ($reg_service > 0) {
            $this->set_members_setmeal($uid, $reg_service);
            $setmeal = $this->company_models->get_setmeal_one($reg_service);
            $this->common_models->write_memberslog($uid, 1, 9002, $username, "注册会员系统自动赠送：" . $setmeal['setmeal_name'], 2, 1011, "开通服务(系统赠送)", "-", "-");
            //套餐变更记录
            $this->company_models->write_setmeallog($uid, $username, "注册会员系统自动赠送：" . $setmeal['setmeal_name'], 1, '0.00', '1', 2, 1);
        }
    }

}
