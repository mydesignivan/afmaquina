<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Products_panel_model extends Model {

    /* CONSTRUCTOR
     **************************************************************************/
    function  __construct() {
        parent::Model();
    }

    /* PUBLIC FUNCTIONS
     **************************************************************************/
    public function get_list($categories_id){
        $this->db->select('reference');
        $row = $this->db->get_where(TBL_CATEGORIES, array('categories_id'=>$categories_id))->row_array();

        $this->db->order_by('order', 'asc');
        $query = $this->db->get_where(TBL_PRODUCTS, array('categorie_reference'=>$row['reference']));
        return $query->result_array();
    }

    public function get_info($id) {
        $this->db->where('products_id', $id);
        return $this->db->get_where(TBL_PRODUCTS)->row_array();
    }

    public function create() {
         $data = array(
            'codlang'              => 1,
            'categorie_reference'  => $this->input->post('categorie_reference'),
            'product_name'         => $this->input->post('txtName'),
            'product_content'      => $this->input->post('txtContent'),
            'reference'            => normalize($this->input->post('txtName')),
            'order'                => $this->_get_num_order(TBL_PRODUCTS, array('categorie_reference'=>$this->input->post('categorie_reference'))),
            'date_added'           => strtotime(date('d-m-Y')),
            'last_modified'        => strtotime(date('d-m-Y'))
         );

         //print_array($data, true);

         $this->db->trans_off();
         $this->db->trans_start(); // INICIO TRANSACCION
         if( !$this->db->insert(TBL_PRODUCTS, $data) ) return $this->_set_error('Error Nº1');
         $this->db->trans_complete(); // COMPLETO LA TRANSACCION

         return 'ok';
    }

    public function edit() {
         $data = array(
            'codlang'              => 1,
            'categorie_reference'  => $this->input->post('categorie_reference'),
            'product_name'         => $this->input->post('txtName'),
            'product_content'      => $this->input->post('txtContent'),
            'reference'            => normalize($this->input->post('txtName')),
            'last_modified'        => strtotime(date('d-m-Y'))
         );

         /*print_array($json);
         print_array($data, true);*/

         $this->db->trans_off();
         $this->db->trans_start(); // INICIO TRANSACCION
         $this->db->where('products_id', $this->input->post('products_id'));
         if( !$this->db->update(TBL_PRODUCTS, $data) ) return $this->_set_error('Error Nº1');
         $this->db->trans_complete(); // COMPLETO LA TRANSACCION

         return 'ok';
    }

    public function delete($id){
        $this->db->trans_start(); // INICIO TRANSACCION
        $this->db->where_in('products_id', $id);
        $res = $this->db->delete(TBL_PRODUCTS);
        $this->db->trans_complete(); // COMPLETO LA TRANSACCION

        return $res;
    }

    public function order(){
        $initorder = $this->input->post('initorder');
        $rows = json_decode($this->input->post('rows'));

        $res = $this->db->query('SELECT `order` FROM '.TBL_PRODUCTS.' WHERE products_id='.$initorder)->row_array();
        $order = $res['order'];

        //print_array($rows, true);
        foreach( $rows as $row ){
            $id = substr($row, 2);
            $this->db->where('products_id', $id);
            if( !$this->db->update(TBL_PRODUCTS, array('order' => $order)) ) return false;
            $order++;
        }

        return true;
    }


    /* PRIVATE FUNCTIONS
     **************************************************************************/
    private function _get_num_order($tbl_name, $where=array()){
        $this->db->select_max('`order`');
        $this->db->where($where);
        $row = $this->db->get($tbl_name)->row_array();
        return is_null($row['order']) ? 1 : $row['order']+1;
    }

    private function _set_error($err){
        $this->db->trans_rollback();
        return $err;
    }

}