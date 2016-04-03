<?php
if (isset($_COOKIE[session_name()])){
    session_start();
    try {
        $admin = Administrator::loginUsingSession();
    } catch(Exception $e){
        Administrator::sessionLogout();
    }
} else {
    if(isset($_COOKIE["autoLogin"])){
        $result = Administrator::autoLogin();
        $result->changeKey();
    }else{
        $admin = null;
    }
}
// var_dump($admin);
