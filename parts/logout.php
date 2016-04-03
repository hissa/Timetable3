<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once "./php/Administrator.php";
require_once "./php/Config.php";
$SETTINGS = new Config("./config.ini", "./timetable1.ini");

define("LOGIN_FORM", "./index.php?page=login");
Administrator::sessionLogout();
$admin->destroyAutoLogin();
header("Location: ".LOGIN_FORM."&msg=logedout");
