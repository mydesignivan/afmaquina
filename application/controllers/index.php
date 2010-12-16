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
        switch ($ref){
            case 'index': case '':
                $tlp_title = TITLE_INDEX;
                $tlp_keywords = META_KEYWORDS_INDEX;
                $tlp_descriptions = META_DESCRIPTION_INDEX;
            break;
            case 'nosotros':
                $tlp_title = TITLE_NOSOTROS;
                $tlp_keywords = META_KEYWORDS_NOSOTROS;
                $tlp_descriptions = META_DESCRIPTION_NOSOTROS;
            break;
            case 'productos':
                $tlp_title = TITLE_PRODUCTOS;
                $tlp_keywords = META_KEYWORDS_PRODUCTOS;
                $tlp_descriptions = META_DESCRIPTION_PRODUCTOS;
            break;
            case 'catalogo':
                $tlp_title = TITLE_CATALOGO;
                $tlp_keywords = META_KEYWORDS_CATALOGO;
                $tlp_descriptions = META_DESCRIPTION_CATALOGO;
            break;
            case 'videos':
                $tlp_title = TITLE_VIDEOS;
                $tlp_keywords = META_KEYWORDS_VIDEOS;
                $tlp_descriptions = META_DESCRIPTION_VIDEOS;
            break;
            case 'contacto':
                $tlp_title = TITLE_CONTACTO;
                $tlp_keywords = META_KEYWORDS_CONTACTO;
                $tlp_descriptions = META_DESCRIPTION_CONTACTO;
            break;
        }

        $this->_render('front/content_view', array(
            'tlp_title' => $tlp_title,
            'tlp_meta_keywords' => $tlp_keywords,
            'tlp_meta_descriptions' => $tlp_descriptions,
            'content' => $this->contents_model->get_content($ref)
        ));
    }

    /* AJAX FUNCTIONS
     **************************************************************************/

    /* PRIVATE FUNCTIONS
     **************************************************************************/

}