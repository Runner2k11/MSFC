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
    * @copyright   2011-2013 Edd - Aleksandr Ustinov
    * @link        http://wot-news.com
    * @package     Clan Stat
    * @version     $Rev: 3.0.2 $
    *
    */


    if(isset($message)){
        echo $message;
    }
?>
<!--LiveInternet counter--><script type="text/javascript"><!--
new Image().src = "//counter.yadro.ru/hit?r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random();//--></script><!--/LiveInternet-->
<div id="allcontainer" style="min-height: 100%; width:100%; padding: 0; margin: 0; border: 0px inset black !important;"
    class="ui-accordion-content ui-widget-content ui-accordion-content-active">
    <?php if(count($multiclan) > 1){ ?>
        <div style="padding-left:26px; padding-right:26px" class="ui-accordion-content ui-widget-content ui-corner-top ui-accordion-content-active">
            <table style="width: 100%;"><tbody><tr valign="center">
            <td>
            <?php
                foreach($multiclan as $val){
                if (substr($multiclan_info[$config['clan']]['data'][$config['clan']]['abbreviation'],0,1) == substr($multiclan_info[$val['id']]['data'][$val['id']]['abbreviation'],0,1)){
                ?>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $("#pane").hover(function() {
                            $("#pane").addClass('ui-state-focus');
                            }, function() {
                                $("#pane").removeClass('ui-state-focus');
                        });
                        $("#<?=$val['prefix'].'1';?>").button();
                        <?php if(isset($_GET['multi'])){ ?>
                            $("#<?=$_GET['multi'].'_1'?>").addClass('ui-state-focus');
                            <?php }else{ ?>
                            $("#<?=$multiclan_main['prefix'].'1'?>").addClass('ui-state-focus');
                            <?php } ?>
                    });
                </script>
                <?php
                    $multi_get = '';
                    if($val['main'] != 1){
                        $multi_get = '?multi='.str_replace('_','',$val['prefix']);
                    }
                ?>                                                             
                <a style="margin: 0 5px;" id="<?=$val['prefix'].'1';?>" href="./index.php<?=$multi_get?>">
                    <img height="24" border="0" src="<?=$multiclan_info[$val['id']]['data'][$val['id']]['emblems']['small'];?>" /><span style="margin: auto 4px; display:block; color:<?=$multiclan_info[$val['id']]['data'][$val['id']]['clan_color']?>"><?=$multiclan_info[$val['id']]['data'][$val['id']]['abbreviation']?></span>
                </a>
                <?php
                }
                }
            ?>
            </td>
            <td align="right" width="300px">
               <img src="./images/logo_small.png" />
            </td>
            </tr></tbody></table>
        </div>
        <?php } ?>
    <table style="width: 100%;" cellpadding="4" cellspacing="0" class="ui-widget-content">
        <tbody>
            <tr style="height: 100px;" valign="center">
                <td id="pane" width="16px" class="ui-state-default" onclick="magic2(this)" rowspan="2" style="background-image: none;">
                    <div id="chan" style="background-origin: content-box; padding: 0; margin: 0; " class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-w">
                        &nbsp;
                    </div>
                </td>
                <td width="222px" align="center" id="tohide"><?=$lang['total_p']; ?>: <?= $multiclan_info[$config['clan']]['data'][$config['clan']]['members_count']; ?></td>
                <td align="center">
                    <a href="http://<?=$config['gm_url']; ?>/community/clans/<?=$config['clan']?>" target="_blank">
                    <?php $multiclan_info[$config['clan']]['data'][$config['clan']]['description_html'] = str_replace("'", '"', $multiclan_info[$config['clan']]['data'][$config['clan']]['description_html']); ?>
                    <img class="bb" src="<?=$multiclan_info[$config['clan']]['data'][$config['clan']]['emblems']['large'];?>"
                    style="padding: 0; margin: 0; height:64px; width:64px;border-width:0;" title='<?=$multiclan_info[$config["clan"]]["data"][$config["clan"]]["description_html"];?>' /></a>
                    <br>
                    <font color="<?=$multiclan_info[$config['clan']]['data'][$config['clan']]['clan_color'];?>">
                        <br><?=$multiclan_info[$config['clan']]['data'][$config['clan']]['name'];?>
                    </font>
                </td>

                <?php include_once(ROOT_DIR.'/views/messages.php'); ?>
                <td width="420px"><?php if($config['lang'] == 'ru' && $config['news'] == '1'){ ?>
                        <iframe src="./news.php<?=$multi_url;?>" frameborder="0" scrolling="no" width="100%" align="middle" height="50px"></iframe>
                    <?php } ?></td>
                <td align="right" width="180px">
                    <?php if($logged > 0){ ?>
                        <table cellpadding="4" cellspacing="0" class="ui-widget ui-widget-content">
                            <tbody>
                                <tr>   
                                    <td><strong><?=$lang['hi'];?> <?=$_COOKIE['user'];?></strong></td>
                                </tr>
                                <tr>
                                    <td>
                                        <? if($logged == 2) { ?>
                                            <a href="./admin/index.php<?=$multi_url;?>" target="_blank"><?=$lang['gotoadmin'];?></a>&nbsp;&nbsp;&nbsp;
                                        <? } ?>
                                        <a href="./main.php?logout=true<?=$multi_url_param;?>"><?=$lang['logout'];?></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php }else{ ?>
                        <div id="login_dialog" class="ui-dialog hidden" style="border: 1px solid; border-collapse: collapse;">
                            <div><?php include(ROOT_DIR.'/login.php'); ?></div>
                        </div>
                        <div class="ui-widget">
                            <a href="#" id="login_opener"><?=$lang['login'];?></a>
                        </div>
                        <?php  } ?>
                </td>
            </tr>
            <tr>
                <td valign="top" id="tohide2">
                    <ul id="menu" class="tabsmenu ui-corner-all">
                        <?php $i=0;
                              $nickname = 0;
                              if (isset($_GET['nickname']) and in_array($_GET['nickname'],array_keys($res))){ $nickname = $_GET['nickname']; ?>
                              <li class="ui-corner-all" value="-6533"><a id="id--6533"  onclick="magic(this)" href="#tabs-player">Статистика: <?php echo $nickname; ?></a></li>
                              <?php }
                            foreach($tabs as $key => $val){
                                foreach(array_keys($val) as $link){
                                    if(is_numeric($key)){ ?>
                                    <li class="ui-corner-all"  value="<?=$key;?>"><a id="id-<?=$key;?>" onclick="magic(this)" href="#tabs-<?=$key; ?>"><?=$link; ?></a></li>
                                    <?php }else{  ?>
                                    <li class="ui-corner-all"  value="<?=$key;?>"><a id="id-<?=$key;?>" onclick="magic(this)" href="<?php echo $key; if(substr_count($key, '?') > 0){echo $multi_url_param;}else{echo $multi_url;}?>"><?=$link; ?></a></li>
                                    <?php  }
                                    $i++;
                                }  
                        } ?>
                    </ul>
                </td>
                <td valign="top" colspan="5">
                    <div>
                        <?php if ($nickname){
                                  ?>
                                  <div id="tabs-player">
                                    <?php include_once(ROOT_DIR.'/ajax/ajax_player.php'); ?>
                                  </div>
                                    <?php }
                            foreach($tabs as $key => $val){
                                foreach($val as $file){    
                                    if(is_numeric($key)){ 
                                        if(!is_array($file)){?>
                                        <div id="tabs-<?=$key; ?>">
                                            <a href="#tabs-<?=$key; ?>"></a>
                                            <?php include(ROOT_DIR.'/tabs/'.$file); ?>
                                        </div>
                                        <?php  }else{ ?>
                                        <div id="tabs-<?=$key; ?>">
                                            <?php include(ROOT_DIR.'/login.php'); ?>
                                        </div>
                                        <?php }
                                    }
                                }
                        } ?>
                    </div>
                </td>
            </tr>
            <?php
                if( function_exists('memory_get_usage') ) {
                    $mem_usage = memory_get_peak_usage(true);
                    if ($mem_usage < 1024)
                        $memory_usage = $mem_usage." bytes";
                    elseif ($mem_usage < 1048576)
                        $memory_usage = round($mem_usage/1024,2)." кб";
                    else
                        $memory_usage = round($mem_usage/1048576,2)." ".$lang['mb'];
                }   
            ?>
            <tr>
                <td valign="top" colspan="6">
                    <div align="center" class="ui-widget ui-widget-content ui-corner-bottom">
                        © 2011-<?=date('Y') ?> <a href="http://wot-news.com/" target="_blank">Wot-news.com</a> <?=$lang['version']; ?> <?php echo VER; ?><br>
                        <?php $end_time = microtime(true); echo $lang['ex_time'],' - ',round($end_time - $begin_time,4),' ',$lang['sec']; ?><br>
                        <?php if(isset($memory_usage)){echo $lang['memory'],' ',$memory_usage;} ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
<center><br>
    &nbsp;<img width='88' height='31' border='0' alt='uptime' src="http://ext.host-tracker.com/uptime-img/?s=31&amp;t=12120310&amp;m=00.09&amp;p=Total&amp;src=lvuc" />&nbsp;
    <!--LiveInternet logo--><a href="http://www.liveinternet.ru/click"
    target="_blank"><img src="//counter.yadro.ru/logo?22.6"
    title="LiveInternet: показано число просмотров за 24 часа, посетителей за 24 часа и за сегодня"
    alt="" border="0" width="88" height="31"/></a><!--/LiveInternet-->
</center>
</div>