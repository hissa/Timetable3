<?php include "./requireList.php";?>
<?php include "./parts/sessionLogin.php"; ?>
<?php
if(is_null($admin)){
    switch($_GET["page"]){
        case "timetable1":
        case "logout":
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
                default:
                    include "./parts/error.php";
                    break;
            }
            ?>
        </div>
    </body>
</html>
