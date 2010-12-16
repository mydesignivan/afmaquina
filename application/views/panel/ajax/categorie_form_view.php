<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div id="error" class="error hide">Los datos no pudieron ser guardados.</div>
<div id="success" class="success hide">Los datos fueron guardados con &eacute;xito.</div>

<form id="form1" class="form-categories" action="<?=site_url('/panel/products/ajax_categories_'. (isset($info) ? 'edit' : 'create'));?>" method="post" enctype="application/x-www-form-urlencoded">
<?php if( !isset($info) ){?>
    <div class="trow">
        <label class="label">Categor&iacute;a Padre:</label>
        <span id="txtParentCat"></span>
    </div>
<?php }?>
    <div class="trow">
        <label class="label" for="txtCategorie">* Nombre Categor&iacute;a</label>
        <div class="fleft"><input type="text" name="txtCategorie" id="txtCategorie" value="<?=@$info['categorie_name']?>" /></div>
    </div>
    <div class="trow">
        Contenido<br /><div class="clear"></div>
        <textarea rows="10" cols="22" id="txtContent" name="txtContent"><?=@$info['categorie_content']?></textarea>
    </div>
    <input type="hidden" name="categories_id" id="categories_id" value="<?=@$info['categories_id']?>" />
</form>