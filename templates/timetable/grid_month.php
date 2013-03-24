<?php
/**
 * @author: MUlt1mate
 * Date: 20.03.13
 * Time: 10:19
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
                <td style="padding:0px; <?
                //                if ($current_day) echo 'background:#ddf;';
                ?>">
                    <div class="month_day">
                        <div class="label <?= $day_label_style ?>month_label"><?=date('d', $current_date)?></div>
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