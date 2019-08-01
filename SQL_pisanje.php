<?php
	function SQL_VnesiPosodobiNapakoTKT($StartnaStevilka,$Kontrolna_tocka,$Sifra_prekrska,$Stevilo_prekrskov){//vnesi prekršek tekmovalca na tekontrolni točki v DB ali popravi obstoječi vnos
	//preveri ali je bila ta napaka že vnesena
		$db = new SQLite3('kolesa_vov.sqlite');
		$obstaja= $db->querySingle('SELECT "ID_napake","Stevilo_prekrskov" FROM "Napake_tekmovalcev" WHERE "Startna_stevilka"='.$StartnaStevilka.' AND "Pozicija_KT"='.$Kontrolna_tocka.' AND Sifra_prekrska='.$Sifra_prekrska.';',true);

		if($obstaja==null){
	//če ne obstaja in je štavilo prekrškov 0 ne vstavljaj
			if(($Stevilo_prekrskov>0)){//||($Sifra_prekrska==1 && $Kontrolna_tocka==1)){	//če ni 0 jo vstavi - drity workorund če je šifra prekrška 1 in kontrolna točka 1, shrani eno 0 za hitrješe računanje ranga
				$vstavi=$db->exec('INSERT INTO "Napake_tekmovalcev" ("ID_napake","Sifra_prekrska","Stevilo_prekrskov","Pozicija_KT","Startna_stevilka") VALUES (NULL,'.$Sifra_prekrska.','.$Stevilo_prekrskov.','.$Kontrolna_tocka.','.$StartnaStevilka.');');
			}
		
		}else{
	//je že in če je drugačna jo posodobi
			if($obstaja['Stevilo_prekrskov']!=$Stevilo_prekrskov)
				$vstavi=$db->exec('UPDATE "Napake_tekmovalcev" SET "Stevilo_prekrskov"='.$Stevilo_prekrskov.' WHERE "ID_napake" = '.$obstaja["ID_napake"].';');
		}//sicer ne naredi nič
	
	}
	
	function SQL_ZaključiKT($Kontrolna_tocka){
		$db = new SQLite3('kolesa_vov.sqlite');
		$vstavi=$db->exec('INSERT INTO "Casi_konca_KT" ("ID_spremebe","Pozicija_KT") VALUES (NULL,'.$Kontrolna_tocka.');');
	}
	function SQL_ZaključiPoligon(){
		$db = new SQLite3('kolesa_vov.sqlite');
		$vstavi=$db->exec('INSERT INTO "Casi_konca_KT" ("ID_spremebe","Pozicija_KT") VALUES (NULL,100);');
	}
	
	function SQL_VnesiPosodobiPoligonT($StartnaStevilka,$Ovira,$Sifra_prekrska,$Stevilo_prekrskov){ //vnesi točke tekmovalca na poligonu
		//preveri ali je bila ta napaka že vnesena
		$db = new SQLite3('kolesa_vov.sqlite');
		$obstaja= $db->querySingle('SELECT "ID_prekrska","Stevilo_prekrskov" FROM "Napake_tekmovalcev_poligon" WHERE "Startna_stevilka"='.$StartnaStevilka.' AND "ID_ovire"='.$Ovira.' AND Sifra_prekrska='.$Sifra_prekrska.';',true);

		if($obstaja==null){
	//če ne obstaja in je štavilo prekrškov 0 ne vstavljaj
			if(($Stevilo_prekrskov>0)){//||($Sifra_prekrska==1 && $Ovira==1)){	//če ni 0 jo vstavi - drity workorund če je šifra prekrška 1 in kontrolna točka 1, shrani eno 0 za hitrješe računanje ranga
				$vstavi=$db->exec('INSERT INTO "Napake_tekmovalcev_poligon" ("ID_prekrska","Sifra_prekrska","Stevilo_prekrskov","ID_ovire","Startna_stevilka") VALUES (NULL,'.$Sifra_prekrska.','.$Stevilo_prekrskov.','.$Ovira.','.$StartnaStevilka.');');
			}
		
		}else{
	//je že in če je drugačna jo posodobi
			if($obstaja['Stevilo_prekrskov']!=$Stevilo_prekrskov)
				$vstavi=$db->exec('UPDATE "Napake_tekmovalcev_poligon" SET "Stevilo_prekrskov"='.$Stevilo_prekrskov.' WHERE "ID_prekrska" = '.$obstaja["ID_prekrska"].';');
		}//sicer ne naredi nič
	}
	
	//dodaj novega tekmovalca - preveri SIO_user id za preprečitev duplikatov
	function SQL_dodajTekmovalca($SIO_userid,$SIO_username,$SIO_firstname,$SIO_lastname,$SIO_email,$SIO_institution,$SIO_mentorid,$SIO_mentor){ 
		$db = new SQLite3('kolesa_vov.sqlite');
		$obstaja= $db->querySingle('SELECT "Startna_stevilka" FROM "Tekmovalci" WHERE "SIO_userid"='.$SIO_userid.';',true);
		
		if($obstaja==null){ //če ne obstaja vstavi
			$db = new SQLite3('kolesa_vov.sqlite');
			$vstavi=$db->exec('INSERT INTO "Tekmovalci" ("Startna_stevilka","SIO_userid","SIO_username","SIO_firstname","SIO_lastname","SIO_email","SIO_institution","SIO_mentorid","SIO_mentor") VALUES (NULL,"'.$SIO_userid.'","'.$SIO_username.'","'.$SIO_firstname.'","'.$SIO_lastname.'","'.$SIO_email.'","'.$SIO_institution.'","'.$SIO_mentorid.'","'.$SIO_mentor.'");');
		}
		//var_dump($vstavi);
	}
	
	function SQL_VnesiPosodobiTeorijo($SIO_userid,$SIO_username,$SIO_firstname,$SIO_lastname,$SIO_email,$SIO_institution,$SIO_mentorid,$SIO_mentor,$SIO_kazenske_tocke){
		$db = new SQLite3('kolesa_vov.sqlite');
		$obstaja= $db->querySingle('SELECT "SIO_kazenske_tocke" FROM "Teorija_uvoz" WHERE "SIO_userid"="'.$SIO_userid.'";',true);
		
		if($obstaja==null){ //če ne obstaja vstavi
			//echo 'INSERT INTO "Teorija_uvoz" ("SIO_userid","SIO_username","SIO_firstname","SIO_lastname","SIO_email","SIO_institution","SIO_mentorid","SIO_mentor","SIO_kazenske_tocke") VALUES ("'.$SIO_userid.'","'.$SIO_username.'","'.$SIO_firstname.'","'.$SIO_lastname.'","'.$SIO_email.'","'.$SIO_institution.'","'.$SIO_mentorid.'","'.$SIO_mentor.'","'.$SIO_kazenske_tocke.'");';
			$vstavi=$db->exec('INSERT INTO "Teorija_uvoz" ("SIO_userid","SIO_username","SIO_firstname","SIO_lastname","SIO_email","SIO_institution","SIO_mentorid","SIO_mentor","SIO_kazenske_tocke") VALUES ("'.$SIO_userid.'","'.$SIO_username.'","'.$SIO_firstname.'","'.$SIO_lastname.'","'.$SIO_email.'","'.$SIO_institution.'","'.$SIO_mentorid.'","'.$SIO_mentor.'","'.$SIO_kazenske_tocke.'");');
			
		}else{ 	//je že in če je drugačna jo posodobi
			if($obstaja['SIO_kazenske_tocke']!=$SIO_kazenske_tocke){
				$vstavi=$db->exec('UPDATE "Teorija_uvoz" SET "SIO_kazenske_tocke"="'.$SIO_kazenske_tocke.'" WHERE "SIO_userid"='.$SIO_userid.';');
				}
		}
	}
	
	function SQL_POBRISI_VSE(){
		$db = new SQLite3('kolesa_vov.sqlite');
		$UKAZ="
DELETE FROM 'Casi_konca_KT';
DELETE FROM sqlite_sequence WHERE name='Casi_konca_KT';

DELETE FROM 'Napake_tekmovalcev';
DELETE FROM sqlite_sequence WHERE name='Napake_tekmovalcev';

DELETE FROM 'Napake_tekmovalcev_ADUDIT';
DELETE FROM sqlite_sequence WHERE name='Napake_tekmovalcev_ADUDIT';

DELETE FROM 'Napake_tekmovalcev_poligon';
DELETE FROM sqlite_sequence WHERE name='Napake_tekmovalcev_poligon';

DELETE FROM 'Napake_tekmovalcev_poligon_ADUDIT';
DELETE FROM sqlite_sequence WHERE name='Napake_tekmovalcev_poligon_ADUDIT';

DELETE FROM 'Tekmovalci';
DELETE FROM sqlite_sequence WHERE name='Tekmovalci';

DELETE FROM 'Teorija_uvoz';
DELETE FROM sqlite_sequence WHERE name='Teorija_uvoz';

VACUUM;";
		$vstavi=$db->exec($UKAZ);
		return $vstavi;
	}

?>