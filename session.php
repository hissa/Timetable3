<?php
if ($_GET["noAction"]){
    die();
}

session_start();

// var_dump(time());die();

if (isset($_GET["sessiondestroy"])){
    $_SESSION = [];
    setcookie("PHPSESSID", "", time() - 3600, "/");
    session_destroy();
    echo "終了しました";
    die();
}


if (!isset($_SESSION["accessNum"])){
    echo "初回の訪問です。セッションを開始します。";
    $_SESSION["accessNum"] = 0;
} else {
    echo "セッションは開始済みです。<br>";
    echo "セッションIDは".$_COOKIE["PHPSESSID"]."です。<br>";
    $_SESSION["accessNum"] = $_SESSION["accessNum"] == null ? 0 : $_SESSION["accessNum"];
    $_SESSION["accessNum"]++;
    echo "接続回数は".$_SESSION["accessNum"]."です。";
}

session_regenerate_id(true);
