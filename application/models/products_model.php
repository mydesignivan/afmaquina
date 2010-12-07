<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Products_model extends Model {

    /* CONSTRUCTOR
     **************************************************************************/
    function  __construct() {
        parent::Model();
    }

    /* PUBLIC FUNCTIONS
     **************************************************************************/
    public function get_list_front($ref){
        $output = array();

        $where = is_numeric($ref) ? array('categories_id'=>$ref) : array('reference'=>$ref);

        $this->db->select('categorie_name, categorie_content, reference, parent_id, level, categories_id');
        $query = $this->db->get_where(TBL_CATEGORIES, $where);
        if( $query->num_rows==0 ) return false;
        $output = $query->row_array();

        $this->db->order_by('order', 'asc');
        $query = $this->db->get_where(TBL_PRODUCTS, array('categorie_reference'=>$output['reference']));
        $output['listProducts'] = $query->result_array();

        $this->_get_sidebar($output);

        return $output;
    }

    public function search(){
        $output = array();

        $this->db->select(TBL_PRODUCTS.'.*,'. TBL_CATEGORIES.'.categorie_name, '. TBL_CATEGORIES.'.categories_id');
        $this->db->from(TBL_PRODUCTS);
        $this->db->join(TBL_CATEGORIES, TBL_PRODUCTS.'.categorie_reference = '.TBL_CATEGORIES.'.reference');
        $this->db->like(TBL_CATEGORIES.'.categorie_name', $this->input->post('txtSearch'));
        $this->db->or_like(TBL_CATEGORIES.'.categorie_content', $this->input->post('txtSearch'));
        $this->db->or_like(TBL_PRODUCTS.'.product_name', $this->input->post('txtSearch'));
        $this->db->or_like(TBL_PRODUCTS.'.product_content', $this->input->post('txtSearch'));
        $this->db->order_by(TBL_CATEGORIES.'.`order`', 'asc');
        $this->db->order_by(TBL_PRODUCTS.'.`order`', 'asc');
        $query = $this->db->get();        
        $output['listProducts'] = $query->result_array();

        return $output;
    }


    /* PRIVATE FUNCTIONS
     **************************************************************************/
    private function _get_sidebar(&$output){
        // Extrae los contenidos hijos
        if( $output['level']==0 ){
            $this->db->select('categories_id as id, reference, categorie_name as title');
            $output['sidebar']['menu'] = $this->db->get_where(TBL_CATEGORIES, array('parent_id'=>$output['parent_id']))->result_array();

        }else{
            $this->db->select('parent_id');
            $row = $this->db->get_where(TBL_CATEGORIES, array('categories_id'=>$output['parent_id']))->row_array();
            $parent_id = $row['parent_id'];

            $this->db->select('categories_id as id, reference, categorie_name as title');
            $this->db->order_by('order', 'asc');
            $output['sidebar']['menu'] = $this->db->get_where(TBL_CATEGORIES, array('parent_id'=>$parent_id))->result_array();
        }
        for( $n=0; $n<=count($output['sidebar']['menu'])-1; $n++ ){
            $id = $output['sidebar']['menu'][$n]['id'];

            $this->db->select('categories_id as id, reference, categorie_name as title');
            $this->db->order_by('order', 'asc');
            $query = $this->db->get_where(TBL_CATEGORIES, array('parent_id'=>$id));
            if( $query->num_rows>0 ){
                $output['sidebar']['menu'][$n]['submenu'] = $query->result_array();
            }
        }

        //print_array($output['sidebar']['menu'], true);

        // Extra la galeria de imagenes
        $this->db->order_by('order', 'asc');
        $query = $this->db->get_where(TBL_GALLERY_PRODUCTS, array('categories_id'=>$output['categories_id']));
        if( $query->num_rows>0 ) $output['sidebar']['gallery'] = $query->result_array();
    }

    private function _get_path_section($ref, &$path=array()){
        $result = $this->db->get_where(TBL_CATEGORIES, array('reference'=>$ref))->row_array();

        $path[] = $result['categorie_name'];

        $query = $this->db->get_where(TBL_CATEGORIES, array('categories_id'=>$result['parent_id']));
        if( $query->num_rows>0 ) {
            $result = $query->row_array();
            $this->_get_path_section($result['reference'], $path);
        }

        return $path;
    }

}