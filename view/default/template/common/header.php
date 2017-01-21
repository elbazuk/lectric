<!doctype html>

<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js"> <!--<![endif]-->

<head>

    <meta charset="utf-8" />
	
    <?php $pageInj = (isset($_GET['page'])) ? ' - Page '.$_GET['page'] : '' ; ?>
	
    <title>
		<?php 
		if ($this->page['webpages']['metatitle'] != "") {
				echo $this->page['webpages']['metatitle'].$pageInj.' | ';
        }
		echo SITE_NAME; ?>
   </title>

	<!--content meta information-->
    <meta name="author" content="Elliott Barratt" />
    <meta name="description" content="<?php echo $this->page['webpages']['metadescription'].$pageInj;?>" />
    <meta name="keywords" content="<?php echo $this->page['webpages']['metakeywords'];?>" />
    
	<!--favicon-->
    <link rel="shortcut icon" type="image/png" href="/public/img/favicon.png" />
	
	<!--mobile support meta-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
	
	<!--layout and font stylesheets-->
	<link rel="stylesheet" href="<?php echo $this->_iconSet; ?>">
	
	<!--Main css stylesheet-->
    <link rel="stylesheet" href="/library/css/jquery_ui.css">
    <link rel="stylesheet" href="/library/css/kendo.css">
    <link rel="stylesheet" href="/view/default/css/style.css">
	
	<script src="/library/js/jquery.js"></script>
	<script src="/library/js/jquery_ui.js"></script>
	<script src="/library/js/kendo.js"></script>
	
	<!--<script src="//kendo.cdn.telerik.com/2016.3.1118/js/kendo.all.min.js"></script>-->
    
</head>


<body >

	<section class="body_wrapper forms">
