<?php
$error = [];

// 必要な入力が空だった場合はエラー内容を添えてリダイレクトする。
if(empty($_POST["id"])){
    $error["id"] = "notInput";
}
if(empty($_POST["pass"])){
    $error["pass"] = "notInput";
}
if(empty($_POST["repass"])){
    $error["repass"] = "notInput";
}
if($error){
    $error["page"] = "newAccount";
    $redirect = "../index.php?".http_build_query($error);
    header("Location: ".$redirect);
    die();
}

// パスワードの確認が一致しなければリダイレクトする。
if($_POST["pass"] != $_POST["repass"]){
    $error["repass"] = "unmach";
    $error["page"] = "newAccount";
    $redirect = "../index.php?".http_build_query($error);
    header("Location: ".$redirect);
    die();
}

error_reporting(E_ALL & ~E_NOTICE);
require_once "../php/Administrator.php";
require_once "../php/Config.php";
$SETTINGS = new Config("../config.ini", "../timetable1.ini");

// 招待コードが使用できるか確認し、使用できなければリダイレクトする。
$key = $_POST["invite"];
if(!$keyId = Administrator::canUseInviteKey($key)){
    $error["invite"] = "cannotuse";
    $error["page"] = "newAccount";
    $redirect = "../index.php?".http_build_query($error);
    header("Location: ".$redirect);
    die();
}

$name = $_POST["id"];
$pass = $_POST["pass"];

$newAdmin = Administrator::create($name, 0, $pass, 0);
try{
    $newAdmin->add();
    Administrator::UseInviteKey($keyId);
    header("Location: ../index.php?page=login&msg=succed");
    die();
}catch(Exception $e){
    header("Location: ../index.php?msg=error");
    die();
}
