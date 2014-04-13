<?php
    /*
    * @author      abagrov
    * @copyright   2014 abagrov
    */
?>

<?php

    error_reporting(E_ALL & ~E_STRICT);
    ini_set("display_errors", 1);

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
        '/(#nations li.)active/'  //old = $("#nations li.ui-state-active")
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
        '$1<link media="print, projection, screen" type="text/css" href="http://'.$_SERVER['HTTP_HOST'].'/theme/sunny/jquery-ui.css" rel="stylesheet"></link>',
        '$1click ui-corner-top ui-state-default',
        '$1ui-corner-top ui-state-active',
        '$1ui-state-active'
    );
    echo preg_replace($search, $replace, $content);
?>
