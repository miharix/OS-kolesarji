<?php

if($_POST==NULL){
		echo '<form action="izberikt.php" method="POST">';
		echo '<div id="ResetShrani">Geslo: <input type="password" name="GESLO">';
		echo '<button type="submit"  name="Uvozi" value=0>Uvozi</button></div>';
		echo '</form>';
	}else{
		if($_POST['GESLO']=='marica'){

include 'SQL_branje.php';
$steviloKT=SQL_SteviloKontrolnih();

echo '<form action="KT.php" method="post">';
for($KT=1; $KT<=$steviloKT; $KT++){
	echo '<button type="submit"  name="KT" value='.$KT.'>KT'.$KT.'</button>';
	
}
echo '</form>';

			
			
		}}

?>