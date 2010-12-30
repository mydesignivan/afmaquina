<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<form id="form-movie" class="form-movie" action="" method="post" enctype="application/x-www-form-urlencoded">
    <div id="error" class="error hide">Los datos no pudieron ser guardados.</div>
    <h2><?=$title?></h2>
    <div class="trow">
        <label class="label" for="txtTitle">* T&iacute;tulo</label>
        <div class="span-8"><input type="text" name="txtTitle" id="txtTitle" value="<?=@$info['title']?>" /></div>
    </div>
    <div class="trow">
        <label class="label" for="txtTitle">* C&oacute;digo</label>
        <div class="span-8"><textarea rows="10" cols="6" name="txtCode" id="txtCode"><?=@$info['code']?></textarea></div>
    </div>
    <div class="trow align-center"><button type="submit">Guardar</button></div>
    <input type="hidden" name="id" value="<?=@$info['id']?>"
</form>
<div id="ajaxloader" class="ajaxloader hide"></div>