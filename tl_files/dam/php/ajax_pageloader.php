<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markus
 * Date: 17.02.13
 * Time: 12:04
 * To change this template use File | Settings | File Templates.
 */

session_start();
if (isset($_POST["mode"])) {
    $_SESSION['pageloader'] = $_POST["mode"];
}
?>