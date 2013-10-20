<?php
session_start();
include('dbconnection.php');

if (mysqli_connect_errno() == 0) {
    if ($_GET['mode'] == "userlogindone") {
        // Zuerst werden die Vortraege ausgelesen
        $sql = "select v.* from app_vortragprozess v order by vstart";
        $ergebnis = $db->query($sql);
        $retValue = array();
        while ($zeile = $ergebnis->fetch_object()) {
            // Für jeden Vortrag wird ein Punkte Array festegelegt
            $eigenePunkteArray = array();
            $gesPunkteArray = array();
            $innersql = "SELECT * FROM app_vortragbewertung where vid='" . $zeile->vid . "'";
            $innererg = $db->query($innersql);
            while ($inner = $innererg->fetch_object()) {
                if ($inner->uid == $_GET['uid']) {
                    $k = array();
                    $k['kriterium'] = $inner->kriterium;
                    $k['punkte'] = $inner->punkte;
                    $eigenePunkteArray[] = $k;
                } else {
                    $k = array();
                    $k['kriterium'] = $inner->kriterium;
                    $k['punkte'] = $inner->punkte;
                    $gesPunkteArray[] = $k;
                }
            }
            $vortrag = array(
                'vid' => $zeile->vid,
                'vtitel' => $zeile->vtitel,
                'vautor' => $zeile->vautor,
                'vstart' => $zeile->vstart,
                'vend' => $zeile->vtitel,
                'vbeschreibung' => $zeile->vtitel,
                'aktiv' => $zeile->aktiv,
                'eigenePunkte' => $eigenePunkteArray,
                'gesPunkte' => $gesPunkteArray,
                'keins' => "Thema",
                'kzwei' => "Aktualität",
                'kdrei' => "Inhalt"
            );
            $retValue[] = $vortrag;
        }
        $data = json_encode($retValue);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }

    if ($_GET['mode'] == "bewerten") {
        $response = array(
            'erstellt' => false
        );
        $kritWert = 1;
        $done1 = false;
        $done2 = false;
        $done3 = false;
        $sql = "INSERT INTO `app_vortragbewertung`(`vid`, `uid`, `punkte`, `kriterium`) VALUES (?,?,?,?)";
        $eintrag = $db->prepare($sql);
        $eintrag->bind_param('ssii', $_GET['vid'], $_GET['u'], $_GET['a'], $kritWert);
        $eintrag->execute();
        if ($eintrag->affected_rows == 1) {
            $done1 = true;
        } else {
            $done1 = false;
        }
        $kritWert = 2;
        $sql = "INSERT INTO `app_vortragbewertung`(`vid`, `uid`, `punkte`, `kriterium`) VALUES (?,?,?,?)";
        $eintrag = $db->prepare($sql);
        $eintrag->bind_param('ssii', $_GET['vid'], $_GET['u'], $_GET['b'], $kritWert);
        $eintrag->execute();
        if ($eintrag->affected_rows == 1) {
            $done2 = true;
        } else {
            $done2 = false;
        }
        $kritWert = 3;
        $sql = "INSERT INTO `app_vortragbewertung`(`vid`, `uid`, `punkte`, `kriterium`) VALUES (?,?,?,?)";
        $eintrag = $db->prepare($sql);
        $eintrag->bind_param('ssii', $_GET['vid'], $_GET['u'], $_GET['c'], $kritWert);
        $eintrag->execute();
        if ($eintrag->affected_rows == 1) {
            $done3 = true;
        } else {
            $done3 = false;
        }
        $response = array(
            'a' => $done1,
            'b' => $done2,
            'c' => $done3
        );
        $data = json_encode($response);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }

    if ($_GET['mode'] == "init") {
        $sql = "SELECT * FROM app_vortragprozess";
        $ergebnis = $db->query($sql);
        while ($zeile = $ergebnis->fetch_object()) {
            ?>
        <div class="round fragenWrapper">
            <div class="startAction round"><?php  echo $zeile->vautor . ": " . $zeile->vtitel; ?></div>
            <ul id="icons" class="ui-widget ui-helper-clearfix ullist">
                <li id="loeschenVortrag_<?php echo $zeile->vid; ?>" class="ui-state-default ui-corner-all"
                    title="Vortrag löschen">
                    <span class="ui-icon ui-icon-trash"></span>
                </li>
                <li id="aktiviereVortrag_<?php echo $zeile->vid; ?>"
                    class="ui-state-default ui-corner-all <?php if ($zeile->aktiv != 0) echo "hidden"; ?>"
                    title="Vortrag freischalten">
                    <span class="">Vortrag aktivieren</span>
                </li>
                <li id="deaktiviereVortrag_<?php echo $zeile->vid; ?>"
                    class="ui-state-default ui-corner-all <?php if ($zeile->aktiv != 1) echo "hidden"; ?>"
                    title="Vortrag deaktivieren">
                    <span class="">Vortrag deaktivieren</span>
                </li>
            </ul>
            <div id="ergH__<?php echo $zeile->vid; ?>" class="hidden resultatH">Ergebnis</div>
            <div id="r__<?php echo $zeile->vid; ?>" class="hidden resultatdiv"></div>
            <div id="r_wrapper__<?php echo $zeile->vid; ?>" class="hidden">
            </div>
            <div style="clear: both;"></div>
        </div>
        <?php
        }
        unset($zeile);
        $ergebnis->close();
    }


    if ($_GET['mode'] == "leseAbstracts") {
        $sql = "SELECT a.firstname, a.lastname, a.email, a.lastLogin, a.createdOn, d.* FROM tl_member a, dam_abstract d where a.id = d.uid order by a.lastname";
        $teilnehmerErg = $db->query($sql);
        echo "<select class=\"round einput\" size=1 id=\"aidselection\" onChange=\"leseAbstractDaten(this.value);\">";
        echo "<option value=\"-1\" selected></option>";
        if ($teilnehmerErg->num_rows > 0) {
            while ($zeile = $teilnehmerErg->fetch_object()) {
                echo "<option value=\"" . $zeile->abstractid . "\">[" . $zeile->firstname . " " . $zeile->lastname . "] " . $zeile->titel . "</option>";
            }
        }
        echo "</select>";
    }

    if ($_GET['mode'] == "beschreibungSpeichern") {
        unset($_SESSION['beschreibung']);
        $_SESSION['beschreibung'] = $_GET['b'];
        $retValue = array();
        $retValue['done'] = true;
        $data = json_encode($retValue);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }

    if ($_GET['mode'] == "leseAbstractDaten") {
        // Zuerst werden die Vortraege ausgelesen
        $sql = "SELECT a.firstname, a.lastname, d.* FROM tl_member a, dam_abstract d where a.id = d.uid and d.abstractid =" . $_GET['aid'];
        $ergebnis = $db->query($sql);
        $retValue = array();
        while ($zeile = $ergebnis->fetch_object()) {
            $retValue = array(
                'vorname' => $zeile->firstname,
                'nachname' => $zeile->lastname,
                'titel' => $zeile->titel,
                'hintergrund' => $zeile->hintergrund
            );
        }
        $data = json_encode($retValue);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }

    if ($_GET['mode'] == "erstellen") {
        $response = array(
            'erstellt' => false
        );

        $sql = "INSERT INTO `app_vortragprozess`(`vid`, `vtitel`, `vautor`, `vbeschreibung`, `vstart`, `vend`, `aktiv`,`tag`, `pause`, `aid` ) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $eintrag = $db->prepare($sql);
        $pause = $_GET['pause'];

        if ($pause == 1) {
            $pause = 1;
        } else {
            $pause = 0;
        }
        $tag = $_GET['tag'];
        setlocale(LC_TIME, "de_DE");
        $time = time();
        $status = 0;
        $vid = md5($time);
        $date_time = strtotime($_GET['vstart'] . ":00"); // works great!
        $date_time2 = strtotime($_GET['vend'] . ":00"); // works great!
        //$eintrag->bind_param('ssssiiiiii', $vid, $_GET['vtitel'], $_GET['vautor'], $_GET['vbeschreibung'], $date_time, $date_time2, $status, $tag, $pause, $_GET['aid']);
        $eintrag->bind_param('ssssiiiiii', $vid, $_GET['vtitel'], $_GET['vautor'], $_SESSION['beschreibung'], $date_time, $date_time2, $status, $tag, $pause, $_GET['aid']);
        $eintrag->execute();

        // Pruefen ob der Eintrag efolgreich war
        if ($eintrag->affected_rows == 1) {
            $response = array(
                'erstellt' => true
            );
        } else {
            $response = array(
                'erstellt' => false
            );
        }
        $data = json_encode($response);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }

    if ($_GET['mode'] == "logindone") {
        $response = array(
            'erstellt' => false
        );

        $sql = "SELECT * FROM app_vortragprozess";
        $aktiveVortraege = "";
        while ($zeile = $ergebnis->fetch_object()) {
            if ($zeile->aktiviert == 1) {
                $aktiveVortraege = $aktiveVortraege . $zeile->vid;
            }
        }
        $response = array(
            'aktiv' => $aktiveVortraege
        );

        $data = json_encode($response);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }

}

?>