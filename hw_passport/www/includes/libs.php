<?php

print "<p align=right>Logged in as ".$_SERVER["PHP_AUTH_USER"]."<br><p>\n";

function print_urls($pself){
	print "<a href=$pself?a=title>ТИТУЛ<br></a>";
	print "<a href=$pself?a=epu>ЭПУ<br></a>";
	print "<a href=$pself?a=cross>КРОСС<br><br></a>";
	print "<a href=$pself?a=chng_lst>Лист изменений<br></a>";
	print "<a href=$pself?a=look>Поиск платы</a>";
}

function print_tr($pself) {

print "<BR>

<table align=center border=7 cellpadding=5 cellspacing=5 width=32% bgcolor=#B0B0B0 class=menu> 
<caption align=center>Схема размещения оборудования в автозале<br><br>
<tr height=20>
<td width=10% align=center>SHARP</td>
<td width=10% align=center>DAIKIN</td>
</tr>
</table>

<br><br>

<table align=left border=7 cellpadding=5 cellspacing=5 width=10% bgcolor=#B0B0B0 class=menu> 
<tr height=100>
<td width=10% align=center>CKY</td>
</tr>
</table>

<table align=right border=7 cellpadding=5 cellspacing=5 width=80% bgcolor=#B0B0B0 class=menu> 
<tr height=200>
<td width=10% align=center><a href=$pself?tr=1&br=1>TR01-B01</td><td width=1%></td>
<td width=10% align=center><a href=$pself?tr=1&br=2>TR01-B02</td><td width=1%></td>
<td width=10% align=center><a href=$pself?tr=1&br=3>TR01-B03</td><td width=1%></td>
<td width=10% align=center><a href=$pself?tr=1&br=4>TR01-B04</td><td width=1%></td>
<td width=10% align=center><a href=$pself?tr=1&br=5>TR01-B05</td><td width=1%></td>
<td width=10% align=center><a href=$pself?tr=1&br=6>TR01-B06</td><td width=1%></td>
<td width=10% align=center><a href=$pself?tr=1&br=7>TR01-B07</td><td width=1%></td>
<td width=10% align=center><a href=$pself?tr=1&br=8>TR01-B08</td><td width=1%></td>
<td width=10% align=center>Modem Rack 2</td>
</tr>
</table>

<BR><BR><BR><BR><BR><BR><BR>
<BR><BR><BR><BR><BR><BR><BR>

<table align=left border=7 cellpadding=5 cellspacing=5 width=13% bgcolor=#B0B0B0 class=menu>
<tr height=250>
<td width=13% align=center>Stulz</td>
</tr>
</table>

<table align=right border=7 cellpadding=5 cellspacing=5 width=80% bgcolor=#B0B0B0 class=menu> 
<tr height=200>
<td width=10% align=center><a href=$pself?tr=2&br=1>TR02-B01</td><td width=1%></td>
<td width=10% align=center><a href=$pself?tr=2&br=2>TR02-B02</td><td width=1%></td>
<td width=10% align=center><a href=$pself?tr=2&br=3>TR02-B03</td><td width=1%></td>
<td width=10% align=center><a href=$pself?tr=2&br=4>TR02-B04</td><td width=1%></td>
<td width=10% align=center><a href=$pself?tr=2&br=5>TR02-B05</td><td width=1%></td>
<td width=10% align=center><a href=$pself?tr=2&br=6>TR02-B06</td><td width=1%></td>
<td width=10% align=center><a href=$pself?tr=2&br=7>TR02-B07</td><td width=1%></td>
<td width=10% align=center><a href=$pself?tr=2&br=8>TR02-B08</td><td width=1%></td>
<td width=10% align=center>Transmit Rack 1</td>
</tr>
</table>

<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>
<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>

<table align=left border=7 cellpadding=5 cellspacing=5 width=13% bgcolor=#B0B0B0 class=menu>
<tr height=50>
<td width=13% align=center>DDF</td>
</tr>
</table>

<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>

<table align=left border=7 cellpadding=5 cellspacing=16 width=13% bgcolor=#B0B0B0 class=menu>
<tr height=200>
<td width=10% align=center>Modem Rack 1</td>
</tr>
</table>

<table align=right border=7 cellpadding=5 cellspacing=16 width=45% bgcolor=#B0B0B0 class=menu>
<tr height=200>
<td width=9% align=center>Ericsson</td>
<td width=9% align=center>Transmit Rack 3</td>
<td width=9% align=center><a href=$pself?tr=3&br=7>TR03-B07</td>
<td width=9% align=center><a href=$pself?tr=3&br=8>TR03-B08</td>
<td width=9% align=center>Transmit Rack 2</td>
</tr>
</table>

<BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>

<table align=center border=7 cellpadding=5 cellspacing=5 width=15% bgcolor=#B0B0B0 class=menu>
<tr height=50>
<td width=15% align=center>Power Supply<br> ~220V     _48V</td>
</tr>
</table>

";

}

function print_br ($br,$tr,$link,$pself) {

$i=0;
### Rack_open_select
	$qstr = "select r_name,br,tr,r_list.sn,sr_list.sn,sr_name,a_pos,sr_id from r_list,r_name,sr_list,sr_name where br=$br and tr=$tr and r_list.r_n_id=r_name.r_n_id and r_list.r_id=sr_list.r_id and sr_list.sr_n_id=sr_name.sr_n_id order by sr_id;";
###
	$res=pg_query($link,$qstr);
	$sr_qtt = pg_num_rows($res); 

	if ($sr_qtt) {
		$row = pg_fetch_row($res, $i);
		$rack="Имя = ".$row[0]."<br>S/N = ".$row[3];
		echo "Статив TR0$tr-B0$br<br>$rack";

	        print "<BR>
        	<table align=center border=7 cellpadding=5 cellspacing=5 width=40% bgcolor=#B0B0B0 class=menu>\n";
		print "<tr height=140>\n<td width=10%>Полка:<br><a href=$pself?tr_s=$tr&br_s=$br&sn=$row[4]&sr_n=$row[5]&a_pos=$row[6]>$row[6]&nbsp;$row[5]&nbsp;$row[4]</a></td>\n</tr>\n";
		for ($i=1; $i < $sr_qtt; $i++) {
			$row = pg_fetch_row($res, $i);
		print "<tr height=140>\n<td width=10%>Полка:<br><a href=$pself?tr_s=$tr&br_s=$br&sn=$row[4]&sr_n=$row[5]&a_pos=$row[6]>$row[6]&nbsp;$row[5]&nbsp;$row[4]</a></td>\n</tr>\n";
		}
	        print "</table>\n";
	}
	else { echo "Empty or<br>Under construction"; }
}

function print_sr ($link,$sr,$sr_n,$a_pos,$pself,$hpv,$tr_s,$br_s) {

$act = "";
$modify = "";
if (check_if_user_is_valid()==1){
	$ro="";
	$modify="<td width=30% align=left><INPUT TYPE=text NAME=rem SIZE=30 MAXLENGTH=50></td><td width=15% align=center><BUTTON NAME=submit VALUE=modify>Modify</BUTTON></td>";
	$act="<td width=30% align=center><b>Remarks</b></td><td width=15% align=center><b>Action</b></td>";
}else{ $ro="READONLY"; }

### SubRack open select
	$qstr = "select r_pos,name,b_list.sn,descr,sr_list.sr_id,b_list.sr_id,sr_list.sn,b_list.b_id,b_name.b_id from b_list,b_name,sr_list where sr_list.sn='$sr' and sr_list.sr_id=b_list.sr_id and b_list.b_id=b_name.b_id order by r_pos;";
        $res =pg_query($link,$qstr);
        $bd_qtt = pg_num_rows($res);

        if ($bd_qtt) {
                $subrack="Имя = ".$sr_n."<br>S/N = ".$sr;
                echo "Статив TR0$tr_s-B0$br_s<br>Полка = $a_pos<br>$subrack";
                print "<BR><table align=center border=7 cellpadding=1 cellspacing=1 width=50% bgcolor=#B0B0B0 class=menu>\n";
		print "<tr><td width=10% align=center><b>Место</b></td><td width=20% align=center><b>Плата</b></td><td width=25% align=center><b>S/N</b></td>".$act."</tr>";
	        $blst = "SELECT name from b_name order by name;";

                for ($i=0; $i < $bd_qtt; $i++) {
	                $str = pg_fetch_row($res, $i);
        		$res_b = pg_query($link,$blst);
                	print "<form method=post><tr height=10>\n<td width=10% align=center title='$str[3]'>$str[0]</td><td width=20%><SELECT NAME=b_new_name>";
		        while ( $bstr = pg_fetch_row($res_b) ) {
				if ($bstr[0]==$str[1]) {
					print "<option SELECTED >$bstr[0]";
				}
		                print "<option >$bstr[0]";
        		}
			print "</SELECT></td><td width=25% align=left><INPUT TYPE=text NAME=new_sn VALUE=$str[2] ".$ro." SIZE=22 MAXLENGTH=30></td>".$modify;
			print "<input type=hidden name=tr_s value=$tr_s>";
			print "<input type=hidden name=br_s value=$br_s>";
			print "<input type=hidden name=a_pos value=$a_pos>";
			print "<input type=hidden name=pos value='$str[0]'>";
			print "<input type=hidden name=o_name value='$str[1]'>";
			print "<input type=hidden name=o_sn value='$str[2]'>";
			print "\n</tr>\n</form>";
                }
                print "</table>\n";
	}
        else {echo "Empty or ...<br>Under construction";}

	if ( isset($_POST["submit"]) && $_POST["submit"]="modify" && check_if_user_is_valid()==1 ) {

		if( $hpv["new_sn"]=="" ) {
			$hpv["new_sn"]="free";
		}
		$update_sn="update b_list set sn='".$hpv["new_sn"]."' where r_pos='".$hpv["pos"]."' and sr_id=(select sr_id from sr_list where a_pos=".$hpv["a_pos"]." and r_id=(select r_id from r_list where br=".$hpv["br_s"]." and tr=".$hpv["tr_s"]."));";
		$res=pg_query($link, $update_sn);

		$update_name="update b_list set b_id=(select b_id from b_name where name='".$hpv["b_new_name"]."') where sn='".$hpv["new_sn"]."' and r_pos='".$hpv["pos"]."' and sr_id=(select sr_id from sr_list where a_pos=".$hpv["a_pos"]." and r_id=(select r_id from r_list where br=".$hpv["br_s"]." and tr=".$hpv["tr_s"]."));";
		$res=pg_query($link, $update_name);

		if ( $hpv["rem"]=="" ) {
			$hpv["rem"]="no remarks";
		}

		#check if log record already exist do nothing in case page reolad
		$str_log_exist=pg_query($link,"SELECT * from chng_log where c_date='".date("Y-m-d")."' and place='".$hpv["tr_s"]."-".$hpv["br_s"]."-".$hpv["a_pos"]."-".$hpv["pos"]."' and o_name='".$hpv["o_name"]."' and o_sn='".$hpv["o_sn"]."' and n_name='".$hpv["b_new_name"]."' and n_sn='".$hpv["new_sn"]."' and remark='".$hpv["rem"]."';");
		$log_exist_res=pg_fetch_row($str_log_exist);
		if ( $log_exist_res[0]=="" ) {
			$ins="insert into chng_log (c_date,place,o_name,o_sn,n_name,n_sn,remark) values ('".date("Y-m-d")."','".$hpv["tr_s"]."-".$hpv["br_s"]."-".$hpv["a_pos"]."-".$hpv["pos"]."','".$hpv["o_name"]."','".$hpv["o_sn"]."','".$hpv["b_new_name"]."','".$hpv["new_sn"]."','".$hpv["rem"]."');";
			$res=pg_query($link, $ins);
		}
	}
}
#phpinfo();
function check_if_user_is_valid() {

	if (	$_SERVER["PHP_AUTH_USER"] == "visor" ||
		$_SERVER["PHP_AUTH_USER"] == "roma" ||
		$_SERVER["PHP_AUTH_USER"] == "loser"
	) {
		return 1;
	} else {
		return 0;
	}
}

function print_title() {

	echo "<br><br><big><u>Восточный филиал ООО &laquo;ТК &laquo;Велтон.Телеком&raquo;</u></big>";
        print "<br><br><br><table align=right width=30% class=menu>
                <tr><td align=left>Утверждаю</td></tr>
                <tr><td align=left>Директор Восточного филиала</td></tr>
                <tr><td align=left>ООО &laquo;ТК &laquo;Велтон.Телеком&raquo;</td></tr>
                <tr><td align=left>__________ Яременко Г. А.</td></tr>
                <tr><td align=left>____ ________ 200__ г.</td></tr>
                </table><br>";
	echo "<br><br><br><br><br><br><big><b>Паспорт Х00</big></b><br><br>";
        print "<br><br><br><table align=left width=50% class=menu>
		<tr><td align=left ><b>Раздел 1</td></tr>
		<tr><td align=left><u>Общие сведения</u></b></td></tr>
		<tr><td align=left>Адрес : ул. Иванова, 7/9</td></tr>
		<tr><td align=left>Тип оборудования : Alcatel 1000E10</td></tr>
		<tr><td align=left>Год ввода в эксплуатацию: 1994</td></tr>
                <tr><td align=left>Расстояние до Host (км) : 0</td></tr>
                <tr><td align=left>Максимальная емкость :</td></tr>
                <tr><td align=left>&nbsp;- без установки новых стативов :</td></tr>
                <tr><td align=left>&nbsp;- при установке новых стативов :</td></tr>
                <tr><td align=left>Система кондиционирования : Автозал</td></tr>
                <tr><td align=left>Охранная сигнализация :</td></tr>
                <tr><td align=left>Пожарная сигнализация :</td></tr>
		</table><br>
";

}

function print_epu() {

	print "<br><table align=center width=80% class=menu>
		<tr><td align=center>Схема размещения оборудования в ЭПУ</td></tr>
		</table><br>";

	print "<br><table align=right border=7 cellpadding=1 cellspacing=1 width=15% bgcolor=#B0B0B0 class=menu>
		<tr height=20><td align=center>Haier</td></tr>
		</table><br><br>";

	print "<br><br><table align=left border=7 cellpadding=1 cellspacing=1 width=15% bgcolor=#B0B0B0 class=menu>
		<tr height=200><td align=center>E.X.T.600</td></tr>
		<tr height=200><td align=center>S.E.M.600</td></tr>
		</table>";

	print "<br><table align=right border=7 cellpadding=1 cellspacing=1 width=15% bgcolor=#B0B0B0 class=menu>
		<tr height=20><td align=center>Haier</td></tr>
		</table><br><br>";

        print "<br><br><br><br><table align=right border=7 cellpadding=1 cellspacing=1 width=12% bgcolor=#B0B0B0 class=menu>
		<tr height=400><td align=center>AB<br>branch B</td></tr>
		</table>";

	print "<table align=center border=7 cellpadding=1 cellspacing=1 width=30% bgcolor=#B0B0B0 class=menu>
		<tr height=170><td align=center>Modem Rack 1</td><td align=center>Modem Rack 2</td></tr>
		</table>";

        print "<br><br><br><br><br><br><table align=right border=7 cellpadding=1 cellspacing=1 width=40% bgcolor=#B0B0B0 class=menu>
		<tr height=95><td align=center>AB<br>branch A</td></tr>
		</table>";

}

function print_cross() {

	print "<br><table align=left width=55%>";
	print "<tr><td width=100% align=center><b>Линейная сторона</b></td></table><br><br>";
	print "<br><table align=left border=7 cellpadding=5 cellspacing=5 width=56% bgcolor=#B0B0B0 class=menu>";
	print "<tr><td width=100% align=center><b>Охранная Сигнализация</b></td></table><br><br><br><br>";

	for ($i=6;$i>-1;$i--) {
		print "<table align=left border=7 cellpadding=5 cellspacing=5 width=8% bgcolor=#B0B0B0 class=menu>";
		print "<tr><td width=100% align=center><b>14-".$i."1</b></td>";
		print "<tr><td width=100% align=center><b>14-".$i."2</b></td>";
		print "<tr><td width=100% align=center><b>14-".$i."3</td>";
		print "<tr><td width=100% align=center><b>14-".$i."4</b></td>";
		print "<tr><td width=100% align=center><b>14-".$i."5</b></td>";
		print "<tr><td width=100% align=center><b>14-".$i."6</b></td>";
		print "<tr><td width=100% align=center><b>14-".$i."7</b></td>";
		if ($i==6) {
			print "<tr><td width=100% align=center>&nbsp</td>";
			print "<tr><td width=100% align=center>&nbsp</td>";
			print "<tr><td width=100% align=center>&nbsp</td></table>";
		}else{
			$n=$i+1;
			print "<tr><td width=100% align=center><b>14-".$i."8</b></td>";
			print "<tr><td width=100% align=center><b>14-".$i."9</b></td>";
			print "<tr><td width=100% align=center><b>14-".$n."0</b></td></table>";
		}
	}
	print "<br><br><br<br><br><br<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><table align=left width=55%>";
	print "<tr><td width=100% align=center><b>Станционная сторона</b></td></table><br><br>";

	print "<table align=left border=7 cellpadding=5 cellspacing=5 width=8% bgcolor=#B0B0B0 class=menu>";
	print "<tr><td width=100% align=center><b>ADSL2<br>Line</b></td>";
	print "<tr><td width=100% align=center><b>7-3</b></td>";
	print "<tr><td width=100% align=center><b>7-4</td>";
	print "<tr><td width=100% align=center><b>7-5</b></td>";
	print "<tr><td width=100% align=center><b>7-6</b></td>";
	print "<tr><td width=100% align=center><b>7-7</b></td>";
	print "<tr><td width=100% align=center><b>7-8</b></td>";
	print "<tr><td width=100% align=center><b>7-9</b></td>";
	print "<tr><td width=100% align=center><b>7-10</b></td></table>";

	print "<table align=left border=7 cellpadding=5 cellspacing=5 width=8% bgcolor=#B0B0B0 class=menu>";
	print "<tr><td width=100% align=center><b>ADSL2<br>NE</b></td>";
	print "<tr><td width=100% align=center><b>7-11</b></td>";
	print "<tr><td width=100% align=center><b>7-12</td>";
	print "<tr><td width=100% align=center><b>7-13</b></td>";
	print "<tr><td width=100% align=center><b>7-14</b></td>";
	print "<tr><td width=100% align=center><b>7-15</b></td>";
	print "<tr><td width=100% align=center><b>7-16</b></td>";
	print "<tr><td width=100% align=center><b>7-17</b></td>";
	print "<tr><td width=100% align=center><b>PCM-4</b></td></table>";

	print "<table align=left border=7 cellpadding=5 cellspacing=5 width=8% bgcolor=#B0B0B0 class=menu>";
	print "<tr><td width=100% align=center><b>Перем<br>А/Зал</b></td>";
	print "<tr><td width=100% align=center><b>14-0</b></td>";
	print "<tr><td width=100% align=center><b>14-1</td>";
	print "<tr><td width=100% align=center><b>14-2</b></td>";
	print "<tr><td width=100% align=center><b>14-3</b></td>";
	print "<tr><td width=100% align=center><b>14-4</b></td>";
	print "<tr><td width=100% align=center><b>14-5</b></td>";
	print "<tr><td width=100% align=center><b>14-6</b></td>";
	print "<tr><td width=100% align=center><b>14-7</b></td></table>";

	print "<table align=left border=7 cellpadding=5 cellspacing=5 width=8% bgcolor=#B0B0B0 class=menu>";
	print "<tr><td width=100% align=center><b>Перем<br>ЭПУ</b></td>";
	print "<tr><td width=100% align=center><b>14-11</b></td>";
	print "<tr><td width=100% align=center><b>14-12</td>";
	print "<tr><td width=100% align=center><b>14-13</b></td>";
	print "<tr><td width=100% align=center><b>14-14</b></td>";
	print "<tr><td width=100% align=center><b>14-15</b></td>";
	print "<tr><td width=100% align=center><b>14-16</b></td>";
	print "<tr><td width=100% align=center><b>14-17</b></td>";
	print "<tr><td width=100% align=center><b>&nbsp</b></td></table>";

	print "<table align=left border=7 cellpadding=5 cellspacing=5 width=8% bgcolor=#B0B0B0 class=menu>";
	print "<tr><td width=100% align=center><b>Перем 1</b></td>";
	print "<tr><td width=100% align=center><b>18-0</b></td>";
	print "<tr><td width=100% align=center><b>18-1</td>";
	print "<tr><td width=100% align=center><b>18-2</b></td>";
	print "<tr><td width=100% align=center><b>18-3</b></td>";
	print "<tr><td width=100% align=center><b>18-4</b></td>";
	print "<tr><td width=100% align=center><b>18-5</b></td>";
	print "<tr><td width=100% align=center><b>18-6</b></td>";
	print "<tr><td width=100% align=center><b>18-7</b></td></table>";

	print "<table align=left border=7 cellpadding=5 cellspacing=5 width=8% bgcolor=#B0B0B0 class=menu>";
	print "<tr><td width=100% align=center><b>Перем &nbsp 2</b></td>";
	print "<tr><td width=100% align=center><b>Перем3</b></td>";
	print "<tr><td width=100% align=center><b>Перем4</td>";
	print "<tr><td width=100% align=center><b>Перем5</b></td>";
	print "<tr><td width=100% align=center><b>Перем6</b></td>";
	print "<tr><td width=100% align=center><b>Перем7</b></td>";
	print "<tr><td width=100% align=center><b>Перем8</b></td>";
	print "<tr><td width=100% align=center><b>Перем9</b></td>";
	print "<tr><td width=100% align=center><b>Перем10</b></td></table>";


}

function print_serch_res($link,$qstr){

                $res = pg_query($link,$qstr);
                $lbn_qtt = pg_num_rows($res);
                if ($lbn_qtt) {
                        print "<BR> <table align=center border=7 cellpadding=5 cellspacing=5 width=40% bgcolor=#B0B0B0 class=menu>\n";
                        print "<tr><td width=30% align=center><b>Плата</b></td><td width=30% align=center><b>S/N</b></td><td width=7% align=center><b>Ряд</b></td><td width=10% align=center><b>Статив</b></td><td width=10% align=center><b>Полка</b></td><td width=13% align=center><b>Место</b></td></tr>";
                        while ($lrow = pg_fetch_row($res)) {
                                $lname = $lrow[0];
                                $lsn = $lrow[1];
                                $ltr = $lrow[2];
                                $lbr = $lrow[3];
                                $lapos = $lrow[4];
                                $lrpos = $lrow[5];
                                print "<tr><td width=30% align=center>$lname</td><td width=30% align=center>$lsn</td><td width=7% align=center>$ltr</td><td width=10% align=center>$lbr</td><td width=10% align=center>$lapos</td><td width=13% align=center>$lrpos</td></tr>";
                        }
                        print "</table>\n";
                } else {
                        echo "<b><big>No boards found</b></big>";
                }
}

function looking_for_board($link,$hpv) {

	$iniv="";
	$qstr = "SELECT name from b_name order by name;";
	$res = pg_query($link,$qstr);
	print "<table align=center>";
	print "<form name=lookf action=index.php?a=look method=post><tr><td>Плата</td><td><SELECT NAME=lfbn><option SELECTED VALUE=$iniv></option>";
	while (	$lstr = pg_fetch_row($res) ) {
		print "<option >".$lstr[0]."</option>";
	}
	print "</SELECT></td><td>S/N</td><td><input type=text name=sn maxLength=20 size=20></td><td><BUTTON NAME=search VALUE=look>Поиск</BUTTON></td></tr></form></table>";
# by name :
	if (isset($hpv["lfbn"]) && $hpv["lfbn"]!="") {
		$lfbn = $hpv["lfbn"];
		$qstr = "select distinct name,b_list.sn,tr,br,a_pos,r_pos from b_name JOIN (b_list join (sr_list join r_list on (sr_list.r_id=r_list.r_id)) ON (b_list.sr_id=sr_list.sr_id)) ON (b_list.b_id = b_name.b_id) where name='$lfbn';";
		print_serch_res($link,$qstr);
## by sn
	} else if (isset($hpv["sn"])) {
		$ssn=$hpv["sn"];
		$qstr = "select distinct name,b_list.sn,tr,br,a_pos,r_pos from b_name JOIN (b_list join (sr_list join r_list on (sr_list.r_id=r_list.r_id)) ON (b_list.sr_id=sr_list.sr_id)) ON (b_list.b_id = b_name.b_id) where b_list.sn='$ssn';";
		print_serch_res($link,$qstr);
	}
}

function print_change_log($link) {

	echo "<p><u>Started at 2006-10-24</u></p>";
	print "<br><br>";

	print "<table align=center border=2 width=90% bgcolor=#B0B0B0 class=menu>";
	print "<td width=10% align=center rowspan=2><b>Дата</b></td>";
	print "<td width=15% align=center rowspan=2><b>Место</b></td>";
	print "<td width=25% align=center colspan=2><b>Снято</b></td>";
	print "<td width=25% align=center colspan=2><b>Установлено</b></td>";
	print "<td width=20% align=center rowspan=2><b>Примечание</b></td></tr>";

	print "<tr height=40>";
	print "<td width=10% align=center><b>Плата</b></td><td width=10% align=center><b>S/N</b></td>";
	print "<td width=10% align=center><b>Плата</b></td><td width=10% align=center><b>S/N</b></td></tr>";

	$qstr="SELECT * from chng_log order by c_date ;";
	$res = pg_query($link,$qstr);
	$log_qtt = pg_num_rows($res);

	while ($log_res=pg_fetch_row($res)) {
		print "<tr><td align=center>$log_res[0]</td>";
		print "<td align=center>$log_res[1]</td>";
		print "<td align=center>$log_res[2]</td>";
		print "<td align=center>$log_res[3]</td>";
		print "<td align=center>$log_res[4]</td>";
		print "<td align=center>$log_res[5]</td>";
		print "<td align=left>$log_res[6]</tr>";
	}
	print "</table>";
	echo "<p>Total rows : $log_qtt </p>";
}
#phpinfo();
?>
