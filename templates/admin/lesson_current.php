<?
/**
 * Занятие в режиме "Текущие"
 * @author: MUlt1mate
 * Date: 17.06.13
 * Time: 22:53
 *
 * @var Lesson $lesson
 */
?>
<div class="lesson_current">
    <div class="lesson_curr" lesson_id="<?= $lesson->id ?>" style="background:#<?= $lesson->subcolor ?>;"
         data-toggle="popover">
        <div class="roof">
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
            <a href="/?action=timetable&teacher=<?= $lesson->teacher_id ?>">
                <div class="teacher">
                    <?=$lesson->teacher?>
                </div>
            </a>
            <?if(0 < $lesson->group_id):?>
            <a href="/?action=timetable&group=<?= $lesson->group_id ?>">
                <?endif;?>
                <div class="group" title="<?= $lesson->GroupFlowPopup ?>">
                    <?=$lesson->GroupFlowName?>
                </div>
                <?if(0 < $lesson->group_id):?>
            </a>
        <?endif;?>
        </div>
        <a href="/?action=timetable&room=<?= $lesson->room_id ?>">
            <div class="lesson_room" style="background:#<?= $lesson->subcolor ?>;">
                <?=$lesson->get_room()?>
            </div>
        </a>
    </div>
</div>