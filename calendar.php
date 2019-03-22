<?php
################################################################################
#...............................Календарь заказов..............................#
#...............................Made by YCsystem...............................#
#..............................YuraZosia@gmail.com.............................#
################################################################################
if(!defined('START7284829fj39f2huwf283fwef2f'))die("Hacking attempt!");
if($_SESSION['uprava']==1 and isset($_POST['change_den']) and $_POST['random']==$_COOKIE['rndd_kalendar'] and !empty($_POST['den']) and isset($_POST['name']) and isset($_POST['data'])){
	$dps=1;
	$name=check($_POST['name']);
	$den=check($_POST['den']);
	$data=check($_POST['data']);
	$cena=clear($_POST['cena']);
	$fio=check($_POST['fio']);
	$oplata=clear($_POST['oplata']);
	$oplata_o=clear($_POST['oplata_o']);
	$oplata_sql='';
	$borg_sql='';
	$last_oplata='';
	$show_tip_sql='';
	if(isset($_REQUEST['show_tip'])){ $show_tip_sql=", show_tip='".checkbox_verify('show_tip')."'";}
	if($_POST['den_func']=='update'){
		//$borg=mysql_result(mysql_query("SELECT borg FROM calendar WHERE den='$den' LIMIT 1"), 0);
		$borg=clear($_POST['borg']);
		$cena_old=clear($_POST['cena_old']);
		if($cena!=$cena_old){
			$borg=$cena-$oplata_o;
			$borg_sql=', borg=\''.$borg.'\' ';
		}
	}
	if($_POST['den_func']=='new'){ $borg_sql=', borg=\''.$cena.'\' ';}
	if(!empty($oplata) and $oplata>0){
		$borg=$borg-$oplata;
		$upd_hs=1;
		// if($borg<=0 or $borg==$cena) $borg='';
		$borg_sql=', borg=\''.$borg.'\' ';
		$oplata_sql=", oplata='".($cena-$borg)."'";
	}
	
		if($upd_hs==1){
			mysql_query("INSERT INTO oplata_history SET den='$den', money='$oplata', date='".russian_date(false)."'") or log::write('sql','ID:3534563332614502012<br />'.mysql_error());
			$last_oplata=", last_oplata='".russian_date(false)."'";
		}
	
	mysql_query("UPDATE calendar SET name='$name', data='$data', cena='$cena', fio='$fio' $oplata_sql $borg_sql $last_oplata $show_tip_sql WHERE den='$den'") or log::write('sql','ID:33326102012<br />'.mysql_error());
	if($_POST['den_func']=='new'){
		stikadd('Новая запись добавлена!<br />', 5000);
	}
	if($_POST['den_func']=='update'){
		$st_mes='Данные обновлены!';
		stikadd($st_mes);
	}
	$dannye=1;
}

if($_SESSION['uprava']==1 and $_FILES['foto_v']['name']!='' and $_POST['func']=='fto' and $_POST['random']==$_COOKIE['rndd_kalendar']){
$uploaddir='image/avatars/'; // это папка, в которую будет загружаться картинка 
$apend=date('YmdHis').$_POST['fden'].'.jpg'; // это имя, которое будет присвоенно изображению 
$uploadfile=$uploaddir.$apend; // в переменную $uploadfile будет входить папка и имя изображения 
if($_FILES['foto_v']['size']!=0 and $_FILES['foto_v']['size']<=1024*1*1024) { // Здесь мы проверяем размер если он более 1 МБ 
if (move_uploaded_file($_FILES['foto_v']['tmp_name'], $uploadfile)) { // Здесь идет процесс загрузки изображения 
$size = getimagesize($uploadfile); // с помощью этой функции мы можем получить размер пикселей изображения 
if ($size[0] < 2001 && $size[1]<3001) { // если размер изображения не более 600 пикселей по ширине и не более 5000 по высоте 
mysql_query("UPDATE calendar SET ava='$apend' WHERE den='{$_POST['fden']}'") or log::write('sql','ID:593868346810134012<br />'.mysql_error());
stikadd ("Фото загружено :)", 6000); 
} else {
stikadd ("Размер пикселей превышает допустимые нормы (ширина не более - 2000 пикселей, высота не более 3000)", 8000); 
unlink($uploadfile); // удаление файла 
}
} else {
	stikadd ("Фото не загружено, верьнитесь и попробуйте еще раз", 6000);
	} 
	} else { 
		stikadd ("Размер фото не должен превышать 1Mb", 6000);
	}

}
if($_SESSION['uprava']==1 and $_GET['func']=='delden' and $_GET['rnd']==$_COOKIE['rndd_kalendar'] and !empty($_GET['den'])){
	$del_den=$_GET['den'];
	mysql_query("UPDATE calendar SET name='', data='', cena='', fio='', oplata='', borg='', last_oplata='' WHERE den='$del_den'") or log::write('sql','ID:5434103510112012<br />'.mysql_error());
	mysql_query("DELETE FROM oplata_history WHERE den='$del_den'") or log::write('sql','ID:8479104910112012<br />'.mysql_error());
	stikadd('Запись и все данные<br />за '.$_GET['d'].' очищены!', 6000);
}
?>

<script type="text/javascript">
$(function () {
	$('.date_has_event').each(function () {
		// options
		var distance = 10;
		var time = 250;
		var hideDelay = 500;
		var hideDelayTimer = null;
		var beingShown = false;
		var shown = false;
		var trigger = $(this);
		var popup = $('.events ul', this).css('opacity', 0);
		$([trigger.get(0), popup.get(0)]).mouseover(function () {
			if (hideDelayTimer) clearTimeout(hideDelayTimer);
			if (beingShown || shown) {
				return;
			} else {
				beingShown = true;
				popup.css({
					bottom: 20,
					left: -120,
					display: 'block' 
				})
				.animate({
					bottom: '+=' + distance + 'px',
					opacity: 1
				}, time, 'swing', function() {
					beingShown = false;
					shown = true;
				});
			}
		}).mouseout(function () {
			if (hideDelayTimer) clearTimeout(hideDelayTimer);
			hideDelayTimer = setTimeout(function () {
				hideDelayTimer = null;
				popup.animate({
					bottom: '-=' + distance + 'px',
					opacity: 0
				}, time, 'swing', function () {
					shown = false;
					popup.css('display', 'none');
				});
			}, hideDelay);
		});
	});
});


$(function () {
	$('.date_has_event_s').each(function () {
		var sdistance = 10;
		var stime = 250;
		var shideDelay = 500;
		var shideDelayTimer = null;
		var sbeingShown = false;
		var sshown = false;
		var strigger = $(this);
		var spopup = $('.events_s ul', this).css('opacity', 0);
		$([strigger.get(0), spopup.get(0)]).mouseup(function () {
			if (shideDelayTimer) clearTimeout(shideDelayTimer);
			if (sbeingShown || sshown) {
				return;
			} else {
				sbeingShown = true;
				spopup.css({
					bottom: 20,
					left: -120,
					display: 'block'
				})
				.animate({
					bottom: '+=' + sdistance + 'px',
					opacity: 1
				}, stime, 'swing', function() {
					sbeingShown = false;
					sshown = true;
				});
			}
		}); $('#close_add_den', this).click(function () {
			if (shideDelayTimer) clearTimeout(shideDelayTimer);
			shideDelayTimer = setTimeout(function () {
				shideDelayTimer = null;
				spopup.animate({
					bottom: '-=' + sdistance + 'px',
					opacity: 0
				}, stime, 'swing', function () {
					sshown = false;
					spopup.css('display', 'none');
				});
			}, shideDelay);
		});
	});
});
</script>

<center><span style="color:#FFFF00;font-family:Arial;font-size:2.2em;font-weight:bold;">Календарь заказов</span></center>

<?php
$data_mcolor=mysql_result(mysql_query("SELECT `value` FROM `site` WHERE `set`='mouns_color' LIMIT 1"), 0);
$mouns_color=explode(';', $data_mcolor);
$mouns_name=array($lang['january'], $lang['february'], $lang['march'], $lang['april'], $lang['may'], $lang['june'], $lang['july'], $lang['august'], $lang['september'], $lang['october'], $lang['november'], $lang['december']);

function day_in_mouns($m){
	$l=28;
	if($m==0) return array(31, $l, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	if($m==1) return array($l, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31, 31);
	if($m==2) return array(31, 30, 31, 30, 31, 31, 30, 31, 30, 31, 31, $l);
	if($m==3) return array(30, 31, 30, 31, 31, 30, 31, 30, 31, 31, $l, 31);
	if($m==4) return array(31, 30, 31, 31, 30, 31, 30, 31, 31, $l, 31, 30);
	if($m==5) return array(30, 31, 31, 30, 31, 30, 31, 31, $l, 31, 30, 31);
	if($m==6) return array(31, 31, 30, 31, 30, 31, 31, $l, 31, 30, 31, 30);
	if($m==7) return array(31, 30, 31, 30, 31, 31, $l, 31, 30, 31, 30, 31);
	if($m==8) return array(30, 31, 30, 31, 31, $l, 31, 30, 31, 30, 31, 31);
	if($m==9) return array(31, 30, 31, 31, $l, 31, 30, 31, 30, 31, 31, 30);
	if($m==10) return array(30, 31, 31, $l, 31, 30, 31, 30, 31, 31, 30, 31);
	if($m==11) return array(31, 31, $l, 31, 30, 31, 30, 31, 31, 30, 31, 30);
}
function calen_foto($id, $rand_kalendar, $kto, $typ='koment', $button_close='true',$position_top='auto',$position_left='auto'){
	$alert_form='<div id="alertModalOuter"><div id="alertModal" style="height: 170px;"><div id="alert"><p>Загрузить фотографию для<br /><b style="text-decoration: underline;">'.$kto.'</b></p><p><form method="POST" action="?p=kalendar"enctype="multipart/form-data" name="foto'.$id.'"><input type="hidden" name="fden" value="'.$id.'"><input type="hidden" name="func" value="fto"><input type="hidden" name="random" value="'.$rand_kalendar.'"><input type="file" size="10" name="foto_v" style="border:1px solid #5ca64c;    border-bottom-color:#318123;"></p><a class="alertButton" id="yes" onClick="javascript:document.foto'.$id.'.submit();"><span>Загрузить</span></a><a class="alertButton" id="no"><span>Отмена</span></a></form></div></div></div>';
	$alert_form=str_replace('"', '\"', $alert_form);
	$top=($position_top=='auto') ? '' : ',top:'.$position_top;
	$left=($position_left=='auto') ? '' : ',left:'.$position_left;
	$typa=($typ=='koment') ? '' : $typ;
	echo '	<script type="text/javascript">$(document).ready(function(){$(\'#foto-load'.$id.'\').click(function(){$.stickr({note:\''.$alert_form.'\',className:\'alertstik\',sticked:'.$button_close.$top.$left.'});});});</script>'; 
}
function generate_calendar($mounse, $otkokogo, $limit, $div_color){
	// месяц, от, количество дней в месяце, цвет фона в НЕХ(#645645) или RGB (rgba(0,0,0,0.25))
	global $rand_kalendar, $den_nedeli, $lang;
echo '<div class="calendar" style="background: '.$div_color.';">
<table style="	
				margin: 10px auto;
				text-align: center;
				width: 100px;">
<thead>
<tr><th style="background: #006344 ; " colspan="7">'.$mounse.'</th></tr>
</thead>
<tbody>
	<tr style="background: #006344 ; "><td>'.$lang['mon'].'</td><td>'.$lang['tue'].'</td><td>'.$lang['wed'].'</td><td>'.$lang['thu'].'</td><td>'.$lang['fri'].'</td><td>'.$lang['sat'].'</td><td>'.$lang['sun'].'</td></tr>
	<tr>';

	$sql_scc=mysql_query("SELECT * from calendar LIMIT $otkokogo, $limit") or log::write('sql','ID:113226102012<br />'.mysql_error()); // ну тут понятно вроде, если нет, то создаем запрос в таблицу , сортируя по den
	$i=0; // для формирования переносов недели
	$ii=1; // для чисел дней
	while($data=@mysql_fetch_array($sql_scc)){
			
		if ($i % 7){
				//echo '';
			} else {echo '</tr><tr>';}
			$date_g=explode('_', $data['den']);
			$dd_gd_0=(strlen($date_g[1])==8) ? $date_g[1] : '0'.$date_g[1];
			$dd_gd=$den_nedeli[$data['nedel']].' '.$dd_gd_0;
			$avatar_src=(empty($data['ava'])) ? 'avatar.gif' : 'avatars/'.$data['ava'];
			if($data['nedel']>=1 and $i==0){
				$i=$data['nedel'];
				for($tdi=1;$tdi<=$data['nedel'];$tdi++){
					echo '<td></td>';
				}
			}
			
			if($data['name']!='' and $data['data']!=''){
				if($_SESSION['uprava']==1){
					echo '<td class="date_has_event"><span class="medium button ';
					if($data['borg']>0){echo 'red';} else {echo 'green';}
					echo '" style="color: yellow;">'.$ii.'</span>
					<div class="events">
					<form method="POST">
					<input type="hidden" name="random" value="'.$rand_kalendar.'">
					<input type="hidden" name="den" value="'.$data['den'].'">
					<input type="hidden" name="cena_old" value="'.$data['cena'].'">
					<input type="hidden" name="borg" value="'.$data['borg'].'">
					<input type="hidden" name="oplata_o" value="'.$data['oplata'].'">
					<input type="hidden" name="den_func" value="update">
					<ul>
						<li style="">
						<div style="float: left; margin: 10px;">
							<span class="kantovka" style="background: red; color: yellow; font-size: 16px; font-weight: bold; text-shadow: 0 1px 1px rgba(0, 0, 0, .75); text-align: center;">'.$dd_gd.'</span>
							<br />
							<span class="title">Событие: <input type="text" style="width: 100px;" name="name" value="'.$data['name'].'"></span>
							<span class="title">Заказчик: <input type="text" style="width: 100px;" name="fio" value="'.$data['fio'].'"></span>
							<span class="title">Показывать событие: <input type="checkbox" name="show_tip" '; if($data['show_tip']==1){echo 'checked="checked"';} echo '/></span>';
							
							echo '</div>
							<div style="float: right;" class="avak"><a id="bgfoto" href="image/'.$avatar_src.'" title="'.$dd_gd.'  '.$data['fio'].' ';
							if($data['borg']>0){ echo 'Борг: '.$data['borg'].' грн';} else { echo 'Заказ оплачен!';}
							echo '" rel="slideshow"><img class="avatar_img" src="image/'.$avatar_src.'"></a>';
							calen_foto($data['den'], $rand_kalendar, $data['fio']);
							echo '<div id="foto-load'.$data['den'].'" style="right: 10px;"><img src="image/img/dir.gif" '.tooltipadd('Загрузить фото!').'>
							</div>';
							echo '</div>
							<div class="end"></div>
							</li>
							<li>
							<span class="title">Описание:</span><span class="title"><textarea rows="4" cols="37" name="data">'.$data['data'].'</textarea></span>
							</li>
							<li>
							<div style="float: left; margin-right: 10px;">
							<span class="title">Цена: <input type="text" name="cena" value="'.$data['cena'].'" size="5"></span>';
							if($data['borg']>0){
								echo '<span class="title" style="color: red; margin-left: 15px; margin-top: 10px; font-size: 16px;">Борг: '.$data['borg'].' грн</span>';
							}
							if($data['borg']==0){
								echo '<span class="title" style="color: green; margin-left: 15px; margin-top: 10px; font-size: 16px;">Заказ оплачен! <img src="image/img/open.gif"></span>';
							}
							$oplata_d=($data['oplata']>0) ? $data['oplata'] : '0';
							echo '
							</div>
							<div style="float: right; width: 120px;">
							<span class="title">Выплатил: <b style="color: green;">'.$oplata_d.'грн</b> </span>
							<span class="title" style="margin-top: 5px; text-decoration: underline;">'.$data['last_oplata'].'</span>
							<span class="title">Внести: <input type="text" name="oplata" value="" size="5" ';
							if($data['borg']>0){echo '';} else {echo 'disabled';}
							echo '></span>
							</div>
							<div class="end"></div>
							</li>
							<li>';
							if($_SESSION['login']=='YCsys'){
							stikalert('?p=kalendar&amp;func=delden&amp;den='.$data['den'].'&amp;d='.$dd_gd.'&amp;rnd='.$rand_kalendar, $data['den'], 'koment', 'Вы действительно хотите удалить<br />запись за '.$dd_gd.'?');
							echo '<a id="show-alert'.$data['den'].'"><img src="image/delete.gif" style="cursor: pointer;" class="opacity_65" '.tooltipadd("Удалить событие!").'></a>';
							}
							echo '<input class="large green button" type="submit" name="change_den" value="Сохранить изменения" style="float: right;">';
							
							echo '
						</li>
					</ul>
					
					</form>
					</div></td>';
				} else {
					if($data['show_tip']==1){
					echo '<td class="date_has_event"><span class="medium button red" style="color: yellow;">'.$ii.'</span>
					<div class="events">
					<ul>
						<li style="">
						<div style="float: left; margin: 10px;">
							<span class="kantovka" style="background: red; color: yellow; font-size: 16px; font-weight: bold; text-shadow: 0 1px 1px rgba(0, 0, 0, .75); text-align: center;">'.$dd_gd.'</span>
							<br />
							<span class="title">Событие: '.$data['name'].'</span>
							<span class="title">Заказчик: '.$data['fio'].'</span>
						</div>
							<div style="float: right;" class="avak">';
							echo '<img class="avatar_img" src="image/'.$avatar_src.'">';
							calen_foto($data['den'], $rand_kalendar, $data['fio']);
							echo '
							</div>
							<div class="end"></div>
					</ul>
					</div></td>';
					} else {
						echo '<td><span class="medium button red" style="color: yellow;">'.$ii.'</span></td>';
					}
				}
				
			} else {
				if($_SESSION['uprava']==1){
				echo '<td class="date_has_event_s"><span class="medium button grey">'.$ii.'</span>
				<div class="events_s">
					<form method="POST">
					<input type="hidden" name="random" value="'.$rand_kalendar.'">
					<input type="hidden" name="den" value="'.$data['den'].'">
					<input type="hidden" name="den_func" value="new">
					<ul>
						<li>
						<span id="close_add_den" class="medium button red" style="float: right;" >x</span>
							<span class="title">Событие: <input type="text" name="name" value=""></span>
							<span class="title">Заказчик: <input type="text" name="fio" value=""></span>
							<span class="title">Описание:</span>
							<span class="desc"><textarea rows="4" cols="27" name="data"></textarea></span>
							<span class="title">К оплате: <input type="text" name="cena" value="" size="5"></span>
							<span class="title">'.$dd_gd.'<input type="submit" name="change_den" value="Сохранить" style="float: right;"></span>
						</li>
					</ul>
					</form>
					</div></td>';
				} else {
					echo '<td><span class="medium button grey">'.$ii.'</span>
					</td>';
				}
			}
		$i++;
		$ii++;
		}
echo '</tr>
</tbody>
</table></div>';
}

$mon_st=9; // переменная с какого месяца начинать генерацию -1
$num_limit=0; // переменная с какого номера (LIMIT) начинать, учитывая $mon_st
$cmn=$mon_st;
$day_in_mouns_arr=day_in_mouns($mon_st);
echo '<table width="100%">
		  <tr>
			  <td> ';
for($c=0;$c<=11;$c++){
	if($cmn==12) $cmn=0;
	generate_calendar($mouns_name[$cmn], $num_limit, $day_in_mouns_arr[$c], $mouns_color[$c]);
	$num_limit+=$day_in_mouns_arr[$c];
	$cmn++;
}
echo '</td>
		  </tr>
	  </table>';
//echo '<br /><img width="100%">';
/*	
		generate_calendar('Жовтень', 0, 31, '#FFC0CB'); 	// Жовтень
		generate_calendar('Листопад', 31, 30, '#FFA500'); 	// Листопад 
		generate_calendar('Грудень', 61, 31, '#1E90FF');	// Грудень
		generate_calendar('Січень', 92, 31, '#FF0000');		// Січень
		generate_calendar('Лютий', 123, 28, '#1CF1B3');		// Лютий
		generate_calendar('Березень', 151, 31, '#1CBFF1');	// Березень
		generate_calendar('Квітень', 182, 30, '#F11CD7');	// Квітень
		generate_calendar('Травень', 212, 31, '#ACF11C');	// Травень
		generate_calendar('Червень', 243, 30, '#F3BE5D');	// Червень
		generate_calendar('Липень', 273, 31, '#3651F6');	// Липень
		generate_calendar('Серпень', 304, 31, '#36F6E8');	// Серпень
		generate_calendar('Вересень', 335, 30, '#F6BB36');	// Вересень
*/


?>
