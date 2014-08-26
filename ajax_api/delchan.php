<?
include "../func.php";
$type=mysql_real_escape_string($_GET['type']);
$id=intval(mysql_real_escape_string($_GET['id']));

		$login = $_COOKIE['login'];
		$hash = $_COOKIE['hash'];

		if(isset($list_moderators[$login]) && $list_moderators[$login]==$hash) {

		if($type=="spam") {

			$rows=mysql_fetch_array(mysql_query("SELECT parent,pic FROM chan WHERE id='$id'"));
			$parent=$rows['parent'];  $pic=$rows['pic'];
			$coo=mysql_real_escape_string($_COOKIE['login']);

			mysql_query("UPDATE chan SET status=1,who_deleted='$coo' WHERE id='$id'");
			if($parent==0) mysql_query("UPDATE chan SET status=1,who_deleted='$coo' WHERE parent='$id'");

			if($parent>0) {
				$last=mysql_fetch_array(mysql_query("SELECT pubdate FROM chan WHERE parent='$parent' AND status=0 ORDER BY id DESC LIMIT 1"));
				$pubdate=$last['pubdate'];
				mysql_query("UPDATE chan SET lastpubdate=$pubdate WHERE id='$parent'");
				if(strlen($pic)>0) {
					unlink("../files/{$pic}");
					unlink("../files/th/{$pic}");
				}
			}
		}


		if($type=="approve") {
			mysql_query("UPDATE chan SET status=0 WHERE id='$id'");

		}
xcache_unset("chan_main");
} else exit;
?>
