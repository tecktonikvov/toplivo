<?php
require_once 'vendor/autoload.php';
require_once 'phpQuery.php';
require_once 'functions.php';

echo '<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">';
echo '<link href="style/style.css" rel="stylesheet">';
echo '<div id = "main_search">Обработка</div>';
echo '<progress id="progress" max="100" value="0"></progress>';

set_time_limit(0);
if(!isset($_POST['repeatSearch'])) {
    $file = 'list.txt';
}else{
    $file = $_POST['tracks'];
}
$array_id = [];
$dubble = [];

if(!isset($_POST['repeatSearch'])){
    if(file_exists('link_list.txt')) unlink('link_list.txt');
    if(file_exists('list_validated.txt')) unlink('list_validated.txt');
}

if(file_exists('no_results_translated.txt')) unlink('no_results_translated.txt'); // Перед стартом скрипта удаляем старые файлы
if(file_exists('no_results_last.txt')) unlink('no_results_last.txt');
if(file_exists('no_results.txt')) unlink('no_results.txt');

$client = new \GuzzleHttp\Client(['cookies' => true,
                                'base_uri' => 'https://z1.fm/']);
$r = $client->request('GET', 'http://httpbin.org/cookies');


validate($file); // Делает замены не валидных символов и кладёт результат в файл list_validated.txt ПЕРЕЗАПИСЫВАЕТ ФАЙЛ
if(isset($_POST['repeatSearch'])) {
    $track_list = validate($file);
}else {
    $track_list = file('list_validated.txt'); // Читает содержимое файла и помещяет каждую строку в массив;
}

$track_list = array_diff($track_list, array('', " ", "\r\n")); // Если в list.txt содержатся пустые строки то мы их убираем из массива

progress( 'start', 0, 1 );
$n = 100 / count($track_list);
$i = 0;

foreach($track_list as $v) {

    $search = $v;
    $search_url = 'mp3/search?keywords=' . urlencode($search);
    $r = $client->request('GET', 'https://z1.fm/'); // на этом запросе мы получаем куки
    sleep(1);
    $r = $client->request('GET', $search_url);   //  на этом получаем страницу с результатми поиска

    if ($r->getStatusCode() != '200') die('Status code:' . $r->getStatusCode());

    $str_body = (string)$r->getBody();

    $dom = phpQuery::newDocument($str_body);

    $array = $dom->find(".song-xl:first > ul > li");
    $file_id = htmlentities(pq($array)->html()); // делаем из HTML string
    $pos = strpos($file_id, 'data-url=');        // Находим позию входа подстроки
    $file_id = substr($file_id, $pos + 9, 30);      // обрезаем начиная от позиции входа подстроки +длинна самой подстроки, длинной 30 символов

    progress( 'run', $i, 1 );
    $i += $n;

    if ($file_id !== false) {
        $file_id = preg_replace("/[^,.0-9]/", '', $file_id);  // вырезаем из строки только цифры, это на ID файла

        if (array_search($file_id, $array_id) == false) {
            file_put_contents('link_list.txt', 'https://z1.fm/download/' . $file_id . "\r\n", FILE_APPEND);// Проверка на повторяющиеся ссылки
            $array_id[] = $file_id;  // Добавляем ID треков в массив для проверки на двойников. И во втором цикле тоже
        }elseif(array_search($file_id, $array_id)){
            $dubble[] = $file_id; // Если найден трек двонийк добавим его ИД в массив
        }

        //sleep(1);
    } else {
        file_put_contents('no_results.txt', $v, FILE_APPEND);//
    }
}
file_put_contents('no_results.txt', '', FILE_APPEND);// Создаем пустой файл если он сайм не создался

if(sizeof(file('no_results.txt')) >= 1) $exception = file('no_results.txt'); // Если есть не найденные треки то закидываем их в переменную.
//echo sizeof(file('no_results.txt'));

progress( 'stop', 100, 1 );

if(sizeof(file('no_results.txt')) >= 1) {
    explode_and_edit($exception);
    $translated_excetpion = file('no_results_translated.txt');
    $translated_excetpion = array_diff($translated_excetpion, array('', '-', " ", "\r\n"));


    progress('start', 0, 1);
    $n = 100 / count($translated_excetpion);
    $i2 = 0;

    foreach ($translated_excetpion as $v) {
        $search = $v;
        $search_url = 'mp3/search?keywords=' . urlencode($search);
        $r = $client->request('GET', 'https://z1.fm/'); // на этом запросе мы получаем куки
        sleep(1);
        $r = $client->request('GET', $search_url);   //  на этом получаем страницу с результатми поиска

        //if($r->getStatusCode() != '200') die('Status code:' . $r->getStatusCode());

        $str_body = (string)$r->getBody();

        $dom = phpQuery::newDocument($str_body);

        $array = $dom->find(".song-xl:first > ul > li");
        $file_id = htmlentities(pq($array)->html());                                // делаем из HTML string
        $pos = strpos($file_id, 'data-url=');                                // Находим позию входа подстроки
        $file_id = substr($file_id, $pos + 9, 30);                      // обрезаем начиная от позиции входа подстроки + длинна самой подстроки, длинной 30 символов

        progress('run', $i2, 1);
        $i2 += $n;

        if ($file_id !== false) {
            $file_id = preg_replace("/[^,.0-9]/", '', $file_id);                                                  // вырезаем из строки только цифры, это на ID файла


            if (array_search($file_id, $array_id) == false) {
                file_put_contents('link_list.txt', 'https://z1.fm/download/' . $file_id . "\r\n", FILE_APPEND); // Проверка на повторяющиеся ссылки
                $array_id[] = $file_id;                                                                                           // Добавляем ID треков в массив для проверки на двойников.
            } elseif (array_search($file_id, $array_id)) {
                $dubble[] = $file_id;                                                                                             // Если найден трек двонийк добавим его ИД в массив
            }

        } else {
            file_put_contents('no_results_last.txt', $v, FILE_APPEND);
        }
        //sleep(1);
    }
    progress('stop', 100, 1);
}
//==================================================================================================================


//========================================================================================================
///////////////////////СТАТИСТИКА
///=============================================================================
$founded_tracks = sizeof (file ('link_list.txt'));
$ordered_tracs = sizeof (file ('list.txt'));

if(file_exists('no_results_last.txt')){
    $not_fouded_tracks = sizeof (file ('no_results_last.txt'));
}else{
    $not_fouded_tracks = 0;
}


$count_tracks = count($track_list);  // Из массива у которого Триманы пустые строки
$double_tracks = count($dubble);

if(!isset($_POST['repeatSearch'])) {
    file_put_contents('statistic.txt', $count_tracks . "\r\n" . $double_tracks); // При первом поиске мы кладем данные статистики в файл
}                                                                                             // Иначе они пропадут когда стр обновится
if(isset($_POST['repeatSearch'])) {
    $array_statistic = file('statistic.txt');                                         // Когда нажимаем на повторный поиск то вытягиваем данные статистики из файла
    $count_tracks = $array_statistic[0];                                                      // И пихаем их в те переменные которые и были.
    $double_tracks = $array_statistic[1];                                                     // Профит статистика сохранена
}

$count =  $unique_tracks - $double_tracks;
$unique_tracks = $count_tracks - $double_tracks;  // Тут лежат все не повторяющиеся id треков



echo "<h1>Success!  Мы нашли $founded_tracks из  $unique_tracks треков </h1>";
if($not_fouded_tracks != 0) echo '<h3>Не найдено ' . $not_fouded_tracks . ' треков </h3>';
else {
    echo '<h3>Все треки найдены</h3>';
    unset($_POST['repeatSearch']);
}

print '<table border="1px">';
print '<tr>';

print '<tr><td>Заявлено треков:</td><td>' . $count_tracks . '</td></tr>';
print '<tr><td>Из них повторяются:</td><td>' . $double_tracks . '</td></tr>';
print '<tr><td>Треков без повторяющихся:</td><td>' . $unique_tracks . '</td></tr>';
print '<tr><td>Найдено треков:</td><td>' . $founded_tracks . '</td></tr>';
print '<tr><td>Не найдено треков:</td><td>' . $not_fouded_tracks . '</td></tr>';
if($not_fouded_tracks != 0) {
    print '<details><summary>Показать не найденые треки:</summary><form action="" method="post">
    <textarea id="tracks" name="tracks" rows="30" cols="1145">' . file_get_contents('no_results_last.txt') . '</textarea><button name="repeatSearch" type="submit">Повторить поиск</form></details>';
}

print '</tr>';

print '</table>';


