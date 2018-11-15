<?php

include "./html_header.php";

if (!isset($hpv)){$hpv=$_POST;}
if (!isset($hgv)){$hgv=$_GET; }
if (!isset($pself)){$pself=$_SERVER["PHP_SELF"]; }

$plink = pg_pconnect ("user=xxx password=xxx dbname=inventory") or die ("<p class=err>Could not connect 2 inventory</p>");

$login=$_SERVER["PHP_AUTH_USER"];
$qstr="select distinct accnt,mol_name,login from records join mols on (records.mol_id=mols.mol_id) join accounts on (records.accnt_id=accounts.accnt_id) where login='$login';";
$res=pg_query($plink,$qstr);
$acnt_qty=pg_num_rows($res);
for ( $i=0;$i<$acnt_qty;$i++ ) {
	$mol_acnt=pg_fetch_row($res,$i);
	$mol_acnt_arr[]=$mol_acnt[0];
}

$qstr="select mol_id from mols where login='$login';";
$res=pg_query($plink,$qstr);
$mol_id=pg_fetch_row($res);

if (!isset($hgv["a"])){$hgv["a"]="";}

print "<table width=100% align=center>
	<tr><td width=11></td><td width=11></td><td width=12>Вы вошли как $login<br><br></td>
	<tr><td width=11><a href=$pself?a=first>К началу/Main</a/></td>
	<td width=11><a href=$pself?a=rec_add>Добавить запись/Add record</a></td>
	<td width=11><a href=$pself?a=rec_chng>Изменить запись/Edit record</a></td></tr>
	<td width=11>
	<td width=11><a href=$pself?a=rec_del>Удалить запись/Delete record</a></td>
	<td width=11><a href=$pself?a=log>Журнал/LOG</a></td></tr>
	</table>";

if ( !$hgv["a"] || $hgv["a"]=="first" ) {
	print_first($acnt_qty,$pself,$mol_acnt,$res,$mol_acnt_arr);
}

elseif ( $hgv["a"] && in_array($hgv["a"],$mol_acnt_arr) ) {

	$qstr="select accnt_id from accounts where accnt=".$hgv["a"].";";
	$res=pg_query($plink,$qstr);
	$acnt_id=pg_fetch_row($res);

	print "<br><br>Выберите файл *.xls текущего счета для сравнения с БД";
	print "<br><br><form method=post enctype=multipart/form-data>
		<input style=background:#f2f2f2 size=40 type=file name=userfile></input>&nbsp;&nbsp;
		<INPUT TYPE=submit VALUE=Check>
		</form></br></br>";
	echo '<pre>';

	$qstr="select sum (cost) from records where mol_id=$mol_id[0] and accnt_id=$acnt_id[0];";
	$res_sum=pg_query($plink,$qstr);
	$sum_acnt=pg_fetch_row($res_sum);

	$qstr="select * from records where mol_id=$mol_id[0] and accnt_id=$acnt_id[0] order by subitem;";
	$res=pg_query($plink,$qstr);
	$rec_qty=pg_num_rows($res);
	
	print "<BR><BR>МОЛ&nbsp;:&nbsp;$mol_acnt[1]<br>Счет#&nbsp;&nbsp;".$hgv["a"]."<br>Всего по счету : $sum_acnt[0]";
	print "<BR><BR><table align=center border=2 cellpadding=0 cellspacing=0 width=95% bgcolor=#8fbc8f class=menu>
		<tr align=left>
		<td align=center width=3%>#</td>
		<td align=center width=47%>Наименование</td>
		<td align=center width=7%>Дата</td>
		<td align=center width=10%>Инв. #</td>
		<td align=center width=12%>S/N</td>
		<td align=center width=4%>Ед. изм.</td>
		<td align=center width=3%>кол-во</td>
		<td align=center width=14%>стоимость</td>
		</tr>";

	for ( $i=0;$i<$rec_qty;$i++ ) {
		$rec=pg_fetch_row($res,$i);
		print "<tr align=left>
			<td align=center width=3%>$rec[1]</td>
			<td align=left width=47%>$rec[2]</td>
			<td align=center width=7%>$rec[3]</td>
			<td align=center width=10%><a href=$pself?a=descr$rec[4]>$rec[4]</a></td>
			<td align=center width=12%>&nbsp;$rec[5]</td>
			<td align=center width=4%>$rec[6]</td>
			<td align=center width=3%>$rec[7]</td>
			<td align=center width=14%>$rec[13]</td></tr>";
	}

	if (isset($_FILES["userfile"])) {
		$cost_cnt=0;
		$item_cnt=0;

		$uploaddir = '/var/www/html/inventory/uploads/';
		$uploadfile =$uploaddir.basename($_FILES['userfile']['name']);

		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
#			echo "File is valid, and was successfully uploaded.\n";
		} else {
			echo "Possible file upload attack!\n";
			echo 'Here is some more debugging info:';
			print_r($_FILES);
		}
		print "</pre>";

		require_once 'excel/reader.php';
		$data = new Spreadsheet_Excel_Reader();
#		$data->setOutputEncoding('KOI8-R');
		$data->read($uploadfile);
		error_reporting(E_ALL ^ E_NOTICE);
		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
#		        for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
			$xls_item=$data->sheets[0]['cells'][$i][1];
			$xls_inv=$data->sheets[0]['cells'][$i][5];
			$xls_cost=$data->sheets[0]['cells'][$i][12];
			if ( preg_match("/\d+/",$xls_inv,$match ) && $i>18 ) {
				$inv_xls_arr[]=$match[0];
			}				
			if ( preg_match("/.*/",$xls_cost,$match ) && $i>18 ) {
				$cost_xls_arr[$xls_inv]=$match[0];
			}				
			if ( preg_match("/\d+/",$xls_item,$match ) && $i>18 ) {
				$item_xls_arr[$xls_inv]=$match[0];
			}				
		}

		$qstr="select inv_num,subitem from records where mol_id=$mol_id[0] and accnt_id=$acnt_id[0] order by subitem;";
		$res=pg_query($plink,$qstr);
		$inv_rec=pg_fetch_all($res);
		
		for ( $i=0;$i<count($inv_rec);$i++ ) {
			$inv_rec_arr[]=$inv_rec[$i][inv_num];
		}

		$dif_del=array_diff ( $inv_rec_arr,$inv_xls_arr );
		$dif_add=array_diff ( $inv_xls_arr,$inv_rec_arr );

		if ( count($dif_del)==0 && count($dif_add)==0 ) {
			echo "<br>Добавление/удаление инвентарных номеров не обнаружено</br>";	
		}
		else {
			print "<table align=left bgcollor=red border=2 cellpadding=0 cellspacing=0 width=30%>
				<tr><td align=center width=80%>Инв номер</td><td wlign=center idth=20%>Статус</td></tr>";
		}
		foreach ( $dif_del as $del ) {
			print "<tr><td align=center width=80%>$del</td><td align=center width=20%>-</td></tr>";
		}
		foreach ( $dif_add as $add ) {
			print "<tr><td align=center width=80%>$add</td><td align=center width=20%>+</td></tr><br>";
		}
		foreach ( $cost_xls_arr as $inv_cost => $cost ) {
			$qstr="select cost from records where mol_id=$mol_id[0] and accnt_id=$acnt_id[0] 
				and inv_num='".$inv_cost."';";
			$res=pg_query($plink,$qstr);
			$cost_rec=pg_fetch_row($res);

			if ( $cost_rec[0]!=$cost ) {
				if ($cost_cnt==0) {
					print "<br><br><br><br><table align=center bgcollor=red border=2 cellpadding=0 cellspacing=0 width=30%>";
					print "<tr><td width=34%>Инв номер</td><td width=33%>Старая стоимость</td>
						<td width=33%>Новая стоимость</td></tr>";
				}
				else {
					if ( $cost!=$sum_acnt[0] ) {
						print "<tr><td width=34%>$inv_cost</td><td width=33%>$cost_rec[0]</td>
							<td width=33%>$cost</td></tr>";
					}
				}
			}
			$cost_cnt=$cost_cnt+1;
		}

	
	}		
}
elseif ( $hgv["a"] && $hgv["a"]=="rec_add" ) {
	print_rec_add ($plink,$hpv,$mol_id);
}
elseif ( $hgv["a"] && $hgv["a"]=="rec_chng" ) {
	print_rec_edit ($plink,$hpv,$mol_id);
}
elseif ( $hgv["a"] && $hgv["a"]=="rec_del" ) {
	print_rec_del ($plink,$hpv,$mol_id);
}
elseif ( $hgv["a"] && $hgv["a"]=="log" ) {
	print_rec_log ($plink,$hpv,$mol_id,$pself);
}
elseif ( $hgv["a"] && preg_match('/^inv_log(.+)/',$hgv["a"],$matches) ) {
	$inv_log_extnd=$matches[1];
	print_inv_log ($plink,$hpv,$mol_id,$pself,$inv_log_extnd);
}
elseif ( $hgv["a"]  && preg_match('/^descr(.+)/',$hgv["a"],$matches) ) {
	$inv_extnd=$matches[1];
	print_rec_descr($plink,$hgv,$inv_extnd);
}

###########################################################################################################################################
###########################################################################################################################################

function print_first($acnt_qty,$pself,$mol_acnt,$res,$mol_acnt_arr) {

	$td_width = floor(85/$acnt_qty);
	$mol_td_width = 100 - $td_width*$acnt_qty;

	print "<BR><BR><BR><BR><BR><BR>
		<table align=center border=2 cellpadding=0 cellspacing=0 width=60% bgcolor=#8fbc8f class=menu>
	 	<tr hight=35% align=left><td width=$mol_td_width%>$mol_acnt[1]</td>";
	foreach ( $mol_acnt_arr as $acnt   ) {
		print "<td width=$td_width%><a href=$pself?a=$acnt>$acnt</a></td>";
	}
	print "</tr>";
}

function print_rec_menu ($but) {

	if ( !isset($hpv["item"]) ) { $hpv["item"]=""; }
	if ( !isset($hpv["name"]) ) { $hpv["name"]=""; }
	if ( !isset($hpv["date"]) ) { $hpv["date"]=""; }
	if ( !isset($hpv["inv_num"]) ) { $hpv["inv_num"]=""; }
	if ( !isset($hpv["sn"]) ) { $hpv["sn"]=""; }
	if ( !isset($hpv["unit"]) ) { $hpv["unit"]=""; }
	if ( !isset($hpv["qty"]) ) { $hpv["qty"]=""; }
	if ( !isset($hpv["cost"]) ) { $hpv["cost"]=""; }
	if ( !isset($hpv["inv_descr"]) ) { $hpv["inv_descr"]=""; }
	if ( !isset($hpv["locate"]) ) { $hpv["locate"]=""; }
	if ( !isset($hpv["include"]) ) { $hpv["include"]=""; }
	if ( !isset($hpv["acnt_ins"]) ) { $hpv["acnt_ins"]=""; }

	print "<BR><br><FORM  method=post>
		<p><LABEL ACCESSKEY=I>*Инв номер<br><INPUT TYPE=text NAME=inv_num SIZE=15 MAXLENGTH=20></LABEL></p>
		<p><LABEL ACCESSKEY=N>#<br><INPUT TYPE=text NAME=item SIZE=15 MAXLENGTH=15></LABEL></p>
		<p><LABEL ACCESSKEY=D>Дата гггг-мм-дд<br><INPUT TYPE=text NAME=date SIZE=15 MAXLENGTH=15></LABEL></p>";
	if ( $but=="Добавить" ) {
		print "<p><LABEL ACCESSKEY=A>Счет<br><INPUT TYPE=text NAME=acnt_ins SIZE=15 MAXLENGTH=20></LABEL></p>";
	}
	print "<p><LABEL ACCESSKEY=U>Ед. изм.<br><INPUT TYPE=text NAME=unit SIZE=15 MAXLENGTH=20></LABEL></p>
		<p><LABEL ACCESSKEY=Q>кол-во<br><INPUT TYPE=text NAME=qty SIZE=15 MAXLENGTH=20></LABEL></p>
		<p><LABEL ACCESSKEY=C>стоимость<br><INPUT TYPE=text NAME=cost SIZE=15 MAXLENGTH=20></LABEL></p>
		<p><LABEL ACCESSKEY=S>SN<br><INPUT TYPE=text NAME=sn SIZE=100 MAXLENGTH=100></LABEL></p>
		<p><LABEL ACCESSKEY=M>Наименование<br><INPUT TYPE=text NAME=name SIZE=100></LABEL></p>
		<p><LABEL ACCESSKEY=L>Состав<br><INPUT TYPE=text NAME=include SIZE=100 ></LABEL></p>
		<p><LABEL ACCESSKEY=O>Описание<br><INPUT TYPE=text NAME=inv_descr SIZE=100 MAXLENGTH=10000></LABEL></p>
		<p><LABEL ACCESSKEY=R>Расположение<br><INPUT TYPE=text NAME=locate SIZE=100 MAXLENGTH=1000></LABEL></p>
		<p><LABEL ACCESSKEY=K>Ремарки<br><INPUT TYPE=text NAME=rem_ins SIZE=100 MAXLENGTH=100></LABEL></p>
		<p><BUTTON NAME=submit VALUE=rec_adit>$but</BUTTON></p>
		</FORM>";
}

function print_rec_add ($plink,$hpv,$mol_id) {

	$action_id=7;
	$but="Добавить";
	
	echo "<br><br>Добавление записи";
	echo "<br><br>Все поля обязательны к заполнению!";
	print_rec_menu ($but);

	if ( isset($hpv["submit"]) && $hpv["submit"]=="rec_adit"){

		$qstr="select accnt_id  from accounts where accnt='".$hpv["acnt_ins"]."';";
		$res=pg_query($plink,$qstr);
		$acnt_ins=pg_fetch_row($res);

		$qstr="select inv_num from records where inv_num='".$hpv["inv_num"]."';";
		$res=pg_query($plink,$qstr);
		$inv_ins=pg_fetch_row($res);

		if ( $inv_ins[0] ) {
			echo "<br><br>Запись с инвентарным номером ".$hpv["inv_num"]." существет !"; 
		}
		elseif (!$acnt_ins[0]) {
			echo "<br><br>Несуществующий счет ".$hpv["acnt_ins"]." !";		
		}
		else {

			$ins_rec="insert into records (subitem,name,in_date,inv_num,sn,unit,qty,cost,mol_id,accnt_id,inv_descr,
				locate,include)	values (".$hpv["item"].",' ".$hpv["name"]."',' ".$hpv["date"]."','".$hpv["inv_num"].
				"',' ".$hpv["sn"]."',' ".$hpv["unit"]."',".$hpv["qty"].",' ".$hpv["cost"]."',$mol_id[0],$acnt_ins[0],'
				 ".$hpv["inv_descr"]."',' ".$hpv["locate"]."',' ".$hpv["include"]."');";
			$res=pg_query($plink,$ins_rec);

			make_log ($plink,$hpv,$action_id);
		}
	}
}

function print_rec_edit ($plink,$hpv,$mol_id) {
	
	$action_id=8;
	$but="Изменить";

	echo "<br><br>Изменение записи по инвентарному номеру*.
		<br>Значения полей будут изменены на указанные.
		<br>Для удаления значения в соответствующем поле поставьте 0.";

	print_rec_menu ($but);

	if ( isset($hpv["submit"]) && $hpv["submit"]=="rec_adit" ) {
		
		$qstr="select inv_num from records where inv_num='".$hpv["inv_num"]."';";
		$res=pg_query($plink,$qstr);
		$inv_upd=pg_fetch_row($res);

		if ( !$inv_upd[0] && $hpv["inv_num"] ) {
			echo "<br><br>Запись с инвентарным номером ".$hpv["inv_num"]." не существет !"; 
		}
		else {
			$inv_update="";

			if ( $hpv["item"] && $inv_update=="" ) { $inv_update=" subitem=".$hpv["item"]; }
			elseif ( $hpv["item"] && isset($inv_update) ) { $inv_update=$inv_update." ,subitem=".$hpv["item"]; }

			if ( $hpv["date"] && $inv_update=="" ) { $inv_update=" in_date='".$hpv["date"]."'"; }
			elseif ( $hpv["date"] && isset($inv_update) ) { $inv_update=$inv_update." ,in_date='".$hpv["date"]."'"; }

			if ( $hpv["sn"] && $inv_update=="" ) { $inv_update=" sn='".$hpv["sn"]."'"; }
			elseif ( $hpv["sn"] && isset($inv_update) ) { $inv_update=$inv_update." ,sn='".$hpv["sn"]."'"; }

			if ( $hpv["name"] && $inv_update=="" ) { $inv_update=" name='".$hpv["name"]."'"; }
			elseif ( $hpv["name"] && isset($inv_update) ) { $inv_update=$inv_update." ,name='".$hpv["name"]."'"; }

			if ( $hpv["unit"] && $inv_update=="" ) { $inv_update=" unit='".$hpv["unit"]."'"; }
			elseif ( $hpv["unit"] && isset($inv_update) ) { $inv_update=$inv_update." ,unit='".$hpv["unit"]."'"; }

			if ( $hpv["qty"] && $inv_update=="" ) { $inv_update=" qty=".$hpv["qty"]; }
			elseif ( $hpv["qty"] && isset($inv_update) ) { $inv_update=$inv_update." ,qty=".$hpv["qty"]; }

			if ( $hpv["cost"] && $inv_update=="" ) { $inv_update=" cost=".$hpv["cost"]; }
			elseif ( $hpv["cost"] && isset($inv_update) ) { $inv_update=$inv_update." ,cost=".$hpv["cost"]; }

			if ( $hpv["inv_descr"] && $inv_update=="" ) { $inv_update=" inv_descr='".$hpv["inv_descr"]."'"; }
			elseif ($hpv["inv_descr"]&& isset($inv_update) ) { $inv_update=$inv_update." ,inv_descr='".$hpv["inv_descr"]."'"; }

			if ( $hpv["locate"] && $inv_update=="" ) { $inv_update=" locate='".$hpv["locate"]."'"; }
			elseif ( $hpv["locate"] && isset($inv_update) ) { $inv_update=$inv_update." ,locate='".$hpv["locate"]."'"; }

			if ( $hpv["include"] && $inv_update=="" ) { $inv_update=" include='".$hpv["include"]."'"; }
			elseif ($hpv["include"] && isset($inv_update) ) { $inv_update=$inv_update." ,include='".$hpv["include"]."'"; }

			if ( $inv_update=="" ) { echo "<br>Не выбраны поля для изменения !"; }
			elseif ( !$hpv["inv_num"] ) { echo "<br>*Инв номер - Обязательное поле !"; }
			else { 
				$qstr="update records set $inv_update where inv_num='".$hpv["inv_num"]."';";
				pg_query($plink,$qstr);
/*	
				$qstr="SELECT last_value from records_rec_id_seq ;";
				$res=pg_query($plink,$qstr);
				$rec_id=pg_fetch_row($res);
*/
				make_log ($plink,$hpv,$action_id);
			}
		}
	}	
}

function print_rec_del ($plink,$hpv,$mol_id) {print "<br>Under construction !<br>";}

function print_rec_log ($plink,$hpv,$mol_id,$pself) {

	echo "<br><br>Started at Mon Nov 24 15:52:26 EET 2008</br></br>";

	$qstr="select * from logs join records on (logs.inv_num=records.inv_num) where mol_id=$mol_id[0] order by event_date;";
	$res=pg_query($plink,$qstr);
	$log_qty=pg_num_rows($res);

	print "<BR><BR><table align=center border=2 cellpadding=0 cellspacing=0 width=70% bgcolor=#8fbc8f class=menu>
		<tr align=left>
		<td align=center width=3%>#</td>
		<td align=center width=17%>Дата</td>
		<td align=center width=30%>Действие</td>
		<td align=center width=50%>Ремарки</td>
		</tr>";

	for ( $i=0;$i<$log_qty;$i++ ) {
		$log=pg_fetch_row($res,$i);
		print "<tr align=left>
		<td align=center width=3%>".($i+1)."</td>
		<td align=center width=17%>$log[0]</td>";
		$qstr_act="select action_descr from actions where action_id=$log[1];";
		$res_act=pg_query($plink,$qstr_act);
		$act=pg_fetch_row($res_act);
		print "<td align=center width=30%>$act[0] <a href=$pself?a=inv_log$log[8]>$log[8]</a></td>";
		print "<td align=center width=50%>$log[3]</td></tr>";
	}
	print "</table>";

}

function print_inv_log ($plink,$hpv,$mol_id,$pself,$inv_log_extnd) {

		$qstr="select * from records where mol_id=$mol_id[0] and inv_num='$inv_log_extnd';";
		$res=pg_query($plink,$qstr);
		$inv_log=pg_fetch_row($res);

		print "<BR><BR><table align=center border=2 cellpadding=0 cellspacing=0 width=95% bgcolor=#8fbc8f class=menu>
			<tr align=left>
			<td align=center width=3%>#</td>
			<td align=center width=47%>Наименование</td>
			<td align=center width=7%>Дата</td>
			<td align=center width=10%>Инв. #</td>
			<td align=center width=12%>S/N</td>
			<td align=center width=4%>Ед. изм.</td>
			<td align=center width=3%>кол-во</td>
			<td align=center width=14%>стоимость</td>
			</tr>";

		print "<tr align=left>
			<td align=center width=3%>$inv_log[1]</td>
			<td align=left width=47%>$inv_log[2]</td>
			<td align=center width=7%>$inv_log[3]</td>
			<td align=center width=10%><a href=$pself?a=descr$inv_log[4]>$inv_log[4]</a></td>
			<td align=center width=12%>&nbsp;$inv_log[5]</td>
			<td align=center width=4%>$inv_log[6]</td>
			<td align=center width=3%>$inv_log[7]</td>
			<td align=center width=14%>$inv_log[13]</td></tr>";
}

function make_log ( $plink,$hpv,$action_id ) {

	$ins_log="insert into logs (event_date,action_id,inv_num,remark) values 
				('".date("m.d.y")."',$action_id,'".$hpv["inv_num"]."','".$hpv["rem_ins"]."');";
	$res=pg_query($plink,$ins_log);
	if ($res!=0) { print "<br>Добавлено в журнал!";}
	else { print "<br>Ошибка при добавлении в журнал!"; }
}

function print_rec_descr($plink,$hgv,$inv_extnd){

	$qstr="select inv_descr,locate,include from records where inv_num='$inv_extnd';";
	$res=pg_query($plink,$qstr);

	$descr=pg_fetch_row($res);
	$inv_descr=$descr[0];
	$locate=$descr[1];
	$include=$descr[2];
	
	print "<br><br><br>
		<b>Inv #</b> $inv_extnd<br><br>
		<b>Record description :</b><p>$inv_descr</p>
		<b>Location : </b><p>$locate</p>
		<b>Include : </b><p>$include</p><br>";
}

?>
