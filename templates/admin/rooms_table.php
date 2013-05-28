<?php
/**
 * @author: MUlt1mate
 * Date: 12.04.13
 * Time: 0:53
 */

foreach ($rooms as $r):
    if (0 < $r['roombusy']) {
        $bg_color = '888';
    } else {
        switch ($r['codroomtype']) {
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

    if (0 < $r['difference']) {
        $badge = '<span class="badge badge-success" style="position: absolute; top: 2px; right: 2px;">+';
    } elseif ($r['difference'] < 0) {
        $badge = '<span class="badge badge-important" style="position: absolute; top: 2px; right: 2px;">';
    } else {
        $badge = '<span class="badge badge-info" style="position: absolute; top: 2px; right: 2px;">';
    }
    ?>
    <div class="room_table" number="<?= $r['codroom'] ?>" style=" background-color: #<?= $bg_color ?>;">
        <strong class="room_name"><?=Rooms::$build_aliases[$r['numbuilding']] . $r['number']?></strong>
        (<?=$r['placecount']?>)
        <?= $badge . $r['difference'] ?></span></br>
        <div>
            <small><?=$r['roomtype']?></small>
        </div>
    </div>
<? endforeach;