<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2011-10-24 11:54:02 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd, Exinaus, Shw  $
    * @copyright   2011-2012 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.0.0 $
    *
    */
?>
<?php
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
    $new = $cache->get('get_last_roster_'.$config['clan'],0);

    if(empty($new['data']['members'])){
        $res = array();
    } else {
      foreach($new['data']['members'] as $val) {
        $res[] = $val['account_name'];
      }
    }

    $time = array();
    $activity = array();
    $activity_total = array();

    $cache_activity = new Cache(ROOT_DIR.'/cache/activity/');

    if(isset($_POST['a_from']) and isset($_POST['a_to']) and preg_match('/[0-9]{2}.[0-9]{2}.[0-9]{4}/',$_POST['a_from']) and preg_match('/[0-9]{2}.[0-9]{2}.[0-9]{4}/',$_POST['a_to'])) {
      $t1 = explode('.',$_POST['a_from']);
      $t2 = explode('.',$_POST['a_to']);

      $time['from'] = mktime(0, 0, 0, $t1['1'], $t1['0'], $t1['2']);
      $time['to'] = mktime(23, 59, 59, $t2['1'], $t2['0'], $t2['2']);
    } else {
      $time['from'] = mktime(0, 0, 0, date('m'), date('d')-7, date('Y'));
      $time['to'] = time();
    }
    
    if(isset($_POST['a_all']) and $_POST['a_all'] ==1) {
      $a_all = 1;
    } else {
      $a_all = 0;
    }
    if(isset($_POST['a_total']) and $_POST['a_total'] ==1) {
      $a_total = 1;
    } else {
      $a_total = 0;
    }
    if($a_total == 1) {
      $a_all = 0;
    }
    if(isset($_POST['a_cat_1']) and $_POST['a_cat_1'] ==1) {
      $cat_1 = 1;
    } else {
      $cat_1 = 0;
    }
    if(isset($_POST['a_cat_2']) and $_POST['a_cat_2'] ==1) {
      $cat_2 = 1;
    } else {
      $cat_2 = 0;
    }
    if(isset($_POST['a_cat_3']) and $_POST['a_cat_3'] ==1) {
      $cat_3 = 1;
    } else {
      $cat_3 = 0;
    }
    if(isset($_POST['a_cat_4']) and $_POST['a_cat_4'] ==1) {
      $cat_4 = 1;
    } else {
      $cat_4 = 0;
    }
    /* ???? ?? ?????? ?????????, ?????????? ?????? */
    if($cat_1 == 0 and $cat_2 == 0 and $cat_3 == 0 and $cat_4 == 0) {
      $cat_1 = 1;
    }
    for($i=$time['from'];$i<=$time['to'];$i+=86400) {
      $t[date('d.m.Y',$i)] = $cache_activity->get(date('d.m.Y',$i),0);
    }

    $empty = 0;
    foreach($t as $date => $val) {
      //1
        //echo '<pre><div align="left">',$date,'<br />';
        //$activity[]
        /* ????????? ?????? ???????, ??? ?????? ?????? ??????? ? ????? ????????? */
        if(isset($val['players']) and $cat_1 == 1) {
          foreach($val['players'] as $name => $count) {
            if(!isset($activity[$date][$name])) {$activity[$date][$name] = 0;}
            $activity[$date][$name] += $count;
            ++$empty;
            if(isset($activity_total[$name])) {$activity_total[$name] += $count;} else {$activity_total[$name] = $count;}
          }
        }
        /* ?????? ????????? */
        if(isset($val['cat_1']) and $cat_1 == 1) {
          foreach($val['cat_1'] as $name => $count) {
            if(!isset($activity[$date][$name])) {$activity[$date][$name] = 0;}
            $activity[$date][$name] += $count;
            ++$empty;
            if(isset($activity_total[$name])) {$activity_total[$name] += $count;} else {$activity_total[$name] = $count;}
          }
        }
        /* ?????? ????????? */
        if(isset($val['cat_2']) and $cat_2 == 1) {
          foreach($val['cat_2'] as $name => $count) {
            if(!isset($activity[$date][$name])) {$activity[$date][$name] = 0;}
            $activity[$date][$name] += $count;
            ++$empty;
            if(isset($activity_total[$name])) {$activity_total[$name] += $count;} else {$activity_total[$name] = $count;}
          }
        }
        /* ?????? ????????? */
        if(isset($val['cat_3']) and $cat_3 == 1) {
          foreach($val['cat_3'] as $name => $count) {
            if(!isset($activity[$date][$name])) {$activity[$date][$name] = 0;}
            $activity[$date][$name] += $count;
            ++$empty;
            if(isset($activity_total[$name])) {$activity_total[$name] += $count;} else {$activity_total[$name] = $count;}
          }
        }
        /* ????????? ????????? */
        if(isset($val['cat_4']) and $cat_4 == 1) {
          foreach($val['cat_4'] as $name => $count) {
            if(!isset($activity[$date][$name])) {$activity[$date][$name] = 0;}
            $activity[$date][$name] += $count;
            ++$empty;
            if(isset($activity_total[$name])) {$activity_total[$name] += $count;} else {$activity_total[$name] = $count;}
          }
        }
        //echo '</div></pre>';

    }
/*
    foreach($t as $date => $val) {
      if(isset($val)) {
        $activity[$date] = $val['players'];
        if(!empty($val['players'])) { ++$empty; }
      }
    }
*/
    //foreach
    unset($t,$cache_activity);

    if(isset($_POST['default']) and $empty == 0) {
      echo'<div align="center" class="ui-widget ui-widget-content ui-corner-all fixed-menu">',$lang['activity_tip'],'</div>';
      $a_all = 0;
    }

    if($empty != 0 or ($a_all == 1)) {
?>
<script type="text/javascript">
    $(document).ready(function()
    {
        $("#activity_table").tablesorter();
    });
</script>
<table id="activity_table" cellspacing="1" width="100%" class="table-id-<?=$_POST['key'];?>">
    <thead>
        <tr>
            <th><?=$lang['name']; ?></th>
            <?php if($a_total == 0) { ?>
               <?php for($i=$time['from'];$i<=$time['to'];$i+=86400) { ?>
                  <?php if(isset($activity[date('d.m.Y',$i)]) or ($a_all == 1)) { ?>
                        <th align="center"><?php echo date('d.m.Y',$i); ?></th>
                  <?php } ?>
               <?php } ?>
            <?php } ?>
            <th align='center' style="min-width: 30px;"><?=$lang['activity_4'];?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($res as $val => $name){ ?>
            <tr>
                <td><a href="<?php echo $config['base'].$name.'/'; ?>"
                        target="_blank"><?=$name; ?></a></td>
                <?php
                if($a_total == 0) {
                  for($i=$time['from'];$i<=$time['to'];$i+=86400) {
                    if(isset($activity[date('d.m.Y',$i)]) or ($a_all == 1)) { ?>
                       <td align="center"><? echo isset($activity[date('d.m.Y',$i)][$name]) ? $activity[date('d.m.Y',$i)][$name] : ''; ?></td>
                <?  }
                  }
                } ?>
                <td align='center'><? echo isset($activity_total[$name]) ? $activity_total[$name] : 0; ?></td>
            </tr>
            <?php } ?>
    </tbody>
</table>
<?php } else {
 echo '<div align="center" class="ui-state-highlight ui-widget-content">',$lang['activity_error_2'],'</div>';
 }
unset($activity,$activity_total);?>