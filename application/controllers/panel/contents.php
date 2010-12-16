<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Contents extends MY_Controller {

    /* CONSTRUCTOR
     **************************************************************************/
    function __construct(){
        parent::MY_Controller();
        if( !$this->session->userdata('logged_in') ) redirect('/panel/');
        
        $this->load->model("contents_model");
    }

    /* PUBLIC FUNCTIONS
     **************************************************************************/
    public function index(){
        $this->assets->add_js('class/contents');
        $this->assets->add_js_group(array('plugins_tiny_mce'), false);
        $this->assets->add_js('plugins/jquery-ui.sortable/jquery-ui-1.8.2.custom.min', false);
        $this->_render('panel/contents_view', array(
            'tlp_title'           => TITLE_INDEX,
            'tlp_title_section'   => "Contenidos",
            'listPages'           => $this->contents_model->get_list()
        ), 'panel_view');
    }

    /* AJAX FUNCTIONS
     **************************************************************************/
    public function ajax_save(){
        if( $_SERVER['REQUEST_METHOD']=="POST" ){
            die($this->contents_model->save() ? "success" : "error");
        }
    }

    /* PRIVATE FUNCTIONS
     **************************************************************************/
}

?>