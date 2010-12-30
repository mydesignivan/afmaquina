<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Contents_model extends Model {

    /* CONSTRUCTOR
     **************************************************************************/
    function  __construct() {
        parent::Model();
    }

    /* PUBLIC FUNCTIONS
     **************************************************************************/
    public function save(){
        $data = array(
            'content'       => $this->input->post('content'),
            'last_modified' => strtotime(date('d-m-Y'))
        );
        $this->db->where('reference', $this->input->post('reference'));
        
        $this->template->refresh_all_cache();

        return $this->db->update(TBL_CONTENTS, $data);
    }

    public function get_content($ref){
        $query = $this->db->get_where(TBL_CONTENTS, array('reference'=>$ref));
        $content="";
        if( $query->num_rows>0 ) {
            $row = $query->row_array();
            $content = $row['content'];
        }
        return $content;
    }

    public function get_list(){
        $query = $this->db->get_where(TBL_CONTENTS);
        return $query->result_array();
    }

    public function get_sidebar(){
        $this->db->select('categories_id as id, reference, categorie_name as title');
        $this->db->order_by('order', 'asc');
        $query = $this->db->get_where(TBL_CATEGORIES, array('level'=>0));

        $output = array();
        foreach( $query->result_array() as $row ){
            $this->db->select(TBL_CATEGORIES.'.categories_id as id, '.TBL_CATEGORIES.'.reference, categorie_name as title');
            $this->db->join(TBL_PRODUCTS, TBL_CATEGORIES.'.reference = '.TBL_PRODUCTS.'.categorie_reference');
            $this->db->order_by(TBL_CATEGORIES.'.order', 'asc');
            $query = $this->db->get_where(TBL_CATEGORIES, array('parent_id'=>$row['id']));
            if( $query->num_rows>0 ) {
                $row['submenu'] = $query->result_array();
                $output[] = $row;
            }else{
                $query = $this->db->get_where(TBL_PRODUCTS, array('categorie_reference'=>$row['reference']));
                if( $query->num_rows>0 ) $output[] = $row;
            }
        }
        if( count($output)==0 ) return $output;

        if( isset($output[0]['submenu']) ){
            $ref = $output[0]['reference'] .'/'. $output[0]['submenu'][0]['reference'];
        }else{
            $ref = $output[0]['reference'];
        }

        return array(
            'output' => $output,
            'reference' => $ref
        );
    }

    /* PRIVATE FUNCTIONS
     **************************************************************************/
    
}
?>