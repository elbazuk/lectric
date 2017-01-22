<!doctype html>

<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]--> 
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js"> <!--<![endif]-->

<head>

    <meta charset="utf-8" />
	
    <title>
		<?php 
		if ($this->page['metatitle'] != "") {
				echo $this->page['metatitle'].' | ';
        }
		echo SITE_NAME; ?>
   </title>

	<!--content meta information-->
    <meta name="author" content="Elliott Barratt" />
    <meta name="description" content="<?php echo $this->page['metadescription'];?>" />
    <meta name="keywords" content="<?php echo $this->page['metakeywords'];?>" />
    
	<!--favicon-->
    <link rel="shortcut icon" type="image/png" href="/public/img/favicon.png" />
	
	<!--mobile support meta-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
	
	<!--layout and font stylesheets-->
	<link rel="stylesheet" href="<?php echo $this->_iconSet; ?>">
	
	<!--Main css stylesheet-->
    <link rel="stylesheet" href="/view/<?php echo $this->_fileDirectory; ?>/css/style.css">
	
	<script src="/library/js/jquery.js"></script>
    
</head>


<body >

	<section class="body_wrapper forms">
