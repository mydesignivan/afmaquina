<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Products extends MY_Controller {

    /* CONSTRUCTOR
     **************************************************************************/
    function __construct(){
        parent::MY_Controller();

        if( !$this->session->userdata('logged_in') ) redirect('panel');
        
        $this->load->model("products_panel_model");
        $this->load->model("categories_model");
    }

    /* PUBLIC FUNCTIONS
     **************************************************************************/
    public function index(){
        $this->assets->add_js_group(array('plugins_treeview', 'plugins_validate', 'plugins_fancybox', 'helpers_json'));
        $this->assets->add_js(array('plugins/picturegallery/picturegallery.min', 'class/products'));
        $this->assets->add_js('plugins/jquery-ui.sortable/jquery-ui-1.8.2.custom.min', false);
        $this->assets->add_js_group('plugins_tiny_mce', false);

        $this->_render('panel/products_view', array(
            'tlp_title'          =>  TITLE_INDEX,
            'tlp_title_section'  => "Productos",
            'treeview'           =>  $this->categories_model->get_treeview()
        ), 'panel_view');
    }


    /* AJAX FUNCTIONS
     **************************************************************************/
     public function ajax_form_categorie(){
         $data = array();
         if( is_numeric($this->uri->segment(4)) ){
             $data['info'] = $this->categories_model->get_info($this->uri->segment(4));
         }
         $this->_render('panel/ajax/categorie_form_view', $data, 'ajax_view');
     }
     
     public function ajax_form_products(){
         $data = array('reference' => $this->categories_model->get_reference($this->uri->segment(4)));

         if( is_numeric($this->uri->segment(5)) ){
             $data['info'] = $this->products_panel_model->get_info($this->uri->segment(5));
         }         
         $this->_render('panel/ajax/products_form_view', $data, 'ajax_view');
     }
     
     public function ajax_list_products(){
         $this->load->helper('text');
         $data = array(
             'List' => $this->products_panel_model->get_list($this->uri->segment(4))
         );
         $this->_render('panel/ajax/products_list_view', $data, 'ajax_view');
     }

     public function ajax_categories_create(){
        if( $_SERVER['REQUEST_METHOD']=="POST" ){
            echo($this->categories_model->create());
            die();
        }
     }

     public function ajax_categories_edit(){
        if( $_SERVER['REQUEST_METHOD']=="POST" ){
            echo($this->categories_model->edit());
            die();
        }
     }

     public function ajax_categories_del(){
        if( $_SERVER['REQUEST_METHOD']=="POST" ){
            echo($this->categories_model->delete($this->uri->segment(4)) ? "ok" : "error");
            die();
        }
     }

     public function ajax_categories_order(){
        die($this->categories_model->order() ? "success" : "error");
     }

     public function ajax_products_create(){
        if( $_SERVER['REQUEST_METHOD']=="POST" ){
            echo($this->products_panel_model->create());
            die();
        }
     }

     public function ajax_products_edit(){
        if( $_SERVER['REQUEST_METHOD']=="POST" ){
            echo($this->products_panel_model->edit());
            die();
        }
     }

     public function ajax_products_del(){
        if( $_SERVER['REQUEST_METHOD']=="POST" ){
            $id = $this->uri->segment_array();
            array_splice($id, 0,3);
            echo($this->products_panel_model->delete($id) ? "ok" : "error");
            die();
        }
     }

     public function ajax_products_order(){
        die($this->products_panel_model->order() ? "success" : "error");
     }

     public function ajax_show_treeview(){
        die($this->categories_model->get_treeview());
     }

    public function ajax_upload_gallery(){
        if( $_SERVER['REQUEST_METHOD']=="POST" ){

            $this->load->library('superupload');

            $config = array(
                'path'            => UPLOAD_PATH_GALLERY.'.tmp/',
                'thumb_width'     => IMAGESIZE_WIDTH_THUMB_GALLERY,
                'thumb_height'    => IMAGESIZE_HEIGHT_THUMB_GALLERY,
                'image_width'     => IMAGESIZE_WIDTH_FULL_GALLERY,
                'image_height'    => IMAGESIZE_HEIGHT_FULL_GALLERY,
                'maxsize'         => UPLOAD_MAXSIZE,
                'filetype'        => UPLOAD_FILETYPE,
                'filename_prefix' => $this->session->userdata('users_id')."_",
                'master_dim'      => 'height',
                'thumb_maintain_ratio' => true
            );
            $this->superupload->initialize($config);
            echo json_encode($this->superupload->upload('txtUploadFile'));
            die();
        }
    }

    public function ajax_upload_delete(){
        if( $_SERVER['REQUEST_METHOD']=="POST" ){
            @unlink($this->input->post('au_filename_image'));
            @unlink($this->input->post('au_filename_thumb'));
            die("ok");
        }
    }


    /* PRIVATE FUNCTIONS
     **************************************************************************/
}
