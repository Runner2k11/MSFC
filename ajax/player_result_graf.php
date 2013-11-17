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
/*
 include(ROOT_DIR."/pChart/pData.class");
 include(ROOT_DIR."/pChart/pChart.class");
*/
?>
<?php
	$b_info_nation = $b_info_type = $b_info_lvl = $b_diff_played = $b_played_tanks = $effect1 = $b_pl_mp = $diff = $last = array();
     global $db;
     $b_nation = tanks_nations();
	 
	 $sql = "SELECT * FROM `tanks` ORDER BY id ASC;";
     $q = $db->prepare($sql);
     if ($q->execute() == TRUE) {
        $b_tank_name_tmp = $q->fetchAll(PDO::FETCH_ASSOC);
     }  else {
        die(show_message($q->errorInfo(),__line__,__file__,$sql));
     };
     foreach($b_tank_name_tmp as $tmp) {
        $b_tank_name[$tmp['id']] = $tmp;
     };
     unset($b_tank_name_tmp);
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
	if (isset($_POST['b_w']) ) {
        $b_w = $_POST['b_w'];
    }else{
        $b_w = '';
    };

If (($b_from<>'') && ($b_to<>'') ) {
    $b_from1 = explode('.',$b_from);
    $b_from11 = mktime(0, 0, 0, $b_from1['1'], $b_from1['0'], $b_from1['2']);
    $b_to1 = explode('.',$b_to);
    $b_to11 = mktime(23, 59, 59, $b_to1['1'], $b_to1['0'], $b_to1['2']);
    $resall = $cache->get('get_last_roster_'.$config['clan'],0);
    foreach($resall['data']['members'] as $id1 => $val ) {
       if ($val['account_name']==$b_player) {
           $b_res = $val;
       }
     };
	
	function Lvl($dat, $db, $b_nation, $b_tank_name, $b_res){
		$result=0;
	    foreach ($b_nation as $val) {
			$sql = "SELECT * FROM `col_tank_".$val['nation']."` WHERE account_id = '".$b_res['account_id']."' AND up = '".$dat['up']."' ORDER BY up DESC;";
                $q = $db->prepare($sql);
                if ($q->execute() == TRUE) {
                    $tanks = $q->fetchAll();
                } else {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                };

			foreach ($b_tank_name as $id => $val3) {
                 if (!isset ($b_diff_played['nation'][$b_tank_name[$id]['nation']]['win']))    $b_diff_played['nation'][$b_tank_name[$id]['nation']]['win'] = 0;
                 if (!isset ($b_diff_played['nation'][$b_tank_name[$id]['nation']]['total']))  $b_diff_played['nation'][$b_tank_name[$id]['nation']]['total'] = 0;
                 if (!isset ($b_diff_played['lvl'][$b_tank_name[$id]['lvl']]['win']))          $b_diff_played['lvl'][$b_tank_name[$id]['lvl']]['win'] = 0;
                 if (!isset ($b_diff_played['lvl'][$b_tank_name[$id]['lvl']]['total']))        $b_diff_played['lvl'][$b_tank_name[$id]['lvl']]['total'] = 0;
                 if (!isset ($b_diff_played['type'][$b_tank_name[$id]['type']]['win']))        $b_diff_played['type'][$b_tank_name[$id]['type']]['win'] = 0;
                 if (!isset ($b_diff_played['type'][$b_tank_name[$id]['type']]['total']))      $b_diff_played['type'][$b_tank_name[$id]['type']]['total'] = 0;

                 if (!isset ($b_info['nation'][$b_tank_name[$id]['nation']]['win']))           $b_info['nation'][$b_tank_name[$id]['nation']]['win'] = 0;
                 if (!isset ($b_info['nation'][$b_tank_name[$id]['nation']]['total']))         $b_info['nation'][$b_tank_name[$id]['nation']]['total'] = 0;
                 if (!isset ($b_info['lvl'][$b_tank_name[$id]['lvl']]['win']))                 $b_info['lvl'][$b_tank_name[$id]['lvl']]['win'] = 0;
                 if (!isset ($b_info['lvl'][$b_tank_name[$id]['lvl']]['total']))               $b_info['lvl'][$b_tank_name[$id]['lvl']]['total'] = 0;
                 if (!isset ($b_info['type'][$b_tank_name[$id]['type']]['win']))               $b_info['type'][$b_tank_name[$id]['type']]['win'] = 0;
                 if (!isset ($b_info['type'][$b_tank_name[$id]['type']]['total']))             $b_info['type'][$b_tank_name[$id]['type']]['total'] = 0;

				If (isset($tanks[0][$id.'_t'])) {

					if (($tanks[count($tanks)-1][$id.'_t'] == '0')&&($tanks[0][$id.'_t'] > 0)) $b_new_tank[$id] = $b_tank_name[$id];
					if ($tanks[count($tanks)-1][$id.'_t'] <> $tanks[0][$id.'_t']) {
						$b_played_tanks[$id]['tank'] =  $b_tank_name[$id]['tank'];
						$b_played_tanks[$id]['nation'] = $val['nation'];
						$b_played_tanks[$id]['total'] = $tanks[0][$id.'_t'];
						$b_played_tanks[$id]['win'] = $tanks[0][$id.'_w'];
						$b_played_tanks[$id]['total_d'] = $tanks[0][$id.'_t']-$tanks[count($tanks)-1][$id.'_t'];
						$b_played_tanks[$id]['win_d'] = $tanks[0][$id.'_w']-$tanks[count($tanks)-1][$id.'_w'];

						$b_diff_played['nation'][$b_tank_name[$id]['nation']]['total'] += ($tanks[0][$id.'_t']- $tanks[count($tanks)-1][$id.'_t']);
						$b_diff_played['nation'][$b_tank_name[$id]['nation']]['win'] +=   ($tanks[0][$id.'_w']- $tanks[count($tanks)-1][$id.'_w']);
						$b_diff_played['lvl'][$b_tank_name[$id]['lvl']]['total'] +=       ($tanks[0][$id.'_t']- $tanks[count($tanks)-1][$id.'_t']);
						$b_diff_played['lvl'][$b_tank_name[$id]['lvl']]['win'] +=         ($tanks[0][$id.'_w']- $tanks[count($tanks)-1][$id.'_w']);
						$b_diff_played['type'][$b_tank_name[$id]['type']]['total'] +=     ($tanks[0][$id.'_t']- $tanks[count($tanks)-1][$id.'_t']);
						$b_diff_played['type'][$b_tank_name[$id]['type']]['win'] +=       ($tanks[0][$id.'_w']- $tanks[count($tanks)-1][$id.'_w']);
					}
					if ($tanks[0][$id.'_t'] <> '0') {
						$b_info['nation'][$b_tank_name[$id]['nation']]['win'] += $tanks[0][$id.'_w'];
						$b_info['nation'][$b_tank_name[$id]['nation']]['total'] += $tanks[0][$id.'_t'];
						$b_info['lvl'][$b_tank_name[$id]['lvl']]['win'] += $tanks[0][$id.'_w'];
						$b_info['lvl'][$b_tank_name[$id]['lvl']]['total'] += $tanks[0][$id.'_t'];
						$b_info['type'][$b_tank_name[$id]['type']]['win'] += $tanks[0][$id.'_w'];
						$b_info['type'][$b_tank_name[$id]['type']]['total'] += $tanks[0][$id.'_t'];
					}
				}
			}
		}
		foreach ($b_info['lvl'] as $lvl_key => $val){
			$result += $lvl_key*$val['total']/$dat['total'];
			};
		return $result;
	};
	$sql = "SELECT * FROM  `col_players` WHERE account_id ='".$b_res['account_id']."'AND up < '".$b_to11."' AND up >= '".$b_from11."' ORDER BY up;";
	$q = $db->prepare($sql);
          if ($q->execute() == TRUE) {
              $b_player_all = $q->fetchAll();
          }   else {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          };
	 $i=0;
	 foreach ($b_player_all as $key => $val) {
		if ($key!=0){
			if  (date("d.m.Y",  $b_player_all[$key]['up']) != date("d.m.Y",  $b_player_all[$key-1]['up'])){
				$last1[$i]=$b_player_all[$key];
				$i++;
			};
		}else{
			$last1[$i]=$b_player_all[$key];
			$i++;
		};
	};

	foreach ($last1 as $key=>$val) {
		If ($last1[$key]['total'] == 0)  {$last1[$key]['total'] = 1/100000;}
		$effect1[$key]['des'] = $last1[$key]['des'] / $last1[$key]['total'];
		$effect1[$key]['dmg'] = $last1[$key]['dmg'] / $last1[$key]['total'];
		$effect1[$key]['spot'] = $last1[$key]['spot'] / $last1[$key]['total'];
		$effect1[$key]['def'] = $last1[$key]['def'] / $last1[$key]['total'];
		$effect1[$key]['cap'] = $last1[$key]['cap'] / $last1[$key]['total'];
		$effect1[$key]['lvl'] = 0;
		$effect1[$key]['lvl']=Lvl($last1[$key], $db, $b_nation, $b_tank_name, $b_res);
		
		$effect1[$key]['lvl'] = number_format($effect1[$key]['lvl'], 2, '.', '');
		$eff_rating[$key]  = number_format($effect1[$key]['dmg']*(10/($effect1[$key]['lvl'] +2 ))*(0.23+2*$effect1[$key]['lvl']/100) + $effect1[$key]['des']*0.25*1000 + $effect1[$key]['spot']*0.15*1000 + log($effect1[$key]['cap']+1,1.732)*0.15*1000 + $effect1[$key]['def']*0.15*1000,2, '.', '');
		$datt1[$key] = date("Y-m-d",  $last1[$key]['up']);
		$effect1[$key]['dey']=number_format($last1[$key]['total']/($last1[$key]['up']-$last1[$key]['reg'])*86400,2,".","");
	};

function graf ($efect, $k){
	foreach ($efect as $key=>$val){
		$grafik[$key]=$efect[$key][$k];
	};
	return $grafik;
};
if ($b_w=='eff') {$grafik=$eff_rating; $legend=$lang['legend_1']; };
if ($b_w=='dmg') {$grafik=graf($effect1, 'dmg'); $legend=$lang['legend_2'];};
if ($b_w=='des') {$grafik=graf($effect1, 'des'); $legend=$lang['legend_3'];};
if ($b_w=='spot') {$grafik=graf($effect1, 'spot'); $legend=$lang['legend_4'];};
if ($b_w=='cap') {$grafik=graf($effect1, 'cap'); $legend=$lang['legend_5'];};
if ($b_w=='def') {$grafik=graf($effect1, 'def'); $legend=$lang['legend_6'];};
if ($b_w=='lvl') {$grafik=graf($effect1, 'lvl'); $legend=$lang['legend_7'];};
if ($b_w=='dey') {$grafik=graf($effect1, 'dey'); $legend=$lang['legend_8'];};

$result = array();
$grafik1=array_combine($datt1, $grafik);
foreach ($grafik1 as $label => $value) {
    $result[] = array($label,(float)$value); // make a "small" array for each pair
};


};
?>

<script type="text/javascript" src="js/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="js/plugins/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="js/plugins/jqplot.cursor.min.js"></script>
<script type="text/javascript" src="js/plugins/jqplot.dateAxisRenderer.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/jquery.jqplot.min.css" />
<script type="text/javascript" src="js/excanvas.min.js"></script>

<script type="text/javascript">
 $(document).ready(function(){
  $.jqplot._noToImageButton = true;
  var line1=<?=json_encode($result)?>;
  var plot1 = $.jqplot('chart_graf', [line1], {
	  
      title: '<?=$legend;?>',
      axesDefaults: {
            rendererOptions: {
                baselineWidth: 1.5,
                baselineColor: '#444444',
                drawBaseline: false
            }
        },
	  axes:{
        xaxis:{
          renderer:$.jqplot.DateAxisRenderer,
          tickOptions:{
            formatString:'%d.%m',
			angle: -30			
          }	  
        },
        yaxis:{
          tickOptions:{
            formatString:'%.2f'
            }
        }
      },
	  grid: {
            background: 'rgba(57,57,57,0.0)',
            drawBorder: false,
            shadow: false,
            gridLineColor: '#666666',
            gridLineWidth: 0.5
        },
	  seriesDefaults: {
            rendererOptions: {
                smooth: true,
                animation: {
                    show: true
                }
            },
            showMarker: true
        },
      highlighter: {
        show: true,
        sizeAdjust: 7.5
      },
      cursor: {
        show: true
      }
  });
});
 
</script>




<div id="chart_graf" style="height:600px; width:800px;"></div>
