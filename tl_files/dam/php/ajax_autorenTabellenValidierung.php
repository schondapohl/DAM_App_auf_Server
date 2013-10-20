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
    * Danach alle Autoren auslesen
    */
    $autorenCount = 0;
    $autorenquery = "select a.*, b.* from dam_autoren a, dam_adressen b, dam_zuordnungen c where a.userid = b.userid and a.autorid = c.autorid and b.adressid = c.adressid and a.userid =\"" . $damUser->userhash . "\"";
    $autorenerg = $db->query($autorenquery);
    $damUser->autorenCount = $autorenerg->num_rows;
    $damUser->autoren = array();
    $damUser->adressen = array();
    while ($zeile = $autorenerg->fetch_object()) {
        $autorenCount = $autorenCount + 1;
    }
    $autorenerg->close();

    if ($_GET['anzeige'] != $autorenCount) {
        $response = array(
            'dorefresh' => true
        );
    } else {
        $response = array(
            'dorefresh' => true
        );
    }
#print_r($response);
    $data = json_encode($response);
    echo $_GET['jsonp_callback'] . '(' . $data . ')';


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
