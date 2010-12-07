<?php

///---------------------------------------------------------------
// theCLOSET ASSETS LIBRARY
// by Lonnie Ezell (http://igniteyourcode.com/thecloset)
//
// version: 1.0 Beta 1
//---------------------------------------------------------------

//---------------------------------------------------------------
// Asset Folders
//---------------------------------------------------------------

/**
 * The parent folder that your css/js/images are stored in.
 *
 * This should be relative to your index.php file.
 */
$config['asset_folder']		= 'public/';

/**
 * Individual asset folders for css/js/images.
 *
 * Relative to the asset_folder.
 */
$config['js_folder']		= 'js';
$config['css_folder']		= 'css';
$config['img_folder']		= 'img';

//---------------------------------------------------------------
// Base Assets
//---------------------------------------------------------------


$config['assets_css_default']	= array(
    array(true, 'blueprint/screen'),
    array(true, 'style'),
);
$config['assets_js_default']  = array(array(false, 'jquery-1.4.2.min'));


$config['inline_js_opener'] = '$(document).ready(function(){';
$config['inline_js_closer'] = '});';

/**
 * Prepended to every js(), css(), or image() call, the use
 * of an asset_host to automatically add a domain to every page
 * makes it simple to change up an existing media path to
 * different subdomains. This is particularly helpful when
 * switching to a third-party cloud file server like Amazon S3.
 *
 * Leave empty '' for the current domain.
 */
$config['asset_host'] = '';

/**
 * The dev, test and production servers. Used by devmode() to
 * to determine which is the active server. Also used by Assets
 * to determine whether to combine the files or not.
 */
$config['servers']= array(
    'dev'	=> 'localhost/trabajos/codeigniter',
    'test'	=> '',
    'prod'	=> ''
);

/**
 * Determines how Assets works on the dev, test, and production
 * environments. If combine_on... is true for the current server,
 * it will render out a link to the combine.php script that it
 * expects to find at the root of the site. Otherwise, it will
 * render out the individual links to files.
 */
$config['combine_on_dev']	= true;
$config['combine_on_test']	= false;
$config['combine_on_prod']	= false;
