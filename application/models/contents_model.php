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
            'content'       => $_POST['content'],
            'last_modified' => date('Y-m-d H:i:s')
        );
        $this->db->where('reference', $_POST['reference']);
        return $this->db->update(TBL_CONTENTS, $data);
    }

    public function get_content($ref=null){
        if( !is_null($ref) ){
            $where = array('reference'=>$ref);
        }else{
            $segs = $this->uri->segment_array();
            $level = count($segs);
            $where = array('reference'=>$segs[$level], 'level'=>$level-1);
        }

        $query = $this->db->get_where(TBL_CONTENTS, $where);
        $content="";
        if( $query->num_rows>0 ) {
            $content = array();
            $content = $query->row_array();

            // Extrae los contenidos hijos
            if( $content['level']>0 ){
                $content['sidebar']['has_submenu']=0;

                if( $content['level']==1 ){
                    $this->db->select('content_id, reference, title, content');
                    $content['sidebar']['menu'] = $this->db->get_where(TBL_CONTENTS, array('parent_id'=>$content['parent_id']))->result_array();

                }else{
                    $this->db->select('parent_id');
                    $row = $this->db->get_where(TBL_CONTENTS, array('content_id'=>$content['parent_id']))->row_array();
                    $parent_id = $row['parent_id'];

                    $this->db->select('content_id, reference, title, content');
                    $this->db->order_by('order', 'asc');
                    $content['sidebar']['menu'] = $this->db->get_where(TBL_CONTENTS, array('parent_id'=>$parent_id))->result_array();
                }

                $menu = array();
                for( $n=0; $n<=count($content['sidebar']['menu'])-1; $n++ ){
                    $menu =& $content['sidebar']['menu'];
                    $content_id = $menu[$n]['content_id'];

                    $this->db->select('content_id, reference, title');
                    $this->db->order_by('order', 'asc');
                    $query = $this->db->get_where(TBL_CONTENTS, array('parent_id'=>$content_id));
                    if( $query->num_rows>0 ){
                        $content['sidebar']['has_submenu']=1;
                        $menu[$n]['submenu'] = $query->result_array();
                        if( $menu[$n]['content']=='' ){
                            $menu[$n]['reference2'] = $menu[$n]['reference'].'/'.$menu[$n]['submenu'][0]['reference'];
                        }
                    }
                    unset($menu[$n]['content']);
                }

                //print_array($content['sidebar']['menu'], true);

            }

            // Extra la galeria de imagenes
            $this->db->select(TBL_GALLERY_CONTENTS.'.*, '.TBL_CONTENTS.'.show_gallery');
            $this->db->join(TBL_GALLERY_CONTENTS, TBL_CONTENTS.'.content_id='.TBL_GALLERY_CONTENTS.'.content_id');
            $this->db->order_by(TBL_GALLERY_CONTENTS.'.order', 'asc');
            $query = $this->db->get_where(TBL_CONTENTS, array(TBL_CONTENTS.'.content_id'=>$content['parent_id'], TBL_CONTENTS.'.parent_id'=>0));
            if( $query->num_rows>0 ) {
                $content['sidebar']['gallery'] = $query->result_array();
                $content['show_gallery'] = $content['sidebar']['gallery'][0]['show_gallery'];
            }else{
                $query = $this->db->get_where(TBL_GALLERY_CONTENTS, array('content_id'=>$content['content_id']));
                if( $query->num_rows>0 ) $content['sidebar']['gallery'] = $query->result_array();
            }

            $content['title'] = $this->_get_title($content['content_id']);
            if( count($content['title'])>1 ){
                array_pop($content['title']);
                $content['title'] = array_reverse($content['title']);
            }
            $content['title'] = implode(" &gt; ", $content['title']);
        }


        return $content;
    }

    public function get_menu(){
        $output = '<ul id="sf-menu" class="sf-menu">';
        $output.= $this->_get_menu();
        $output.= "</ul>";

        $i = strpos($output, '</li>', strpos($output, '>Productos<'));
        $part1 = substr($output, 0, $i);
        $part2 = substr($output, $i);

        $output = $part1 .'<ul class="hide">'. $this->_get_menu_catalog(). '</ul>'. $part2;

        return $output;
    }

    public function get_list(){
        $this->db->order_by('order', 'asc');
        $query = $this->db->get_where(TBL_CONTENTS);
        return $query->result_array();
    }

    public function get_list_banner(){
        $this->db->distinct();
        $this->db->select('banner_thumb, banner_thumb_width, banner_thumb_height, categorie_content, categorie_name,'.TBL_CATEGORIES.'.reference');
        $this->db->join(TBL_PRODUCTS, TBL_CATEGORIES.'.reference = '.TBL_PRODUCTS.'.categorie_reference');
        $this->db->order_by(TBL_CATEGORIES.'.`order`', 'asc');
        return $this->db->get_where(TBL_CATEGORIES, array('banner'=>1))->result_array();
    }

    public function get_footer(){
        $this->db->select('content');
        $row = $this->db->get_where(TBL_CONTENTS, array('content_id'=>15))->row_array();
        return $row['content'];
    }


    /* PRIVATE FUNCTIONS
     **************************************************************************/
    private function _get_menu($parent_id=0, $reference_parent=''){

        $this->db->order_by('`order`', 'asc');
        $query = $this->db->get_where(TBL_CONTENTS, array('parent_id'=>$parent_id, 'exclusive'=>0));

        $j=0;
        $output='';

        foreach( $query->result_array() as $row ){
            $j++;

            $output.= '<li'.($j==$query->num_rows && $row['parent_id']==0 ? ' class="outline"' : '').'>';

            $this->db->select('reference');
            $query2 = $this->db->get_where(TBL_CONTENTS, array('parent_id'=>$row['content_id']));

            if( $query2->num_rows>0 && $row['parent_id']==0 || $row['reference']=="productos" ){
                $href = "#";
            }
            elseif( $query2->num_rows>0 && $row['content']=='' && $row['reference']!="productos" ){
                $row2 = $query2->result_array();
                $href = site_url($reference_parent."/".$row['reference'].'/'.$row2[0]['reference']);
            }else{
                $href = site_url($reference_parent."/".$row['reference']);
            }

            $seg = $this->uri->segment(1);
            $class=array();
            if( $seg==$row['reference'] && $row['parent_id']==0  ) $class[] = "current";
            if( $j==$query->num_rows ) $class[] = "outline";
            if( count($class)>0 ) $class = ' class="'.implode(" ", $class).'"';
            else $class="";

            $output.= '<a href="'.$href.'"'.$class.'>'.$row['title'].'</a>';

            if( $query2->num_rows>0 ) {
                $output.= '<ul class="hide">';
                $reference = $row['reference'];
                if( $reference_parent!='' ) $reference = $reference_parent .'/'. $row['reference'];
                $output.= $this->_get_menu($row['content_id'], $reference);
                $output.= '</ul></li>';
            }
            else {
                $output.= '</li>';
            }

        }

        return $output;
    }

    private function _get_menu_catalog($parent_id=0, $reference_parent='productos'){

        $this->db->distinct();
        $this->db->select(TBL_CATEGORIES.'.*', true);
        $this->db->from(TBL_CATEGORIES);
        $this->db->order_by(TBL_CATEGORIES.'.`order`', 'asc');
        $this->db->join(TBL_PRODUCTS, TBL_CATEGORIES.'.reference = '.TBL_PRODUCTS.'.categorie_reference');
        $this->db->where(TBL_CATEGORIES.'.parent_id', $parent_id);
        $query = $this->db->get();

        $j=0;
        $output='';

        foreach( $query->result_array() as $row ){
            $j++;

            $output.= '<li>';

            $this->db->from(TBL_CATEGORIES);
            $this->db->join(TBL_PRODUCTS, TBL_CATEGORIES.'.reference='.TBL_PRODUCTS.'.categorie_reference');
            $this->db->where(TBL_CATEGORIES.'.parent_id', $row['categories_id']);
            $count_child = $this->db->count_all_results();
            
            $reference = $reference_parent."/".$row['reference'];
            $href = site_url($reference);
            $seg = $this->uri->segment(1);
            $class = $j==$query->num_rows ? ' class="outline"' : '';

            $output.= '<a href="'.$href.'"'.$class.'>'.$row['categorie_name'].'</a>';

            if( $count_child>0 ) {
                $output.= '<ul class="hide">';
                $output.= $this->_get_menu_catalog($row['categories_id'], $reference);
                $output.= '</ul></li>';
            }
            else {
                if( $j==$query->num_rows ) $output.= '<div class="shadow-h"></div>';
                $output.= '</li>';
            }

        }

        return $output;
    }

    private function _get_title($id, &$title=array()){
        $this->db->select('title, parent_id');
        $row = $this->db->get_where(TBL_CONTENTS, array('content_id'=>$id))->row_array();

        $title[] = $row['title'];

        $childs = $this->db->get_where(TBL_CONTENTS, array('content_id'=>$row['parent_id']))->num_rows;
        if( $childs>0 ) $this->_get_title($row['parent_id'], $title);

        return $title;
    }
    
}