<?php
/**
 * Расписание в режиме "Ближайшие"
 * @author: MUlt1mate
 * Date: 20.03.13
 * Time: 22:27
 */
$current_date = $begin_date;
?>
    <table class="table table-condensed table-bordered">
        <tr>
            <th class="agenda_time">Время</th>
            <th class="agenda_lesson_name">Предмет</th>
            <?if ($teacher_visible): ?>
                <th class="agenda_teacher">Преподаватель</th>
            <?endif;
            if ($group_visible):?>
                <th class="agenda_group">Группа</th>
            <? endif;?>
            <th class="agenda_room">Аудитория</th>
            <th class="agenda_type_lesson">Тип занятия</th>
        </tr>
    </table>
<? if (is_array($grid)):
    foreach ($days_date as $day_date):
        $year_day = TimeDate::get_year_day_by_ts($current_date);
        $week_day_name = TimeDate::$weekdays[TimeDate::get_weekday_by_ts($current_date)];
        if (isset($grid[$year_day])):?>
            <h4><?=$week_day_name?> — <?=$day_date?></h4>
            <table class="table table-condensed table-bordered">
                <?foreach ($grid[$year_day] as $lesson)
                    $this->screen('lesson_agenda', array(
                        'lesson' => $lesson,
                        'group_visible' => $group_visible,
                        'teacher_visible' => $teacher_visible,
                        'by_subgroups' => $is_all_subgroups,
                    ));?>
            </table>
        <?
        endif;
        $current_date += TimeDate::DAY_LEN;
    endforeach;
endif;