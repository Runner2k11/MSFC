<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2012
    * Date:        $Date: 2012-12-01
    * -----------------------------------------------------------------------
    * @author      $Author: SHW $
    * @copyright   2012-2012 SHW
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.2.0 $
    *
    */
?>

<?php
    $content = file_get_contents('http://armor.kiev.ua/wot/gamertrees/'.$_GET['b_player']);
    $search = array('/<h1>wot[^.]*<\/h1>/i',
                    '/<div id="h_counters">.*<\/div>/s',
                    '/margin: 30px 8px 30px 16px;/',
                    '/height: inherit;/',
                    '/body {background-color: #060606;}/',
                    '/#060606/',
                    '/#BEBEBE/',
                    '/255,255,255/');
    $replace = array('',
                    '',
                    'margin: 30px 8px 0px 16px;',
                    'height: 580px;',
                    'body {background-color: transparent;}',
                    'transparent',
                    'rgb(56, 56, 56)',
                    '0,0,0');
    echo preg_replace($search, $replace, $content);
?>
