<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<h1 class="title"><?=$path_section?></h1>

<?php
$n=0;
foreach( $listProducts as $row ){
$n++;
$url = site_url('productos/leermas/'.$row['reference']);
?>
<div class="product">
    <a href="<?=$url?>"><img src="<?=UPLOAD_PATH_GALLERY . $row['thumb']?>" alt="<?=$row['thumb']?>" width="<?=$row['thumb_width']?>" height="<?=$row['thumb_height']?>" /></a><br />
    <div class="desc-top"><a href="<?=$url?>">ver m&aacute;s</a></div>
    <div class="desc-middle"><?=$row['product_name']?></div>
    <div class="desc-bottom"></div>
</div>
<?php 
    if( $n==3 ) {
        echo '<div class="clear"></div>';
        $n=0;
    }
}?>
