<?php

require_once "Carbon.php";
require_once "Config.php";
require_once "Task.php";
use Carbon\Carbon;



/**
 * 時間割表示用の日付を扱うクラスです。
 * Carbonクラスを継承し、コンストラクタに平日化と15時以降の日送りを追加します。
 */
class TimetableCarbon extends Carbon{

    public function __construct($time = null, $tz = null){
        parent::__construct($time, $tz);

        if ($this->hour >= 15){
            $this->addDay();
        }
        if ($this->dayOfWeek === 0){
            $this->addDay();
        }
        if ($this->dayOfWeek === 6){
            $this->addDays(2);
        }
    }

}

/**
 * 時間割を表示するためのクラスです。
 * 予めグローバル変数$SETTINGSにConfigクラスのインスタンスを用意しておく必要があります。
 */
class Timetable{

    /**
     * 作成したHTML
     * @var string
     */
    protected $html;

    /**
     * ヘッダーに表示する内容
     * @var string[5]
     */
    protected $topHead;

    /**
     * 横のヘッダーに表示する内容
     * @var string[3]
     */
    protected $sideHead;

    /**
     * 表示する情報
     * @var ClassInfo[][][]
     */
    protected $infos;

    /**
     * このクラスのコンストラクタです。
     */
    public function __construct(){
        global $SETTINGS;
        $this->topHead = explode(",", $SETTINGS->headOfTop);
        $this->sideHead = explode(",", $SETTINGS->headOfSide);
        $this->setClassInfo();
    }

    /**
     * 時間割を生成します。
     * @param  int $period 生成する範囲
     * @return string         生成したhtml
     */
    public function create($period){
        global $SETTINGS;
        $this->html = "";
        $this->html .= "<table class=\"".$SETTINGS->tableClassName."\">";
        if ($period == 0){
            $startOfWeek = TimetableCarbon::now()->addWeeks($period)
                                                 ->startOfWeek();
            $dayOfWeek = TimetableCarbon::now()->dayOfWeek;
            $this->createHead($dayOfWeek);
        } else {
            $this->createHead();
        }
        for ($i = 0; $i <= 2; $i++){
            $this->html .= "<tr>";
            for ($j = 0; $j <= 5; $j++){
                if ($j == 0){
                    $this->html .= "<th>";
                    $this->html .= $this->sideHead[$i];
                    $this->html .= "</th>";
                } else {
                    $this->html .= $this->infos[$period][$i][$j - 1]
                                        ->getHtml();
                }
            }
            $this->html .= "</tr>";
        }
        $this->html .= "</table>";
        return $this->html;
    }

    /**
     * 必要な情報を配列に格納します。
     */
    protected function setClassInfo(){
        global $SETTINGS;
        $start = explode(",", $SETTINGS->showPeriodWeek)[0];
        $end = explode(",", $SETTINGS->showPeriodWeek)[1];
        for ($i = $start; $i <= $end; $i++){
            $week = TimetableCarbon::now()
                                        ->addWeeks($i)
                                        ->startOfWeek();
            $this->infos[$i] = $this->setClassInfoOneWeek($week);
        }
    }

    /**
     * 一週間分のClassInfoを取得します。
     * @param Carbon\Carbon $date 週のどこかに位置する日付
     * @return ClassInfo[][] 一週間分の情報が格納された2次配列
     */
    protected function setClassInfoOneWeek($date){
        $date = $date->startOfWeek();
        for ($i = 0; $i <= 2; $i++){
            for ($j = 0; $j <= 4; $j++){
                $date = $date->startOfWeek();
                $subject = Subject::fetchSchedule($j + 1, $i + 1);
                $infos[$i][$j] = new ClassInfo($date->addDays($j),
                                             $j, $subject);
            }
        }
        return $infos;
    }

    /**
     * 表のヘッダーを作成します。
     */
    protected function createHead($dayOfWeek = null){
        global $SETTINGS;
        $html = "";
        $html .= "<thead>";
        $html .= "<tr>";
        for ($i = 0; $i <= 5; $i++){
            if ($dayOfWeek == $i){
                $html .= "<th class=\"".$SETTINGS->todayClassName."\">";
            } else {
                $html .= "<th>";
            }
            if ($i != 0){
                $html .= $this->topHead[$i - 1];
            }
            $html .= "</th>";
        }
        $html .= "</tr>";
        $html .= "</thead>";

        $this->html .= $html;
    }

    /**
     * 課題詳細一覧のヘッダーを生成します。
     */
    protected static function createContentListHead(){
        $html = "";
        $html .= "<thead>";
        $html .= "<tr>";
        $html .= "<th>";
        $html .= "曜日";
        $html .= "</th>";
        $html .= "<th>";
        $html .= "教科";
        $html .= "</th>";
        $html .= "<th>";
        $html .= "詳細";
        $html .= "</th>";
        $html .= "</tr>";
        $html .= "</thead>";

        return $html;
    }

    /**
     * 課題詳細一覧を生成します
     * @param  int $period 期間
     */
    public function createContentList($period){
        global $SETTINGS;
        $this->html = "";
        $html = "";
        $html .= "<table class=\"".$SETTINGS->tableClassName."\">";
        $html .= static::createContentListHead();
        $i = 0;
        $iMax = count($this->infos[$period]);
        $jMax = count($this->infos[$period][0]);
        for ($j = 0; $j < $jMax; $j++){
            for ($i = 0; $i < $iMax; $i++){
                if ($this->infos[$period][$i][$j]){
                    $html .= $this->infos[$period][$i][$j]->getContentHtml();
                }
            }
        }
        $html .= "</table>";
        return $html;
    }

    /**
     * 指定された期間の課題一覧を生成します。
     * @param  Carbon\Carbon $start 始まる日付
     * @param  Carbon\Carbon $end   終わる日付
     * @return string        表のhtml
     */
    public static function createTaskList($start, $end){
        $taskList = Task::fetchTaskList($start, $end);
        $html = "";
        $html .= "<table class=\"table table-bordered table-striped\">";
        $html .= "<thead><tr>";
        $html .= "<th>日付</th><th>教科</th><th>詳細</th><th>編集</th>";
        $html .= "</tr></thead><tbody>";
        $i = 0;
        while(!is_null($taskList[$i])){
            $html .= $taskList[$i]->getTaskEditRow();
            $i++;
        }
        $html .= "</tbody></table>";
        return $html;
    }

}

/**
 * 時間割に表示するマスの情報を保持するクラスです。
 * 予めグローバル変数$SETTINGSにConfigクラスのインスタンスを用意しておく必要があります。
 */
class ClassInfo{

    /**
     * 日付
     * @var Carbon\Carbon
     */
    protected $date;

    /**
     * コマ数
     * @var int
     */
    protected $classNum;

    /**
     * 教科
     * @var Subject
     */
    protected $subject;

    /**
     * 課題情報
     * @var Task
     */
    protected $task;

    /**
     * セルを出力するHTML
     * @var string
     */
    protected $html;

    /**
     * 課題詳細一覧の行を表示するHTML
     * @var string
     */
    protected $contentHtml;

    /**
     * このクラスのコンストラクタです。
     * @param Carbon\Carbon $date 日付
     * @param int $classNum 授業コマ目
     * @param Subject $subject 教科
     */
    public function __construct($date, $classNum, $subject){
        if (is_subclass_of($date, "Carbon\Carbon") !== true){
            throw new Exception("dateはCarbonまたはTimetableCarbonで指定してください。");
        }
        if (get_class($subject) !== "Subject"){
            throw new Exception("subjectはSubjectで指定してください。");
        }
        $this->date = $date;
        $this->classNum = $classNum;
        $this->subject = $subject;
        $this->html = "";
        $this->task = Task::fetchTask($date, $subject);
        $this->createHtml();
        $this->createContentHtml();
    }

    /**
     * ReadOnlyのアクセサです。
     * @param  string $name 変数名
     * @return anyType       変数の値
     */
    public function __get($name){
        switch ($name){
            case "date":
                return $this->date;
            case "classNum":
                return $this->classNum;
            case "subject":
                return $this->subject;
            case "task":
                return $this->task;
            default:
                throw new Exception("存在しない変数が呼び出されました。");
        }
    }

    /**
     * セルを出力するHTMLを生成します。
     */
    protected function createHtml(){
        global $SETTINGS;
        $html = "";
        if (!is_null($this->task) && $this->date->isToday()){
            $html .= "<td class=\"".$SETTINGS->todayClassName." ".
                    $SETTINGS->taskClassName."\">";
        }else if (!is_null($this->task)){
            $html .= "<td class=\"".$SETTINGS->taskClassName."\">";
        }else if ($this->date->isToday()){
            $html .= "<td class=\"".$SETTINGS->todayClassName."\">";
        } else {
            $html .= "<td>";
        }
        if (!is_null($this->task)){
            if (!is_null($this->task->getContent())){
                $html .= "<span class=\"glyphicon glyphicon-tag aria-hidden=\"true\"></span>";
            }
        }

        $html .= $this->subject->getShortName();

        $html .= "</td>";
        $this->html .= $html;
    }

    /**
     * 生成したHTMLを返します
     * @return string html
     */
    public function getHtml(){
        return $this->html;
    }

    /**
     * 課題詳細一覧表の行を生成します
     */
    protected function createContentHtml(){
        global $SETTINGS;
        $dayOfWeekName = ["日", "月", "火", "水", "木", "金", "土"];
        if (is_null($this->task)){
            $this->contentHtml = null;
            return;
        }
        if (!$this->task->getContent()){
            $this->contentHtml = null;
            return;
        }
        $html = "";
        if ($this->date->isToday()){
            $html .= "<tr class=\"".$SETTINGS->todayClassName."\">";
        } else {
            $html .= "<tr>";
        }
        $html .= "<td>";
        $html .= $dayOfWeekName[$this->date->dayOfWeek];
        $html .= "</td>";
        $html .= "<td>";
        $html .= $this->subject->getShortName();
        $html .= "</td>";
        $html .= "<td>";
        $html .= $this->task->getContent();
        $html .= "</td>";
        $html .= "</tr>";

        $this->contentHtml = $html;
    }

    /**
     * 課題詳細一覧の行を取得します。
     * @return string 行のhtml
     */
    public function getContentHtml(){
        return $this->contentHtml;
    }

    /**
     * 自身のインスタンスのtaskのContentが存在するかどうかを返します。
     * @return bool 存在していればtrueを返す
     */
    public function doesContentExist(){
        if (is_null($this->task)){
            return false;
        }
        if (is_null($this->task->getContent())){
            return false;
        }
        return true;
    }
}
