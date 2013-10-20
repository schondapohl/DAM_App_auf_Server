<?php

include('dbconnection.php');

if (mysqli_connect_errno() == 0) {
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