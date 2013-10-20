<?php

include('dbconnection.php');

if (mysqli_connect_errno() == 0) {
    if ($_GET['mode'] == "log") {
        $response = array(
            'erstellt' => false
        );
        $sql = "INSERT INTO `app_log`(`name`, `os`, `geraeteid`, `modell`, `version`, `aktion`, `zeitpunkt`) VALUES (?,?,?,?,?,?,?)";
        $eintrag = $db->prepare($sql);
        $time = time();
        $status = 0;
        $fid = md5($time);
        $eintrag->bind_param('ssssssi', $fid, $_GET['dname'], $_GET['dos'], $_GET['did'], $_GET['dmodell'], $_GET['daktion'], $time());
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