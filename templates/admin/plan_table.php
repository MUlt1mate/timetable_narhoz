<?php
/**
 * @author: MUlt1mate
 * Date: 13.04.13
 * Time: 15:47
 */
?>
<input type="hidden" id="planwork_hours_value" value="<?= $hours ?>">
<table class="table table-bordered table-condensed" id="plan_table_grid">
    <tr>
        <th>Группа</th>
        <th>Преподаватель</th>
        <th>Предмет</th>
        <th>Занятие</th>
        <th>Часы</th>
    </tr>
    <? if (is_array($plans))
        foreach ($plans as $p) :?>
            <tr style="background-color: #<?= $p['subcolor'] ?>;" class="plan_table_row"
                group_flow_id="<?= $p['group_flow_id'] ?>"
                is_flow="<?= $p['is_flow'] ?>"
                teacher_id="<?= $p['codpe'] ?>"
                lesson_id="<?= $p['codsub'] ?>"
                lesson_type_id="<?= $p['codworktype'] ?>"
                subgroup="<?= $p['subgroup'] ?>">
                <td style="max-width:300px;">
                    <div style="height:19px;  overflow:hidden;">
                        <?= $p['grupflowname'] ?>
                    </div>
                </td>
                <td><?=$p['fio']?></td>
                <td><?=$p['namesub']?></td>
                <td><?=$p['worktype']?></td>
                <td><?=round($p['hours'], 2)?></td>
            </tr>
        <? endforeach;?>
</table>