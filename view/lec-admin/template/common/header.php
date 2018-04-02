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
				echo $this->page['metatitle'].' | Admin for ';
        }
		echo SITE_NAME; ?>
   </title>

	<!--content meta information-->
    <meta name="author" content="Elliott Barratt" />
    <meta name="description" content="<?php echo $this->page['metadescription'];?>" />
    <meta name="keywords" content="<?php echo $this->page['metakeywords'];?>" />
    
	<!--favicon-->
    <link rel="shortcut icon" type="image/png" href="<?php echo $this->_imgLocalDir; ?>/favicon.png" />
	
	<!--mobile support meta-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
	
	<!--layout and font stylesheets-->
	<link rel="stylesheet" href="<?php echo $this->_iconSet; ?>">
	
	<!--Main css stylesheet-->
    <link rel="stylesheet" href="<?php echo $this->_cssLocalDir; ?>/style.css">
    <link rel="stylesheet" href="<?php echo $this->_cssLibDir; ?>/jquery_ui.css">
	<link rel="stylesheet" href="/view/lec-admin/css/fancybox-v20170806.css">
	
	<script src="<?php echo $this->_jsLibDir; ?>/jquery.js"></script>
	<script src="<?php echo $this->_jsLibDir; ?>/jquery_ui.js"></script>
	<script src="<?php echo $this->_jsLibDir; ?>/plugins.js"></script>
	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
	<script src="/view/lec-admin/js/fancybox.js"></script>
    
</head>


<body>

	<header>
		<h1 class="end left"><?php echo SITE_NAME.' - Administration'; ?></h1>
		
		<a href="/lec-admin/" class="right end"><i class="fa fa-home fa-fw"></i></a>
		<?php 
		if ($this->adminUser->loggedIn()){
			
			//header menu ?>
			
			<form action="/do/action/LecAdmin/adminUser/adminLogout" method="post" class="forms end right" display="inline"> 
				<input type="hidden" name="logout" value="yes">
				<button class="btn btn-blue " type="submit"><i class="fa fa-sign-out"></i> Logout</button>
			</form>
			<p class="right end">Logged in as: <?php echo htmlentities($this->adminUser->name); ?>. </p>
			<nav class="navbar nav-fullwidth end clear">
				<?php echo $this->lecAdmin->adminTabsHTML() ; ?>
			</nav>
			<?php
		}
		?>
	</header>
	
	<div style="height:90px;"></div>
	
	<div class="admin_page_wrapper">
