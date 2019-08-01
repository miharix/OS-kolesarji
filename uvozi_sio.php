<?php

/*ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);*/

include 'SQL_pisanje.php';
include 'SQL_branje.php';

echo "<!DOCTYPE html>\n<HTML>\n<HEAD>\n<title>Tekmovanje - UVOZ TEKMOVALCEV</title>\n";
echo '<link rel="shortcut icon" href="favicon.ico" />';
echo "<link rel='stylesheet' type='text/css' href='slog.css'>\n</HEAD>\n<BODY>\n";
echo '<div id="naslov">UVOZ TEKMOVALCEV TER GENRIRANJE ŠTARTNIH ŠTEVILK</div>';

if($_FILES==null){
	if($_POST==NULL){
		echo '<form action="uvozi_sio.php" method="POST">';
		echo '<div id="ResetShrani">Geslo: <input type="password" name="GESLO">';
		echo '<button type="submit"  name="Uvozi" value=0>Uvozi</button></div>';
		echo '</form>';
	}else{
		if($_POST['GESLO']=='miharix'){
echo 'uvozi podatke o tekmovalcih';
echo "<form enctype=\"multipart/form-data\" action=\"uvozi_sio.php\" method=\"POST\">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"3000000\" />
    <!-- Name of input element determines name in \$_FILES array -->
    Send this file: <input class='datoteka' name=\"userfile\" type=\"file\"  accept=\"text/csv\" />
    <input class='datoteka' type=\"submit\" value=\"Send File\" />
</form>";
}}
}else{

$uploaddir = '/var/www/kolesarji/tmp/';
$uploadfile = $uploaddir . 'tekmovalci.csv';//basename($_FILES['userfile']['name']);

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
    
	$rows   = array_map(function($row) { return str_getcsv($row, ';'); }, file($uploadfile));
    $header = array_shift($rows);
    $csv    = array();
    foreach($rows as $row) {
        $csv[] = array_combine($header, $row);
    }
    
    echo count($csv).'<br>';
    //echo $csv[1]['username'];
    shuffle($csv); //premešaj tabelo

    foreach($csv as $vrstica) {
    	
    	SQL_dodajTekmovalca($vrstica['userid'],$vrstica['username'],$vrstica['firstname'],$vrstica['lastname'],$vrstica['email'],$vrstica['institution'],$vrstica["mentorid"],$vrstica["mentor"]);
    	//echo $vrstica['userid'].' '.$vrstica['username'].' '.$vrstica['firstname'].' '.$vrstica['lastname'].' '.$vrstica['email'].' '.$vrstica['institution'].' '.$vrstica["mentor"].$vrstica["kazenske_tocke"].'<br>';
    	
    }
    
    $startne=SQL_StartneStevilkeTekmovalcev();
    while ($startna = $startne->fetchArray(SQLITE3_NUM) ){
    	echo '<div>'.$startna[0].' '.$startna[1].' '.$startna[2].' '.$startna[3].' '.$startna[4].'</div>';
    }
    
    //$SIO_userid,$SIO_username,$SIO_firstname,$SIO_lastname,$SIO_email,$SIO_institution,$SIO_mentorid,$SIO_mentor,$SIO_kazenske_tocke
    
    /*shuffle($csv);*/
    
    
} else {
    echo "Possible file upload attack!\n";
}

}

echo "</BODY>\n</HTML>";

?>