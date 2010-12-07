<?php
class MY_Controller extends Controller {

    private $filter;
    private $ci;

    /* CONSTRUCTOR
     **************************************************************************/
    public function MY_Controller(){
        parent::Controller();
        @include(APPPATH.'config/filters'.EXT);
        $this->ci =& get_instance();
        $this->filter = $filter;
        $this->precontroller('auth');
    }

    /* PUBLIC FUNCTIONS
     **************************************************************************/
    public function precontroller($filter){
        if( $this->_applies($this->filter[$filter]) ){
            /*if(!$ci->tank_auth->is_logged_in()) {
                $ci->template->set_message('Su sesión ha caducado.', 'fail');
                redirect('/');
            }*/
        }
    }

    public function _render($view='front/content_view', $data=array(), $layout = FALSE){
        if( is_array($data) ){
            $this->template->set(array(
                'tlp_title' => !isset($data['title']) ? TITLE_GLOBAL : $data['title'],
                'tlp_keywords' => !isset($data['keywords']) ? META_KEYWORDS_GLOBAL : $data['keywords'],
                'tlp_description' => !isset($data['description']) ? META_DESCRIPTION_GLOBAL : $data['description']
            ));
        }elseif( is_bool($data) ) $layout = $data;

        $this->template->current_view = $view;
        $this->template->render($layout);
    }

    public function _post($var = FALSE){
        if($var) return $this->input->post($var);
        else return $this->input->post();

    }



    /* PRIVATE FUNCTIONS
     **************************************************************************/
    private function _applies($filter_conf) {
        $paths = $filter_conf[1];
        switch ($filter_conf[0]) {
            // exclusion mode
            case 'exclude':
                $apply = TRUE;
                foreach($paths as $path) if ($this->_matches($path)) return FALSE;
                break;
            // inclusion mode
            case 'include':
                $apply = TRUE;
                foreach( $paths as $path ) if ( $this->_matches($path) ) return TRUE;
                break;
            default:
                $this->_error('Bad filter type in config/filters.php - only "exclude" and "include" are valid.');
        }

        return $apply;
    }


    private function _matches($path){
        global $class, $method;

        if ( $path == '*' ) {
            return TRUE;
        } else if ( $path == '/' ) {
            if ($_SERVER['REQUEST_URI'] == '' || $_SERVER['REQUEST_URI'] == '/') return TRUE;
        } else {
            $parts = explode('/', $path);
            if ( $parts[1] == '*' ) {
                if ( $parts[0] == $method ) return TRUE;
            } else if ( strpos($parts[1], ',') !== false ) {
                $subparts = explode( ',', $parts[1] );
                if ( array_search($class, $subparts) !== FALSE ) return TRUE;
            } else {
                if ( $parts[0] == $class && $parts[1] == $method ) return TRUE;
            }
        }
        return FALSE;
    }



}