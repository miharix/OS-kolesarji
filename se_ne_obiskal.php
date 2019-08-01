<?php
include 'SQL_branje.php';

echo " <!DOCTYPE html>\n<HTML>\n<HEAD>\n<title>Še niso obiskali</title>\n";
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<link rel="shortcut icon" href="favicon.ico" />';
echo "<link rel='stylesheet' type='text/css' href='slog.css'>\n</HEAD>\n<BODY>\n";
echo '<div id="tekmovalec">Katere štartne številke še niso obiskale:</div>';



$neobiskali=SQL_NeObiskaliTeorijo();
echo '<div class="ovira">';
echo "Teorijo: ";
echo '<div class="prekrsek" >';
while ($neobiskal = $neobiskali->fetchArray(SQLITE3_NUM) ){
	echo $neobiskal[0].' ';
}
echo '</div>';
echo '</div>';

$neobiskali=SQL_NeObiskaliPoligon();
echo '<div class="ovira">';
echo "Poligon: ";
echo '<div class="prekrsek" >';
while ($neobiskal = $neobiskali->fetchArray(SQLITE3_NUM) ){
	echo $neobiskal[0].' ';
}
echo '</div>';
echo '</div>';

$ST_KT=SQL_SteviloKontrolnih();
$VsehNapak=0;
echo '<div id="kontrolne">';
for($StK=1; $StK<=$ST_KT; $StK++){
	$neobiskali=SQL_NeObiskaliKT($StK);
	echo "Kontrolna točka: ".$StK;
	echo '<div id="kontrolna" >';
	
	while ($neobiskal = $neobiskali->fetchArray(SQLITE3_NUM) ){
		echo $neobiskal[0].' ';
	}
	echo '</div>';
}
echo '</div><div id="dev">Developed by OŠ MK rač. Miha Kočar</div>';



?>