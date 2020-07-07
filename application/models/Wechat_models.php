<?php

class Wechat_models extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->mem = new Memcache;
        $this->mem->connect("localhost", 11111);
    }

    function add_wechat_message($data) {
        $this->db->insert('wechat_message', $data);
    }

    function get_wechat_account($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('jiaoshi_wechat_account');
        return $query->row_array();
    }

    public function add_wechat_statistics($name) {
        $time = date('Ymd', time());
        $this->db->where('name', $name);
        $this->db->where('time', $time);
        $query = $this->db->get('jiaoshi_wechat_statistics');
        $wechat_statistics = $query->row_array();
        if (!empty($wechat_statistics)) {
            $this->db->where('id', $wechat_statistics['id']);
            $data = array('name' => $name, 'time' => $time, 'count' => ($wechat_statistics['count'] + 1));
            $this->db->update('jiaoshi_wechat_statistics', $data);
        } else {
            $data = array('name' => $name, 'time' => $time, 'count' => 1);
            $this->db->insert('jiaoshi_wechat_statistics', $data);
        }
    }

    //匹配微信回复关键字
    function parse_keyword($keyword) {
        $result_category = array();
        $districts = $this->get_parse_keyword_districts();
        $jobs = $this->get_parse_keyword_jobs();
        $category = array_merge($districts, $jobs);
        foreach ($category as $k => $v) {
            $v['categoryname'] = iconv("GB2312", "UTF-8//IGNORE", $v['categoryname']);
            $pos = stristr($keyword, $v['categoryname']);
            if ($pos) {
                $result_category[$k] = $v;
                $keyword_arr = explode($v['categoryname'], $keyword);
                if (!empty($keyword_arr)) {
                    break;
                }
            }
        }
        foreach ($keyword_arr as $ka) {
            if (!empty($ka)) {
                foreach ($category as $k => $v) {
                    $v['categoryname'] = iconv("GB2312", "UTF-8//IGNORE", $v['categoryname']);
                    $pos = stristr($v['categoryname'], $ka);
                    if ($pos) {
                        $result_category1[$k] = $v;
                    }
                }
            }
        }
        foreach ($keyword_arr as $ka) {
            if (!empty($ka)) {
                foreach ($category as $k => $v) {
                    $v['categoryname'] = iconv("GB2312", "UTF-8//IGNORE", $v['categoryname']);
                    $pos = stristr($ka, $v['categoryname']);
                    if ($pos) {
                        $result_category2[$k] = $v;
                    }
                }
            }
        }
        $result_category_tmp = (count($result_category1) > count($result_category2)) ? $result_category1 : $result_category2;
        $result_category = !empty($result_category_tmp) ? array_merge($result_category, $result_category_tmp) : $result_category;
        foreach ($result_category as $key => $rc) {
            $t = explode("_", $key);
            $this->db->select('id,parentid,categoryname');
            $this->db->where('id', $t[1]);
            $query = $this->db->get('category_' . $t[0]);
            $end = $query->row_array();
            $end['c_type'] = $t[0];
            $end_category[] = $end;
            $this->db->where('name', $end['categoryname']);
            $query = $this->db->get('jiaoshi_keyword_tag');
            $keyword_tag = $query->row_array();
            if (empty($keyword_tag)) {
                $data = array('name' => $end['categoryname'], 'count' => 1);
                $this->db->insert('jiaoshi_keyword_tag', $data);
            } else {
                $this->db->where('id', $keyword_tag['id']);
                $data = array('count' => ($k['count'] + 1));
                $this->db->update('jiaoshi_keyword_tag', $data);
            }
        }
        $end_category = !empty($result_category) ? $end_category : $result_category;
        return $end_category;
    }

    //推送微信消息
    function request_wechat_msg($msg_type, $openid, $data1, $data2, $data3, $data4, $data5, $data6, $data7, $data8, $data9) {
        $templates = $this->get_wx_msg_templates($msg_type);
        if (stripos($msg_type, "|")) {
            $msg_type = explode("|", $msg_type);
            $msg_type = $msg_type[0];
        }
        if ($msg_type == 'template') {
            $content = sprintf($templates, $openid, $data1, $data2, $data3, $data4, $data5, $data6, $data7, $data8, $data9);
            $website = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=';
        } else if ($msg_type == 'custom') {
            $content = sprintf($templates, $openid, $data1);
            $website = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=';
        } else if ($msg_type == 'mass') {
            $website = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=';
        }
        $access_token = $this->get_wechat_base_token();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //不直接输出，返回到变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_URL, $website . $access_token);
        $curl_result = curl_exec($ch);
        $de_json = json_decode($curl_result, TRUE);
        curl_close($ch);
    }

    //获取微信token
    function get_wechat_token($code) {
        $website = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . WX_ID . '&secret=' . WX_SECRET . '&code=' . $code . '&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $website);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $curl_result = curl_exec($ch);
        curl_close($ch);
        $de_json = json_decode($curl_result, TRUE);
        $openid = $de_json['openid'];
        $refresh_token = $de_json['refresh_token'];
        $access_token = $de_json['access_token'];
        //$mem_str = '{"wechat_code_token":"' . $de_json['access_token'] . '","expire_time":' . ($de_json['expires_in'] + time()) . '}';
        //$this->mem->set('wechat_code_token', $mem_str, 0, 7000);
        $website = 'https://api.weixin.qq.com/sns/auth?access_token=' . $access_token . '&openid=' . $de_json['openid'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $website);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $curl_result = curl_exec($ch);
        curl_close($ch);
        $de_json = json_decode($curl_result, TRUE);
        if ($de_json['errcode'] != 0) {
            $website = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=' . WX_ID . '&grant_type=refresh_token&refresh_token=' . $refresh_token;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $website);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $curl_result = curl_exec($ch);
            curl_close($ch);
            $de_json = json_decode($curl_result, TRUE);
            //$mem_str = '{"wechat_code_token":"' . $de_json['access_token'] . '","expire_time":' . ($de_json['expires_in'] + time()) . '}';
            //$this->mem->set('wechat_code_token', $mem_str, 0, 7000);
            $access_token = $de_json['access_token'];
        }
        $result = array('access_token' => $access_token, 'openid' => $openid);
        return $result;
    }

    //获取微信token
    function get_wechat_base_token($refresh = 0) {
        $access_token_mem = $this->mem->get('wechat_base_token');
        if (empty($access_token_mem)) {
            $access_token_mem = '{"wechat_base_token":"","expire_time":0}';
        }
        $data = json_decode($access_token_mem);
        //if (1) {
        if ((empty($data->wechat_base_token) || $data->expire_time < time()) || $refresh == 1) {
            $access_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . WX_ID . '&secret=' . WX_SECRET;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $access_token_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $curl_result = curl_exec($ch);
            $data = json_decode($curl_result, TRUE);
            $datastr = '{"wechat_base_token":"' . $data['access_token'] . '","expire_time":' . ($data['expires_in'] + time()) . '}';
            $this->mem->set('wechat_base_token', $datastr, 0, 7000);
            $access_token_mem = $this->mem->get('wechat_base_token');
            $data = json_decode($access_token_mem);
        }
        return $data->wechat_base_token;
    }

    public function get_media_by_eventkey($eventkey) {
        $this->db->where('eventkey', $eventkey);
        $query = $this->db->get('wechat_response_img');
        return $query->row_array();
    }

    //获取微信消息模版
    function get_wx_msg_templates($type = "") {
        switch ($type) {
            case 'custom':
                $templates = '{
                                    "touser":"%s",
                                    "msgtype":"text",
                                    "text":
                                    {
                                        "content":"%s"
                                    }
                                }';
                break;
            case 'template':
                $templates = ' {
                                    "touser":"%s",
                                    "template_id":"%s",
                                    "url":"%s",
                                    "topcolor":"#32afd3",
                                    "data":{
                                            "first": {
                                                "value":"%s",
                                                "color":"#0A0A0A"
                                            },
                                            "job":{
                                                "value":"%s",
                                                "color":"#000"
                                            },
                                            "company": {
                                                "value":"%s",
                                                "color":"#000"
                                            },
                                            "time":{
                                                "value":"%s",
                                                "color":"#000"
                                            },
                                            "remark":{
                                                "value":"%s",
                                                "color":"#173177"
                                            }
                                    }
                                }';
                break;
            case 'template|2':
                $templates = ' {
                                    "touser":"%s",
                                    "template_id":"%s",
                                    "url":"%s",
                                    "topcolor":"#32afd3",
                                    "data":{
                                            "first": {
                                                    "value":"%s",
                                                    "color":"#0A0A0A"
                                            },
                                            "company": {
                                                    "value":"%s",
                                                    "color":"#000"
                                            },
                                            "time":{
                                                    "value":"%s",
                                                    "color":"#000"
                                            },
                                            "result":{
                                                    "value":"%s",
                                                    "color":"#000"
                                            },
                                            "remark":{
                                                    "value":"%s",
                                                    "color":"#173177"
                                            }
                                    }
                            }';
                break;
            case 'template|3':
                $templates = ' {
                                    "touser":"%s",
                                    "template_id":"%s",
                                    "url":"%s",
                                    "topcolor":"#32afd3",
                                    "data":{
                                            "first": {
                                                    "value":"%s",
                                                    "color":"#0A0A0A"
                                            },
                                            "keyword1": {
                                                    "value":"%s",
                                                    "color":"#000"
                                            },
                                            "keyword2": {
                                                    "value":"%s",
                                                    "color":"#000"
                                            },
                                            "keyword3":{
                                                    "value":"%s",
                                                    "color":"#000"
                                            },
                                            "keyword4":{
                                                    "value":"%s",
                                                    "color":"#000"
                                            },
                                            "remark":{
                                                    "value":"%s",
                                                    "color":"#173177"
                                            }
                                    }
                            }';
                break;
            case 'template|4':
                $templates = ' {
                                    "touser":"%s",
                                    "template_id":"%s",
                                    "url":"%s",
                                    "topcolor":"#32afd3",
                                    "data":{
                                            "first": {
                                                    "value":"%s",
                                                    "color":"#0A0A0A"
                                            },
                                            "keyword1": {
                                                    "value":"%s",
                                                    "color":"#000"
                                            },
                                            "keyword2": {
                                                    "value":"%s",
                                                    "color":"#000"
                                            },
                                            "keyword3":{
                                                    "value":"%s",
                                                    "color":"#000"
                                            },
                                            "keyword4":{
                                                    "value":"%s",
                                                    "color":"#000"
                                            },
                                            "keyword5":{
                                                    "value":"%s",
                                                    "color":"#000"
                                            },
                                            "remark":{
                                                    "value":"%s",
                                                    "color":"#173177"
                                            }
                                    }
                            }';
                break;
            case 'template|5':
                $templates = ' {
                                    "touser":"%s",
                                    "template_id":"%s",
                                    "url":"%s",
                                    "topcolor":"#32afd3",
                                    "data":{
                                            "first": {
                                                    "value":"%s",
                                                    "color":"#0A0A0A"
                                            },
                                            "keyword1": {
                                                    "value":"%s",
                                                    "color":"#000"
                                            },
                                            "keyword2": {
                                                    "value":"%s",
                                                    "color":"#000"
                                            },
                                            "keyword3":{
                                                    "value":"%s",
                                                    "color":"#000"
                                            },
                                            "remark":{
                                                    "value":"%s",
                                                    "color":"#173177"
                                            }
                                    }
                            }';
                break;
            default:
                break;
        }
        return $templates;
    }

    //获取微信用户openid
    function get_wx_userinfo_by_code($code) {
        $result = $this->get_wechat_token($code);
        $unionid_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $result['access_token'] . '&openid=' . $result['openid'] . '&lang=zh_CN';
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $unionid_url);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        $c_result = curl_exec($ch2);
        curl_close($ch2);
        $json = json_decode($c_result, TRUE);
        return $json;
    }

    //获取微信回复关键字所有地区分类
    function get_parse_keyword_districts() {
        $districts = $this->mem->get('parse_keyword_districts');
        if (empty($districts)) {
            $this->db->select('id,parentid,categoryname');
            $query = $this->db->get('category_district');
            $query_districts = $query->result_array();
            foreach ($query_districts as $qd) {
                $districts['district_' . $qd['id']] = $qd;
            }
            $this->mem->set('parse_keyword_districts', $districts, 0, 7000);
        }
        return $districts;
    }

    //获取微信回复关键字所有职位分类
    function get_parse_keyword_jobs() {
        $jobs = $this->mem->get('parse_keyword_obs');
        if (empty($jobs)) {
            $this->db->select('id,parentid,categoryname');
            $query = $this->db->get('category_jobs');
            $query_jobs = $query->result_array();
            foreach ($query_jobs as $qj) {
                $jobs['jobs_' . $qj['id']] = $qj;
            }
            $this->mem->set('parse_keyword_jobs', $jobs, 0, 7000);
        }
        return $jobs;
    }

}
