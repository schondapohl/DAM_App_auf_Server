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
?>
<h1>Kongressanmeldung</h1>
<div class="step">
    <h2>1. Schritt</h2>

    <p class="FreieForm">Ihre Anmeldung ist rechtsverbindlich. Bei Verhinderung bitten wir um schriftliche Absage. Bei
        Rücktritt bis inklusive 28. Oktober 2013 werden 50% der Teilnahmegebühr zurückerstattet. Bei Rücktritt nach dem
        28. Oktober 2013 kann keine Rückerstattung mehr vorgenommen werden.</p>
    <?php if ($damUser->kongressstatus == 0) { ?>
    <input type="checkbox" value="None" id="akzept_1" name="check"/>
    <span class="checkboxTitel">Hiermit akzeptiere ich die Stornierungsbedinungen.</span>
    <span class="checkboxTitel2">Die Kongressanmeldung ist noch nicht möglich. Sobald die Anmeldung möglich ist, werden sie von uns benachrichtigt.</span>
    <?php } else if ($damUser->kongressstatus == 1) { ?>
    <table>
        <tr>
            <td rowspan=2><img src="<?php echo $picpath . "autorenchecked.png"; ?>" title="Autoren angelegt"
                               alt="Autoren angelegt"
                               style="vertical-align:middle;"/></td>
            <td><span
                    class="checkboxTitelSuccess">Sie sind für den Kongress bereits angemeldet. Gebucht wurde: <?php echo $damUser->getAnmeldungsText();?></span>
            </td>
        </tr>
        <tr>
            <td><span
                    class="expl">Bitte überweisen Sie den Betrag <b><?php echo $damUser->grundbetrag + $damUser->zusatzbetrag; ?>
                Euro</b>
                auf das angegebene Konto (<span class="textlink"
                                                onclick="$.scrollTo('#preise', 1500, {easing:'swing'});">Kontodetails</span>).</span>
            </td>
        </tr>
    </table>
    <?php } else if ($damUser->kongressstatus == 2) { ?>
    <table>
        <tr>
            <td rowspan=2><img src="<?php echo $picpath . "autorenchecked.png"; ?>" title="Autoren angelegt"
                               alt="Autoren angelegt"
                               style="vertical-align:middle;"/></td>
            <td><span
                    class="checkboxTitelSuccess">Sie sind für den Kongress bereits angemeldet. Gebucht wurde: <?php echo $damUser->getAnmeldungsText();?></span>
            </td>
        </tr>
        <tr>
            <td><span class="expl">Wir haben Ihre Bezahlung erhalten.</span>
            </td>
        </tr>
    </table>
    <?php } ?>
</div>
<div class="step" id="anmeldetabellewrapper">
    <h2>2. Schritt</h2>

    <p class="FreieForm">Nach Anmeldung und Bezahlung der Kongressgebühr erhalten Sie eine schriftliche
        Bestätigung.<br/>Bitte wählen Sie Ihr Ticket für den Kongress aus der Tabelle.</p>
    <table class="preisetabelle2">
        <thead>
        <tr>
            <th class="dummyTh">
                <p align="center" class="berschrift21">&nbsp;</p>
            </th>
            <th class="redTh">
                <p align="center" class="berschrift21">Gesamter Kongress</p>

                <p align="center" class="berschrift21">bis 30.09.13</p>
            </th>
            <th class="redTh">
                <p align="center" class="berschrift21">Gesamter Kongress</p>

                <p align="center" class="berschrift21">ab 01.10.13</p>
            </th>
            <th class="redTh">
                <p align="center" class="berschrift21">Tageskarte Donnerstag/Freitag/Samstag</p>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td valign="top" class="firstCol">
                <p class="Text">Studenten</p>
            </td>
            <td align="center">
                <p class="Text">50,-€</p>
                <span class="anmeldespanstudent" id="a_1">Jetzt anmelden</span>
            </td>
            <td align="center">
                <p class="Text">50,-€</p>
                <span class="hiddenElement anmeldespanstudent" id="a_2">Jetzt anmelden</span>
            </td>
            <td align="center">
                <p class="Text">50,-€</p>
                <span class="anmeldespanstudent" id="a_3">Jetzt anmelden</span>
            </td>
        </tr>
        <tr>
            <td valign="top" class="firstCol">
                <p class="Text">Assistenzärzte (Mitglied)</p>
            </td>
            <td align="center">
                <p class="Text">180,-€</p>
                <span class="anmeldespanNormal" id="a_4">Jetzt anmelden</span>
            </td>
            <td align="center">
                <p class="Text">210,-€</p>
                <span class="hiddenElement anmeldespanNormal" id="a_5">Jetzt anmelden</span>
            </td>
            <td align="center">
                <p class="Text">100,-€</p>
                <span class="anmeldespanTag" id="a_6">Jetzt anmelden</span>
            </td>
        </tr>
        <tr>
            <td valign="top" class="firstCol">
                <p class="Text">Assistenzärzte (Nicht-Mitglied)</p>
            </td>
            <td align="center">
                <p class="Text">200,-€</p>
                <span class="anmeldespanNormal" id="a_7">Jetzt anmelden</span>
            </td>
            <td align="center">
                <p class="Text">230,-€</p>
                <span class="hiddenElement anmeldespanNormal" id="a_8">Jetzt anmelden</span>
            </td>
            <td align="center">
                <p class="Text">120,-€</p>
                <span class="anmeldespanTag" id="a_9">Jetzt anmelden</span>
            </td>
        </tr>
        <tr>
            <td valign="top" class="firstCol">
                <p class="Text">OÄ, Chefärzte (Mitglied)</p>
            </td>
            <td align="center">
                <p class="Text">280,-€</p>
                <span class="anmeldespanNormal" id="a_10">Jetzt anmelden</span>
            </td>
            <td align="center">
                <p class="Text">310,-€</p>
                <span class="hiddenElement anmeldespanNormal" id="a_11">Jetzt anmelden</span>
            </td>
            <td align="center">
                <p class="Text">140,-€</p>
                <span class="anmeldespanTag" id="a_12">Jetzt anmelden</span>
            </td>
        </tr>
        <tr>
            <td valign="top" class="firstCol">
                <p class="Text">OÄ, Chefärzte (Nicht-Mitglied)</p>
            </td>
            <td align="center">
                <p class="Text">300,-€</p>
                <span class="anmeldespanNormal" id="a_13">Jetzt anmelden</span>
            </td>
            <td align="center">
                <p class="Text">330,-€</p>
                <span class="hiddenElement anmeldespanNormal" id="a_14">Jetzt anmelden</span>
            </td>
            <td align="center">
                <p class="Text">160,-€</p>
                <span class="anmeldespanTag" id="a_15">Jetzt anmelden</span>
            </td>
        </tr>
        <!--
        <tr>
            <td valign="top" class="firstCol">
                <p class="Text">Vortragende</p>
            </td>
            <td align="center">
                <p class="Text">40,-€ Rabatt</p>
                <input type="checkbox" id="rabatt_1"/>
            </td>
            <td align="center">
                <p class="Text">40,-€ Rabatt</p>
                <input type="checkbox" id="rabatt_2"/>
            </td>
            <td align="center">
                <p class="Text">20,-€ Rabatt</p>
                <input type="checkbox" id="rabatt_3"/>
            </td>
        </tr>-->
        <tr>
            <td valign="top" class="firstCol">
                <p class="Text">Get together / Weinkeller (Donnerstag)</p>
            </td>
            <td align="center">
                <p class="Text">inklusive</p>
            </td>
            <td align="center">
                <p class="Text">inklusive</p>
            </td>
            <td align="center">
                <p class="Text">inklusive</p>
            </td>
        </tr>
        <tr>
            <td valign="top" class="firstCol">
                <p class="Text">Festabend (Freitag)</p>
            </td>
            <td align="center">
                <p class="Text">inklusive</p>
            </td>
            <td align="center">
                <p class="Text">inklusive</p>
            </td>
            <td align="center">
                <p class="Text">80,-€</p>
                <!--<input type="checkbox" id="festabend"/>-->
            </td>
        </tr>
        <tr>
            <td valign="top" class="firstCol">
                <p class="Text">Festabend Begleitperson</p>
            </td>
            <td align="center">
                <p class="Text">80,-€</p>
                <!--<input type="checkbox" id="begl_1"/>-->
            </td>
            <td align="center">
                <p class="Text">80,-€</p>
                <!--<input type="checkbox" id="begl_2"/>-->
            </td>
            <td align="center">
                <p class="Text">80,-€</p>
                <!--<input type="checkbox" id="begl_3"/>-->
            </td>
        </tr>
        </tbody>
    </table>
</div>

<div id='confirm'>
    <div class='header'><span>Kongressanmeldung</span></div>
    <div class='message'>Möchten Sie am Festabend (Freitag) teilnehmen? </div>
    <div class='buttons'>
        <div class='no simplemodal-close'>Nein</div><div class='yes'>Ja</div>
    </div>
</div>

<div id='confirm2'>
    <div class='header'><span>Abstract einreichen</span></div>
    <div class='message'> Bringen Sie eine Begleitperson zum Festabend mit? </div>
    <div class='buttons'>
        <div class='no simplemodal-close'>Nein</div><div class='yes2'>Ja</div>
    </div>
</div>

<div id='lastcheck'>
    <div class='header'><span>Kongressanmeldung</span></div>
    <div class='message'>Hiermit melde ich mich verbindlich zum Kongress an.</div>
    <div class='buttons'>
        <div class='no simplemodal-close'>Nein</div><div class='yes'>Ja</div>
    </div>
</div>

<script type='text/javascript' src='tl_files/dam/js/jquery.simplemodal.js'></script>
<script type='text/javascript' src='tl_files/dam/js/confirm.js'></script>