<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?=@$tlp_title;?></title>
    <base href="<?=base_url();?>" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="<?=@$tlp_meta_description;?>" />
    <meta name="keywords" content="<?=@$tlp_meta_keywords;?>" />
    <meta name="robots" content="index,follow" />
    <link href="public/images/favicon.ico" rel="stylesheet icon" type="image/ico" />
    <?php echo $this->assets->css(); ?>
    <!--[if lt IE 8]><link rel="stylesheet" href="public/css/blueprint/ie.css" type="text/css" media="screen, projection"/><![endif]-->
    <?php echo $this->assets->js(); ?>
    <!--[if IE 6]><script type="text/javascript" src="public/js/helpers/DD_belatedPNG.js"></script><![endif]-->
</head>
<body>
    <div class="container">
        <div class="span-24 last header"> 
        <?php require(APPPATH . 'views/includes/header_inc.php')?>
        </div>
        <div class="clear span-24 last main-container">
            <div class="contents" <?php if($this->uri->segment(1)=="panel") echo 'style="width:100%"'?>>
                <?php echo $this->template->yield(); ?>
            </div>
            <?php if( $this->uri->segment(1)!="panel" ){?>
            <div class="sidebar">
                <?php require(APPPATH . 'views/includes/sidebar_inc.php')?>
            </div>
            <?php }?>
        </div>
        <div class="main-container-bottom"></div>
        <div class="clear span-24 last footer"> 
        <?php require(APPPATH . 'views/includes/footer_inc.php')?>
        </div>
    </div>
</body>
</html>