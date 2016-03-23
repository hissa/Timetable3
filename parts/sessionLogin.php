<?php

if (isset($_COOKIE[session_name()])){
    session_start();
    try {
        $admin = Administrator::loginUsingSession();
    } catch(Exception $e){
        Administrator::sessionLogout();
    }
} else {
    $admin = null;
}
// var_dump($admin);
