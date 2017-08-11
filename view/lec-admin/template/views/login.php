<div class="login_wrapper">

	<?php
	if (($lecMessages = \Lectric\controller::getSessionMessages()) !== null){
		
		?><div class="tools-alert tools-alert-yellow"><?php
			foreach ($lecMessages as $msg){
				echo $msg.'<br/>';
			}	
			\Lectric\controller::clearSessionMessages();
		?></div><?php
		
	}?>

	<form method="post" action="/do/action/LecAdmin/adminUser/adminLogin" class="forms  end" id="login_form">
	
		<p class="text-centered unit-25 unit-centered"><img src="/view/<?php echo $this->_fileDirectory; ?>/img/header_logo.png" alt="Lec Admin Admin Logo" class="width-10"/> </p>
		
		<br/>
	
		<div class="unit-25 unit-centered end login_box" >
		
			<div class="login_box_inner">
				
				<p class="text-centered">Log into the Lectric Admin Area</p>
				
				<div class="input-groups"><span class="input-prepend">&nbsp;&nbsp;&nbsp;<i class="fa fa-user fa-lg fa-fw"></i>&nbsp;&nbsp;&nbsp;</span><input tabindex="1" type="text" name="admin_username" placeholder="email/username"   class="width-100" /></div>
				<br/>									
				<div class="input-groups"><span class="input-prepend">&nbsp;&nbsp;&nbsp;<i class="fa fa-lock fa-lg fa-fw"></i>&nbsp;&nbsp;&nbsp;</span><input  tabindex="2" type="password" name="admin_password" placeholder="password"   class="width-100"/></div>
			
			</div>
			
			<hr class="end"/>
			
			<div class="login_box_inner">
				<button  tabindex="4" type="submit" class="width-100 btn btn-blue btn-big btn-login"><i class="fa fa-fw fa-check"></i> Login</button>
			</div>
		
		</div>
	
	</form>
	 
	 <p class="text-centered color_white">
		<br/>Recommended browser: Google Chrome &nbsp;&nbsp;&nbsp;&nbsp;<img src="/view/<?php echo $this->_fileDirectory; ?>/img/chrome.png" alt="Google Chrome" style="height:30px;"/>
	</p>

</div>

<script>

$(document).ready(function() {
	
	setTimeout(function(){
		$( ".login_wrapper " ).animate(
			{
				paddingTop : "6%",
			}, 
			{
				duration: 500,
				specialEasing: {
					opacity: "linear",
					paddingTop: "easeOutBounce"
				}
			}
		);
	}, 500);
	
});

</script>