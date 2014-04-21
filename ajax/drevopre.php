<?php
    /*
    * @author      abagrov
    * @copyright   2014 abagrov
    */
?>

<?php

    error_reporting(E_ALL & ~E_STRICT);
    ini_set("display_errors", 1);

    $theme = 'sunny';
    $arrcolor = 'black';
    // $arrcolor = '#d19405';

    $content = file_get_contents('http://armor.kiev.ua/wot/gamertrees/'.$_GET['b_player']);
    $search = array(
        '/<h1>wot[^.]*<\/h1>/i',  //отключаем заголовок
        '/<div id="h_counters">.*<\/div>/s',  //отключаем счётчики
        '/margin: 30px 8px 30px 16px;/',  //для tree убираем нижний margin
        '/height: inherit;/',  //фиксируем (уменьшаем без потерь) высоту деревьев техники, чтобы не вылезать за пределы фрейма по вертикали
        '/#060606/',  //делаем все фоны прозрачными
        '/#BEBEBE/',  //цвет текста и т.п. = ui-widget-content
        '/255,255,255/',  //меняем зебру уровней с белой на чёрную
        '/target="_top"/',  //открываем ссылки на бронесайт в новой вкладке
        '/(<style.*<\/style>)/s',  //добавляем ссылку на свой стиль JQ
        '/(attr\(\'class\'\, \'|class=")click/',  //меняем стиль класса li.click
        '/(attr\(\'class\'\, \'|class=")active/',  //меняем стиль класса li.active
        '/(#nations li.)active/',  //old = $("#nations li.ui-state-active")
        '/(<style.*)(<\/style>)/s',  //добавляем стиль стрелок
        '/<img (class="line.{0,2}").*width="([0-9]*)" height="([0-9]*)".*>/',  //заменяем картинки стрелок на стиль
        '/<img (class="arr.{0,3}").*>/'  //заменяем картинки стрелок на стиль
        // '/(<div class="tblock)/'
    );
    $replace = array(
        '',
        '',
        'margin: 30px 8px 0px 16px;',
        'height: 580px;',
        'transparent',
        'rgb(56, 56, 56)',
        '0,0,0',
        'target="_blank"',
        '$1'.chr(13).chr(10).
          '<link media="print, projection, screen" type="text/css" href="http://'.$_SERVER['HTTP_HOST'].'/theme/'.$theme.'/jquery-ui.css" rel="stylesheet"></link>',
        '$1click ui-corner-top ui-state-default',
        '$1ui-corner-top ui-state-active',
        '$1ui-state-active',
        '$1'.chr(13).
          '.lineD, .lineU, .line2R, .lineRD, .lineRU, .arrLR, .arrTB, .arrBT, .arrLRD, .arrLRU { background: '.$arrcolor.'; }'.chr(13).chr(10).
          '.arrLR:after, .arrTB:after, .arrBT:after, .arrLRD:after, .arrLRU:after { content: ""; display: block; position: absolute; width: 0; height: 0; border: 40px solid transparent; }'.chr(13).chr(10).
          '.arrLR { top: 16px; width: 17px;  height: 1px; }'.chr(13).chr(10).
          '.arrLRD:after, .arrLRU:after, .arrLR:after { top: -2px;  right: -1px; border-left-color: '.$arrcolor.'; border-width: 3px 0 3px 6px; }'.chr(13).chr(10).
          '.arrLRD, .arrLRU { right: 120px; width: 56px;  height: 1px; }'.chr(13).chr(10).
          '.arrLRD { bottom: 45px; -webkit-transform: rotate(45deg); -moz-transform: rotate(45deg); -ms-transform: rotate(45deg); -o-transform: rotate(45deg); }'.chr(13).chr(10).
          '.arrLRU { top: 45px; -webkit-transform: rotate(-45deg); -moz-transform: rotate(-45deg); -ms-transform: rotate(-45deg); -o-transform: rotate(-45deg); }'.chr(13).chr(10).
          '.arrTB, .arrBT { left: 63px; width: 1px;  height: 38px; }'.chr(13).chr(10).
          '.arrTB:after { top: 33px; right: -2px; border-top-color: '.$arrcolor.'; border-width: 6px 3px 0px 3px; }'.chr(13).chr(10).
          '.arrBT:after { top: -1px; right: -2px; border-bottom-color: '.$arrcolor.'; border-width: 0px 3px 6px 3px; }'.chr(13).chr(10).
          '$2',
        '<div $1 style="width:$2px; height:$3px;"></div>',
        '<div $1></div>'
        // '$1 ui-state-default'
    );
    echo preg_replace($search, $replace, $content);
?>
