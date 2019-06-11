<?php 
session_start();
include 'config.php';
include 'ipClass.php';
require 'Captcha_model.php';
$user = new User();
$ip   = new IpAddress();
$ipp  = $ip->getIpAddress();
$captcha = new Captcha_model();

if ($user->get_session())
{
    //header("location: profile");
	echo 'First you must logout';
}
else{
	if (isset($_POST['email'])){
		$xss = $user->xss($_POST,true);
		if($xss == ''){
			if($captcha->check($_POST['captchaCode'])){
				$data = array(
					'pass' 	  => $_POST['pass'],
					'email'   => $_POST['email']
				);
				if($user->login($data)){
					echo 'done';
				}else{
					echo 'wrong email or password';
				}
			}else{
				echo 'Invalid Captcha ';
			}
		}else{
			 print_r($xss);
		}
    }
}
?>