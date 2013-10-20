<?php
// Query vorbereiten und an die DB schicken
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

function printTextAreaInput($theTextAreaId)
{
    if (isset($_SESSION['inputs']) && isset($_SESSION['inputs'][$theTextAreaId]) && $_SESSION['inputs'][$theTextAreaId] != "") {
        echo $_SESSION['inputs'][$theTextAreaId];
    }
}

$damUser = unserialize($_SESSION['user']);
if (isset($damUser)) {
    ?>
<div id="tabAbstractTitel" class="activeTab" xmlns="http://www.w3.org/1999/html">Abstract</div>
<!-- TabEnd -->
<div class="abstractDetails">
    <div class="abstractImageWrapper">
        <img src="<?php echo $picpath."abstract.png"?>" alt="abstract" title="abstract"/>
    </div>
    <div class="abstractInfoWrapper">
        <div class="abstractLine">
            <table class="abstractInfoTable">
                <tr>
                    <td>Titel:</td>
                    <td>
                        <input type="text" name="abstractName" value="" id="abstractName">
                        <span id="ex_abstractName" style="padding-left:5px;color:#708090;font-size: 12px;"></span>
                        <img class="clearImage" src="<?php echo $picpath."clear.png"?>" alt="Felder zurücksetzen"
                             title="Felder zurücksetzen" onclick="clearFields();"/>
                    </td>
                    <td><span id="titelError" class="errorTitelHidden hiddenElement">Bitte Titel eingeben.</span></td>
                </tr>
                <tr>
                    <!-- maxlength="150" -->
                    <td>Hintergrund:<span id="ex_hintergrundid" class="areaexpl">150 Zeichen</span></td>
                    <td><textarea onblur="storeValues('hintergrundid');" class="inputarea"
                                  id="hintergrundid"><?php printTextAreaInput('hintergrundid'); ?></textarea></td>
                    <td><span id="hintergrundError"
                              class="errorTitelHidden hiddenElement">Bitte Hintergrund angeben.</span></td>
                </tr>
                <tr>
                    <td>Methoden:<span id="ex_methodenid" class="areaexpl">200 Zeichen</span></td>
                    <td><textarea onblur="storeValues('methodenid');" class="inputarea"
                                  id="methodenid"><?php printTextAreaInput('methodenid'); ?></textarea></td>
                    <td><span id="methodenError" class="errorTitelHidden hiddenElement">Bitte Methoden angeben.</span>
                    </td>
                </tr>
                <tr>
                    <td>Ergebnisse:<span id="ex_ergenisseid" class="areaexpl">200 Zeichen</span></td>
                    <td><textarea onblur="storeValues('ergenisseid');" class="inputarea"
                                  id="ergenisseid"><?php printTextAreaInput('ergenisseid'); ?></textarea></td>
                    <td><span id="ergebnisseError"
                              class="errorTitelHidden hiddenElement">Bitte Ergebnisse angeben.</span></td>
                </tr>
                <tr>
                    <td>Schlussfolgerung:<span id="ex_schlussid" class="areaexpl">150 Zeichen</span></td>
                    <td><textarea onblur="storeValues('schlussid');" class="inputarea"
                                  id="schlussid"><?php printTextAreaInput('schlussid'); ?></textarea></td>
                    <td><span id="schlussfolgerungError" class="errorTitelHidden hiddenElement">Bitte Schlussfolgerung angeben.</span>
                    </td>
                </tr>
                <tr>
                    <td>Vortragsart:</td>
                    <td>
                        <input type="checkbox" id="vortrag"/><span>Vortrag</span>
                        <input type="checkbox" id="kurzvortrag"/><span>Kurzvortrag</span>
                        <input type="checkbox" id="pv"/><span>Vortrag oder Kurzvortrag</span></td>
                    <td><span id="artError" class="errorTitelHidden hiddenElement">Bitte wählen sie eine der Vortragsarten.</span>
                    </td>
                </tr>
                <tr>
                    <td>Sitzungsthema</td>
                    <td><select id="themaselect" class="thefirstautor">
                        <option value="" selected=""></option>
                        <option value="Funktionelle Rekonstruktion">Funktionelle Rekonstruktion</option>
                        <option value="Technische Innovationen">Technische Innovationen</option>
                        <option value="Interdisziplinäre Behandlungsstrategien">Interdisziplinäre Behandlungsstrategien</option>
                        <option value="Personalisierte Medizin">Personalisierte Medizin</option>
                        <option value="Sozioökonomische Aspekte">Sozioökonomische Aspekte</option>
                        <option value="Evidenzbasierte Medizin">Evidenzbasierte Medizin</option>
                        <option value="Mikrochirurgische Aus- und Weiterbildung">Mikrochirurgische Aus- und Weiterbildung</option>
                        <option value="Experimentelle und translationale Forschungsansätze">Experimentelle und translationale Forschungsansätze</option>
                        <option value="Fallpräsentationen">Fallpräsentationen</option>
                        <option value="Freies Thema">Freies Thema</option>
                    </select>
                    </td>
                    <td><span id="errorThema" class="errorTitelHidden hiddenElement">Bitte Thema angebeben.</span>
                    </td>
                </tr>
                <tr id="refreshPart_<?php echo $abstract->id; ?>">
                    <td>1. Autor</td>
                    <td><select id="firstautorselect" class="thefirstautor">
                        <?php
                        echo "<option value=\"\" selected=\"selected\"></option>";
                        foreach ($damUser->autoren as $theautor) {
                            echo "<option value=\"" . $theautor->id . "\">";
                            echo $theautor->toString();
                            echo "</option>";
                        }
                        ?>
                    </select>
                    </td>
                    <td><span id="errorAutor" class="errorTitelHidden hiddenElement">Bitte 1. Autor angebeben.</span>
                    </td>
                </tr>
                <tr id="refreshPart2_<?php echo $abstract->id; ?>">
                    <td>2. Autor</td>
                    <td><select id="autor_2" class="autorselection">
                        <option value="" selected="selected"></option>
                        <?php
                        foreach ($damUser->autoren as $theautor) {
                            echo "<option value=\"" . $theautor->id . "\">";
                            echo $theautor->toString();
                            echo "</option>";
                        }
                        ?>
                    </select></td>
                </tr>
                <tr id="trautor_3">
                    <td style="text-align:right"><span onclick="addAutor(3)" class="usageIcons" id="addImageSpan_3">weiteren Autor zum Abstract hinzufügen
                        <img src="<?php echo $picpath."add.png"?>" id="addImage_3"
                             class="usageIcons"
                             title="Autor hinzufügen" alt="Autor hinzufügen"/></span>
                    </td>
                    <td><select id="autor_3" class="hiddenInput autorselection">
                        <option value="" selected="selected"></option>
                        <?php
                        foreach ($damUser->autoren as $theautor) {
                            echo "<option value=\"" . $theautor->id . "\">";
                            echo $theautor->toString();
                            echo "</option>";
                        }
                        ?>
                    </select></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div id='confirmAbstract'>
    <div class='header'><span>Abstract einreichen</span></div>
    <div class='message'>Möchten Sie Ihr Abstract abschicken? <br/>Das Abstract kann danach <b>NICHT</b> mehr geändert werden<br/>Sie erhalten eine Mail als Bestätigung. Bitte prüfen Sie auch Ihren SPAM-Ordner. Für den Fall, dass Sie keine Bestätigung erhalten, versuchen Sie es bitte erneut oder wenden Sie sich an info@dam2013.org! </div>
    <div class='buttons'>
        <div class='no simplemodal-close'>Nein</div><div class='yes'>Ja</div>
    </div>
</div>

<div id="showFileUploader" onclick="showUploadModul()">Wenn Sie Dateien als Anhang einreichen möchten klicken Sie hier.<br/> Sie können maximal 3 Dateien je 10 MB einreichen.
</div>
<?php
} else {
    echo "p class=\"error\">Aktuell sind noch keine Autoren angelegt.</p>";
}
?>
