<?
$start_time = microtime(true);

$host=$_SERVER['HTTP_HOST'];
if(isset($_SERVER['HTTP_X_REAL_IP'])) $ip=$_SERVER['HTTP_X_REAL_IP']; else $ip=$_SERVER['REMOTE_ADDR'];

$y=date("Y"); $m=date("n"); $d=date("d");

$mysql=mysql_connect('localhost','chan','pass');


#Список модераторов
$list_moderators = array(
	"Admin" => "hash"
);



if(!$mysql) {

  $title="TopSape.ru: Ошибка MySql";
  header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Status: 503 Service Temporarily Unavailable');
header('Retry-After: 60');



$str.='
<td valign=top>
<div style="margin-top: 10; margin-left: 10; border-left: 1px gray double; padding-left: 20px; width: 100%; ">

<h2>База данных упала</h2>
<br>
<img src="http://topsape.ru/template/bear.jpg">
<br><br><br>
<div style="width: 100%;">&nbsp;</div>
';

$str.='<td valign=top width=200>&nbsp;

';

  include "template/template.php";
  echo $template;

  die();
}
mysql_select_db('chan');




function template($page,$q="",$dop=""){
global $host,$ads,$ads_link,$ads_link2,$_tads_cost,$ads_banner,$start_time,$_tads_dollar;
//Соединяет данные и шаблон
/*
1) Проверяет, есть ли страница в сорцах
2) Проверяет, есть ли шаблон
3) Инклудит страницу
4) Соединяет её с шаблоном
5) Выводит полученные данные
*/
$str="";
if(file_exists("source/$page.php")) include("source/$page.php"); else include("source/error.php");

    include "template/template.php";
 // }
return $template;

}

function source($page,$q="",$dop=""){
global $host,$ads,$ads_link,$ads_link2,$_tads_cost,$_tads_dollar;
include "source/$page.php";
}

function show($page,$q="",$dop="") {//Показывает страницу из кеша, или генерирует новую
/*
1) Проверяем давность кеша и его наличие
2) Если новый - показываем кешированую страницу
3) Если старый - кеширует заново
4) Пункт второй
*/
if($page!="reader") {mysql_query("set names 'cp1251'");}

echo template($page,$q,$dop);

}

function utf8win1251($str){
str_replace("'","&#39;",$str);
$out=iconv("UTF-8","Windows-1251//IGNORE",$str);
return $out;
}



function declOfNum($number, $titles)
{
    $cases = array (2, 0, 1, 1, 1, 2);
    return $number." ".$titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ];
}


function russian_date() {
   $translation = array(
      "am" => "дп",
      "pm" => "пп",
      "AM" => "ДП",
      "PM" => "ПП",
      "Monday" => "Понедельник",
      "Mon" => "Пн",
      "Tuesday" => "Вторник",
      "Tue" => "Вт",
      "Wednesday" => "Среда",
      "Wed" => "Ср",
      "Thursday" => "Четверг",
      "Thu" => "Чт",
      "Friday" => "Пятница",
      "Fri" => "Пт",
      "Saturday" => "Суббота",
      "Sat" => "Сб",
      "Sunday" => "Воскресенье",
      "Sun" => "Вс",
      "January" => "Января",
      "Jan" => "Янв",
      "February" => "Февраля",
      "Feb" => "Фев",
      "March" => "Марта",
      "Mar" => "Мар",
      "April" => "Апреля",
      "Apr" => "Апр",
      "May" => "Мая",
      "May" => "Мая",
      "June" => "Июня",
      "Jun" => "Июн",
      "July" => "Июля",
      "Jul" => "Июл",
      "August" => "Августа",
      "Aug" => "Авг",
      "September" => "Сентября",
      "Sep" => "Сен",
      "October" => "Октября",
      "Oct" => "Окт",
      "November" => "Ноября",
      "Nov" => "Ноя",
      "December" => "Декабря",
      "Dec" => "Дек",
      "st" => "ое",
      "nd" => "ое",
      "rd" => "е",
      "th" => "ое",
      );
   if (func_num_args() > 1) {
      $timestamp = func_get_arg(1);
      return strtr(date(func_get_arg(0), $timestamp), $translation);
   } else {
      return strtr(date(func_get_arg(0)), $translation);
   };
}




?>