<?php
/**
 * @author: MUlt1mate
 * Date: 16.06.13
 * Time: 23:44
 */

$list = array(
    'announce' => 'Ближайшие события',
    'times' => 'Время занятий',
    'lessons' => 'Предметы',
);
?>
<ul class="nav nav-tabs">
    <? foreach ($list as $l => $desc):
        $active = ($l == $_GET['action']) ? ' class="active"' : '';
        ?>
        <li<?=$active?>>
            <a href="/?action=<?= $l ?>"><?=$desc?></a>
        </li>
    <? endforeach;?>
</ul>