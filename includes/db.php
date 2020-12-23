<?php
$link = mysqli_connect('mysql.zzz.com.ua', 'rootttt', 'Vova24726224', 'tecktnikvov');
if(!$link){
	die(mysqli_connect_errno().'<br>'.mysqli_connect_error());
}else mysqli_set_charset($link, "utf8");	

$link_autorise = mysqli_connect('mysql.zzz.com.ua', 'rootttt', 'Vova24726224', 'tecktnikvov');
if(!$link){
	die(mysqli_connect_errno().'<br>'.mysqli_connect_error());
}else mysqli_set_charset($link, "utf8");
