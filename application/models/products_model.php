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

        $this->db->select('categorie_name, categorie_content, reference, parent_id');
        $query = $this->db->get_where(TBL_CATEGORIES, array('reference'=>$ref));
        if( $query->num_rows==0 ) return false;
        $output = $query->row_array();

        $this->db->order_by('order', 'asc');
        $query = $this->db->get_where(TBL_PRODUCTS, array('categorie_reference'=>$ref));
        $output['listProducts'] = $query->result_array();

        $output['childs'] = $this->_get_childs($output['parent_id']);

        return $output;
    }

    public function get_product($ref){
        $this->db->select(TBL_CATEGORIES.'.parent_id as categorie_parent_id, '.TBL_PRODUCTS.'.*', true);
        $this->db->from(TBL_PRODUCTS);
        $this->db->where(TBL_PRODUCTS.'.reference', $ref);
        $this->db->join(TBL_CATEGORIES, TBL_PRODUCTS.'.categorie_reference = '.TBL_CATEGORIES.'.reference');
        $query = $this->db->get();
        if( $query->num_rows==0 ) return false;
        $result = $query->row_array();

        $result['path_section'] = $this->_get_path_section($result['categorie_reference']);
        $result['path_section'] = array_reverse($result['path_section']);
        $result['path_section'] = implode(" &gt; ", $result['path_section'])." &gt; ".$result['product_name'];

        $result['childs'] = $this->_get_childs($result['categorie_parent_id']);
        return $result;
    }

    public function search(){
        $output = array();

        $this->db->select(TBL_PRODUCTS.'.*,'. TBL_CATEGORIES.'.categorie_name,'. TBL_CATEGORIES.'.categorie_content');
        $this->db->from(TBL_PRODUCTS);
        $this->db->join(TBL_CATEGORIES, TBL_PRODUCTS.'.categorie_reference = '.TBL_CATEGORIES.'.reference');
        $this->db->like(TBL_CATEGORIES.'.categorie_name', $this->input->post('txtSearch'));
        $this->db->or_like(TBL_CATEGORIES.'.categorie_content', $this->input->post('txtSearch'));
        $this->db->or_like(TBL_PRODUCTS.'.product_name', $this->input->post('txtSearch'));
        $this->db->or_like(TBL_PRODUCTS.'.description', $this->input->post('txtSearch'));
        $this->db->or_like(TBL_PRODUCTS.'.product_content', $this->input->post('txtSearch'));
        $this->db->order_by(TBL_CATEGORIES.'.`order`', 'asc');
        $this->db->order_by(TBL_PRODUCTS.'.`order`', 'asc');
        $query = $this->db->get();        
        $output['listProducts'] = $query->result_array();

        return $output;
    }


    /* PRIVATE FUNCTIONS
     **************************************************************************/
    // Extrae los contenidos hijos
    private function _get_childs($id){
        $this->db->order_by('order', 'asc');
        $this->db->distinct();
        $this->db->select(TBL_CATEGORIES.'.*', true);
        $this->db->from(TBL_CATEGORIES);
        $this->db->join(TBL_PRODUCTS, TBL_CATEGORIES.'.reference = '.TBL_PRODUCTS.'.categorie_reference');
        $this->db->where('parent_id', $id);
        $query = $this->db->get();
        if( $query->num_rows>0 ) return $query->result_array();
        return false;
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