<?php
session_start();
$files = array();

if (isset($_POST['mode']) && $_POST['mode'] == "add") {
    if (isset($_POST['file'])) {
        if (isset($_SESSION['dam_uploadedfiles']) && $_SESSION['dam_uploadedfiles'] != "") {
            $files = $_SESSION['dam_uploadedfiles'];
            if (count($files) < 3) {
                $files[] = $_POST['file'];
                unset($_SESSION['dam_uploadedfiles']);
                $_SESSION['dam_uploadedfiles'] = $files;
            } else {
                unset($files);
                unset($_SESSION['dam_uploadedfiles']);
            }
        } else {
            $files[] = $_POST['file'];
            $_SESSION['dam_uploadedfiles'] = $files;
        }
        print_r($_SESSION['dam_uploadedfiles']);
    }
} elseif (isset($_POST['mode']) && $_POST['mode'] == "del") {

    $dateiname = $_POST['id'];
    unlink("../../../system/tmp/" . $dateiname);

    $ref = false;

    if (isset($_SESSION['AJAX-FFL']['3']) && isset($_SESSION['AJAX-FFL']['3']['arrSessionFiles'])) {
        foreach ($_SESSION['AJAX-FFL']['3']['arrSessionFiles'] as $key => $arrFile) {
            if (!is_file("../../../system/tmp/" . $arrFile['name'])) {
                unset($_SESSION['AJAX-FFL']['3']['arrSessionFiles'][$key]);
                $ref = true;
            }
        }
    }

    if (isset($_SESSION['VALUM_FILES'])) {
        foreach ($_SESSION['VALUM_FILES'] as $key => $arrFile) {
            if (!is_file("../../../system/tmp/" . $arrFile['name'])) {
                unset($_SESSION['VALUM_FILES'][$key]);
                $ref = true;
            }
        }
    }

    if ($ref) {
        echo "refresh";
    }
    else {
        echo "norefresh";
    }
    /*}*/
} elseif (isset($_POST['mode']) && $_POST['mode'] == "tab") {
    ?>
<tr>
    <td class="">
        <img class="usageIcons" src="tl_files/dam/images/delete.png" alt="löschen" title="löschen"
             onclick="removeUploadFile('<?php echo $_POST['file']; ?>')"/>
    </td>
    <td class="tdfilename"><?php echo $_POST['file']; ?></td>
    <td class="tdfilesize"><?php echo $_POST['size']; ?></td>
</tr>
<?php
} elseif (isset($_POST['mode']) && $_POST['mode'] == "store") {
    if (isset($_POST['field'])) {
        if (!isset($_SESSION['inputs'])) {
            $_SESSION['inputs'] = array();
        }
        $_SESSION['inputs'][$_POST['field']] = $_POST['value'];
    }
} elseif (isset($_POST['mode']) && $_POST['mode'] == "clear") {
    unset($_SESSION['inputs']);
} elseif (isset($_POST['mode']) && $_POST['mode'] == "clearUploads") {
    unset($_SESSION['AJAX-FFL']['3']['arrSessionFiles']);
    unset($_SESSION['VALUM_FILES']);

}
?>
