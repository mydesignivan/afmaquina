<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Index extends MY_Controller {

    /* CONSTRUCTOR
     **************************************************************************/
    function __construct(){
        parent::MY_Controller();
        $this->load->model('contents_model');
    }

    /* PRIVATE PROPERTIES
     **************************************************************************/

    /* PUBLIC FUNCTIONS
     **************************************************************************/
    public function index(){
        $ref = $this->uri->segment(1,'index');
        $view = 'front/content_view';
        $data = array();

        switch ($ref){
            case 'index': case '':
                $data['tlp_title'] = TITLE_INDEX;
                $data['tlp_keywords'] = META_KEYWORDS_INDEX;
                $data['tlp_descriptions'] = META_DESCRIPTION_INDEX;
                $view = "front/home_view";
                $this->assets->add_css('view_home');
            break;
            case 'nosotros':
                $data['tlp_title'] = TITLE_NOSOTROS;
                $data['tlp_keywords'] = META_KEYWORDS_NOSOTROS;
                $data['tlp_descriptions'] = META_DESCRIPTION_NOSOTROS;
            break;
            case 'productos':
                $data['tlp_title'] = TITLE_PRODUCTOS;
                $data['tlp_keywords'] = META_KEYWORDS_PRODUCTOS;
                $data['tlp_descriptions'] = META_DESCRIPTION_PRODUCTOS;
            break;
            case 'catalogo':
                $data['tlp_title'] = TITLE_CATALOGO;
                $data['tlp_keywords'] = META_KEYWORDS_CATALOGO;
                $data['tlp_descriptions'] = META_DESCRIPTION_CATALOGO;
                $this->assets->add_css('view_catalog');
            break;
            case 'videos':
                $this->load->model('movies_model');

                $data['tlp_title'] = TITLE_VIDEOS;
                $data['tlp_keywords'] = META_KEYWORDS_VIDEOS;
                $data['tlp_descriptions'] = META_DESCRIPTION_VIDEOS;
                $data['list'] = $this->movies_model->get_list();
                $view = 'front/movies_view';
                $this->assets->add_css('view_movie');
            break;
            case 'contacto':
                $data['tlp_title'] = TITLE_CONTACTO;
                $data['tlp_keywords'] = META_KEYWORDS_CONTACTO;
                $data['tlp_descriptions'] = META_DESCRIPTION_CONTACTO;
            break;
        }

        $this->_render($view, array_merge($data, array(
            'content_sidebar'   => $this->contents_model->get_sidebar(),
            'content' => $this->contents_model->get_content($ref)
        )));
    }

    /* AJAX FUNCTIONS
     **************************************************************************/

    /* PRIVATE FUNCTIONS
     **************************************************************************/

}