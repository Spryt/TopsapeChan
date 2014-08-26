<?
include "image.php";
if(isset($_SERVER['HTTP_X_REAL_IP'])) $ip=$_SERVER['HTTP_X_REAL_IP']; else $ip=$_SERVER['REMOTE_ADDR'];
$ref=$_SERVER['HTTP_REFERER'];
$agent=$_SERVER['HTTP_USER_AGENT'];
$str="";
$title="TopSape Chan";

function wakaba($text){
	$text=stripslashes($text);

	$text=str_replace("&amp;","&",$text);
	$text=str_replace("&#","^^^",$text);

	$text = preg_replace('|(*ANY)#(\d*)|',' <a id="showtred" class="$1" style="cursor: pointer; color: blue;">#$1</a>',$text);

	$text=str_replace("^^^","&#",$text);

	$text = preg_replace('/^(\/[^\>](.*))\n/m', '<span class=cit>\\1</span>', $text."\n");

	$text= preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a rel=nofollow href=\"$3\" >$3</a>", $text);
	$text= preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a rel=nofollow href=\"http://$3\" >$3</a>", $text);

	return nl2br($text);
}



function psot($n,$string=array('пост','поста','постов'))
{
    $n = abs($n) % 100;
    $n1 = $n % 10;
    if ($n > 10 && $n < 20) return $string[2];
    if ($n1 > 1 && $n1 < 5) return $string[1];
    if ($n1 == 1) return $string[0];
    return $string[2];
}

function password($number){
	$arr = array('a','b','c','d','e','f', 'g','h','i','j','k','l','m','n','o','p','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','R','S','T','U','V','X','Y','Z','1','2','3','4','5','6','7','8','9','0');
	$pass = "";
	for($i = 0; $i < $number; $i++)
	{
	$index = rand(0, count($arr) - 1);
	$pass .= $arr[$index];
	}
	return $pass;
}

function get_forma($id,$redir){
	$ftitle=password(8); $comment=password(8);  $str="";
	$encode=base64_encode(base64_encode("{$ftitle}|{$comment}"));

	if($id==0) {
		$str.='<div id=forma style="display: none;">
		<form method=post enctype="multipart/form-data">
		<table id=tableform border=0 class=chan_block>
		<tr><td colspan=2>Тема: <input type=text size="50" name="'.$ftitle.'" maxlength="50">'; 
	}
	else {
		$str.='<div style="clear: both;"></div><div> <a class=jslink onclick="$(\'#form'.$id.'\').slideToggle(\'fast\');">ответить</a> &darr;</div>
		<div id=form'.$id.' style="display: none;">
		<form method=post enctype="multipart/form-data">
		<table id=tableform border=0>';
	}

	$str.='<tr><td colspan=2>Комментарий: <strike>HTML</strike><textarea id="textarea_form_'.$id.'" name="'.$comment.'" rows=10 cols=60 class=forin></textarea>
	<tr><td colspan=2>Изображение (не обязательно): <span style="color: #666; font-size: 10pt;">Не более 2Mb, только картинки</span> 
	<br><input type="file" name="file" size="10">';
	if($id!==0) $str.='<input type="checkbox" name="sage" value="1" id="sage'.$id.'" title="Не поднимать тред"> <label for="sage'.$id.'" title="Не поднимать тред">Sage (без пикчи)</label>';
	$str.='
	<input type=hidden name=parent value='.$id.'><input type=hidden name=redirect value='.$redir.'>
	<input type=hidden name=eggs2 value="'.$encode.'">
	<tr><td colspan=2><input type=submit Value="Добавить" name=submit class=forin>
	</table>
	</form>
	</div>';

	return $str;
}


function show_tred($row) {
	global $host,$list_moderators;
	$chan="";

	$comment=wakaba($row['comment']); 
	$pubdate=russian_date("j F Y, H:i",$row['pubdate']);
	$id=$row['id']; 
	$pic=$row['pic'];
	$resize=$row['res']; 
	$size=$row['size'];  
	$ip1=$row['ip']; 
	$coo=$row['cookie'];
	$parent=$row['parent'];
	$sage=$row['sage'];

	if($parent==0) {
		$ftitle=$row['title']; 
		if(strlen($ftitle)<1) $ftitle="Без темы";
		$ftitle=stripslashes($ftitle);

		$chan.="\n\n<div class=backf4>";
		$chan.="<b><big>{$ftitle}</big></b> <i style=\"color: #333;\">$pubdate, <a href=\"http://$host/chan/$id/\">#$id</a></i>";
		if($id==49109) $chan.=" <span style='color: brown;'> # tabibito-san #</span>";
		$chan.=" <input type='checkbox' id='hidetred$id' name='hidetred' onclick='hidetred($id)'><label for='hidetred$id' style='color: #666;' title='Скрыть тред'>Скрыть тред</label>
		<div id=\"tred{$id}\">";
	} 
	else {
		$chan.="\n\n<div id=\"tred{$id}\" class=backfff>";
		if($sage==1) $chan.="<strong>sage</strong> ";
		$chan.="<i class=gray_min>$pubdate, <a class=chanlink onclick='chanlink($parent,$id)'>#$id</a></i>";
		if($id==47528 || $id==47529) $chan.=" <span style='color: purple; font-size: 10pt;'> # Abu #</span>";
		if($id==47536) $chan.=" <span style='color: darkgreen; font-size: 10pt;'> # God #</span>";
		if($id>=59624 && $coo=="wmfler") $chan.=" <span style='color: darkred; font-size: 10pt;'> # Артур #</span>";

	}

	if(isset($_COOKIE['login'])){

		$login = $_COOKIE['login'];
		$hash = $_COOKIE['hash'];

		if(isset($list_moderators[$login]) && $list_moderators[$login]==$hash) {
			$chan.=" $ip1 <b>$coo</b> <div id=tredstatus$id style=\"display: inline;\">
			<a onclick=\"tred('spam','$id')\" style=\"cursor: pointer; color: red;\">Спам!</a> 
			</div>";
		}
	}

	if(strlen($pic)>0) { 
		//if($parent!=0) $chan.="<br>";
		//$chan.="<span class=gray_min>{$resize}, {$size}кб</span>";
	}

	$chan.="\n\n<div>";

	if(strlen($pic)>0) {
		$chan.="<a href=\"http://$host/files/{$pic}\"><img title=\"{$resize}, {$size}кб\" src=\"http://$host/files/th/{$pic}\"  border=0 style=\"float: left; margin-right: 10px;\"></a>";
	}
	
	$chan.="\n $comment </div>";
	if($parent!=0) $chan.="</div>";
	$chan.='<div style="clear: both;"></div>';

	return $chan;
}


/** Banhammer **/

if(
	//$_SERVER['HTTP_X_REAL_IP']=="127.0.0.1"

	
	isset($_GET['banned']) 
) {
	$str.='<td valign=top>
	<div id="chan_main">

		<h2>Потрачено!</h2>
		<img src="/template/loh.jpg" width=400 height=300> 

		<p>Отдохни пару дней от чана

	</div>';

	include "template/template.php";
  	echo $template;
	die();
}




$q=mysql_real_escape_string($_GET['q']);

$row=mysql_fetch_array(mysql_query("SELECT MAX(id) FROM chan"));
$lastid=$row['MAX(id)'];
setcookie("ChanLastTred", $lastid,time()+2592000,"/",".".$host);


if(!isset($_POST['submit'])) {
	$str.='
	<link rel="stylesheet" href="http://'.$host.'/template/chan.css?v=2" type="text/css" media="screen" />

	<script type="text/javascript">
		var since_id = '.$lastid.';
	</script> 

	<script src="http://'.$host.'/template/jquery-1.10.2.min.js" language="JavaScript" type="text/javascript"></script> 
	<script src="http://'.$host.'/template/jquery.qtip.min.js?v=2"  charset="windows-1251" language="JavaScript" type="text/javascript"></script>
	<script src="http://'.$host.'/template/jquery.cookie.js"  charset="windows-1251" language="JavaScript" type="text/javascript"></script>
	<script src="http://'.$host.'/template/chan.js?v=2"  charset="windows-1251" language="JavaScript" type="text/javascript"></script>


	<td valign=top>
	<div id="chan_main">';

}

$num=0;
if(strlen($q)>0) list($p,$num)=explode("page",$q);

if($num==0) $num++;
$limit=($num-1)*20;


if(!is_numeric($q) && !isset($_POST['submit']) && $q!="stats" && $q!="top") {


	$htime=time()-3600; $hspeed=mysql_num_rows(mysql_query("SELECT id FROM chan WHERE status=0 AND pubdate>$htime"));
	$dtime=time()-78400; $dspeed=mysql_num_rows(mysql_query("SELECT id FROM chan WHERE status=0 AND pubdate>$dtime"));


	$str.= '
	<ul id="bread_menu">
		<li><a href="http://'.$host.'/chan/">Главная</a></li>
		<li><a class=jslink onclick="$(\'#help\').hide();$(\'#forma\').slideToggle(\'fast\');">Новый тред &darr;</a></li>
		<li><a class=jslink onclick="$(\'#forma\').hide();$(\'#help\').slideToggle(\'fast\');">Что это? &darr;</a></li>
		<li><a href="http://'.$host.'/chan/stats/">Статистика</a></li>
		<li class=chanspeed>Скорость борды: '.$hspeed.' '.psot($hspeed).' в час, '.$dspeed.' в сутки</li>
	</ul>
	
	<h2 style="float: right; display: iniline; margin:0; padding: 0;"><a class="bread_tile" href="http://'.$host.'/chan/">SEO/Chan</a></h2>
	<p>';

	
	
	$str.=get_forma(0,0);

	//Свежие треды с сообщениями - наверх
	$str.='<div style="clear: both;"></div>
	<div id=help style="display: none; max-width: 100%; padding: 20px; color: #111;" class=chan_block>
	<h2 style="margin: 0; border-bottom: 1px gray dotted;">Что есть борда?</h2>
	<p><em>&laquo;Имиджборд - разновидность сетевого форума, отличающаяся большими возможностями по прикреплению к сообщениям картинок. Как правило, имиджборды построены по одинаковой схеме и состоят из нескольких тематических разделов, в которых содержатся треды, состоящие из постов от разных пользователей. Пользователи имиджборд, как правило, избавлены от необходимости регистрироваться, и поэтому анонимны, хотя и существуют трипкоды и другие методы идентификации постов одного человека.&raquo; &copy; <a href="http://lurkmore.to/Имиджборд">Lurkmore</a></em>

	<p><strong>TopChan</strong>, в свою очередь - это борда, объединящая манимейкеров, сеошников и прочих вебмастеров, зарабатывающих в интернете. Здесь всего один раздел, нет трипкодов (пока) и кучи другого "нужного" функционала, но тут очень просто и удобно общаться, в отличие от других форумов или вакаба-движков. Тем не менее, это тематичная борда, для трепа за жизнь и по отличным от заработка в интернете - лучше на <a href="http://2ch.hk/">Два.ч</a>
	<p><strong>Правила:</strong>
	<ul>
		<li>Мат разрешен в разумных пределах
		<li>Адалт и шок-контент - запрещен
		<li>Реклама, бессмысленные посты, не несущие смысла оскорбления, повторяющиеся сообщения/треды - все это удаляется модераторами
		<li>Придерживайтесь правил русского языка
		<li>Не плодите тредов, если уже есть подобный (вопросов и ответов например)
	</ul>
	<p><strong>Особенности:</strong>
	<ul>
		<li><span class=cit>/Цитирования оформляется слешем</span>
		<li>Ответ на сообщение - решеткой <a id="showtred" class="47046" style="cursor: pointer; color: blue;">#47046</a>
		<li>В сообщении должна быть хоть одна русская буква (изображение без текста допускается)
	</ul>

	</div>
	<p>
	';

	$str.='<div id="update_status"></div>';

$admin=0;
if(isset($_COOKIE['login'])){
	$login = $_COOKIE['login'];
		$hash = $_COOKIE['hash'];

		if(isset($list_moderators[$login]) && $list_moderators[$login]==$hash) { $admin=1;}
}

if (is_callable('xcache_get') && xcache_isset("chan_main") && $admin==0 && $num==1) { $str.=xcache_get("chan_main"); }
else {
	$chan="";

	$ic=0;
	$res=mysql_query("SELECT * FROM chan WHERE parent=0 AND status=0 ORDER by lastpubdate DESC LIMIT $limit,20"); 
	while($row=mysql_fetch_array($res)) {
		
		$ic++;
		$chan.=show_tred($row);
		
		$id=$row['id']; 

		$countpar=mysql_num_rows(mysql_query("SELECT id FROM chan WHERE parent=$id AND status=0")); if($countpar<=10) $cp=0; else $cp=$countpar-10; 
		if($cp>0) $chan.="\n\n<div style=\"clear: both;\"><a href=\"http://$host/chan/$id/\">Скрыто $cp постов</a></div>\n\n";

		$res2=mysql_query("SELECT * FROM chan WHERE parent=$id AND status=0 ORDER by pubdate ASC LIMIT $cp,10"); 
		while($row2=mysql_fetch_array($res2)) $chan.=show_tred($row2);

		$chan.=get_forma($id,0);
		$chan.='</div></div>';

	}


$chan.="<div style=\"clear: both;\"></div>\n\n";

$count=mysql_num_rows(mysql_query("SELECT * FROM chan"));
$pg=ceil($count/20);
$chan.="<br><div style=\"border-bottom: 1px dotted gray\"></div>";

$next=$num+1; $urlnext="/chan/page$next/";
$pre=$num-1; $urlpre="/chan/page$pre/"; if($pre==1) {$urlpre="/chan/";}
if($num>1){
$chan.='<ul class="next-prev">
			<li>&#8592;&nbsp;<a title="На страницу назад" class="prev" href="'.$urlpre.'" rel="">сюда</a></li>
			<li><a title="На страницу вперед" class="next" href="'.$urlnext.'" rel="">туда</a>&nbsp;&#8594;</li>
	</ul>';
}
else {
$chan.='<ul class="next-prev">
			<li>&#8592;&nbsp;сюда</li>
			<li><a title="На страницу вперед" class="next" href="/chan/page2/" rel="">туда</a>&nbsp;&#8594;</li>
	</ul>';
}

$chan.="<ul id=\"nav-pages\">";
		
if($num>5) $chan.="<li><a href=\"/chan/\" title=\"Первая страница\">&larr;</a></li>";
if($num<=5) {$ot=1; $do=$num+7;}
if($num>5 && $num<$pg-4) {$ot=$num-4; $do=$num+4;}
if($num>=$pg-4) {$ot=$num-4; $do=$num+($pg-$num);}
if($num==$pg) {$ot=$num-4; $do=$num;}
if($ot<0) $ot=1;
if($do>$pg) $do=$pg;

for($i=$ot; $i<=$do; $i++){

if($i==$num) {$chan.="<li><em>$i</em>";} else { if($i==1) {$chan.="<li><a href=\"/chan/\">{$i}</a>";} else {$chan.="<li><a href=\"/chan/page{$i}/\">{$i}</a>"; }}
}

//if($num<$pg-4) $str.="<li><a title=\"Последняя страница\" href=\"http://topsape.ru/reader/{$pg}/\">&rarr;</a></li>";
$chan.="</ul>";

if(is_callable('xcache_get') && $admin==0 && $num==1) xcache_set("chan_main", $chan);
$str.=$chan;
}


}


if(isset($_POST['submit'])){
	$err=0; $pic=0;
	$parent=mysql_real_escape_string(trim($_POST['parent']));

	list($ftitlef,$commentf)=explode("|",base64_decode(base64_decode($_POST['eggs2'])));

	$comment=strip_tags(htmlspecialchars(mysql_real_escape_string(trim($_POST[$commentf])),ENT_COMPAT,"ISO-8859-1")); 

	$ftitle=strip_tags(mysql_real_escape_string(trim($_POST[$ftitlef])));
	$parent=mysql_real_escape_string(trim($_POST['parent'])); $t=time();
	$redirect=mysql_real_escape_string(trim($_POST['redirect']));
	$sage=intval(mysql_real_escape_string($_POST['sage']));
	$ftitle=substr($ftitle,0,50);
	$blacklist = array(".php", ".phtml", ".php5", ".php4"); $err_ext=0;
	foreach ($blacklist as $item) {if(preg_match("/$item\$/i", $_FILES['file']['name'])) {$err_ext=1;}  }

	if(strlen($_FILES['file']['name'])>0 && $err_ext==0){
		$infos = GetImageSize($_FILES['file']['tmp_name']);
		if(!empty($infos)){$pic=1;}
	}

	//$comment = preg_replace("#(https?|ftp)://\S+[^\s.,>)\];'\&quot;!?]#", '<a href="\\0">\\0</a>', $comment);
	preg_match_all('|<a(.*)>(.*)<\/a>|U',$comment,$find);
	if(count($find['1'])>=7) $err=1;

	if(!preg_match("/[а-яА-Я]+/",$comment) && $pic==0) $err=1; 

	$substr_count = substr_count($comment,"[url=");
	if($substr_count>=2) $err=1;

	if($pic==0 && strlen($comment)<1 && $parent!=0) $err=1;
	if($pic==0 && strlen($comment)<1 && strlen($ftitle)<1 && $parent==0) $err=1;
	if(strlen($comment)>20000) $err=1;

	if (isset($_COOKIE['login']) && strlen($_COOKIE['login'])>1) $cookie=$_COOKIE['login']; else $cookie="";

	if($err==0)
	{
		mysql_query("INSERT INTO chan (title,comment,parent,pubdate,lastpubdate,ip,ref,agent,cookie) VALUES ('$ftitle','$comment','$parent','$t','$t','$ip','$ref','$agent','$cookie')");
		$lastid=mysql_insert_id();
		if(is_callable('xcache_get') ) xcache_unset("chan_main");

		$uploaddir = './files/';


		if($pic==1) {
			$uploadfile = $uploaddir.basename($_FILES['file']['name']);
			copy($_FILES['file']['tmp_name'], $uploadfile);
			$info2 = GetImageSize($_FILES['file']['tmp_name']);
			$width2 = $info2[0]; $height2 = $info2[1];

			$image = new Resize_Image;
			if($width2>200 || $height2>200) {
				$image->new_width = 200;
				$image->new_height = 200;
			} else {
				$image->new_width = $width2;
				$image->new_height = $height2;
			}

			$image->image_to_resize = $uploadfile;
			$image->ratio = true;
			$image->new_image_name = $lastid;
			$image->save_folder = './files/th/';

			
			$process = $image->resize();
			

			list($filename,$old,$fileext)=explode(".",$process['new_file_path']);
			$fileim="{$lastid}.{$fileext}";
			rename($uploadfile, "./files/{$fileim}");
			$info = GetImageSize("./files/{$fileim}");
			$width = $info[0]; $height = $info[1];
			$res="{$width}x{$height}";
			$size=round($_FILES['file']['size']/1024,0);


			mysql_query("UPDATE chan SET pic='$fileim', res='$res', size='$size' WHERE id=$lastid");
		}

		if($pic==1) $sage=0;
		if($parent==0) $sage=0;

		if($sage==1) {
			$old_tred=mysql_fetch_array(mysql_query("SELECT lastpubdate FROM chan WHERE id=$parent"));
			$mid_time=time()-$old_tred['lastpubdate'];

			if($mid_time<604800) mysql_query("UPDATE chan SET sage=1 WHERE id=$lastid");
			else mysql_query("UPDATE chan SET lastpubdate=$t WHERE id=$parent");
		} else {
			mysql_query("UPDATE chan SET lastpubdate=$t WHERE id=$parent");
		}
		//Редирект на страницу, где был отправлен коммент

		if($redirect==0) header("Location: http://$host/chan/#tred{$lastid}"); 
		else header("Location: http://$host/chan/{$redirect}/#tred{$lastid}");
		$str.="Успешно!";
	}
	else {
		$str="<td valign=top>
		<div style=\"margin-top: 10; margin-left: 10; border-left: 1px gray double; padding-left: 20px; border-right: 0px gray double; padding-right:20px; margin-right:-10px; min-width: 500px; max-width: 900px;\">

		<h2>Ошибка при публикации</h2>Если потеряли текст коммента, вот он: 
		<p>
		<textarea cols=80 rows=10>$comment</textarea>
		<p>
		Возможные причины:
		<ul>
		<li>Текст только на английском (привет хрумеру)
		<li>Больше 7-ми ссылок
		<li>Нет текста (прикрепите хотя бы пикчу)
		<li>Более 20к символов текста (сириусли, это чан, а не филиал графоманов)
		</ul>

		<a href='/chan/'>&larr; Вернуться к чану</a>
		</div>";
	}

}


if(is_numeric($q) && !isset($_POST['submit'])){

	$_SESSION['channames']="";

	$res=mysql_query("SELECT * FROM chan WHERE parent=0 AND id='$q' AND status=0"); 
	while($row=mysql_fetch_array($res)) {

		$id=$row['id'];
		mysql_query("UPDATE chan SET views=views+1 WHERE id=$id");

		$ftitle=$row['title']; 

		if(strlen($ftitle)<1) $ftitle="Без темы"; else $title=stripslashes($ftitle)." : TopSape Chan"; 

		$ftitle=stripslashes($ftitle);

		$str.= '
		<h2 style="float: left; margin: 0; padding: 0; display: inline;">'.$ftitle.'</h2>

		<h2 style="float: right; display: iniline; margin:0; padding: 0;"><a class="bread_tile" href="http://'.$host.'/chan/">SEO/Chan</a></h2>
		<div style="clear: both;"></div>
		';

		$str.=show_tred($row);


		$res2=mysql_query("SELECT * FROM chan WHERE parent=$id AND status=0 ORDER by pubdate ASC"); 
		while($row2=mysql_fetch_array($res2)) $str.=show_tred($row2);

	$str.=get_forma($id,$id);
	$str.='</div></div><br>';
	}
}


if($q=="stats" && !isset($_POST['submit'])){

	$title="Статистика &larr; TopSape Chan";
	$str.= '
	<h2 style="float: left; margin: 0; padding: 0; display: inline;">Статистика</h2>

	<h2 style="float: right; display: iniline; margin:0; padding: 0;"><a class="bread_tile" href="http://'.$host.'/chan/">SEO/Chan</a></h2>
	<br><br>';

	$all_stat=mysql_fetch_array(mysql_query("SELECT COUNT(id),SUM(size) FROM chan WHERE status=0"));
	$all_stat_zero=mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM chan WHERE status=1"));

	$all_stat_parent=mysql_fetch_array(mysql_query("SELECT COUNT(id),SUM(views) FROM chan WHERE status=0 AND parent=0"));
	$all_size=floor($all_stat['SUM(size)']/1024);

	$time30=time()-30*86400;
	$mon_stat=mysql_fetch_array(mysql_query("SELECT COUNT(id),SUM(size) FROM chan WHERE status=0 AND pubdate>$time30"));
	$mon_stat_zero=mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM chan WHERE status=1 AND pubdate>$time30"));

	$mon_stat_parent=mysql_fetch_array(mysql_query("SELECT COUNT(id),SUM(views) FROM chan WHERE status=0 AND parent=0 AND pubdate>$time30"));
	$mon_size=floor($mon_stat['SUM(size)']/1024);

	if(isset($_COOKIE['login']) && strlen($_COOKIE['login'])>1) {
		$cookie=$_COOKIE['login'];
		$coo="cookie=\"$cookie\"";
		$who=$cookie;
	}
	else {
		$coo="ip=\"$ip\"";
		$who=$ip;
	}

	/*if(isset($_GET['cookie'])) {
		$cookie=mysql_real_escape_string($_GET['cookie']);
		$coo="cookie=\"$cookie\"";
		$who=$cookie;
	}*/

	$you_stat=mysql_fetch_array(mysql_query("SELECT COUNT(id),SUM(size) FROM chan WHERE status=0 AND $coo"));
	$you_stat_zero=mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM chan WHERE status=1 AND $coo"));

	$you_stat_parent=mysql_fetch_array(mysql_query("SELECT COUNT(id),SUM(views) FROM chan WHERE status=0 AND parent=0 AND $coo"));
	$you_size=floor($you_stat['SUM(size)']/1024);



	$str.="<div style='clear: both;'></div>
	<div style='max-width: 100%; padding: 20px; color: #111;' class=chan_block>
	<h3 style='margin: 0; border-bottom: 1px gray dotted; width: 50%;'>У тебя:</h3>
	<p>
	<ul>
		<li>Сообщений: <strong>{$you_stat['COUNT(id)']}</strong> (удалено: <em>{$you_stat_zero['COUNT(id)']}</em> )
		<li>Тредов: <strong>{$you_stat_parent['COUNT(id)']}</strong> (просмотров: <em>{$you_stat_parent['SUM(views)']}</em>)
		<li>Загружено изображений: <strong>{$you_size} мб</strong>
	</ul>

	(очень приблизительная оценка, <em>$who</em>)
	<p>


	<h3 style='margin: 0; border-bottom: 1px gray dotted; width: 50%;'>За последние 30 дней:</h3>
	<p>
	<ul>
		<li>Сообщений: <strong>{$mon_stat['COUNT(id)']}</strong> (удалено: <em>{$mon_stat_zero['COUNT(id)']}</em> )
		<li>Тредов: <strong>{$mon_stat_parent['COUNT(id)']}</strong> (просмотров: <em>{$mon_stat_parent['SUM(views)']}</em>)
		<li>Загружено изображений: <strong>{$mon_size} мб</strong>
	</ul>
	<p>

	<h3 style='margin: 0; border-bottom: 1px gray dotted; width: 50%;'>За всё время:</h3>
	<p>
	<ul>
		<li>Сообщений: <strong>{$all_stat['COUNT(id)']}</strong> (удалено: <em>{$all_stat_zero['COUNT(id)']}</em> )
		<li>Тредов: <strong>{$all_stat_parent['COUNT(id)']}</strong> (просмотров: <em>{$all_stat_parent['SUM(views)']}</em>)
		<li>Загружено изображений: <strong>{$all_size} мб</strong>
	</ul>

	</div>
	<p><a href='http://$host/chan/'>&larr; Назад</a>
	<div style='min-width: 800px;'></div>
	";




	}


if($q=="top" && !isset($_POST['submit'])){
	$title="Топ тредов &larr; TopSape Chan";
	$str.= '
	<h2 style="float: left; margin: 0; padding: 0; display: inline;">Топ тредов по числу ответов</h2>

	<h2 style="float: right; display: iniline; margin:0; padding: 0;"><a class="bread_tile" href="http://'.$host.'/chan/">SEO/Chan</a></h2>
	<br><br>';

	

	$res1=mysql_query("SELECT parent, count(id) as count FROM `chan` WHERE parent<>0 AND status=0 GROUP BY parent ORDER BY count DESC LIMIT 30"); 
	while($row1=mysql_fetch_array($res1)) {
		$count=$row1['count'];
		$id=$row1['parent'];
		$arr[$id]=$count;
	}

	$id_list = implode(',', array_keys($arr));


    $res = mysql_query("SELECT * FROM `chan` WHERE `id` IN ($id_list) ORDER BY FIELD(`id`, $id_list)");
	while ($row = mysql_fetch_array($res)) {

		$str.=show_tred($row);
		
		$id=$row['id']; 

		$countpar=mysql_num_rows(mysql_query("SELECT id FROM chan WHERE parent=$id AND status=0")); if($countpar<=10) $cp=0; else $cp=$countpar; 
		if($cp>0) $str.="\n\n<div style=\"clear: both;\"><a href=\"http://$host/chan/$id/\">Скрыто $cp постов</a></div>\n\n";

		$str.='</div></div>';
	}


}


$str.='<div style="clear: both;"></div>
<td valign=top width=0>
&nbsp;';
?>