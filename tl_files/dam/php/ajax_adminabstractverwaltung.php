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
        $sql = "SELECT a.firstname, a.lastname, a.email, a.lastLogin, a.createdOn, d.* FROM tl_member a, dam_abstract d where a.id = d.uid";
        $teilnehmerErg = $db->query($sql);
        if ($teilnehmerErg->num_rows > 0) {
            echo "<table id=\"adminabstractTabelle\" class=\"autorenTabelle\">";

            echo "<tr>";
            echo "<th class=\"dummyTh\"></th>";
            echo "<th class=\"redTh\">Titel</th>";
            echo "<th class=\"redTh\">Vortragsart</th>";
            echo "<th class=\"redTh\">Sequenz</th>";
            echo "<th class=\"redTh\">Eingereicht am</th>";
            echo "<th class=\"redTh\">Eingereicht von</th>";
            echo "<th class=\"redTh\">Anhang</th>";
            echo "</tr>";

            while ($zeile = $teilnehmerErg->fetch_object()) {
                ?>
            <tr>
                <td>
                    <a href="tl_files/dam/php/pdf/ajax_pdfgen.php?mode=pdf&aid=<?php echo $zeile->abstractid;?>&uid=<?php echo $zeile->userid;?>" target="_blank"><img src="tl_files/dam/images/viewabstract.png"
                         alt="Abstract anzeigen" title="Abstract anzeigen" class="usageIcons" />
                    </a>
                </td>
                <td><?php echo $zeile->titel;?></td>
                <td>
                    <?php
                        if($zeile->vortragart == 1)
                        {
                            echo "Vortrag";
                        }
                        else if($zeile->vortragart == 2)
                        {
                            echo "Kurzvortrag";
                        }
                        else if($zeile->vortragart == 3)
                        {
                            echo "Vortrag oder Kurzvortrag";
                        }
                    ?>
                </td>
                <td><?php echo "";?></td>
                <td><?php echo date("d.m.Y H:i", $zeile->datum) . " Uhr";?></td>
                <td><?php echo $zeile->firstname. " " . $zeile->lastname . " [".$zeile->email."]"; ?></td>
                <td>
                    <?php
                        if($zeile->datei1 != "")
                        {
                            echo "Datei 1 ";
                        }
                        else if($zeile->datei2 != "")
                        {
                            echo "Datei 2 ";
                        }
                        else if($zeile->datei3 != "")
                        {
                            echo "Datei 3 ";
                        }
                    ?>
                </td>
            </tr>
            <?php
            }
            echo " </table > ";
            ?>
        </div>
        <?php
        } else {
            ?>
        <p class="error">Es sind bisher keine Abstracts eingereicht worden.</p>
        <script>hideAdminAbstractTabelle();</script>
        <?php
        }
    }
}

?>



