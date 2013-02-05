<?php
    class User_model extends CI_Model {

        function __construct(){
            parent::__construct();
        }

        
        function insert_user($data_user){
            $this -> db -> insert('users', $data_user);
            return $this -> db -> insert_id();
        }

        function check_register($fb_uid){
            $this->db->select('id_user');
            $this->db->from('users');
            $this->db->where('fb_uid', $fb_uid);
            $query = $this->db->get();
            return $query->num_rows();
        }

        function get_id_user($fb_uid){
            $this->db->select('id_user');
            $this->db->from('users');
            $this->db->where('fb_uid', $fb_uid);
            $query = $this->db->get();
            return $query->row_array();
        }

        function get_user(){
            $query = $this->db->get('users');
            return $query->result_array();
        }

        function get_paged_list($limit = 10, $offset = 0, $order_column = '', $order_type = 'asc')
        {
            if (empty($order_column) || empty($order_type))
                $this -> db -> order_by ('id_user', 'asc');
            else $this -> db -> order_by ($order_column, $order_type);

            return $this -> db -> get('users', $limit, $offset);
        }

        function count_all(){
            return $this -> db -> count_all('users');
        }

        function getByAddress($address){
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where("address LIKE '%$address%'");
            $query = $this->db->get();
            return $query->result_array();
        }

        function getById($id_user){
            $qry      = "SELECT fb_uid, id_user, name, email, phone, address, twitter_account, date_registered
                        FROM users WHERE id_user = '".$id_user."'";
            $query = $this->db->query($qry);
            return $query->row_array();
        }

        function sortBydate($start, $end){
            $qry      = "SELECT * FROM users WHERE date_registered >= '".$start."' AND date_registered <= '".$end."' ORDER BY date_registered ASC";
            $query = $this->db->query($qry);
            return $query->result_array();   
        }

    }
?>