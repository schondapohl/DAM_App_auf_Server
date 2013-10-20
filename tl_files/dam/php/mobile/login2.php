<?php





$MSV = array(
    'foo'  => 'Superaxl Anweisung A: Metasyntaktische Variable',
    'bar'  => 'Anweisung B: foo bar baz',
    'baz'  => 'Anweisung C: Antwort auf das Leben, das Universum und den ganzen Rest',
    'axl'  => $_GET['wert']
);

$data = json_encode($MSV);
echo $_GET['jsonp_callback'] . '(' . $data . ');';

/* prints:
({"foo":"Anweisung A: Metasyntaktische Variable","bar":"Anweisung B: foo bar baz","baz":"Anweisung C: Antwort auf das Leben, das Universum und den ganzen Rest"});
*/
?>


<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");

echo print_r($_POST);
include("../dbconnection.php");
$inputPassword = $_POST['password'];
$username = $_POST['username'];
$dbPassword = "";
$sql = "SELECT * FROM `tl_member` where username='".$username."'";
$ergebnis = $db->query($sql);
while ($zeile = $ergebnis->fetch_object()) {
    $dbPassword = $zeile->password;
    #echo "<br/>DB Password: ".$dbPassword;
}

$blnAuthenticated = false;
list($strPassword, $strSalt) = explode(':', $dbPassword);

// Password is correct but not yet salted
if (!strlen($strSalt) && $strPassword == sha1($inputPassword)) {
    $strSalt = substr(md5(uniqid(mt_rand(), true)), 0, 23);
    $strPassword = sha1($strSalt . $inputPassword);
    $this->password = $strPassword . ':' . $strSalt;
}

// Check the password against the database
if (strlen($strSalt) && $strPassword == sha1($strSalt . $inputPassword)) {
    $blnAuthenticated = true;
}

if($blnAuthenticated)
{
    $response["error"] = 1;
    $response["error_msg"] = "Events not found";
    echo json_encode($response);
}
#echo  "<br/>erfolgreich ";
else
    echo "<br/>nicht erfolgreich";

?>