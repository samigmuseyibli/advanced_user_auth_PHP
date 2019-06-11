<?php 
	include 'Captcha_model.php';
	$captcha = new Captcha_model();
	$captcha = $captcha->createCaptcha();
?>