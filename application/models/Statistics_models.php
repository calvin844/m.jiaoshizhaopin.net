<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Statistics_models extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function add_statistics_all($name) {
        $this->db->set('total_count', 'total_count+1');
        $this->db->set('new_count', 'new_count+1');
        $this->db->where('name', $name);
        $this->db->update('jiaoshi_statistics_all');
    }

    function del_statistics_all($name) {
        $this->db->set('total_count', 'total_count-1');
        $this->db->set('new_count', 'new_count-1');
        $this->db->where('name', $name);
        $this->db->update('jiaoshi_statistics_all');
    }

    function add_statistics_day($name) {
        $date = date('Y-m-d', time());
        $this->db->where('name', $name);
        $this->db->where('date', $date);
        $query = $this->db->get('jiaoshi_statistics_day');
        $statistics_day = $query->row_array();
        if (empty($statistics_day)) {
            $data = array('name' => $name, 'count' => 1, 'date' => $date);
            $this->db->insert('jiaoshi_statistics_day', $data);
        } else {
            $this->db->where('name', $name);
            $this->db->where('date', $date);
            $data = array('count' => ($statistics_day['count'] + 1));
            $this->db->update('jiaoshi_statistics_day', $data);
        }
    }

}
