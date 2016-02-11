<!DOCTYPE html>
<html lang="ja">
    <head>
        <?php include "./common.php"; ?>
    </head>
    <body>
        <?php include "./parts/header.html"; ?>
        <div class="container">
            <?php
            switch($_GET["page"]){
                case null:
                    include "./parts/toppage.php";
                    break;
                case "timetable1":
                    include "./parts/timetable1.php";
                    break;
                default:
                    include "./parts/error.php";
                    break;
            }
            ?>
        </div>
    </body>
</html>
