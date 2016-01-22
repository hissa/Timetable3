<?php
require_once "Database.php";
require_once "Carbon.php";
use "Carbon";

/**
 * 課題についての情報を扱うクラスです。
 */
class Task{
    // データベースから直接取得できるもの
    /**
     * タスクid
     * @var int
     */
    protected $id;

    /**
     * 日付
     * @var Carbon
     */
    protected $date;

    /**
     * 教科
     * @var Subject
     */
    protected $subject;

    /**
     * 課題の内容
     * @var string
     */
    protected $content;

    /**
     * 更新日時
     * @var Carbon
     */
    protected $mdified;

    /**
     * このクラスのコンストラクタです。
     * 全てのメンバ変数を指定してインスタンスを作る時のみnewを使用し、
     * 通常はfetchやcreateメソッドを使用すること。
     * このコンストラクタはNULLを許容するので安全ではありません。
     * @param int|null $id      タスクid
     * @param Carbon|null $date    タスクの日付
     * @param Subject|null $subject タスクの教科
     * @param string|null $content タスクの内容
     * @param Carbon|null $mdified 更新日時
     */
    protected function __construct($id = null, $date = null,
                                $subject = null, $content = null,
                                $mdified = null
                                ){
        // 決まった型かnullに当てはまらない場合は処理を中断します。
        if(gettype($id) !== "integer" || !is_null($id)){
            throw new Exception("idはintで指定してください。");
        }
        if(get_class($date) !== "Carbon" || !is_null($date)){
            throw new Exception("dateはCarbonクラスのインスタンスで指定してください。");
        }
        if(get_class($subject) !== "Subject" || !is_null($subject)){
            throw new Exception("subjectはSubjectクラスのインスタンスで指定してください。");
        }
        if(gettype($content) !== "string" || !is_null($content)){
            throw new Exception("contentはstringで指定してください。");
        }
        if(get_class($mdified) !== "Carbon" || !is_null($mdified)){
            throw new Exception("mdifiedはCarbonクラスのインスタンスで指定してください。");
        }
        $this->id = $id;
        $this->date = $date;
        $this->subject = $subject;
        $this->content = $content;
        $this->mdified = $mdified;
    }

    /**
     * タスクidを元にタスクを取得し、Taskクラスのインスタンスとして返す。
     * @param  int $id タスクid
     * @return Task     取得した情報を格納したTaskクラスのインスタンス
     */
    public static function fetch($id){
        if(gettype($id) !== "integer"){
            throw new Exception("idはintで指定してください。");
        }
        $date = self::fetchDate($id);
        $subject = self::fetchSubject($id);
        $content = self::fetchContent($id);
        $mdfied = self::fetchMdified($id);
        return new self($id, $date, $subject, $content, $mdified);
    }

    /**
     * 新しくタスクを作成し、インスタンスとして返す。
     * @param  Carbon $date    タスクの日付
     * @param  int|Subject $subject 教科
     * @param  string $content タスクの内容
     * @return Task          作成されたインスタンス
     */
    public static function create($date, $subject, $content){
        if(get_class($date) !== "Carbon"){
            throw new Exception("dateがCarbonクラスのインスタンスではありません。");
        }
        if(gettype($subject) === "integer"){
            // $subjectがidで渡された場合
            $subject = new Subject($subject);
        }
        if(get_class($subject) !== "Subject"){
            throw new Exception("subjectが整数またはSubjectクラスのインスタンスではありません。");
        }
        if(gettype($content) !== "string"){
            throw new Exception("contentはstringで指定してください。");
        }
        return new self(null, $date, $subject, $content, null);
    }

    /**
     * タスクidを元にそのタスクの日付を取得します。
     * @param  int $id タスクid
     * @return Carbon     タスクの日付
     */
    protected static function fetchDate($id){
        $db = new Database();
        $sql = "select date from tasks where id=".$id.";";
        $stmt = $db->query($sql);
        $result = Database::encode($stmt);
        return Carbon::parse($result[0][0]);
    }

    /**
     * タスクidを元にそのタスクの教科を取得します。
     * @param  int $id タスクid
     * @return Subject     タスクの教科
     */
    protected static function fetchSubject($id){
        $db = new Database();
        $sql = "select subject_id from tasks where id=".$id.";";
        $stmt = $db->query($sql);
        $result = Database::encode($stmt);
        return new Subject($result[0][0]);
    }

    /**
     * タスクidを元にそのタスクの内容を取得します。
     * @param  int $id タスクid
     * @return string     タスクの内容
     */
    protected static function fetchContent($id){
        $db = new Database();
        $sql = "select content from tasks where id=".$id.";";
        $stmt = $db->query($sql);
        $result = Database::encode($stmt);
        return $result[0][0];
    }

    /**
     * タスクidを元にそのタスクの更新日時を取得します。
     * @param  int $id タスクid
     * @return Carbon     タスクの更新日時
     */
    protected static function fetchMdified($id){
        $db = new Database();
        $sql = "select mdified from tasks where id=".$id.";";
        $stmt = $db->query($sql);
        $result = Database::encode($stmt);
        return Carbon::parse($result[0][0]);
    }

    /**
     * 自身のインスタンスをデータベースに登録することができるかどうかを確認します。
     */
    protected function canExist(){

    }
}

/**
 * 教科の情報を扱うクラスです。
 */
class Subject{
    /**
     * 教科id
     * @var int
     */
    protected $id;

    /**
     * 教科名
     * @var string
     */
    protected $name;

    /**
     * 英語教科名
     * @var string
     */
    protected $engName;

    /**
     * 短縮教科名
     * @var string
     */
    protected $shortName;

    /**
     * このクラスのコンストラクタです。
     * @param int $id 教科id
     */
    public function __construct($id){
        if(gettype($id) !== "integer"){
            throw new Exception("idはintで指定してください。");
        }
        $this->id = $id;
        $this->name = self::fetchName($this->id);
        $this->engName = self::fetchEnglishName($this->id);
        $this->shortName = self::fetchShortName($this->id);
    }

    /**
     * 教科idから教科名を取得します。
     * @param  int $subjectId 教科id
     * @return string            教科名
     */
    protected static function fetchName($id){
        $db = new Database();
        $sql = "select name from subjects where id=".$subjectId.";";
        $stmt = $db->query($sql);
        $result = Database::encode($stmt);
        return $result[0][0];
    }

    /**
     * 教科idから英語教科名を取得します。
     * @param  int $subjectId 教科id
     * @return string            英語教科名
     */
    protected static function fetchEnglishName($id){
        $db = new Database();
        $sql = "select english_name from subjects where id=".$subjectId.";";
        $stmt = $db->query($sql);
        $result = Database::encode($stmt);
        return $result[0][0];
    }

    /**
     * 教科idから短縮教科名を取得します。
     * @param  int $subjectId 教科id
     * @return string            短縮教科名
     */
    protected static function fetchShortName($id){
        $db = new Database();
        $sql = "select short_name from subjects where id=".$subjectId.";";
        $stmt = $db->query($sql);
        $result = Database::encode($stmt);
        return $result[0][0];
    }

    /**
     * 自身の教科名を取得します。
     * @return string 教科名
     */
    public function getName(){
        return $this->name;
    }

    /**
     * 自身の英語教科名を取得します。
     * @return string 英語教科名
     */
    public function getEnglishName(){
        return $this->engName;
    }

    /**
     * 自身の短縮教科名を取得します。
     * @return string 短縮教科名
     */
    public function getShortName(){
        return $this->shortName;
    }
}
?>
