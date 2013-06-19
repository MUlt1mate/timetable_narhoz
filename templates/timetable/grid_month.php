<?php
/**
 * Расписание в режиме "Месяц"
 * @author: MUlt1mate
 * Date: 20.03.13
 * Time: 10:19
 *
 * @var int $days_count
 * @var int $week_count
 * @var int $month
 * @var array $days_name
 * @var string $begin_date
 * @var string $today_date
 * @var View $this
 */
?>
<table class="table table-bordered" style="table-layout:fixed;">
    <tr>
        <? for ($day = 1; $day <= $days_count; $day++): ?>
            <th>
                <div class="weekday"><?=$days_name[$day]?></div>
            </th>
        <? endfor;?>

    </tr>
    <? $current_date = $begin_date;
    for ($i = 1; $i <= $week_count; $i++): ?>
        <tr class="month_table_tr">
            <? for ($j = 1; $j <= $days_count; $j++):
                $day_label_style = ($month == date('m', $current_date)) ? 'label-info ' : '';
                ?>
                <td class="month_table_td" weekday_id="<?= $j ?>" <?
                    if (TimeDate::ts_to_screen($today_date) == TimeDate::ts_to_screen($current_date))
                        echo 'style="background: #ddf;"'
                    ?>>
                    <div class="month_day">
                        <div class="label <?= $day_label_style ?>month_label"><?=date('d.m', $current_date)?></div>
                        <?if (isset($grid[$j][$i]) && is_array($grid[$j][$i]))
                            foreach ($grid[$j][$i] as $lesson)
                                $this->screen('lesson_month', array(
                                    'lesson' => $lesson,
                                    'show_subgroups' => true,
                                ));
                        ?>
                    </div>
                </td>
                <?    $current_date += TimeDate::DAY_LEN;
            endfor;
            $current_date += TimeDate::DAY_LEN * (7 - $days_count);?>
        </tr>
    <? endfor;?>
</table>