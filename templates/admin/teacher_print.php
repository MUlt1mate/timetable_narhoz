<?php
/**
 * @author: MUlt1mate
 * Date: 06.04.13
 * Time: 0:37
 */
?>
<h4 style="margin:0 0 10px 20px;"><?=$teacher->fio?></h4>
<table border style="font-size:12px">
    <? foreach ($weekdays as $weekday_id => $weekday_name): ?>
        <tr>
            <td colspan="4">
                <h4 style="margin: 5px 0"><?=$weekday_name?></h4>
            </td>
        </tr>
        <? if (isset($lessons[$weekday_id]))
            foreach ($lessons[$weekday_id] as $l): ?>
                <tr style="height: 20px;">
                    <td style="height:20px;">
                        <?=substr($l['time_begin'], 0, 5)?>
                        - <?=substr(($l['time_end']), 0, 5)?>
                        <?=$l['weekday_name']?>
                    </td>
                    <td style="width:100px;">
                        <div style="height:20px;  overflow:hidden;">
                            <?=$l['grupflowname']?>
                        </div>
                    </td>
                    <td>
                        <div style="height:20px;  overflow:hidden;">
                            <?=$l['lesson']?>
                        </div>
                    </td>
                    <td>
                        <?=Rooms::$build_aliases[$l['numbuilding']] . $l['room']?>
                    </td>
                </tr>
            <? endforeach;
    endforeach;?>
</table>