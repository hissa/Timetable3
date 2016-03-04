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

}
//
// $SETTINGS = new Config("../config.ini", "../timetable1.ini");
// $admin = Administrator::fetch(1);
// $admin->name = "hissatest";
// $admin->update();
// var_dump($admin);
// $admin = Administrator::create("hissa2", 0, "test2", 1);
// $admin->add();
