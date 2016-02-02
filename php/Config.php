<?php

/**
 * コンフィグを保持するクラスについてを書きます。
 */

/**
 * コンフィグの情報を保持します。
 */
class Config{
    // 基本設定
    /**
     * configで指定されたデータベースのプリセットです。
     * @var string
     */
    private $dbPriset;

    /**
     * configで指定された動作モードです。
     * @var string
     */
    private $mode;

    // 全体設定で指定されたものを読み込む設定
    /**
     * configで指定されたデータベースのホスト名です。
     * @var string
     */
    private $dbHost;

    /**
     * configで指定されたデータベース名です。
     * @var string
     */
    private $dbName;

    /**
     * configで指定されたデータベースのユーザー名です。
     * @var string
     */
    private $dbUser;

    /**
     * configで指定されたデータベースのパスワードです。
     * @var string
     */
    private $dbPass;

    // 個別設定で指定されたものを読み込む設定
    /**
     * configで指定された教科一覧のテーブル名です。
     * @var string
     */
    private $dbSubjects;

    /**
     * configで指定された課題スケジュールのテーブル名です。
     * @var string
     */
    private $dbTasks;

    /**
     * configで指定された時間割のテーブル名です。
     * @var [type]
     */
    private $dbSchedules;

    /**
     * 表の曜日部分に表示するヘッダーのテキストです（カンマ区切り）
     * @var string
     */
    private $headOfWeek;

    /**
     * 表のコマ数部分に表示するヘッダーのテキストです（カンマ区切り）
     * @var string
     */
    private $headOfSide;

    /**
     * 表の曜日部分のヘッダーを表示するかどうか（表示する:1 表示しない:0）
     * @var int
     */
    private $showTopHead;

    /**
     * 表のコマ数部分のヘッダーを表示するかどうか（表示する:1 表示しない:0）
     * @var int
     */
    private $showSideHead;

    /**
     * このクラスのコンストラクタです。
     * @param string 全体configファイルのパス
     * @param string 個別configファイルのパス
     * @return null
     */
    public function __construct($mainPass, $subPass){
        $config = parse_ini_file($mainPass, true);
        // 代入したときの返り値がNULLならば取得に失敗したと判断して
        // 例外をスローする。
        if(is_null($this->dbPriset = $config["Main"]["DatabasePriset"])){
            throw new Exception("全体configのDatabasePrisetの".
                                "値が見つかりません。");
        }
        if(is_null($this->mode = $config["Main"]["Mode"])){
            throw new Exception("全体configのModeの値が見つかりません。");
        }
        if(is_null($this->dbHost = $config[$this->dbPriset]["Host"])){
            throw new Exception("全体configのHostの値が見つかりません。");
        }
        if(is_null($this->dbName = $config[$this->dbPriset]["DbName"])){
            throw new Exception("全体configのDbNameの値が見つかりません。");
        }
        if(is_null($this->dbUser = $config[$this->dbPriset]["Username"])){
            throw new Exception("全体configのUsernameの値が見つかりません。");
        }
        if(is_null($this->dbPass = $config[$this->dbPriset]["Password"])){
            throw new Exception("全体configのPasswordの値が見つかりません。");
        }
        $config = parse_ini_file($subPass, true);
        if(is_null($this->dbSubjects = $config["SubjectsTableName"])){
            throw new Exception("個別configのSubjectsTableName".
                                "の値が見つかりません。");
        }
        if(is_null($this->dbTasks = $config["TasksTableName"])){
            throw new Exception("個別configのTasksTableName".
                                "の値が見つかりません。");
        }
        if(is_null($this->dbSchedules = $config["SchedulesTableName"])){
            throw new Exception("個別configのSchedulesTableName".
                                "の値が見つかりません。");
        }
        if(is_null($this->headOfWeek = $config["headOfWeek"])){
            $this->headOfWeek = "月,火,水,木,金"; // 初期値
        }
        if(is_null($this->sideOfWeek = $config["sideOfWeek"])){
            $this->headOfWeek = "1,2,3"; // 初期値
        }
        if(is_null($this->showTopHead = intval($config["showTopHead"]))){
            $this->showTopHead = 1; // 初期値
        }else{
            // 0以外の数が入っていた場合は1にする。
            if($this->showTopHead === 0){
                $this->showTopHead = 1;
            }
        }
        if(is_null($this->showSideHead = intval($config["showSideHead"]))){
            $this->showSideHead = 1; //初期値
        }else{
            // 0以外の数が入っていた場合は1にする。
            if($this->showSideHead === 0){
                $this->showTopHead = 0;
            }
        }
    }

    /**
     * ReadOnlyのアクセサ代わりの__getメソッドです。
     * 直接インスタンスの変数にアクセスしようとするとこのメソッドが呼び出されます。
     * @param  string $name アクセスしようとした変数名
     * @return string       アクセスしようとした変数の値
     */
    public function __get($name){
        switch($name){
            case "dbPriset":
                return $this->dbPriset;
            case "mode":
                return $this->mode;
            case "dbHost":
                return $this->dbHost;
            case "dbName":
                return $this->dbName;
            case "dbUser":
                return $this->dbUser;
            case "dbPass":
                return $this->dbPass;
            case "dbSubjects":
                return $this->dbSubjects;
            case "dbTasks":
                return $this->dbTasks;
            case "dbSchedules":
                return $this->dbSchedules;
            case "headOfWeek":
                return $this->headOfWeek;
            case "headOfSide":
                return $this->headOfSide;
            case "showTopHead":
                return $this->showTopHead;
            case "showSideHead":
                return $this->showSideHead;
            default:
                throw new Exception("存在しない変数がアクセスされました。");
        }
    }
}
?>
