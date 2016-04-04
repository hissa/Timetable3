<div class="container col-md-6 col-md-offset-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            ログイン
        </div>
        <div class="panel-body">
            <?php
            if ($_GET["msg"] == "failed"){
                echo "<div class=\"alert alert-danger\">";
                echo "<span class=\"glyphicon glyphicon-remove-sign\" aria-hidden=\"true\"></span>";
                echo " ログインに失敗しました。";
                echo "</div>\n";
            }
            if ($_GET["msg"] == "logedout"){
                echo "<div class=\"alert alert-success\">";
                echo "<span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>";
                echo " ログアウトしました。";
                echo "</div>\n";
            }
            ?>
            <form action="./parts/login.php" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" id="inputId"
                    placeholder="管理者名" name="name">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control"
                    id="InputPassword" placeholder="パスワード"
                    name="password">
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="keepLogin" value="true">
                        ログインを継続する
                    </label>
                </div>
                <?php
                // 元のページにリダイレクトできないことがあるので
                // すべてトップページに戻ることとする。
                // $redirect = isset($_SERVER["HTTP_REFERER"])
                //             ? $_SERVER["HTTP_REFERER"] : "../index.php";
                $redirect = "../index.php";
                ?>
                <input type="text" class="form-control hidden"
                value="<?php echo $redirect; ?>" name="redirect">
                <button type="submit" class="btn btn-default btn-block btn-primary">
                    ログイン
                </button>
            </form>
        </div>
    </div>
</div>
