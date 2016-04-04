<?php include "./requireList.php";?>
<?php include "./parts/sessionLogin.php"; ?>
<?php
if(is_null($admin)){
    header("Location: ./index.php?page=login");
}
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include "./common.php"; ?>
        <?php TimetableCarbon::setTestNow(TimetableCarbon::parse("2015/12/8")) ?>
    </head>
    <body>
        <?php include "./parts/header.php"; ?>
        <div class="container">
            <?php
            switch ($_GET["page"]){
                case null:
                    include "./parts/adminMenu.php";
                    break;
                case "list":
                    include "./parts/taskList.php";
                    break;
                default:
                    include "./parts/error.php";
                    break;
            }
            ?>
        </div>
    </body>
</html>
