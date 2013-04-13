<?php
/**
 * @author: MUlt1mate
 * Date: 13.04.13
 * Time: 15:47
 */
?>
<input type="hidden" name="timetable_hours_value" value="<?= $hours ?>">
<table id="time_table_grid" class="table table-bordered table-condensed tablegrid">
    <tr>
        <th>Группа</th>
        <th>Преподаватель</th>
        <th>Предмет</th>
        <th>Тип</th>
        <th>Ауд.</th>
        <th>Д.Н.</th>
        <th>НД</th>
        <th>Время</th>
        <th>Начало</th>
        <th>Конец</th>
        <th>Часы</th>
        <th></th>
        <th></th>
    </tr>
    <? if (is_array($lessons))
        foreach ($lessons as $l) :?>
            <tr id="TimeTable<?= $l['id'] ?>" style="background-color: #<?= $l['subcolor'] ?>;">
                <td style="max-width:300px;">
                    <div style="height:19px;  overflow:hidden;">
                        <?=$l['grupflowname']?>
                    </div>
                </td>
                <td><?=$l['teacher']?></td>
                <td>
                    <div class="tt_lesson" title="<?= $l['lesson'] ?>">
                        <?=$l['lesson']?>
                    </div>
                </td>
                <td><?=$l['typelessonabbr']?></td>
                <td><?=$l['room']?></td>
                <td><?=$l['weekdayabbr']?></td>
                <td><?=$l['week']?></td>
                <td><?=$l['time_begin']?> - <?=$l['time_end']?></td>
                <td><?=$l['lesson_date_begin']?></td>
                <td><?=$l['lesson_date_end']?></td>
                <td><?=$l['hours']?></td>
                <td>
                    <a data-toggle="modal" href="#" href="">
                        <label class="icon-edit"></label>
                    </a>
                </td>
                <td>
                    <a data-toggle="modal" href="#DeleteModal">
                        <label class="icon-remove"></label>
                    </a>
                </td>
            </tr>
        <? endforeach;?>
</table>