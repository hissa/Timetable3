<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once "./php/Config.php";
require_once "./php/Carbon.php";
use Carbon\Carbon;
require_once "./php/Timetable.php";
require_once "./php/Database.php";
require_once "./php/Task.php";
require_once "./php/Administrator.php";
$SETTINGS = new Config("./config.ini", "./timetable1.ini");
?>
