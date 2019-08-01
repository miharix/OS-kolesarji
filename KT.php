<?php 
/*ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);*/

include 'SQL_branje.php';
include 'SQL_pisanje.php';


$Kontrolna_tocka=-1;

if(isset($_POST["KT"])){
	$Kontrolna_tocka=$_POST["KT"];
	setcookie("KT", $Kontrolna_tocka, time()+48*3600); //piškotek - poteče čez 48ur
}else{
//	$Kontrolna_tocka=1;
	if(isset($_COOKIE["KT"])){
	$Kontrolna_tocka=$_COOKIE["KT"];
	}
}

if($Kontrolna_tocka!=-1){




$ZeZakljucenaKT=SQL_JeKTZakljucena($Kontrolna_tocka);
$zakluciKT=false;

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
	if($key[0]=="N"){
	//	echo 'Key = ' . $key ;
	//	echo '  Value= ' . $value. '<br />';
		//če je ključ napaka poizkusi shranit
		SQL_VnesiPosodobiNapakoTKT($_POST["startna"],$Kontrolna_tocka,(int)substr($key,1),$value);
		$shranjeval=true;
	}
}



}
}

//preveri če katere napake že so hranjene
$ShranjeneN=SQL_NapakeTnaKT($StartnaStevilka,$Kontrolna_tocka);

/*	while ($ShranjenaN = $ShranjeneN->fetchArray(SQLITE3_NUM) ){
		echo  $ShranjenaN[2].'x'.$ShranjenaN[3].' ['.$ShranjenaN[0].'] - '.$ShranjenaN[1].'<br>';
	}*/
echo "<!DOCTYPE html>\n<HTML>\n<HEAD>\n<title>Tekmovanje KT". $Kontrolna_tocka."</title>\n";
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<link rel="shortcut icon" href="favicon.ico" />';
echo "<link rel='stylesheet' type='text/css' href='slog.css'>\n
<link rel='stylesheet' type='text/css' href='spinner.css'>\n
</HEAD>\n<BODY>\n";
echo '<div id="naslov">KT'. $Kontrolna_tocka.'</div>';

if(!$zakluciKT && !$ZeZakljucenaKT){

echo '<form action="KT.php" method="POST">';
echo '<input type="hidden" name="KT" value='.$Kontrolna_tocka.'>';
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
//else
//echo ' <button type="submit" disabled name="gumb" value=-1>Prejšnji</button>';



if($StartnaStevilka<$MaxTekmovalcev){
echo '<button type="submit"  name="gumb" value=1 autofocus>Naslednji</button></div>';}
else{
	echo '</div>';
//echo '<button type="submit" disabled  name="gumb" value=1 autofocus>Naslednji</button></div>';
echo '<div id="zakljuciKT"><button type="submit"  name="gumb" value="ZK" autofocus>ZAKLJUČI KONTROLNO TOČKO</button></div>';
}





/*
if(!SQL_ZeShranjen($StartnaStevilka,$Kontrolna_tocka)){
	echo "še ni nič shranil";
}else{
	echo "že shranjeni prekrški";
}*/
$da_nasel=false;
		if($ShranjeneN!=null){ //že ima napake shranjene - preveri če je ta shranjena
			while ($ShranjenaN = $ShranjeneN->fetchArray(SQLITE3_NUM) ){
				if($ShranjenaN[0]==0){
					echo '<div id="idealni">Obiskal točko in bil brez napak <input type="hidden" value="0" name="N0"><input type="checkbox" value="1" name="N0" checked="checked"></div>';
					$da_nasel=true;
					break;
				}
			}
		}
		if(!$da_nasel){
			echo '<div id="idealni">Obiskal točko in bil brez napak <input type="hidden" value="0" name="N0"><input type="checkbox" value="1" name="N0"></div>';
		}



echo '<div id="pricnapake">Pričakovane napake:';

$PricakovaneN_KT = SQL_PricakovaneN_KT($Kontrolna_tocka);

if($PricakovaneN_KT != ''){
	while ($PricakovanaN_KT = $PricakovaneN_KT->fetchArray(SQLITE3_NUM) ){
		$da_nasel=false;
		if($ShranjeneN!=null){ //že ima napake shranjene - preveri če je ta shranjena
			while ($ShranjenaN = $ShranjeneN->fetchArray(SQLITE3_NUM) ){
				if($PricakovanaN_KT[1]==$ShranjenaN[0]){
					echo '<div class="pricnapaka c-spinner" data-js-spinner><button type="button" class="c-spinner__button c-spinner__button--decrement" data-js-spinner-decrement>-</button>';
					echo '<input type="number" min=0 max=9 value='.$ShranjenaN[2].' name="N'.$ShranjenaN[0].'" id="spinner-neutral" class="c-spinner__input" autofocus data-js-spinner-input>';
					echo '<button type="button" class="c-spinner__button c-spinner__button--increment" data-js-spinner-increment>+</button>'.$PricakovanaN_KT[0].'</div>';
					
					$da_nasel=true;
					break;
				}
			}
		}
		if(!$da_nasel){
			echo '<div class="pricnapaka c-spinner" data-js-spinner><button type="button" class="c-spinner__button c-spinner__button--decrement" data-js-spinner-decrement>-</button>';
			echo '<input type="number" min=0 max=9 value=0 name="N'.$PricakovanaN_KT[1].'" id="spinner-neutral" class="c-spinner__input" autofocus data-js-spinner-input>';
			echo '<button type="button" class="c-spinner__button c-spinner__button--increment" data-js-spinner-increment>+</button>'.$PricakovanaN_KT[0].'</div>';
		}
	}
	}else{echo "nič";}
	

	echo '</div>';


echo '<div id="ostnapake">Ostale možne napake:';
	
$OstaleN_KT = SQL_OstaleN_KT($Kontrolna_tocka);

if($OstaleN_KT != ''){
	while ($OstalaN_KT = $OstaleN_KT->fetchArray(SQLITE3_NUM) ){
		
		$da_nasel=false;
		if($ShranjeneN!=null){ //že ima napake shranjene - preveri če je ta shranjena
			while ($ShranjenaN = $ShranjeneN->fetchArray(SQLITE3_NUM) ){
				if($OstalaN_KT[1]==$ShranjenaN[0]){
					echo '<div class="ostnapaka c-spinner" data-js-spinner><button type="button" class="c-spinner__button c-spinner__button--decrement" data-js-spinner-decrement>-</button>';
					echo '<input type="number" min=0 max=9 value='.$ShranjenaN[2].' name="N'.$OstalaN_KT[1].'" id="spinner-neutral" class="c-spinner__input" autofocus data-js-spinner-input>';
					echo '<button type="button" class="c-spinner__button c-spinner__button--increment" data-js-spinner-increment>+</button>'.$OstalaN_KT[0].'</div>';
					$da_nasel=true;
					break;
				}
			}
		}
		if(!$da_nasel){
			echo '<div class="ostnapaka c-spinner" data-js-spinner><button type="button" class="c-spinner__button c-spinner__button--decrement" data-js-spinner-decrement>-</button>';
			echo  '<input type="number" min=0 max=9 value=0 name="N'.$OstalaN_KT[1].'" id="spinner-neutral" class="c-spinner__input" autofocus data-js-spinner-input>';
			echo '<button type="button" class="c-spinner__button c-spinner__button--increment" data-js-spinner-increment>+</button>'.$OstalaN_KT[0].'</div>';
		}
		
		
		
		
		
		
	}
	}else{echo "nič";}
		echo '</div>';
echo '</form>';	


	
$Fotke_KT = SQL_Foto_KT($Kontrolna_tocka);

if($Fotke_KT != ''){
	echo  '<div id="fotke">';
	$FotoNum=1;
	while ($Foto_KT = $Fotke_KT->fetchArray(SQLITE3_NUM) ){
		echo '<div class="fotka"><a href="/foto/'.$Foto_KT[0].'" target="_BLANK">Foto '.$FotoNum.'</a></div>';
		$FotoNum++;
	}
	echo  '</div>';
	}else{echo "nič";}
	
}else{
	//zaključi kontrolno točko
	if(!$ZeZakljucenaKT){
		SQL_ZaključiKT($Kontrolna_tocka);
	}
	echo 'Kontrolna točka zaključena.<br><br>SEDAJ ZAPRITE BRSKALNIK<br><br>Hvala za sodelovanje!';
	
}
echo '<script src="spinner.js"></script>';
echo "</BODY>\n</HTML>";

}
?>