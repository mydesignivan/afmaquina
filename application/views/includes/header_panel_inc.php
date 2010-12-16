<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<a href="<?=base_url()?>" class="logo"><img src="public/images/logo.png" alt="Af Maquinas y Herramientas S.R.L." border="0" width="257" height="34" /></a>
<ul class="header-menu header-menu-panel resetul">
    <li><a href="<?=base_url()?>" target="_blank">Inicio</a></li>
    <li><a href="<?=site_url('/panel/myaccount/')?>" <?php if($this->uri->segment(2)=='myaccount') echo 'class="current"'?>>Mi Cuenta</a></li>
    <li><a href="<?=site_url('/panel/products/')?>" <?php if($this->uri->segment(2)=='products') echo 'class="current"'?>>Productos</a></li>
    <li><a href="<?=site_url('/panel/contents/')?>" <?php if($this->uri->segment(2)=='contents') echo 'class="current"'?>>Contenidos</a></li>
    <li class="outline"><a href="<?=site_url('/panel/index/logout')?>">Salir</a></li>
</ul>
