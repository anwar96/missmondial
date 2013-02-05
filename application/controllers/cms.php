<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Cms extends CI_Controller {

    private $limit  = 20;
    private $model  = "";
    private $callback_dir   = "";
    private $facebook = "";

    function __construct() {
        parent::__construct();
        $this->load->model('admin_model');
        $this->load->model('user_model');
        $this->load->model('story_model');
        //$this->load->model('gallery_model');
        $this -> load -> library(array('table', 'form_validation', 'pagination'));
        $this->load->helper(array('form', 'url', 'html'));
        $this->config->load('facebook', TRUE);
        $this->facebook = $this->config->item('facebook');
        $this->callback_dir = $this->facebook['callback_dir'];
    }

    function index() {
        redirect(site_url('cms/login'));
    }

    function login() {
        $this->form_validation->set_rules('username', 'username', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'password', 'required|xss_clean');

        $this->form_validation->set_error_delimiters('', '<br/>');

        if ($this->form_validation->run() == TRUE) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $login_data = $this->admin_model->cek_user_login($username, $password);
            if ($login_data) {
                $session_data = array(
                    'id' => $login_data['id'],
                    'username' => $login_data['username'],
                    'type' => $login_data['type'],
                    'is_login' => TRUE
                );

                $this->session->set_userdata($session_data);
                if ($this->session->userdata('type') == 'admin') {
                    redirect('cms/home/');
                } else {
                    redirect('users/dashboard');
                }
            } else {
                $this->session->set_flashdata('message', 'Login Gagal, Kombinasi username dan password salah.');
                redirect('cms/login');
            }
        }
        $this->load->view('cms/login');
    }

    function home() {
        $this->check_logged_in();
        $data['content']    = "cms/home";
        $this->load->view('cms/template', $data);
    }

    function gallery(){
        $this->check_logged_in();
        $data['datagallerys']  = $this->gallery_model->get_gallery();
        $data['content']    = "cms/gallery";
        $this->load->view('cms/template', $data);
    }

    function deleteimage($id){
        $this->check_logged_in();
        $id_gallery = $id;
        $this->gallery_model->delete_gallery($id_gallery);
        unlink($this->callback_dir."images/gallery/".$id_gallery.".jpg");
        redirect(site_url('cms/gallery'));
    }

    function updateimage(){
        $this->check_logged_in();
        $id_gallery = addslashes(trim($_POST['id_gallery']));
        $title      = addslashes(trim($_POST['title']));
        $img        = addslashes(trim($_POST['image']));

        if ($title == "" || $img == ""){
            $result['t']    = "error";
            $result['m']    = "All Field is required";
        }
        else{
            $gallery       = array('title'  => $title);
            $this -> gallery_model -> update_gallery($gallery, $id_gallery);
            $newImgName     = md5($id_gallery);
            $old_dir        = $this->callback_dir."/uploaded/temporary/".$img;
            $new_dir        = $this->callback_dir."/uploaded/gallery/".$newImgName.".jpg";

            rename($old_dir, $new_dir);

            $result['t']    = "success";
            $result['m']    = "";
        }
        echo json_encode($result);
    }

    function editgallery($id){
        $this->check_logged_in();
        $id_gallery = $id;
        $data['gallery']    = $this->gallery_model->getById($id_gallery);
        $data['content']    = "cms/edit_gallery";
        $this->load->view('cms/template', $data);
    }

    function addgallery(){
        $this->check_logged_in();
        $data['content']    = "cms/add_gallery";
        $this->load->view('cms/template', $data);
    }

    function do_upload() {
        $config['upload_path'] = './uploaded/temporary/';
        $config['allowed_types'] = 'jpg|png';
        $config['max_size'] = '100';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $config['overwrite']  = TRUE;

        $name = $_FILES['userfile']['name']; // get file name from form
        $fileNameParts = explode('.', $name); // explode file name to two part
        $default_name = $fileNameParts[0]; // get raw name from client
        $config['file_name'] = $default_name; //constructing a new name

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());
            $this->generate_result(0, $error['error']);
        } else {
            $data   = array('upload_data' => $this->upload->data());
            $url    = base_url('uploaded/temporary/'.$name);
            $this->generate_result(1,$url);
        }
    }

    function generate_result($t, $m) {
        if($t == 0) {
            ?>
        <script type="text/javascript">
            parent.show_message("<?PHP echo $m ?>");
        </script>
        <?PHP
        }

        else {
            ?>
        <script type="text/javascript">
            parent.refresh_image("<?PHP echo $m ?>");
        </script>
        <?PHP
        }
    }

    function save_image(){
        $this->check_logged_in();
        $title  = addslashes(trim($_POST['title']));
        $img    = addslashes(trim($_POST['image']));

        if ($title == "" || $img == ""){
            $result['t']    = "error";
            $result['m']    = "All Field is required";
        }
        else{
            $date          = date('Y-m-d H:i:s');
            $gallery       = array('title'  => $title,'date_uploaded'=> $date);
            $id            = $this -> gallery_model -> insert_gallery($gallery);
            $this -> validation -> id = $id;
            $newImgName     = md5($id);
            $old_dir        = $this->callback_dir."/uploaded/temporary/".$img;
            $new_dir        = $this->callback_dir."/uploaded/gallery/".$newImgName.".jpg";
            $move           = rename($old_dir, $new_dir);

            if ($move){
                $result['t']    = "success";
                $result['m']    = "";
            }
        }

        echo json_encode($result);
    }

    function datauser(){
        $this->check_logged_in();
        if (isset($_POST['submit-date'])){
            $start  = $_POST['start'];
            $end    = $_POST['end'];
            $data['datausers']  = $this->user_model->sortByDate($start, $end);
        }
        else{
            $data['datausers']  = $this->user_model->get_user();
        }
        
        $data['content']    = "cms/user";
        $this -> load -> view('cms/template', $data);
    }

    function detailuser(){
        $this->check_logged_in();
        $id = trim($_POST['id']);
        $data_user  = $this->user_model->getById($id);
        $result     = array();
        $result['fb_uid']   = $data_user['fb_uid'];
        $result['name']     = $data_user['name'];
        $result['twitter']  = $data_user['twitter_account'];
        $result['email']    = $data_user['email'];
        $result['phone']    = $data_user['phone'];
        $result['address']  = $data_user['address'];
        $result['date']     = $data_user['date_registered'];

        echo json_encode($result);
    }

    function datastory(){
        $this->check_logged_in();
        if (isset($_POST['submit'])){
            $start  = $_POST['start'];
            $end    = $_POST['end'];
            $data['datastorys']  = $this->story_model->sortByDate($start, $end);
        }
        else{
            $data['datastorys']  = $this->story_model->get_story();   
        }
        $data['content']    = "cms/story";
        $this -> load -> view('cms/template', $data);
    }

    function detailstory(){
        $this->check_logged_in();
        $id_story   = $_POST['id'];
        if (!empty($id_story)){
            $data_story = $this->story_model->getById($id_story);
            $result['fb_uid']       = $data_story['fb_uid'];
            $result['name']         = $data_story['name'];
            $result['endorse']      = $data_story['endorsed_person'];
            $result['place']        = $data_story['suggest_places'];
            $result['ttl_like']     = $data_story['total_like'];
            $result['date']         = $data_story['date_insert'];
            $result['reason']       = $data_story['reason'];
            $result['t']            = 'success';
        }
        echo json_encode($result);
    }

    function logout() {

        $data = array
        (
            'user_id' => 0,
            'username' => 0,
            'type' => 0,
            'is_login' => FALSE
        );

        $this->session->sess_destroy();
        $this->session->unset_userdata($data);

        redirect('cms/login');
    }

    public function check_logged_in() {
        if ($this->session->userdata('is_login') != TRUE) {
            redirect('cms/login', 'refresh');
            exit();
        }
    }

    public function is_logged_in() {
        if ($this->session->userdata('logged_in') == TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function export_user($search){
        $data['datausers'] = $this->user_model->get_user();
        $this->load->view('cms/excel_view',$data);
    }

    function export_story($search){
        $data['datastorys']  = $this->story_model->get_story();
        $this->load->view('cms/excel_story',$data);
    }

}

?>
