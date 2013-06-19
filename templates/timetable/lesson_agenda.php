<?php
/**
 * Занятие в режиме "Ближайшие"
 * @author: MUlt1mate
 * Date: 20.03.13
 * Time: 23:13
 *
 * @var Lesson $lesson
 * @var bool $by_subgroups
 * @var bool $teacher_visible
 * @var bool $group_visible
 */
?>
<tr style="background:#<?= $lesson->subcolor ?>" class="agenda_lesson" lesson_id="<?= $lesson->id ?>">
    <td class="agenda_time">
        <?=$lesson->get_time_begin()?> - <?=$lesson->get_time_end()?>
    </td>
    <td class="agenda_lesson_name">
        <?echo $lesson->lesson;
        if ($by_subgroups && (0 < $lesson->subgroup))
            echo '-' . $lesson->subgroup;?>
    </td>
    <?if ($teacher_visible): ?>
        <td class="agenda_teacher">
            <?=$lesson->teacher?>
        </td>
    <?endif;
    if ($group_visible):?>
        <td class="agenda_group">
            <?=$lesson->grupflowname?>
        </td>
    <? endif;?>
    <td class="agenda_room">
        <?=$lesson->get_room()?>
    </td>
    <td class="agenda_type_lesson">
        <?=$lesson->typelesson?>
    </td>
</tr>