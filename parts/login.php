<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once "../php/Administrator.php";
require_once "../php/Config.php";
$SETTINGS = new Config("../config.ini", "../timetable1.ini");

define("LOGIN_FORM", "../index.php?page=login");
if (!isset($_POST["name"]) || !isset($_POST["password"])){
    header("Location: ".LOGIN_FORM."&msg=failed");
    die();
}

$name = $_POST["name"];
$pass = $_POST["password"];

try {
    $admin = Administrator::loginFromName($name, $pass);
} catch(Exception $e){
    header("Location: ".LOGIN_FORM."&msg=failed");
}

session_start();
$_SESSION["name"] = $name;
$_SESSION["password"] = $pass;
if($_POST["keepLogin"]){
    $admin->addAutoLogin();
}
header("Location: ".$_POST["redirect"]);
