<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<h1 class="title"><?=$info['path_section']?></h1>
<br />
<?php if( isset($info['gallery']) ){?>
    <div id="gallery" class="ad-gallery">
        <div class="ad-image-wrapper"></div>
        <div class="title-product"><div class="top"></div><div class="middle"><?=$info['product_name']?></div><div class="bottom"></div></div>
        <div class="ad-nav">
            <div class="ad-thumbs">
                <ul class="ad-thumb-list">
            <?php
            $n=-1;
            foreach( $info['gallery'] as $row ){$n++;?>
                    <li>
                        <a href="<?=UPLOAD_PATH_GALLERY . $row['image']?>"><img src="<?=UPLOAD_PATH_GALLERY.$row['thumb']?>" title="" alt="<?//=$row['thumb']?>" class="image<?=$n?>" /></a>
                    </li>
            <?php }?>
                </ul>
            </div>
        </div>
    </div>
<br />
<?php }?>
<?=$info['product_content']?>