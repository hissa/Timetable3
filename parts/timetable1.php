<?php
$SETTINGS = new Config("./config.ini", "./timetable1.ini");
$table = new Timetable(2);
?>
<ul class="nav nav-tabs">
    <?php
    $start = explode(",", $SETTINGS->showPeriodWeek)[0];
    $end = explode(",", $SETTINGS->showPeriodWeek)[1];
    for ($i = $start; $i <= $end; $i++){
        $date = null;
        $date = TimetableCarbon::now()->addWeeks($i);
        if ($i == 0){
            echo "<li class=\"active\"><a href=\"#table".$i."\" data-toggle=\"tab\">";
            echo "今週";
        } else {
            echo "<li><a href=\"#table".$i."\" data-toggle=\"tab\">";
            echo $date->format("n月j日～");
        }
        echo "</a></li>";
    }
    ?>
</ul>
<div class="tab-content">
    <?php
    for ($i = $start; $i <= $end; $i++){
        if ($i == 0){
            echo "<div class=\"tab-pane active\" id=\"table".$i."\">";
        } else {
            echo "<div class=\"tab-pane\" id=\"table".$i."\">";
        }
        echo "<p>";
        echo $table->create($i);
        echo "</p>";
        echo "<p>";
        echo $table->createContentList($i);
        echo "</p>";
        echo "</div>";
    }
    ?>
</div>
