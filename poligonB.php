<?php
include 'SQL_branje.php';
include 'SQL_pisanje.php';

if(isset($_POST["startna"])){
if($_POST["startna"]>0){
		$StartnaStevilka=$_POST["startna"];
		if(isset($_POST["gumb"])){
			if(is_numeric($_POST["gumb"])){
				$StartnaStevilka+=$_POST["gumb"];
			}else{$zakluciKT=true;}
		}
	}

}else{
$StartnaStevilka=1;
}

if(isset($_POST["startna"])){
if($_POST["startna"]>0 && isset($_POST["gumb"])){
$shranjeval=false;
foreach($_POST as $key => $value)
{
	if($key[0]=="O"){
	//	echo 'Key = ' . $key ;
	//	echo '  Value= ' . $value. '<br />';
		//če je ključ napaka poizkusi shranit
		//echo (int)substr($key,1,2).'-'.(int)substr($key,4).'='.$value.' ';
		SQL_VnesiPosodobiPoligonT($_POST["startna"],(int)substr($key,1,2),(int)substr($key,4),$value);
		$shranjeval=true;
	}
}
}
}

echo "<!DOCTYPE html>\n<HTML>\n<HEAD>\n<title>Tekmovanje - Poligon B</title>\n";
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<link rel="shortcut icon" href="favicon.ico" />';
echo "<link rel='stylesheet' type='text/css' href='slog.css'>\n
<link rel='stylesheet' type='text/css' href='spinner.css'>\n
</HEAD>\n<BODY>\n";
echo '<script type="text/javascript" src="jsskripte.js"></script>';
echo '<div id="naslov">Poligon B</div>';

//if(!$zakluciKT && !$ZeZakljucenaKT){

echo '<form action="poligonB.php" method="POST">';
echo '<div id="ResetShrani"><button type="reset">Razveljavi</button><button type="submit"  name="gumb" value=0>Shrani</button></div>';
echo '<div id="tekmovalec">Tekmovalec:';
echo '<select name="startna" onchange="this.form.submit()">';


$MaxTekmovalcev=SQL_maxTekm();
for($tekm=1; $tekm<=$MaxTekmovalcev; $tekm++){
	if($tekm==$StartnaStevilka){
		echo '<option value="'.$tekm.'" selected >'.$tekm.'</option>';
	}else{
		echo '<option value="'.$tekm.'">'.$tekm.'</option>';
	}
}
echo '</select></div><div id="naprejnazaj">';



if($StartnaStevilka>1)
echo ' <button type="submit" name="gumb" value=-1>Prejšnji</button>';
else
echo ' <button type="submit" disabled name="gumb" value=-1>Prejšnji</button>';



if($StartnaStevilka<$MaxTekmovalcev){
echo '<button type="submit"  name="gumb" value=1 autofocus>Naslednji</button></div>';}
else{
echo '<button type="submit" disabled  name="gumb" value=1 autofocus>Naslednji</button></div>';
echo '<div id="zakljuciKT"><button type="submit"  name="gumb" value="ZK" autofocus>ZAKLJUČI POLIGON</button></div>';
}

$SPrekrskiPoligona=SQL_NapakePoligonT($StartnaStevilka);
/*while ($SPrekrsekPoligona = $SPrekrskiPoligona->fetchArray(SQLITE3_NUM) ){
	echo $SPrekrsekPoligona[0].$SPrekrsekPoligona[1].$SPrekrsekPoligona[2];
}*/
				if(SQL_PoligonBrezNapak($StartnaStevilka)){
					echo '<div class="ovira">Obiskal poligon in bil brez napak <input  type="hidden" value="0" name="O0"><input type="checkbox" value="1" name="O0" checked="checked"></div>';
				}else{
					echo '<div class="ovira">Obiskal poligon in bil brez napak <input  type="hidden" value="0" name="O0"><input type="checkbox" value="1" name="O0"></div>';
				}

$OvirePoligona=SQL_PoligonOvireImenaB();
$PoligonPrekrski=SQL_PoligonPrekrskiImena();
$OviraNum=1;
while ($OviraPoligona = $OvirePoligona->fetchArray(SQLITE3_NUM) ){
		echo '<div class="ovira">';
		echo '<div class="ovira_ime"  onclick="skrij('.$OviraNum.')">'.$OviraNum.'. '.$OviraPoligona[0].'</div>';
		echo '<div id="prekrskiOvire-'.$OviraNum.'">';
		while ($PoligonPrekrsek = $PoligonPrekrski->fetchArray(SQLITE3_NUM) ){
			$OShranjena=false;
			while ($SPrekrsekPoligona = $SPrekrskiPoligona->fetchArray(SQLITE3_NUM) ){
			//	echo $SPrekrsekPoligona[0].$SPrekrsekPoligona[1].$SPrekrsekPoligona[2];
				if(($SPrekrsekPoligona[0]==$OviraPoligona[1])&&($SPrekrsekPoligona[1]==$PoligonPrekrsek[0])){ //nekaj je shranjeno
					echo '<div class="prekrsek c-spinner" data-js-spinner><button type="button" class="c-spinner__button c-spinner__button--decrement" data-js-spinner-decrement>-</button>';
					echo '<input type="number" min=0 max=9 value="'.$SPrekrsekPoligona[2].'" name="O'.str_pad($OviraPoligona[1], 2, '0', STR_PAD_LEFT).'P'.$PoligonPrekrsek[0].'" id="spinner-neutral" class="c-spinner__input" autofocus data-js-spinner-input>';
					echo '<button type="button" class="c-spinner__button c-spinner__button--increment" data-js-spinner-increment>+</button>'.$PoligonPrekrsek[1].'</div>';
					$OShranjena=true;
				}
			}
			if(!$OShranjena){
			echo '<div class="prekrsek c-spinner" data-js-spinner><button type="button" class="c-spinner__button c-spinner__button--decrement" data-js-spinner-decrement>-</button>';
			echo '<input type="number" min=0 max=9 value=0 name="O'.str_pad($OviraPoligona[1], 2, '0', STR_PAD_LEFT).'P'.$PoligonPrekrsek[0].'" id="spinner-neutral" class="c-spinner__input" autofocus data-js-spinner-input>';
			echo '<button type="button" class="c-spinner__button c-spinner__button--increment" data-js-spinner-increment>+</button>'.$PoligonPrekrsek[1].'</div>';
			}
		}
		echo '</div>';
		echo '</div>';
		$OviraNum++;
}





echo "</BODY>\n</HTML>";


?>