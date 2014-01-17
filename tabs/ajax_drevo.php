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
<script type="text/javascript">

    $(document).ready(function(){
        $("#b_show_activity1").button();
		$("#b_show_activity2").button();
        $("#b_show_activity1").click( function() {
            $.ajax({
                cache: true,
                type: "POST",
                data: ({
                                    b_player : $('#b_player1').val()
                }),
                url: "./ajax/drevo.php",
                success: function(msg){
                    $("#player_result1").addClass("ui-state-disabled");
                    $("#player_result1").html(msg).show();
                },
                complete: function() {
                  $("#player_result1").removeClass("ui-state-disabled");
                  check_Width($("table#tmain"), $("div#tabs-<?=$key;?>"));
                }
            });
            return false;
        });
        $("#b_show_activity2").click( function() {
			var adrr = "http://armor.kiev.ua/wot/gamertrees/"+$('#b_player1').val();
			window.open(adrr,'…','');
			return false;
		});
   
});
</script>
<?php 
	foreach ($res as $name => $val) {
		$arrnames[$name] = $name;
	};
	asort($arrnames);

?>
<div align="center" id="ajax_player_result_width1">
    <form method="post" enctype="multipart/form-data">
    <br />
    <?=$lang['name'];?>
    <select id="b_player1" onchange="smtmagic();">
            <?php foreach($arrnames as $name){ ?>
            <option value="<?=$name;?>"><?=$name;?></option>
            <?php } ?>
    </select>
        <a href="#tabs-<?php echo $key; ?>" id="b_show_activity1"><?=$lang['select_show'];?></a>
		<a href="#tabs-<?php echo $key; ?>" id="b_show_activity2">Открыть в новой вкладке</a>
    </form>
    <div id="player_result1"></div>
</div>
<?php unset ($arrnames);?>