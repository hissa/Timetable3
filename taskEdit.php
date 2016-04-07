<?php
require_once "./requireList.php";
require_once "./parts/sessionLogin.php";
use Carbon\Carbon;

function error(){
    header("Location: ./index.php?msg=error");
}

function succed(){
    header("Location: ./index.php?page=taskedit&msg=succed");
}

function overlapped(){
    header("Location: ./index.php?page=taskedit&msg=overlap");
}

if(is_null($admin)){
    header("Location: ./index.php?page=login");
}

switch($_GET["action"]){
    case "add":
        include "./parts/addTask.php";
        break;
    case "edit":
        include "./parts/editTask.php";
        break;
    case "delete":
        include "./parts/taskDelete.php";
        break;
    default:
        error();
        break;
}
