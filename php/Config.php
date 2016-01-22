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
    }

    /**
     * Modeの設定値を取得します。
     * @return string Modeの設定値
     */
    public function getMode(){
        return $this->mode;
    }

    /**
     * データベースのホスト名を取得します。
     * @return string データベースのホスト名
     */
    public function getDbHost(){
        return $this->dbHost;
    }

    /**
     * データベース名を取得します。
     * @return string データベース名
     */
    public function getDbName(){
        return $this->dbName;
    }

    /**
     * データベースのユーザー名を取得します。
     * @return string データベースのユーザー名
     */
    public function getDbUser(){
        return $this->dbUser;
    }

    /**
     * データベースのパスワードを取得します。
     * @return string データベースのパスワード
     */
    public function getDbPass(){
        return $this->dbPass;
    }

    /**
     * データベースの課題一覧のテーブル名を取得します。
     * @return string テーブル名
     */
    public function getTasks(){
        return $this->dbTasks;
    }

    /**
     * データベースの教科一覧のテーブル名を取得します。
     * @return string テーブル名
     */
    public function getSubjects(){
        return $this->dbSubjects;
    }

    /**
     * データベースの時間割のテーブル名を取得します。
     * @return string テーブル名
     */
    public function getSchedules(){
        return $this->dbSchedules;
    }
}
?>
