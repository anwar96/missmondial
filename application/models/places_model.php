<?php
    class Places_model extends CI_Model {

        function __construct(){
            parent::__construct();
        }

        function insert_gallery($data_place){
            $this -> db -> insert('suggest_places', $data_place);
            return $this -> db -> insert_id();
        }

        function get_places(){
            $this->db->select('*');
            $this->db->from('suggest_places');
            $this->db->where("status","0");
            $query = $this->db->get();
            return $query->result_array();
        }

        function delete_gallery($id){
            $this->db->delete('gallery', array('id_gallery' => $id));
        }

        function update_gallery($data_gallery, $id_gallery){
            $this->db->where('id_gallery', $id_gallery);
            $this->db->update('gallery', $data_gallery);
        }
    }
?>