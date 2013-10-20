<?php
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

<ul id="internList">
    <?php if (isset($backendUserLoggedIn) && $backendUserLoggedIn ) { ?>
    <li><a id="ideingreichteabstracts" href="">&Uuml;bersicht eingereichter Abstracts</a><span class="erklaerung">Bearbeiten Sie hier Ihre Benutzer-Daten</span>
    </li>
    <?php } else { ?>
    <!--<li><a href="#meinedaten">Meine Daten</a><span class="erklaerung">Bearbeiten Sie hier Ihre Benutzer-Daten</span></li>-->
    <li>
    <?php
    $autorenText = "Sie haben noch keine Autoren angelegt. Bevor Sie Ihr Abstract absenden m&uuml;ssen Sie hier ihre Autoren hinterlegen.";
    if (isset($damUser)) {
        if (count($damUser->autoren) > 0) {
            $autorenText = "Sie haben bereits Autoren angelegt. Hier k&ouml;nnen Sie diese bearbeiten oder l&ouml;schen. Die Reihenfolge der Autoren des Abstracts geben Sie unter <b>Abstract einreichen</b> an."
            ?><img src="<?php echo $picpath."autorenchecked.png"; ?>" title="Autoren angelegt" alt="Autoren angelegt"
                   style="float:left;margin-right: 15px;"/><?php
        } else {
            ?><img src="<?php echo $picpath."autorenunchecked.png";?>" title="Autoren noch nicht angelegt"
                   alt="Autoren noch nicht angelegt" style="float:left;margin-right: 15px;"/><?php
        }
    }
}
    ?>
    <a id="idautorenverwaltung" href="">Autorenverwaltung</a><span class="erklaerung"><?php echo $autorenText; ?></span>
</li>
    <!--<li><?php
    $uploadedText = "Sie haben noch keine Abstracts auf den Server geladen. Bitte klicken Sie auf 'Abstract Upload' um Ihre Abstracts auf den Server zu laden.";
    if (isset($damUser)) {
        if ($damUser->uploadedFileCount > 0) {
            $uploadedText = "Sie haben bereits Abstracts auf den Server geladen. Bitte klicken Sie auf 'Abstract einreichen', um Ihre Abstracts einzureichen. Oder laden Sie hier weitere Dateien auf den Server."
            ?><img src="tl_files/dam/images/autorenchecked.png" title="Abstracts hochgeladen"
                       alt="Abstracts hochgeladen" style="float:left;margin-right: 15px;"/><?php
        } else {
            ?><img src="tl_files/dam/images/autorenunchecked.png" title="Abstracts noch nicht hochgeladen"
                       alt="Abstracts noch nicht hochgeladen" style="float:left;margin-right: 15px;"/><?php
        }
    }
    ?>
        <a href="#abstractuploader">Abstract Upload</a><span class="erklaerung"><?php echo $uploadedText; ?></span>
    </li>-->
    <li><?php
        $uploadedText = "Sie haben noch keine Abstracts eingereicht. Hier k&ouml;nnen Sie Ihre Abstracts einreichen und sehen den aktuellen Status.";
        if (isset($damUser)) {
            if (count($damUser->abstracts) > 0) {
                $uploadedText = "Sie haben bereits Abstracts auf den Server geladen. Bitte klicken Sie auf 'Abstract einreichen', um Ihre Abstracts einzureichen. Oder laden Sie hier weitere Dateien auf den Server."
                ?><img src="<?php echo $picpath."autorenchecked.png";?>" title="Abstracts eingereicht"
                       alt="Abstracts eingereicht" style="float:left;margin-right: 15px;"/><?php
            } else {
                ?><img src="<?php echo $picpath."autorenunchecked.png";?>" title="Abstracts noch nicht eingereicht"
                       alt="Abstracts noch nicht eingereicht" style="float:left;margin-right: 15px;"/><?php
            }
        }
        ?>
        <a id="idabstractverwaltung" href="">Abstract einreichen</a><span class="erklaerung"><?php echo $uploadedText; ?></span>
    </li>
    <li><?php
        $uploadedText = "Sie haben noch keine Abstracts eingereicht. Hier k&ouml;nnen Sie Ihre Abstracts einreichen und sehen den aktuellen Status.";
        if (isset($damUser)) {
            if (count($damUser->abstracts) > 0) {
                $uploadedText = "Sie haben bereits Abstracts auf den Server geladen. Bitte klicken Sie auf 'Abstract einreichen', um Ihre Abstracts einzureichen. Oder laden Sie hier weitere Dateien auf den Server."
                ?><img src="<?php echo $picpath."autorenchecked.png";?>" title="Abstracts eingereicht"
                       alt="Abstracts eingereicht" style="float:left;margin-right: 15px;"/><?php
            } else {
                ?><img src="<?php echo $picpath."autorenunchecked.png";?>" title="Abstracts noch nicht eingereicht"
                       alt="Abstracts noch nicht eingereicht" style="float:left;margin-right: 15px;"/><?php
            }
        }
        ?>
        <a id="idabstractuebersicht" href="">Abstract Übersicht</a><span class="erklaerung"><?php echo $uploadedText; ?></span>
    </li>
    <!--<li><a href="#">Mein Zeitplan</a><span class="erklaerung">Hier sehen Sie Ihren Zeitplan f&uuml;r den Kongress</span>-->
    </li>
</ul>