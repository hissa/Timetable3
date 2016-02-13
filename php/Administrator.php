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
    protected $logined;

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
        if(!password_verify($password, $this->hashedPassword)){
            throw new Exception("ログインに失敗しました。");
        }
        $this->logined = true;
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
        switch($name){
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
        switch($name){
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
        if(is_null($this->id)){
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

}
//
// $SETTINGS = new Config("../config.ini", "../timetable1.ini");
// $admin = Administrator::fetch(1);
// $admin->name = "hissatest";
// $admin->update();
// var_dump($admin);
