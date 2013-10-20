<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Markus Zippelt
 * Date: 23.01.13
 * Time: 15:56
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
?>
<div class="tableWrapper">


</div>
<div id="part1" class="floatingDiv">
    <table class="anlageTabelle" cellspacing="10">
        <tr>
            <td>Titel</td>
            <td><input type="text" id="autorTitel" tabindex="1"/>
        </tr>
        <tr>
            <td>Vorname</td>
            <td>
                <input type="text" class="newinput" class="newinput" id="autorVorname" tabindex="2"/>
            </td>
        </tr>
        <tr>
            <td>Nachname</td>
            <td>
                <input type="text" class="newinput" id="autorNachname" tabindex="3"/>
            </td>
        </tr>
        <tr>
            <td>Email</td>
            <td>
                <input type="text" class="newinput" id="autorEmail" tabindex="4"/>
            </td>
        </tr>
    </table>
</div>
<div id="part2" class="floatingDiv">
    <table class="anlageTabelle" cellspacing="10">
        <tr>
            <td style="width:100%;text-align: center;">W&auml;hlen Sie aus</td>
        </tr>
        <tr>
            <td style="text-align: center">bereits angelegten Adressen</td>
        </tr>
        <tr>
            <td style="width: 100%;text-align: center;"><select tabindex="7" onChange="fillAdressTabelle(this.value);"
                                                                name="adressVorlagen" id="adressVorlagen" size="1"
                <?php if (count($damUser->adressen) == 0) {
                echo "disabled";
            } ?>
                    >
                <option value="-1" selected></option>
                <?php
                $adressListe = array();
                foreach ($damUser->adressen as $selectAdress) {
                    if (!in_array($selectAdress->id, $adressListe)) {
                        echo "<option id =\"vorlage_" . $selectAdress->id . "\" value=\"" . $selectAdress->id . "\">" . $selectAdress->toString() . "</option>";
                        $adressListe[] = $selectAdress->id;
                    }
                }
                ?>
            </select></td>
        </tr>
    </table>
</div>
<div id="part3" class="floatingDiv">
    <table class="anlageTabelle" cellspacing="10">
        <tr>
            <td class="secondTD">Institution</td>
            <td class="">
                <input tabindex="8" type="text" class="newinput" id="adresseInstitution"/>
            </td>
        </tr>
        <tr>
            <td class="secondTD">Stra&szlig;e</td>
            <td class="">
                <input tabindex="9" type="text" class="newinput" id="adresseStrasse"/>
            </td>

        </tr>
        <tr>
            <td class="secondTD">PLZ</td>
            <td class="">
                <input tabindex="10" type="text" class="newinput" id="adressePLZ"/>
            </td>
        </tr>
        <tr>
            <td class="secondTD">Ort</td>
            <td class="">
                <input tabindex="11" type="text" class="newinput" id="adresseOrt"/>
            </td>
        </tr>
        <tr>
            <td class="secondTD">Land</td>
            <td class="">
                <input tabindex="12" type="text" class="newinput" id="adresseLand"/>
            </td>
        </tr>
    </table>
</div>
<table>
    <tr>
        <td>
            <div id="addAuthorButtonWrapper">
                <img src="<?php echo $picpath."addauthor.png"; ?>" id="addAuthorButton" alt="Autor erstellen" title="Autor erstellen"/>
                <h2>Autor anlegen</h2>
            </div></td>
        <td><span id="infoTextAutor" class="loading">Bitte warten: Autor wird angelegt</span><div id="addAuthorButtonWrapper2" onclick="autorenAnzeigeAktualisieren()">
            <img src="<?php echo $picpath."refresh2.png"; ?>" id="addAuthorButton2"  alt="aktualisieren" title="aktualisieren"/>
            <h2>Anzeige aktualisieren</h2>
        </div></td>
    </tr>
</table>

<div id="autorenHinweis" style="display:none;font-weight: bold;clear:both;text-align:center;">Bitte alle (rot
    markierten) Felder ausfüllen.
</div>
<?php
if (isset($_GET["mode"])) {
    if ("refresh" == $_GET["mode"]) {
        ?>
    <script>domHandler();</script>
    <?php
    }
}
?>