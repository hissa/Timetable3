<?php require_once "./requireList.php";?>
<?php require_once "./parts/sessionLogin.php"; ?>
<?php
if(is_null($admin)){
    switch($_GET["page"]){
        case "timetable1":
        case "logout":
        case "taskedit":
            header("Location: ?page=login");
            break;
        default:
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include "./common.php"; ?>
        <?php TimetableCarbon::setTestNow(TimetableCarbon::parse("2016/1/9")); ?>
    </head>
    <body>
        <?php include "./parts/header.php"; ?>
        <div class="container">
            <?php
            switch($_GET["msg"]){
                case "succed":
                    include "./parts/alert-succed.php";
                    break;
                case "error":
                    include "./parts/alert-error.php";
                    break;
            }
            switch ($_GET["page"]){
                case null:
                    include "./parts/toppage.php";
                    break;
                case "timetable1":
                    include "./parts/timetable1.php";
                    break;
                case "login":
                    include "./parts/loginForm.php";
                    break;
                case "logout":
                    include "./parts/logout.php";
                    break;
                case "taskedit":
                    include "./parts/taskList.php";
                    break;
                default:
                    include "./parts/alert-notfound.php";
                    break;
            }
            ?>
        </div>
    </body>
</html>
