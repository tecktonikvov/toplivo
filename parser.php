<?php 
require_once 'phpQuery.php';
require_once 'includes/db.php';
define ('SITENAME', 'https://digit.com.ua/shop/category/telefoniia/mobilnye-telefony?order=&lp=0&rp=56430&p%5B79292%5D%5B%5D=OnePlus'); 
$html = file_get_contents(SITENAME);
$dom = phpQuery::newDocument($html);
// Ищем в объекте dom элемент с id items-catalog-main, обращаясь к методу find(). Он вмещает в себя все данные о продукте.

$parsed_products = [];
$i=1;
foreach($dom->find('span[class="title"]') as $key => $value){
    // Преобразуем dom объект в объект phpQuery. Делаем сие действие с помощью метода pq(); который является аналогом ($) в jQuery.
    $pq = pq($value);
    $parsed_products ["phone $i"] = array ('product_name' => $pq->text());
    $i++;
};

$i=1;
foreach($dom->find('span[class="code d_b"]') as $key => $value){
    $pq = pq($value);
    $parsed_products["phone $i"]['code'] =  substr($pq->text(), -6) * 1;
    $i++;
};

$i=1;
foreach($dom->find('div[class="catalog-prices"]') as $key => $value){
    $pq = pq($value);
    $parsed_products["phone $i"]['price'] =  trim($pq->text());
    $i++;
};

foreach ($parsed_products as $value){
    if(!isset($value['code']) OR !isset($value['price'])) continue;
    $query = mysqli_query($link,"INSERT INTO iphones (product_name, catalog_number, product_price) 
                                VALUES ('".$value['product_name']."', '".$value['code']."', '".$value['price']."')");
    if(!$query) printf(mysqli_error($link));  
}


//$query = 'INSERT INTO iphones (product_name, catalog_numbet, product_price) values("", '123456')';
echo '<pre>';
var_dump($parsed_products);
echo '</pre>';

