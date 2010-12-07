<?php
if( $LOAD=="js" ){
    $arr[] = "plugins/jquery-treeview/jquery.treeview.pack";

}else{
    $arr[] = 'js/plugins/jquery-treeview/jquery.treeview'.$this->config->item('sufix_pack_css');
}
?>