<?php
require_once 'phpQuery.php';
require_once 'includes/db.php';
require_once 'functions.php';
define ('SITENAME', 'https://index.minfin.com.ua/markets/fuel/tm/'); 

$html = file_get_contents(SITENAME);

$dom = phpQuery::newDocument($html);
$i=0;
$brands_arr = [];

foreach($dom->find('table[class="zebra"] td[align="left"]') as $key => $value){  // создаем двумерный массив ключами кторого будут 
    $pq = pq($value);                                                            // массивы с брендами топлива
    $brands_arr [$pq->text()] = array();
    $trPrice = $dom->find("table[class=\"zebra\"] tr:gt({$i})");

    foreach ($trPrice as $v){                               // Перебираем обьект ДОМ вставляем в двумерный массив цену на бенз A95+
        $res = pq($v);                                      // делаем обьект jQuery;
        $result = $res->find('td[align="right"]:first');    // ищем в нем по селектору 
            if($result->text() == ''){                      // есть заправки у которых цена = '' тогда пи вставляем значение НЕТ                                
                $brands_arr [$pq->text()] += ["A95+" => "NET"];
            }else{
                $brands_arr [$pq->text()] += ["A95+" => "{$result->text()}"];// если цена есть то вставляем результат работы метода text();
            }
    }

    foreach ($trPrice as $v){                               // Перебираем обьект ДОМ вставляем в двумерный массив цену на бенз A95
        $res = pq($v);                                      // делаем обьект jQuery;
        $result = $res->find('td[align="right"]:eq(1)');    // ищем в нем по селектору 
            if($result->text() == ''){                      // есть заправки у которых цена = '' тогда пи вставляем значение НЕТ                                
                $brands_arr [$pq->text()] += ["A95" => "NET"];
            }else{
                $brands_arr [$pq->text()] += ["A95" => "{$result->text()}"];// если цена есть то вставляем результат работы метода text();
            }
    }

    foreach ($trPrice as $v){                               // Перебираем обьект ДОМ вставляем в двумерный массив цену на бенз A92
        $res = pq($v);                                      // делаем обьект jQuery;
        $result = $res->find('td[align="right"]:eq(2)');    // ищем в нем по селектору 
            if($result->text() == ''){                      // есть заправки у которых цена = '' тогда пи вставляем значение НЕТ                                
                $brands_arr [$pq->text()] += ["A92" => "NET"];
            }else{
                $brands_arr [$pq->text()] += ["A92" => "{$result->text()}"];// если цена есть то вставляем результат работы метода text();
            }
    }

    foreach ($trPrice as $v){                               // Перебираем обьект ДОМ вставляем в двумерный массив цену на бенз DT
        $res = pq($v);                                      // делаем обьект jQuery;
        $result = $res->find('td[align="right"]:eq(3)');    // ищем в нем по селектору 
            if($result->text() == ''){                      // есть заправки у которых цена = '' тогда пи вставляем значение НЕТ                                
                $brands_arr [$pq->text()] += ["DT" => "NET"];
            }else{
                $brands_arr [$pq->text()] += ["DT" => "{$result->text()}"];// если цена есть то вставляем результат работы метода text();
            }
    }

    foreach ($trPrice as $v){                               // Перебираем обьект ДОМ вставляем в двумерный массив цену на LPG
        $res = pq($v);                                      // делаем обьект jQuery;
        $result = $res->find('td[align="right"]:eq(4)');    // ищем в нем по селектору 
            if($result->text() == ''){                      // есть заправки у которых цена = '' тогда пи вставляем значение НЕТ                                
                $brands_arr [$pq->text()] += ["LPG" => "NET"];
            }else{
                $brands_arr [$pq->text()] += ["LPG" => "{$result->text()}"];// если цена есть то вставляем результат работы метода text();
            }
    }
    $i++;
};

//==========================Описываем логику работы с БД

foreach($brands_arr as $key => $value ){            //создем таблицы с брендом если не существуют

$sql_create_table = "CREATE TABLE IF NOT EXISTS `$key` (
                    a95plus VARCHAR(10),
                    a95 VARCHAR(10),
                    a92 VARCHAR(10),                                    
                    dt VARCHAR(10),
                    lpg VARCHAR(10),
                    request_date TIMESTAMP DEFAULT current_timestamp()
                    )";                             // В этом цикле самодокументирующийся код

$sql_add_prices = "INSERT INTO `$key` (a95plus, a95, a92, dt, lpg)
                   VALUES ('".$value['A95+']."', '".$value['A95']."', '".$value['A92']."', '".$value['DT']."', '".$value['LPG']."')";

$query = mysqli_query($link, $sql_create_table);
if(!$query) printf(mysqli_error($link));
$query = mysqli_query($link, $sql_add_prices);
if(!$query) printf(mysqli_error($link)); 
};
//debug($query);

debug($brands_arr);


