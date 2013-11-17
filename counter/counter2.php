<?
/*
 * Based on http://www.anton-pribora.ru/articles/php/image-counter/
 * @author zg (http://anton-pribora.ru)
 * @package imageCounter
 */

// Выключаем вывод ошибок
error_reporting(0);

// Запрет кэширования
header('Expires: Mon, 11 Jul 1991 03:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

// Объявляем некоторые константы
define('COOKIE_NAME', '__count_date');
define('SPLITTER'   , ' ');
define('STAT_FILE'  , 'counter2.txt');
define('TODAY_TIME' , time());
define('TODAY_DATE' , date('Y-m-d') );

// Получение куки
$userTime = isset($_COOKIE[ COOKIE_NAME ]) ? (int) $_COOKIE[ COOKIE_NAME ] : null;

// Установка куки
setcookie(COOKIE_NAME, TODAY_TIME, TODAY_TIME + 60 * 60 * 24);

// Проверка на хост (хостом пусть будет браузер без куки)
if ( $userTime )
    $isNewHost = date('Y-m-d', $userTime) !== TODAY_DATE;
else 
    $isNewHost = true;

// Хитом будем называть просто показ страницы
$isHit = true;

// Обнуляем суммарные значения счтёчика
$totalHosts = (int) $isNewHost;
$totalHits  = (int) $isHit;

$todayHosts = (int) $isNewHost;
$todayHits  = (int) $isHit;

// Открываем файл статистики для чтения и записи
if ( $fp = fopen(STAT_FILE, 'a+b') )
{
    // Блокируем файл, чтобы не дать другим процессам переписать файл до его обработки
    if ( flock($fp, LOCK_EX) )
    {
        // Файл успешно блокирован, выполняем его обработку
        
        // Переводим указатель на начало файла
        fseek($fp, 0, SEEK_SET);
        
        // Подготавливаем переменные для подсчёта хитов и хостов
        $totalHostsTemp = 0;
        $todayHostsTemp = 0;
        
        $totalHitsTemp  = 0;
        $todayHitsTemp  = 0;
        
        $todayTemp = null;

        // Будем думать, что в файле первая строка содержит нужные данные
        $line = fgets($fp);

        // Пускай в первой строке содержатся: хосты, хиты, хосты за сегодня, 
        // хиты за сегодня, дата записи
        if ( $line ) @list($totalHostsTemp, $totalHitsTemp, $todayHostsTemp, $todayHitsTemp, $todayTemp) = split(SPLITTER, $line);

        // Проверка даты
        if ( $todayTemp !== TODAY_DATE )
        {
            // Дата в файле ститистики устарела, обнуляем сегодняшие хосты и хиты
            $todayHostsTemp = 0;
            $todayHitsTemp  = 0;
        }
        
        // Прибавляем данные
        $totalHosts += $totalHostsTemp;
        $todayHosts += $todayHostsTemp;
        
        $totalHits  += $totalHitsTemp;
        $todayHits  += $todayHitsTemp;
        
        // Переводим указатель на начало файла
        fseek($fp, 0, SEEK_SET);
        
        // Урезаем файл до нулевой длины
        ftruncate($fp, 0);
        
        // Записываем данные - сначало хосты, хиты, хосты за сегодня, 
        // хиты за сегодня, дата
        fputs($fp, join(SPLITTER, 
            array($totalHosts, $totalHits, $todayHosts, $todayHits, TODAY_DATE)));
        
        // Снимаем блокировку, но можно и не снимать, если верить мануалу
        flock($fp, LOCK_UN);
    }
    
    // Обработка файла завершена, закрываем файловый указатель
    fclose($fp);
}


    $counter = $totalHosts;

    $myClients = date('Ymd') . "_myclients.log";
    if($fh = fopen($myClients, "a")){
        flock($fh,LOCK_EX);
        $date = date('Y-m-d H:i:s');
        fwrite($fh, $date.": Counter ".$line."\n");
        fwrite($fh, $date.": HTTP_USER_AGENT ".$_SERVER['HTTP_USER_AGENT']."\n");
        fwrite($fh, $date.": REMOTE_ADDR ".$_SERVER['REMOTE_ADDR']."\n\n");
        fflush($fh);
        flock($fh,LOCK_UN);
        fclose($fh);
    }

    $MaxLen = 6;
    $img = "counter.png";
    $arr = GetImageSize($img);
    $Width = $arr[0]/10;
    $Height = $arr[1];

    $CounterLen = strLen($counter);
    $pic = imageCreateTrueColor($MaxLen * $Width, $Height);
    $picsrc = imageCreateFrompng($img);

    for ($i=0; $i < $MaxLen - $CounterLen; $i++) {
      imageCopy ($pic, $picsrc, $i * $Width, 0, 0, 0, $Width, $Height);
    };

    for ($i=1; $i <= $CounterLen; $i++) {
      imageCopy ($pic, $picsrc, ($MaxLen - $i) * $Width, 0, ($counter % 10) * $Width, 0, $Width, $Height);
      $counter = floor($counter / 10);
    };

    header('Content-type:image/png');
    imagepng($pic);
    imagedestroy($pic);
    imagedestroy($picsrc);

?>
