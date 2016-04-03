<?php
require_once "Database.php";
require_once "Carbon.php";
require_once "Config.php";
require_once "password.php";
use Carbon\Carbon;

/**
 * 管理者を扱うクラスです。
 */
class Administrator{

    /**
     * 管理者ID
     * @var int
     */
    protected $id;

    /**
     * 管理者名
     * @var string
     */
    protected $name;

    /**
     * 操作権限を持つ学年
     * @var int
     */
    protected $grade;

    /**
     * ハッシュ化されたログインパスワード
     * @var string
     */
    protected $hashedPassword;

    /**
     * 権限レベル
     * @var int
     */
    protected $permissionLevel;

    /**
     * ログインされているかどうか
     * @var bool
     */
    protected $loggedIn;

    /**
     * コンストラクタ
     * @param int $id              管理者ID
     * @param string $name            管理者名
     * @param int $grade           管理者の学年
     * @param string $hashedPassword  ハッシュ化されたパスワード
     * @param int $permissionLevel 権限レベル
     */
    protected function __construct($id, $name, $grade, $hashedPassword, $permissionLevel){
        $this->id = $id;
        $this->name = $name;
        $this->grade = $grade;
        $this->hashedPassword = $hashedPassword;
        $this->permissionLevel = $permissionLevel;
    }

    /**
     * 管理者IDを指定してその管理者の情報を持つ管理者クラスのインスタンスを返します。
     * @param  int $id 管理者ID
     * @return Administrator     情報を持つインスタンス
     */
    public static function fetch($id){
        if (!static::doesIdExist($id)){
            throw new Exception("指定されたIDが存在しません。");
        }
        $name = static::fetchName($id);
        $grade = intval(static::fetchGrade($id));
        $hashedPassword = static::fetchHashedPassword($id);
        $permissionLevel = intval(static::fetchPermissionLevel($id));

        return new static($id, $name, $grade, $hashedPassword, $permissionLevel);
    }

    /**
     * 情報を渡してその情報を持つAdministratorクラスのインスタンスを返します。
     * @param  string $name            管理者名
     * @param  int $grade           管理者の学年
     * @param  string $password        ハッシュ化されていないパスワード
     * @param  int $permissionLevel 管理者の権限レベル
     * @return Administrator                  インスタンス
     */
    public static function create($name, $grade, $password, $permissionLevel){
        $hashedPassword = static::hashPassword($password);
        return new static(null, $name, intval($grade), $hashedPassword, $permissionLevel);
    }

    /**
     * パスワードを指定して自身のアカウントにログインする。
     * このメソッドはログインに失敗した際に例外を発生させます。
     * @param  string $password パスワード
     * @return bool           ログイン成功した場合にtrueを返す。
     */
    public function login($password){
        if (!password_verify($password, $this->hashedPassword)){
            throw new Exception("ログインに失敗しました。");
        }
        $this->loggedIn = true;
        return true;
    }

    /**
     * パスワードをハッシュします。
     * @param  string $password パスワード
     * @return string           ハッシュ化されたパスワード
     */
    protected static function hashPassword($password){
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $hashedPassword;
    }

    /**
     * GETアクセサ
     * @param  string $name アクセスされた変数名
     * @return anyType       変数の値
     */
    public function __get($name){
        switch ($name){
            case "id":
                return $this->id;
            case "name":
                return $this->name;
            case "grade":
                return $this->grade;
            case "hashedPassword":
                return $this->hashedPassword;
            case "permissionLevel":
                return $this->permissionLevel;
            default:
                throw new Exception("存在しない変数がアクセスされました。");
                break;
        }
    }

    /**
     * SETアクセサ
     * @param string $name  アクセスする変数名
     * @param anyType $value 代入する値
     */
    public function __set($name, $value){
        switch ($name){
            case "name":
                $this->name = $value;
                break;
            case "grade":
                $this->grade = $value;
                break;
            case "hashedPassword":
                $this->hashedPassword = $value;
                break;
            case "permissionLebel":
                $this->hashedPassword = $value;
                break;
            default:
                throw new Exception("存在しない、またはアクセスが許可されていない".
                                    "変数がアクセスされました。");
                break;
        }
    }

    /**
     * IDから管理者名を取得します。
     * @param  int $id 管理者ID
     * @return string     管理者名
     */
    protected static function fetchName($id){
        $db = new Database();
        $sql = "select name from administrators where id=".$id.";";
        $stmt = $db -> query($sql);
        $result = Database::encode($stmt);
        return $result[0][0];
    }

    /**
     * IDから管理者の学年を取得します。
     * @param  int $id 管理者ID
     * @return int     管理者の学年
     */
    protected static function fetchGrade($id){
        $db = new Database();
        $sql = "select grade from administrators where id=".$id.";";
        $stmt = $db -> query($sql);
        $result = Database::encode($stmt);
        return $result[0][0];
    }

    /**
     * IDからハッシュ化されたパスワードを取得します。
     * @param  int $id 管理者ID
     * @return string     ハッシュ化されたパスワード
     */
    protected static function fetchHashedPassword($id){
        $db = new Database();
        $sql = "select hashed_password from administrators where id=".$id.";";
        $stmt = $db -> query($sql);
        $result = Database::encode($stmt);
        return $result[0][0];
    }

    /**
     * IDからその管理者の権限レベルを取得します。
     * @param  int $id 管理者ID
     * @return int     権限レベル
     */
    protected static function fetchPermissionLevel($id){
        $db = new Database();
        $sql = "select permission_level from administrators where id=".$id.";";
        $stmt = $db -> query($sql);
        $result = Database::encode($stmt);
        return $result[0][0];
    }

    /**
     * 管理者情報を更新します。
     */
    public function update(){
        if (is_null($this->id)){
            throw new Exception("管理者情報を上書きするためにはIDが必要です。");
        }
        $db = new Database();
        $sql = "update administrators set name=\"".$this->name."\", ".
                "grade=".$this->grade.", ".
                "hashed_password=\"".$this->hashedPassword."\", ".
                "permission_level=".$this->permissionLevel." ".
                "where id=".$this->id.";";
        $db->query($sql);
    }

    /**
     * 自身のインスタンスの情報をデータベースに登録します。
     */
    public function add(){
        if (!static::canAdd()){
            throw new Exception("情報を追加できる条件が整っていません。");
        }
        $grade = is_null($this->grade) ? "null" : $this->grade;
        $db = new Database();
        $sql = "insert into ".
                "administrators(name,grade,hashed_password,permission_level)".
                " values(?, ?, ?, ?);";
        $stmt = $db->prepare($sql);
        $db->execute($stmt, [$this->name, $grade, $this->hashedPassword,
                             $this->permissionLevel], false);
    }

    /**
     * 自身のインスタンスの管理者情報を追加できるかどうかを確認する。
     * @return bool 追加できるならばtrueを返す
     */
    public function canAdd(){
        if (!is_null($this->id)){
            return false;
        }
        if (static::doesNameExist($this->name)){
            return false;
        }
        if (is_null($this->hashedPassword)){
            return false;
        }
        return true;
    }

    /**
     * 管理者名が既に存在するかどうかを確認します。
     * @param  string $name 管理者名
     * @return bool       存在していればtrueを返す
     */
    protected static function doesNameExist($name){
        $db = new Database();
        $sql = "select exists(select id from administrators where name=?);";
        $stmt = $db->prepare($sql);
        $result = $db->execute($stmt, [$name]);
        return $result[0][0];
    }

    /**
     * 管理者名から管理者IDを検索します。
     * @param  string $name 管理者名
     * @return int       管理者ID
     */
    protected static function searchName($name){
        $db = new Database();
        $sql = "select id from administrators where name=?;";
        $stmt = $db->prepare($sql);
        $result = $db->execute($stmt, [$name]);
        if (!($result[0])){
            throw new Exception("管理者名が見つかりませんでした。");
        }
        return (int)$result[0][0];
    }

    /**
     * 管理者名とパスワードを指定してログインします
     * @param  string $name 管理者名
     * @param  string $pass パスワード
     * @return Administrator       ログインされた管理者のインスタンス
     */
    public static function loginFromName($name, $pass){
        $id = static::searchName($name);
        return static::loginFromId($id, $pass);
    }

    /**
     * 管理者IDとパスワードを指定してログインします。
     * @param  int $id   管理者ID
     * @param  string $pass パスワード
     * @return Administrator       ログインされたインスタンス
     */
    public static function loginFromId($id, $pass){
        if (!static::doesIdExist($id)){
            throw new Exception("指定されたIDが存在しません。");
        }
        $admin = static::fetch($id);
        $admin->login($pass);
        return $admin;
    }

    /**
     * 指定されたIDが存在するかどうかを返します。
     * @param  int $id 管理者ID
     * @return bool     存在すればtrueを返す
     */
    protected static function doesIdExist($id){
        $id = intval($id);
        $db = new Database();
        $sql = "select exists(select id from administrators where id=?);";
        $stmt = $db->prepare($sql);
        $result = $db->execute($stmt, [$id]);
        return $result[0][0];
    }

    /**
     * セッションからログインします。
     * @return Administrator ログイン済みのインスタンス
     */
    public static function loginUsingSession(){
        if (!static::canLoginUsingSession()){
            throw new Exception("ログインに必要な情報が存在しません。");
        }
        return static::loginFromName($_SESSION["name"], $_SESSION["password"]);
    }

    /**
     * セッションからログインする準備が整っているかを確認します。
     * @return bool 整っていればtrueを返す
     */
    protected static function canLoginUsingSession(){
        if (!isset($_SESSION["name"])){
            return false;
        }
        if (!isset($_SESSION["password"])){
            return false;
        }
        return true;
    }

    /**
     * セッションのログイン情報を削除します。
     */
    public static function sessionLogout(){
        $_SESSION = [];
        if (isset($_COOKIE[session_name()])){
            setcookie(session_name(), "", time() - 1800, "/");
        }
        @session_destroy();
    }

    /**
     * ログインされているかどうか
     * @return boolean されていればtrueを返す
     */
    public function isLoggedIn(){
        return $this->loggedIn == true ? true : false;
    }

    /**
     * アクションログを書き込みます。
     * @param  string $action 詳細
     */
    protected function writeActionLog($action){
        $id = $this->id;
        $datetime = Carbon::now()->format("Y-m-d H:i:s");
        $db = new Database();
        $sql = "insert into `action_logs`(`actioned`, `administrator_id`,".
               " `action`) values(?, ?, ?);";
        $stmt = $db->prepare($sql);
        $result = $db->execute($stmt, [$datetime, $id, $action], false);
    }

    /**
     * 操作ができるかどうかを確認します。
     * このメソッドは操作ができない場合に例外を発生させます。
     * @param  int $requestPermissionLevel 権限レベル
     * @return bool                         可能であればtrueを返す
     */
    protected function canAction($requestPermissionLevel){
        if (!$this->isLoggedIn()){
            throw new Exception("ログインしてください。");
        }
        if (!$this->doesHavePermission($requestPermissionLevel)){
            throw new Exception("操作権限が足りません。");
        }
        return true;
    }

    /**
     * 課題詳細を上書きします。
     * 操作ができない場合は例外が発生します。
     * @param int $id 対象のタスクid
     */
    public function setContent($id, $content){
        $requestLevel = 2;
        $this->canAction($requestLevel);
        $task = Task::fetch($id);
        $task->setContent($content);
        $task->overwriteToDatabase();
        $action = $this->name."がTask".$id."に".$content."を上書きしました。";
        $this->writeActionLog($action);
    }

    /**
     * Taskを削除します。
     * 操作ができない場合は例外が発生します。
     * @param  int $id タスクid
     */
    public function deleteTask($id){
        $requestLevel = 3;
        $this->canAction($requestLevel);
        $task = Task::fetch($id);
        $task->delete();
        $task->overwriteToDatabase();
        $action = $this->name."がTask".$id."を削除しました。";
        $this->writeActionLog($action);
    }

    /**
     * Taskを作成します。
     * 操作ができない場合は例外が発生します。
     * @param Carbon\Carbon $date      日付
     * @param Subject $subject 教科
     * @return int 作成されたTaskのIDを返す
     */
    public function addTask($date, $subject){
        $requestLevel = 3;
        $this->canAction($requestLevel);
        $task = Task::create($date, $subject, "");
        $addedTaskId = $task->addNewTask();
        $action = $this->name."がTask".$addedTaskId."を追加しました。";
        $this->writeActionLog($action);
        return $addedTaskId;
    }

    /**
     * Taskを作成して同時に詳細も追加します。
     * このメソッドを使用した場合でもアクションログは別々に記録されます。
     * @param Carbon\Carbon $date    日付
     * @param Subject $subject 教科
     * @param string $content 内容
     */
    public function addTaskSetContent($date, $subject, $content){
        $requestLevel = 3;
        $this->canAction($requestLevel);
        $id = $this->addTask($date, $subject);
        $this->setContent($id, $content);
    }

    /**
     * 操作権限が足りているかどうかを確認します。
     * @param  int $requestlevel 必要レベル
     * @return bool              足りていればtrueを返す
     */
    private function doesHavePermission($requestLevel){
        return $this->permissionLevel >= $requestLevel;
    }

    /**
     * 自動ログインのテーブルに自身を追加します。
     */
    public function addAutoLogin(){
        $key = uniqid(rand(),1);
        $hasshedKey = password_hash($key, PASSWORD_DEFAULT);
        $db = new Database();
        $sql = "insert into auto_login(user_id, last_logedin, login_key, user_agent) ".
                "value(".$this->id.", \"".Carbon::now()->format("Y-m-d H:i:s").
                "\", \"".$key."\", \"".$_SERVER["HTTP_USER_AGENT"]."\");";
        $db->query($sql);
        $keepTime = time() + 60 * 60 * 24 * 14;
        setcookie("autoLogin", $hasshedKey, $keepTime, "/");
        setcookie("userId", $this->id, $keepTime, "/");
    }

    /**
     * 自動ログインのキーを変更して期限もリセットします。
     */
    public function changeKey(){
        $this->destroyAutoLogin();
        $this->addAutoLogin();
    }

    /**
     * 自動ログインのためのクッキーを削除します。
     */
    public static function destroyAutoLoginCookie(){
        setcookie("autoLogin", "", time()-1000, "/");
        setcookie("userId", "", time()-1000, "/");
    }

    /**
     * 自動ログインを解除します。
     */
    public function destroyAutoLogin(){
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $db = new Database();
        $sql = "delete from auto_login ".
            "where user_id=".$this->id." and user_agent=\"".$ua."\";";
        $db->query($sql);
        static::destroyAutoLoginCookie();
    }

    /**
     * 継続ログインをオンにしてる場合に自動でログインします。
     * @return bool 成功ならtrueを返す
     */
    public static function autoLogin(){
        if(!isset($_COOKIE["autoLogin"])){
            throw new Exception("オートログインに失敗しました。");
        }
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $userid = $_COOKIE["userId"];
        $db = new Database();
        $sql = "select user_id, login_key from auto_login ".
                "where user_id=".$userid." and user_agent=\"".$ua."\" ".
                "order by last_logedin desc;";
        $stmt = $db->query($sql);
        $result = Database::encode($stmt);
        $hashedKey = $_COOKIE["autoLogin"];
        if($result[0]){
            $key = $result[0]["login_key"];
            if(password_verify($key, $hashedKey)){
                $admin = static::fetch($userid);
                $admin->forcedLogin();
                return $admin;
            }else{
                throw new Exception("オートログインに失敗しました。");
            }
        }else{
            throw new Exception("オートログインに失敗しました。");
        }
        throw new Exception("解決できません。");
    }

    /**
     * 強制的にログインします。
     */
    public function forcedLogin(){
        $this->loggedIn = true;
    }

}
