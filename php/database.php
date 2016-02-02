<?php
/**
 * データベースにアクセスするためのクラスです。
 * データベースにはこのクラスのインスタンスからアクセスします。
 */
class Database{
    private $pdo;

    /**
     * このクラスのコンストラクタです。
     */
    public function  __construct(){
        global $SETTINGS;
        if(is_null($SETTINGS)){
            throw new Exception("設定ファイルを読み込むことができませんでした。");
        }
        $dbname = $SETTINGS->dbName;
        $host = $SETTINGS->dbHost;
        $dsn = "mysql:dbname=".$dbname.";host=".$host.";";
        $user = $SETTINGS->dbUser;
        $pass = $SETTINGS->dbPass;

        try{
            $this->pdo = new PDO($dsn, $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            $this->pdo->query("use ".$dbname);
            $this->pdo->query("SET NAMES utf8");
        }catch(Exception $e){
            echo "Error:".$e->getMessage();
        }
    }

    /**
     * 渡されたSQL文をそのまま実行します。
     * @param  string $sql SQL文
     * @return instance      返されたPDOステートメントオブジェクト
     */
    public function query($sql){
        try{
            $stmt = $this->pdo->query($sql);
        }catch(Exception $e){
            echo "Error:".$e->getMessage();
        }
        return $stmt;
    }

    /**
     * 渡されたPDOステートメントオブジェクトを配列として返します。
     * @param  instance $stmt PDOステートメントオブジェクト
     * @return string[][]       結果の2次元配列
     */
    public static function encode($stmt){
        $count = 0;
        while($result[$count] = $stmt->fetch(PDO::FETCH_BOTH)){
            $count++;
        }
        return $result;
    }
}
