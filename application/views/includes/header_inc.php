<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<a href="<?=base_url()?>" class="logo"><img src="images/logo.png" alt="Af Maquinas y Herramientas S.R.L." border="0" width="257" height="34" /></a>
<div class="text">Al servicio de la industria, trabajamos d&iacute;a a d&iacute;a para ofrecer una excelente atenci&oacute;n, para as&iacute; poder lograr una estrecha relaci&oacute;n con nuestros clientes basada en la confianza y el respeto.</div>
<ul class="menu resetul">
    <li><a href="<?=base_url()?>" <?php if($this->uri->segment(1)=='inicio'||$this->uri->segment(1)=='') echo 'class="current"'?>>Inicio</a></li>
    <li><a href="<?=site_url('/nosotros/')?>" <?php if($this->uri->segment(1)=='nosotros') echo 'class="current"'?>>Nosotros</a></li>
    <li><a href="<?=site_url('/productos/')?>" <?php if($this->uri->segment(1)=='productos') echo 'class="current"'?>>Productos</a></li>
    <li><a href="<?=site_url('/catalogo/')?>" <?php if($this->uri->segment(1)=='catalogo') echo 'class="current"'?>>Cat&aacute;logo</a></li>
    <li><a href="<?=site_url('/videos/')?>" <?php if($this->uri->segment(1)=='videos') echo 'class="current"'?>>Videos</a></li>
    <li class="outline"><a href="<?=site_url('/contacto/')?>" <?php if($this->uri->segment(1)=='contacto') echo 'class="current"'?>>Contacto</a></li>
</ul>
<div class="banner">
    <img src="images/imagen-banner-01.jpg" alt="" width="450" height="184" />
    <img src="images/imagen-banner-02.jpg" alt="" width="450" height="184" />
</div>