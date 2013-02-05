<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of General
 *
 * @author gieart
 */
class General {

    //put your code here
    var $ci;

    function __construct() {
        $this->ci = &get_instance();
    }

    function isLogin() {
        if ($this->ci->session->userdata('is_login') == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function isAdmin() {
        if ($this->ci->session->userdata('type') == 'admin') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function isTeacher() {
        if ($this->ci->session->userdata('type') == 'teachers') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function isStudent() {
        if ($this->ci->session->userdata('type') == 'students') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function isAlumni() {
        if ($this->ci->session->userdata('type') == 'alumni') {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function checkAdmin() {
        if (($this->isLogin() && $this->isAdmin()) != TRUE) {
            $this->ci->session->set_flashdata('error', 'Maaf, Anda tidak memiliki hak akses sebagai admin');
            redirect('users/login');
        }
    }

    function checkTeacher() {
        if (($this->isLogin() && $this->isTeacher()) != TRUE) {
            $this->ci->session->set_flashdata('error', 'Maaf, Anda tidak memiliki hak akses sebagai guru');
            redirect('users/login');
        }
    }

    function checkStudent() {
        if (($this->isLogin() && $this->isStudent()) != TRUE) {
            $this->ci->session->set_flashdata('error', 'Maaf, Anda tidak memiliki hak akses sebagai siswa');
            redirect('users/login');
        }
    }

    function checkAlumni() {
        if (($this->isLogin() && $this->isAlumni()) != TRUE) {
            $this->ci->session->set_flashdata('error', 'Maaf, Anda tidak memiliki hak akses sebagai alumni');
            redirect('users/login');
        }
    }

}

?>
