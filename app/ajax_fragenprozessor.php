<?php

include('dbconnection.php');
include('frage.php');
if (mysqli_connect_errno() == 0) {
    if ($_GET['mode'] == "getStatus") {
        $sql = "SELECT * FROM `app_fragenprozess` where frageid=\"" . $_GET['frage'] . "\"";
        $ergebnis = $db->query($sql);
        $anzahl = $ergebnis->num_rows;
        $status = null;
        $frage = null;
        $antworta = null;
        $antwortb = null;
        $antwortc = null;
        $antwortd = null;
        while ($zeile = $ergebnis->fetch_object()) {
            $status = $zeile->status;
        }
        unset($zeile);
        $ergebnis->close();

        if ($status == null) {
            $sql = "INSERT INTO `app_fragenprozess`(`frageid`, `status`, `gestartet`) VALUES (?,?,?)";
            $eintrag = $db->prepare($sql);
            $tempStatus = 1;
            $time = time();
            $eintrag->bind_param('sii', $_GET['frage'], $tempStatus, $time);
            $eintrag->execute();
            // Pruefen ob der Eintrag efolgreich war
            if ($eintrag->affected_rows == 1) {
                $fragenSql = "SELECT * FROM app_fragen where frageid=\"" . $_GET['frage'] . "\"";
                $fragenerg = $db->query($fragenSql);
                while ($z = $fragenerg->fetch_object()) {
                    $frage = $z->frage;
                    $antworta = $z->antworta;
                    $antwortb = $z->antwortb;
                    $antwortc = $z->antwortc;
                    $antwortd = $z->antwortd;
                }
                $response = array(
                    'gestartet' => false,
                    'beendet' => false,
                    'neu' => true,
                    'frage' => $frage,
                    'typ' => 1,
                    'a' => $antworta,
                    'b' => $antwortb,
                    'c' => $antwortc,
                    'd' => $antwortd,
                    'fid' => $_GET['frage']
                );
            }
        }
        if ($status == 1) {
            $response = array(
                'gestartet' => true,
                'beendet' => false,
                'neu' => false,
                'typ' => 1
            );
        }
        if ($status == 2) {
            $response = array(
                'gestartet' => false,
                'beendet' => true,
                'neu' => false,
                'typ' => 1
            );
        }
        #print_r($response);
        $data = json_encode($response);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    } else if ($_GET['mode'] == "end") {
        $sql = "Update app_fragenprozess set status = ?, beendet = ? where frageid=\"" . $_GET['frage'] . "\"";
        $eintrag = $db->prepare($sql);
        $time = time();
        $tempStatus = 2;
        $eintrag->bind_param('ii', $tempStatus, $time);
        $eintrag->execute();
        // Pruefen ob der Eintrag efolgreich war
        if ($eintrag->affected_rows == 1) {
            $sql = "SELECT * FROM `app_antworten` where frageid=\"" . $_GET['frage'] . "\"";
            $ergebnis = $db->query($sql);
            $anzahl = $ergebnis->num_rows;
            $status = null;
            $frage = null;
            $antworta = 0;
            $antwortb = 0;
            $antwortc = 0;
            $antwortd = 0;
            while ($zeile = $ergebnis->fetch_object()) {
                if ($zeile->antwort == 'a') {
                    $antworta = $antworta + 1;
                } else if ($zeile->antwort == 'b') {
                    $antwortb = $antwortb + 1;
                } else if ($zeile->antwort == 'c') {
                    $antwortc = $antwortc + 1;
                } else if ($zeile->antwort == 'd') {
                    $antwortd = $antwortd + 1;
                }
            }
            $response = array(
                'gestartet' => false,
                'beendet' => true,
                'neu' => false,
                'typ' => 2,
                'a' => $antworta,
                'b' => $antwortb,
                'c' => $antwortc,
                'd' => $antwortd,
                'fid' => $_GET['frage']
            );

            $data = json_encode($response);
            echo $_GET['jsonp_callback'] . '(' . $data . ')';
        }
    } else if ($_GET['mode'] == "refreshall") {
        $sql = "DELETE FROM app_fragenprozess where frageid=\"" . $_GET['fid'] . "\"";
        $eintrag = $db->prepare($sql);
        $eintrag->execute();

        $sql = "DELETE FROM app_antworten where frageid =\"" . $_GET['fid'] . "\"";
        $eintrag = $db->prepare($sql);
        $eintrag->execute();

        $response = array(
            'done' => true
        );
        $data = json_encode($response);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }
    else if ($_GET['mode'] == "delete") {

        $sql = "DELETE FROM app_fragen where frageid=\"" . $_GET['fid'] . "\"";
        $eintrag = $db->prepare($sql);
        $eintrag->execute();

        $sql = "DELETE FROM app_fragenprozess where frageid=\"" . $_GET['fid'] . "\"";
        $eintrag = $db->prepare($sql);
        $eintrag->execute();

        $sql = "DELETE FROM app_antworten where frageid =\"" . $_GET['fid'] . "\"";
        $eintrag = $db->prepare($sql);
        $eintrag->execute();

        $response = array(
            'done' => true
        );
        $data = json_encode($response);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }
}

?>