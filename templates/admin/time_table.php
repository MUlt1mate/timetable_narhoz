<?php
/**
 * @author: MUlt1mate
 * Date: 13.04.13
 * Time: 15:47
 *
 * @var float $hours
 * @var array $lessons
 */
?>
<input type="hidden" id="timetable_hours_value" value="<?= $hours ?>">
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
            <tr style="background-color: #<?= $l['subcolor'] ?>;" id="lesson<?= $l['id'] ?>">
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
                <td class="time_table_row"
                    timegrid_id="<?= $l['id'] ?>"
                    group_flow_id="<?= $l['group_flow_id'] ?>"
                    is_flow="<?= $l['is_flow'] ?>"
                    teacher_id="<?= $l['teacher_id'] ?>"
                    lesson_id="<?= $l['lesson_id'] ?>"
                    room_id="<?= $l['room_id'] ?>"
                    lesson_type_id="<?= $l['typelessonid'] ?>"
                    weekday_id="<?= $l['weekday_id'] ?>"
                    week="<?= $l['week'] ?>"
                    time_id="<?= $l['time_id'] ?>"
                    date_begin="<?= $l['lesson_date_begin'] ?>"
                    date_end="<?= $l['lesson_date_end'] ?>"
                    subgroup="<?= $l['subgroup'] ?>"
                    >
                    <a data-toggle="modal" href="#" href="">
                        <label class="icon-edit"></label>
                    </a>
                </td>
                <td class="delete_prepare" timegrid_id="<?= $l['id'] ?>">
                    <a data-toggle="modal" href="#DeleteModal">
                        <label class="icon-remove"></label>
                    </a>
                </td>
            </tr>
        <? endforeach;?>
</table>