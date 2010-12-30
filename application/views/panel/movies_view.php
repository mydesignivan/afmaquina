<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<?=$this->template->message()?>

<div class="trow" style="text-align:right">
    <button type="button" onclick="Movies.openwindow('new')">Nuevo</button>&nbsp;
    <button type="button" onclick="Movies.del(false)">Eliminar</button>
</div>

<?php if( count($list)>0 ) {?>
<table id="tblList" cellpadding="0" cellspacing="0" class="table-movies">
    <thead>
        <tr>
            <td class="cell1"><input type="checkbox" onclick="Movies.mark_items_all(this)" /></td>
            <td class="cell2">Video</td>
            <td class="cell3">T&iacute;tulo</td>
            <td class="cell4">Orden</td>
            <td class="cell5">Modificar</td>
            <td class="cell6">Eliminar</td>
        </tr>
    </thead>
    <tbody id="sortable">
<?php
    $n=0;
    foreach( $list as $row ) {
        $class = $n%2 ? 'class="row-even"' : '';
?>
        <tr id="tr<?=$row['id']?>" <?=$class?>>
            <td class="cell1"><input type="checkbox" value="<?=$row['id']?>" /></td>
            <td class="cell2"><?=get_object_movie($row['url'])?></td>
            <td class="cell3"><?=$row['title']?></td>
            <td class="cell4"><a href="javascript:void(0)" class="handle"><img src="public/images/icon_arrow_move.png" alt="" width="16" alt="16" /></a></td>
            <td class="cell5"><a href="javascript:void(Movies.openwindow('edit', <?=$row['id']?>))"><img src="public/images/icon_edit.png" alt="" width="16" alt="16" /><span>Modificar</span></a></td>
            <td class="cell6"><a href="javascript:void(Movies.del(<?=$row['id']?>))"><img src="public/images/icon_delete.png" alt="" width="16" alt="16" /><span>Eliminar</span></a></td>
        </tr>
<?php }?>
    </tbody>
</table>
<?php }else{?>
<div class="align-center"><br /><br /><br /><br /><h4>No hay Videos.</h4></div>
<?php }?>
<div id="popup"></div>