<?
$down_border_height = ceil(8) * 60;
$hour_bar_visible = true;
?>
<div class="content">
    <div>
        <table class="table table-bordered" style="table-layout:fixed;">
            <tr>
                <th style="width:50px;"></th>
                <? for ($day = 1; $day <= $days_count; $day++): ?>
                    <th>
                        <div class="weekday"><?=$days_name[$day]?></div>
                        <div class="weekdate"><?=$days_date[$day]?></div>
                    </th>
                <? endfor;?>
            </tr>
            <tr>
                <td style="padding:0;">
                    <div style="height:<?= $down_border_height ?>px; overflow:hidden;">
                        <?for ($hour = 8; $hour < 21; $hour++): ?>
                            <div class="hour"><?=$hour?>:00</div>
                        <? endfor;?>
                    </div>
                </td>
                <? for ($day = 1; $day <= $days_count; $day++):
                    if (isset($grid[$day]))
                        $day_lessons = $grid[$day];
                    else
                        $day_lessons = array();
                    $day_class = 'regular_day';
                    if ($today_date == $day)
                        $day_class = 'current_day';
                    ?>
                    <td class="<?= $day_class ?>">
                        <div class="day">
                            <?if ($hour_bar_visible && ($today_date == $day)): ?>
                                <div id="current_hour" style=" top:<?= (($current_hour - 8) * 60) ?>px;"></div>
                                <div id="current_minute"
                                     style=" top:<?= (($current_hour - 8) * 60 + $current_minutes) ?>px;"></div>
                            <? endif;
                            foreach ($day_lessons as $lesson) {
                                $this->screen('lesson_week', array(
                                    'lesson' => $lesson,
                                    'is_all_subgroup' => $is_all_subgroup,
                                    'teacher_visible' => $teacher_visible,
                                    'group_visible' => $group_visible,
                                ));
                            }
                            ?>
                        </div>
                    </td>
                <? endfor;?>
            </tr>
            <tr>
                <td></td>
                <? for ($day = 1; $day <= $days_count; $day++): ?>
                    <td>
                        <div class="weekday"><?=$days_name[$day]?></div>
                        <div class="weekdate"><?=$days_date[$day]?></div>
                    </td>
                <? endfor;?>
            </tr>
        </table>
    </div>
</div>