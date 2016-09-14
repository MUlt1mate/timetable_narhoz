<?php
/**
 * Подробная информация о занятии
 * @author: MUlt1mate
 * Date: 24.03.13
 * Time: 13:48
 *
 * @var Lesson $lesson
 */
$group_title = ((0 < $lesson->flow_id)) ? 'Поток' : 'Группа';
$group_value = ((0 < $lesson->flow_id)) ? str_replace(';', ', ', $lesson->grupflowname) :
    '<a href="/?group=' . $lesson->group_id . '">' . $lesson->grupflowname . '</a>';
switch ($lesson->numbuilding) {
    case 1:
        $build = 'I-';
        break;
    case 2:
        $build = 'II-';
        break;
    default:
        $build = '';
}
$room = $build . $lesson->room;
switch ($lesson->week) {
    case 0:
        $interval = 'Каждую неделю';
        break;
    case 1:
        $interval = 'По верхней неделе';
        break;
    case 2:
        $interval = 'По нижней неделе';
        break;
}
?>
<table class="table table-condensed table-bordered">
    <tr>
        <td><?= $group_title ?></td>
        <td><?=$group_value  ?></td>
    </tr>
    <tr>
        <td>Преподаватель</td>
        <td><a href="/?teacher=<?= $lesson->teacher_id ?>"><?= $lesson->teacher ?></a></td>
    </tr>
    <tr>
        <td>Предмет</td>
        <td><?= $lesson->lesson ?></td>
    </tr>
    <tr>
        <td>Тип занятия</td>
        <td><?= $lesson->typelesson ?></td>
    </tr>
    <tr>
        <td>Аудитория</td>
        <td><?= $room ?></td>
    </tr>
    <tr>
        <td>Интервал дат</td>
        <td><?= TimeDate::db_to_screen($lesson->date_begin)?> - <?= TimeDate::db_to_screen($lesson->date_end)?></td>
    </tr>
    <tr>
        <td>Повторение</td>
        <td><?=$interval?></td>
    </tr>
    <tr>
        <td>День недели</td>
        <td><?=$lesson->weekday?></td>
    </tr>
</table>