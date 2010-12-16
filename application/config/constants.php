<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 				'rb');
define('FOPEN_READ_WRITE',			'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 	'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 			'ab');
define('FOPEN_READ_WRITE_CREATE', 		'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 		'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',	'x+b');


/*
|--------------------------------------------------------------------------
| NOMBRE DE LAS TABLAS (BASE DE DATO)
|--------------------------------------------------------------------------
*/
define('TBL_USERS',   'users');
define('TBL_SETUP',   'setup');
define('TBL_CONTENTS',                    'contents');
define('TBL_GALLERY_PRODUCTS',            'gallery_products');
define('TBL_CATEGORIES',                  'categories');
define('TBL_PRODUCTS',                    'products');
define('TBL_LIST_COUNTRY',                'list_country');
define('TBL_LIST_STATES',                 'list_states');

/*
|--------------------------------------------------------------------------
| MENSAJES DE ERROR PARA UPLOAD
|--------------------------------------------------------------------------
*/
define('ERR_UPLOAD_NOTUPLOAD', 'El archivo no ha podido llegar al servidor.');
define('ERR_UPLOAD_MAXSIZE', 'El tamaño del archivo debe ser menor a {size} MB.');
define('ERR_UPLOAD_FILETYPE', 'El tipo de archivo es incompatible.');

/*
|--------------------------------------------------------------------------
| EMAIL FORM CONTACTO
|--------------------------------------------------------------------------
*/
define('EMAIL_CONTACT_SUBJECT', 'Formulario de Contacto');
define('EMAIL_CONTACT_MESSAGE', json_encode(array(
    '<b>Nombre:</b> {txtName}<br />',
    '<b>Calle/Avenida:</b> {txtCalle}<br />',
    '<b>Ciudad:</b> {txtCity}<br />',
    '<b>Estado:</b> {txtState}<br />',
    '<b>Codigo Postal:</b> {txtPostCode}<br />',
    '<b>Pais:</b> {cboCountry}<br />',
    '<b>Telefono:</b> {txtPhoneCode}-{txtPhoneNum}<br />',
    '<b>Celular:</b> {txtCelCode}-{txtCelNum}<br />',
    '<b>Email:</b> {txtEmail}<br />',
    '<b>Comentario:</b> <br />{txtComment}'
)));


/*
|--------------------------------------------------------------------------
| UPLOAD FILE
|--------------------------------------------------------------------------
*/
define('UPLOAD_FILETYPE', 'gif|jpg|png');
define('UPLOAD_MAXSIZE', 2048); //Expresado en Kylobytes

define('UPLOAD_PATH_GALLERY', './uploads/productsgallery/');

define('IMAGESIZE_WIDTH_THUMB_GALLERY', 150);
define('IMAGESIZE_HEIGHT_THUMB_GALLERY', 110);
define('IMAGESIZE_WIDTH_FULL_GALLERY', 320);
define('IMAGESIZE_HEIGHT_FULL_GALLERY', 220);

//100x75


/*
|--------------------------------------------------------------------------
| TITULOS DE CADA SECCION
|--------------------------------------------------------------------------
*/
define('TITLE_GLOBAL', 'AF Maquinas y Herramientas S.R.L.');
define('TITLE_INDEX', 'AF Maquinas y Herramientas S.R.L.');
define('TITLE_NOSOTROS', 'AF Maquinas y Herramientas S.R.L. - Nosotros');
define('TITLE_PRODUCTOS', 'AF Maquinas y Herramientas S.R.L. - Productos');
define('TITLE_CATALOGO', 'AF Maquinas y Herramientas S.R.L. - Catálogo');
define('TITLE_VIDEOS', 'AF Maquinas y Herramientas S.R.L. - Videos');
define('TITLE_CONTACTO', 'AF Maquinas y Herramientas S.R.L. - Contacto');


/*
|--------------------------------------------------------------------------
| META - Palabras Claves y Descripcion de la web
|--------------------------------------------------------------------------
*/
define('META_KEYWORDS_GLOBAL', '');
define('META_KEYWORDS_INDEX', '');
define('META_KEYWORDS_NOSOTROS', '');
define('META_KEYWORDS_PRODUCTOS', '');
define('META_KEYWORDS_CATALOGO', '');
define('META_KEYWORDS_VIDEOS', '');
define('META_KEYWORDS_CONTACTO', '');

define('META_DESCRIPTION_GLOBAL', '');
define('META_DESCRIPTION_INDEX', '');
define('META_DESCRIPTION_NOSOTROS', '');
define('META_DESCRIPTION_PRODUCTOS', '');
define('META_DESCRIPTION_CATALOGO', '');
define('META_DESCRIPTION_VIDEOS', '');
define('META_DESCRIPTION_CONTACTO', '');

/*
|--------------------------------------------------------------------------
| CONFIGURACION GENERAL
|--------------------------------------------------------------------------
*/
define('LANG', 1);


/* End of file constants.php */
/* Location: ./system/application/config/constants.php */