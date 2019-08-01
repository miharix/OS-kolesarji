<?php

include 'cache_glava.php';

include 'SQL_branje.php';

if(isset($_GET["startna"]) && isset($_GET["gumb"])){
if($_GET["startna"]>0){
		$StartnaStevilka=$_POST["startna"]+$_GET["gumb"];
	}

}else{
	if(isset($_GET["startna"])){
		
		$StartnaStevilka=$_GET["startna"];
	}else{
		$StartnaStevilka=1;
	}
}

$MaxTekmovalcev=SQL_maxTekm();
if(!is_numeric($StartnaStevilka)&&($StartnaStevilka>$MaxTekmovalcev)&&($StartnaStevilka<0)){
	$StartnaStevilka=1;
}

echo " <!DOCTYPE html>\n<HTML>\n<HEAD>\n<title>Štartna številka ". $StartnaStevilka."</title>\n";
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<link rel="shortcut icon" href="favicon.ico" />';
echo "<link rel='stylesheet' type='text/css' href='slog.css'>\n</HEAD>\n<BODY>\n";

echo '<form action="tekmovalec.php" method="GET">';
echo '<div id="tekmovalec">Tekmovalec/ka štartna številka ';

echo $StartnaStevilka.'</div>';
/* //spustni meni kjer se lahko spremeni štartna številka 
echo '<select name="startna" onchange="this.form.submit()">';


for($tekm=1; $tekm<=$MaxTekmovalcev; $tekm++){
	if($tekm==$StartnaStevilka){
		echo '<option value="'.$tekm.'" selected >'.$tekm.'</option>';
	}else{
		echo '<option value="'.$tekm.'">'.$tekm.'</option>';
	}
}
echo '</select></div>';
*/

/* //gumba prejšnji/naslednji
if($StartnaStevilka>1)
echo ' <button type="submit" name="gumb" value=-1>Prejšnji</button>';
else
echo ' <button type="submit" disabled name="gumb" value=-1>Prejšnji</button>';

if($StartnaStevilka<$MaxTekmovalcev)
echo '<button type="submit"  name="gumb" value=1>Naslednji</button>';
else
echo '<button type="submit" disabled  name="gumb" value=1>Naslednji</button>';

echo '</form>';*/

$ST_KT=SQL_SteviloKontrolnih();
$VsehNapak=0;
echo '<div id="kontrolne">';
for($StK=1; $StK<=$ST_KT; $StK++){
	$Napak=0;
	$InfoKT=SQL_PodatkiOKT($StK);
	echo '<div id="kontrolna" ><div id="kontrolna_naziv">Kontrolna točka '.$StK.': '.$InfoKT['Opis_KT'];
	if($InfoKT['Nadzornik']!='') echo '<small>(nadzornik: '.$InfoKT['Nadzornik'].')</small>';
	echo '</div>';
/*	echo 'Obiskal: ';
	echo SQL_ZadnjiVnosiKT($StartnaStevilka,$StK) ? 'Da' : 'Ne';
	echo '<br>';*/
	echo '<div id="napake">Napake na kontrolni točki:';
	$NaredilVseNarobe=SQL_NapakeTnaKT($StartnaStevilka,$StK);
	if($NaredilVseNarobe != ''){
	while ($NaredilNarobe = $NaredilVseNarobe->fetchArray(SQLITE3_NUM) ){
		echo  '<div id="napaka">'.$NaredilNarobe[2].'x'.$NaredilNarobe[3].' ['.$NaredilNarobe[0].'] - '.$NaredilNarobe[1].'</div>';
		$Napak+=$NaredilNarobe[2]*$NaredilNarobe[3];
	}
	}else{echo "nič";}
	
	if($Napak==0){
	echo '<div id="napake0">Skupaj: '.$Napak.'</div>';
	}else{
	echo '<div id="napakeT">Skupaj: '.$Napak.'</div>';	
	}
	$VsehNapak+=$Napak;
	echo '</div></div>';
	
}

$ST_Ovir=SQL_SteviloOvir();
for($StO=1; $StO<=$ST_Ovir; $StO++){
	$InfoOviri=SQL_PodatkiOOviri($StO);
echo '<div id="poligonN">';
echo '<div id="kontrolna" ><div id="kontrolna_naziv">Ovira : <a href="/foto/ovire/'.$InfoOviri["foto_ovire"].'">'.$InfoOviri['Ime_ovire'].'</a></div>';
echo '<div id="napake">Napake na oviri:';
$Napak=0;
$NaredilVseNarobeOvira=SQL_NapakeTnaOviri($StartnaStevilka,$StO);
while ($NaredilNarobeOvira = $NaredilVseNarobeOvira->fetchArray(SQLITE3_NUM) ){
	echo  '<div id="napaka">'.$NaredilNarobeOvira[2].'x'.$NaredilNarobeOvira[3].' - '.$NaredilNarobeOvira[1].'</div>';
		$Napak+=$NaredilNarobeOvira[2]*$NaredilNarobeOvira[3];
}
if($Napak==0){
	echo '<div id="napake0">Skupaj: '.$Napak.'</div>';
	}else{
	echo '<div id="napakeT">Skupaj: '.$Napak.'</div>';	
	}
	$VsehNapak+=$Napak;
echo '</div>';
echo '</div>';
}

echo '<div id="poligonN">';
echo '<div id="kontrolna" ><div id="kontrolna_naziv">Rezultati teorije</div>';
echo '<div id="napake">';
$Tock=SQL_NapakIzTeorijeTekmovalca($StartnaStevilka);
if($Tock==0){
	echo '<div id="napake0">Napak: '.$Tock.'</div>';
	}else{
	echo '<div id="napakeT">Napak: '.$Tock.'</div>';	
	}
	$VsehNapak+=$Tock;
echo '</div>';
echo '</div>';

echo "</div>\n<div id='napakeSkup'>Vse napake skupaj: ".$VsehNapak.'</div><div id="dev">Developed by OŠ MK rač. Miha Kočar</div>';
echo "</BODY>\n</HTML>";


include 'cache_noga.php';

?>