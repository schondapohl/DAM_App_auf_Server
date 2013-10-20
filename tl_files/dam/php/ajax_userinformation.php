<?php
session_start();
/**
 * Created by JetBrains PhpStorm.
 * User: Markus Zippelt
 * Date: 19.02.13
 * Time: 17:43
 * To change this template use File | Settings | File Templates.
 */

include("dbconnection.php");
include("damuser.php");
include("damabstract.php");
include("damautor.php");
include("damadresse.php");

$damUser = new damuser();
$filePath = "tl_files/dam/referenten/";

if (isset($_GET["mode"])) {

    if ("refresh" == $_GET["mode"]) {
        $damUser = unserialize($_SESSION['user']);
        $filePath = "../referenten/";
    }
} else {
    $damUser->userid = $this->replaceInsertTags('{{user::id}}');
    $damUser->username = $this->replaceInsertTags('{{user::username}}');
    $damUser->userhash = md5($this->replaceInsertTags('{{user::id}}'));
    $damUser->vorname = $this->replaceInsertTags('{{user::firstname}}');
    $damUser->nachname = $this->replaceInsertTags('{{user::lastname}}');
    $damUser->email = $this->replaceInsertTags('{{user::email}}');
    $this->import('FrontendUser', 'User');
    if ($this->User->isMemberOf(6)) {
        $damUser->manager = true;
    }
}
$files = array();

if (mysqli_connect_errno() == 0) {
    /*
    * Zuerst Dateien aus Verzeichnis auslesen
    */
    $handle = opendir($filePath . $damUser->username . "/");
    while ($datei = readdir($handle)) {
        if ($datei != "." && $datei != "..") {
            $files[] = $datei;
        }
    }
    closedir($handle);

    $sqll = "SELECT * FROM `dam_anmeldungen` where userid=\"" . $damUser->userhash . "\"";
    $ergebniss = $db->query($sqll);
    while ($zeile = $ergebniss->fetch_object()) {
        $damUser->anmeldungstyp = $zeile->anmeldetyp;
        $damUser->kongressstatus = $zeile->anmeldestatus;
        $damUser->grundbetrag = $zeile->grundkosten;
        $damUser->zusatzbetrag = $zeile->zusatzkosten;
    }
    unset($zeile);
    $ergebniss->close();


    /*
    * Dann Abstracts aus der DB auslesen         *
    */
    $sql = "SELECT * FROM `dam_abstract` where userid=\"" . $damUser->userhash . "\"";
    $ergebnis = $db->query($sql);
    $damUser->abstractCount = $ergebnis->num_rows;
    $damUser->abstracts = array();
    while ($zeile = $ergebnis->fetch_object()) {
        $abstract = new damabstract();
        $abstract->datum = $zeile->datum;
        $abstract->status = $zeile->status;
        $abstract->id = $zeile->abstractid;
        $abstract->name = $zeile->titel;
        $abstract->hintergrund = $zeile->hintergrund;
        $abstract->methoden = $zeile->methoden;
        $abstract->ergebnisse = $zeile->ergebnisse;
        $abstract->schlussfolgerung = $zeile->schlussfolgerung;
        $abstract->dateinamen[] = $zeile->datei1;
        $abstract->dateinamen[] = $zeile->datei2;
        $abstract->dateinamen[] = $zeile->datei3;
        $abstract->vortragart = $zeile->vortragart;
        $damUser->abstracts[] = $abstract;
    }
    unset($zeile);
    $ergebnis->close();

    /*
    * Danach alle Autoren auslesen
    */
    $autorenquery = "select a.*, b.* from dam_autoren a, dam_adressen b, dam_zuordnungen c where a.userid = b.userid and a.autorid = c.autorid and b.adressid = c.adressid and a.userid =\"" . $damUser->userhash . "\"";
    $autorenerg = $db->query($autorenquery);
    $damUser->autorenCount = $autorenerg->num_rows;
    $damUser->autoren = array();
    $damUser->adressen = array();
    while ($zeile = $autorenerg->fetch_object()) {
        $autor = new damautor();
        $autor->id = $zeile->autorid;
        $autor->vorname = $zeile->vorname;
        $autor->nachname = $zeile->nachname;
        $autor->email = $zeile->email;
        $autor->telefon = $zeile->telefon;
        $autor->adressid = $zeile->adressid;
        $autor->titel = $zeile->titel;
        $autor->adressid = $zeile->adressid;
        $damUser->addAutor($autor);
        #$damUser->autoren[] = $autor;

        $adresse = new damadresse();
        $adresse->id = $zeile->adressid;
        $adresse->institution = $zeile->institut;
        $adresse->strasse = $zeile->strasse;
        $adresse->plz = $zeile->plz;
        $adresse->ort = $zeile->ort;
        $adresse->land = $zeile->land;
        $damUser->addAdresse($adresse);
        #$damUser->adressen[] = $adresse;
    }
    $autorenerg->close();

    foreach ($damUser->abstracts as $a) {
        // Dann alle Abstracts durchlaufen und die Zuweisungen auslesen
        $zuweisungsql = "SELECT * FROM `dam_abstract_zuordnungen` WHERE 1 AND abstractid = " . $a->id . " order by id";
        $zuweisungerg = $db->query($zuweisungsql);
        $counter = 0;
        while ($zeile = $zuweisungerg->fetch_object()) {
            if ($counter == 0) {
                $a->firstAutor = $damUser->findAutor($zeile->autorid);
                $counter++;
            } else {
                $a->otherAutors[] = $damUser->findAutor($zeile->autorid);
            }

        }
        $zuweisungerg->close();
    }


    /*
    * Vergleich der Dateien im Userverzeichnis und der DB
    */
    /*$damUser->uploadedFileCount = count($files);
if (count($files) > $damUser->abstractCount) {
$elementFound = false;
foreach ($files as $file) {
$elementFound = false;
foreach ($damUser->abstracts as $dbabstract) {
if ($dbabstract->dateiname == $file) {
  $elementFound = true;
}
}
if (!$elementFound) {
$abstractInsertSql = "INSERT INTO `dam_abstracts` (`dateiname`, `username`, `userid`, `status`, `datum`) VALUES (?,?,?,?,?)";
$abstractEintrag = $db->prepare($abstractInsertSql);
$status = 1;
$time = time();
$abstractEintrag->bind_param('sssii', $file, $damUser->username, $damUser->userhash, $status, $time);
$abstractEintrag->execute();
$abstractEintrag->close();
/* Jetzt noch die UserInfos updaten */
    /*$damUser->abstractCount = $damUser->abstractCount + 1;
                $newAbstract = new damabstract();
                $newAbstract->dateiname = $file;
                $newAbstract->datum = $time;
                $newAbstract->status = $status;
                $damUser->abstracts[] = $newAbstract;
            }
        }
        unset($file); // break the reference with the last element
    }*/
    #echo " Userid " . $damUser->userid . " Username " . $damUser->username . " UserHash " . $damUser->userhash . " AbstractCount: " . $damUser->abstractCount;

} else {
    // Es konnte keine Datenbankverbindung aufgebaut werden
    echo 'Die Datenbank konnte nicht erreicht werden. Folgender Fehler trat auf: <span class="hinweis">' . mysqli_connect_errno() . ' : ' . mysqli_connect_error() . '</span>';
}

// Datenbankverbindung schliessen
$db->close();


$_SESSION['user'] = serialize($damUser);
