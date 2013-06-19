<?php
/**
 * @author: MUlt1mate
 * Date: 17.04.13
 * Time: 20:37
 *
 * @var Lesson $lesson
 */
switch ($lesson->busy_type) {
    case Timetable::LESSON_TYPE_GROUP:
        $left = 0;
        $color = 'FAA';
        break;
    case Timetable::LESSON_TYPE_TEACHER:
        $left = 33;
        $color = 'AFA';
        break;
    case Timetable::LESSON_TYPE_ROOM:
        $left = 66;
        $color = 'AAF';
        break;
}
?>
<div class="busy_lesson" style="
    top: <?= ($lesson->TimeOffset() / 2) ?>px;
    height:<?= (($lesson->duration) / 2 - 2) ?>px;
    background: #<?= $color ?>;
    left:<?= $left ?>%;">
    <div class="time">
        <?=$lesson->get_time_begin()?> <?=$lesson->get_time_end()?>
    </div>
</div>
