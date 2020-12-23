<?php
require_once 'includes/db.php';
$userName = $_COOKIE['id'];
$userLogo = 'userLogoDefault.png';
function pre($msg){
	echo '<wrap class="err_wrap">'.'<div class ="error_msg">';
	echo "<h3 id = \"error\"> $msg </h3>";
	echo '</div>'.'<wrap class="err_wrap">';
}

if(isset($_POST['logout'])){
	setcookie("id", "", time() - 3600);
	unset($_POST);
	header('Location: /index.php');
}

if(isset($_COOKIE['id'])){
		require_once 'header.php';
}
if(isset($_COOKIE['newUser'])){
    header("refresh: 2; url=fuell.php");
	setcookie("id",$_COOKIE['newUser'], time()+60*60*24*30, "/");
	setcookie("newUser", '', time() - 3600, "/");
	$hi = "Вы успешно зарегистрированы!) ";
	pre($hi);
	echo "<form  action=\"\" method=\"post\" >
		<button type=\"submit\" class=\"bubbly-button\" name=\"logout\">Выйти</button>
	</form>";
} 

if(!isset($_COOKIE['id']) && !isset($_COOKIE['newUser'])) include 'loginForm.php';

	?>
<!DOCTYPE html>
<html lang="en">
<head>
	<script src="https://kit.fontawesome.com/083aa882ee.js" crossorigin="anonymous"></script>
	<meta charset="UTF-8">
	<title>Mysite</title>
	<link rel="stylesheet" href="css/style.css">
</head>

</html>