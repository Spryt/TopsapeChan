<?
include "../func.php";

if(isset($_GET['since'])) {
	$since=intval($_GET['since']/1000);

	$count=mysql_num_rows(mysql_query("SELECT id FROM chan WHERE pubdate>$since AND status=0"));

	echo $count;
}

elseif(isset($_GET['since_id'])) {
	$since_id=intval($_GET['since_id']);

	$row=mysql_fetch_array(mysql_query("SELECT MAX(id) FROM chan"));
	$lastid=$row['MAX(id)'];

	$count=$lastid-$since_id;

	echo $count;
}
?>
