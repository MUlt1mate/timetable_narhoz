<?php
/**
 * @author: MUlt1mate
 * Date: 12.04.13
 * Time: 0:53
 */

foreach ($rooms as $r):
    if (Rooms::STATE_NOT_READY == $r->codroomstate) {
        $bg_color = '888';
    } else {
        switch ($r->codroomtype) {
            case Rooms::TYPE_LESSON:
                $bg_color = 'CFC';
                break;
            case Rooms::TYPE_COMPUTER:
                $bg_color = 'CCF';
                break;
            case Rooms::TYPE_LAB:
                $bg_color = 'FCC';
                break;
            default:
                $bg_color = 'FFF';
        }
    }

    $diff = $r->placecount - $students_count;

    if (0 < $diff) {
        $badge = '<span class="badge badge-success" style="position: absolute; top: 2px; right: 2px;">+';
    } elseif ($diff < 0) {
        $badge = '<span class="badge badge-important" style="position: absolute; top: 2px; right: 2px;">';
    } else {
        $badge = '<span class="badge badge-info" style="position: absolute; top: 2px; right: 2px;">';
    }
    ?>
    <div class="room_table" id="room_<?= $r->codroom ?>" style=" background-color: #<?= $bg_color ?>;">
        <strong><?=Rooms::$build_aliases[$r->numbuilding] . $r->number?></strong> (<?=$r->placecount?>)
        <?= $badge . $diff ?></span></br>
        <div>
            <small><?=$r->roomtype?></small>
        </div>
    </div>
<? endforeach;