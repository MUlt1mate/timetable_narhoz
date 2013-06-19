<?php
/**
 * @author: MUlt1mate
 * Date: 18.06.13
 * Time: 0:43
 *
 * @var Lesson $lesson
 * @var bool $is_all_subgroup
 * @var bool $group_visible
 * @var bool $teacher_visible
 */
$width = 95;
$left = 0;
if ($is_all_subgroup) {
    switch ($lesson->subgroup) {
        case 1:
            $width = 45;
            $left = 0;
            break;
        case 2:
            $width = 45;
            $left = 50;
            break;
    }
}
switch ($lesson->status) {
    case SheduleStatus::STATUS_EDIT:
        $roof_class = 'roof_red';
        break;
    case SheduleStatus::STATUS_RETIRED:
        $roof_class = 'roof_green';
        break;
    case SheduleStatus::STATUS_PUBLIC:
    default:
        $roof_class = 'roof';
        break;
}
?>
<div class="lesson" lesson_id="<?= $lesson->id ?>" style="
    top: <?= $lesson->TimeOffset() ?>px;
    width:<?= $width ?>%;
    left:<?= $left ?>%;
    height:<?= ($lesson->duration - 2) ?>px;
    background:#<?= $lesson->subcolor ?>
    " onmouseover="FullWidth(this);" onmouseout="NormalWidth(this);"
     data-toggle="popover"
    >
    <div class="<?= $roof_class ?>">
        <div class="lesson_time">
            <?=$lesson->get_time_begin()?> - <?=$lesson->get_time_end()?>
        </div>
        <div class="lesson_type">
            <?=$lesson->typelessonabbr?>
        </div>
    </div>
    <div class="lesson_name">
        <?=$lesson->lesson?>
    </div>
    <div class="footer">
        <?if ($teacher_visible) : ?>
            <div class="teacher">
                <?=$lesson->teacher?>
            </div>
        <? endif;?>
        <?if ($group_visible) : ?>
            <div class="group">
                <a class="flow" rel="tooltip" title="<?= $lesson->GroupFlowPopup ?>">
                    <?=$lesson->GroupFlowName?>
                </a>
            </div>
        <? endif;?>
    </div>
    <div class="lesson_room" style="background:#<?= $lesson->subcolor ?>;">
        <?=$lesson->get_room()?>
    </div>
</div>