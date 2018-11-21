<?php
error_reporting(0);
date_default_timezone_set("Asia/Manila");
session_start();
$db = new mysqli('localhost', 'root', '', 'overdrive');

function audit($desc) {
    $id = $_SESSION['id'];
    $date = time();
    $db = new mysqli('localhost', 'root', '', 'overdrive');
    $db->query("INSERT INTO audit_trail (`account_id`,`description`,`audit_date`)"
                    . " VALUES ('$id','$desc',$date)") or die($db->error);
}

function file_name() {
    return basename($_SERVER['PHP_SELF'], ".php");
}
