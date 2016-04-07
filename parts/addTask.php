<?php
use Carbon\Carbon;
if(is_null($_POST["date"])){
    error();
}
if(is_null($_POST["subject"])){
    error();
}
if(is_null($_POST["grade"])){
    error();
}
$grade = $_POST["grade"];
$jpn = ["年", "月", "日"];
$sign = ["-", "-", ""];
$dateStr = str_replace($jpn, $sign, $_POST["date"]);
$date = Carbon::parse($dateStr);
$subject = new Subject((int)explode(".",$_POST["subject"])[0]);
$content = $_POST["content"];
try{
    if($content){
        $admin->addTaskSetContent($date, $subject, $content, $grade);
    }else{
        $admin->addTask($date, $subject, $grade);
    }
    succed();
}catch(Exception $e){
    if($e->getMessage() == "データベースに追加できる条件を満たしていません。"){
        overlapped();
        die();
    }
    error();
}
