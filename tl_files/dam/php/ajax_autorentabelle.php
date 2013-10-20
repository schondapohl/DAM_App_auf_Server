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
if (isset($_GET["mode"])) {
    if ("refresh2" == $_GET["mode"]) {
        include("dbconnection.php");
        include("damuser.php");
        include("damabstract.php");
        include("damautor.php");
        include("damadresse.php");
    }
}

$damUser = unserialize($_SESSION['user']);
?>

<div style="text-align:left;padding: 0;margin: 0;" xmlns="http://www.w3.org/1999/html">
    <img id="datenrefresh" style="cursor: pointer;display:none;" onclick="refreshData()" src="<?php echo $picpath . "refresh.png";?>"
         alt="Anzeige aktualisieren" title="Anzeige aktualisieren"/>
    <?php
    if (isset($_GET["mode"])) {
        if ("refresh" == $_GET["mode"]) { ?>
    <img id="aloader" src="<?php echo $picpath . "aloader.gif";?>" /> <span id="datenrefresh2" class="round refreshbuttontext" onclick="refreshData()">Anzeige aktualisieren</span>
            <?php } }?>
</div>

<?php
if (count($damUser->autoren) > 0) {

    echo "<table id=\"autorenTabelle\" class=\"autorenTabelle\">";
    #if (!$abstractView) {
    echo "<tr>";
    echo "<th class=\"dummyTh\"></th>";
    echo "<th class=\"redTh\"> Titel</th>";
    echo "<th class=\"redTh\"> Vorname</th>";
    echo "<th class=\"redTh\"> Nachname</th>";
    echo "<th class=\"redTh\"> Email</th>";
    #echo "<th class=\"redTh\"> Telefon</th>";
    #echo "<th class=\"redTh\"> Fax</th>";
    echo "<th class=\"redTh\"> Adresse</th>";
    echo "</tr>";

    foreach ($damUser->autoren as $autorenrow) {
        #echo "autorenrow ".$autorenrow->adressid;
        $theAdress = $damUser->findAdresse($autorenrow->adressid);
        ?>
    <tr id="autorenrow_<?php echo $autorenrow->id; ?>" class="autorenzeile">
        <td style="text-align: center;width: 60px;">
            <img id="editIcon_<?php echo $autorenrow->id; ?>"
                 src="<?php echo $picpath ."edit.png";?>"
                 alt="Autor editieren"
                 title="Autor editieren" class="usageIcons"
                 onclick="editAutor(<?php echo $autorenrow->id . "," . $autorenrow->adressid ?>)"/>
            <img id="deleteIcon_<?php echo $autorenrow->id; ?>"
                 src="<?php echo $picpath ."delete.png"; ?>"
                 alt="Autor loeschen" title="Autor loeschen" class="usageIcons"
                 onclick="delAutor(<?php echo $autorenrow->id . "," . $autorenrow->adressid ?>)"/>
            <img class="iconHidden" id="saveIcon_<?php echo $autorenrow->id; ?>"
                 src="<?php echo $picpath ."save.png"; ?>"
                 alt="Aenderung speichern" id="saveChanges" title="Aenderung speichern" class="usageIcons"
                 onclick="saveChanges(<?php echo $autorenrow->id . "," . $autorenrow->adressid ?>)"/>
            <img class="iconHidden" id="abortIcon_<?php echo $autorenrow->id; ?>"
                 src="<?php echo $picpath ."abort.png"?>"
                 alt="Aenderung abbrechen" id="abortChanges" title="Aenderung abbrechen" class="usageIcons"
                 onclick="abortAutor(<?php echo $autorenrow->id . "," . $autorenrow->adressid; ?>)"/>
        </td>
        <td><span id="tdtitel_<?php echo $autorenrow->id; ?>"
                  class="visibleCellValue"><?php echo $autorenrow->titel; ?></span>
            <input type="text" id="titel_<?php echo $autorenrow->id; ?>"
                   class="hiddenInput" value="<?php echo $autorenrow->titel; ?>">
        </td>
        <td><span id="tdvorname_<?php echo $autorenrow->id; ?>"
                  class="visibleCellValue"><?php echo $autorenrow->vorname; ?></span>
            <input type="text" id="vorname_<?php echo $autorenrow->id; ?>"
                   class="hiddenInput" value="<?php echo $autorenrow->vorname; ?>">
        </td>
        <td><span id="tdnachname_<?php echo $autorenrow->id; ?>"
                  class="visibleCellValue"><?php echo $autorenrow->nachname; ?></span><input
                type="text"
                id="nachname_<?php echo $autorenrow->id; ?>"
                class="hiddenInput"
                value="<?php echo $autorenrow->nachname; ?>">
        </td>
        <td><span id="tdemail_<?php echo $autorenrow->id; ?>"
                  class="visibleCellValue"><?php echo $autorenrow->email; ?></span><input
                type="text"
                id="email_<?php echo $autorenrow->id; ?>"
                class="hiddenInput"
                value="<?php echo $autorenrow->email; ?>">
        </td>
        <td><span id="tdAdresseKombi_<?php echo $autorenrow->adressid . "_" . $autorenrow->id; ?>"
                  class="visibleCellValue"><?php echo $theAdress->toString(); ?></span><input
                type="text" id="AdresseKombi_<?php echo $autorenrow->adressid . "_" . $autorenrow->id; ?>"
                class="hiddenInput"
                value="<?php echo $theAdress->toString(); ?>">
        </td>
    </tr>
    <?php
    }
    echo "</table>";
    ?>
<div class="moresteps">Sie haben bereits Autoren angelegt. Die Reihenfolge der Autoren des Abstracts geben Sie unter <b>Abstract einreichen</b> an. Folgende weitere Schritte sind möglich
    <div>
        [<span class="textlink" onclick="$('#tabErstellen').click();">weitere Autoren anlegen</span>]
        oder
        [<span class="textlink" onclick="$.scrollTo('#abstractverwaltung', 1500, {easing:'swing'});">ein Abstract einreichen</span>]
    </div>
</div>
<?php
} else {
    ?>
<p class="error">Aktuell sind noch keine Autoren angelegt. Klicken Sie <span
        style="cursor:pointer; text-decoration: underline;" onclick="$('#tabErstellen').click();">HIER</span> um einen
    neuen Autor anzulegen. </p>
<script>hideAutorenTable();</script>
<?php
} ?>



