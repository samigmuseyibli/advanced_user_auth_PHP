<?php  
include 'config.php';
$user = new User();
session_start();
if (isset($_GET['logout']) && $user->xss($_POST,true)){
    $user->user_logout();
    header("location: ../index");
}
if (!$user->get_session())
{
    header("location:../index");
}
if($_SESSION['user']) {
	print_r($_SESSION['user']);
}
?>
<a href="profile?logout=logout">LOGOUT</a>