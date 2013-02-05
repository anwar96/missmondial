<?php
    class Story_model extends CI_Model {

        function __construct(){
            parent::__construct();
        }

        function insert_story($data_story){
            $this -> db -> insert('storys', $data_story);
            return $this -> db -> insert_id();
        }

        function getByAddress($address){
            $this->db->select('*');
            $this->db->from('storys');
            $this->db->join('users', 'user.id_user = storys.id_user');
            $this->db->where("alamat_ibu LIKE '%$address%'");
            $query = $this->db->get();
            return $query->result_array();
        }

        function getById($id_story){
            $this->db->select('*');
            $this->db->from('storys');
            $this->db->join('users', 'users.id_user = storys.id_user');
            $this->db->where("id_story", $id_story);
            $query = $this->db->get();
            return $query->row_array();
        }

        function check_insert($fb_uid){
            $this->db->select('*');
            $this->db->from('storys');
            $this->db->join('users', 'users.id_user = storys.id_user');
            $this->db->where('fb_uid', $fb_uid);
            $query = $this->db->get();
            return $query->num_rows();
        }

        function get_story(){
            $this->db->select('*');
            $this->db->from('storys');
            $this->db->join('users', 'users.id_user = storys.id_user');
            $query = $this->db->get();
            return $query->result_array();
        }

        function sortBydate($start, $end){
            $qry      = "SELECT * FROM storys s INNER JOIN users u ON s.id_user = u.id_user WHERE s.date_insert >= '".$start."' AND s.date_insert <= '".$end."' ORDER BY date_insert ASC";
            $query = $this->db->query($qry);
            return $query->result_array();   
        }

        function update_story($data_story, $id_story){
            $this->db->where('id_story', $id_story);
            $this->db->update('storys', $data_story);
        }
    }
?>