<?php
/**
 * @author: MUlt1mate
 * Date: 31.03.13
 * Time: 11:36
 */
$title = 'Аудитории';
$this->screen(self::A_HEADER, array('title' => $title));?>
    <h3><?=$title?></h3>
<? foreach ($rooms as $r):
    switch ($r->numbuilding) {
        case 1:
            $build = 'I-';
            break;
        case 2:
            $build = 'II-';
            break;
        default:
            $build = '';
            break;
    }

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
    ?>
    <div class="room" id="<?= $r->codroom ?>" style=" background-color: #<?= $bg_color ?>;">
        <strong><?=$build . $r->number?></strong> (<?=$r->placecount?>)
        <div>
            <small><?=$r->roomtype?></small>
        </div>
        <div>
            <small><?=$r->roomstate?></small>
        </div>
    </div>
<? endforeach; ?>
<? $this->screen(self::A_FOOTER);