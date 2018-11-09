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
    <link rel="shortcut icon" type="image/png" href="/view/<?php echo $this->_fileDirectory; ?>/img/header_logo.png" />
	
	<!--mobile support meta-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="format-detection" content="telephone=no">
	
	<!--Main css stylesheet-->
    <link rel="stylesheet" href="<?php echo $this->_cssLocalDir; ?>/style-2018-11-09.css">
	
	<script src="<?php echo $this->_jsLocalDir; ?>/plugins.js"></script>
	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    
</head>


<body>

	<header>
	
		<h3 class="end left color_white mobile-hide"><?php echo SITE_NAME; ?></h3>
		
		<?php 
		if ($this->adminUser->loggedIn()){
			
			?>
			
				<span class="menu_button desktop-hide right"><i class="fa fa-bars fa-fw fa-2x"></i></span>
				
				<div class="text-right right menu_options mobile_menu">
					
					<a href="/lec-admin/" class=" end"><i class="fa fa-fw fa-home fa-fw"></i></a>
					
					<a href="/" class=" end" target="_blank"><i class="fa fa-fw fa-desktop"></i></a>
					
					<span class="mobile-hide"><i class="fa fa-fw fa-user"></i> <?php echo htmlentities($this->adminUser->name); ?></span>
					
					<form action="/do/action/LecAdmin/adminUser/adminLogout" method="post" class="forms end inline" > 
						<input type="hidden" name="logout" value="yes">
						<button class="btn btn-blue " type="submit"><i class="fa fa-fw fa-power-off"></i> Logout</button>
					</form>
					
				</div>
				
				<nav class="navbar nav-fullwidth end clear mobile_menu">
					<?php echo $this->lecAdmin->adminTabsHTML() ; ?>
				</nav>
				
			<?php
		}
		?>
		
		<script>
			$('.menu_button').on('click', function(){ $('.mobile_menu').toggle(); });
		</script>
		
	</header>
	
	<div style="height:90px;"></div>
	
	<div class="admin_page_wrapper">
