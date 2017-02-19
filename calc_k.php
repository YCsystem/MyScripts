<?php
// Loading the config and function

// include 'config.php';
// include 'func.php';
function clear($message){ 
$message=str_replace("|","I",$message); 
$message=str_replace("||","I",$message);
$message=str_replace("&","",$message);
$message=str_replace("\"","",$message);
$message=str_replace(">","",$message);
$message=str_replace("<","",$message);
$message=str_replace("'","",$message);
$message=str_replace("^","",$message);
$message=str_replace("*","",$message);
$message=str_replace("/","",$message);
$message=str_replace("(","",$message);
$message=str_replace(")","",$message);
$message=str_replace("-","",$message);
$message=str_replace("+","",$message);
$message=str_replace("\"","",$message);
$message=str_replace("\$","",$message);
$message=str_replace("$","",$message);
$message=str_replace("\\","", $message);
$message=str_replace("`","", $message);
$message=str_replace("%","", $message);
return $message; }
// 
if ($_GET[func]=='do'){
	if (isset($_POST[x]) and $_POST[x]>0) $err=0; else $err=1;
	if (isset($_POST[y]) and $_POST[y]>0) $err=0; else $err=1;
	if (isset($_POST[z]) and $_POST[z]>0) $err=0; else $err=1;
	if (isset($_POST[typ]) and ($_POST[typ]=='a' or $_POST[typ]=='b' or $_POST[typ]=='c')) $err=0; else $err=1;
	if ($err!=1){
		$x=clear($_POST[x]);
		$y=clear($_POST[y]);
		$z=clear($_POST[z]);
		if ($_POST[typ]=='a'){
			$typ[x]=600; 
			$typ[y]=300;
			$typ[z]=200;
			$typ[cena]=clear($_POST[cena_a]);
		}
		if ($_POST[typ]=='b'){
			$typ[x]=600;
			$typ[y]=300;
			$typ[z]=100;
			$typ[cena]=clear($_POST[cena_b]);
		}
		if ($_POST[typ]=='c'){
			$typ[x]=400;
			$typ[y]=200;
			$typ[z]=200;
			$typ[cena]=clear($_POST[cena_c]);
		}
		$kol=round(round((($x+$y)*2)/$typ[x],1)*round($z/$typ[z],1),0);
		$cena=$kol*$typ[cena];
		$resault=1;
		
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Made by YCsystem</title>
<META HTTP-EQUIV="EXPIRES" CONTENT="Mon, 22 Jul 2002 11:12:01 GMT">
<META HTTP-EQUIV="Last-Modified" CONTENT="<?php echo gmdate("D, d M Y H:i:s");?> GMT">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-store, no-cache, must-revalidate">
    <META HTTP-EQUIV="Cache-Control" CONTENT="post-check=0, pre-check=0">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META NAME="ROBOTS" CONTENT="ALL"> 
  <meta name="Description" content="Росчёт количества блоков">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>body {
background:#000 url(game/berloga/image/wood.jpg) repeat fixed center top;
 margin: 15px 0px 20px 0px;

 font: 12px Tahoma, Arial, Verdana, Helvetica;
 color: #272727;

 } 
.calc_k {
padding: 10px;
margin-top: 100px;
margin-left: auto;
margin-right: auto;
width: 350px;
background:#F0FF85;
	border-radius: 10px;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	-moz-box-shadow: 0px 0px 40px #000000;
-webkit-box-shadow: 0px 0px 40px #000000;
box-shadow: 0px 0px 40px #000000;
}
  .tr_1 {
	background: #FFFACB;
}
  .tr_2 {
background:#E4E4E4;
}
</style>
</head>
<body>
<div class="calc_k">
<form action="?func=do" method="POST">
<table width="100%" style="" border="1">
<tbody>
<tr class="tr_1"><td>Длина:</td><td><input type="text" size="3" name="x" value="<?php echo $x;?>">мм</td></tr>
<tr class="tr_2"><td>Ширина:</td><td><input type="text" size="3" name="y" value="<?php echo $y;?>">мм</td></tr>
<tr class="tr_1"><td>Высота:</td><td><input type="text" size="3" name="z" value="<?php echo $z;?>">мм </td></tr>
<tr class="tr_2"><td colspan="2">Тип:</td></tr>
<tr class="tr_1"><td>Кирпич "600x300x200":</td><td><input type="radio" size="3" name="typ" value="a">  цена: <input type="text" size="3" name="cena_a" value="<?php if (empty($_POST[cena_a])) echo '20'; else echo clear($_POST[cena_a]);?>"> грн</td></tr>
<tr class="tr_2"><td>Кирпич "600x300x100":</td><td><input type="radio" size="3" name="typ" value="b">  цена: <input type="text" size="3" name="cena_b" value="<?php if (empty($_POST[cena_b])) echo '10'; else echo clear($_POST[cena_b]);?>"> грн</td></tr>
<tr class="tr_1"><td>Кирпич "400x200x200":</td><td><input type="radio" size="3" name="typ" active value="c">  цена: <input type="text" size="3" name="cena_c" value="<?php if (empty($_POST[cena_c])) echo '8'; else echo clear($_POST[cena_c]);?>"> грн</td></tr>
<tr class="tr_2"><td colspan="2"><input type="submit" value="Подсчитать"></td></tr>
<tbody><table>
<?php
if ($resault==1){
	echo '<p>Для стены <br>длинной - '.$x.'мм<br>шириной - '.$y.'мм<br>высотой - '.$z.'мм</p>';
	echo '<p>Вам нужно "'.$kol.'" блоков размером ("'.$typ[x].'x'.$typ[y].'x'.$typ[z].'")</p>';
	echo '<p>Цена:   "'.$cena.'грн"</p>';
}
?>
</div>
</body>
</html>
