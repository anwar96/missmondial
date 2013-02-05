<?php
    class Like_model extends CI_Model {
        
        private $table = "likes";

        function __construct(){
            parent::__construct();
        }

        function count_by_fb_uid($fb_uid) {
            $this->db->select('*');
            $this->db->where('fb_uid', $fb_uid);
            $query = $this->db->get($this->table, 1);
            return $query->num_rows();
        }

        function checkTimeLike($fb_uid, $id_story, $now){
            $this->db->select(array('id_like', 'date_like', 'fb_uid', 'date_like', 'TIMEDIFF("'.$now.'",date_like) AS timedif'));
            $this->db->order_by ('date_like', 'desc');
            $query = $this->db->get_where($this->table, array('id_story' => $id_story, 'fb_uid' => $fb_uid), 1, 0);
            return $query->row_array();
        }

        function insert_like($data_like){
            $this -> db -> insert($this -> table, $data_like);
            return $this -> db -> insert_id();
        }

        function get_allow($fb_uid){
            $this->db->select('*');
            $this->db->where('fb_uid', $fb_uid);
            $query = $this->db->get($this->table, 1);
            return $query->row_array();
        }

    }
?>