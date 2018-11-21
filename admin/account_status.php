<?php
require "../includes/dbconnections.php";
if (!isset($_SESSION['id'])) {
    header("location: ../index.php");
}
if ($_SESSION['type'] != 1) {
    header("location: ../logout.php");
}

if (empty($_GET['id'])) {
    header("location: user_management.php");
}
$ID = $_GET['id'];
$stat = $_GET['s'];

$db->query("UPDATE accounts SET acc_status = $stat  WHERE account_id = $ID") OR die($db->error);

$stat_text = ($stat == 0) ? "Deactivated" : "Activated";
audit("$stat_text Account with ID of $ID");

header("location: user_management.php");
?>



