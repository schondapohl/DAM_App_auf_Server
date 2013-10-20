<?php
session_start();
include("dbconnection.php");
include("damuser.php");
include("damabstract.php");
include("damautor.php");
include("damadresse.php");

if (isset($_POST["mode"])) {
    $mode = $_POST["mode"];
    $postautorid = $_POST["autorid"];
    $postabstractid = $_POST["abstractid"];

    /**
     * Hier wird ein Autor hinugefügt
     */
    if ($mode == "add") {
        // Neues Datenbank-Objekt erzeugen
        #include("../../../tl_files/dam/php/dbconnection.php");
        include("dbconnection.php");
        // Pruefen ob die Datenbankverbindung hergestellt werden konnte
        if (mysqli_connect_errno() == 0) {
            $sql = "INSERT INTO `dam_abstract_zuordnungen` ( `autorid`, `abstractid`) VALUES (?,?)";
            $eintrag = $db->prepare($sql);
            $eintrag->bind_param('ii', $postautorid, $postabstractid);
            $eintrag->execute();
            // Pruefen ob der Eintrag efolgreich war
            if ($eintrag->affected_rows == 1) {
                $damUser = unserialize($_SESSION['user']);
                foreach ($damUser->abstracts as $singleAbstract) {
                    if ($singleAbstract->id == $postabstractid) {
                        echo "1";
                        if ($singleAbstract->firstAutor == null || $singleAbstract->firstAutor == "") {
                            echo "2";
                            foreach ($damUser->autoren as $theautor) {
                                if ($theautor->id == $postautorid) {
                                    echo "3";
                                    $singleAbstract->firstAutor = $theautor;
                                }
                            }
                            echo "4";
                            unset($theautor);
                        } else {
                            echo "5";
                            foreach ($damUser->autoren as $theautor) {
                                if ($theautor->id == $postautorid) {
                                    $singleAbstract->otherAutors[] = $theautor;
                                }
                            }
                            unset($theautor);
                        }
                    }
                }
                unset($singleAbstract);
                $_SESSION['user'] = serialize($damUser);
            } else {
                // MYSQL ERROR
                //echo 'Der Eintrag konnte nicht hinzugef&uuml;gt werden.';
            }

        } else {
            // MYSQL ERROR
            echo 'Die Datenbank konnte nicht erreicht werden. Folgender Fehler trat auf: <span class="hinweis">' . mysqli_connect_errno() . ' : ' . mysqli_connect_error() . '</span>';
        }
        // Datenbankverbindung schliessen
        $db->close();

    }
} else if (isset($_GET["addAutorRow"])) {
    $rowNumber = $_GET["addAutorRow"];
    $rowNumber = $rowNumber + 1;
    $damUser = unserialize($_SESSION['user']);
    ?>
<tr id="<?php echo "trautor_" . $rowNumber; ?>">
    <td style="text-align:right">
        <span onclick="addAutor(<?php echo $rowNumber; ?>)" class="usageIcons" id="<?php echo "addImageSpan_" . $rowNumber; ?>">weiteren Autor zum Abstract hinzufügen
                        <img src="<?php echo $picpath."add.png"?>" id="<?php echo "addImage_" . $rowNumber; ?>"
                             class="usageIcons"
                             title="Autor hinzufügen" alt="Autor hinzufügen"/></span>

    </td>
    <td><select id="autor_<?php echo $rowNumber; ?>" class="hiddenInput autorselection">
        <option value="" selected="selected"></option>
        <?php
        foreach ($damUser->autoren as $theautor) {
            echo "<option value=\"$theautor->id\">";
            echo $theautor->toString();
            echo "</option>";
        }
        unset($theautor);
        ?>
    </select></td>
</tr>

<?
} else if (isset($_POST["sendAbstract"])) {
    $coAutoren = $_POST["coAutoren"];
    $first = $_POST["firstAutor"];
    $abstractTitel = $_POST["titel"];

    $inputsSet = false;
    /*
    $hintergrund = $_GET["hintergrund"];
    $methoden = $_GET["methoden"];
    $ergebnisse = $_GET["ergebnisse"];
    $schlussfolgerung = $_GET["schlussfolgerung"];*/
    #print_r($_SESSION['inputs']);
    $inputsSet = isset($_SESSION['inputs']);

    $hintergrund = $_SESSION['inputs']['hintergrundid'];
    $methoden = $_SESSION['inputs']['methodenid'];
    $ergebnisse = $_SESSION['inputs']['ergenisseid'];
    $schlussfolgerung = $_SESSION['inputs']['schlussid'];

    $vortragart = $_POST["vortragart"];
    $thema = $_POST["thema"];
    $damUser = unserialize($_SESSION['user']);
    $singleAbstract = null;
    include("dbconnection.php");
    // Pruefen ob die Datenbankverbindung hergestellt werden konnte
    if (mysqli_connect_errno() == 0 && $inputsSet) {
        $time = time();
        $status = 1;
        #echo "<br> user " . $damUser->userhash;
        #echo "<br> titel " . $abstractTitel;
        #echo "<br> hintergrund " . $hintergrund;
        #echo "<br> methoden " . $methoden;
        #echo "<br> ergebnisse " . $ergebnisse;
        #echo "<br> schlussfolgerung" . $schlussfolgerung;
        #echo "<br> datei1 " . $datei;
        #echo "<br> datei2 " . $dateii;
        if (isset($_SESSION['dam_uploadedfiles'])) {
            $filenames = $_SESSION['dam_uploadedfiles'];
            print_r($filenames);
            $datei = "";
            $dateii = "";
            $dateiii = "";
            $hash1 = "";
            $hash2 = "";
            $hash3 = "";

            $value = time() * rand();
            if (isset($filenames[0]) && $filenames[0] != "") {
                $datei = $filenames[0];
                $hash1 = md5($value)."_1";
                $position_des_letzten_punktes = strrpos($datei, '.');
                $endung = substr($datei, $position_des_letzten_punktes, strlen($datei) - 1);
                $hash1 .= $endung;
            }

            if (isset($filenames[1]) && $filenames[1] != "") {
                $dateii = $filenames[1];
                $hash2 = md5($value)."_2";
                $position_des_letzten_punktes = strrpos($dateii, '.');
                $endung = substr($dateii, $position_des_letzten_punktes, strlen($dateii) - 1);
                $hash2 .= $endung;
            }

            if (isset($filenames[2]) && $filenames[2] != "") {
                $dateiii = $filenames[2];
                $hash3 = md5($value)."_3";
                $position_des_letzten_punktes = strrpos($dateiii, '.');
                $endung = substr($dateiii, $position_des_letzten_punktes, strlen($dateiii) - 1);
                $hash3 .= $endung;
            }
        }

        $sql = "INSERT INTO `dam_abstract`(`userid`, `titel`, `hintergrund`, `methoden`, `ergebnisse`, `schlussfolgerung`, `datei1`, `datei2`, `datum`, `status`, `datei3`, `vortragart`, uid, thema)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $eintrag = $db->prepare($sql);
        $eintrag->bind_param('ssssssssiisiis', $damUser->userhash, $abstractTitel, $hintergrund, $methoden, $ergebnisse, $schlussfolgerung, $hash1, $hash2, $time, $status, $hash3, $vortragart, $damUser->userid, $thema);
        $eintrag->execute();
        // Pruefen ob der Eintrag efolgreich war
        if ($eintrag->affected_rows == 1) {
            # Erfolgreicher Insert
            # Jetzt die ID des Abstracts auslesen
            $sql = "SELECT * FROM `dam_abstract` where userid=\"" . $damUser->userhash . "\" and datum = " . $time;
            $ergebnis = $db->query($sql);
            $damUser->abstractCount = $damUser->abstractCount + $ergebnis->num_rows;
            $abstractId = -1;
            while ($zeile = $ergebnis->fetch_object()) {
                $abstract = new damabstract();
                $abstract->datum = $zeile->datum;
                $abstract->status = $zeile->status;
                $abstract->id = $zeile->abstractid;
                $abstractId = $zeile->abstractid;
                $abstract->hintergrund = $zeile->hintergrund;
                $abstract->methoden = $zeile->methoden;
                $abstract->ergebnisse = $zeile->ergebnisse;
                $abstract->schlussfolgerung = $zeile->schlussfolgerung;
                $abstract->dateinamen[] = $zeile->datei1;
                $abstract->dateinamen[] = $zeile->datei2;
                $abstract->dateinamen[] = $zeile->datei3;
                $abstract->vortragart = $vortragart;
                $abstract->thema = $thema;
                $damUser->abstracts[] = $abstract;
            }
            unset($zeile);
            $ergebnis->close();
            if ($abstractId != -1) {
                $singleAbstract = $damUser->findAbstract($abstractId);
                /* Zuerst FirstAutor */
                $sql = "INSERT INTO `dam_abstract_zuordnungen` ( `autorid`, `abstractid`) VALUES (?,?)";
                $eintrag = $db->prepare($sql);
                $eintrag->bind_param('ii', $first, $abstractId);
                $eintrag->execute();
                // Pruefen ob der Eintrag efolgreich war
                if ($eintrag->affected_rows == 1) {
                    if ($singleAbstract != null) {
                        $singleAbstract->firstAutor = $damUser->findAutor($first);
                    } else {
                        echo "Abstract is null";
                    }
                } else {
                    # echo 'Der Eintrag konnte nicht hinzugef&uuml;gt werden.1';
                }
                $eintrag->close();
                #echo "<br> co autoren". $coAutoren;
                $autorenIds = explode(",", $coAutoren);
                #echo "count " . count($autorenIds);
                #print_r($autorenIds);
                foreach ($autorenIds as $coAutorid) {
                    if (trim($coAutorid) != "") {
                        $idd = -1;
                        $idd = trim($coAutorid);
                        $sql = "INSERT INTO `dam_abstract_zuordnungen` ( `autorid`, `abstractid`) VALUES (?,?)";
                        $e = $db->prepare($sql);
                        $e->bind_param('ii', $idd, $abstractId);
                        $e->execute();
                        // Pruefen ob der Eintrag efolgreich war
                        if ($e->affected_rows == 1) {
                            if ($singleAbstract != null) {
                                $singleAbstract->otherAutors[] = $damUser->findAutor($coAutorid);
                            } else {
                                echo "Coautor is null";
                            }
                        } else {
                            // MYSQL ERROR
                            echo 'Der Eintrag konnte nicht hinzugef&uuml;gt werden.2';
                        }
                        $e->close();
                    }
                }
                unset($coAutorid);

                /* Jetzt Dateien verschieben und löschen */

                /*echo $datei;
                if(is_file("../../../system/tmp/" . $datei))
                {
                    echo " datei gefunden";
                }
                else
                {
                    echo " datei nicht gefunden";
                }*/
                $handle = opendir("../../../system/tmp/");
                while ($file = readdir($handle)) {
                    if ($file != "." && $file != ".." && $datei != "") {
                        if ($file == $datei) {
                            copy("../../../system/tmp/" . $file, "../referenten/" . $damUser->username . "/" . $hash1);
                            /*copy("../../../system/tmp/" . $file, "../referenten/" . $damUser->username . "/" . html_entity_decode($file));*/
                            unlink("../../../system/tmp/" . $file);
                        }
                        if ($file == $dateii) {
                            copy("../../../system/tmp/" . $file, "../referenten/" . $damUser->username . "/" . $hash2);
                            /*copy("../../../system/tmp/" . $file, "../referenten/" . $damUser->username . "/" . html_entity_decode($file));*/
                            unlink("../../../system/tmp/" . $file);
                        }
                        if ($file == $dateiii) {
                            copy("../../../system/tmp/" . $file, "../referenten/" . $damUser->username . "/" . $hash3);
                            /*copy("../../../system/tmp/" . $file, "../referenten/" . $damUser->username . "/" . html_entity_decode($file));*/
                            unlink("../../../system/tmp/" . $file);
                        }
                    }
                }
                closedir($handle);


                $namen = "";
                // Email an CoAutoren
                foreach ($autorenIds as $coAutorid) {
                    if (trim($coAutorid) != "") {
                        $coaaaautor = $damUser->findAutor($coAutorid);
                        // Email versand
                        $namen .= "[ ".$coaaaautor->vorname." ".$coaaaautor->nachname." ] ";
                    }
                }

                // Email versand
                $an = "info@dam2013.org";
                $von = $damUser->vorname . " " . $damUser->nachname . " <" . $damUser->email . ">";
                $betreff = "DAM2013 Administration - Abstract eingereicht. Titel: " . $abstractTitel;
                $header = "From: $von";
                #$header .= "Subject: $betreff\r\n";
                #$header .= "Content-Type: text/plain\r\n";
                #$header .= "MIME-Version: 1.0\r\n";

                $msg = "Titel:                " . $abstractTitel . "\r\n\r\n";
                if ($vortragart == 1) {
                    $msg .= "Vortragsart:       Vortrag \r\n\r\n";
                } else if ($vortragart == 2) {
                    $msg .= "Vortragsart:       Kurzvortrag \r\n\r\n";
                } else if ($vortragart == 3) {
                    $msg .= "Vortragsart:       Vortrag oder Kurzvortrag \r\n\r\n";
                }
                $msg .= "1. Autor          ".$singleAbstract->firstAutor->vorname." ".$singleAbstract->firstAutor->nachname. "\r\n\r\n";
                $msg .= "Co-Autoren        ".$namen. "\r\n\r\n";
                $msg .= "Thema:            " . $thema . "\r\n\r\n";
                $msg .= "Hintergrund:      \r\n" . $hintergrund . "\r\n\r\n";
                $msg .= "Methoden:         \r\n" . $methoden . "\r\n\r\n";
                $msg .= "Ergebnisse:       \r\n" . $ergebnisse . "\r\n\r\n";
                $msg .= "Schlussfolgerung: \r\n" . $schlussfolgerung . "\r\n\r\n";

                if ($datei != "") {
                    $msg .= "Anhang: " . $filepath . $damUser->username . "/" . $hash1 . "\r\n";
                }

                if ($dateii != "") {
                    $msg .= "Anhang: " . $filepath . $damUser->username . "/" . $hash2 . "\r\n";
                }

                if ($dateiii != "") {
                    $msg .= "Anhang: " . $filepath . $damUser->username . "/" . $hash3 . "\r\n";
                }
                $tempmsg = $msg;

                if (mail($an, $betreff, $msg, $header)) {
                    if (isset($_SESSION['AJAX-FFL']['3']) && isset($_SESSION['AJAX-FFL']['3']['arrSessionFiles'])) {
                        foreach ($_SESSION['AJAX-FFL']['3']['arrSessionFiles'] as $key => $arrFile) {
                            unset($_SESSION['AJAX-FFL']['3']['arrSessionFiles'][$key]);
                        }
                    }

                    // Email an Autor
                    $an = $damUser->email;
                    $von = "info@dam2013.org";
                    $betreff = "DAM2013 - Sie haben folgendes Abstract eingereicht. Titel: " . $abstractTitel;
                    if(mail($an, $betreff, $msg, $header))
                    {

                        // Email an erstAutor
                        if($damUser->email != $singleAbstract->firstAutor->email)
                        {
                            $an = $singleAbstract->firstAutor->email;
                            $von = "info@dam2013.org";
                            $betreff = "DAM2013 - Sie sind 1. Autor eines Abstracts mit dem Titel: " . $abstractTitel;
                            $header = "From: $von";
                            $msg = "Zu Ihrer Information: Es wurde ein Abstract mit Ihnen als 1. Autor eingereicht.\r\n\r\n".$tempmsg."\r\nBitte antworten Sie nicht auf diese Email.\r\n\r\nIhr DAM2013 Team";
                            mail($an, $betreff, $msg, $header);
                        }

                        // Email an CoAutoren
                        foreach ($autorenIds as $coAutorid) {
                            if (trim($coAutorid) != "") {
                                $coaaaautor = $damUser->findAutor($coAutorid);
                                // Email versand

                                $an = $coaaaautor->email;
                                $von = "info@dam2013.org";
                                $betreff = "DAM2013 - Sie sind Co-Autor eines Abstracts mit dem Titel: " . $abstractTitel;
                                $header = "From: $von";
                                #$header .= "Subject: $betreff\r\n";
                                #$header .= "Content-Type: text/plain\r\n";
                                #$header .= "MIME-Version: 1.0\r\n";

                                $msg = "Zu Ihrer Information: Es wurde ein Abstract mit Ihnen als Co-Autor eingereicht.\r\n\r\n".$tempmsg."\r\nBitte antworten Sie nicht auf diese Email.\r\n\r\nIhr DAM2013 Team";
                                mail($an, $betreff, $msg, $header);
                            }
                        }
                        $_SESSION['goto'] = "abstract";
                    }




                } else {
                    echo "FEHLER";
                }





            }

        } else {
            #echo "Update nicht erfolgreich 3";
        }
        // Datenbankverbindung schliessen
        $db->close();
        unset($_SESSION['dam_uploadedfiles']);
        $_SESSION['user'] = serialize($damUser);
    }
} else {
    echo "Nope sorry little hacker";
}
?>