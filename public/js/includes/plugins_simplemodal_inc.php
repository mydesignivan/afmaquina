<?php
if( $LOAD=="js" ){
    $arr[] = "plugins/simplemodal/js/jquery.simplemodal";
    
}else{
    $this->load->library('user_agent');
    if( $this->agent->browser()=="Internet Explorer" && $this->agent->version()>=6 ){
        $arr[] = "js/plugins/simplemodal/css/style_ie";
    }else{
        $arr[] = "js/plugins/simplemodal/css/style";
    }
}
?>