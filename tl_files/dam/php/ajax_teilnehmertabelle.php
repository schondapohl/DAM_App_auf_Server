<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Markus Zippelt
 * Date: 23.01.13
 * Time: 15:53
 * To change this template use File | Settings | File Templates.
 */
session_start();

function getAnmeldungsText($art)
{
    #$datum1 = " bis 30.09.13";
    #$datum2 = " ab 01.10.13";
    $datum1 = "";
    $datum2 = "";
    if ($art == 1) {
        return "Student" . $datum1;
    } elseif ($art == 2) {
        return "Student" . $datum2;
    } elseif ($art == 3) {
        return "Student Tageskarte";
    } elseif ($art == 4) {
        return "Assistenzärzte (Mitglied)" . $datum1;
    } elseif ($art == 5) {
        return "Assistenzärzte (Mitglied)" . $datum2;
    } elseif ($art == 6) {
        return "Assistenzärzte (Mitglied) Tageskarte";
    } elseif ($art == 7) {
        return "Assistenzärzte (Nicht-Mitglied)" . $datum1;
    } elseif ($art == 8) {
        return "Assistenzärzte (Nicht-Mitglied)" . $datum2;
    } elseif ($art == 9) {
        return "Assistenzärzte (Nicht-Mitglied) Tageskarte";
    } elseif ($art == 10) {
        return "OÄ, Chefärzte (Mitglied)" . $datum1;
    } else if ($art == 11) {
        return "OÄ, Chefärzte (Mitglied)" . $datum2;
    } elseif ($art == 12) {
        return "OÄ, Chefärzte (Mitglied) Tageskarte";
    } elseif ($art == 13) {
        return "OÄ, Chefärzte (Nicht-Mitglied)" . $datum1;
    } elseif ($art == 14) {
        return "OÄ, Chefärzte (Nicht-Mitglied)" . $datum2;
    } elseif ($art == 15) {
        return "OÄ, Chefärzte (Nicht-Mitglied) Tageskarte";
    }
}


if (isset($_GET["mode"])) {
    if ("refresh" == $_GET["mode"]) {
        include("damuser.php");
        include("damabstract.php");
        include("damautor.php");
        include("damadresse.php");
    }
}

include("dbconnection.php");
$damUser = unserialize($_SESSION['user']);
if ($damUser != null && $damUser->manager) {


    if (mysqli_connect_errno() == 0) {
        $sql = "SELECT a.firstname, a.lastname, a.email, a.lastLogin, a.createdOn, d.* FROM tl_member a, dam_anmeldungen d where a.id = d.uid";
        $teilnehmerErg = $db->query($sql);

        if ($teilnehmerErg->num_rows > 0) {
            echo "<table id=\"teilnehmerTabelle\" class=\"autorenTabelle\">";

            echo "<tr>";
            echo "<th class=\"dummyTh\"></th>";
            echo "<th class=\"redTh\">Vorname</th>";
            echo "<th class=\"redTh\">Nachname</th>";
            echo "<th class=\"redTh\">Email</th>";
            echo "<th class=\"redTh\">Angemeldet am</th>";
            echo "<th class=\"redTh\">Angemeldet als</th>";
            echo "<th class=\"redTh\">Bezahlt</th>";
            echo "<th class=\"redTh\">Online registriert</th>";
            echo "<th class=\"redTh\">Letzer Login</th>";
            echo "</tr>";

            while ($zeile = $teilnehmerErg->fetch_object()) {
                ?>
            <tr>
                <td>
                    <?php if ($zeile->anmeldestatus == 1) { ?>
                    <img id="mg_<?php echo $zeile->uid; ?>" src="<?php echo $picpath."regbutnotpaid.png"; ?>"
                         alt="als bezahlt markieren"
                         title="als bezahlt markieren" class="usageIcons"
                         onclick="mg(<?php echo $zeile->uid . ",2"; ?>)"/>
                    <?php } else if ($zeile->anmeldestatus == 2) { ?>
                    <img id="mg_<?php echo $zeile->uid; ?>" src="<?php echo $picpath."regandpaid.png"; ?>"
                         alt="als noch nicht bezahlt markieren"
                         title="als noch nicht bezahlt markieren" class="usageIcons"
                         onclick="mg(<?php echo $zeile->uid . ",1"; ?>)"/>
                    <?php } ?>
                </td>
                <td><?php echo $zeile->firstname; ?></td>
                <td><?php echo $zeile->lastname; ?></td>
                <td><?php echo $zeile->email; ?></td>
                <td><?php echo date("d.m.Y H:i", $zeile->anmeldedatum) . " Uhr"; ?></td>
                <td><?php echo getAnmeldungsText($zeile->anmeldetyp); ?></td>
                <td><?php if ($zeile->anmeldestatus == 1) echo "nocht nicht bezahlt"; else if ($zeile->anmeldestatus == 2) echo date("d.m.Y H:i", $zeile->bezahlt) . " Uhr"; ?></td>
                <td><?php echo date("d.m.Y H:i", $zeile->createdOn) . " Uhr"; ?></td>
                <td><?php echo date("d.m.Y H:i", $zeile->lastLogin) . " Uhr"; ?></td>
            </tr>
            <?php
            }
            echo " </table > ";
            ?>
        </div>
        <?php
        } else {
            ?>
        <p class="error">Es sind bisher keine Teilnehmer angemeldet.</p>
        <script>hideTeilNehmerTabelle();</script>
        <?php
        }
    }
}


?>



