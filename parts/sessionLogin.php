<?php
session_start();
if (isset($_COOKIE[session_name()])){
    try {
        $admin = Administrator::loginUsingSession();
    } catch(Exception $e){
        echo "error";
        Administrator::sessionLogout();
    }
} else {
    $admin = null;
}
var_dump($admin);
