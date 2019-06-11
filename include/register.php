<?php 
session_start();
include_once 'config.php';
include_once 'ipClass.php';
require 'Captcha_model.php';
$ip   = new IpAddress();
$ipp  = $ip->getIpAddress();
$user = new User();
$captcha = new Captcha_model();
// Checking for user logged in or not
if ($user->get_session())
{
    //header("location: profile");
	echo 'First you must logout';
}
else{
	if (isset($_POST)){
		$xss = $user->xss($_POST, true);
		if($xss == ''){
			if($captcha->check($_POST['captchaCode'])){
				$data = array(
					'pass' 	  => $_POST['pass'],
					're_pass' => $_POST['re_pass'],
					'email'   => $_POST['email'],
					'name'    => $_POST['name'],
				);
				//print_r($data);
				if($data['pass'] == $data['re_pass']){
					if($user->register($data)){
						echo 'done';
					}else{
						echo 'Email already exists';
					}
				}else{
					echo "Passwords not matched";
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