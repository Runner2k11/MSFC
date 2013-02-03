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
    * @version     $Rev: 2.2.0 $
    *
    */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$lang['admin_title']; ?></title>
    <?php if (!isset($config['theme'])) {
        $config['theme'] = 'ui-lightness'; } ?>
    <link rel="stylesheet" href="../theme/<?=$config['theme']; ?>/jquery-ui.css" type="text/css" media="print, projection, screen" />
    <link rel="stylesheet" href="../theme/style.css" type="text/css" media="print, projection, screen" />

    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/jquery.metadata.js"></script>
    <script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="../js/jquery.tablesorter.widgets.js"></script>
    <script type="text/javascript" src="../js/jquery.ui.js"></script>
    <script type="text/javascript" src="../js/jquery.tools.min.js"></script>
    <?php if ($config['lang'] == 'ru') { ?>
        <script type="text/javascript" src="../js/jquery.ui.ru.js"></script>
        <?php }; ?>
    <script type="text/javascript" src="../js/jquery.vticker.js"></script>
    <script type="text/javascript" src="../js/msfc.shared.js"></script>

    <?php if(isset($current_user)){ ?>
      <script type="text/javascript">
          $(document).ready(function()
            {
              <?php foreach($current_user as $val){?>
                  $('#dialog_<?=$val['user']?>').dialog({appendTo: "#adminalltabs", autoOpen: false});
                  $('.trigger_<?=$val['user']?>').click(function(){
                     $('#dialog_<?=$val['user']?>').dialog("open");
                     return false;
                  });
              <?php } ?>
            });
      </script>
    <?php } ?>

    <script type="text/javascript" id="js">
        $(document).ready(function() {
            <?php if(isset($_GET['multi'])){ ?>
                $("#iserver").prop('disabled', true);
                $("#iclan").prop('disabled', true);
                $("#ccontrol").hide();
                $("#dccontrol").hide();
            <?php } ?>

            $("#ad_menu").menu();
            $("#adminalltabs").tabs({
                ajaxOptions: {
                    error: function( xhr, status, index, anchor ) {
                        $( anchor.hash ).html(
                            "<?php echo $lang['error_1'];?>");
                    }
                }
            });
            $('#adminalltabs ul li a').click(function () {window.location.hash = $(this).attr('href');window.scrollTo(0, 0);});

            $('#loadeng').click(function(){
                <?php foreach($tabs_lang['en'] as $key => $val){ ?>
                    $('#<?=$key;?>php').val('<?=$val?>');
                    <?php } ?>
                return false;
            });
            $('#loadrus').click(function(){
                <?php foreach($tabs_lang['ru'] as $key => $val){ ?>
                    $('#<?=$key;?>php').val('<?=$val?>');
                    <?php } ?>
                return false;
            });

            $("#files").tablesorter({
                sortList: [[2, 0]],
                textExtraction: function(node) {
                    return $(node).find("span.hidden").text();
                }
            });
            $("#users").tablesorter({sortList: [[1, 0]]});
            $("#multiclan_table").tablesorter({ sortList: [[1, 0]] });

            <?php if(!empty($adm_top_tanks)){ ?>
                $("#top_tanks").tablesorter({ sortList:[[6,0],[3,0]]});
            <?php } ?>
            <?php if(!empty($tanks_list)){ ?>
                $("#tanks_list").tablesorter({
                    sortList: [[2, 0]],
                    textExtraction: function(node) {
                        return $(node).find("span.hidden").text();
                    }
                });
            <?php } ?>
        });
    </script>

</head>
<body>