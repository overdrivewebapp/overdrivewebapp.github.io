<?php

require "../includes/dbconnections.php";

if (!isset($_SESSION['id'])) {
    header("location: ../index.php");
}
if ($_SESSION['type'] != 2) {
    header("location: ../logout.php");
}

$ID = $_SESSION['id'];
$user_folder = $db->query("SELECT files_folder FROM accounts WHERE account_id = $ID");
$user_dir = mysqli_fetch_assoc($user_folder)['files_folder'];

if (!empty($_FILES)) {
    $file = $_FILES["fileupload"];
    // Check Available Storage Space
    $dir = scandir("../files/$user_dir");
    $total_size = 0;
    foreach ($dir as $value) {
        $total_size += filesize("../files/$user_dir/$value");
    }
    if ($total_size + $file["size"] > 100000000) {
        $_SESSION['file_upload_error'] = "Storage Space is full";
    } else {
        //Check Duplicate File and Rename
        $file_name = $file["name"];
        $i = 1;
        while (file_exists("../files/$user_dir/$file_name")) {
            $file_data = explode(".", $file_name);
            if (substr($file_data[0], -2, -1) == ("_")) {
                $file_name = substr($file_data[0], 0, -2) . "_$i." . $file_data[1];
            } else {
                $file_name = $file_data[0] . "_$i." . $file_data[1];
            }
            $i++;
        }
        //Upload The File
        if (!move_uploaded_file($file['tmp_name'], "../files/$user_dir/$file_name")) {
            $_SESSION['file_upload_error'] = "File Uploading Error";
        }
    }
    if (!isset($_SESSION['file_upload_error'])) {
        audit("Uploaded file : $file_name");
    }
    header("location: file_manager.php");
}

function download_file($file) {
    global $user_dir;
    header("Content-Disposition: attachment; filename='$file'");
    readfile("../files/$user_dir/$file");
}

function delete_file($file) {
    global $user_dir;
    unlink("../files/$user_dir/$file");
}

if (isset($_GET["dl_file"])) {
    download_file($_GET["dl_file"]);
    audit("Downloaded file : " . $_GET["dl_file"]);
}

if (isset($_GET["del_file"])) {
    delete_file($_GET["del_file"]);
    audit("Deleted file : " . $_GET["del_file"]);
    header("location: file_manager.php");
}
