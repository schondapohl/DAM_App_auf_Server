<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Markus Zippelt
 * Date: 23.01.13
 * Time: 15:53
 * To change this template use File | Settings | File Templates.
 */
session_start();
if (isset($_GET["mode"])) {
    if ("refresh" == $_GET["mode"]) {
        include("dbconnection.php");
        include("damuser.php");
        include("damabstract.php");
        include("damautor.php");
        include("damadresse.php");
    }
}
$damUser = unserialize($_SESSION['user']);

if (count($damUser->abstracts) > 0) {
    echo "<table id=\"abstractTabelle\" class=\"autorenTabelle\">";
    #if (!$abstractView) {
    echo "<tr>";
    echo "<th class=\"dummyTh\"></th>";
    echo "<th class=\"redTh\">Titel</th>";
    echo "<th class=\"redTh\">Status</th>";
    echo "<th class=\"redTh\">Datum</th>";
    echo "<th class=\"redTh\">Erster Autor</th>";
    echo "<th class=\"redTh\">weitere Autoren</th>";
    echo "<th class=\"redTh\">Dateianhang</th>";
    #echo "<th class=\"redTh\"> Fax</th>";
    echo "</tr>";

    foreach ($damUser->abstracts as $a) {

        ?>
    <tr>
        <td>
            <a href="tl_files/dam/php/pdf/ajax_pdfgen.php?mode=pdf&aid=<?php echo $a->id;?>&uid=<?php echo $damUser->userhash;?>"
               target="_blank"><img src="<?php echo $picpath."viewabstract.png"; ?>"
                                    alt="Abstract anzeigen" title="Abstract anzeigen" class="usageIcons"/>
            </a></td>
        <td><?php echo $a->name; ?></td>
        <td><?php if ($a->status == 1) echo "eingereicht"; elseif ($a->status == 2) echo "angenommen"; elseif ($a->status == 3) echo "abgelehnt";  ?></td>
        <td><?php echo date("d.m.Y H:i", $a->datum) . " Uhr"; ?></td>
        <td>
            <?php
            #echo "<span class=\"firstAutorData\">" . $a->firstAutor->vorname . " " . $a->firstAutor->nachname . "</span>";
            echo $a->firstAutor->vorname . " " . $a->firstAutor->nachname;
            ?>
        </td>
        <td>
            <?php
            foreach ($a->otherAutors as $autor) {
                echo $autor->vorname . " " . $autor->nachname;
                echo ", ";
            }
            ?>
        </td>
        <td>
            <?php  foreach ($a->dateinamen as $datei) {
            if ($datei != "") {
                ?>
                <a target="_blank" href="<?php echo $filepath.$damUser->username; ?>/<?php echo html_entity_decode($datei); ?>" alt="Anhang" title="Anhang">Datei</a>
                <?php
            }
        }?>
        </td>
    </tr>
    <?php
    }
    echo " </table > ";
    if ($damUser->kongressstatus == 0) {
        ?>
    <p class="FreieForm">Sie sind noch nicht zum Kongress angemeldet.<br/> Klicken Sie <a
            style="color:black; text-decoration: none; font-weight: bold;" id="anmeldelink" href="">HIER</a> um zur
        Kongressanmeldung zu navigieren.</p>
    <?php
    }
} else {
    ?>
<p class="error">Aktuell sind noch keine Abstracts angelegt.</p>
<script>hideAbstractUebersicht();</script>
<?php
} ?>

