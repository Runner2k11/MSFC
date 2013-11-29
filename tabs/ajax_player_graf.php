<?php
    /*
    * Project:     Clan Stat
    * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
    * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
    * -----------------------------------------------------------------------
    * Began:       2012
    * Date:        $Date: 2012-12-01 $
    * -----------------------------------------------------------------------
    * @author      $Author: SHW $
    * @copyright   2011-2012 SHW
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 2.2.0 $
    *
    */
?>
<?php $mindate_g = array();
      foreach($res as $name => $val){
         $arrname_g[$name]= strtoupper($name);
      }
      asort($arrname_g);
      foreach($arrname_g as $key2 => $name){
         $sql = "SELECT MIN(updated_at) FROM `col_players` where nickname='".$key2."';";
         $q = $db->prepare($sql);
         if ($q->execute() == TRUE) {
             $mindate_g[$key2] = $q->fetchColumn();
         }   else {
             $mindate_g[$key2] = 0;
         }
      }
?>
<script type="text/javascript">
function smtmagic_g()
        {
            miscval = <?php if (isset($mindate_g)) {echo json_encode($mindate_g);} else {echo 'array ();';}; ?>;
            $( "#b_from_graf" ).datepicker( "option", "minDate", new Date(miscval[$('#b_player_graf').val()]*1000) );
            if ($('#b_from_graf').val() >= $('#b_to_graf').val()) { $( "#b_to" ).datepicker( "setDate", new Date()); }
        };
    $(document).ready(function(){
        $( "#b_from_graf" ).datepicker({
            defaultDate: "-1d",
            altFormat: '@' ,
            firstDay: 1,
            maxDate: 0,
            dateFormat: 'dd.mm.yy',
            onSelect: function( selectedDate ) {
                $( "#b_to_graf" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#b_to_graf" ).datepicker({
            defaultDate: 0,
            firstDay: 1,
            dateFormat: 'dd.mm.yy',
            maxDate: 0,
            onSelect: function( selectedDate ) {
                $( "#b_from_graf" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
        $("#b_show_activity_graf").button();

        $("#b_show_activity_graf").click( function() {
            $.ajax({
                cache: true,
                type: "POST",
                data: ({
                  b_from   : $('#b_from_graf').val(),
                  b_to     : $('#b_to_graf').val(),
                  b_player : $('#b_player_graf').val(),
				  b_w	   : $('#b_w_graf').val(),
				  b_type   : $('#b_type').val(),
                  db_pref : '<?php echo $db->prefix; ?>'
                }),
                url: "./ajax/player_result_graf.php",
                success: function(msg){
                    $("#player_result_graf").addClass("ui-state-disabled");
                    $("#player_result_graf").html(msg).show();
                },
                complete: function() {
                  $("#player_result_graf").removeClass("ui-state-disabled");
                  check_Width($("table#tmain"), $("div#tabs-<?=$key;?>"));
                }
            });
            return false;
        });
        $("#b_show_activity_graf").click();
        smtmagic_g();
});
</script>

<div align="center" id="ajax_player_result_width_graf">
    <form method="post" enctype="multipart/form-data">
    <br />
    <?=$lang['name'];?>
    <select id="b_player_graf" onchange="smtmagic_g();">
            <?php foreach($arrname_g as $key2 => $name){ ?>
            <option value="<?=$key2;?>"><?=$key2;?></option>
            <?php } ?>
    </select>
	<select id="b_type">
			<option value="b_type_1"><?=$lang['b_type_1'];?></option>
			<option value="b_type_2"><?=$lang['b_type_2'];?></option>
			<option value="b_type_3"><?=$lang['b_type_3'];?></option>
	</select>
    <select id="b_w_graf" >
            <option value="eff"><?=$lang['b_w_graf_1'];?></option>
			<option value="all_damage_dealt"><?=$lang['b_w_graf_2'];?></option>
			<option value="all_frags"><?=$lang['b_w_graf_3'];?></option>
			<option value="all_spotted"><?=$lang['b_w_graf_4'];?></option>
			<option value="all_capture_points"><?=$lang['b_w_graf_5'];?></option>
			<option value="all_dropped_capture_points"><?=$lang['b_w_graf_6'];?></option>
			<option value="level"><?=$lang['b_w_graf_7'];?></option>
			<option value="dey"><?=$lang['b_w_graf_8'];?></option>
			<option value="all_wins"><?=$lang['b_w_graf_9'];?></option>
    </select>
	<?=$lang['graf_1'];?>
	<input type="text" id="b_from_graf" name="b_from_graf" value="" />
	<?=$lang['graf_2'];?>
    <input type="text" id="b_to_graf" name="b_to_graf" value="" />
    <a href="#tabs-<?php echo $key; ?>" id="b_show_activity_graf"><?=$lang['select_show'];?></a>
    </form>
	
    <div id="player_result_graf"></div>
</div>