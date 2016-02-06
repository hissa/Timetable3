<?php
require_once "Database.php";
require_once "Carbon.php";
require_once "Config.php";
use Carbon\Carbon;


/**
 * 課題についての情報を扱うクラスです。
 * このクラスは予めグローバル変数$SETTINGSにConfigクラスのインスタンスを用意しておく必要があります。
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
    protected $modified;

    /**
     * 削除された
     * @var boolean
     */
    protected $deleted;

    /**
     * このクラスのコンストラクタです。
     * 全てのメンバ変数を指定してインスタンスを作る時のみnewを使用し、
     * 通常はfetchやcreateメソッドを使用すること。
     * このコンストラクタはNULLを許容するので安全ではありません。
     * @param int|null $id      タスクid
     * @param Carbon|null $date    タスクの日付
     * @param Subject|null $subject タスクの教科
     * @param string|null $content タスクの内容
     * @param Carbon|null $modified 更新日時
     */
    protected function __construct($id = null, $date = null,
                                $subject = null, $content = null,
                                $modified = null, $deleted = null
                                ){
        // 決まった型かnullに当てはまらない場合は処理を中断します。
        if(gettype($id) !== "integer" && !is_null($id)){
            throw new Exception("idはintで指定してください。");
        }
        if(is_a($date, "Carbon\Carbon") !== true && !is_null($date)){
            throw new Exception("dateはCarbonクラスのインスタンスで指定してください。");
        }
        if(get_class($subject) !== "Subject" && !is_null($subject)){
            throw new Exception("subjectはSubjectクラスのインスタンスで指定してください。");
        }
        if(gettype($content) !== "string" && !is_null($content)){
            throw new Exception("contentはstringで指定してください。");
        }
        if(is_a($date, "Carbon\Carbon") !== true && !is_null($modified)){
            throw new Exception("modifiedはCarbonクラスのインスタンスで指定してください。");
        }
        $this->id = $id;
        $this->date = $date;
        $this->subject = $subject;
        $this->content = $content;
        $this->modified = $modified;
        $this->deleted = $deleted;
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
        $date = static::fetchDate($id);
        $subject = static::fetchSubject($id);
        $content = static::fetchContent($id);
        $modified = static::fetchModified($id);
        $deleted = static::fetchDeleted($id);
        return new self($id, $date, $subject, $content, $modified, $deleted);
    }

    /**
     * 新しくタスクを作成し、インスタンスとして返す。
     * @param  Carbon $date    タスクの日付
     * @param  int|Subject $subject 教科
     * @param  string $content タスクの内容
     * @return Task          作成されたインスタンス
     */
    public static function create($date, $subject, $content){
        if(is_a($date, "Carbon\Carbon") !== true){
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
        global $SETTINGS;
        $db = new Database();
        $sql = "select date from ".$SETTINGS->dbTasks.
                " where id=".$id.";";
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
        global $SETTINGS;
        $db = new Database();
        $sql = "select subject_id from ".$SETTINGS->dbTasks.
                " where id=".$id.";";
        $stmt = $db->query($sql);
        $result = Database::encode($stmt);
        return new Subject(intval($result[0][0]));
    }

    /**
     * タスクidを元にそのタスクの内容を取得します。
     * @param  int $id タスクid
     * @return string     タスクの内容
     */
    protected static function fetchContent($id){
        global $SETTINGS;
        $db = new Database();
        $sql = "select content from ".$SETTINGS->dbTasks." where id=".$id.";";
        $stmt = $db->query($sql);
        $result = Database::encode($stmt);
        return $result[0][0];
    }

    /**
     * タスクidを元にそのタスクの更新日時を取得します。
     * @param  int $id タスクid
     * @return Carbon     タスクの更新日時
     */
    protected static function fetchModified($id){
        global $SETTINGS;
        $db = new Database();
        $sql = "select modified from ".$SETTINGS->dbTasks.
                " where id=".$id.";";
        $stmt = $db->query($sql);
        $result = Database::encode($stmt);
        return Carbon::parse($result[0][0]);
    }

    protected static function fetchDeleted($id){
        global $SETTINGS;
        $db = new Database();
        $sql = "select modified from ".$SETTINGS->dbTasks.
                " where id=".$id.";";
        $stmt = $db->query($sql);
        $result = Database::encode($stmt);
        return $result[0][0];
    }

    /**
     * 自身のインスタンスの情報をデータベースに追加することができるかどうかを確認します。
     * @return boolean 追加することができるならばtrueを返す
     */
    protected function canAddToDatabase(){
        if($this->doesIdExist()){
            return false;
        }
        return true;
    }

    /**
     * 自身のインスタンスの情報をデータベースに上書きすることができるかどうかを確認します。
     * @return boolean 上書きすることができるならばtrueを返す
     */
    protected function canOverwriteToDatabase(){
        global $SETTINGS;
        $db = new Database();
        $idExists = $this->doesIdExist();
        if(!$idExists){
            return false;
        }
        $dbDate = static::fetchDate($this->id);
        $thisDate = $this->date;
        $dateEqual = $dbDate->eq($thisDate);
        if(!$dateEqual){
            return false;
        }
        return true;
    }

    /**
     * 自身のインスタンスのIDがデータベース上に存在するかどうかを確認します。
     * @return bool 存在したらtrueを返す
     */
    protected function doesIdExist(){
        if(is_null($this->id)){
            return false;
        }
        global $SETTINGS;
        $db = new Database();
        $sql = "select exists(select id from ".$SETTINGS->dbTasks.
                " where id=".$this->id.");";
        $stmt = $db->query($sql);
        $result = Database::encode($stmt);
        return $result[0][0];
    }

    /**
     * 新しいTaskとして自身のインスタンスの情報をデータベースに追加します。
     * データベースに追加する条件を満たしていなければ例外をスローします。
     */
    public function addNewTask(){
        global $SETTINGS;
        if(!$this->canAddToDatabase()){
            throw new Exception("データベースに追加できる条件を満たしていません。");
        }
        $db = new Database();
        $sql = "insert into ".$SETTINGS->dbTasks."(date, subject_id, content, modified)".
                "values(\"".$this->date->format("Y-m-d")."\",".
                $this->subject->getId().",\"".$this->content."\",\"".
                Carbon::now()."\");";
        $db->query($sql);
    }

    /**
     * データベースにある既存のtaskを上書きします。
     * 上書きできる条件を満たしていなければ例外をスローします。
     */
    public function overwriteToDatabase(){
        global $SETTINGS;
        if(!$this->canOverwriteToDatabase()){
            throw new Exception("データベースに上書きする条件を満たしていません。");
        }
        $db = new Database();
        $sql = "update ".$SETTINGS->dbTasks." set content=\"".$this->content.
                "\" where id=".$this->id.";";
        $db->query($sql);
    }

    /**
     * 自身のインスタンスのcontentに文字列をsetします。
     * @param string $content setする文字列
     */
    public function setContent($content){
        $this->content = $content;
    }

    /**
     * 自身のインスタンスのcontentを取得します。
     * @return string content
     */
    public function getContent(){
        return $this->content;
    }

    /**
     * 自身のインスタンスに削除フラグを立てます。
     * このメソッドを実行しても直ちにデータベースに保存されることはありません。
     * overwriteToDatabase()メソッドを実行して上書きする必要があります。
     */
    public function delete(){
        $this->deleted = 1;
    }

    /**
     * 日付と教科を指定してそれに合う情報があればTaskインスタンスで返します。
     * 削除フラグが立っているものは取得しません。
     * @param  Carbon\Carbon $date    日付
     * @param  Subject $subject 教科
     * @return Task|NULL          情報
     */
    public static function fetchTask($date, $subject){
        global $SETTINGS;
        $db = new Database();
        $sql = "select id from ".$SETTINGS->dbTasks.
                " where date=\"".$date->format("Y-m-d")."\" and".
                " subject_id=".$subject->getId()." and".
                " deleted=0;";
        $stmt = $db->query($sql);
        $result = Database::encode($stmt);
        if($result[0][0]){
            return static::fetch(intval($result[0][0]));
        }else{
            return null;
        }
    }

}

/**
 * 教科の情報を扱うクラスです。
 * 予めグローバル変数$SETTINGSにConfigクラスのインスタンスを用意しておく必要があります。
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
        if(!static::doesIdExist($id)){
            throw new Exception("指定された教科idは存在しない可能性があります。");
        }
        $this->id = $id;
        $this->name = static::fetchName($this->id);
        $this->shortName = static::fetchShortName($this->id);
    }

    /**
     * 指定されたidが存在するかどうかを確認する
     * @param  int  $id 教科id
     * @return boolean     存在する場合はtrueを返します。
     */
    protected static function doesIdExist($id){
        global $SETTINGS;
        $db = new Database();
        $sql = "select exists(select id from ".$SETTINGS->dbSubjects.
                " where id=".$id.");";
        $stmt = $db->query($sql);
        $result = Database::encode($stmt);
        return $result[0][0];
    }

    /**
     * 教科idから教科名を取得します。
     * @param  int $subjectId 教科id
     * @return string            教科名
     */
    protected static function fetchName($id){
        global $SETTINGS;
        $db = new Database();
        $sql = "select name from ".$SETTINGS->dbSubjects.
                " where id=".$id.";";
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
        global $SETTINGS;
        $db = new Database();
        $sql = "select short_name from ".$SETTINGS->dbSubjects.
                " where id=".$id.";";
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
     * 自身の短縮教科名を取得します。
     * @return string 短縮教科名
     */
    public function getShortName(){
        return $this->shortName;
    }

    /**
     * 自身の教科idを取得します。
     * @return int 教科id
     */
    public function getId(){
        return $this->id;
    }

    /**
     * 曜日とコマ数目を指定して教科を取得します。
     * @param  int $dayOfWeek 曜日
     * @param  int $classNum  コマ数目
     * @return Subject       教科
     */
    public static function fetchSchedule($dayOfWeek, $classNum){
        global $SETTINGS;
        $db = new Database();
        $sql = "select subject_id from ".$SETTINGS->dbSchedules.
                " where day_of_week=".$dayOfWeek." and".
                " class_number=".$classNum.";";
        $stmt = $db->query($sql);
        $result = Database::encode($stmt);
        return new static(intval($result[0][0]));
    }
}
?>
