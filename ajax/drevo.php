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

    if (isset($_POST['b_player']) ) {
        $b_player = $_POST['b_player'];
    }else{
        $b_player = '';
    };
?>

<div align="center">
<iframe frameborder="no" height="720" width="100%" src="ajax/drevopre.php?b_player=<?=$b_player?>"></iframe></br>
Данные взяты с сайта <a href="http://armor.kiev.ua" target="_blank">armor.kiev.ua</a>
</div>