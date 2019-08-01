<?php
include 'SQL_pisanje.php';

echo "<!DOCTYPE html>\n<HTML>\n<HEAD>\n<title>Tekmovanje - pobriši bazo</title>\n";
echo '<link rel="shortcut icon" href="favicon.ico" />';
echo "<link rel='stylesheet' type='text/css' href='slog.css'>\n</HEAD>\n<BODY>\n";
echo '<div id="naslov">pobriši bazo</div>';

if($_POST==NULL){

echo '<form action="zacni-tekmovanje.php" method="POST">';
echo '<div id="ResetShrani">Geslo: <input type="password" name="GESLO">';
echo '<button type="submit"  name="RESETIRAJ" value=0>RESETIRAJ</button></div>';
echo '</form>';

}else{
	if($_POST['GESLO']=='marica'){
		echo "Pravilno geslo brišem vsebino baze<br>";
		if(SQL_POBRISI_VSE()){
			echo "Baza pobrisana - TEKMOVANJE SE LAHKO ZAČNE";
		}else{
			echo "!!! NEZNANA NAPAKA !!! BAZA NI POBRISANA !!!";
		}
	}
}


echo "</BODY>\n</HTML>";

?>