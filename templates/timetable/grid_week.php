<?
/**
 * Расписание в режиме "Неделя"
 * @author: MUlt1mate
 * Date: 20.03.13
 * Time: 10:19
 *
 * @var View $this
 * @var int $current_hour
 * @var int $current_minutes
 * @var int $days_count
 * @var int $current_weekday
 * @var array $days_date
 * @var array $days_name
 * @var bool $is_all_subgroup
 * @var bool $teacher_visible
 * @var bool $group_visible
 */
if ($last_hour < 15)
    $last_hour = 15;
$down_border_height = ($last_hour - 8) * 60;
$hour_bar_visible = false;
if ($current_hour * 60 + $current_minutes < $last_hour)
    $hour_bar_visible = true;
?>
<div class="content">
    <div>
        <table class="table table-bordered" style="table-layout:fixed;">
            <tr>
                <th style="width:50px;"></th>
                <? for ($day = 1; $day <= $days_count; $day++): ?>
                    <th>
                        <div class="weekdate"><?=$days_date[$day]?></div>
                        <div class="weekday"><?=$days_name[$day]?></div>
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
                    if ($current_weekday == $day)
                        $day_class = 'current_day';
                    ?>
                    <td class="<?= $day_class ?>" weekday_id="<?= $day ?>">
                        <div class="day">
                            <?if ($current_weekday == $day): ?>
                                <div id="current_hour" style=" top:<?= (($current_hour - 8) * 60) ?>px;"></div>
                                <? if ($hour_bar_visible): ?>
                                    <div id="current_minute"
                                         style=" top:<?= (($current_hour - 8) * 60 + $current_minutes) ?>px;"></div>
                                <? endif;
                            endif;
                            foreach ($day_lessons as $lesson) {
                                $this->screen('lesson_week', array(
                                    'lesson' => $lesson,
                                    'is_all_subgroup' => $is_all_subgroup,
                                    'teacher_visible' => $teacher_visible,
                                    'group_visible' => $group_visible,
									'subgroup_count' => $subgroup_count,
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