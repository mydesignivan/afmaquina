<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Productos extends MY_Controller {

    /* CONSTRUCTOR
     **************************************************************************/
    function __construct(){
        parent::MY_Controller();
        $this->load->model('contents_model');
        $this->load->model('products_model');

        $this->_data = array(
            'tlp_title' => TITLE_PRODUCTOS,
            'tlp_meta_keywords' => META_KEYWORDS_PRODUCTOS,
            'tlp_meta_descriptions' => META_DESCRIPTION_PRODUCTOS,
            'content_sidebar'   => $this->contents_model->get_sidebar()
        );
    }

    /* PRIVATE PROPERTIES
     **************************************************************************/

    /* PUBLIC FUNCTIONS
     **************************************************************************/
    public function index(){
        $total_segments = $this->uri->total_segments();
        if( $total_segments==1 ) redirect($this->config->item('base_url'));

        $info = $this->products_model->get_list_front($this->uri->segment($total_segments));
        if( !$info ) redirect($this->config->item('base_url'));

        $ref = $this->uri->segment(1,'index');
        $this->assets->add_css('view_products');
        $this->_render('front/products_view', array_merge($this->_data, array(
            'listProducts' => $info['listProducts'],
            'path_section' => $info['path_section']
        )));

    }

    public function leermas(){
        $info = $this->products_model->get_product($this->uri->segment(3));
        if( !$info ) redirect($this->config->item('base_url'));

        $this->assets->add_css('view_products');
        
        if( isset($info['gallery']) ){
            $this->assets->add_js_group('plugins_adgallery');
            $this->assets->add_js('ready/product_detail');
        }

        $this->_render('front/products_detail_view', array_merge($this->_data, array(
            'info'  => $info,
            'sidebar_def_seg1' => $info['segment1'],
            'sidebar_def_seg2' => $info['segment2']
        )));
    }

    public function search(){
        if( $_SERVER['REQUEST_METHOD']=="POST" ){
            $this->load->helper('text');
            $this->_render('front/products_resultsearch_view', array_merge($this->_data, array(
                'info'         => $this->products_model->search()
            )));
        }
    }


    /* AJAX FUNCTIONS
     **************************************************************************/

    /* PRIVATE FUNCTIONS
     **************************************************************************/

}