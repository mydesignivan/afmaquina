<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<fieldset class="fieldset-categories">
    <legend>Categor&iacute;as</legend>

    <div class="cont-treeview">
        <ul id="treeview" class="filetree">
            <li><span id="id0" class='folder'>Categor&iacute;as</span>
                <?=$treeview?>
            </li>
        </ul>
    </div>

    <div class="clear">
        <a href="javascript:void(Products.categorie_new())" class="link-img"><img src="public/images/icon-new.png" alt="" width="16" height="16" /> Nuevo</a>
        <a id="linkCatEdit" href="javascript:void(Products.categorie_edit())" class="link-img hide"><img src="public/images/icon-edit.png" alt="" width="16" height="16" /> Modificar</a>
        <a id="linkCatDel" href="javascript:void(Products.categorie_delete())" class="link-img hide"><img src="public/images/icon-delete.png" alt="" width="16" height="16" /> Eliminar</a>
    </div>
</fieldset>
<fieldset id="fieldset-form" class="fieldset-form">
    <legend>Productos</legend>
    <div id="cont-products" class="cont-products"></div>
    <div id="cont-btn" class="trow align-center hide"><button type="button" onclick="/*Products.prueba();*/ $('#form1').submit()">Guardar</button></div>
    <div id="busy" class="busy"></div>
</fieldset>