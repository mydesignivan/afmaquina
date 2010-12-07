<?php
if( $LOAD=="js" ){
    $arr[] = "plugins/jquery.ad-gallery.1.2.4/jquery.ad-gallery";

}else{
    $arr[] = 'js/plugins/jquery.ad-gallery.1.2.4/jquery.ad-gallery'.$this->config->item('sufix_pack_css');
}
?>