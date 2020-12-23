<?php

require_once 'vendor/autoload.php';
require_once 'phpQuery.php';
set_time_limit(0);
$file = 'list.txt';
if (file_exists('no_result_translated.txt')) unlink('no_results_translated.txt'); // Перед стартом скрипта удаляем старые файлы
if (file_exists('no_results_last.txt')) unlink('no_results_last.txt');
if (file_exists('no_results.txt')) unlink('no_results.txt');
if (file_exists('link_list.txt')) unlink('link_list.txt');
if (file_exists('list_validated.txt')) unlink('list_validated.txt');

$client = new \GuzzleHttp\Client(['cookies' => true,
    'base_uri' => 'https://z1.fm/']);
$r = $client->request('GET', 'http://httpbin.org/cookies');


validate($file); // Делает замены хуёвых символов и ложит результат в файл list_validated.txt ПЕРЕЗАПИСЫВАЕТ ФАЙЛ
$track_list = file('list_validated.txt'); // Читает содержимое файла и помещяет каждую строку в массив

foreach ($track_list as $v) {
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
    if ($file_id !== false) {
        $file_id = preg_replace("/[^,.0-9]/", '', $file_id);  // вырезаем из строки только цифры, это на ID файла
        //file_put_contents('link_list.txt', 'https://z1.fm/download/' . $file_id . "\r\n", FILE_APPEND);
        $array_id[] = $file_id;  // Добавляем ID треков в массив для проверки на двойников. И во втором цикле тоже
    } else {
        if (sizeof(file('no_results.txt')) > 1) $exception = file_get_contents('no_results.txt'); // Если есть не найденіе треки то закидіваем их в переменную.

        file_put_contents('no_results.txt', $v, FILE_APPEND);
    }
    //sleep(1);
}
file_put_contents('no_results.txt', '', FILE_APPEND);// Создаем пустой файл если он сайм не создался, ато будет ругатся

//echo sizeof(file('no_results.txt'));
explode_and_edit($exception);
$translated_excetpion = file('no_results_translated.txt');





var_dump($array_id);
$founded_tracks = sizeof(file('link_list.txt'));
$count_tracks = sizeof(file('list_validated.txt'));

//==================================================================================================================

function validate($input)
{
    $gost = array(
        "#" => "", "_" => " ", "..." => " ", ".." => " ", "/" => " ", "//" => " ", "(zaycev.net)" => "", "[Dropmp3.ru]" => "",
        "Ü" => "u", "ë" => "e", "(vk.com/bassflex)" => "", "Arthur" => "Artur", "Yuri" => "Yuriy", "ТНК" => "ТНМК",
        "Elbrus" => "Ельбрус", "[drivemusic.me]" => "", "(Topmuzon.net)" => ""
    );

    $result = strtr(file_get_contents($input), $gost);
    file_put_contents('list_validated.txt', $result);

}

function explode_and_edit($input)
{
    $input = explode(PHP_EOL, $input);
    foreach ($input as $v) {
        $artist = explode(' - ', $v); // разбираем строку, нам нужно имя артиста
        $translite_name = translite($artist[0]) . ' - ' . $artist[1] . "\r\n";// Делаем транслит имени артиста,
        file_put_contents('no_results_translated.txt', $translite_name, FILE_APPEND);//  Собираем строку ложим обратно в файл
    }
}

function translite($input)
{ // Делает транслит замену символов.
    $gost = array(
        "a" => "а", "b" => "б", "v" => "в", "g" => "г", "d" => "д", "e" => "е", "yo" => "ё",
        "j" => "ж", "z" => "з", "i" => "и", "i" => "й", "k" => "к",
        "l" => "л", "m" => "м", "n" => "н", "o" => "о", "p" => "п", "r" => "р", "s" => "с", "t" => "т",
        "y" => "й", "f" => "ф", "h" => "х", "c" => "ц",
        "ch" => "ч", "sh" => "ш", "sch" => "щ", "i" => "ы", "e" => "е", "u" => "у", "ya" => "я", "A" => "А", "B" => "Б",
        "V" => "В", "G" => "Г", "D" => "Д", "E" => "Е", "Yo" => "Ё", "J" => "Ж", "Z" => "З", "I" => "И", "I" => "Й", "K" => "К", "L" => "Л", "M" => "М",
        "N" => "Н", "O" => "О", "P" => "П", "x" => "кс",
        "R" => "Р", "S" => "С", "T" => "Т", "Y" => "Ю", "F" => "Ф", "H" => "Х", "C" => "Ц", "Ch" => "Ч", "Sh" => "Ш",
        "SCh" => "Щ", "I" => "Ы", "E" => "Е", "U" => "У", "Ya" => "Я", "'" => "ь", "'" => "Ь", "''" => "ъ", "''" => "Ъ", "j" => "ї", "i" => "и", "g" => "г",
        "ye" => "є", "J" => "Ї", "I" => "И",
        "G" => "Г", "YE" => "Є", "ts" => "ц", "zz" => "цц", "ee" => "и", "lzy" => "льзи", "ea" => "и", "kh" => "х", "sy" => "сы",
        "Okean" => "Океан", "Lube" => "Любе", "iy" => "ий", "ry" => "ри", "zh" => "ж", "Yu" => "Ю", "SH" => "Ш", "Sc" => "Ск", "SC" => "СК", "sc" => "ск",
        "te " => "т ", "Cr" => "Кр", "cr" => "кр", "Cream" => "Крем", "Korol" => "Король"
    );

    return strtr($input, $gost);
}

//========================================================================================================

$count = $count_tracks - $founded_tracks;


echo '  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet"> ';
echo '<link href="style.css" rel="stylesheet">';
echo "<h1>Success!  Мы нашли $founded_tracks из  $count_tracks треков </h1>";
if ($count != 0) echo '<h3>Не найдено ' . $count . ' треков </h3>';
else echo '<h3>Все треки найдены</h3>';


