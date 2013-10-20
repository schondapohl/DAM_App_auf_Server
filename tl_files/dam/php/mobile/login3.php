<?php

$MSV = array(
    'foo'  => 'Superaxl Anweisung A: Metasyntaktische Variable',
    'bar'  => 'Anweisung B: foo bar baz',
    'baz'  => 'Anweisung C: Antwort auf das Leben, das Universum und den ganzen Rest',
    'axl'  => 'SUBBAAXL'.$_GET['wert']
);

$data = json_encode($MSV);
echo $_GET['jsonp_callback'] . '(' . $data . ');';

/* prints:
({"foo":"Anweisung A: Metasyntaktische Variable","bar":"Anweisung B: foo bar baz","baz":"Anweisung C: Antwort auf das Leben, das Universum und den ganzen Rest"});
*/
?>