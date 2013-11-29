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

?>
<?php
	$b_info_nation = $b_info_type = $b_info_lvl = $b_diff_played = $b_played_tanks = $effect1 = $b_pl_mp = $diff = $last = array();
     global $db;
     $b_nation = tanks_nations();
	 $b_tank_name = tanks();
	 
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
	if (isset($_POST['b_type']) ) {
        $b_type = $_POST['b_type'];
    }else{
        $b_type = '';
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
	
	function Lvl($dat, $db, $b_nation, $b_tank_name, $b_res){
		$result=0;
	    foreach ($b_nation as $val) {
			
				$sql = "SELECT * FROM `col_tank_".$val['nation']."` WHERE account_id = '".$b_res['account_id']."' AND updated_at = '".$dat['updated_at']."' ORDER BY updated_at DESC;";
            
				$q = $db->prepare($sql);
                if ($q->execute() == TRUE) {
                    $tanks = $q->fetchAll();
                } else {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                };

			foreach ($b_tank_name as $id => $val3) {
                if (!isset($info[$b_tank_name[$id]['level']])) $info[$b_tank_name[$id]['level']]=0;
				If (isset($tanks[0][$id.'_battles'])) {
					$info[$b_tank_name[$id]['level']]+=$tanks[0][$id.'_battles'];
				};
			}
		}
		
		foreach ($info as $lvl_key => $val){
			$result += $lvl_key*$val/$dat['all_battles'];
		};
		
		return $result;
		
	};
	
	function lvl_1 ($dat1, $dat2, $db, $b_nation, $b_tank_name, $b_res){
		$info=array();
		$result=0;
	    foreach ($b_nation as $val) {
			$sql = "SELECT * FROM `col_tank_".$val['nation']."` WHERE account_id = '".$b_res['account_id']."' AND (updated_at = '".$dat1."' OR updated_at = '".$dat2."' ) ORDER BY updated_at DESC;";
            $q = $db->prepare($sql);
                if ($q->execute() == TRUE) {
                    $tanks = $q->fetchAll();
                } else {
                    die(show_message($q->errorInfo(),__line__,__file__,$sql));
                };
			foreach ($b_tank_name as $id => $val3) {
				if (!isset($info[$b_tank_name[$id]['level']])) $info[$b_tank_name[$id]['level']]=0;
				If (isset($tanks[0][$id.'_battles'])) {
					$info[$b_tank_name[$id]['level']]+=$tanks[0][$id.'_battles']-$tanks[1][$id.'_battles'];
				};
			};
		};
		
		$t=array_sum($info);
		if ($t==0) $t=1/100000;
			foreach ($info as $lvl_k=>$val){
				$result+=$lvl_k*$val/$t;
			};
		return $result;
	};
	
	$sql = "SELECT * FROM  `col_players` WHERE account_id = '".$b_res['account_id']."' AND updated_at < '".$b_to11."' AND updated_at >= '".$b_from11."' ORDER BY updated_at DESC;";
	$q = $db->prepare($sql);
          if ($q->execute() == TRUE) {
              $b_player_all = $q->fetchAll();
          }   else {
              die(show_message($q->errorInfo(),__line__,__file__,$sql));
          };
	 $i=0;
	 foreach ($b_player_all as $key => $val) {
		if ($key!=0){
			if  (date("d.m.Y",  $b_player_all[$key]['updated_at']) != date("d.m.Y",  $b_player_all[$key-1]['updated_at'])){
				$last1[$i]=$b_player_all[$key];
				$i++;
			};
		}else{
			$last1[$i]=$b_player_all[$key];
			$i++;
		};
	};
	
		foreach ($last1 as $key=>$val) {
			If ($last1[$key]['all_battles'] == 0)  {$last1[$key]['all_battles'] = 1/100000;}
			$effect1[$key]['all_frags'] = $last1[$key]['all_frags'] / $last1[$key]['all_battles'];
			$effect1[$key]['all_damage_dealt'] = $last1[$key]['all_damage_dealt'] / $last1[$key]['all_battles'];
			$effect1[$key]['all_spotted'] = $last1[$key]['all_spotted'] / $last1[$key]['all_battles'];
			$effect1[$key]['all_dropped_capture_points'] = $last1[$key]['all_dropped_capture_points'] / $last1[$key]['all_battles'];
			$effect1[$key]['all_capture_points'] = $last1[$key]['all_capture_points'] / $last1[$key]['all_battles'];
			$effect1[$key]['level'] = 0;
			$effect1[$key]['level']=Lvl($last1[$key], $db, $b_nation, $b_tank_name, $b_res);
			$effect1[$key]['all_wins']=number_format($last1[$key]['all_wins']/$last1[$key]['all_battles']*100, 2,'.', '');
			$effect1[$key]['level'] = number_format($effect1[$key]['level'], 2, '.', '');
			$eff_rating[$key]  = number_format($effect1[$key]['all_damage_dealt']*(10/($effect1[$key]['level'] +2 ))*(0.23+2*$effect1[$key]['level']/100) + $effect1[$key]['all_frags']*0.25*1000 + $effect1[$key]['all_spotted']*0.15*1000 + log($effect1[$key]['all_capture_points']+1,1.732)*0.15*1000 + $effect1[$key]['all_dropped_capture_points']*0.15*1000,2, '.', '');
			$datt1[$key] = date("Y-m-d",  $last1[$key]['updated_at']);
			$effect1[$key]['dey']=number_format($last1[$key]['all_battles']/($last1[$key]['updated_at']-$last1[$key]['created_at'])*86400,2,".","");
			
		};
		$st='mean';
		$en='all';
		
	if($b_type=='b_type_2'){
		foreach ($last1 as $key=>$val) {
			if ($key==0){}else{
				$effect1[$key]['dey'] = $last1[$key]['all_battles'] - $last1[$key-1]['all_battles'];
				if ($effect1[$key]['dey']==0) continue;
				$effect1[$key]['all_frags'] = ($last1[$key]['all_frags']-$last1[$key-1]['all_frags']) / $effect1[$key]['dey'];
				$effect1[$key]['all_damage_dealt'] = ($last1[$key]['all_damage_dealt']-$last1[$key-1]['all_damage_dealt']) / $effect1[$key]['dey'];
				$effect1[$key]['all_spotted'] = ($last1[$key]['all_spotted']-$last1[$key-1]['all_spotted']) / $effect1[$key]['dey'];
				$effect1[$key]['all_dropped_capture_points'] = ($last1[$key]['all_dropped_capture_points']-$last1[$key-1]['all_dropped_capture_points']) / $effect1[$key]['dey'];
				$effect1[$key]['all_capture_points'] = ($last1[$key]['all_capture_points']-$last1[$key-1]['all_capture_points']) / $effect1[$key]['dey'];
				$effect1[$key]['all_wins']=number_format(($last1[$key]['all_wins']-$last1[$key-1]['all_wins'])/$effect1[$key]['dey']*100, 2,'.', '');
				
				$effect1[$key]['level'] = 0;
				$effect1[$key]['level']=Lvl_1($last1[$key]['updated_at'], $last1[$key-1]['updated_at'], $db, $b_nation, $b_tank_name, $b_res);
		
				$effect1[$key]['level'] = number_format($effect1[$key]['level'], 2, '.', '');
				$eff_rating[$key]  = number_format($effect1[$key]['all_damage_dealt']*(10/($effect1[$key]['level'] +2 ))*(0.23+2*$effect1[$key]['level']/100) + $effect1[$key]['all_frags']*0.25*1000 + $effect1[$key]['all_spotted']*0.15*1000 + log($effect1[$key]['all_capture_points']+1,1.732)*0.15*1000 + $effect1[$key]['all_dropped_capture_points']*0.15*1000,2, '.', '');
			
			};
		};
		$st='mean';
		$en='day';
	}elseif($b_type=='b_type_3'){
		foreach ($last1 as $key=>$val) {
			if ($key==0){}else{
				$effect1[$key]['dey'] = $last1[$key]['all_battles'] - $last1[$key-1]['all_battles'];
				if ($effect1[$key]['dey']==0) continue;
				$effect1[$key]['all_frags'] = $last1[$key]['all_frags'] - $last1[$key-1]['all_frags'];
				$effect1[$key]['all_damage_dealt'] = $last1[$key]['all_damage_dealt'] - $last1[$key-1]['all_damage_dealt'];
				$effect1[$key]['all_spotted'] = $last1[$key]['all_spotted'] - $last1[$key-1]['all_spotted'];
				$effect1[$key]['all_dropped_capture_points'] = $last1[$key]['all_dropped_capture_points'] - $last1[$key-1]['all_dropped_capture_points'];
				$effect1[$key]['all_capture_points'] = $last1[$key]['all_capture_points'] - $last1[$key-1]['all_capture_points'];
				$effect1[$key]['all_wins']=number_format(($last1[$key]['all_wins']-$last1[$key-1]['all_wins'])/$effect1[$key]['dey']*100, 2,'.', '');
				$effect1[$key]['level'] = 0;
				$effect1[$key]['level']=Lvl_1($last1[$key]['updated_at'], $last1[$key-1]['updated_at'], $db, $b_nation, $b_tank_name, $b_res);
		
				$effect1[$key]['level'] = number_format($effect1[$key]['level'], 2, '.', '');
			};
		};
		$st='abs';
		$en='day';
	};

	function graf ($efect, $k){
		foreach ($efect as $key=>$val){
			$grafik[$key]=$efect[$key][$k];
		};
		return $grafik;
	};
	if ($b_w=='eff') {
		$grafik=$eff_rating;
		$legend=$lang['legend_'.$b_w];
	}else{
		$grafik=graf($effect1, $b_w);
		if($b_w=='dey' & $st=='mean' & $en=='day'){
			$legend=$lang['legend_'.$b_w].$lang['legend_end_'.$en];
		}else{
			$legend=$lang['legend_start_'.$st].$lang['legend_'.$b_w].$lang['legend_end_'.$en];
		};
	};



	$result = array();
	$grafik1=array_combine($datt1, $grafik);
	foreach ($grafik1 as $label => $value) {
		$result[] = array($label,(float)$value); // make a "small" array for each pair
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
      axesall_dropped_all_capture_pointsture_pointsaults: {
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
	  seriesall_dropped_all_capture_pointsture_pointsaults: {
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
<? 
} else echo '<div align="center" class="ui-state-highlight ui-widget-content">Выберите две корректные даты!</div>';


?>