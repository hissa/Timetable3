<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#topmenu">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">WEB版課題予定表</a>
        </div>
        <div class="collapse navbar-collapse" id="topmenu">
            <ul class="nav navbar-nav">
                <li><a href="index.php?page=timetable1">
                    1年次
                </a></li>
            </ul>
            <?php
            if(!is_null($admin)){
                echo "<p class=\"navbar-text\">";
                echo $admin->name." でログインされています。";
                echo "</p>";
                echo "<button type=\"button\" class=\"btn btn-default navbar-btn\"".
                    " onclick=\"location.href='?page=logout'\">";
                echo "ログアウト";
                echo "</button>";
            }else{
                echo "<button type=\"button\" class=\"btn btn-primary navbar-btn\"".
                    " onclick=\"location.href='?page=login'\">";
                echo "ログイン";
                echo "</button>";
            }
            ?>
        </div>
    </div>
</nav>
