<?
$title="TopSape.ru: Ошибка";
header("HTTP/1.1 404 Not Found");

$ref=$_SERVER['HTTP_REFERER'];
$uri=$_SERVER['REQUEST_URI'];
$ip=$_SERVER['REMOTE_ADDR'];
$agent=$_SERVER['HTTP_USER_AGENT'];

//mysql_query("INSERT INTO error_log (request_uri,ip,user_agent,referer) VALUES ('$uri','$ip','$agent','$ref')");

$str.='
<td valign=top>
<div style="margin-top: 10; margin-left: 10; border-left: 1px gray double; padding-left: 20px; ">

<h2>404 - Ничего не найдено</h2>
<br>
<img src="http://topsape.ru/template/monkey_not_found.jpg">
<br><br><br>';
/*if($ads==1) {$str.="<div align=left>{$ads_link2}</div><br>";}*/
$str.='<td valign=top width=200>&nbsp;

';
?>

