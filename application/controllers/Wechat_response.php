<?php

//define your token
define("TOKEN", "jiaoshizhaopin123");


define('from_user', 'oflWCjiGtJmzS8Wbwo94XSyG2Y7w');
define('to_user', 'gh_18ca075e3348');
define('msg_type', 'text');

$wechatObj = new wechatCallbackapiTest();
$valid = $wechatObj->valid();
$wechatObj->responseMsg();

class wechatCallbackapiTest extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('article_models');
        $this->load->model('common_models');
        $this->load->model('job_models');
        $this->load->model('wechat_models');
        $this->load->model('statistics_models');
    }

    public function valid() {
        if (isset($_GET['echostr'])) {
            $echoStr = $_GET["echostr"];
            //valid signature , option
            if ($this->checkSignature()) {
                echo $echoStr;
                exit;
            }
        }
    }

    public function checkSignature() {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    public function responseMsg() {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        //extract post data
        if (!empty($postStr)) {
            //libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
            //the best way is to check the validity of xml by yourself 
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $msgType = $postObj->MsgType;
            switch ($msgType) {
                case 'text':
                    $this->receiveText($postObj);
                    break;
                case 'event':
                    $this->receiveEvent($postObj);
                    break;
                default:
                    break;
            }
        }
    }

    function receiveText($postObj) {
        $keyword = trim($postObj->Content);
        if (!empty($keyword)) {
            $this->wechat_models->add_wechat_message(array('openid' => $postObj->FromUserName, 'message' => toUTF8($keyword), 'addtime' => time()));
            $this->wechat_models->add_wechat_statistics('keyword');
            //�ɼ���ѯ begin
            $pos = stristr($keyword, toGBK("����"));
            if ($pos) {
                $contentStr = "��deluxe1989������z��?���仰��4wK21Y982SG�������?��";
                $this->encodeWechatText($postObj->FromUserName, $postObj->ToUserName, toGBK($contentStr));
                exit;
            }
            //�ɼ���ѯ end
            $district_categories = '';
            $jobs_categories = '';
            $contentStr = '';
            $list_keywords = $this->wechat_models->parse_keyword($keyword);
            //$this->logger("T " . $list_keywords);
            if (!empty($list_keywords)) {
                foreach ($list_keywords as $lk) {
                    if ($lk['c_type'] == 'jobs') {
                        if ($lk['parentid'] > 0) {
                            $where['subclass'][] = $lk['id'];
                        } else {
                            $where['category'][] = $lk['id'];
                        }
                    } else {
                        if ($lk['parentid'] > 0) {
                            $where['sdistrict'][] = $lk['id'];
                        } else {
                            $where['district'][] = $lk['id'];
                        }
                    }
                }
            }
            $jobs_list = $this->job_models->get_job_list_in_district_category($where, 5);
            if (count($jobs_list) < 5) {
                $limit = 5 - count($jobs_list);
                $article_list = $this->article_models->get_article_list_in_district($where, $limit);
            }
            if (!empty($article_list)) {
                $list = array_merge($jobs_list, $article_list);
            } else {
                $list = $jobs_list;
            }
            foreach ($list as $item) {
                if (empty($item['title'])) {
                    $contentStr.="��" . $item['district_cn'] . "�� <a href='http://" . $_SERVER['HTTP_HOST'] . "/job/detail?job_id=" . $item['id'] . "'>" . $item['jobs_name'] . "</a>��" . date("Y-m-d", $item['refreshtime']) . "��\n\n";
                } else {
                    $contentStr.="��" . $item['district_cn'] . "�� <a href='http://" . $_SERVER['HTTP_HOST'] . "/article/detail?article_id=" . $item['id'] . "'>" . $item['title'] . "</a>��" . date("Y-m-d", $item['addtime']) . "��\n\n";
                }
            }
            if (!empty($list)) {
                $contentStr.="<a href='http://" . $_SERVER['HTTP_HOST'] . "'>������Ϣ >></a>\n";
            } else {
                $contentStr.="û���ҵ����ְλŶ�����������Ϣ���Կ���\n<a href='http://" . $_SERVER['HTTP_HOST'] . "'>������Ϣ >></a>\n";
            }
            $this->encodeWechatText($postObj->FromUserName, $postObj->ToUserName, toGBK($contentStr));
        } else {
            $this->encodeWechatText($postObj->FromUserName, $postObj->ToUserName, toGBK("�����ʲô��..."));
        }
    }

    function receiveEvent($postObj) {
        $event = trim($postObj->Event);
        $eventKey = trim($postObj->EventKey);
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        if ($event == 'subscribe') {
            /* ��ע���ں� */
            $this->response_subscribe($fromUsername, $toUsername, $eventKey);
            $this->statistics_models->add_statistics_all('wechat_user');
            $this->statistics_models->add_statistics_day('wechat_user_increase');
        } else if ($event == 'unsubscribe') {
            /* ȡ����ע���ں� */
            $this->statistics_models->del_statistics_all('wechat_user');
            $this->statistics_models->add_statistics_day('wechat_user_decrease');
        } else if ($event == 'SCAN') {
            /* ɨ���ά���ע */
            $this->response_subscribe($fromUsername, $toUsername, "qrscene_scan");
        } else if ($event == 'LOCATION') {
            /* ���ù��� */
            /* ��΢�Ź��ںŷ��͵�λʱ���� */
        } else if ($event == 'CLICK') {
            /* ���ù��� */
            /* ΢�Ź��ںŹ����̨�������Զ���˵��¼����룬����󷵻ص���¼������¼����� */
            $this->encodeWechatImage($fromUsername, $toUsername, $eventKey);
        }
    }

    function response_subscribe($fromUsername, $toUsername, $eventKey) {
        $event_key = toUTF8($eventKey);
        $wechat_account = $this->wechat_models->get_wechat_account(1);
        $contentStr = toGBK($wechat_account['subscribe_reply']);
        if (strpos($event_key, 'qrscene_') !== false) {
            $this->encodeWechatArticle($fromUsername, $toUsername, toGBK('֪ͨ�����ѳɹ���¼��ʦ��Ƹ��'), toGBK('�ҹ���������ʦ����������ƶ��ˡ�'));
        } else {
            $this->encodeWechatText($fromUsername, $toUsername, $contentStr);
        }
    }

    function encodeWechatText($fromUsername, $toUsername, $contentStr) {
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                    </xml>";
        $time = time();
        $msgType = 'text';
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
        echo $resultStr;
    }

    function encodeWechatArticle($fromUsername, $toUsername, $title, $description) {
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <ArticleCount>1</ArticleCount>
                        <Articles>
                            <item>
                                <Title><![CDATA[%s]]></Title> 
                                <Description><![CDATA[%s]]></Description>
                                <PicUrl><![CDATA[%s]]></PicUrl>
                                <Url><![CDATA[%s]]></Url>
                            </item>
                        </Articles>
                    </xml> ";
        $time = time();
        $msgType = 'news';
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $title, $description, 'http://' . $_SERVER['HTTP_HOST'] . '/application/views/images/wechat_welcome_top.jpg', 'http://m.jiaoshizhaopin.net/');
        echo $resultStr;
    }

    function encodeWechatImage($fromUsername, $toUsername, $eventKey) {
        $eventimg = $this->wechat_models->get_media_by_eventkey($eventKey);
        $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Image>
                            <MediaId><![CDATA[%s]]></MediaId>
                            </Image>
                    </xml>";
        $time = time();
        $msgType = 'image';
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $eventimg['media_id']);
        echo $resultStr;
    }

    //д��Ӳ����־��¼�������ã�
    private function logger($log_content) {
        if (isset($_SERVER['HTTP_APPNAME'])) { //SAE
            sae_set_display_errors(false);
            sae_debug($log_content);
            sae_set_display_errors(true);
        } else if ($_SERVER['REMOTE_ADDR'] != "127.0.0.1") { //LOCAL
            $max_size = 10000;
            $log_filename = "/data2/log/wechat_log/log.xml";
            if (file_exists($log_filename) and ( abs(filesize($log_filename)) > $max_size)) {
                unlink($log_filename);
            }
            file_put_contents($log_filename, date('H:i:s') . " " . $log_content . "\r\n", FILE_APPEND);
        }
    }

}

?>