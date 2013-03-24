<?php
/**
 * @author: MUlt1mate
 * Date: 20.03.13
 * Time: 11:07
 */
?>
<div class="month_lesson" style="background:#<?= $lesson->subcolor ?>;" lesson_id="<?= $lesson->id ?>">
    <div style="display:inline;">
        <?=substr($lesson->time_begin, 0, 5)?>
    </div>
    <div style="display:inline;">
        <?if ($show_subgroups && (0 < $lesson->subgroup)): ?>
            (<?= $lesson->subgroup ?>)
        <? endif;?>
        <?=$lesson->lesson; ?>
    </div>
</div>