<?php
use Carbon\Carbon;
// echo Timetable::createTaskList(Carbon::parse("2015-5-1"),Carbon::parse("2015-5-31"));

$addMonth = is_null($_GET["month"]) ? 0 : $_GET["month"];
$start = Carbon::today()->addMonths($addMonth)->startOfMonth();
$end = Carbon::today()->addMonths($addMonth)->endOfMonth();
$myUrl = strstr($_SERVER["REQUEST_URI"], "?", true);
$get = $_GET;
$get["month"]++;
$nextUrl = $myUrl."?".http_build_query($get);
$get = $_GET;
$get["month"]--;
$previousUrl = $myUrl."?".http_build_query($get);
?>
<ul class="pager">
    <li class="previous"><a href="<?php echo $previousUrl; ?>">先月</a></li>
    <li class="next"><a href="<?php echo $nextUrl; ?>">次月</a></li>
</ul>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php
        echo $start->format("Y年n月j日～");
        echo $end->format("Y年n月j日");
        ?>
        <div class="modal fade" id="modalAddTask" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="addTask.php" method="post">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                <span>×</span>
                            </button>
                            課題の追加
                        </div>
                        <div class="modal-body">
                            <input type="text" name="grade"
                            value="1" class="hidden">
                            <div class="form-group">
                                <label for="dateInput">日付</label>
                                <input type="text"
                                class="form-control datepicker" name="date"
                                value="<?php
                                    echo Carbon::today()->format("Y/m/d");
                                ?>">
                                <script type="text/javascript">
                                $(".datepicker").datepicker({
                                    language: 'ja',
                                    format: "yyyy年mm月dd日",
                                    autoclose: true
                                });
                                </script>
                            </div>
                            <div class="form-group">
                                <label for="subjectInput">教科</label>
                                <select class="form-control" name="subject"
                                id="subjectInput">
                                    <?php
                                    $list = Subject::fetchSubjectsList(1);
                                    $i = 0;
                                    while(!is_null($list[$i])){
                                        echo "<option>".$list[$i]->getName()."</option>";
                                        $i++;
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="contentInput">詳細</label>
                                <input type="text" name="content" id="contentInput"
                                class="form-control"
                                placeholder="詳細がなければこのまま空欄にしてください。">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
                                追加
                            </button>
                            <button type="button" class="btn btn-default"
                            data-dismiss="modal">キャンセル</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <button type="button" class="btn btn-primary btn-sm pull-right btn-block"
        data-toggle="modal" data-target="#modalAddTask">課題の追加</button>
        <?php echo Timetable::createTaskList($start, $end); ?>
        <button type="button" class="btn btn-primary btn-sm pull-right btn-block"
        data-toggle="modal" data-target="#modalAddTask">課題の追加</button>
    </div>
    <div class="panel-footer">
        <?php
        echo $start->format("Y年n月j日～");
        echo $end->format("Y年n月j日");
        ?>
    </div>
</div>
<ul class="pager">
    <li class="previous"><a href="<?php echo $previousUrl; ?>">先月</a></li>
    <li class="next"><a href="<?php echo $nextUrl; ?>">次月</a></li>
</ul>
