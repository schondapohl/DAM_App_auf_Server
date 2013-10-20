<?php
// Neues Datenbank-Objekt erzeugen
$mode = 1;

if ($mode == 1) {
    $db = @new mysqli('localhost', 'web229', '9HW3BYKY', 'usr_web229_1');
    $picpath = "http://www.dam2013.org/tl_files/dam/images/";
    $filepath = "http://www.dam2013.org/tl_files/dam/referenten/";
    $phppath = "http://www.dam2013.org/tl_files/dam/php/";
} else if ($mode == 2) {
    $db = @new mysqli('localhost', 'web251', 'i6X62edy', 'usr_web251_1');
    $picpath = "http://www.emzed.de/tl_files/dam/images/";
    $filepath = "http://www.emzed.de/tl_files/dam/referenten/";
    $phppath = "http://www.emzed.de/tl_files/dam/php/";
    $urlpath = "http://www.emzed.de/";
} else if ($mode == 0) {
    $db = @new mysqli('localhost', 'web251', 'i6X62edy', 'usr_web251_1');
    #$db = @new mysqli('localhost', 'root', 'root', 'damneu');
    $picpath = "http://localhost/contao/tl_files/dam/images/";
    $filepath = "http://localhost/contao/tl_files/dam/referenten/";
    $phppath = "http://localhost/contao/tl_files/dam/php/";
}

?>