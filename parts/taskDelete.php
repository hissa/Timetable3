<?php
if(is_null($_POST["taskId"])){
    error();
}

$taskId = (int)$_POST["taskId"];

try{
    $admin->deleteTask($taskId);
    succed();
}catch(Exception $e){
    error();
}
