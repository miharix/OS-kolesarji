<?php

include 'cache_glava.php';

echo " <!DOCTYPE html>\n<HTML>\n<HEAD>\n<title>Rang tabela</title>\n";
echo '<meta http-equiv="refresh" content="300" >';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<link rel="shortcut icon" href="favicon.ico" />';
//echo "<link rel='stylesheet' type='text/css' href='slog.css'>\n</HEAD>\n<BODY>\n";



include 'SQL_branje.php';
$RangLista=SQL_RangLista();
$Mesto=1;
$prejsni_tock=-1;

echo '<div id="top"></div>';
echo "<div id='naslov'>Regijsko tekmovanje \"Kaj veš o prometu\" na OŠ Martina Krpana LJ</div>\n";

if (SQL_SteviloKontrolnih()+1!=SQL_SteviloZKT()){//+1zaradi poligona
	echo '<div id="opozorilo">Tekmovanje še poteka - Seznam še ni končen</div>';
}



echo "\n<TABLE border='1'>\n";
echo '<TH>MESTO</TH><TH>Štartna</TH><TH>SKUPAJ</TH><TH>POLIGON</TH><TH>TEORIJA</TH><TH>TRASA</TH><TH>IME</TH><TH>PRIIMEK</TH><TH>ŠOLA</TH>';//<TH>MENTOR/ICA</TH>';
while ($TMesto = $RangLista->fetchArray(SQLITE3_NUM) ){
	echo '<TR>';
				if(($prejsni_tock>$TMesto[1])&&($prejsni_tock!=-1)){
				$Mesto++;
				}
				$prejsni_tock=$TMesto[1];
				if($TMesto[1]<=-9000){$TMesto[1]='';};
				if($TMesto[2]<=-9000){$TMesto[2]='';};
				if($TMesto[3]<=-9000){$TMesto[3]='';};
				if($TMesto[4]<=-9000){$TMesto[4]='';};
				echo '<TD>'.$Mesto.'</TD><TD>'.$TMesto[0].'</TD><TD>'.$TMesto[1].'</TD><TD>'.$TMesto[2].'</TD><TD>'.$TMesto[3].'</TD><TD>'.$TMesto[4].'</TD><TD>'.$TMesto[5].'</TD><TD>'.$TMesto[6].'</TD><TD>'.$TMesto[7].'</TD>';//<TD>'.$TMesto[8].'</TD>';
				
				//$prejsni_tock=$TMesto[1];
	echo '</TR>';
		}
		
		
echo "\n</TABLE><div id='dev'>Izpisano: ".date("d. m. Y H:i:s") ."</div>\n";
echo '<div id="bottom"></div>';
echo "</BODY>\n</HTML>";

include 'cache_noga.php';
?>