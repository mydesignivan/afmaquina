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
        
        $this->db->select('categorie_name, categorie_content, reference, parent_id, level, categories_id');
        $query = $this->db->get_where(TBL_CATEGORIES, array('reference'=>$ref));
        if( $query->num_rows==0 ) return false;
        $output = $query->row_array();

        $prefix = "";

        $sql = TBL_PRODUCTS.'.*,
            (SELECT thumb FROM '.$prefix.TBL_GALLERY_PRODUCTS.' WHERE '.$prefix.TBL_GALLERY_PRODUCTS.'.products_id='.$prefix.TBL_PRODUCTS.'.products_id ORDER BY `order` asc LIMIT 1) as thumb,
            (SELECT width FROM '.$prefix.TBL_GALLERY_PRODUCTS.' WHERE '.$prefix.TBL_GALLERY_PRODUCTS.'.products_id='.$prefix.TBL_PRODUCTS.'.products_id ORDER BY `order` asc LIMIT 1) as thumb_width,
            (SELECT height FROM '.$prefix.TBL_GALLERY_PRODUCTS.' WHERE '.$prefix.TBL_GALLERY_PRODUCTS.'.products_id='.$prefix.TBL_PRODUCTS.'.products_id ORDER BY `order` asc LIMIT 1) as thumb_height
        ';

        $this->db->select($sql);
        $this->db->order_by('order', 'asc');
        $query = $this->db->get_where(TBL_PRODUCTS, array('categorie_reference'=>$ref));
        $output['listProducts'] = $query->result_array();
        
        $output['path_section'] = $this->_get_path_section($ref);
        $output['path_section'] = array_reverse($output['path_section']['name']);
        $output['path_section'] = implode(" - ", $output['path_section']);

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
        //print_array($result, true);

        $arr_path = $this->_get_path_section($result['categorie_reference']);
        $result['path_section'] = array_reverse($arr_path['name']);
        $result['path_section'] = implode(" - ", $result['path_section']);

        $this->db->order_by('order', 'asc');
        $query = $this->db->get_where(TBL_GALLERY_PRODUCTS, array('products_id'=>$result['products_id']));
        if( $query->num_rows>0 ) $result['gallery'] = $query->result_array();

        $result['segment1'] = @$arr_path['reference'][1];
        $result['segment2'] = $arr_path['reference'][0];

        //print_array($result, true);
        
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
    private function _get_path_section($ref, &$arr_name=array(), &$arr_reference=array()){
        $this->db->select('reference, categorie_name, parent_id');
        $result = $this->db->get_where(TBL_CATEGORIES, array('reference'=>$ref))->row_array();

        $arr_name[] = $result['categorie_name'];
        $arr_reference[] = $result['reference'];

        $query = $this->db->get_where(TBL_CATEGORIES, array('categories_id'=>$result['parent_id']));
        if( $query->num_rows>0 ) {
            $result = $query->row_array();
            $this->_get_path_section($result['reference'], $arr_name, $arr_reference);
        }

        return array(
            'name' => $arr_name,
            'reference' => $arr_reference
        );
    }

    private function _get_first_reference(){
        $this->db->select('reference, categories_id');
        $this->db->order_by('order', 'asc');
        $this->db->limit(1);
        $res = $this->db->get_where(TBL_CATEGORIES, array('level'=>0))->row_array();

        $this->db->select('reference');
        $this->db->order_by('order', 'asc');
        $this->db->limit(1);
        $query = $this->db->get_where(TBL_CATEGORIES, array('parent_id'=>$res['categories_id']));
        if( $query->num_rows>0 ){
            $row = $query->row_array();
            return $row['reference'];
        }else return $res['reference'];

    }



}