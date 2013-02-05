<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {

    var $facebook = "";

    function __construct(){
        parent::__construct();
        $this->load->library(array('fb_connect','form_validation'));
        $this->load->model('places_model','', TRUE);
        $this->load->model('user_model', '', TRUE);
        $this->load->model('story_model', '', TRUE);
        $this->load->model('like_model', '', TRUE);
        $this->load->helper(array('form','url'));
        $this->config->load('facebook', TRUE);
        $this->facebook = $this->config->item('facebook');
        date_default_timezone_set("Asia/Jakarta");
    }

	function index()
	{
        $this -> check_publish_apps();
        $fb_uid = $this->fb_connect->authentication();
        $this->check_isFan();
        $data['content']    = 'home';
        $this->load->view('template', $data);
    }

    function register(){
        $this->check_publish_apps();
        $fb_uid = $this->fb_connect->authentication();
        $this->check_isFan();

        $check_register         = $this->check_regsitered_user($fb_uid);
        if ($check_register) $this->fb_connect->redirect($this->facebook['canvas_url']."insert_story");
        else{
            $user_data  = $this->user_data();
            $data['email']          = $user_data->email;
            $data['content']        = 'register';
            $this -> load -> view ('template', $data);
        }

    }

    function process_register(){
        $this -> check_publish_apps();
        $result = array();

        $fb_uid = $this->fb_connect->authentication('register');
        $this->check_isFan();
        
        $check_register         = $this->check_regsitered_user($fb_uid);
        if ($check_register) $this->fb_connect->redirect($this->facebook['canvas_url']."insert_story");
        else{
            $name       = addslashes(trim($_POST['name']));
            $email      = addslashes(trim($_POST['email']));
            $phone      = addslashes(trim($_POST['phone']));
            $address    = addslashes(trim($_POST['address']));
            $twitter    = addslashes(trim($_POST['twitter']));
            $checkTnc   = $_POST['check'];


            if ($name == "" || $twitter == "" || $email == "" || $phone == "" || $address == ""){
                $result['t']    = "error";
                $result['m']    = "Semua field harus diisi";
            }
            else{
                $valid_email 	= "^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$";
                if(!eregi($valid_email , $email))
                {
                    $result ["t"]	= "error";
                    $result ["m"]   = "Alamat email tidak benar";
                }
                elseif (!ctype_digit($phone)){
                    $result ["t"]	= "error";
                    $result ["m"]   = "Nomor telp tidak benar";
                }
                elseif ($checkTnc == "false") {
                    $result ["t"]   = "error";
                    $result ["m"]   = "Anda harus menyetujui Terms & Condistions";   
                }
                else
                {
                    $xtwit         = substr($twitter, 1);
                    if ($xtwit != "@") $twitter = "@".$twitter;
                    else $twitter = $twitter;

                    $member        = array('fb_uid'  => $fb_uid,'name'=> $name,'twitter_account'=> $twitter,'email'=> $email,'phone'=> $phone,'address'=> $address,'date_registered'=> date("Y-m-d H:i:s"));
                    $id            = $this -> user_model -> insert_user($member);
                    $this -> validation -> id = $id;
                }
            }
            echo json_encode($result);
        }
    }

    function tab(){
        $this->load->view("tab");
    }

    function end_page(){
        $this->load->view("like_page");
    }

    function end_campaign(){
        $this->load->view("end");
    }

    function tnc(){
        $this->load->view("tnc");
    }

    function insert_story(){
        $this -> check_publish_apps();
        $fb_uid = $this->fb_connect->authentication('insert_story');
        $this->check_isFan();
        $check_register         = $this->check_regsitered_user($fb_uid);
        if (!$check_register) $this->fb_connect->redirect($this->facebook['canvas_url']);
        else{
            $check_insertStory  = $this->check_insertStory($fb_uid);
            if ($check_insertStory) $this->fb_connect->redirect($this->facebook['canvas_url']."gallery");
            else{
                $data['places']     = $this->places_model->get_places();
                $data['content']    = "insert_story";
                $this->load->view('template', $data);
            }    
        }
    }

    function process_insert_story(){
        $this -> check_publish_apps();
        $result = array();
        $fb_uid = $this->fb_connect->authentication('insert_story');
        $this->check_isFan();
        $check_register         = $this->check_regsitered_user($fb_uid);
        if (!$check_register) $this->fb_connect->redirect($this->config->item('canvas_url'));
        else{
            $data_user          = $this->user_model->get_id_user($fb_uid);
            $id_user            = $data_user['id_user'];
            $endorsed_person    = addslashes(trim($_POST['endorsed_person']));
            $place              = addslashes(trim($_POST['suggest_place']));
            $reason             = addslashes(trim($_POST['reason']));

            if ($endorsed_person == "" || $place == "" || $reason == ""){
                $result['t']    = "error";
                $result['m']    = "Semua field harus diisi";
            }
            else{
                $datastory      = array('id_user'  => $id_user,'endorsed_person'=> $endorsed_person,'suggest_places'=> $place, 'reason'=> $reason, 'total_like'=> '0','date_insert'=> date("Y-m-d H:i:s"), 'status' => '0');
                $id             = $this -> story_model -> insert_story($datastory);
                $this -> validation -> id = $id;
                $user_data  = $this->user_data();
                $this->fb_connect->auto_publish($user_data->name);
            }
            echo json_encode($result);
        }
    }

    function gallery(){
        $this->check_publish_apps();
        $fb_uid = $this->fb_connect->authentication('gallery');
        $this->check_isFan();

        $data['storys']     = $this->story_model->get_story();
        $data['content']    = "gallery";
        $this->load->view("template", $data);

        $data_session = array('signed_request' => $_POST["signed_request"]);
        $this->session->set_userdata($data_session); 

    }

    function gallery_detail($id){
        $id_story = $id;
        $data['story']  = $this->story_model->getById($id_story);
        $data['signed_request'] = $this->session->userdata('signed_request');
        $data['content']= "detail";     
        $this->load->view('template', $data);
    }

    function process_like(){
        $this->check_publish_apps();
        $fb_uid = $this->fb_connect->authentication('gallery');
        $this->check_isFan();

        $result     = array();
        $id_story   = $_POST['idx'];
        $now        = date("Y-m-d H:i:s");
        $data_like  = $this->like_model->checkTimeLike($fb_uid, $id_story, $now);
        if (!empty($data_like)){
            if ($data_like['timedif'] < "00:15:00"){
                $result['t']    = "error";
                $result['m']    = "Kamu tercatat baru saja me-like, tunggu beberapa saat lagi";
            }
            else {
                $datalike           = array('fb_uid' => $fb_uid, 'id_story' => $id_story, 'date_like' => $now);
                $id                 = $this->like_model->insert_like($datalike);
                $result['id_like']  = $this -> validation -> id = $id;    
                $result['t']        = "success";
                $result['m']        = "";
            }
        }
        else{
            $datalike           = array('fb_uid' => $fb_uid, 'id_story' => $id_story, 'date_like' => $now);
            $id                 = $this->like_model->insert_like($datalike);
            $result['id_like']  = $this -> validation -> id = $id;
            $result['']         = "success";
            $result['m']        = "";
        }
        $total_like         = $data_like['total_like'] + 1;
        $data_story         = array('total_like' => $total_like);
        $this->story_model->update_story($data_story, $id_story);
        echo json_encode($result);
    }

    function check_regsitered_user($fb_uid){
        $num    = $this->user_model->check_register($fb_uid);
        if ($num == 0) return false;
        else return true;
    }

    function check_insertStory($fb_uid){
        $num    = $this->story_model->check_insert($fb_uid);
        if ($num == 0) return false;
        else return true;   
    }

    function check_publish_apps(){
        $o_nowdate = new DateTime(date("Y-m-d H:i:s"));
        $nowTime = $o_nowdate->getTimestamp();

        $o_enddate = new DateTime("2013-01-30 23:59:59");
        $endTime = $o_enddate->getTimestamp();
        if ($endTime < $nowTime) $this->fb_connect->redirect($this->facebook['canvas_url']."end_campaign");
    }

    function check_isFan(){
        $fan    = $this->fb_connect->check_isFan();
        if (!$fan) $this->fb_connect->redirect($this->facebook['landing_page']);
    }

    function user_data(){
        $user_data  = $this->fb_connect->user_data();
        return $user_data[0];
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */