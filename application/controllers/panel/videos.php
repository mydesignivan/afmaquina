<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Videos extends MY_Controller {

    /* CONSTRUCTOR
     **************************************************************************/
    function __construct(){
        parent::MY_Controller();
        if( !$this->session->userdata('logged_in') ) redirect('/panel/');
        
        $this->load->model("movies_model");
    }

    /* PUBLIC FUNCTIONS
     **************************************************************************/
    public function index(){
        $this->load->library('user_agent');

        // CARGA LOS JS
        $this->assets->add_js('class/movies');
        $this->assets->add_js('plugins/jquery-ui.sortable/jquery-ui-1.8.2.custom.min', false);
        $this->assets->add_js('plugins/simplemodal/js/jquery.simplemodal');
        $this->assets->add_js_group(array('plugins_validate', 'helpers_json'));

        // CARGA LOS CSS
        if( $this->agent->browser()=="Internet Explorer" ){
            if( $this->agent->version()>=6 ){
                $this->assets->add_css_default(array('plugins_simplemodalie6'));
            }elseif( $this->agent->version()==7 ){
                $this->assets->add_css_default(array('plugins_simplemodalie7'));
            }
        }else{
            $this->assets->add_css_default(array('plugins_simplemodal'));
        }
        $this->assets->add_css('view_movie');

        // ABRE LA VISTA
        $this->_render('panel/movies_view', array(
            'tlp_title'              => TITLE_INDEX,
            'tlp_title_section'   => "Videos",
            'list'                     => $this->movies_model->get_list()
        ), 'panel_view');
    }

    public function delete(){
        if( is_numeric($this->uri->segment(4)) ){
            $id = $this->uri->segment_array();
            array_splice($id, 0, 3);

            if( !$this->movies_model->delete($id) ){
                $this->template->set_message('No se pudo eliminar el video.', 'error');
            }
            redirect('/panel/videos/');
        }
    }

    /* AJAX FUNCTIONS
     **************************************************************************/
    public function ajax_loadwin(){
        if( $this->uri->segment(4)=="edit" ){
            $info = $this->movies_model->get_info($this->uri->segment(5));
            $data = array(
                'title' => "Editar - ".$info['title'],
                'info' => $info
            );
        }else{
            $data = array(
                'title' => "Nuevo"
            );
        }
        
        $this->_render('panel/ajax/movie_form_view', $data,'ajax_view');
    }

    public function ajax_create(){
        if( $_SERVER['REQUEST_METHOD']=="POST" ){
            die($this->movies_model->create() ? "ok" : "error");
        }
    }

    public function ajax_edit(){
        if( $_SERVER['REQUEST_METHOD']=="POST" ){
            die($this->movies_model->edit() ? "ok" : "error");
        }
    }

    public function ajax_order(){
        die($this->movies_model->order() ? "success" : "error");
    }


    /* PRIVATE FUNCTIONS
     **************************************************************************/
}

?>