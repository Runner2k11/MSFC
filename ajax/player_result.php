<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2012
    * Date:        $Date: 2013-11-28
    * -----------------------------------------------------------------------
    * @author      $Author: SHW $
    * @copyright   2012-2013 SHW
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
    };
    include_once(ROOT_DIR.'/function/auth.php');
    include_once(ROOT_DIR.'/function/mysql.php');
    if (isset($_POST['db_pref']) ) {
        $db->change_prefix($_POST['db_pref']);
    }   else{
        if (isset($_GET['db_pref']) ) {
            $db->change_prefix($_GET['db_pref']);
        }   };
    include_once(ROOT_DIR.'/function/func.php');
    include_once(ROOT_DIR.'/function/func_main.php');
    include_once(ROOT_DIR.'/function/func_time.php');
    include_once(ROOT_DIR.'/function/config.php');
    include_once(ROOT_DIR.'/config/config_'.$config['server'].'.php');

    foreach(scandir(ROOT_DIR.'/translate/') as $files){
        if (preg_match ("/_".$config['lang'].".php/", $files)){
            include_once(ROOT_DIR.'/translate/'.$files);
        }
    } 
    //cache
    include_once(ROOT_DIR.'/function/cache.php');
    $cache = new Cache(ROOT_DIR.'/cache/');
?>
<script type="text/javascript" id="js">
   $(document).ready(function()
   {  $("#t-table1")
      <? for ($i=2; $i<=13; $i++) {?>
              .add("#t-table<?=$i;?>")
      <? } ;?>
      .tablesorter({
          headerTemplate : '<div style="padding: 0px;">{content}</div>{icon}',
          widgets : ['uitheme', 'zebra'],
          headers:{ 0: { sorter: false}, 1: {sorter: false}, 2: {sorter: false}, 3: {sorter: false}, 4: {sorter: false},
                    5: { sorter: false}, 6: {sorter: false}, 7: {sorter: false}, 8: {sorter: false}, 9: {sorter: false},
                    10:{ sorter: false}, 11:{sorter: false}, 12:{sorter: false}, 13:{sorter: false}, 14:{sorter: false},
                    15:{ sorter: false}, 16:{sorter: false}, 17:{sorter: false}, 18:{sorter: false}, 19:{sorter: false},
                    20:{ sorter: false}, 21:{sorter: false}, 22:{sorter: false}, 23:{sorter: false}, 24:{sorter: false}
                  },
          theme : 'bootstrap'
      });
      $('.bb[title]').tooltip({
          track: false,
          delay: 0,
          fade: 250,
          items: "[title]",
          content: function() {
              var element = $( this );
              if ( element.is( "[title]" ) ) {
                   return element.attr( "title" );
              }
          }
      });
   });
</script>

<?   $not_incl = array ('account_id', 'nickname', 'created_at');
     $darkgreen = '<span style="color:DarkGreen;"><img style="vertical-align: -5%;" width="11" height="11" src="./images/up.png">&nbsp;';
     $darkred = '<span style="color:DarkRed;"><img style="vertical-align: -5%;" width="11" height="11" src="./images/down.png">&nbsp;';
     $darkend = '</span>';
     $b_info_nation = $b_info_type = $b_info_lvl = $b_diff_played = $b_played_tanks = $effect = $b_pl_mp = $diff = $last = array();
     $mark_of_mastrery = $mark_of_mastrery_d = array_fill(0, 5, 0);
     global $db;
     $b_nation = tanks_nations();
     $medn = medn($b_nation);
     $b_tank_name = tanks();
     $marks = marks();

    if (isset($_POST['b_from']) ) {
        $b_from = $_POST['b_from'];
    }else{
        $b_from = '';
    };
    if (isset($_POST['b_to']) ) {
        $b_to = $_POST['b_to'];
    }else{
        $b_to = '';
    };
    if (isset($_POST['b_player']) ) {
        $b_player = $_POST['b_player'];
    }else{
        $b_player = '';
    };

If (($b_from<>'') && ($b_to<>'') ) {
    $b_from1 = explode('.',$b_from);
    $b_from11 = mktime(0, 0, 0, $b_from1['1'], $b_from1['0'], $b_from1['2']);
    $b_to1 = explode('.',$b_to);
    $b_to11 = mktime(23, 59, 59, $b_to1['1'], $b_to1['0'], $b_to1['2']);
    $resall = $cache->get('get_last_roster_'.$config['clan'],0);
    foreach($resall['data'][$config['clan']]['members'] as $id1 => $val ) {
       if ($val['account_name']==$b_player) {
           $b_res = $val;
       }
     };
     if (empty($b_res)) {die('No cached data');};
     $sql = "SELECT * FROM `col_players` WHERE account_id = '".$b_res['account_id']."' AND updated_at < '".$b_to11."' AND updated_at >= '".$b_from11."' ORDER BY updated_at DESC;";
          $q = $db->prepare($sql);
          if ($q->execute() == TRUE) {
              $b_player_all = $q->fetchAll();
          }   else {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          };
if (count($b_player_all) >1) {

//new tanks
     foreach ($b_nation as $val) {
        $sql = "SELECT * FROM `col_tank_".$val['nation']."` WHERE account_id = '".$b_res['account_id']."' AND updated_at < '".$b_to11."' AND updated_at >= '".$b_from11."' ORDER BY updated_at DESC;";
                $q = $db->prepare($sql);
                if ($q->execute() == TRUE) {
                    $tanks = $q->fetchAll();
                } else {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                };

        foreach ($b_tank_name as $id => $val3) {
                 if (!isset ($b_diff_played['nation'][$b_tank_name[$id]['nation']]['win']))    $b_diff_played['nation'][$b_tank_name[$id]['nation']]['win'] = 0;
                 if (!isset ($b_diff_played['nation'][$b_tank_name[$id]['nation']]['total']))  $b_diff_played['nation'][$b_tank_name[$id]['nation']]['total'] = 0;
                 if (!isset ($b_diff_played['level'][$b_tank_name[$id]['level']]['win']))      $b_diff_played['level'][$b_tank_name[$id]['level']]['win'] = 0;
                 if (!isset ($b_diff_played['level'][$b_tank_name[$id]['level']]['total']))    $b_diff_played['level'][$b_tank_name[$id]['level']]['total'] = 0;
                 if (!isset ($b_diff_played['type'][$b_tank_name[$id]['type']]['win']))        $b_diff_played['type'][$b_tank_name[$id]['type']]['win'] = 0;
                 if (!isset ($b_diff_played['type'][$b_tank_name[$id]['type']]['total']))      $b_diff_played['type'][$b_tank_name[$id]['type']]['total'] = 0;

                 if (!isset ($b_info['nation'][$b_tank_name[$id]['nation']]['win']))           $b_info['nation'][$b_tank_name[$id]['nation']]['win'] = 0;
                 if (!isset ($b_info['nation'][$b_tank_name[$id]['nation']]['total']))         $b_info['nation'][$b_tank_name[$id]['nation']]['total'] = 0;
                 if (!isset ($b_info['level'][$b_tank_name[$id]['level']]['win']))             $b_info['level'][$b_tank_name[$id]['level']]['win'] = 0;
                 if (!isset ($b_info['level'][$b_tank_name[$id]['level']]['total']))           $b_info['level'][$b_tank_name[$id]['level']]['total'] = 0;
                 if (!isset ($b_info['type'][$b_tank_name[$id]['type']]['win']))               $b_info['type'][$b_tank_name[$id]['type']]['win'] = 0;
                 if (!isset ($b_info['type'][$b_tank_name[$id]['type']]['total']))             $b_info['type'][$b_tank_name[$id]['type']]['total'] = 0;

          If (isset($tanks[0][$id.'_battles'])) {
              if (isset($tanks[0][$id.'_mark_of_mastery'])){$mark_of_mastrery[$tanks[0][$id.'_mark_of_mastery']] ++;}
              if (isset($tanks[count($tanks)-1][$id.'_mark_of_mastery'])){$mark_of_mastrery_d[$tanks[count($tanks)-1][$id.'_mark_of_mastery']] ++;}
              if (($tanks[count($tanks)-1][$id.'_battles'] == '0')&&($tanks[0][$id.'_battles'] > 0)) $b_new_tank[$id] = $b_tank_name[$id];
              if ($tanks[count($tanks)-1][$id.'_battles'] <> $tanks[0][$id.'_battles']) {
                 $b_played_tanks[$id]['name_i18n'] =  $b_tank_name[$id]['name_i18n'];
                 $b_played_tanks[$id]['nation'] = $val['nation'];
                 $b_played_tanks[$id]['total'] = $tanks[0][$id.'_battles'];
                 $b_played_tanks[$id]['mark_of_mastery'] = $tanks[0][$id.'_mark_of_mastery'];
                 $b_played_tanks[$id]['win'] = $tanks[0][$id.'_wins'];
                 $b_played_tanks[$id]['total_d'] = $tanks[0][$id.'_battles']-$tanks[count($tanks)-1][$id.'_battles'];
                 $b_played_tanks[$id]['win_d'] = $tanks[0][$id.'_wins']-$tanks[count($tanks)-1][$id.'_wins'];
                 $b_played_tanks[$id]['mark_of_mastery_d'] = $tanks[count($tanks)-1][$id.'_mark_of_mastery'];

                 $b_diff_played['nation'][$b_tank_name[$id]['nation']]['total'] += ($tanks[0][$id.'_battles']- $tanks[count($tanks)-1][$id.'_battles']);
                 $b_diff_played['nation'][$b_tank_name[$id]['nation']]['win'] +=   ($tanks[0][$id.'_wins']- $tanks[count($tanks)-1][$id.'_wins']);
                 $b_diff_played['level'][$b_tank_name[$id]['level']]['total'] +=   ($tanks[0][$id.'_battles']- $tanks[count($tanks)-1][$id.'_battles']);
                 $b_diff_played['level'][$b_tank_name[$id]['level']]['win'] +=     ($tanks[0][$id.'_wins']- $tanks[count($tanks)-1][$id.'_wins']);
                 $b_diff_played['type'][$b_tank_name[$id]['type']]['total'] +=     ($tanks[0][$id.'_battles']- $tanks[count($tanks)-1][$id.'_battles']);
                 $b_diff_played['type'][$b_tank_name[$id]['type']]['win'] +=       ($tanks[0][$id.'_wins']- $tanks[count($tanks)-1][$id.'_wins']);
                 }
              if ($tanks[0][$id.'_battles'] <> '0') {
                 $b_info['nation'][$b_tank_name[$id]['nation']]['win'] += $tanks[0][$id.'_wins'];
                 $b_info['nation'][$b_tank_name[$id]['nation']]['total'] += $tanks[0][$id.'_battles'];
                 $b_info['level'][$b_tank_name[$id]['level']]['win'] += $tanks[0][$id.'_wins'];
                 $b_info['level'][$b_tank_name[$id]['level']]['total'] += $tanks[0][$id.'_battles'];
                 $b_info['type'][$b_tank_name[$id]['type']]['win'] += $tanks[0][$id.'_wins'];
                 $b_info['type'][$b_tank_name[$id]['type']]['total'] += $tanks[0][$id.'_battles'];
              }
           }
        }
     };



//main_data w/o tanks
     foreach ($b_player_all[0] as $key => $val) {
        $last[$key] = $b_player_all[0][$key];
        if ((!is_numeric($key)) && (!In_array($key,$not_incl))) {
              $diff[$key] = $b_player_all[0][$key] - $b_player_all[count($b_player_all)-1][$key];
              $first[$key] = $b_player_all[count($b_player_all)-1][$key];
        }
     };
     $first['dead_heat'] = $first['all_draws'];
     $last['dead_heat'] = $last['all_draws'];
     $diff['dead_heat'] = $first['dead_heat'] - $last['dead_heat'];
//efficient
     If ($last['all_battles'] == 0)  {$last['all_battles'] = 1/100000;}
     If ($first['all_battles'] == 0) {$first['all_battles'] = 1/100000;}
     $effect['all_frags'] = $last['all_frags'] / $last['all_battles'];
     $effect['all_damage_dealt'] = $last['all_damage_dealt'] / $last['all_battles'];
     $effect['all_spotted'] = $last['all_spotted'] / $last['all_battles'];
     $effect['all_dropped_capture_points'] = $last['all_dropped_capture_points'] / $last['all_battles'];
     $effect['all_capture_points'] = $last['all_capture_points'] / $last['all_battles'];
     $effect['level'] = 0;

     foreach ($b_info['level'] as $lvl_key => $val)
        $effect['level'] += $lvl_key*$val['total']/$last['all_battles'];
     $effect['level'] = number_format($effect['level'], 2, '.', '');
     $eff_rating  = number_format($effect['all_damage_dealt']*(10/($effect['level'] +2 ))*(0.23+2*$effect['level']/100) + $effect['all_frags']*0.25*1000 + $effect['all_spotted']*0.15*1000 + log($effect['all_capture_points']+1,1.732)*0.15*1000 + $effect['all_dropped_capture_points']*0.15*1000,2, '.', '');
     $effect['des2'] = ($first['all_frags'])  / ($first['all_battles']);
     $effect['dmg2'] = ($first['all_damage_dealt'])  / ($first['all_battles']);
     $effect['spot2'] = ($first['all_spotted']) / ($first['all_battles']);
     $effect['def2'] = ($first['all_dropped_capture_points'])  / ($first['all_battles']);
     $effect['cap2'] = ($first['all_capture_points'])  / ($first['all_battles']);
     $effect['level2'] = 0;
     foreach ($b_info['level'] as $lvl_key => $val)
        $effect['level2'] += $lvl_key*$val['total']/($first['all_battles']);
     $effect['level2'] = number_format($effect['level'], 2, '.', '');
     $eff_rating_ = number_format($effect['dmg2']*(10/($effect['level2'] +2 ))*(0.23+2*$effect['level2']/100) + $effect['des2']*0.25*1000 + $effect['spot2']*0.15*1000 + log($effect['cap2']+1,1.732)*0.15*1000 + $effect['def2']*0.15*1000,2, '.', '');
     $eff_rating2 = number_format($eff_rating - $eff_rating_, 2, '.', '');
     $eff_ratingb = round((log($last['all_battles'])/10)*(($last['all_battle_avg_xp']*1)+($effect['all_damage_dealt']*(($last['all_wins']/$last['all_battles'])*2+$effect['all_frags']*0.9+$effect['all_spotted']*0.5+$effect['all_dropped_capture_points']*0.5+$effect['all_capture_points']*0.5))),0);
     $eff_ratingb_ = round((log($first['all_battles'])/10)*((($first['all_battle_avg_xp'])*1)+($effect['dmg2']*((($first['all_wins'])/($first['all_battles']))*2+$effect['des2']*0.9+$effect['spot2']*0.5+$effect['def2']*0.5+$effect['cap2']*0.5))),0);
     $eff_ratingb2 = $eff_ratingb - $eff_ratingb_;

     switch ($eff_rating) {
        case ($eff_rating < 600):
           $color = 'red';
           break;
        case ($eff_rating < 900):
           $color = 'slategray';
           break;
        case ($eff_rating < 1200):
           $color = 'green';
           break;
        case ($eff_rating < 1500):
           $color = 'royalblue';
           break;
        case ($eff_rating < 1800):
           $color = 'purple';
           break;
        default:
           $color = '#FF7900';
           break;
     };

     switch ($eff_ratingb+1) {
        case ($eff_ratingb > 7294):
           $textt = $lang['classVf'];$imgg='classVf.png';
           break;
        case ($eff_ratingb > 5571):
           $textt = $lang['classMf'];$imgg='classMf.png';
           break;
        case ($eff_ratingb > 3851):
           $textt = $lang['class1f'];$imgg='class1f.png';
           break;
        case ($eff_ratingb > 2736):
           $textt = $lang['class2f'];$imgg='class2f.png';
           break;
        case ($eff_ratingb > 2084):
           $textt = $lang['class3f'];$imgg='class3f.png';
           break;
        case ($eff_ratingb > 1452):
           $textt = $lang['deer3f'];$imgg='deer3f.png';
           break;
        case ($eff_ratingb > 1010):
           $textt = $lang['deer2f'];$imgg='deer2f.png';
           break;
        case ($eff_ratingb > 517):
           $textt = $lang['deer1f'];$imgg='deer1f.png';
           break;
        default:
           $textt = $lang['deerMf'];$imgg='deerMf.png';
           break;
     };

     $roster = roster_sort($resall['data'][$config['clan']]['members']);
     $roster_id = roster_resort_id($roster);
     $b_pl_mp1 = medal_progress($roster_id, $medn, $b_from11, $b_to11);
     unset($resall, $roster, $roster_id);
     $count_med = 0;
     Unset($b_pl_mp1['unsort']);

     if (isset($b_pl_mp1['sorted'])) {
         foreach ($b_pl_mp1['sorted'] as $mdtype => $val) {
            foreach ($val as $id =>$val2) {
               if ($id == $b_res['account_id']) $b_pl_mp[$mdtype] = $val2;
            }
        }
     }
     ksort($b_pl_mp);
     foreach ($b_pl_mp as $keytype => $val){
        $ctyp = 0;
        foreach ($val as $keymedal => $val2){
           $count_med += $val2;
           $ctyp += $val2;
        }
        if ($ctyp == 0) {unset ($b_pl_mp[$keytype]);}
     }
     Unset ($b_pl_mp1);
?>

<div align="center">
<div align="center" class="ui-state-highlight ui-widget-content">Период отображаемых данных c <?php echo date('d.m.Y',$first['updated_at']); ?> по <?php echo date('d.m.Y',$last['updated_at']); ?></div>
  <table cellspacing="2" cellpadding="2" width="100%" id="tmain">
   <tbody>
    <tr>
     <td valign="top" width="20%">
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="t-table8">
         <thead>
           <tr>
            <th align="center" colspan="2" ><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="<?=$lang['overall_eff_table'];?>"><?=$lang['eff_ret']; ?> (c)</span>
            <br><a href="http://wot-news.com/" target="_blank">wot-news.com</a></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td align="center"><font color="<?=$color; ?>"><?=$eff_rating; ?></font></td>
             <td align="center"><?php if ($eff_rating2 >  0) echo $darkgreen.'+'.$eff_rating2.$darkend;
                                      if ($eff_rating2 <  0) echo $darkred.$eff_rating2.$darkend;
                                      if ($eff_rating2 == 0) echo '0';?>
             </td>
           </tr>
         </tbody>
       </table>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="t-table10">
         <thead>
           <tr>
             <th align="center" colspan="3"><?=$lang['emem'];?> (c) <br><a href="http://emem.ru/" target="_blank">emem.ru</a></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td><span class="hidden">1</span><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="<?=$lang['emem_fsb_title'];?>"><?=$lang['emem_fsb'];?></span>:</td>
             <td><?php $showl = ($last['all_spotted']+$last['all_frags'])/$last['all_battles'];
                       echo number_format($showl,3); ?>
             </td>
             <td><?php $shown = number_format($showl-($first['all_spotted']+$first['all_frags'])/$first['all_battles'],3);
                       if ($shown >  0) echo $darkgreen.'+'.$shown.$darkend;
                       if ($shown <  0) echo $darkred.$shown.$darkend;
                       if ($shown == 0) echo '0'; ?>
             </td>
           </tr>
           <tr>
             <td><span class="hidden">2</span><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="<?=$lang['emem_fb_title'];?>"></span><?=$lang['emem_fb'];?>:</td>
             <td><?php echo number_format(($last['all_frags'])/$last['all_battles'],3);?></td>
             <td><?php $shown = round (($last['all_frags']/$last['all_battles']-$first['all_frags']/$first['all_battles'] ), 3);
                       if ($shown >  0) echo $darkgreen.'+'.$shown.$darkend;
                       if ($shown <  0) echo $darkred.$shown.$darkend;
                       if ($shown == 0) echo '0';
                 ?></td>
           </tr>
           <tr>
             <td><span class="hidden">3</span><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="<?=$lang['emem_sb_title'];?>"></span><?=$lang['emem_sb'];?>:</td>
             <td><?php echo number_format($last['all_spotted']/$last['all_battles'],3);?></td>
             <td><?php $shown = round (($last['all_spotted']/$last['all_battles']-$first['all_spotted']/$first['all_battles'] ), 3);
                       if ($shown >  0) echo $darkgreen.'+'.$shown.$darkend;
                       if ($shown <  0) echo $darkred.$shown.$darkend;
                       if ($shown == 0) echo '0';
                 ?></td>
           </tr>
           <tr>
             <td><span class="hidden">4</span><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="<?=$lang['emem_cb_title'];?>"></span><?=$lang['emem_cb'];?>:</td>
             <td><?php echo number_format($last['all_capture_points']/$last['all_battles'],3);?></td>
             <td><?php $shown = round (($last['all_capture_points']/$last['all_battles']-$first['all_capture_points']/$first['all_battles'] ), 3);
                       if ($shown >  0) echo $darkgreen.'+'.$shown.$darkend;
                       if ($shown <  0) echo $darkred.$shown.$darkend;
                       if ($shown == 0) echo '0';
                 ?></td>
           </tr>
           <tr>
             <td><span class="hidden">5</span><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;" title="<?=$lang['emem_db_title'];?>"></span><?=$lang['emem_db'];?>:</td>
             <td><?php echo number_format($last['all_dropped_capture_points']/$last['all_battles'],3);?></td>
             <td><?php $shown = round (($last['all_dropped_capture_points']/$last['all_battles']-$first['all_dropped_capture_points']/$first['all_battles'] ), 3);
                       if ($shown >  0) echo $darkgreen.'+'.$shown.$darkend;
                       if ($shown <  0) echo $darkred.$shown.$darkend;
                       if ($shown == 0) echo '0';
                 ?></td>
           </tr>
         </tbody>
       </table>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="t-table9">
         <thead>
           <tr>
            <th align="center" colspan="2" ><span class="bb" style="border-bottom: 1px dashed #666666; cursor: pointer;"
                title="<?=$lang['brone_anno'];?>"><?=$lang['brone_ret'];?> (c)</span>
                <br><a href="http://armor.kiev.ua/wot/" target="_blank">armor.kiev.ua/wot</a></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td align="center"><?=$eff_ratingb; ?></td>
             <td align="center"><?php if ($eff_ratingb2 >  0) echo $darkgreen.'+'.$eff_ratingb2.$darkend;
                                      if ($eff_ratingb2 <  0) echo $darkred.$eff_ratingb2.$darkend;
                                      if ($eff_ratingb2 == 0) echo '0';
                                ?>
             </td>
           </tr>
           <tr>
             <td align="center" colspan="2"><?php echo '<img src="./images/brone/'.$imgg.'" />'; ?></td>
           </tr>
           <tr>
             <td align="center" colspan="2"><?=$textt; ?></td>
           </tr>
         </tbody>
       </table>
       <?php if (isset($b_new_tank)) { ?>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="t-table11" style="position: relative;">
         <thead>
          <tr>
            <th align="center" colspan="2"><?=$lang['new_tanks'];?></th>
          </tr>
         </thead>
       <tbody>
         <?php foreach($b_new_tank as $id => $val) { //  id, title,tank,nation,level, type,link?>
         <tr style="height:31px;">
            <td>
              <span class="hidden"><?=$val['level'];?></span>
              <?php if (strlen($val['name_i18n']) > 20) {
                        $trimmed = substr($val['name_i18n'], 0, 18 );
                        echo $trimmed.'...';
                    }   else {
                        echo $val['name_i18n'];
                    } ?>
            </td>
            <td style="width:131px;">
               <span class="bb" title="<?php echo $val['name_i18n'].'<br>'.$lang[$val['nation']].'<br>'.$val['level'].' lvl<br>'.$lang[$val['type']]; ?>">
                 <?php echo '<img src="http://'.$config['gm_url'].'/static/3.6.0.1/common/img/nation/'.$val['nation'].'.png" />';
                       echo '<img style="right: -45px; position: absolute;" src="'.$val['image_small'].'" />'; ?>
               </span>
            </td>
         </tr>
         <?php } ?>
       </tbody>
       </table>
       <?php };?>
     </td>
     <td valign="top" width="42%">
       <?php $misc4 = array ('all_battles', 'all_wins', 'all_losses', 'all_draws', 'all_survived_battles' );?>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="t-table1">
         <thead>
           <tr>
             <th align="center" colspan="5"><?=$lang['overall_title'];?></th>
           </tr>
         </thead>
         <tbody>
           <?php

           $i=1; foreach ($misc4 as $val) {?>
           <tr>
             <td><span class="hidden"><?=$i; ?></span><?=$lang[$val];?>:</td>
             <td><?php echo round($last[$val]); ?></td>
             <td><?php if ($diff[$val]> 0) echo '+'; echo round($diff[$val]); ?></td>
             <td><?php
                 if ($val == 'all_battles') {
                     echo ' ';
                 } else {
                    if ($last['all_battles'] <> 0 ){
                       echo round($last[$val]/$last['all_battles']*100,2).'% ';
                    }  else {echo '0%';}
                 }?></td>
             <td><?php
                 if ($last['all_battles'] <> 0) {
                     if ($val == 'all_battles') {
                         $delta = round($diff['all_battles']/$last['all_battles']*100,3);
                     }   else {
                         $delta = round(($last[$val]/$last['all_battles']-$first[$val]/$first['all_battles'])*100,3);
                     }
                 }   else $delta = 0;
                 if ($delta >0) echo $darkgreen.'+'.$delta.'%'.$darkend;
                 if ($delta <0) echo $darkred.$delta.'%'.$darkend;
                 if ($delta == 0) echo '0%';
               ?></td>
           </tr>
           <?php ++$i;}; ?>
       </table>

       <?php $i = 2; $misc = array ('all_frags', 'all_spotted', 'all_damage_dealt', 'all_capture_points', 'all_dropped_capture_points');?>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="t-table2">
         <thead>
           <tr>
             <th align="center" colspan="5"><?=$lang['perform_title'];?></th>
           </tr>
         </thead>
         <tbody>
           <tr>
             <td><span class="hidden">1</span><?=$lang['all_hits_percents'];?>:</td>
             <?php echo '<td>'.$last['all_hits_percents'].'%</td><td colspan="2">'.' '.'</td><td>';
                   if ($diff['all_hits_percents']> 0) {
                       echo $darkgreen.'+'.$diff['hits_percents'].'%'.$darkend;
                   }   else {echo '0';} ?>
             </td>
           </tr>
           <?php foreach ($misc as $val) {?>
           <tr>
             <td><span class="hidden"><?=$i; ?></span><?=$lang[$val];?>:</td>
             <td><?php echo $last[$val].'</td><td>';
                       if ($diff[$val]> 0) echo '+';
                       echo $diff[$val]?></td>
             <td><?php
                 if ($last['all_battles'] <> 0 ){
                    echo round($last[$val]/$last['all_battles'],2);
                 }  else {echo '0';}?></td>
             <td><?php
                 $delta=round(($last[$val]/$last['all_battles']-$first[$val]/$first['all_battles']),2);
                 if ($delta >0) echo $darkgreen.'+'.$delta.$darkend;
                 if ($delta <0) echo $darkred.$delta.$darkend;
                 if ($delta == 0) echo '0';?></td>
           </tr>
           <?php ++$i; }; ?>
         </tbody>
       </table>

       <?php $misc3 = array ('all_xp', 'all_battle_avg_xp', 'max_xp');?>
       <table cellspacing="1" cellpadding="1" width="100%" align="center" id="t-table7">
         <thead>
           <tr>
             <th align="center" colspan="3"><?= $lang['battel_title'];?></th>
           </tr>
         </thead>
         <tbody>
               <?php $i=1;foreach($misc3 as $val) { ?>
           <tr>
             <td><span class="hidden"><?php echo $i;++$i; ?></span><?=$lang[$val]; ?>:</td>
             <td><?=$last[$val]; ?></td>
             <td><?php
                   if ($diff[$val] >  0) echo $darkgreen.'+'.$diff[$val].$darkend;
                   if ($diff[$val] <  0) echo $darkred.$diff[$val].$darkend;
                   if ($diff[$val] == 0) echo '0';
                 ?></td>
           </tr>
               <?php } ?>
         </tbody>
       </table>

       <table cellspacing="1" cellpadding="0" width="100%" align="center" id="t-table3">
         <thead>
          <tr>
            <th align="center" colspan="3">Знаки классности</th>
          </tr>
         </thead>
         <tbody>
               <?php for($i=1; $i<=4;$i++) {
                   $shown = $mark_of_mastrery[$i]-$mark_of_mastrery_d[$i]; ?>
           <tr>
             <td><span class="hidden"><?=4-$i; ?></span><img src="<?='http://'.$config['gm_url'].$marks[$i]; ?>" /></td>
             <td><?=$mark_of_mastrery[$i]; ?></td>
             <td><?php if ($shown >0) echo $darkgreen.'+'.$shown.$darkend;
                 if ($shown <0) echo $darkred.$shown.$darkend;
                 if ($shown == 0) echo '0'; ?></td>
           </tr>
               <?php } ?>
         </tbody>
       </table>
     </td>
     <td valign="top" width="38%">
       <?php $misc4 = array ('type', 'nation', 'level');
             $i=4;
             foreach ($misc4 as $mkey) {?>

       <table cellspacing="1" cellpadding="1"  width="100%" align="center" id="t-table<?=$i;?>">
         <thead>
           <tr>
             <th colspan="7" align="center"><?=$lang['perform_title'];?>
             <?php if ($mkey == 'type') echo $lang['perform_class'].'</td></tr><tr><th align="center">'.$lang['class'].'</th>';
                   if ($mkey == 'nation') echo $lang['perform_nation'].'</td></tr><tr><th align="center" >'.$lang['nation'].'</th> ';
                   if ($mkey == 'level') echo $lang['perform_lvl'].'</td></tr><tr><th align="center">'.$lang['level'].'</th>';
             ?>
             <th align="center" colspan="2"><?=$lang['all_battles'];?></th>
             <th align="center" colspan="2"><?=$lang['all_wins'];?></th>
             <th align="center" colspan="2"><?=$lang['winp'];?></th>
           </tr>
         </thead>
         <tbody>
           <?php foreach($b_info[$mkey] as $key_key =>$val) { ?>
           <tr>
             <td><?php if ($mkey <> 'level') {
                           echo $lang[$key_key] ;
                       } else {
                        echo $key_key;
                       };
                 ?></td>
             <td><?=$val['total']; ?></td>
             <td><?php if ($b_diff_played[$mkey][$key_key]['total'] <> 0) echo '+';
                       echo $b_diff_played[$mkey][$key_key]['total']?></td>
             <td><?=$val['win']; ?></td>
             <td><?php if ($b_diff_played[$mkey][$key_key]['win'] <> 0) echo '+';
                       echo $b_diff_played[$mkey][$key_key]['win'];?></td>
             <td><?php if ($val['total']<> 0) {
                          echo round($val['win']/$val['total']*100,2);
                       } else {
                          echo '0';
                       }
                 ?>%</td>
             <td><?php
                 if (($b_diff_played[$mkey][$key_key]['total'] <> 0)&&($val['total'] <> $b_diff_played[$mkey][$key_key]['total'])) {
                   $rett1 = round( $val['win']/$val['total']*100 - ($val['win'] - $b_diff_played[$mkey][$key_key]['win'])/($val['total'] - $b_diff_played[$mkey][$key_key]['total'])*100,2);
                 } else {
                   $rett1 = 0;
                 }
                 if ($rett1 >0) echo $darkgreen.'+'.$rett1.'%'.$darkend;
                 if ($rett1 <0) echo $darkred.$rett1.'%'.$darkend;
                 if ($rett1 == 0) echo '0';
                 ?></td>
           </tr>
           <?php } ?>
         </tbody>
       </table>
       <?php ++$i;}; //foreach?>
     </td>
    </tr>
  </tbody>
</table>

</div>
<? if (count($b_played_tanks)>0) { ?>
<div align="center">
<br>
<table cellspacing="1" cellpadding="1" width="100%" align="center" id="t-table12">
    <thead>
      <tr>
        <th colspan="23" align="center"><?=$lang['teh_title'];?></th>
      </tr>
      <tr>
        <th align="center" rowspan="2"><?=$lang['name'];?></th>
        <th align="center" rowspan="2" colspan="2"><?=$lang['all_battles'];?></th>
        <th align="center" colspan="4"><?=$lang['all_wins'];?></th>
        <th align="center" colspan="2">Знаки классности</th>
      </tr>
      <tr>
        <th align="center" colspan="2">Кол-во</th>
        <th align="center">За период</th>
        <th align="center">Общее</th>
        <th align="center" ><?=$lang['name'];?></th>
        <th align="center" >Дельта</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $total_d_all = $win_d_all = 0;
    foreach ($b_played_tanks as $idkey => $val) {
       $total_d_all += $val['total_d'];
       $win_d_all += $val['win_d'];
       $b_misc8_sp = array ('_mark_of_mastery'); ?>

      <tr>
        <td><span class="hidden"><?=$val['level'];?></span>
            <?php if (strlen($val['name_i18n']) > 40) {
                      $trimmed = substr($val['name_i18n'], 0, 38 );
                      echo $trimmed.'...';
                  }   else {
                      echo $val['name_i18n'];
                  } ?>
        </td>
        <td><?=$val['total']; ?></td>
        <?php if (($val['total'] <> $val['total_d']) && ($val['total'] != 0)){ ?>
        <td>+<?=$val['total_d']; ?></td>
        <td><?=$val['win']; ?></td>
        <td><?php if ($val['win_d']<>0) echo '+';

                  echo $val['win_d']; ?></td>
        <td><?php echo round ($val['win_d']/$val['total_d']*100,2); ?>%</td>
        <td><?php echo round ($val['win']/$val['total']*100,2); ?>%
        (<?php $shown = Round($val['win']/$val['total']*100 - ($val['win']- $val['win_d'])/($val['total'] - $val['total_d'])*100,2);
                  if ($shown >  0) echo $darkgreen.'+'.$shown.'%'.$darkend;
                  if ($shown <  0) echo $darkred.$shown.'%'.$darkend;
                  if ($shown == 0) echo '0%';
        ?>)</td>

        <td><img src="<?='http://'.$config['gm_url'].$marks[$val['mark_of_mastery']]; ?>" /></td>
        <td><?php $shown = $val['mark_of_mastery']-$val['mark_of_mastery_d'];
                  if ($shown<>0) echo '+';
                  echo $shown;
            ?></td>

        <?php

        }   else {
            if ($val['total'] == 0 ) $val['total'] = 1;?>
        <td><?php echo '-'; ?></td>
        <td><?=$val['win']; ?></td>
        <td><?php echo '-'; ?></td>
        <td><?php echo round ($val['win']/$val['total']*100,2); ?>%</td>
        <td><?php echo '-'; ?></td>
        <td><?php if ($val['mark_of_mastery'] <> 0) {echo '<img src="http://'.$config['gm_url'].$marks[$val['mark_of_mastery']].'" />';} else {echo '-';} ?></td>
        <td><?php echo '-'; ?></td>
        <?php }; ?>
      </tr>
<?   $i++;  } ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan=2></td>
        <td>+<?php echo $total_d_all; ?></td>
        <td></td>
        <td>+<?php echo $win_d_all; ?></td>
        <td><?php echo Round($win_d_all/$total_d_all*100,2); ?>%
        (<?php
          $windiff = Round($win_d_all/$total_d_all*100 - $last['all_wins']/$last['all_battles']*100,2);
          if ($windiff >  0) echo $darkgreen.'+'.$windiff.'%'.$darkend;
          if ($windiff <  0) echo $darkred.$windiff.'%'.$darkend;
          if ($windiff == 0) echo '0%';
        ?>)</td>
        <td></td>
        <td colspan=2></td>
      </tr>
    </tfoot>
</table>
</div>
<? };
if ($count_med>0) {
      if (isset($b_pl_mp['major'])) {
         $sql = "SELECT medal_carius, medal_ekins, medal_kay, medal_le_clerc, medal_abrams, medal_poppel, medal_lavrinenko, medal_knispel FROM `col_medals` WHERE account_id = '".$b_res['account_id']."' AND updated_at < '".$b_to11."' AND updated_at >= '".$b_from11."' ORDER BY updated_at DESC;";
         $q = $db->prepare($sql);
         if ($q->execute() == TRUE) {
             $b_medals = $q->fetchAll();
         }   else {
             die(show_message($q->errorInfo(),__line__,__file__,$sql));
         };
         foreach ($b_pl_mp['major'] as $key => $val){
            if (($val > 0) ) {
                 $b_pl_mp['major'][$key]=$b_medals[0][$key];
            }
         }
      }; ?>
<br>
<div align="center">
<table cellspacing="1" cellpadding="1" width="100%" id="t-table13">
  <thead>
    <tr>
      <th><?=$lang['med_title'];?></th>
    </tr>
  </thead>
  <tbody>
  <?php $i=1; foreach($b_pl_mp as $type_key => $tmp) { ?>
    <tr>
      <td align="center">
          <?php echo '<span class="hidden">'.$i.'</span>';
                if ($type_key[strlen($type_key)-1]== '2') {
                    $out = substr($type_key, 0, strlen($type_key)-1);
                    echo $lang[$out].' - 2';
                }   else {
                    echo $lang[$type_key];
                }   ++$i; ?>
      </td>
    </tr>
    <tr>
      <td style="border:1px solid #666; text-align:center;"><span class="hidden"><?=$i;?>'</span>
      <?php foreach($tmp as $tm => $val) {
               $tm2 = ucfirst($tm);
               if (($type_key == 'major') && ($val <> '0')) {$tm2 .= $val;};
               if ($tm<>'lumberjack') { ?>
                  <div class="medalDiv">
                    <img width="67" height="71" title="<?php echo '<center>'.$lang['medal_'.$tm].'</center>'.$lang['title_'.$tm]; ?>" class="bb <?php if($val == 0) {echo 'faded';} ?>" alt="<?=$lang['title_'.$tm]; ?>" src="<?=$medn[$tm]['img']; ?>">
                    <div class="a_num ui-state-highlight ui-widget-content"><?=$val; ?></div>
                  </div>
          <?   }
            } ?>
      </td>
    </tr>
  <? ++$i; }; ?>
  </tbody>
</table>
</div>
<? };
} else {?>
    <div align="center" class="ui-state-highlight ui-widget-content"><?=$lang['error_cron_off_or_none'];?></div>
  <?php };
} else echo '<div align="center" class="ui-state-highlight ui-widget-content">Выберите две корректные даты!</div>';

Unset($b_tanks);
?>