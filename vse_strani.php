<?php

echo "<!DOCTYPE html>\n<HTML>\n<HEAD>\n<title>Tekmovanje centrala</title>\n";
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<link rel="shortcut icon" href="favicon.ico" />';
echo "</HEAD>\n<BODY>\n";

echo '
<b>Sistemske:</b><br>
<ol>
<li><a href="zacni-tekmovanje.php">Pobriši bazo</a></li>
<li><a href="uvozi_sio.php">Uvozi tekmovalce</a></li>
<li><a href="startni_listki.php">Štartni listki z QR kodo</a></li>
<li><a href="uvozi_sio_teorija.php">Uvozi teorijo iz SIO</a></li>
</ol>
<hr>
<b>Potrdila:</b><br>
<ol>
<li><a href="potrdila_tekmovalci.php">Tekmovalci </a></li>
<li><a href="potrdila_mentorji.php">Mentorji - NI KONČANO </a></li>
</ol>
<hr>
<b>Javne:</b><br>
<ol>
<li><a href="se_ne_obiskal.php">Kdo še ni obiskal česa</a></li>
<li><a href="/">Rang Lista</a></li>
<li><a href="/rang_tabela.php">Rang Lista - TABELA</a></li>
<li><a href="tekmovalec.php">Statistika za tekmovalca</a></li>
</ol>
<hr>
<b>Ocenjevalci na terenu:</b><br>
<ol>
<li><a href="poligonA.php">Poligon A</a></li>
<li><a href="poligonB.php">Poligon B</a></li>
<li><a href="izberikt.php">Izberi Kontrolno točko</a></li>
</ol>
';
echo "</BODY>\n</HTML>";
?>