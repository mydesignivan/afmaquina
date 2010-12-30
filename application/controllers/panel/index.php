<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Index extends MY_Controller {

    /* CONSTRUCTOR
     **************************************************************************/
    function __construct(){
        parent::MY_Controller();
        $this->load->library("simplelogin");
        $this->load->model('contents_model');
    }

    /* PUBLIC FUNCTIONS
     **************************************************************************/
    public function index(){
        //echo $this->encpss->encode('')."<br>";

        if( $this->session->userdata('logged_in') ) {
            redirect('/panel/myaccount/');
        }else{
            $this->_render('panel/login_view', array(
                'tlp_title' => TITLE_INDEX,
                'tlp_meta_keywords' => '',
                'tlp_meta_descriptions' => '',
                'content_sidebar'   => $this->contents_model->get_sidebar()
            ));
        }
    }

    public function login(){
        if( $_SERVER['REQUEST_METHOD']=="POST" ){
            $statusLogin = $this->simplelogin->login($this->input->post("txtUser"), $this->input->post("txtPass"));
            
            if( $statusLogin['status']=="error" ){
                if( $statusLogin['error']=="loginfaild" ){
                    $message = "El usuario y/o password son incorrectos.";
                }
                $this->session->set_flashdata('message_login', $message);
                redirect('/panel/');

            }else{
                redirect('/panel/myaccount/');
            }
        }
    }

    public function logout(){
        $this->simplelogin->logout();
        redirect($this->config->item('base_url'));
    }


    /* AJAX FUNCTIONS
     **************************************************************************/

    /* PRIVATE FUNCTIONS
     **************************************************************************/
}