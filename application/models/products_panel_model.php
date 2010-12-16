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

        $this->db->select(TBL_PRODUCTS.'.*, (SELECT thumb FROM '.TBL_GALLERY_PRODUCTS.' WHERE '.TBL_GALLERY_PRODUCTS.'.products_id='.TBL_PRODUCTS.'.products_id ORDER BY `order` LIMIT 1) as filename');
        $this->db->order_by('order', 'asc');
        $query = $this->db->get_where(TBL_PRODUCTS, array('categorie_reference'=>$row['reference']));
        return $query->result_array();
    }

    public function get_info($id) {
        $row = $this->db->get_where(TBL_PRODUCTS, array('products_id'=>$id))->row_array();
        $this->db->order_by('order', 'asc');
        $row['gallery'] = $this->db->get_where(TBL_GALLERY_PRODUCTS, array('products_id'=>$id))->result_array();
        return $row;
    }

    public function create() {
         $json = json_decode($this->input->post('json'));
         //print_array($json, true);
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

         if( $this->db->insert(TBL_PRODUCTS, $data) ){
             if( $json->gallery ){
                 $id = $this->db->insert_id();
                 if( !$this->_copy_images($json->gallery->images_new, $id) ) return $this->_set_error("Error Nº2");
             }

         }else return $this->_set_error('Error Nº1');
         $this->db->trans_complete(); // COMPLETO LA TRANSACCION

         $this->load->helper('file');
         delete_files(UPLOAD_PATH_GALLERY.".tmp");

         return 'ok';
    }

    public function edit() {
         $json = json_decode($this->input->post('json'));

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
         if( $this->db->update(TBL_PRODUCTS, $data) ){
            if( count($json->gallery->images_new)>0 ){
                if( !$this->_copy_images($json->gallery->images_new, $this->input->post('products_id')) ) return $this->_set_error("Error Nº2");
            }

             // Elimina las imagenes quitadas
             if( count($json->gallery->images_del)>0 ){
                foreach( $json->gallery->images_del as $row ){

                    if( $this->db->delete(TBL_GALLERY_PRODUCTS, array('image' => urldecode($row->image_full))) ){
                        @unlink(UPLOAD_PATH_GALLERY . urldecode($row->image_full));
                        @unlink(UPLOAD_PATH_GALLERY . urldecode($row->image_thumb));
                    }else return $this->_set_error("Error Nº3");
                }
             }

            // Reordena los thumbs
            foreach( $json->gallery->images_order as $row ){
                $this->db->where('image', $row->image_full);
                $this->db->update(TBL_GALLERY_PRODUCTS, array('order' => $row->order));
            }
         }else return $this->_set_error('Error Nº1');
         
         $this->db->trans_complete(); // COMPLETO LA TRANSACCION

         $this->load->helper('file');
         delete_files(UPLOAD_PATH_GALLERY.".tmp");

         return 'ok';
    }

    public function delete($id){
        $this->db->select('products_id');

        if( is_array($id) ) $this->db->where_in('products_id', $id);
        else $this->db->where_in('categorie_reference', $id);
        $list = $this->db->get(TBL_PRODUCTS)->result_array();
        
        if( count($list)>0 ){
            if( is_array($id) ) $this->db->where_in('products_id', $id);
            else $this->db->where_in('categorie_reference', $id);

             if( is_array($id) ){
                 $this->db->trans_off();
                 $this->db->trans_start(); // INICIO TRANSACCION
             }
             $res = $this->db->delete(TBL_PRODUCTS);

            if( $res ){
                foreach( $list as $row ) {
                    $listImages = $this->db->get_where(TBL_GALLERY_PRODUCTS, array('products_id'=>$row['products_id']))->result_array();
                    if( $this->db->delete(TBL_GALLERY_PRODUCTS, array('products_id'=>$row['products_id'])) ){
                        foreach( $listImages as $row2 ){
                            @unlink(UPLOAD_PATH_GALLERY . $row2['thumb']);
                            @unlink(UPLOAD_PATH_GALLERY . $row2['image']);
                        }
                    }
                }
            }else return false;

            if( is_array($id) ) $this->db->trans_complete(); // COMPLETO LA TRANSACCION
        }
        
        return true;
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
    
    private function _copy_images($json, $id){
        $n=0;
        foreach( $json as $row ){
            $n++;
            $cp1 = @copy(UPLOAD_PATH_GALLERY.".tmp/".$row->image_full, UPLOAD_PATH_GALLERY . $row->image_full);
            $cp2 = @copy(UPLOAD_PATH_GALLERY.".tmp/".$row->image_thumb, UPLOAD_PATH_GALLERY . $row->image_thumb);

            if( $cp1 && $cp2 ){
                $data = array(
                    'products_id'  => $id,
                    'image'       => $row->image_full,
                    'thumb'       => $row->image_thumb,
                    'width'       => $row->width,
                    'height'      => $row->height
                );

                if( !is_numeric($this->input->post('products_id')) ) $data['order'] = $n;
                if( !$this->db->insert(TBL_GALLERY_PRODUCTS, $data) ) return false;
            }else return false;
        }

        return true;
    }

    private function _set_error($err){
        $this->db->trans_rollback();
        return $err;
    }

}