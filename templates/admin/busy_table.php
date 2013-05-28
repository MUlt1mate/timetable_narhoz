<?php
/**
 * @author: MUlt1mate
 * Date: 06.04.13
 * Time: 20:20
 */
$first_hour = 8;
$last_hour = 22;
?>
<table id="TableBusy" class="table table-bordered table-condensed" style="table-layout:fixed;">
    <tr>
        <th style="width:50px; padding: 0;"></th>
        <? if (is_array($days))
            foreach ($days as $d): ?>
                <th class="weekday">
                    <?=$d?>
                </th>
            <? endforeach;?>
    </tr>

    <tr>
        <td style="padding:0;">
            <div style="height:400px; overflow:hidden;">
                <?for ($i = $first_hour; $i < $last_hour; $i++): ?>
                    <div style="height: 19px; width:30px; padding: 5px 10px; border-bottom: 1px solid #DDD;">
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
            <td style="border-left: 1px solid #DDD; vertical-align: top; padding:0; background: #fff url(/img/halfhour.png) repeat;"
                weekday_id="<?= $i ?>">
                <div style="height: 100%; position: relative;">
                    <div id="new_lesson<?= $i ?>"
                         style="display:none; background:RGBA(50,50,50,0.3); width:95%; height:40px; top:90px; position:absolute;">
                    </div>


                    <? if (isset($lessons[$i]) && is_array($lessons[$i]))
                        foreach ($lessons[$i] as $l)
                            $this->screen('lesson_busy', array(
                                'lesson' => $l,
                            ));

                    for ($j = 1; $j <= 13; $j++):
                        $top = ($j - 1) * 30;?>
                        <div class="overhour" style="top:<?= $top ?>px;" duration="30" top="<?= $top ?>">
                            <?= ($j + 7)?>:00 <?= ($j + 8)?>:00
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