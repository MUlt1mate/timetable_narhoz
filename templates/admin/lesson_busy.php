<?php
/**
 * @author: MUlt1mate
 * Date: 17.04.13
 * Time: 20:37
 */
switch ($lesson->type) {
    case 1:
        $left = 0;
        $back = 'FAA';
        break;
    case 2:
        $left = 33;
        $back = 'AFA';
        break;
    case 3:
        $left = 66;
        $back = 'AAF';
        break;
}
?>
<div id="ls'.$this->id.'" class="lesson" style="
    top: <?= $lesson->TimeOffset() ?>px;
    height:<?= (($lesson->duration) / 2 - 2) ?>px;
    background: #<?= $back ?>;
    left:<?= $left ?>%;">
    <div class="time">
        <?=$lesson->get_time_begin()?> <?=$lesson->get_time_end()?>
    </div>
</div>
