<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<?php echo $this->template->message()?>

<form id="form1" class="form-myaccount" action="<?=site_url('/panel/myaccount/save');?>" method="post" enctype="application/x-www-form-urlencoded">
    <div class="trow">
        <label class="label" for="txtEmailContact">* Email Cont&aacute;ctenos</label>
        <div class="fleft"><input type="text" name="txtEmailContact" id="txtEmailContact" value="<?=$info['email_contact']?>" /></div>
    </div>
    <div class="trow">
        <label for="txtInfo" class="label">Contrase&ntilde;a</label>
        <button type="button" onclick="Account.showcontapass(this);" class="button">Modificar</button>
    </div>
    <div id="contPass" class="clear hide">
        <div class="trow">
            <label for="txtPassOld" class="label">* Contrase&ntilde;a actual</label>
            <div class="fleft"><input type="password" name="txtPassOld" id="txtPassOld" /></div>
        </div>
        <div class="trow">
            <label for="txtPassNew" class="label">* Nueva contrase&ntilde;a</label>
            <div class="fleft"><input type="password" name="txtPassNew" id="txtPassNew" /></div>
        </div>
        <div class="trow">
            <label for="txtConfirmPass" class="label">* Repetir Contrase&ntilde;a</label>
            <div class="fleft"><input type="password" name="txtConfirmPass" id="txtConfirmPass" /></div>
        </div>
    </div>

    <div class="trow align-center"><button type="submit">Guardar</button></div>
</form>