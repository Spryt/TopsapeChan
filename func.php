<?
$start_time = microtime(true);

$host=$_SERVER['HTTP_HOST'];
if(isset($_SERVER['HTTP_X_REAL_IP'])) $ip=$_SERVER['HTTP_X_REAL_IP']; else $ip=$_SERVER['REMOTE_ADDR'];

$y=date("Y"); $m=date("n"); $d=date("d");

$mysql=mysql_connect('localhost','chan','pass');


#������ �����������
$list_moderators = array(
	"Admin" => "hash"
);



if(!$mysql) {

  $title="TopSape.ru: ������ MySql";
  header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Status: 503 Service Temporarily Unavailable');
header('Retry-After: 60');



$str.='
<td valign=top>
<div style="margin-top: 10; margin-left: 10; border-left: 1px gray double; padding-left: 20px; width: 100%; ">

<h2>���� ������ �����</h2>
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
//��������� ������ � ������
/*
1) ���������, ���� �� �������� � ������
2) ���������, ���� �� ������
3) �������� ��������
4) ��������� � � ��������
5) ������� ���������� ������
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

function show($page,$q="",$dop="") {//���������� �������� �� ����, ��� ���������� �����
/*
1) ��������� �������� ���� � ��� �������
2) ���� ����� - ���������� ����������� ��������
3) ���� ������ - �������� ������
4) ����� ������
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
      "am" => "��",
      "pm" => "��",
      "AM" => "��",
      "PM" => "��",
      "Monday" => "�����������",
      "Mon" => "��",
      "Tuesday" => "�������",
      "Tue" => "��",
      "Wednesday" => "�����",
      "Wed" => "��",
      "Thursday" => "�������",
      "Thu" => "��",
      "Friday" => "�������",
      "Fri" => "��",
      "Saturday" => "�������",
      "Sat" => "��",
      "Sunday" => "�����������",
      "Sun" => "��",
      "January" => "������",
      "Jan" => "���",
      "February" => "�������",
      "Feb" => "���",
      "March" => "�����",
      "Mar" => "���",
      "April" => "������",
      "Apr" => "���",
      "May" => "���",
      "May" => "���",
      "June" => "����",
      "Jun" => "���",
      "July" => "����",
      "Jul" => "���",
      "August" => "�������",
      "Aug" => "���",
      "September" => "��������",
      "Sep" => "���",
      "October" => "�������",
      "Oct" => "���",
      "November" => "������",
      "Nov" => "���",
      "December" => "�������",
      "Dec" => "���",
      "st" => "��",
      "nd" => "��",
      "rd" => "�",
      "th" => "��",
      );
   if (func_num_args() > 1) {
      $timestamp = func_get_arg(1);
      return strtr(date(func_get_arg(0), $timestamp), $translation);
   } else {
      return strtr(date(func_get_arg(0)), $translation);
   };
}




?>