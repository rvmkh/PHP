<?php

include ("includes/html_header.php");
include ("includes/libs.php");

$hpv = $HTTP_POST_VARS;
$hgv = $HTTP_GET_VARS;
$pself = $_SERVER["PHP_SELF"];

$link = pg_pconnect ("host=192.168.52.7 user=xxx password=xxx dbname=hw_passport") or die ("<p class=err>Could not connect</p>");

if (!isset($hgv["br"]) && !isset($hgv["tr"]) && !isset($hgv["sn"]) && !isset($hgv["a"])) {
	print_urls($pself);
	print_tr($pself);
} else if (isset($hgv["br"]) && isset($hgv["tr"]) && !isset($hgv["sn"])) {
	$br=$hgv["br"];
	$tr=$hgv["tr"];
	print_br ($br,$tr,$link,$pself);
} else if (isset($hgv["br_s"])&&isset($hgv["tr_s"])&&isset($hgv["sn"])&& isset($hgv["sr_n"])&& isset($hgv["a_pos"])){
	$tr_s=$hgv["tr_s"];
	$br_s=$hgv["br_s"];
	$sr=$hgv["sn"];
	$sr_n=$hgv["sr_n"];
	$a_pos=$hgv["a_pos"];
	print_sr($link,$sr,$sr_n,$a_pos,$pself,$hpv,$tr_s,$br_s);
} else if (isset($hgv["a"])){
	if ($hgv["a"]=="epu"){ print_epu(); }
	else if ($hgv["a"]=="title"){ print_title(); }
	else if ($hgv["a"]=="cross"){ print_cross(); }
	else if ($hgv["a"]=="chng_lst"){ print_change_log($link); }
	else if ($hgv["a"]=="look"){ looking_for_board($link,$hpv); }
}
#$c_date=date("Y-m-d");
#echo "<p>".$c_date;

include ("includes/html_footer.php");
?>
