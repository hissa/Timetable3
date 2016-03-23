<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="./bootstrap/css/bootstrap-datepicker.min.css" rel="stylesheet">
<link href="./style.css" rel="stylesheet">
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="./bootstrap/js/bootstrap.min.js"></script>
<script src="./bootstrap/js/bootstrap-datepicker.min.js"></script>
<script src="./bootstrap/locales/bootstrap-datepicker.ja.min.js"></script>

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
