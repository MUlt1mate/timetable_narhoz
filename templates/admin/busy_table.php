<?php
/**
 * @author: MUlt1mate
 * Date: 06.04.13
 * Time: 20:20
 *
 * @var View $this
 * @var array $days
 * @var array $sunday_times
 * @var array $saturday_times
 * @var array $work_days_times
 */
$first_hour = 8;
$last_hour = 22;
?>
<table id="TableBusy" class="table table-bordered table-condensed" style="table-layout:fixed;">
    <tr>
        <th style="width:50px; padding: 0;"></th>
        <? if (is_array($days))
            foreach ($days as $d): ?>
                <th class="weekday_name">
                    <?=$d?>
                </th>
            <? endforeach;?>
    </tr>

    <tr>
        <td style="padding:0;">
            <div id="busy_table_hours">
                <?for ($i = $first_hour; $i < $last_hour; $i++):
                    if ($i < 10)
                        $i = '0' . $i;?>
                    <div class="busy_table_one_hour">
                        <?=$i?>:00
                    </div>
                <? endfor;?>
            </div>
        </td>

        <?for ($i = 1; $i <= 7; $i++):
            switch ($i) {
                case 7:
                    $tops = $sunday_times;
                    break;
                case 6:
                    $tops = $saturday_times;
                    break;
                default:
                    $tops = $work_days_times;
                    break;
            }?>
            <td class="busy_table_day"
                weekday_id="<?= $i ?>">
                <div style="height: 100%; position: relative;">
                    <div id="new_lesson<?= $i ?>" class="busy_new_lesson" style="display: none">
                    </div>


                    <? if (isset($lessons[$i]) && is_array($lessons[$i]))
                        foreach ($lessons[$i] as $l)
                            $this->screen('lesson_busy', array(
                                'lesson' => $l,
                            ));

                    for ($j = 8; $j <= 20; $j++):
                        $top = ($j - 8) * 30;

                        if ($j < 10)
                            $j = '0' . $j;
                        $jj = $j + 1;
                        if ($jj < 10)
                            $jj = '0' . $jj;
                        ?>
                        <div class="overhour" style="top:<?= $top ?>px;" duration="30" top="<?= $top ?>">
                            <?= $j?>:00 <?= $jj?>:00
                        </div>
                    <? endfor;

                    foreach ($tops as $key => $t): ?>
                        <div class="overpair" style="top:<?= $key ?>px;" duration="40" top="<?= $key ?>">
                            <?=$t?>
                        </div>
                    <? endforeach;?>
                </div>
            </td>
        <? endfor;?>
</table>