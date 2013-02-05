<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin_model extends CI_Model {

    var $tabel_name = 'user_admin';

    function __construct() {
        parent::__construct();
    }

    function cek_user_login($username, $password) {
        $this->db->select('*');
        $this->db->where('username', $username);
        $this->db->where('password', md5($password));

        $query = $this->db->get($this->tabel_name, 1);

        if ($query->num_rows() == 1) {
            return $query->row_array();
        }
    }

}

?>
