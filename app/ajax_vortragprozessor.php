<?php

include('dbconnection.php');
include('frage.php');
if (mysqli_connect_errno() == 0) {
    if ($_GET['mode'] == "loeschen") {
        $sql = "DELETE FROM app_vortragprozess where vid=\"" . $_GET['fid'] . "\"";
        $eintrag = $db->prepare($sql);
        $eintrag->execute();
        if ($eintrag->num_rows == 1 || $eintrag->affected_rows == 1) {
            $response = array(
                'vid' => $_GET['vid'],
                typ => 3,
                aktion => "loeschen"
            );
        } else {
            $response = array(
                'geloescht' => false
            );
        }
        $data = json_encode($response);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }
    if ($_GET['mode'] == "aktivieren") {
        $sql = "Update app_vortragprozess set aktiv = ? where vid= ?";
        $eintrag = $db->prepare($sql);
        $aktivStatus = 1;
        $eintrag->bind_param('is', $aktivStatus, $_GET['vid']);
        $eintrag->execute();
        if ($eintrag->affected_rows == 1) {
            $response = array(
                'vid' => $_GET['vid'],
                'typ' => 3,
                'aktion' => "aktivieren"
            );
        } else {
            $response = array(
                'aktion' => false
            );
        }
        $data = json_encode($response);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }

    if ($_GET['mode'] == "deaktivieren") {
        $sql = "Update app_vortragprozess set aktiv = ? where vid= ?";
        $eintrag = $db->prepare($sql);
        $inaktivStatus = 0;
        $eintrag->bind_param('is', $inaktivStatus, $_GET['vid']);
        $eintrag->execute();
        if ($eintrag->affected_rows == 1) {
            $response = array(
                'vid' => $_GET['vid'],
                'typ' => 3,
                'aktion' => "deaktivieren"
            );
        } else {
            $response = array(
                'aktion' => false
            );
        }
        $data = json_encode($response);
        echo $_GET['jsonp_callback'] . '(' . $data . ')';
    }
}

?>