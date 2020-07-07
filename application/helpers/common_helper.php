<?php

error_reporting(0);

//转GBK编码
function toGBK($str) {
    $str = iconv("GB2312", "UTF-8//IGNORE", $str);
    return $str;
}

//转UTF8编码
function toUTF8($str) {
    $str = iconv("UTF-8", "GB2312", $str);
    return $str;
}

//获取随机字符串
function randstr($length = 6) {
    $hash = '';
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz@#!~?:-=';
    $max = strlen($chars) - 1;
    mt_srand((double) microtime() * 1000000);
    for ($i = 0; $i < $length; $i++) {
        $hash.=$chars[mt_rand(0, $max)];
    }
    return $hash;
}

//截取字符串
function cut_str($string, $length, $start = 0, $dot = '') {
    $length = $length * 2;
    if (strlen($string) <= $length) {
        return $string;
    }
    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
    $strcut = '';
    for ($i = 0; $i < $length; $i++) {
        $strcut .= ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
    }
    $strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
    return $strcut . $dot;
}

//不同环境下获取真实的IP
function get_ip() {
//判断服务器是否允许$_SERVER
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $realip = $_SERVER['REMOTE_ADDR'];
        }
    } else {
//不允许就使用getenv获取  
        if (getenv("HTTP_X_FORWARDED_FOR")) {
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }
    return $realip;
}

//转换单个IP段为地址
function convertip_tiny($ip, $ipdatafile) {
    static $fp = NULL, $offset = array(), $index = NULL;
    $ipdot = explode('.', $ip);
    $ip = pack('N', ip2long($ip));
    $ipdot[0] = (int) $ipdot[0];
    $ipdot[1] = (int) $ipdot[1];
    if ($fp === NULL && $fp = @fopen($ipdatafile, 'rb')) {
        $offset = @unpack('Nlen', @fread($fp, 4));
        $index = @fread($fp, $offset['len'] - 4);
    } elseif ($fp == FALSE) {
        return '- Invalid IP data file';
    }
    $length = $offset['len'] - 1028;
    $start = @unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]);

    for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8) {

        if ($index{$start} . $index{$start + 1} . $index{$start + 2} . $index{$start + 3} >= $ip) {
            $index_offset = @unpack('Vlen', $index{$start + 4} . $index{$start + 5} . $index{$start + 6} . "\x0");
            $index_length = @unpack('Clen', $index{$start + 7});
            break;
        }
    }
    @fseek($fp, $offset['len'] + $index_offset['len'] - 1024);
    if ($index_length['len']) {
        return '- ' . @fread($fp, $index_length['len']);
    } else {
        return '- Unknown';
    }
}

//转换IP为地址
function convertip($ip) {
    $return = "";
    if (preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
        $iparray = explode('.', $ip);
        if ($iparray[0] == 10 || $iparray[0] == 127 || ($iparray[0] == 192 && $iparray[1] == 168) || ($iparray[0] == 172 && ($iparray[1] >= 16 && $iparray[1] <= 31))) {
            $return = '- LAN';
        } elseif ($iparray[0] > 255 || $iparray[1] > 255 || $iparray[2] > 255 || $iparray[3] > 255) {
            $return = '- Invalid IP Address';
        } else {
            $tinyipfile = BASEPATH . 'data/tinyipdata.dat';
            if (@file_exists($tinyipfile)) {
                $return = convertip_tiny($ip, $tinyipfile);
            }
        }
    }
    return $return;
}

//ajax上传图片
function _asUpFiles($dir, $file_var, $max_size = '', $type = '', $name = false) {
    if (!file_exists($dir)) {
        exit("-1");
    } elseif (!is_writable($dir)) {
        exit("-2");
    }
    $upfile = & $_FILES["$file_var"];
    $upfilename = $upfile['name'];
    if (!empty($upfilename)) {
        if (!is_uploaded_file($upfile['tmp_name'])) {
            exit("-3");
        } elseif ($max_size > 0 && $upfile['size'] / 1024 > $max_size) {
            exit("-4");
        }
        $ext_name = strtolower(str_replace(".", "", strrchr($upfilename, ".")));
        if (!empty($type)) {
            $arr_type = explode('/', $type);
            $arr_type = array_map("strtolower", $arr_type);
            if (!in_array($ext_name, $arr_type)) {
                exit("-5");
            }
            $harmtype = array("asp", "php", "jsp", "js", "css", "php3", "php4", "ashx", "aspx", "exe");
            if (in_array($ext_name, $harmtype)) {
                exit("ERR!");
            }
        }
        if (!is_bool($name)) {
            $uploadname = $name . "." . $ext_name;
        } elseif ($name === true) {
            $uploadname = time() . mt_rand(100, 999) . "." . $ext_name;
        }
        if (!move_uploaded_file($upfile['tmp_name'], $dir . $uploadname)) {
            exit("-6");
        }
        return $uploadname;
    }
    return '';
}

//创建目录
function make_dir($path) {
    if (!file_exists($path)) {
        make_dir(dirname($path));
        @mkdir($path, 0777);
        @chmod($path, 0777);
    }
}

//获取PC域名
function get_base_site() {
    switch ($_SERVER['HTTP_HOST']) {
        case 'm.jiaoshizhaopin.net':
            return 'www.jiaoshizhaopin.net';
            break;
        default:
            return 'test.jiaoshizhaopin.net';
            break;
    }
}

//弹框跳转
function alert_to($text = "", $url = "", $js = 0) {
    if ($js == 0) {
        $url = 'window.location.href = "' . $url . '"';
    }
    echo '<script>alert("' . $text . '");' . $url . ';</script>';
    exit();
}

//获取分页数组
function get_page_arr($total = 0, $page = 1, $page_num = 10) {
    $page = $page > $total ? $total : $page;
    $page = $page < 1 ? 1 : $page;
    $totalpage = ceil($total / $page_num);
    $offset = ($page - 1) * $page_num;
    $page_arr['offset'] = $offset;
    $page_arr['total'] = $total;
    $page_arr['totalpage'] = $totalpage;
    $page_arr['page_num'] = $page_num;
    $page_arr['now_page'] = $page;
    $page_arr['per_page'] = $page - 1 < 1 ? 1 : $page - 1;
    $page_arr['next_page'] = $page + 1 > $totalpage ? $totalpage : $page + 1;
    $page_arr['start_page'] = $page - 5 > 0 ? $page - 5 : 1;
    $page_arr['end_page'] = $page + 5 > $totalpage ? $totalpage : $page + 5;
    return $page_arr;
}

//转换时间
function parse_date($time) {
    $time = strtotime(date("Y-m-d", $time));
    $now = strtotime(date("Y-m-d"));
    $day = ceil(($now - $time) / 86400);
    if ($day == 0) {
        $time = '今天';
    } else if ($day == 1) {
        $time = '昨天';
    } else if ($day <= 3 && $day > 0) {
        $time = $day . '天前';
    } else {
        $time = date("Y-m-d", $time);
    }
    return $time;
}

//转换剩余时间
function sub_day($endday, $staday, $range = '') {
    $value = $endday - $staday;
    if ($value < 0) {
        return '';
    } elseif ($value >= 0 && $value < 59) {
        return ($value + 1) . "秒";
    } elseif ($value >= 60 && $value < 3600) {
        $min = intval($value / 60);
        return $min . "分钟";
    } elseif ($value >= 3600 && $value < 86400) {
        $h = intval($value / 3600);
        return $h . "小时";
    } elseif ($value >= 86400 && $value < 86400 * 30) {
        $d = intval($value / 86400);
        return intval($d) . "天";
    } elseif ($value >= 86400 * 30 && $value < 86400 * 30 * 12) {
        $mon = intval($value / (86400 * 30));
        return $mon . "月";
    } else {
        $y = intval($value / (86400 * 30 * 12));
        return $y . "年";
    }
}

//判断是否在微信里打开
function is_wechat() {
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    } else {
        return false;
    }
}

//获取页面描述
function get_page_desc($content, $length = 100) {
    $str = trim($content); //清除字符串两边的空格
    $str = preg_replace("/\t/", "", $str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。
    $str = preg_replace("/\r\n/", "", $str);
    $str = preg_replace("/\r/", "", $str);
    $str = preg_replace("/\n/", "", $str);
    $str = preg_replace("/ /", "", $str);
    $str = preg_replace("/  /", "", $str);  //匹配html中的空格
    $desc = mb_substr(strip_tags($str), 0, $length, 'GBK');
    return $desc;
}

//发送短信通知
function send_sms($mobile = 0, $text = "") {
    $mobile = intval($mobile);
    if (preg_match("/^(1)\d{10}$/", $mobile)) {
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
    } else {
        exit("手机号格式错误");
    }
}
