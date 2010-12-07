<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Contents_panel_model extends Model {

    /* CONSTRUCTOR
     **************************************************************************/
    function  __construct() {
        parent::Model();
    }

    /* PUBLIC PROPERTIES
     **************************************************************************/
    private $error;

    /* PUBLIC FUNCTIONS
     **************************************************************************/
    public function get_treeview(){
        $output = '<ul>';
        $output.= $this->_get_treeview();
        $output.= "</ul>";

        return $output;
    }

    public function create(){
        $json = json_decode($this->input->post('json'));

        $reference = normalize(trim($this->input->post('txtTitle')));
        $data = array(
            'codlang'         => 1,
            'parent_id'       => $this->input->post('parent_id'),
            'title'           => trim($this->input->post('txtTitle')),
            'reference'       => $reference,
            'content'         => $this->input->post('txtContent'),
            'content_sidebar' => $this->input->post('txtContentSidebar'),
            'level'           => $this->input->post('parent_id')>0 ? $this->_get_level() : 0,
            'order'           => $this->_get_num_order(TBL_CONTENTS, array('parent_id'=>$this->input->post('parent_id'))),
            'date_added'      => strtotime(date('d-m-Y')),
            'last_modified'   => strtotime(date('d-m-Y'))
        );

        /*print_array($json);
        print_array($data, true);*/

        $this->db->trans_off();
        $this->db->trans_start(); // INICIO TRANSACCION
        if( $this->db->insert(TBL_CONTENTS, $data) ){
            $id = $this->db->insert_id();
            if( !$this->_copy_images($json->gallery->images_new, $id) ) return $this->_set_error("Error Nº2");

        }else return $this->_set_error("Error Nº1");

        $this->db->trans_complete(); // COMPLETO LA TRANSACCION

        //Borra archivos temporales
        $this->load->helper('file');
        delete_files(UPLOAD_PATH_SIDEBAR.".tmp");

        return true;
    }

    public function edit(){
        $json = json_decode($this->input->post('json'));

        $reference = normalize(trim($this->input->post('txtTitle')));
        $data = array(
            'codlang'           => 1,
            'title'             => trim($this->input->post('txtTitle')),
            'reference'         => $reference,
            'content'           => $this->input->post('txtContent'),
            'content_sidebar'   => $this->input->post('txtContentSidebar'),
            'last_modified'     => strtotime(date('d-m-Y'))
        );

        /*print_array($json);
        print_array($data, true);*/

        $this->db->trans_off();
        $this->db->trans_start(); // INICIO TRANSACCION

        $this->db->where('content_id', $this->input->post('content_id'));
        if( $this->db->update(TBL_CONTENTS, $data) ){

            $gallery = $json->gallery;

            if( count($json->gallery->images_new)>0 ){
                if( !$this->_copy_images($gallery->images_new, $this->input->post('content_id')) ) return $this->_set_error("Error Nº2");
            }

            // Elimina las imagenes quitadas
             if( count($gallery->images_del)>0 ){
                foreach( $gallery->images_del as $row ){

                    if( $this->db->delete(TBL_GALLERY_CONTENTS, array('image'=>$row->image_full)) ){
                        @unlink(UPLOAD_PATH_SIDEBAR . $row->image_full);
                        @unlink(UPLOAD_PATH_SIDEBAR . $row->image_thumb);
                    }else return $this->_set_error("Error Nº3");
                }
             }

            // Reordena los thumbs
            foreach( $gallery->images_order as $row ){
                $this->db->where('image', $row->image_full);
                $this->db->update(TBL_GALLERY_CONTENTS, array('order' => $row->order));
            }

            // Modifica los titulos de la galeria de imagen
            foreach( $gallery->images_edit as $row ){
                $this->db->where('image', $row->image_full);
                $this->db->update(TBL_GALLERY_CONTENTS, array('title' => $row->title));
            }

        }else return $this->_set_error("Error Nº1");

        $this->db->trans_complete(); // COMPLETO LA TRANSACCION

        //Borra archivos temporales
        $this->load->helper('file');
        delete_files(UPLOAD_PATH_SIDEBAR.".tmp");
        
        return true;
    }

    public function delete($id) {
        return $this->_delete(array(array('content_id'=>$id)));
    }

    public function get_info($id) {
        $row = array();
        $row = $this->db->get_where(TBL_CONTENTS, array('content_id'=>$id))->row_array();
        $this->db->order_by('order', 'asc');
        $row['gallery'] = $this->db->get_where(TBL_GALLERY_CONTENTS, array('content_id'=>$id))->result_array();
        return $row;
    }

    public function order(){
        $initorder = $this->input->post('initorder');
        $rows = json_decode($this->input->post('rows'));

        $res = $this->db->query('SELECT `order` FROM '.TBL_CONTENTS.' WHERE content_id='.$initorder)->row_array();
        $order = $res['order'];

        //print_array($rows, true);
        foreach( $rows as $row ){
            $id = substr($row, 2);
            $this->db->where('content_id', $id);
            if( !$this->db->update(TBL_CONTENTS, array('order' => $order)) ) return false;
            $order++;
        }

        return true;
    }

    public function prueba(){
        $this->db->trans_off();
        $this->db->trans_start(); // INICIO TRANSACCION
        $this->db->insert('prueba', array('name'=>'nuevo2'));
        $this->db->trans_complete(); // COMPLETO LA TRANSACCION
    }

    /* PRIVATE FUNCTIONS
     **************************************************************************/
    private function _get_num_order($tbl_name, $where=array()){
        $this->db->select_max('`order`');
        $this->db->where($where);
        $row = $this->db->get($tbl_name)->row_array();
        return is_null($row['order']) ? 1 : $row['order']+1;
    }

    private function _get_level(){
        $this->db->select('level');
        $row = $this->db->get_where(TBL_CONTENTS, array('content_id'=>$this->input->post('parent_id')))->row_array();
        return $row['level']+1;
    }

    private function _get_treeview($parent_id=0){

        $this->db->order_by('`order`', 'asc');
        $query = $this->db->get_where(TBL_CONTENTS, array('parent_id'=> $parent_id, 'hide'=>0));

        $output='';

        foreach( $query->result_array() as $row ){

            $output.= '<li id="li'. $row['content_id'] .'">';

            $this->db->from(TBL_CONTENTS);
            $this->db->where('parent_id', $row['content_id']);
            $this->db->where('hide', 0);
            $count_child = $this->db->count_all_results();

            $output.= '<span id="id'.$row['content_id'].'" class="'. ($count_child==0 ? 'file' : "folder") .'">'.$row['title'].'</span>';

            if( $count_child>0 ) {
                $output.= '<ul>';
                $output.= $this->_get_treeview($row['content_id']);
                $output.= '</ul></li>';
            }
            else $output.= '</li>';

        }

        return $output;
    }

    private function _delete($arr){
        foreach( $arr as $row ){

            $this->db->trans_start(); // INICIO TRANSACCION
            if( $this->db->delete(TBL_CONTENTS, array('content_id'=>$row['content_id'])) ) {
                $result = $this->db->get_where(TBL_GALLERY_CONTENTS, array('content_id'=>$row['content_id']))->result_array();
                if( $this->db->delete(TBL_GALLERY_CONTENTS, array('content_id'=>$row['content_id'])) ) {
                    foreach( $result as $row ){
                        @unlink(UPLOAD_PATH_SIDEBAR . $row['thumb']);
                        @unlink(UPLOAD_PATH_SIDEBAR . $row['image']);
                    }
                }else return false;
                
            }else return false;

            $this->db->trans_complete(); // COMPLETO LA TRANSACCION

            $this->db->select('content_id');
            $query = $this->db->get_where(TBL_CONTENTS, array('parent_id'=>$row['content_id']));
            if( $query->num_rows>0 ) {
                if( !$this->_delete($query->result_array()) ) return false;
            }

        }

        return true;
    }

    private function _copy_images($json, $id){
        $n=0;
        foreach( $json as $row ){
            $n++;
            $cp1 = @copy(UPLOAD_PATH_SIDEBAR.".tmp/".$row->image_full, UPLOAD_PATH_SIDEBAR . $row->image_full);
            $cp2 = @copy(UPLOAD_PATH_SIDEBAR.".tmp/".$row->image_thumb, UPLOAD_PATH_SIDEBAR . $row->image_thumb);

            if( $cp1 && $cp2 ){
                $data = array(
                    'content_id'  => $id,
                    'image'       => $row->image_full,
                    'thumb'       => $row->image_thumb,
                    'title'       => $row->title,
                    'width'       => $row->width,
                    'height'      => $row->height
                );

                if( !is_numeric($this->input->post('content_id')) ) $data['order'] = $n;
                if( !$this->db->insert(TBL_GALLERY_CONTENTS, $data) ) return false;
            }else return false;
        }

        return true;
    }

    private function _set_error($err){
        $this->db->trans_rollback();
        return $err;
    }

}