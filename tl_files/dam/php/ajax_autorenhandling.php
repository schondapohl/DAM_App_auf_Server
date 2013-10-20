<?php
session_start();
include("dbconnection.php");
include("damuser.php");
include("damabstract.php");
include("damautor.php");
include("damadresse.php");

if (isset($_POST["mode"])) {
    $damUser = unserialize($_SESSION['user']);
    $mode = $_POST["mode"];
    if ($mode == "add") {
        $titel = $_POST["titel"];
        $vorname = $_POST["vorname"];
        $nachname = $_POST["nachname"];
        $email = $_POST["email"];
        $titel = $_POST["titel"];
        $institution = $_POST["institution"];
        $plz = $_POST["plz"];
        $strasse = $_POST["strasse"];
        $ort = $_POST["ort"];
        $land = $_POST["land"];
        $vorlagenid = $_POST["adressvorlagenid"];
        $time = time();
        // Neues Datenbank-Objekt erzeugen
        #$db = @new mysqli('localhost', 'root', 'root', 'damneu');
        // Pruefen ob die Datenbankverbindung hergestellt werden konnte
        if (mysqli_connect_errno() == 0) {
            $sql = "INSERT INTO `dam_autoren` (`vorname`, `nachname`, `email`, `userid`, `erstellung`, `aenderung`, `titel`)
            VALUES (?,?,?,?,?,?,?)";
            $eintrag = $db->prepare($sql);
            $eintrag->bind_param('ssssiis', $vorname, $nachname, $email, $damUser->userhash, $time, $time, $titel);
            $eintrag->execute();
            // Pruefen ob der Eintrag efolgreich war
            if ($eintrag->affected_rows == 1) {
                $newAutor = new damautor();
                $newAutor->vorname = $vorname;
                $newAutor->nachname = $nachname;
                $newAutor->email = $email;
                $newAutor->titel = $titel;
                $newAutor->id = -1;
                $idQuery = "SELECT autorid FROM dam_autoren where userid=\"" . $damUser->userhash . "\" and vorname=\"" . $newAutor->vorname .
                    "\"  and nachname=\"" . $newAutor->nachname .
                    "\"  and email=\"" . $newAutor->email . "\"";
                $ergebnis = $db->query($idQuery);
                while ($zeile = $ergebnis->fetch_object()) {
                    $newAutor->id = $zeile->autorid;
                }
                if ($newAutor->id != -1) {
                    // Zuerst prüfen wie ob die Adresse bereits existiert
                    $createAdress = false;
                    if ($vorlagenid == -1) {
                        $createAdress = true;
                    } else {
                        $adressCheckQuery = "select * from dam_adressen where adressid =" . $vorlagenid;
                        $adressCheckErg = $db->query($adressCheckQuery);
                        $adressCheckCount = $adressCheckErg->num_rows;
                        if ($adressCheckCount == 0) {
                            $createAdress = true;
                        } else {
                            while ($zeile = $adressCheckErg->fetch_object()) {
                                if ($zeile->institut == $institution && $zeile->strasse == $strasse && $zeile->plz == $plz && $zeile->ort == $ort && $zeile->land == $land) {
                                    $createAdress = false;
                                } else {
                                    $createAdress = true;
                                }
                            }
                        }
                    }
                    $newAdress = new damadresse();
                    $newAdress->id = -1;
                    $newAdress->institution = $institution;
                    $newAdress->strasse = $strasse;
                    $newAdress->plz = $plz;
                    $newAdress->ort = $ort;
                    $newAdress->land = $land;
                    if ($createAdress) {
                        $sql = "INSERT INTO `dam_adressen` ( `institut`, `strasse`, `plz`, `ort`, `land`, `userid`)
                        VALUES (?,?,?,?,?,?)";
                        $eintrag = $db->prepare($sql);
                        $eintrag->bind_param('ssssss', $institution, $strasse, $plz, $ort, $land, $damUser->userhash);
                        $eintrag->execute();
                        // Pruefen ob der Eintrag efolgreich war
                        if ($eintrag->affected_rows == 1) {
                            $idQuery = "SELECT distinct adressid FROM dam_adressen where institut=\"" . $newAdress->institution . "\" and strasse=\"" . $newAdress->strasse .
                                "\"  and plz=\"" . $newAdress->plz .
                                "\"  and userid=\"" . $damUser->userhash .
                                "\"  and ort=\"" . $newAdress->ort . "\" and land=\"" . $newAdress->land . "\"";
                            $selectErg = $db->query($idQuery);
                            while ($zeile = $selectErg->fetch_object()) {
                                $newAdress->id = $zeile->adressid;
                                $newAutor->adressid = $newAdress->id;
                            }
                            if ($newAdress->id != -1) {
                                $sql = "INSERT INTO `dam_zuordnungen` ( `autorid`, `adressid`) VALUES (?,?)";
                                $eintrag = $db->prepare($sql);
                                $eintrag->bind_param('ii', $newAutor->id, $newAutor->adressid);
                                $eintrag->execute();
                                // Pruefen ob der Eintrag efolgreich war
                                if ($eintrag->affected_rows == 1) {
                                } else {
                                    // MYSQL ERROR
                                    echo 'Der Eintrag konnte nicht hinzugef&uuml;gt werden.1';
                                }
                            }
                        } else {
                            // MYSQL ERROR
                            echo 'Der Eintrag konnte nicht hinzugef&uuml;gt werden.2';
                        }
                    } else {
                        $newAutor->adressid = $vorlagenid;
                        $sql = "INSERT INTO `dam_zuordnungen` ( `autorid`, `adressid`) VALUES (?,?)";
                        $eintrag = $db->prepare($sql);
                        $eintrag->bind_param('ii', $newAutor->id, $newAutor->adressid);
                        $eintrag->execute();
                        // Pruefen ob der Eintrag efolgreich war
                        if ($eintrag->affected_rows == 1) {
                            $damUser->addAutor($newAutor);
                        } else {
                            // MYSQL ERROR
                            echo 'Der Eintrag konnte nicht hinzugef&uuml;gt werden.1';
                        }
                    }
                    $_SESSION['user'] = serialize($damUser);
                }

            } else {
                // MYSQL ERROR
                echo 'Der Eintrag konnte nicht hinzugef&uuml;gt werden.3';
            }
        } else {
            // MYSQL ERROR
            echo 'Die Datenbank konnte nicht erreicht werden. Folgender Fehler trat auf: <span class="hinweis">' . mysqli_connect_errno() . ' : ' . mysqli_connect_error() . '</span>';
        }
        // Datenbankverbindung schliessen
        $db->close();
    } else if ($mode == "update") {

        /**
         *
         * UPDATE
         *
         *
         */
        $titel = $_POST["titel"];
        $vorname = $_POST["vorname"];
        $nachname = $_POST["nachname"];
        $email = $_POST["email"];
        $institution = $_POST["institution"];
        $plz = $_POST["plz"];
        $strasse = $_POST["strasse"];
        $ort = $_POST["ort"];
        $land = $_POST["land"];
        $vorlagenid = $_POST["adressvorlagenid"];
        $time = time();
        $aid = $_POST["aid"];
        $sql = "Update dam_autoren set vorname = ?, nachname = ?, email = ?, aenderung = ?, titel=? where userid = ? and autorid = ?";
        $eintrag = $db->prepare($sql);
        $eintrag->bind_param('ssssssi', $vorname, $nachname, $email, $time, $titel, $damUser->userhash, $aid);
        $eintrag->execute();
        // Pruefen ob der Eintrag efolgreich war
        if ($eintrag->affected_rows == 1) {

            // 1.0 Zähle die vorkommenden Adressen mit der alten id
            // 1.1. wenn die alte adressid nur einmal vorkommt, alte Adresse Updaten
            // 1.2 wenn die alte adressid mehrmals vorkommt, die zuordnung löschen, eine neue adresse und neue zuordnung anlegen
            // 2.1. wenn ja hole die ändere die id in der zuordnung

            // 1.0
            $dataSql = "SELECT * FROM `dam_adressen` where `adressid` = " . $vorlagenid;
            $dataErg = $db->query($dataSql);
            $damAdressDb = new damadresse();
            while ($zeile = $dataErg->fetch_object()) {
                $damAdressDb->id = $zeile->adressid;
                $damAdressDb->institution = $zeile->institut;
                $damAdressDb->strasse = $zeile->strasse;
                $damAdressDb->plz = $zeile->plz;
                $damAdressDb->ort = $zeile->ort;
                $damAdressDb->land = $zeile->land;
            }
            unset($zeile);

            $idQuery = "SELECT * FROM `dam_zuordnungen` where `adressid` = " . $vorlagenid;
            $selectErg = $db->query($idQuery);
            if ($selectErg->num_rows == 1) {
                $usql = "UPDATE `dam_adressen` SET `institut`=?,`strasse`=?,`plz`=?,`ort`=?,`land`=? where `adressid` = ?";

                if ($updateErg = $db->prepare($usql)) {
                    $updateErg->bind_param('sssssi', $institution, $strasse, $plz, $ort, $land, $vorlagenid);
                    $updateErg->execute();
                    if ($eintrag->affected_rows == 1) {
                        echo "\n Update 1 erfolgreich";
                    } else {
                        echo "\n Update 1 nicht erfolgreich";
                    }
                    $updateErg->close();
                }

            } else {
                if ($damAdressDb->institution != trim($institution)
                    || $damAdressDb->plz != trim($plz)
                    || $damAdressDb->ort != trim($ort)
                    || $damAdressDb->land == trim($land)
                    || $damAdressDb->strasse == trim($strasse)
                ) {
                    // Wenn sich die Daten unterscheiden die zuordnung löschen, prüfen ob diese Adresse bereits existiert
                    // und wenn ja dann die neue Zuordnung eingeben wenn nicht eine neue adresse und neue zuordnung anlegen
                    $delSql = "Delete from dam_zuordnungen where adressid = ? and autorid = ?";
                    $delErg = $db->prepare($delSql);
                    $delErg->bind_param('ii', $aid, $vorlagenid);
                    $delErg->execute();
                    if ($delErg->affected_rows == 1) {
                        echo "\n Delete 1 erfolgreich";
                    } else {
                        echo "\n Delete 1 nicht erfolgreich";
                    }
                    $delErg->close();

                    // Prüfen ob die Adresse bereits existiert
                    $newIDQuery = "SELECT adressid FROM dam_adressen where institut=\"" . $institution . "\" and strasse=\"" . $strasse .
                        "\"  and plz=\"" . $plz .
                        "\"  and userid=\"" . $damUser->userhash .
                        "\"  and ort=\"" . $ort . "\" and land=\"" . $land . "\"";
                    $idResult = $db->query("\"  and plz=\"");
                    $existingID = -1;
                    $existingID = $idResult->num_rows;
                    if ($existingID != -1) {
                        $theID = -1;
                        // Die Adresse existiert schon
                        while ($zeile = $selectErg->fetch_object()) {
                            $theID = $zeile->adressid;
                        }
                        $sql = "INSERT INTO `dam_zuordnungen` ( `autorid`, `adressid`) VALUES (?,?)";
                        $eintrag = $db->prepare($sql);
                        $eintrag->bind_param('ii', $aid, $theID);
                        $eintrag->execute();
                        // Pruefen ob der Eintrag efolgreich war
                        if ($eintrag->affected_rows == 1) {
                        } else {
                            // MYSQL ERROR
                            echo 'Der Eintrag konnte nicht hinzugef&uuml;gt werden.2';
                        }


                    } else {
                        // Die Adresse existiert noch nicht
                        $newAdressSQL = "INSERT INTO `dam_adressen` ( `institut`, `strasse`, `plz`, `ort`, `land`, `userid`)
                        VALUES (?,?,?,?,?,?)";
                        $newAdressEintrag = $db->prepare($newAdressSQL);
                        $newAdressEintrag->bind_param('ssssss', $institution, $strasse, $plz, $ort, $land, $damUser->userhash);
                        $newAdressEintrag->execute();
                        if ($delErg->affected_rows == 1) {
                            echo "\n Insert 1new erfolgreich";
                        } else {
                            echo "\n Insert 1 new erfolgreich";
                        }
                        $newAdressEintrag->close();
                        $newIDQuery = "SELECT distinct adressid FROM dam_adressen where institut=\"" . $institution . "\" and strasse=\"" . $strasse .
                            "\"  and plz=\"" . $plz .
                            "\"  and userid=\"" . $damUser->userhash .
                            "\"  and ort=\"" . $ort . "\" and land=\"" . $land . "\"";
                        $idResult = $db->query($newIDQuery);
                        while ($zeile = $selectErg->fetch_object()) {
                            $addyID = $zeile->adressid;
                            $counter = $counter + 1;
                        }
                        if ($counter > 1) {
                            $sql = "INSERT INTO `dam_zuordnungen` ( `autorid`, `adressid`) VALUES (?,?)";
                            $eintrag = $db->prepare($sql);
                            $eintrag->bind_param('ii', $aid, $addyID);
                            $eintrag->execute();
                            // Pruefen ob der Eintrag efolgreich war
                            if ($eintrag->affected_rows == 1) {
                            } else {
                                // MYSQL ERROR
                                echo 'Der Eintrag konnte nicht hinzugef&uuml;gt werden.2';
                            }
                        }
                    }
                }
            }
        } else {
            echo "Update1 failed";
        }
        $db->close();
    } else if ($mode == "delete") {
        $theAutorenId = $_POST["theid"];
        $theAdressId = $_POST["theaid"];
        $sql = "Delete from dam_autoren where userid = ? and autorid = ?";
        $eintrag = $db->prepare($sql);
        $eintrag->bind_param('si', $damUser->userhash, $theAutorenId);
        $eintrag->execute();
        // Pruefen ob der Eintrag efolgreich war
        if ($eintrag->affected_rows == 1) {
            echo "delete succeded";
            $sql = "Delete from dam_zuordnungen where autorid = ?";
            $eintraga = $db->prepare($sql);
            $eintraga->bind_param('i', $theAutorenId);
            $eintraga->execute();
            $eintraga->close();
            /* Adressen */
            $sqla = "select * from dam_zuordnungen where adressid = " . $theAdressId;
            $eintragb = $db->query($sqla);
            if ($eintragb->num_rows == 0) {
                $sqlc = "Delete from dam_adressen where adressid = ?";
                $eintragc = $db->prepare($sqlc);
                $eintragc->bind_param('i', $theAdressId);
                $eintragc->execute();
            }
        } else {
            echo "delete failed";
        }
    }
} else {
    echo "Nope sorry little hacker";
}
?>