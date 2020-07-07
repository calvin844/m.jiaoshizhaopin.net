<?php

class Ad_models extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    //根据广告位编号获取广告
    public function get_ad_by_alias($alias) {
        $this->db->where('alias', $alias);
        $query = $this->db->get('ad');
        return $query->result_array();
    }

    //根据全部广告位
    function get_ad() {
        $this->db->where('deadline >', time());
        $this->db->or_where('deadline', 0);
        $this->db->order_by('addtime', 'desc');
        $query = $this->db->get('ad');
        $ad = $query->result_array();
        foreach ($ad as $a) {
            $result[$a['alias']][] = $a;
        }
        return $result;
    }

}
