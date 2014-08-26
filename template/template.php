<?

$uri=$_SERVER['REQUEST_URI'];
$urb=base64_encode($uri);

if(isset($_COOKIE['ReaderLastViewd'])) $lastviewd=intval(mysql_real_escape_string($_COOKIE['ReaderLastViewd']));
if(isset($_COOKIE['ChanLastTred'])) $chan=intval(mysql_real_escape_string($_COOKIE['ChanLastTred']));

$newpost="";
if(isset($lastviewd)) {
	$row=mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM posts2 WHERE status<>1 AND pubdate>$lastviewd"));
	$count=$row['COUNT(id)'];
	if($count>1000) $count=0;
	if($count>0) {$newpost=" <span style=\"color: green; font-size: 10pt;\">+$count</span>";}
}

$newtred="";
if(isset($chan)) {
	$row=mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM chan WHERE id>$chan AND status<>1 "));
	$count2=$row['COUNT(id)'];
	if($count2>500) $count2=0;
	if($count2>0) {$newtred=" <span style=\"color: green; font-size: 10pt;\">+$count2</span>";}
}




$template='<!DOCTYPE html>
<html>
<head>
<title>'.$title.'</title>

<meta http-equiv="Content-Type" content="text/html; charset=Windows-1251" />
<link rel="stylesheet" href="http://'.$host.'/template/style.css?v=2" type="text/css" media="screen" />';
if($page=="reader") $template.='<link rel="alternate" type="application/rss+xml" title="RSS" href="http://'.$host.'/reader/rss/" />';

$template.="<script type=\"text/javascript\">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-11055654-4']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>";

$template.='<link href="http://'.$host.'/template/favicon.png" rel="shortcut icon" />


</head>
';


//if(substr($uri,0,5)=="/chan") 
if($uri=="/chan" || $uri=="/chan/") $template.="<body onload=\"update_new()\">\n";
else $template.="<body>\n";


$template.='
<table width=100% border=0 cellspacing="0" cellpadding="0" styl="margin: 0; padding: 0;">
<tr><td valign=top align=left>
<div align=left style="margin: 10px; margin-top: 10px;"><a href="http://'.$host.'/"><img src="http://'.$host.'/template/logo.gif" border=0></a><br>
<ul id=menu>';
$template.='
<li>&bull;&bull; <a href="http://'.$host.'/chan/" title="Анонимная борда сео-тематики">Имиджборд</a> '.$newtred.'

<li>&nbsp;
</ul>

<ul id=menu>
<li>&nbsp;

</ul>
</div>
';



$template.=$str;


$template.='
<tr><td align=center colspan=3 style="padding: 0; margin: 0; ">
<style>
#footer_new { width: 100%; border-top: 1px #ccc solid; background-color: #f9f9f9; margin: 0px; padding: 0;}
#footer_new table, #footer_new td  {padding: 0; margin: 0;}
#footer_new a, #footer_new li {font-size: 10pt;}
#footer_new a{color: #444; text-decoration: none;}
#footer_new a:hover {color: black; text-decoration: underline;}
#footer_new li {list-style-type: none; padding: 3px;}
#footer_new ul {padding-top: 5px; margin-top: 0;}
</style>
<div id="footer_new">
<div style="float: right;">
<img src="http://'.$host.'/template/trigun_small.jpg" width=230 height=155>
</div>
<table border=0  width="50%" cellspacing="0" cellpadding="0">
<tr>
<td valign=top>


<td valign=top>


<td valign=bottom>
<ul>
	
	
	
</ul>

<td valign=bottom>



</table>
</div>


</table>
</body>
</html>';
?>