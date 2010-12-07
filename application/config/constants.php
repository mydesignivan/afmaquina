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
$msg = '
    <b>Compa&ntilde;&iacute;a:</b> {company}<br />
    <b>Nombre:</b> {name}<br />
    <b>Direcci&oacute;n:</b> {address}<br />
    <b>Ciudad:</b> {city}<br />
    <b>C&oacute;digo Postal:</b> {postcode}<br />
    <b>Pa&iacute;s:</b> {country}<br />
    <b>Provincia:</b> {state}<br />
    <b>E-Mail:</b> {email}<br />
    <b>Telefono:</b> {phone}<br />
    <b>Fax:</b> {fax}<br />
    <b>Tema:</b> {theme}
    <hr color="#666666" />{message}
';
define('EMAIL_CONTACT_SUBJECT', 'Formulario de Contacto');
define('EMAIL_CONTACT_MESSAGE', $msg);


/*
|--------------------------------------------------------------------------
| UPLOAD FILE
|--------------------------------------------------------------------------
*/
define('UPLOAD_FILETYPE', 'gif|jpg|png');
define('UPLOAD_MAXSIZE', 2048); //Expresado en Kylobytes

define('UPLOAD_PATH_PRODUCTS', './uploads/products/');
define('UPLOAD_PATH_SIDEBAR', './uploads/sidebar/');
define('UPLOAD_PATH_BANNER', './uploads/banner/');
define('UPLOAD_PATH_CV', './uploads/cv/');

define('IMAGESIZE_WIDTH_THUMB_PRODUCTS', 141);
define('IMAGESIZE_HEIGHT_THUMB_PRODUCTS', 108);
define('IMAGESIZE_WIDTH_FULL_PRODUCTS', 320);
define('IMAGESIZE_HEIGHT_FULL_PRODUCTS', 260);

define('IMAGESIZE_WIDTH_THUMB_SIDEBAR', 150);
define('IMAGESIZE_HEIGHT_THUMB_SIDEBAR', 100);
define('IMAGESIZE_WIDTH_FULL_SIDEBAR', 320);
define('IMAGESIZE_HEIGHT_FULL_SIDEBAR', 260);

define('IMAGESIZE_WIDTH_THUMB_BANNER', 234);
define('IMAGESIZE_HEIGHT_THUMB_BANNER', 175);


/*
|--------------------------------------------------------------------------
| TITULOS DE CADA SECCION
|--------------------------------------------------------------------------
*/
define('TITLE_GLOBAL', 'AF Maquinas y Herramientas S.R.L.'); // Titulo para todas las secciones
define('TITLE_INDEX', '');



/*
|--------------------------------------------------------------------------
| META - Palabras Claves y Descripcion de la web
|--------------------------------------------------------------------------
*/
define('META_KEYWORDS_GLOBAL', '');
define('META_KEYWORDS_INDEX', '');

define('META_DESCRIPTION_GLOBAL', '');
define('META_DESCRIPTION_INDEX', '');

/*
|--------------------------------------------------------------------------
| CONFIGURACION GENERAL
|--------------------------------------------------------------------------
*/
define('LANG', 1);


/* End of file constants.php */
/* Location: ./system/application/config/constants.php */