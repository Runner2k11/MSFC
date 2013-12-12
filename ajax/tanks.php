<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2013-11-20 00:00:00 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd, Exinaus, SHW  $
    * @copyright   2011-2013 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.0.1 $
    *
    */


    error_reporting(E_ALL & ~E_STRICT);
    ini_set("display_errors", 1);
    if (file_exists(dirname(__FILE__).'/func_ajax.php')) {
        define('LOCAL_DIR', dirname(__FILE__));
        include_once (LOCAL_DIR.'/func_ajax.php');

        define('ROOT_DIR', base_dir('ajax'));

    }else{
        define('LOCAL_DIR', '.');
        include_once (LOCAL_DIR.'/func_ajax.php');

        define('ROOT_DIR', '..');

    }
    include_once(ROOT_DIR.'/including/check.php');
    include_once(ROOT_DIR.'/function/auth.php');
    include_once(ROOT_DIR.'/function/mysql.php');
    $db->change_prefix($_POST['db_pref']);
    include_once(ROOT_DIR.'/function/func.php');
    include_once(ROOT_DIR.'/function/func_main.php');
    include_once(ROOT_DIR.'/function/config.php');
    include_once(ROOT_DIR.'/config/config_'.$config['server'].'.php');                      

    foreach(scandir(ROOT_DIR.'/translate/') as $files){
        if (preg_match ("/_".$config['lang'].".php/", $files)){
            include_once(ROOT_DIR.'/translate/'.$files);
        }
    } 
    include_once(ROOT_DIR.'/function/cache.php');

    //cache
    $cache = new Cache(ROOT_DIR.'/cache/');
    $new_roster = $cache->get('get_last_roster_'.$config['clan'],0);
    //print_r($new_roster);
    foreach($new_roster['data'][$config['clan']]['members'] as $val){
        $res[$val['account_name']] = $cache->get($val['account_id'],0,ROOT_DIR.'/cache/players/');
    }
    if($_POST['type'] != 'all'){
        $type = array($_POST['type']);   
    }else{
        $type = array('AT-SPG','SPG','lightTank','mediumTank','heavyTank');
    }
    if($_POST['nation'] != 'all'){
        $nation = array($_POST['nation']);
    }else{
        $nation = array('germany','usa','china','ussr','france','uk');
    }
    if($_POST['lvl'] != 'all'){
        $lvl = array($_POST['lvl']);
    }else{
        $lvl = array('1','2','3','4','5','6','7','8','9','10');
    }
    $tanks = tanks();
    foreach ($tanks as $key => $val) {
       if (!in_array ($val['level'], $lvl) || !in_array ($val['nation'], $nation) || !in_array ($val['type'], $type) ){
           unset($tanks[$key]);
       }
    }
    //print_r($res);
?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#tankslist").tablesorter();
    });
</script>   
<table id="tankslist" width="100%" cellspacing="1" class="table-id-<?=$_POST['key'];?>">
    <thead>
        <tr>
            <th><?=$lang['name']; ?></th><?php 
            foreach ($tanks as $key => $val) {
               echo '<th>',$val['name_i18n'],'</th>';
            } ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach($res as $name => $val){ ?>
            <tr>
                <td><a href="<?php echo $config['base'].$name.'/'; ?>" target="_blank"><?=$name; ?></a></td><?php
                foreach ($tanks as $key => $stat) {
                   echo '<td>';
                   $present = 0;
                   foreach ($val['data']['tanks'] as $key => $val2) {
                      if ($val2['tank_id'] == $stat['tank_id'] ) {
                          if ($val2['statistics']['battles'] > 0) {
                              $percent = round($val2['statistics']['wins']/$val2['statistics']['battles']*100,2);
                              echo $percent.'% ('.$val2['statistics']['wins'].'/'.$val2['statistics']['battles'].')';
                              $present++;
                          }
                      }
                   }
                   if ($present==0) echo '<span style="display: none;">-1</span>';
                   echo '</td>';
                } ?>
            </tr>
            <?php } ?>
    </tbody>
</table>