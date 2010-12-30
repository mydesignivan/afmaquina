<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Movies_model extends Model {

    /* CONSTRUCTOR
     **************************************************************************/
    function  __construct() {
        parent::Model();
    }

    /* PUBLIC FUNCTIONS
     **************************************************************************/
    public function get_list(){
        $this->db->order_by('order', 'asc');
        return $this->db->get_where(TBL_MOVIES)->result_array();
    }

    public function get_info($id){
        $row = $this->db->get_where(TBL_MOVIES, array('id'=>$id))->row_array();
        $row['code'] = get_object_movie($row['url']);
        return $row;
    }

    public function create(){
        $data = array(
            'title'              => $this->input->post('txtTitle'),
            'url'                => $this->input->post('txtCode'),
            'order'             => $this->_get_num_order(TBL_MOVIES),
            'date_added'    => strtotime(date('d-m-Y')),
            'last_modified' => strtotime(date('d-m-Y'))
        );
        $this->template->refresh_all_cache();
        return $this->db->insert(TBL_MOVIES, $data);
    }

    public function edit(){
        $data = array(
            'title'              => $this->input->post('txtTitle'),
            'url'                => $this->input->post('txtCode'),
            'last_modified' => strtotime(date('d-m-Y'))
        );
        $this->db->where('id', $this->input->post('id'));
        $this->template->refresh_all_cache();
        return $this->db->update(TBL_MOVIES, $data);
    }

    public function delete($id) {
        $this->db->where_in('id', $id);
        $this->template->refresh_all_cache();
        return $this->db->delete(TBL_MOVIES);
    }

    public function order(){
        $initorder = $this->input->post('initorder');
        $rows = json_decode($this->input->post('rows'));

        $this->db->select('order');
        $res = $this->db->get_where(TBL_MOVIES, array('id' => $initorder))->row_array();
        $order = $res['order'];

        //print_array($rows, true);
        foreach( $rows as $row ){
            $id = substr($row, 2);
            $this->db->where('id', $id);
            if( !$this->db->update(TBL_MOVIES, array('order' => $order)) ) return false;
            $order++;
        }

        $this->template->refresh_all_cache();
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
    
}