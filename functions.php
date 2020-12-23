<?php 
require_once 'includes/db.php';

function debug($array){
    echo '<pre>';
    var_dump($array);
    echo '</pre>';
};

//                                       Функция сортировки по цене:




//сортировка по УБЫВАНИЮ A95+.
function toLowerA95plus($x, $y) {

    if ($x['a95plus'] == 'NET') $x['a95plus'] = 0; // Костыль, что бы НЕТ было внизу списка
    if ($y['a95plus'] == 'NET') $y['a95plus'] = 0; 

    if ($x['a95plus'] < $y['a95plus']) {
        return true;
    } else if ($x['a95plus'] > $y['a95plus']) {
        return false;
    }else {
        return 0;
    }
}
//сортировка по УБЫВАНИЮ A95.
function toLowerA95($x, $y) {
    
    if ($x['a95'] == 'NET') $x['a95'] = 0; // Костыль, что бы НЕТ было внизу списка
    if ($y['a95'] == 'NET') $y['a95'] = 0; 

    if ($x['a95'] < $y['a95']) {
        return true;
    } else if ($x['a95'] > $y['a95']) {
        return false;
    } else {
        return 0;
    }
}
//сортировка по УБЫВАНИЮ a92.
function toLowerA92($x, $y) {
    
    if ($x['a92'] == 'NET') $x['a92'] = 0; // Костыль, что бы НЕТ было внизу списка
    if ($y['a92'] == 'NET') $y['a92'] = 0; 

    if ($x['a92'] < $y['a92']) {
        return true;
    } else if ($x['a92'] > $y['a92']) {
        return false;
    } else {
        return 0;
    }
}
//сортировка по УБЫВАНИЮ dt.
function toLowerDT($x, $y) {
    
    if ($x['dt'] == 'NET') $x['dt'] = 0; // Костыль, что бы НЕТ было внизу списка
    if ($y['dt'] == 'NET') $y['dt'] = 0; 

    if ($x['dt'] < $y['dt']) {
        return true;
    } else if ($x['dt'] > $y['dt']) {
        return false;
    } else {
        return 0;
    }
}
//сортировка по УБЫВАНИЮ LPG.
function toLowerLPG($x, $y) {
    
    if ($x['lpg'] == 'NET') $x['lpg'] = 0; // Костыль, что бы НЕТ было внизу списка
    if ($y['lpg'] == 'NET') $y['lpg'] = 0; 

    if ($x['lpg'] < $y['lpg']) {
        return true;
    } else if ($x['lpg'] > $y['lpg']) {
        return false;
    } else {
        return 0;
    }
}


//                 ПО ВОЗРАСТАНИЮ===================================================

//сортировка по ВОЗРАСТАНИЮ a95plus.
function toHigherA95plus($x, $y) {
    
    if ($x['a95plus'] > $y['a95plus']) {
        return true;
    } else if ($x['a95plus'] < $y['a95plus']) {
        return false;
    } else {
        return 0;
    }
}
//сортировка по ВОЗРАСТАНИЮ a95.
function toHigherA95($x, $y) {
    if ($x['a95'] > $y['a95']) {
        return true;
    } else if ($x['a95'] < $y['a95']) {
        return false;
    } else {
        return 0;
    }
}
//сортировка по ВОЗРАСТАНИЮ a92.
function toHigherA92($x, $y) {
    if ($x['a92'] > $y['a92']) {
        return true;
    } else if ($x['a92'] < $y['a92']) {
        return false;
    } else {
        return 0;
    }
}
//сортировка по ВОЗРАСТАНИЮ dt.
function toHigherDT($x, $y) {
    if ($x['dt'] > $y['dt']) {
        return true;
    } else if ($x['dt'] < $y['dt']) {
        return false;
    } else {
        return 0;
    }
}
//сортировка по ВОЗРАСТАНИЮ lpg.
function toHigherLPG($x, $y) {
    if ($x['lpg'] > $y['lpg']) {
        return true;
    } else if ($x['lpg'] < $y['lpg']) {
        return false;
    } else {
        return 0;
    }
}

//=============================================

function getAllBrands(){ // возвращяет индексный массив со значениями имена брендов
    global $link;
    $sql_brands_name = "SHOW TABLES FROM tecktnikvov";
    $query = mysqli_query($link, $sql_brands_name);
    //debug($query);
    foreach ($query as $key =>$value){
        $brand_name[] = $value['Tables_in_tecktnikvov'];  // Такой индекс массива возращяет база|
    }
    unset($brand_name[array_search('users', $brand_name)]);
    return $brand_name;
}

function getUserBrands(){
    $user =$_COOKIE['id']; 
    global $link_autorise;

    $query = "SELECT user_brands FROM `users` WHERE user_name = '$user'";
     // берем колонку с брендами конкретного user_name запихуем в массив индексный
    $result = mysqli_query($link_autorise, $query);
    if(!$result) printf(mysqli_error($link_autorise));
    list($view_brands) = mysqli_fetch_row($result);
    return explode(',', $view_brands); // получаем строку со списком брендов через запятую
}


function showUserBrands(){
    $arrAllBrands = getAllBrands();      //записываем в эту переменную результат работы функции, это массив с всеми существующими брендами.
    $arrBrandsFromDB = getUserBrands();  //$arrBrandsFromDB это массив с актуальными брендами юзера из DB
    print "<form action = \"\" method =\"post\"><div><h2>Выберите интерисующие Вас АЗС:</h2><button type = \"submit\" name = \"save_azs\">Сохранить</button></div>";
    print "<div class=\"user_brand\">"."\n";
    print "<fieldset id=\"shest\">"."\n";
    print "<div></div>";
    print "<legend><input type=\"checkbox\" onchange = \"selectAllBrands()\" id = \"chk_all\"> Check all</legend>"."\n";
    print "<div class=\"wrap_brands\">";
    foreach ($arrAllBrands as $value){
        foreach ($arrBrandsFromDB as $value2){
            if(strcasecmp($value2, $value) == 0){  // если регистронезависимое сравнение вернет 0 то строки равны
                print "<span class = \"span_brand brand_selected\" id = \"span_{$value2}\" ><input type=\"checkbox\" id=\"{$value2}\" name = \"{$value2}\" onchange = \"changeColor('$value2')\" checked/> $value2</span>"."\n";
                continue 2; // выходим в след. итерацию внешнего цикла
            }
        }
        print "<span class = \"span_brand\"  id = \"span_{$value}\"><input type=\"checkbox\" id=\"{$value}\" name = \"{$value}\" onchange = \"changeColor('$value')\" /> $value</span>"."\n"; 
    }
    print "</div>"."\n";
    print "</fieldset>"."\n";
    print "</div>"."\n";
    print "</form>"."\n"; 
}

function setUserBrands(){
    global $link_autorise;
    $user = $_COOKIE['id'];
    // Блок КОСТЫЛЬ, почему то атрибут name от чекбоксов передает значения с замененным в них пробелом на "_", и при сравнении 
    // выбраных брендов с общим списком те в которых _ не проходят, тут делаем замену _ обратно на пробелы. и перезаписываем в массив
    foreach ($_POST as $k => $v){
        $_POST[$k] = str_replace('_', ' ', $k);     // здесь мы устанавливаем ключ в значение, потому что чекбокс возвращяет 'brand' => 'on'
    }
    
    unset($_POST['save_azs']);                                  //убираем кнопку сабмит

    // Фомируем  SQL строку для добавления в БД
    $string = '';
    foreach ($_POST as $key => $value){
            $string .= $value . ','; 
    }
    $string = substr($string, 0 ,-1);

    $query = "UPDATE users
              SET user_brands = '$string'
              WHERE user_name = '$user'";

    $result = mysqli_query($link_autorise, $query);
    if(!$result) printf(mysqli_error($link_autorise)); 
    
    unset($arrBrandsFromDB);
    
}
?>




