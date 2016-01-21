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

    // プリセットで指定されたものを読み込む設定
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

    /**
     * このクラスのコンストラクタです。
     * @param string configファイルのパス
     * @return null
     */
    public function __construct($pass){
        $config = parse_ini_file($pass, true);
        // 基本設定を読み込む。
        $this->dbPriset = $config["Main"]["DatabasePriset"];
        $this->mode = $config["Main"]["Mode"];
        // 基本設定を元に個別設定を読み込む
        $this->dbHost = $config[$this->dbPriset]["Host"];
        $this->dbName = $config[$this->dbPriset]["DbName"];
        $this->dbUser = $config[$this->dbPriset]["Username"];
        $this->dbPass = $config[$this->dbPriset]["Password"];
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
}
?>
