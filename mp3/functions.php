<?php
function validate($input)
{
    $gost = array(
        "#" => "", "_" => " ", "..." => " ", ".." => " ", "/" => " ", "//" => " ", "(zaycev.net)" => "", "[Dropmp3.ru]" => "",
        "Ü" => "u", "ë" => "e", "(vk.com/bassflex)" => "", "Arthur" => "Artur", "Yuri" => "Yuriy", "ТНК" => "ТНМК",
        "Elbrus" => "Ельбрус", "[drivemusic.me]" => "", "(Topmuzon.net)" => ""
    );

    if(!isset($_POST['repeatSearch'])) {
        $result = strtr(file_get_contents($input), $gost);
        file_put_contents('list_validated.txt', $result);
    }elseif(isset($_POST['repeatSearch'])){
        $result = strtr($input, $gost);
        $result = explode("\r\n", $input);
        foreach($result as $k => $v){  // тут нужно к каждому елементу массива(название трека) в конце добавить символ переноса строки так так explodе его удаляет
            $result[$k] = $v . "\r\n";
        }
        return $result;
    }else die('Неизвестный формат на входе в validate()');



}

function explode_and_edit($input){

    foreach($input as $v){
        $artist = explode(' - ', $v); // разбираем строку, нам нужно имя артиста
        $translite_name = translite($artist[0]) . ' - ' . $artist[1];// Делаем транслит имени артиста,
        file_put_contents('no_results_translated.txt', $translite_name, FILE_APPEND );//  Собираем строку ложим обратно в файл
    }
}

function translite($input){ // Делает транслит замену символов.
    $gost = array(
        "a"=>"а","b"=>"б","v"=>"в","g"=>"г","d"=>"д","e"=>"е","yo"=>"ё",
        "j"=>"ж","z"=>"з","i"=>"и","i"=>"й","k"=>"к",
        "l"=>"л","m"=>"м","n"=>"н","o"=>"о","p"=>"п","r"=>"р","s"=>"с","t"=>"т",
        "y"=>"й","f"=>"ф","h"=>"х","c"=>"ц",
        "ch"=>"ч","sh"=>"ш","sch"=>"щ","i"=>"ы","e"=>"е","u"=>"у","ya"=>"я","A"=>"А","B"=>"Б",
        "V"=>"В","G"=>"Г","D"=>"Д", "E"=>"Е","Yo"=>"Ё","J"=>"Ж","Z"=>"З","I"=>"И","I"=>"Й","K"=>"К","L"=>"Л","M"=>"М",
        "N"=>"Н","O"=>"О","P"=>"П","x"=>"кс",
        "R"=>"Р","S"=>"С","T"=>"Т","Y"=>"Ю","F"=>"Ф","H"=>"Х","C"=>"Ц","Ch"=>"Ч","Sh"=>"Ш",
        "SCh"=>"Щ","I"=>"Ы","E"=>"Е", "U"=>"У","Ya"=>"Я","'"=>"ь","'"=>"Ь","''"=>"ъ","''"=>"Ъ","j"=>"ї","i"=>"и","g"=>"г",
        "ye"=>"є","J"=>"Ї","I"=>"И",
        "G"=>"Г","YE"=>"Є","ts"=>"ц","zz"=>"цц","ee"=>"и","lzy"=>"льзи","ea"=>"и","kh"=>"х","sy"=>"сы",
        "Okean"=>"Океан","Lube"=>"Любе","iy"=>"ий","ry"=>"ри","zh"=>"ж","Yu"=>"Ю","SH"=>"Ш","Sc"=>"Ск","SC"=>"СК","sc"=>"ск",
        "te "=>"т ","Cr"=>"Кр","cr"=>"кр","Cream"=>"Крем","Korol"=>"Король"
    );

    return strtr($input, $gost);
}

function d($input){
    echo '<pre>';
    var_dump($input);
    echo '</pre>';
}

function progress($status, $percent, $step) {
    global 	$percentlast;

    switch ($status) {
        case 'start':
            echo '
				<script type="text/javascript">
				    
					document.getElementById("progress").style.display = "block";
					document.getElementById("progress").style.height = "5px";
					document.getElementById("progress").value = "' . $percent . '";
				</script>
			';
            flush();
            break;

        case 'run':

            if ( @$percentlast + $step <= $percent ) {
                $percentlast = $percent;
                echo '
					<script type="text/javascript">
						document.getElementById("progress").value = "' . $percent . '";
					</script>
				';
                flush();
            }
            break;

        case 'stop':
            echo '
				<script type="text/javascript">
					document.getElementById("progress").style.height = "0px";
					document.getElementById("progress").value = "' . $percent . '";	
					document.getElementById("progress").style.display = "none";
					$percentlast = "0";
				</script>
			';
            flush();
            break;
    }
}

