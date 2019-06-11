<?php 
session_start();
include 'include/config.php';
$user = new User();
if ($user->get_session())
{
    header("location: include/profile");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Management</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="assets/fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="assets/css/style.css">
	<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
	<style>
	 .captcha{width: 39%;line-height: 37px;float: left;}
	 .img-reload{cursor: pointer;float: left;width: 50px;}
	 .img-captcha{float:left;}
	 .cursor{cursor:pointer;}
	</style>
</head>
<body>

    <div class="main">
		<!-- Sing in  Form -->
        <section>
            <div class="container sign-in">
                <div class="signin-content">
                    <div class="signin-image">
                        <figure><img src="assets/images/signin-image.jpg" alt="sing up image"></figure>
                        <a onclick="return(setReg());" class="signup-image-link cursor">Create an account</a>
                    </div>

                    <div class="signin-form">
                        <h2 class="form-title">Sign in</h2>
                        <form method="POST" class="register-form" action="include/login" id="login-form">
							<div style="display:none">
								<input type="hidden" name="csrf_test_name" value="de7e46525678b394c4c45f19e06b9943">
							</div>
                            <div class="form-group">
                                <label for="your_name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="email" name="email" placeholder="Your Email" autocomplete="off"/>
                            </div>
                            <div class="form-group">
                                <label for="your_pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="pass" placeholder="Password" autocomplete="off"/>
                            </div>
							<div class="form-group">
								<img alt="Captcha" class="img-captcha" id="captcha_img_sig" src="include/captcha.php">
								<input type="text" name="captchaCode" class="captcha" id="cap_input" autocomplete="off">
								<img alt="Reload" onclick="return(reloadCaptcha('sig'));" class="img-reload" src="assets/captcha/reload.png">
							</div>
							
                            <!--<div class="form-group">
                                <input type="checkbox" name="remember-me" id="remember-me" class="agree-term" />
                                <label for="remember-me" class="label-agree-term"><span><span></span></span>Remember me</label>
                            </div>-->
                            <div class="form-group form-button">
                                <span id="signin" class="form-submit" onClick="return(doLogin());"> Log in</span>
                            </div>
                        </form>
                        <div class="social-login">
                            <span class="social-label">Or login with</span>
                            <ul class="socials">
                                <li><a href="#"><i class="display-flex-center zmdi zmdi-facebook"></i></a></li>
                                <li><a href="#"><i class="display-flex-center zmdi zmdi-twitter"></i></a></li>
                                <li><a href="#"><i class="display-flex-center zmdi zmdi-google"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
			
            <div class="container signup" style="display:none;">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="form-title">Sign up</h2>
                        <form method="POST" class="register-form" action="include/register" id="register-form">
							<div style="display:none">
								<input type="hidden" name="csrf_test_name" value="de7e46525678b394c4c45f19e06b9943">
							</div>
                            <div class="form-group">
                                <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="name" id="name" placeholder="Your Name" autocomplete="off"/>
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="zmdi zmdi-email"></i></label>
                                <input type="email" name="email" id="email" placeholder="Your Email" autocomplete="off"/>
                            </div>
                            <div class="form-group">
                                <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="pass" id="pass" placeholder="Password" autocomplete="off"/>
                            </div>
                            <div class="form-group">
                                <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                                <input type="password" name="re_pass" id="re_pass" placeholder="Repeat your password" autocomplete="off"/>
                            </div>
							<div class="form-group">
								<img alt="Captcha" class="img-captcha" id="captcha_img_reg" src="include/captcha.php">
								<input type="text" name="captchaCode" class="captcha" id="cap_input" autocomplete="off">
								<img alt="Reload" onclick="return(reloadCaptcha('reg'));" class="img-reload" src="assets/captcha/reload.png">
							</div>
                            <div class="form-group">
                                <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                                <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree all statements in  <a href="#" class="term-service">Terms of service</a></label>
                            </div>
                            <div class="form-group form-button">
                                <span name="signup" id="signup" class="form-submit" onClick="return(doRegister());">Register</span>
                            </div>
                        </form>
                    </div>
                    <div class="signup-image">
                        <figure><img src="assets/images/signup-image.jpg" alt="sing up image"></figure>
                        <a onclick="return(setLogin());" class="signup-image-link cursor">I am already member</a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="http://bootstrap-notify.remabledesigns.com/js/bootstrap-notify.min.js"></script>
    <script src="assets/js/main.js"></script>
	<script>
			function notify(message, type, from, align){
				$.notify({
					message: message,
					icon: 'glyphicon glyphicon-warning-sign',
				}, {
					type: type,
					placement: {
						from: from,
						align: align
					},
					template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
						'<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
						'<span data-notify="icon"></span> ' +
						'<span data-notify="title">{1}</span> ' +
						'<span data-notify="message">{2}</span>' +
						'<div class="progress" data-notify="progressbar">' +
							'<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
						'</div>' +
						'<a href="{3}" target="{4}" data-notify="url"></a>' +
					'</div>' 
					
				});
			}
			function setLogin(){
				$('.signup').css('display','none');
				$('.sign-in').css('display','block');
			}
			function setReg(){
				$('.signup').css('display','block');
				$('.sign-in').css('display','none');
			}
			
			function doLogin(){
				var form = $("#login-form");
				 $.ajax({
                     url: form.attr('action'),
                     type: 'POST',
                     data: form.serialize(),
                     beforeSend: function() {
							$('#cnt_contact span').html('Sign in...');
                        },
                     success: function(data) {
                         data = $.trim(data);
                         if(data == "done"){
							notify('Successfully Signin','success','bottom','right');
							setTimeout(function(){
								window.location.href = "include/profile";
							}, 2000);
                         }else{
							notify(data,'danger','bottom','right');
                         }
                     },
                     error: function(e) {
                         console.log(e)
                     }
                 });
			}
			function doRegister(){
				var form = $("#register-form");
				$.ajax({
                     url: form.attr('action'),
                     type: 'POST',
                     data: form.serialize(),
                     beforeSend: function() {
							reloadCaptcha('reg');
							$('#signup').html('Registering...');
							$('#signup').attr('disbaled',true);
                        },
                     success: function(data) {
                         data = $.trim(data);
                         if(data == "done"){
							notify('Successfully Signup','success','bottom','right');
							$('#signup').html('Please wait...');
							setTimeout(function(){
								location.reload();
							}, 2000);
                         }else{
							notify('<br>'+data,'danger','bottom','right');
							$('#signup').attr('disbaled',false);
							$('#signup').html('Register');
                         }
                     },
                     error: function(e) {
                         console.log(e)
                     }
                 });
			}
			function reloadCaptcha(val){
					//cap_input = '';
					$('#captcha_img_'+val).attr('src','include/captcha?'+$.now());
				}
	</script>
</body>
</html>