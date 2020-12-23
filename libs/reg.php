<!DOCTYPE html>
<html lang="en">
<head>
	<? require_once 'includes/db.php'; ?>
	<meta charset="UTF-8">
	<title>Mysite</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

	 <form method="post" action="index.php" class="login">
    <p>
      <label for="login">Логин:</label>
      <input type="text" name="login" id="login" value="name@example.com">
    </p>

    <p>
      <label for="password1">Пароль:</label>
      <input type="password" name="password1" id="password" value="4815162342">
    </p>
    <p>
      <label for="password2">Пароль:</label>
      <input type="password" name="password2" id="password" value="4815162342">
    </p>

    <p class="login-submit">
      <button type="submit" class="login-button">Войти</button>
    </p>

    <p class="forgot-password"><a href="index.html">Забыл пароль?</a></p>
  </form>


	
</body>

<?php
	 if(isset ($_POST['submit'])){
	 	$err = [];

	 		if(!preg_match("/^[a-zA-Z0-9]+$/", $_POST['login'])) $err[] = 'Логин может состоять только из букв латинского алфавита и цифр';
	 
	 		if(strlen($_POST['login'])< 3 or strlen($_POST['login'] >30)) $err[] = 'Логин не может быть короче 3х и длинее 30 символов';

	 		$querry = mysqli_query($link, "SELECT id FROM users WHERE user_name = '" .mysqli_real_escape_string($link, $_POST['login']).  "'");
	 		if(mysqli_num_rows($query)>0) $err[]='Пользователь с таким именем уже существует';

	 		if(count($err) == 0){
	 			$login = $_POST['login'];
	 			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	 			$email = $_POST['email'];
	 			$birthDate = $_POST['birthDate'];
	 		}
	 	}
	?>
</html>