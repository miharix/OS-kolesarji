<?php
	function SQL_PricakovaneN_KT($KT){ //vrne pričakovane napake na kontrolni točki
		$db = new SQLite3('kolesa_vov.sqlite');
		//$results = $db->query('SELECT "Opis_prekrska", "Sifra_prekrska"  FROM "Prekrski" WHERE "Sifra_prekrska" IN ( SELECT "Sifra_prekrska" FROM "KT_pricak_napak" WHERE "Pozicija_KT"='.$KT.') ORDER BY "zaporedje_prikaza" ASC;');
		$results = $db->query('SELECT "Opis_prekrska", "KT_pricak_napak"."Sifra_prekrska" FROM  "KT_pricak_napak"   INNER JOIN "Prekrski" ON "KT_pricak_napak"."Sifra_prekrska" = "Prekrski"."Sifra_prekrska" where	Pozicija_KT='.$KT.' ORDER BY zaporedje_prikaza ASC;');
		return $results;
	}
	
	function SQL_OstaleN_KT($KT){ //vrne nepricakovane napake na kontrolni točki
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->query('SELECT "Opis_prekrska", "KT_p_ost_napak"."Sifra_prekrska" FROM  "KT_p_ost_napak"   INNER JOIN "Prekrski" ON "KT_p_ost_napak"."Sifra_prekrska" = "Prekrski"."Sifra_prekrska" where Pozicija_KT='.$KT.' ORDER BY zaporedje_prikaza ASC;');
		return $results;
	}
	
	function SQL_OstaleN_KT2($KT){ //vrne vse napake ki niso znotraj pričakovanih na kontrolni točki
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->query('SELECT "Opis_prekrska", "Sifra_prekrska"  FROM "Prekrski" WHERE "Sifra_prekrska" NOT IN ( SELECT "Sifra_prekrska" FROM "KT_pricak_napak" WHERE "Pozicija_KT"='.$KT.') AND "Sifra_prekrska" != 0 ORDER BY "Sifra_prekrska" ASC;');
		return $results;
	}
	
	
	function SQL_Foto_KT($KT){ // vrne ime datoteke fotografije lokacije
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->query('SELECT "Datoteka" FROM "Fotografije" WHERE "Pozicija_KT" = '.$KT.';');
		return $results;
	}
	
	function SQL_maxTekm(){ //vrne število tekmovalcev
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->querySingle('SELECT COUNT("Startna_stevilka") FROM "Tekmovalci"');
		return $results;
	}
	
	function SQL_ZeShranjen($startna,$KT){ //vnre true če ima tekmovalec že kake shranjene prekrške
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->querySingle('SELECT COUNT ("ID_napake") FROM "Napake_tekmovalcev" WHERE "Startna_stevilka"='.$startna.' AND "Pozicija_KT" ='.$KT.';');
		if ((int)$results>0){
			return true;
		}else{
			return false;
		}
	}
	
	function SQL_ZadnjiVnosiKT($startna,$KT){ //vrne ce je tekmovalec ze bil vnesen v kontrlni točki
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->querySingle('SELECT "Cas_spremembe" FROM "Casi_shranjevanja" WHERE "Pozicija_KT"='.$KT.' AND "Startna_stevilka" ='.$startna.';');
		if ($results!=null){
			return true;
		}else{
			return false;
		}
	}
	
	function SQL_SteviloKontrolnih(){ //vne koliko kontrolnih točk sploh je
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->querySingle('SELECT COUNT("ID_KT") FROM "KT_opis";');
		return $results;
	}
	function SQL_SteviloOvir(){ //vne koliko ovir sploh je
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->querySingle('SELECT COUNT("ID_ovire") FROM "Poligon_ovire_opis";');
		return $results;
	}
	
	function SQL_PodatkiOKT($KT){ //vrne osnove podatke o kotrnolni točki
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->querySingle('SELECT "Opis_KT", "Nadzornik" FROM "KT_opis" WHERE "ID_KT" ='.$KT.';',true);
		return $results;
	}
	function SQL_PodatkiOOviri($Ovira){ //vrne osnove podatke o kotrnolni točki
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->querySingle('SELECT "ID_ovire", "Ime_ovire", "foto_ovire" FROM "Poligon_ovire_opis" WHERE "ID_ovire" ='.$Ovira.';',true);
		return $results;
	}
	
	function SQL_NapakeTnaKT($startna,$KT){ //vrne katere napake je naredil tekmovalec na kotrnolni točki
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->query('SELECT Napake_tekmovalcev.Sifra_prekrska, Opis_prekrska, Stevilo_prekrskov, tockovna_vrednost FROM "Prekrski" INNER JOIN Napake_tekmovalcev ON Prekrski."Sifra_prekrska"=Napake_tekmovalcev."Sifra_prekrska" WHERE Startna_stevilka='.$startna.' AND Pozicija_KT='.$KT.' AND Stevilo_prekrskov != 0 AND Napake_tekmovalcev."Sifra_prekrska"!=0;');
		
		return $results;
	}
	
	function SQL_RangListaPoligon(){
		$db = new SQLite3('kolesa_vov.sqlite');
		$stavek='SELECT Napake_tekmovalcev_poligon.Startna_stevilka,SUM(Stevilo_prekrskov*tockovna_vrednost) AS tock   FROM Napake_tekmovalcev_poligon
INNER JOIN Prekrski_poligon ON
Napake_tekmovalcev_poligon.Sifra_prekrska=Prekrski_poligon.ID_prekrska
 GROUP BY "Startna_stevilka";';
		$results = $db->query($stavek);
		return $results;
	}
	
	function SQL_RangListaTrasa(){
		$db = new SQLite3('kolesa_vov.sqlite');
		$stavek='SELECT Napake_tekmovalcev.Startna_stevilka,SUM(Stevilo_prekrskov*tockovna_vrednost) AS tock  FROM Napake_tekmovalcev
INNER JOIN Prekrski ON
Napake_tekmovalcev.Sifra_prekrska=Prekrski.Sifra_prekrska
 GROUP BY "Startna_stevilka";';
		$results = $db->query($stavek);
		return $results;
	}
	
	function SQL_RangListaTeorija(){
		$db = new SQLite3('kolesa_vov.sqlite');
		$stavek='SELECT "Startna_stevilka", "SIO_kazenske_tocke" FROM "Teorija_uvoz" INNER JOIN "Tekmovalci" ON "Teorija_uvoz"."SIO_userid"="Tekmovalci"."SIO_userid";';
		$results = $db->query($stavek);
		return $results;
	}
	
	function SQL_RangLista(){ //tabela tekmovalcev, od najboljšega proti najslabšemu - samo štartne številek z prekrški/točakmi
		$db = new SQLite3('kolesa_vov.sqlite');
		$stavek='SELECT "Tekmovalci"."Startna_stevilka" AS Startna,IFNULL(-"rang_poligon"."tock"-rang_trasa.tock-rang_teorija.tock,-9000)  AS skupaj,IFNULL(-"rang_poligon"."tock",-9000) AS poligon,IFNULL( -rang_teorija.tock,-9000) AS teorija,IFNULL(-rang_trasa.tock,-9000) AS trasa, SIO_firstname AS ime, SIO_lastname AS priimek,SIO_institution AS Sola,substr( "SIO_mentor",1, INSTR("SIO_mentor", "(")-2  ) AS mentor FROM "Tekmovalci" 
LEFT JOIN "rang_poligon" ON "Tekmovalci"."Startna_stevilka"="rang_poligon"."Startna_stevilka"
LEFT JOIN "rang_teorija" ON "Tekmovalci"."Startna_stevilka"="rang_teorija"."Startna_stevilka"
LEFT JOIN "rang_trasa" ON "Tekmovalci"."Startna_stevilka"="rang_trasa"."Startna_stevilka"
ORDER BY 
skupaj DESC,
rang_trasa.tock DESC,
"rang_poligon"."tock" DESC,
rang_teorija.tock DESC;';
		//$results = $db->query('SELECT Startna_stevilka, SUM(Stevilo_prekrskov*tockovna_vrednost) FROM "Prekrski" INNER JOIN Napake_tekmovalcev ON Prekrski."Sifra_prekrska"=Napake_tekmovalcev."Sifra_prekrska" GROUP BY "Startna_stevilka" ORDER BY SUM(Stevilo_prekrskov*tockovna_vrednost) ;');
	/*	$stavek='SELECT Startna_stevilka,SUM(tock) AS ima FROM
 (SELECT Napake_tekmovalcev_poligon.Startna_stevilka,SUM(Stevilo_prekrskov*tockovna_vrednost) AS tock   FROM Napake_tekmovalcev_poligon
INNER JOIN Prekrski_poligon ON
Napake_tekmovalcev_poligon.Sifra_prekrska=Prekrski_poligon.ID_prekrska
 GROUP BY "Startna_stevilka"
UNION
SELECT Napake_tekmovalcev.Startna_stevilka,SUM(Stevilo_prekrskov*tockovna_vrednost) AS tock  FROM Napake_tekmovalcev
INNER JOIN Prekrski ON
Napake_tekmovalcev.Sifra_prekrska=Prekrski.Sifra_prekrska
 GROUP BY "Startna_stevilka"
UNION
SELECT "Startna_stevilka", "SIO_kazenske_tocke" FROM "Teorija_uvoz" INNER JOIN "Tekmovalci" ON "Teorija_uvoz"."SIO_userid"="Tekmovalci"."SIO_userid"
) AS nekaj
GROUP BY "Startna_stevilka"
ORDER BY "ima"';*/

/*
sestavljen iz
SELECT "Startna_stevilka", "SIO_kazenske_tocke" FROM "Teorija_uvoz" INNER JOIN "Tekmovalci" ON "Teorija_uvoz"."SIO_userid"="Tekmovalci"."SIO_userid"

SELECT Napake_tekmovalcev_poligon.Startna_stevilka,Stevilo_prekrskov*tockovna_vrednost  FROM Napake_tekmovalcev_poligon
INNER JOIN Prekrski_poligon ON
Napake_tekmovalcev_poligon.Sifra_prekrska=Prekrski_poligon.ID_prekrska
GROUP BY "Startna_stevilka"
UNION
SELECT Napake_tekmovalcev.Startna_stevilka,Stevilo_prekrskov*tockovna_vrednost  FROM Napake_tekmovalcev
INNER JOIN Prekrski ON
Napake_tekmovalcev.Sifra_prekrska=Prekrski.Sifra_prekrska
GROUP BY "Startna_stevilka"
*/
$results = $db->query($stavek);
		return $results;	
	}
	
	function SQL_JeKTZakljucena($KT){//preveri ali je Kontrolna točka že zaključena
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->querySingle('SELECT "Cas_spremembe" FROM "Casi_konca_KT" WHERE "Pozicija_KT"='.$KT.';');
		if ($results!=null){
			return true;
		}else{
			return false;
		}
	}
	function SQL_JePoligonZakljucen(){//preveri ali je poligon že zaključen
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->querySingle('SELECT "Cas_spremembe" FROM "Casi_konca_KT" WHERE "Pozicija_KT"=100;');
		if ($results!=null){
			return true;
		}else{
			return false;
		}
	}
	function SQL_PoligonBrezNapak($startna){
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->querySingle('SELECT "Stevilo_prekrskov" FROM "Napake_tekmovalcev_poligon" WHERE "Sifra_prekrska" =0 AND "Startna_stevilka"='.$startna.';');
		if ($results!=null){
			return true;
		}else{
			return false;
		}
	}
	
	function SQL_SteviloZKT(){ //prešteje koliko kontrolnih točk je že zaključilo obratovanje
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->querySingle('SELECT COUNT("Cas_spremembe") FROM "Casi_konca_KT";');
		return $results;
	}
	function SQL_PoligonOvireImenaA(){ //vrne vsa imena ovir, po vrstnem redu za poligon A
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->query('SELECT "Ime_ovire","ID_ovire" FROM "Poligon_ovire_opis" ORDER BY "ID_ovire" ASC;');
		return $results;
	}
	function SQL_PoligonOvireImenaB(){ //vrne vsa imena ovir, po vrstnem redu za poligon B
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->query('SELECT "Ime_ovire","ID_ovire" FROM "Poligon_ovire_opis" ORDER BY "Vrstni_redB" ASC;');
		return $results;
	}
	function SQL_PoligonPrekrskiImena(){ //vrne vsa imena in id prekrskkov na poligonu
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->query('SELECT "ID_prekrska","Opis_prekrska" FROM "Prekrski_poligon" WHERE "ID_prekrska" != 0 ;');
		return $results;
	}
	
	function SQL_NapakePoligonT($startna){ //vnre vse napake tekmovalca na poligonu - samo številke
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->query('SELECT "ID_ovire", "Sifra_prekrska", "Stevilo_prekrskov" FROM "Napake_tekmovalcev_poligon" WHERE "Startna_stevilka" = '.$startna.';');
		return $results;
	}
	
	function SQL_NapakeTnaOviri($startna,$Ovira){ //vrne katere napake je naredil tekmovalec na oviri
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->query('SELECT Napake_tekmovalcev_poligon.Sifra_prekrska, Opis_prekrska, Stevilo_prekrskov, tockovna_vrednost FROM "Prekrski_poligon" INNER JOIN Napake_tekmovalcev_poligon ON Prekrski_poligon."ID_prekrska"=Napake_tekmovalcev_poligon."Sifra_prekrska" WHERE Startna_stevilka='.$startna.' AND ID_ovire='.$Ovira.' AND Stevilo_prekrskov != 0 ;');
		
		return $results;
	}
	
	function SQL_StartneStevilkeTekmovalcev(){ //vrne seznam vseh tekmovalcev in njihovih štartnih številk
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->query('SELECT "Startna_stevilka","SIO_firstname","SIO_lastname", "SIO_institution" ,"SIO_mentor" FROM "Tekmovalci";');
		return $results;
	}
	
	function SQL_RezultatiTeorijeTekmovalcev(){ //vrne rezultate teorije vseh tekmovalcev
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->query('SELECT "SIO_kazenske_tocke","SIO_userid","SIO_firstname","SIO_lastname","SIO_institution" FROM "Teorija_uvoz";');
		return $results;
	}
	function SQL_NapakIzTeorijeTekmovalca($startna){
		$db = new SQLite3('kolesa_vov.sqlite');
		$napak = $db->querySingle('SELECT "SIO_kazenske_tocke" FROM "Teorija_uvoz" WHERE "SIO_userid" IN (SELECT "SIO_userid" FROM "Tekmovalci" WHERE "Startna_stevilka" = '.$startna.');');
		return $napak;
		
	}
	function SQL_NeObiskaliKT($KT){ //vrne štarnte tekmovalcev ki še niso obiskali kontrolne
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->query('SELECT "Startna_stevilka" FROM "Tekmovalci" WHERE "Startna_stevilka" NOT IN (SELECT "Startna_stevilka" FROM Napake_tekmovalcev WHERE Pozicija_KT='.$KT.');');
		return $results;
	}
	function SQL_NeObiskaliPoligon(){ //vrne štarnte tekmovalcev ki še niso obiskali kontrolne
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->query('SELECT "Startna_stevilka" FROM "Tekmovalci" WHERE "Startna_stevilka" NOT IN (SELECT "Startna_stevilka" FROM Napake_tekmovalcev_poligon);');
		return $results;
	}
	function SQL_NeObiskaliTeorijo(){ //vrne štarnte tekmovalcev ki še niso obiskali kontrolne
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->query('SELECT "Startna_stevilka" FROM "Tekmovalci" WHERE "SIO_userid" NOT IN (SELECT "SIO_userid" FROM "Teorija_uvoz");');
		return $results;
	}
	function SQL_VsiMetnorji(){ //vrne ime in priimek vsakega mentorja ter število njegovih tekmovalcev
		$db = new SQLite3('kolesa_vov.sqlite');
		$results = $db->query('SELECT COUNT("Startna_stevilka") AS tekmovalcev, substr( "SIO_mentor",1, INSTR("SIO_mentor", "(")-2  ) AS mentor FROM "Tekmovalci" GROUP BY "SIO_mentorid";');
		return $results;
	}
?>