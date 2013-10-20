<?php

include('dbconnection.php');
if (mysqli_connect_errno() == 0) {
    if ($_GET['mode'] == "generiereProgramm") {
        // Zuerst werden die Vortraege ausgelesen
        $sql = "select v.* from app_vortragprozess v order by tag, vstart, vend";
        $ergebnis = $db->query($sql);
        $retValue = array();
        while ($zeile = $ergebnis->fetch_object()) {
            $programmpunkt = array();
            $programmpunkt['titel'] = $zeile->vtitel;
            $programmpunkt['person'] = $zeile->vautor;
            $programmpunkt['von'] = date("H:i", $zeile->vstart);
            $programmpunkt['bis'] = date("H:i", $zeile->vend);
            $programmpunkt['hintergrund'] = $zeile->vbeschreibung;
            $programmpunkt['pause'] = $zeile->pause;
            $programmpunkt['tag'] = $zeile->tag;
            $programmpunkt['vid'] = $zeile->vid;
            $retValue[] = $programmpunkt;
        }
        $data = json_encode($retValue);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }

    if ($_GET['mode'] == "waslaeuftjetzt") {
        setlocale(LC_TIME, "de_DE");
        $time = time();
        $retValue = array();
        $sql = "SELECT * FROM `app_vortragprozess` where vstart <  " . $time . " and vend > " . $time . " ";
        $waslaeuftErg = $db->query($sql);
        $html = "";
        if ($waslaeuftErg->num_rows > 0) {
            while ($zeile = $waslaeuftErg->fetch_object()) {
                $retValue['gelesen'] = true;
                $retValue['info'] = "<b>".$zeile->vautor . ":</b> " . $zeile->vtitel;
            }
        } else {
            $retValue['gelesen'] = false;
        }
        $data = json_encode($retValue);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }
    if ($_GET['mode'] == "generierTeilnehmerListe") {
        $sql = "SELECT a.firstname, a.lastname, a.email, a.lastLogin, a.createdOn, d.* FROM tl_member a, dam_anmeldungen d where a.id = d.uid and d.anmeldestatus = 1 order by a.lastname";
        $teilnehmerErg = $db->query($sql);
        if ($teilnehmerErg->num_rows > 0) {
            while ($zeile = $teilnehmerErg->fetch_object()) {
                $teilnehmerPerson = array();
                $teilnehmerPerson['info'] = $zeile->lastname . ", " . $zeile->firstname;
                $retValue[] = $teilnehmerPerson;
            }
        }
        $data = json_encode($retValue);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }
    if ($_GET['mode'] == "generiereAutorenliste") {
        $sql = "select distinct a.* from tl_member a, dam_abstract b where a.id = b.uid order by a.lastname";
        $teilnehmerErg = $db->query($sql);
        if ($teilnehmerErg->num_rows > 0) {
            while ($zeile = $teilnehmerErg->fetch_object()) {
                $autor = array();
                $autor['name'] = $zeile->lastname . ", " . $zeile->firstname;
                $autor['institut'] = $zeile->company;
                $autor['strasse'] = $zeile->street;
                $autor['ort'] = $zeile->postal . " " . $zeile->city;
                $retValue[] = $autor;
            }
        }
        $data = json_encode($retValue);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }

    if ($_GET['mode'] == "leseMehrZuVortrag") {
        $retValue = array();
        $sql = "SELECT a.* FROM `dam_abstract` a, app_vortragprozess b WHERE a.abstractid = b.aid and b.vid = \"" . $_GET['id'] . "\"";
        $teilnehmerErg = $db->query($sql);
        $htmlcode = "";
        if ($teilnehmerErg->num_rows > 0) {
            while ($zeile = $teilnehmerErg->fetch_object()) {
                $htmlcode = $htmlcode . "<p><div><b>Methoden</b></div>";
                $htmlcode = $htmlcode . "<div class=\"fontnormal\">" . $zeile->methoden . "</div></p>";
                $htmlcode = $htmlcode . "<p><div><b>Ergebnisse</b></div>";
                $htmlcode = $htmlcode . "<div class=\"fontnormal\">" . $zeile->ergebnisse . "</div></p>";
                $htmlcode = $htmlcode . "<p><div><b>Schlussfolgerung</b></div>";
                $htmlcode = $htmlcode . "<div class=\"fontnormal\">" . $zeile->schlussfolgerung . "</div></p>";
                $retValue['response'] = $htmlcode;
            }
        }
        $data = json_encode($retValue);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }


}

?>