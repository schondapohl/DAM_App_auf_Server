<?php

include('dbconnection.php');

if (mysqli_connect_errno() == 0) {

    if ($_GET['mode'] == "init") {
        $sql = "SELECT * FROM app_fragen";
        $ergebnis = $db->query($sql);
        while ($zeile = $ergebnis->fetch_object()) {
            ?>
        <div class="round fragenWrapper">
            <div class="startAction round"><?php  echo $zeile->fautor . ": " . $zeile->frage; ?></div>
            <ul id="icons" class="ui-widget ui-helper-clearfix ullist">
                <li id="del_<?php echo $zeile->frageid; ?>" class="ui-state-default ui-corner-all"
                    title="Umfrage löschen">
                    <span class="ui-icon ui-icon-trash"></span>
                </li>
                <li id="reset_<?php echo $zeile->frageid; ?>" class="ui-state-default ui-corner-all hidden"
                    title="Umfrage zurücksetzen">
                    <span class="ui-icon ui-icon-circle-close"></span>
                </li>
                <li id="resulth_<?php echo $zeile->frageid; ?>" class="ui-state-default ui-corner-all hidden"
                    title="Ergebnis ausblenden">
                    <span class="">Ergebnis ausblenden</span>
                </li>
                <li id="result_<?php echo $zeile->frageid; ?>" class="ui-state-default ui-corner-all hidden"
                    title="Ergebnis anzeigen">
                    <span class="">Ergebnis anzeigen</span>
                </li>
                <li id="start_<?php echo $zeile->frageid; ?>" class="ui-state-default ui-corner-all"
                    title="Umfrage starten">
                    <span class="">Umfrage starten</span>
                </li>
            </ul>
            <div style="clear:both;">
                <table id="auswahl_<?php echo $zeile->frageid; ?>" class="antwortenTabelle hidden" cellspacing=20>
                    <tr>
                        <td class="round">A: <?php echo $zeile->antworta; ?></td>
                        <td class="round">B: <?php echo $zeile->antwortb; ?></td>
                        <td rowspan=2 style="background: none; border:none">
                            <div id="countdown_dashboard_<?php echo $zeile->frageid; ?>" class="hidden">
                                <div class="dash seconds_dash">
                                    <span class="dash_title">Sekunden</span>

                                    <div class="digit">0</div>
                                    <div class="digit">0</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="round">C: <?php echo $zeile->antwortc; ?></td>
                        <td class="round">D: <?php echo $zeile->antwortd; ?></td>
                        <td style="background: none;border:none;"></td>
                    </tr>
                </table>
            </div>
            <div class="rWrapper round hidden" id="rw__<?php echo $zeile->frageid; ?>">
                <canvas id="chartCanvas_<?php echo $zeile->frageid; ?>" width="600" height="400">
                    Your web-browser does not support the HTML 5 canvas element.
                </canvas>
                <div id="padder__<?php echo $zeile->frageid; ?>" style="clear: both;padding:30px" class="hidden"></div>
            </div>
            <div style="clear: both;"></div>
            <!-- Countdown dashboard start -->
        </div>

        <?php
        }
        unset($zeile);
        $ergebnis->close();
    }

    if ($_GET['mode'] == "leseErgebnisse") {
        $sql = "SELECT * FROM `app_antworten` where frageid=\"" . $_GET['fid'] . "\"";
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
            'fid' => $_GET['fid']
        );

        $data = json_encode($response);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }

    if ($_GET['mode'] == "initGuiElements") {
        $sql = "SELECT * FROM app_fragenprozess ";
        $ergebnis = $db->query($sql);
        $anzahl = $ergebnis->num_rows;
        $status = null;
        $frage = null;
        $response = array();
        while ($zeile = $ergebnis->fetch_object()) {
            $status = $zeile->status;
            $fid = $zeile->frageid;
            if ($status == 2 || $status == 1) {
                $datensatz = array(
                    'gestartet' => false,
                    'beendet' => true,
                    'neu' => false,
                    'typ' => 2,
                    'fid' => $fid
                );
                $response[] = $datensatz;
            }
        }
        unset($zeile);
        $ergebnis->close();

        #print_r($response);
        $data = json_encode($response);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }

    if ($_GET['mode'] == "erstellen") {
        $response = array(
            'erstellt' => false
        );
        $sql = "INSERT INTO `app_fragen`(`frageid`, `frage`, `antworta`, `antwortb`, `antwortc`, `antwortd`, `fautor`) VALUES (?,?,?,?,?,?,?)";
        $eintrag = $db->prepare($sql);
        $time = time();
        $status = 0;
        $fid = md5($time);
        #$date_time = strtotime($_GET['vstart'] . ":00 GMT"); // works great!
        #$date_time2 = strtotime($_GET['vend'] . ":00 GMT"); // works great!
        $eintrag->bind_param('sssssss', $fid, $_GET['pfrage'], $_GET['pa'], $_GET['pb'], $_GET['pc'], $_GET['pd'], $_GET['pautor']);
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
}

?>