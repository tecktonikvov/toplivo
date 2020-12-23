<?php
if(isset ($_POST['submit'])){
	 	$err = [];
	 		if(empty($_POST['login'])) $err[]='Введите логин';
	 		elseif(!empty($_POST['login']) && !preg_match("/^[a-zA-Z0-9]+$/", $_POST['login'])) $err[] = 'Логин может состоять только из букв латинского алфавита и цифр';	
	 		elseif(strlen($_POST['login'])< 3 or strlen($_POST['login']) >30) $err[] = 'Логин не может быть короче 3х и длинее 30 символов';
	 		if(empty($_POST['password'])) $err[]='Введите пароль';
	 		$querry = mysqli_query($link_autorise, "SELECT user_name, password FROM users WHERE user_name = '" .mysqli_real_escape_string($link, $_POST['login']).  "'");
	 		$data = mysqli_fetch_assoc($querry);
	 		if(count($err) == 0){
				mysqli_error($link_autorise);

				if($data['user_name'] == $_POST['login']) {
					if(password_verify($_POST['password'] , $data['password'])){
						
						setcookie("id", $data['user_name'], time()+60*60*24*30, "/");
						header('Location: /fuell.php');
						echo 'Авторизация прошла успешно';
						unset($_POST);
					}else{
						$errMsg = 'пароль или логин неверны';
						pre($errMsg);
					}
	 			}
	 		}else{
	 			foreach ($err as $value) {
	 			pre($value);
	 			}
	 	}
	 }
?>
<body>

	 <form method="post" action="" class="login">
    <p>
      <label for="login">Логин:</label>
      <input type="text" name="login" id="login" placeholder="user" value="<? echo $_POST['login']?>">
    </p>
    <p>
      <label for="password">Пароль:</label>
      <input type="password" name="password" id="password" placeholder="**********">
    </p>
    <div id="wrapButton">
    	<p class="forgot-password"><a href="index.php">Забыл пароль?</a></p>
    		<p class="login-submit">
     			<button type="submit" name ="submit" class="bubbly-button">Войти</button>
    		</p>
    	<p><a href="reg.php" class="bubbly-button" id="reg">Регистрация</a></p>

</div>
  </form>
	
</body>