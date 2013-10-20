<?php

include('dbconnection.php');
if (mysqli_connect_errno() == 0) {
    if ($_GET['mode'] == "abstimmen") {
        $sql = "INSERT INTO `app_antworten`(`frageid`, `userid`, `antwort`) VALUES (?,?,?)";
        $eintrag = $db->prepare($sql);
        $eintrag->bind_param('sss', $_GET['fid'], $_GET['u'], $_GET['a']);
        $eintrag->execute();
        // Pruefen ob der Eintrag efolgreich war
        if ($eintrag->affected_rows == 1) {
            $response = array(
                'gezaehlt' => true
            );
            $data = json_encode($response);
            echo $_GET['jsonp_callback'] . '(' . $data . ')';
        } else {
            $response = array(
                'gezaehlt' => false
            );
            $data = json_encode($response);
            echo $_GET['jsonp_callback'] . '(' . $data . ')';
        }
    }

    if ($_GET['mode'] == "pruefeAufAktiveUmfrage") {
        $sql = "SELECT * FROM `app_fragenprozess` where status=1";
        $ergebnis = $db->query($sql);
        $anzahl = $ergebnis->num_rows;
        $fragenid = null;
        $frage = "";
        if ($anzahl == 1) {
            while ($zeile = $ergebnis->fetch_object()) {
                $status = $zeile->status;
                $fragenid = $zeile->frageid;
            }
            $fragenSql = "SELECT * FROM app_fragen where frageid=\"" . $fragenid . "\"";
            $fragenerg = $db->query($fragenSql);
            while ($z = $fragenerg->fetch_object()) {
                $frage = $z->frage;
                $antworta = $z->antworta;
                $antwortb = $z->antwortb;
                $antwortc = $z->antwortc;
                $antwortd = $z->antwortd;
            }

            $bereitsabgestimmt = false;
            $antwortenSQL = "SELECT * FROM `app_antworten` where frageid=\"" . $fragenid . "\" and userid = \"" . $_GET['gid'] . "\"";
            $antwortenErg = $db->query($antwortenSQL);
            $anzahl = $antwortenErg->num_rows;
            if ($anzahl != 0) {
                $bereitsabgestimmt = true;
            }
            $response = array(
                'gestartet' => true,
                'beendet' => false,
                'neu' => false,
                'frage' => $frage,
                'typ' => 1,
                'a' => $antworta,
                'b' => $antwortb,
                'c' => $antwortc,
                'd' => $antwortd,
                'fid' => $fragenid,
                'abgestimmt' => $bereitsabgestimmt
            );
            unset($zeile);
            $ergebnis->close();
            $data = json_encode($response);
            echo $_GET['jsonp_callback'] . '(' . $data . ')';
        }
    }
}

?>