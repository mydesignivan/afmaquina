<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<h2 class="title">Categor&iacute;as de Productos</h2>
<?php
$compare_seg1 = !isset($sidebar_def_seg1) ? $this->uri->segment(2) : $sidebar_def_seg1;
$compare_seg2 = !isset($sidebar_def_seg2) ? $this->uri->segment(3) : $sidebar_def_seg2;

echo '<ul id="sidebar-menu" class="resetul menu1">';
foreach( $content_sidebar['output'] as $row )  {
    $img = '- '; $ref="";
    if( isset($row['submenu']) ){
        $img = '<img src="public/images/arrow-sidebar.jpg" alt="" width="5" height="5" />';
        $ref = "/".$row['submenu'][0]['reference'];
    }

    $current = $compare_seg1==$row['reference'] ? ' class="current1"' : "";
    echo '<li'.$current.'>'. $img .' <a href="'. site_url('productos/'.$row['reference'].$ref) .'" class="lnk1">'. $row['title'] .'</a></li>';
}
echo '</ul>';

echo '<ul id="sidebar-submenu" class="resetul menu2">';
$n=0;
foreach( $content_sidebar['output'] as $row )  {

    if( !isset($row['submenu']) )  echo '<li>&nbsp;</li>';
    else {
        $n++;
        $hide = $compare_seg1==$row['reference'] ? "" : " hide";

        echo '<li><ul class="resetul'.$hide.'">';
        foreach( $row['submenu'] as $row2  ){
            $current = $compare_seg2==$row2['reference'] ? ' class="current2"' : "";
            echo '<li'.$current.'>- <a href="'.site_url('productos/'.$row['reference'].'/'.$row2['reference']).'" class="lnk2">'.$row2['title'] .'</a></li>';
        }
        echo '</ul></li>';
    }
    
}
echo '</ul>';
?>

    <!--<li class="current1"><img src="public/images/arrow-select-sidebar.jpg" alt="" width="5" height="5" /> <a href="" class="lnk1">Tornos</a>
        <ul class="resetul menu2">
            <li class="current2">- <a href="" class="lnk2">CNC</a></li>
            <li>- <a href="" class="lnk2">De Banco</a></li>
            <li>- <a href="" class="lnk2">De Produccion</a></li>
            <li>- <a href="" class="lnk2">Servicios Petroleros</a></li>
            <li>- <a href="" class="lnk2">Vertical</a></li>
        </ul>
    </li>-->
