<?
include "../func.php";
header('Content-Type: text/html; charset=Windows-1251');
mysql_query("set names 'cp1251'");
$id=intval(mysql_real_escape_string($_GET['id']));
$row=mysql_fetch_array(mysql_query("SELECT * FROM chan WHERE id='$id'"));

function wakaba($text){
	$text=stripslashes($text);
	$text=str_replace("&amp;","&",$text);
	$text=str_replace("&#","^^^",$text);

	$text = preg_replace('|(*ANY)#(\d*)|',' <a id="showtred" class="$1" style="cursor: pointer; color: blue;">#$1</a>',$text);

	$text=str_replace("^^^","&#",$text);

	$text = preg_replace('/^(\/[^\>](.*))\n/m', '<span class=cit>\\1</span>', $text);
	$text= preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a rel=nofollow href=\"$3\" >$3</a>", $text);
	$text= preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a rel=nofollow href=\"http://$3\" >$3</a>", $text);

	return nl2br($text);
}

$str="";

$ftitle=$row['title']; $comment=wakaba($row['comment']); $pubdate=russian_date("j F Y, H:i",$row['pubdate']); $id=$row['id']; $pic=$row['pic'];
$resize=$row['res']; $size=$row['size'];  
if(strlen($ftitle)<1) $ftitle="Без темы";
$ftitle=stripslashes($ftitle);

$str.="<div style=\"padding: 5px; padding: 10px;word-wrap: break-word; \"><span style=\"color: #333; \"><i>$pubdate</i>";


if(strlen($pic)>0) $str.="<br><span style=\"color: gray; font-size: 10pt;\">{$resize}, {$size}кб</span>";
$str.="<div style=\"\">";
if(strlen($pic)>0) $str.="<a href=\"http://$host/files/{$pic}\"><img src=\"http://$host/files/th/{$pic}\" border=0 style=\"float: left; margin-right: 10px;\"></a>";
$str.=" $comment</div>";

echo $str;
?>
