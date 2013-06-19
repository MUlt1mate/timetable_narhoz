<?php
/**
 * @author: MUlt1mate
 * Date: 31.03.13
 * Time: 11:35
 *
 * @var View $this
 * @var array $teachers
 */
$title = 'Преподаватели';
$this->screen(View::A_HEADER, array('title' => $title));?>
    <h3><?=$title?></h3>
<?if (is_array($teachers))
    foreach ($teachers as $word => $teacher_word):?>
        <section id="<?= Text::translate($word) ?>"></section>

        <h4><?=$word?></h4>
        <ul class="inline">
            <?foreach ($teacher_word as $teacher):
                /**
                 * @var Teachers $teacher
                 */
                ?>
                <li style="width: 180px;">
                    <div>
                        <a href="/?action=timetable&teacher=<?= $teacher->id ?>" <?if ($teacher->count == 0) echo 'class="muted"';?>>
                            <?=$teacher->shortfio?>
                        </a>
                        <a href="/?action=teachers&id=<?= $teacher->id ?>" target="_blank">(<?=$teacher->count?>)</a>
                    </div>
                </li>
            <? endforeach;?>
        </ul>
    <? endforeach; ?>
<? $this->screen(View::A_FOOTER);