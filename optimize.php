<?php

    if (file_exists(dirname(__FILE__).'/function/mysql.php')) {
        define('ROOT_DIR', dirname(__FILE__));
    }else{
        define('ROOT_DIR', '.');
    }

    include_once('function/mysql.php');

$db_host = $dbhost; 
$db_user = $dbuser; 
$db_pass = $dbpass;
$db_name = $dbname;

//set_time_limit(0);

mysql_connect($db_host, $db_user, $db_pass) or die(mysql_error() . "\n\n");
mysql_select_db($db_name) or die(mysql_error() . "\n\n");

$r = mysql_query("SHOW TABLES");

$q = "LOCK TABLES";

while($row = mysql_fetch_row($r))
{
  $table[] = $row[0];
  $q .= " " . $row[0]." WRITE,";
}
$q = substr($q,0,strlen($q)-1);
mysql_query($q);

print "База данных заблокированна для чтения/записи.<br>\n";

foreach($table as $value)
{
  $pos = strpos($value, '_col_');
  if ($pos > 0) {
        if (preg_match_all("/_col_tank_|_col_rating_tank_/", $value, $matches)) {
            $result = mysql_query("SHOW COLUMNS FROM `".$value."`");
            $i = 0;
            while($col = mysql_fetch_row($result)){
               $i++;
               if (preg_match_all("/([\d]{1,})(_w|_t|_sp|_sb|_fr)($)/", $col[0], $matches)) {
                 if ($col[1] != 'smallint(5) unsigned') {
                   $q = "ALTER TABLE ".$value." MODIFY ".$col[0]." SMALLINT UNSIGNED NOT NULL";
                   print $q."<br>"; flush();
                   mysql_query($q) or die("QUERY: \"$q\" " . mysql_error() . "\n\n");
                 }
               }
            }
        }
        $q = "ALTER TABLE ".$value." ROW_FORMAT=dynamic";
        print $q."<br>"; flush();
        mysql_query($q) or die("QUERY: \"$q\" " . mysql_error() . "\n\n");
  }
  $q = "OPTIMIZE TABLE ".$value;
  print $q."<br>"; flush();
  mysql_query($q) or die("QUERY: \"$q\" " . mysql_error() . "\n\n");
}
mysql_query("UNLOCK TABLES");
print "База данных разблокированна.<br>\n";
?>


