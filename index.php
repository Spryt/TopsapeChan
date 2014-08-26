<?
include "func.php";
@$act=mysql_real_escape_string($_GET['act']);
if(strlen($act)<1) $act="main";
@$q=mysql_real_escape_string($_GET['q']);

show($act,$q);
?>