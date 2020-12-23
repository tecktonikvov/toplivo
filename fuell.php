<?php 
require_once 'header.php';
require_once 'includes/db.php';
require_once 'functions.php';

$order = $_GET['order']; // Записываем значение сортировки в переменную, по умолчанию выводится из базы без сортировки

// Создать заголовок таблицы
print "<table border = 1 class=\"toplivo\">";
print "<tr>
<th><a href=\"fuell.php?order=0\">АЗС</a></th>

<th>A95+ <a href=\"fuell.php?order=toLowerA95plus\"><i class=\"fas fa-long-arrow-alt-up\"></i></a><a href=\"fuell.php?order=toHigherA95plus\"><i class=\"fas fa-long-arrow-alt-down\"></a></i></th>

<th>A95 <a href=\"fuell.php?order=toLowerA95\"><i class=\"fas fa-long-arrow-alt-up\"></i></a><a href=\"fuell.php?order=toHigherA95\"><i class=\"fas fa-long-arrow-alt-down\"></a></i></th>

<th>A92 <a href=\"fuell.php?order=toLowerA92\"><i class=\"fas fa-long-arrow-alt-up\"></i></a><a href=\"fuell.php?order=toHigherA92\"><i class=\"fas fa-long-arrow-alt-down\"></a></i></th>

<th>DT <a href=\"fuell.php?order=toLowerDT\"><i class=\"fas fa-long-arrow-alt-up\"></i></a><a href=\"fuell.php?order=toHigherDT\"><i class=\"fas fa-long-arrow-alt-down\"></a></i></th>

<th>LPG <a href=\"fuell.php?order=toLowerLPG\"><i class=\"fas fa-long-arrow-alt-up\"></i></a><a href=\"fuell.php?order=toHigherLPG\"><i class=\"fas fa-long-arrow-alt-down\"></a></i></th>
</th></tr>";

$brand_name = getUserBrands();
if ($brand_name[0] == "") $brand_name = getAllBrands();
foreach($brand_name as $key => $value) {

    // Создать и выполнить запрос.
    // Выбранные данные сортируются по убыванию столбца $key DESC - сортировка по убыванию
    $query = "SELECT a95plus, a95, a92, dt, lpg FROM `$value` LIMIT 1";
    $result = mysqli_query($link, $query);
    if(!$result) printf(mysqli_error($link)); 

    // В этом блоке на каждой итерации цикла мы берем каждую строку из результата запроса SQL и записываем переменные, и потом из 
    // этих пермененных лепим двумерный массив, ключами которояго являются бренды а значения массив маркаТоплива => цена
    list($a95plus, $a95, $a92, $dt, $lpg) = mysqli_fetch_row($result);
    $arr[$brand_name[$key]] = ['a95plus' => $a95plus, 'a95' => $a95, 'a92' => $a92, 'dt' => $dt,'lpg' => $lpg];

}

// Сортируем таблицу массив по ключу из ГЕТ запрос.
if($order) uasort($arr, $order);

// Блок форматирования и вывода таблицы================================================
foreach ($arr as $key => $value){

    $value = array_values($value); // переиндексируем асоциативный массив в индексный
    list($a95plus, $a95, $a92, $dt, $lpg) = $value;  // функция list работает только с индексными массивами, каждый елемент массива записывает в переменную

    print "<tr>";
    print "<td>$key</td><td>$a95plus</td><td>$a95</td><td>$a92</td><td>$dt</td><td>$lpg</td>";
    print "</tr>";

}
// Завершить таблицу
print "</table>";


// ======================Отрисовка Гафиков ======----------------------------------

//$tableName = 'amic';
//
//$query = "SELECT `request_date`, `a95plus`, `a95`,`dt`, `lpg` FROM `$tableName`;";
//$result = mysqli_query($link, $query);
//
//while ($record = $result->fetch_row()){
//    $all[] =  array(strtotime($record[0]), $record[1], $record[2], $record[3], $record[4]);
//    //debug($all);
//}
//echo json_encode($all);
 

