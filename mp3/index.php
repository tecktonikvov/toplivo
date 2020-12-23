<?php

// require_once 'phpQuery.php';

// $filename = 'Rauf & Faik - Детство';
// $search_url1 = 'https://z1.fm/mp3/search?keywords=' . urlencode($filename);
// $search_url = 'https://z1.fm.';

// $dir = 'files/' . $filename;
// $file_id = 21136812;
// $file_url = 'https://z1.fm/download/' . $file_id;
// $url = 'https://stackoverrun.com/ru/q/155480';
// //echo $search_url;
// //echo $search_url;

// $ckfile = tempnam ("files/", "CURLCOOKIE");
// $headers =  array(
// 'Request URL' => 'https://z1.fm/',
// 'Request Method' => 'GET',
// 'Status Code' => '200',
// 'Remote Address' => '104.18.34.45:443',
// 'Referrer Policy' => 'no-referrer-when-downgrade'
//     );
// // 1. инициализация
// $ch = curl_init();
// // 2. устанавливаем опции, включая урл
// curl_setopt($ch, CURLOPT_URL, $search_url1);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile); 
// curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)');
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
// //curl_setopt($ch, CURLOPT_COOKIESESSION, true);
 
// // 3. выполнение запроса и получение ответа
// //$output = curl_getinfo($ch);\

// $output = curl_exec($ch);
// var_dump($output);
// if(curl_errno($ch)) var_dump(curl_strerror(curl_errno($ch)));


// $dom = phpQuery::newDocument($output);

// $array = $dom->find(".song-xl:first > ul > li");
// //$str = $array->html();
// var_dump($array->html());

// //file_put_contents('link.txt', $array->html());
// //$link = file_get_contents('link.txt');
// $link = strstr($array->html(), 'data-url="', '"');
// //var_dump($link);


// // 4. очистка ресурсов

// curl_close($ch);


//Сначала входящий список нужно обработат,
// удаляем симоволы #, _, ..., .., (zaycev.net),[Dropmp3.ru], Ü, ë, (vk.com/bassflex)
//естли строка имеет (From находим первое вхождение и удаляем всё от него 
$ishodnik = file_get_contents('list.txt');

function validate($input){
    $gost = array(
        "#"=>"","_"=>" ","..."=>" ",".."=>" ","/"=>" ","//"=>" ","(zaycev.net)"=>"","[Dropmp3.ru]"=>"",
        "Ü"=>"u","ë"=>"e","(vk.com/bassflex)"=>""
        );

    return strtr($input, $gost);
    //$input = file_put_contents('no_results_after_translate.txt', FILE_APPEND);
}

$ishodnik = validate($ishodnik);

$list = file_get_contents('no_results.txt');

function translite($input){
    $gost = array(
        "a"=>"а","b"=>"б","v"=>"в","g"=>"г","d"=>"д","e"=>"е","yo"=>"ё",
        "j"=>"ж","z"=>"з","i"=>"и","i"=>"й","k"=>"к",
        "l"=>"л","m"=>"м","n"=>"н","o"=>"о","p"=>"п","r"=>"р","s"=>"с","t"=>"т",
        "y"=>"й","f"=>"ф","h"=>"х","c"=>"ц",
        "ch"=>"ч","sh"=>"ш","sch"=>"щ","i"=>"ы","e"=>"е","u"=>"у","ya"=>"я","A"=>"А","B"=>"Б",
        "V"=>"В","G"=>"Г","D"=>"Д", "E"=>"Е","Yo"=>"Ё","J"=>"Ж","Z"=>"З","I"=>"И","I"=>"Й","K"=>"К","L"=>"Л","M"=>"М",
        "N"=>"Н","O"=>"О","P"=>"П",
        "R"=>"Р","S"=>"С","T"=>"Т","Y"=>"Ю","F"=>"Ф","H"=>"Х","C"=>"Ц","Ch"=>"Ч","Sh"=>"Ш",
        "SCh"=>"Щ","I"=>"Ы","E"=>"Е", "U"=>"У","Ya"=>"Я","'"=>"ь","'"=>"Ь","''"=>"ъ","''"=>"Ъ","j"=>"ї","i"=>"и","g"=>"г",
        "ye"=>"є","J"=>"Ї","I"=>"И",
        "G"=>"Г","YE"=>"Є","ts"=>"ц","zz"=>"ц","ee"=>"и","lzy"=>"льзи","ea"=>"и","kh"=>"х","sy"=>"сы",
        "Okean"=>"Океан","Lube"=>"Любе","iy"=>"ий","ry"=>"ри","zh"=>"ж","Yu"=>"Ю","SH"=>"Ш","Sc"=>"Ск","SC"=>"СК","sc"=>"ск",
        "te "=>"т ","Cr"=>"Кр","cr"=>"кр","Cream"=>"Крем"
        );
        
        return strtr($input, $gost);
}

function trans($input){            
    $input = explode(PHP_EOL, $input);
    foreach($input as $v){
        $artist = explode(' - ', $v);
        $translite_name = translite($artist[0]) . ' - ' . $artist[1] . "\r\n";
        file_put_contents('no_result_translated.txt', $translite_name, FILE_APPEND );
    }       
}      

trans($list);




