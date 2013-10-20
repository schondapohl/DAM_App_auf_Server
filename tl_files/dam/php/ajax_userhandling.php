<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markus
 * Date: 14.02.13
 * Time: 20:07
 * To change this template use File | Settings | File Templates.
 */

session_start();
include("dbconnection.php");
include("damuser.php");
include("damabstract.php");
include("damautor.php");
include("damadresse.php");

$damUser = unserialize($_SESSION['user']);

if (isset($_POST['mode'])) {
    if ($_POST['mode'] == "anmeldung") {
        if (mysqli_connect_errno() == 0) {
            $atyp = $_POST['atyp'];
            $kosten = explode("#", $_POST['paket']);
            $paketString = "";
            $counter = 1;
            $zusatzkosten = 0;
            foreach ($kosten as $a) {
                if(trim($a) != 0 )
                {
                    if($counter == 1)
                    {
                        $paketString = "Rabatt 1 ";
                    }
                    else if($counter == 2)
                    {
                        $paketString .= "Rabatt 2 ";
                    }
                    else if($counter == 3)
                    {
                        $paketString .= "Rabatt 3 ";
                    }
                    else if($counter == 4)
                    {
                        $paketString .= "Festabend  ";
                    }
                    else if($counter == 5)
                    {
                        $paketString .= "Zusatzperson 1 ";
                    }
                    else if($counter == 6)
                    {
                        $paketString .= "Zusatzperson 2 ";
                    }
                    else if($counter == 7)
                    {
                        $paketString .= "Zusatzperson 3 ";
                    }
                    $zusatzkosten += trim($a);
                }
                $counter = $counter + 1;
            }

            $damUser->anmeldungstyp = $atyp;
            #echo "a ".$atyp;
            #echo " anmeldungstyp ".$damUser->anmeldungstyp;
            #echo " pakete ".$paketString;
            #echo " grundbetrag ".$damUser->getBetrag();
            #echo " zusatzkosten ".$zusatzkosten;

            $status = 1;
            $grundbetrag = $damUser->getBetrag();
            $sql = "INSERT INTO dam_anmeldungen(userid,anmeldetyp,anmeldestatus, grundkosten, zusatzpaket, zusatzkosten, anmeldedatum, uid ) VALUES (?,?,?,?,?,?,?,?)";
            $e = $db->prepare($sql);
            $datum = time();
            $e->bind_param('siidsdii', $damUser->userhash, $atyp, $status, $grundbetrag, $paketString, $zusatzkosten, $datum, $damUser->userid);
            $e->execute();
            // Pruefen ob der Eintrag efolgreich war
            if ($e->affected_rows == 1) {
                $damUser->kongressstatus = 1;
                $damUser->anmeldungstyp = $atyp;

                if($damUser->email != null)
                {
                    $an = $damUser->email;
                    $von = "kongressanmeldung@dam2013.org";
                    $betreff = "Sie haben sich für den DAM 2013 Kongress angemeldet: ";
                    $header = "From: $von";
                    $gesBetrag = $grundbetrag+$zusatzkosten;
                    #$msg = "Sie haben sich für den DAM 2013 Kongress verbindlich angemeldet.\r\nBitte überweisen Sie den Betrag von ".$gesBetrag ." Euro auf das folgende Konto:\r\n\r\nInhaber: HeiMed Dienstleistungs-GmbH\r\nKontonummer: 144 655 0\r\nBankleitzahl: 672 202 86\r\nInstitut: HypoVereinsbank AG Filiale Heidelberg\r\nIBAN: DE78672202860001446550\r\nBIC: HYVEDEMM479\r\nVerwendungszweck: DAM 2013 + Ihren Namen\r\n\r\nSobald wir den Betrag verbuchen konnten, erhalten Sie von uns eine Bestätigungs-Email. Die Originalrechnung erhalten Sie auf dem Kongress in Deidesheim.\r\n\r\nWir freuen uns Sie im November begrüßen zu dürfen und verbleiben\r\n\r\nMit freundlichen Grüßen\r\n\r\nIhr DAM 2013 Team";
                    #$msg = "Sie haben sich für den DAM 2013 Kongress angemeldet.\r\n\r\nBitte überweisen Sie den Betrag von ".$gesBetrag ." Euro auf unser Konto. Die Kontodaten finden Sie auf unserer Homepage.\r\n\r\nSobald wir das Geld verbuchen konnten, erhalten Sie von uns eine Bestätigung. Die Originalrechnung erhalten Sie auf dem Kongress in Deidesheim.\r\n\r\nIhr DAM2013 Team";
                    $msg = "Sie haben sich verbindlich für die diesjährige Jahresversammlung der deutschsprachigen Arbeitsgemeinschaft für Mikrochirurgie (DAM) in Deidesheim angemeldet.\r\n\r\nSie erhalten Ihre Rechnung mit dem entsprechendem Betrag postalisch an die in Ihrem Online-Account hinterlegte Adresse.\r\nLetztgenannte bitten wir nach erfolgreichem Login unter \"Organisation\" -> \"Meine Daten\" zu kontrollieren und ggf. zu ändern.\r\n\r\nDie Rechnung wird in 2 Tagen versendet.\r\n\r\nWir freuen uns Sie in Deidesheim im Novemeber begrüßen zu dürfen\r\n\r\nMit freundlichen Grüßen\r\n\r\nIhr DAM 2013 Team";
                    mail($an, $betreff, $msg, $header);

                    $von = $damUser->email;
                    $an = "kongressanmeldung@dam2013.org";
                    $betreff = "Kongressanmeldung von ". $damUser->vorname. " " .$damUser->nachname ;
                    $header = "From: $von";
                    $msg = "Der User hat sich für den Kongress angemeldet und muss ".$gesBetrag ." Euro auf das genannte Konto...\r\n\r\n überweisen.\r\n\r\nDAM2013 Team";
                    mail($an, $betreff, $msg, $header);
                }


            }
        }
    }
    else if ($_POST['mode'] == "pay")
    {
        $userid = $_POST['a'];
        $status = $_POST['b'];
        $zeitpunkt = time();
        if($status == 1)
        {
            $zeitpunkt = null;
        }
        $sql = "Update dam_anmeldungen set anmeldestatus = ?, bezahlt = ? where uid = ? ";
        $eintrag = $db->prepare($sql);
        $eintrag->bind_param('iii', $status, $zeitpunkt, $userid);
        $eintrag->execute();
    }
}

unset($_SESSION['user']);
$_SESSION['user'] = serialize($damUser);
?>