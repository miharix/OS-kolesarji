<?php

include 'cache_glava.php';

echo " <!DOCTYPE html>\n<HTML>\n<HEAD>\n<title>Rang lista</title>\n";
echo '<meta http-equiv="refresh" content="300" >';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<link rel="shortcut icon" href="favicon.ico" />';
echo "<link rel='stylesheet' type='text/css' href='slog.css'>\n</HEAD>\n<BODY>\n";


include 'SQL_branje.php';
$RangLista=SQL_RangLista();
$Mesto=1;
$prejsni_tock=-1;


echo "<div id='naslov'>Regijsko tekmovanje \"Kaj veš o prometu\" na OŠ Martina Krpana LJ</div>\n";

if (SQL_SteviloKontrolnih()+1!=SQL_SteviloZKT()){//+1zaradi poligona
	echo '<div id="opozorilo">Tekmovanje še poteka - Seznam še ni končen</div>';
}

echo "\n<div id='mesta'>\n";
while ($TMesto = $RangLista->fetchArray(SQLITE3_NUM) ){
				if(($prejsni_tock>$TMesto[1])&&($prejsni_tock!=-1)){
				$Mesto++;
				}
				$prejsni_tock=$TMesto[1];
				
				if($TMesto[1]<=-9000){$TMesto[1]='/';};
				if($TMesto[2]<=-9000){$TMesto[2]='/';};
				if($TMesto[3]<=-9000){$TMesto[3]='/';};
				if($TMesto[4]<=-9000){$TMesto[4]='/';};
				echo '<div id="mesto'.$Mesto.'" class="mesto">'.$Mesto.'. mesto - Št.š. '.$TMesto[0].'   '.$TMesto[1].' točk <div class="tockeM">Poligon: '.$TMesto[2].' Teorija: '.$TMesto[3].' Trasa: '.$TMesto[4].'</div></div>';
				
				
		}
		
		
echo "\n</div><div id='dev'>\"http://kolesarji.vov.si\"  Developed by OŠ MK rač. Miha Kočar</div>\n";

echo "</BODY>\n</HTML>";

include 'cache_noga.php';
?>