<?php
require_once 'includes/db.php'; 
	 if(isset ($_POST['submit'])){
	 	$err = [];
	 		if(empty($_POST['login'])) $err[]='Введите логин';

	 		elseif(!empty($_POST['login']) && !preg_match("/^[a-zA-Z0-9]+$/", $_POST['login'])) $err[] = 'Логин может состоять только из букв латинского алфавита и цифр';

	 		if(empty($_POST['email'])) $err[]='Введите email';

	 		if(empty($_POST['birthday'])) $err[]='Введите дату рождения';
	 			
	 		elseif(strlen($_POST['login'])< 3 or strlen($_POST['login']) >30) $err[] = 'Логин не может быть короче 3х и длинее 30 символов';
	 	
	 		if(empty($_POST['password1']) or empty($_POST['password2'])) $err[]='Введите пароль';

	 		if($_POST['password1'] !== $_POST['password2']) $err[]='Пароли не совпадают';

	 		if($_POST['birthday'] == '01/01/1900') $err[]='Выберите дату рождения';

	 		$querry = mysqli_query($link, "SELECT id FROM users WHERE user_name = '" .mysqli_real_escape_string($link, $_POST['login']).  "'");

	 		if(mysqli_num_rows($querry) > 0) $err[]='Пользователь с таким именем уже существует';
	 		
	 		if(count($err) == 0){
	 			$login = trim(strip_tags(stripcslashes(htmlspecialchars($_POST['login']))));
	 			$password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
	 			$email = trim($_POST['email']);
	 			$birthDate = trim(strip_tags(stripcslashes(htmlspecialchars($_POST['birthday']))));
	 			$querry = mysqli_query($link, "INSERT INTO users SET user_name='".$login."',password ='".$password."', email='".$email."', birth_date ='".$birthDate."'"); 
	 			if($querry) {
	 				setcookie("newUser", $_POST['login'], time()+60*60*24*30, "/");
					header('Location: /index.php');
	 				//echo '<br>'.'<pre style=" text-align:center;>';
					//echo 'Авторизация прошла успешно';
					//unset($_POST);
					//echo '</pre>';
	 			}else echo 'Ошибка регистрации'.mysqli_error($link);
	 			
	 		}else{
	 			echo '<wrap class="err_wrap">'.'<div class ="error_msg">';
	 			foreach ($err as $value) {
	 				echo "<h3 id = \"error\"> $value </h3>";
	 			}echo '</div>'.'<wrap class="err_wrap">';

	 		}

	 	}
	?>


<!DOCTYPE html>
<html lang="en">
<head>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
$(function() {
  $('input[name="birthday"]').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    minYear: 1901,
    maxYear: parseInt(moment().format('YYYY'),10)
  }, function(start, end, label) {
    var years = moment().diff(start, 'years');
    alert("You are " + years + " years old!");
  });
});
</script>

	<meta charset="UTF-8">
	<title>Mysite</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

	 <form method="post" action="" class="login">
    <p>
      <label for="login">Логин:</label>
      <input type="text" name="login" id="login" placeholder="user" value="<? echo $_POST['login']?>">
    </p>
    <p>
      <label for="login">Email:</label>
      <input type="email" name="email" id="login" placeholder="name@example.com" value="<? echo $_POST['email']?>">
    </p>

    <p>
      <label for="password1">Пароль:</label>
      <input type="password" name="password1" id="password" placeholder="**********">
    </p>
    <p>
      <label for="password2">Пароль:</label>
      <input type="password" name="password2" id="password" placeholder="**********">
    </p>
    <p>
    	<label for="password2">Дата рождения:</label>
    	<input type="text" name="birthday" value ="01/01/1900" />
    </p>
    <div id="wrapButton">
    <p class="login-submit">
      <button type="submit" name ="submit" class="bubbly-button">Регистрация</button>
    </p>
	
    <p class="forgot-password"><a href="index.html">Забыл пароль?</a></p>
</div>
  </form>


	
</body>
</html>