<?php
if(isset($_POST['logout'])){
	setcookie("id", "", time() - 3600);
	unset($_POST);
	header('Location: /index.php');
	$a = "<script type='text/javascript'>
    document.location;
    </script>";
    echo $a;
}

if(isset($_POST['but']) && isset($_FILES['path']['tmp_name'])){
	$file = "avatar/".$_FILES['path']['name'];
 	move_uploaded_file($_FILES['path']['tmp_name'], $file);
	rename($file, "avatar/{$_COOKIE['id']}_ava.jpg");
		if(isset($_FILES['path']['name'])){
			$sucsess = "<div class=\"sucsess\">Аватар успешно загружен</div>";
		}else echo 'err';
}
$userLogo = 'avatar/'.$_COOKIE['id'].'_ava.jpg';
if(!file_exists($userLogo)) $userLogo = 'userLogoDefault.png';
$userName = $_COOKIE['id'];
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script type="text/javascript">
    document.write(document.location);
</script>
<script src="https://kit.fontawesome.com/083aa882ee.js" crossorigin="anonymous"></script>

	<script src="script.js"></script>
	<link rel="stylesheet" href="css/style.css">
	<meta charset="UTF-8">
</head>

<body>
<div class="head_wrap">
		<img src="<?=$userLogo?>" alt="">
		<h2> <?=$userName?> </h2>
		<form id="change_ava_form" action="" method="post" >
		    <button type="submit" onclick = "window.location.href = '/'" name="logout">Выйти</button>
		    <button type ="button" id="changeAva" onmousedown="viewDivHeadWrap()">Сменить аватар</button></br>
		    <?=$sucsess?>
		</form>
</div><hr>
<div class="navBar">
    <ul class="nav">
        <li><a href="pars.php">Спарсить</a></li>
        <li><a href="setings.php">Настройки</a></li>
        <li><a href="fuell.php">Таблица</a></li>
    </ul>
</div>

<div class = "blur_bg" id = "blur_bg">
</div>
	<div class="wrapForm" id="wrap_form">
		<form action="" enctype="multipart/form-data" method="post">	
			<div class="selectNewAva">
				<i class="fas fa-times fa-lg" onmousedown = "hideDiv()"></i>
				<input type="file" name ="path" title="Выберите ваш аватар"><br>
				<button type="submit" class= "bubbly-button" name="but">Загрузить аватар!</button>
			</div>
		</form>
	</div>

	

</body>
</html>
