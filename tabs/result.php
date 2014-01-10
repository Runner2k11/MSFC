<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2011
    * Date:        $Date: 2013-10-20 00:00:00 +0200 $
    * -----------------------------------------------------------------------
    * @author      $Author: Edd, Exinaus, SHW  $
    * @copyright   2011-2012 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.0.0 $
    *
    */
?>
<script>
$(document).ready(function() {
	$( "#trigger_active" ).buttonset();
    $( "#triggerperform_all" ).buttonset();
    $( "#triggerperform_clan" ).buttonset();
	$( "#triggerperform_company" ).buttonset();
	$(".t_all").show();
	$(".wall").show();
	$(".t_clan")
		.add(".t_company")
		.add(".qall")
		.add(".qclan")
		.add(".wclan")
		.add(".qcompany")
		.add(".wcompany").hide();
	$("#change_all_active").click(function() {
		$(".t_all").show();
		$(".wall").show();
		$(".t_clan")
			.add(".t_company")
			.add(".qall")
			.add(".qclan")
			.add(".wclan")
			.add(".qcompany")
			.add(".wcompany").hide();
	});
	$("#change_clan_active").click(function() {
		$(".t_clan").show();
		$(".wclan").show();
		$(".t_company")
			.add(".t_all")
			.add(".qall")
			.add(".qclan")
			.add(".wall")
			.add(".qcompany")
			.add(".wcompany").hide();
	});
	$("#change_company_active").click(function() {
		$(".t_company").show();
		$(".wcompany").show();
		$(".t_all")
			.add(".t_clan")
			.add(".qall")
			.add(".qclan")
			.add(".wclan")
			.add(".qcompany")
			.add(".wall").hide();
	});
	$("#change_button_fullshow_all").click(function() {
		$(".wall").show();
		$(".qall")
			.add(".qclan")
			.add(".wclan")
			.add(".qcompany")
			.add(".wcompany").hide();
	});
	$("#change_button_averageshow_all").click(function() {
		$(".qall").show();
		$(".wall")
			.add(".qclan")
			.add(".wclan")
			.add(".qcompany")
			.add(".wcompany").hide();
	});
	$("#change_button_fullshow_clan").click(function() {
		$(".wclan").show();
		$(".qall")
			.add(".qclan")
			.add(".wall")
			.add(".qcompany")
			.add(".wcompany").hide();
	});
	$("#change_button_averageshow_clan").click(function() {
		$(".qclan").show();
		$(".wall")
			.add(".qall")
			.add(".wclan")
			.add(".qcompany")
			.add(".wcompany").hide();
	});
	$("#change_button_fullshow_company").click(function() {
		$(".wcompany").show();
		$(".qall")
			.add(".qclan")
			.add(".wclan")
			.add(".qcompany")
			.add(".wall").hide();
	});
	$("#change_button_averageshow_company").click(function() {
		$(".qcompany").show();
		$(".wall")
			.add(".qclan")
			.add(".wclan")
			.add(".qall")
			.add(".wcompany").hide();
	});
});
</script>
<div align="center">
  <form>
    <div id="trigger_active" align="center">
        <input type="radio" id="change_all_active" name="trigger_active" checked="checked" /><label for="change_all_active"><?=$lang['a_cat_1'];?></label>
        <input type="radio" id="change_clan_active" name="trigger_active" /><label for="change_clan_active"><?=$lang['a_cat_2'];?></label>
        <input type="radio" id="change_company_active" name="trigger_active" /><label for="change_company_active"><?=$lang['a_cat_3'];?></label>
    </div>
  </form>
</div>
<div align="center">
<form class="t_all">
    <div id="triggerperform_all" align="center" class="table-id-<?=$key;?> ">
        <input type="radio" id="change_button_fullshow_all" name="triggerperform_all" checked="checked" /><label for="change_button_fullshow_all"><?=$lang['show_full_perform'];?></label>
        <input type="radio" id="change_button_averageshow_all" name="triggerperform_all" /><label for="change_button_averageshow_all"><?=$lang['show_average_perform'];?></label>
    </div>
</form>
<form class="t_clan">
    <div id="triggerperform_clan" align="center" class="table-id-<?=$key;?>">
        <input type="radio" id="change_button_fullshow_clan" name="triggerperform_clan" checked="checked" /><label for="change_button_fullshow_clan"><?=$lang['show_full_perform'];?></label>
        <input type="radio" id="change_button_averageshow_clan" name="triggerperform_clan" /><label for="change_button_averageshow_clan"><?=$lang['show_average_perform'];?></label>
    </div>
</form>
<form class="t_company">
    <div id="triggerperform_company" align="center" class="table-id-<?=$key;?>">
        <input type="radio" id="change_button_fullshow_company" name="triggerperform_company" checked="checked" /><label for="change_button_fullshow_company"><?=$lang['show_full_perform'];?></label>
        <input type="radio" id="change_button_averageshow_company" name="triggerperform_company" /><label for="change_button_averageshow_company"><?=$lang['show_average_perform'];?></label>
    </div>
</form>
    <table id="perform_all_n" width="100%" cellspacing="1" class="table-id-<?=$key;?>">
        <thead>
            <tr>
                <?php echo '<th>'.$lang['name'].'</th>';
                $perform = array ('battles', 'wins',  'losses', 'draws', 'survived_battles', 'battle_avg_xp', 'xp', 'frags', 'spotted', 'damage_dealt', 'damage_received', 'hits_percents', 'capture_points', 'dropped_capture_points');
 				$chang = array ('all', 'clan', 'company');
				foreach($perform as $cat){ 
                    foreach ($chang as $cat1) { ?>
						<?php if(($cat == 'battles')||($cat =='hits_percents')) { ?>
                            <th class='q<?=$cat1?> '><?=$lang['all_'.$cat];?></th>
							<th class='w<?=$cat1?> '><?=$lang['all_'.$cat];?></th>
						<? } elseif(($cat == 'wins')||($cat == 'losses')||($cat == 'draws')||($cat=='survived_battles')){ ?>
                            <th class='q<?=$cat1?>'><?=$lang['all_'.$cat];?></th>
                            <th class='w<?=$cat1?>'><?=$lang['all_'.$cat];?></th>
						<?php }elseif ($cat == 'xp') {  ?>
                            <th class='w<?=$cat1?>'><?=$lang['all_'.$cat];?></th>
						<?php }elseif ($cat == 'battle_avg_xp') {  ?>
							<th class='q<?=$cat1?>'><?=$lang['all_'.$cat];?></th>
						<?php }else {  ?>
							<th class='q<?=$cat1?>'><?=$lang['all_'.$cat];?></th>
                            <th class='w<?=$cat1?>'><?=$lang['all_'.$cat];?></th>
						<?php }}} ?>
            </tr>  
        </thead>
        <tbody>
            <?php foreach($res as $name => $val){  ?>
                <tr> 
                    <td><a href="<?php echo $config['base'],$name,'/'; ?>" target="_blank"><?=$name; ?></a></td>
                    <?php foreach($perform as $cat){ 
							foreach ($chang as $cat1) {?>
                        <?php if(($cat == 'battles')||($cat =='hits_percents')) { ?>
                            <td class='q<?=$cat1?>'>
                            <?php echo $val['data']['statistics'][$cat1][$cat]; ?>
                            </td>
							<td class='w<?=$cat1?>'>
                            <?php echo $val['data']['statistics'][$cat1][$cat]; ?>
                            </td>
						<?php } elseif($cat == 'xp'){ ?>
							<td class='w<?=$cat1?>'>
                            <?php echo $val['data']['statistics'][$cat1][$cat]; ?>
                            </td>
						<?php } elseif($cat == 'battle_avg_xp'){ ?>
							<td class='q<?=$cat1?>'>
                            <?php echo $val['data']['statistics'][$cat1][$cat]; ?>
                            </td>
						<?php } elseif(($cat == 'wins')||($cat == 'losses')||($cat == 'draws')||($cat=='survived_battles')){ ?>
							<td class='q<?=$cat1?>'>
                            <? if($val['data']['statistics'][$cat1]['battles'] > 0) { echo round($val['data']['statistics'][$cat1][$cat]/$val['data']['statistics'][$cat1]['battles']*100,2), '%'; } else { echo '0'; } ?>
                            </td>
                            <td class='w<?=$cat1?>'>
                            <?php echo $val['data']['statistics'][$cat1][$cat]; ?>
                            </td>
                        <?php } else { ?>
                            <td class='q<?=$cat1?>'>
                            <? if($val['data']['statistics'][$cat1]['battles'] > 0) { echo round($val['data']['statistics'][$cat1][$cat]/$val['data']['statistics'][$cat1]['battles'],2); } else { echo '0'; } ?>
                            </td>
                            <td class='w<?=$cat1?>'>
                            <?php echo $val['data']['statistics'][$cat1][$cat]; ?>
                            </td>
                       <?php }
                       }} ?>
                </tr>
                <?php } ?>
        </tbody>  
    </table>
</div>
<? unset($perform); unset($cat); unset($cat1); unset($name); unset($val); ?>