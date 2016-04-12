<div class="container col-md-6 col-md-offset-3">
    <div class="panel panel-default">
        <div class="panel-heading">
            新規アカウント作成
        </div>
        <div class="panel-body">
            <div class="form-group" id="inviteform">
                <label for="invite">招待コード</label>
                <input type="text" class="form-control" id="invite"
                placeholder="招待コードがある場合は入力してください。"
                onChange="showCheck()">
            </div>
            <div class="form-group" id="idform">
                <label for="id">ユーザー名</label>
                <input type="text" class="form-control" id="id"
                onChange="showCheck()">
            </div>
            <div class="form-group" id="passform">
                <label for="pass">パスワード</label>
                <input type="password" class="form-control" id="pass"
                onChange="showCheck()">
            </div>
            <div class="form-group" id="repassform">
                <label for="repass">パスワードの再入力</label>
                <input type="password" class="form-control" id="repass"
                onChange="showCheck()">
            </div>
            <button type="button" class="btn btn-primary btn-block"
            data-toggle="modal" data-target="#modalCheck">
                確認画面へ
            </button>
            <div class="modal fade" id="modalCheck" tabindex="-1">
                <form action="./parts/newAccount.php" method="post">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close"
                                data-dismiss="modal"><span>×</span></button>
                                確認画面
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>項目</th>
                                            <th>入力内容</th>
                                        </tr>
                                    </thead>
                                    <tr id="checkinvitekeyrow">
                                        <td>招待コード</td>
                                        <td><span id="checkinvitekey"></span></td>
                                    </tr>
                                    <tr id="checkusernamerow">
                                        <td>ユーザー名</td>
                                        <td><span id="checkusername"></span></td>
                                    </tr>
                                    <tr id="checkpassrow">
                                        <td>パスワード</td>
                                        <td><span id="checkpass">表示しません</span></td>
                                    </tr>
                                </table>
                                <input type="text" class="hidden" id="postinvite"
                                name="invite">
                                <input type="text" class="hidden" id="postid"
                                name="id">
                                <input type="password" class="hidden" id="postpass"
                                name = "pass">
                                <input type="password" class="hidden" id="postrepass"
                                name = "repass">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary"
                                id="completebutton">
                                    アカウント作成
                                </button>
                                <button type="button" class="btn btn-default"
                                data-dismiss="modal">
                                    キャンセル
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <script type="text/javascript">
            window.onload = function(){
                showCheck();
            }
            // 確認画面に入力内容を反映
            // リファクタリング予定地
            function showCheck(){
                var err = false;
                var inviteKey = $("#invite").val();
                var userName = $("#id").val();
                $("#checkinvitekey").get(0).textContent = inviteKey;
                $("#checkusername").get(0).textContent = userName;
                if(inviteKey.length < 1){
                    err = true;
                    $("#checkinvitekeyrow").get(0).className =
                        "danger";
                    $("#checkinvitekey").get(0).textContent =
                        "招待コードを入力してください";
                    $("#inviteform").get(0).className = "form-group has-error";
                }else{
                    $("#checkinvitekeyrow").get(0).className =
                        "success";
                    $("#inviteform").get(0).className = "form-group has-success";
                }
                if(userName.length < 3){
                    err = true;
                    $("#checkusernamerow").get(0).className =
                        "danger";
                    $("#checkusername").get(0).textContent =
                        "ユーザー名は3文字以上です";
                    $("#idform").get(0).className = "form-group has-error";
                }else{
                    $("#checkusernamerow").get(0).className =
                        "success";
                    $("#idform").get(0).className = "form-group has-success";
                }
                var pass = $("#pass").val();
                var rePass = $("#repass").val();
                if(pass != rePass){
                    err = true;
                    $("#checkpass").get(0).textContent = "再入力が不一致";
                    $("#checkpassrow").get(0).className = "danger";
                    $("#repassform").get(0).className = "form-group has-error";
                }else{
                    $("#checkpass").get(0).textContent = "表示しません";
                    $("#checkpassrow").get(0).className = "";
                    $("#repassform").get(0).className = "form-group has-success";
                }

                if(pass.length < 5){
                    err = true;
                    $("#checkpassrow").get(0).className =
                        "danger";
                    $("#checkpass").get(0).textContent =
                        "パスワードは4文字以上です";
                    $("#passform").get(0).className = "form-group has-error";
                }else{
                    $("#checkpassrow").get(0).className =
                        "success";
                        $("#passform").get(0).className = "form-group has-success";
                }
                $("#postinvite").val(inviteKey);
                $("#postid").val(userName);
                $("#postpass").val(pass);
                $("#postrepass").val(rePass);
                if(err){
                    $("#completebutton").get(0).className =
                        "btn btn-primary disabled";
                }else{
                    $("#completebutton").get(0).className =
                        "btn btn-primary";
                }
            }
            </script>
        </div>
    </div>
</div>
