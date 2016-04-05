<?php
if(is_null($_POST["taskId"])){
    error();
}

$taskId = (int)$_POST["taskId"];
$content = $_POST["inputContent"];

try{
    $admin->setContent($taskId, $content);
    succed();
}catch(Exception $e){
    error();
}
