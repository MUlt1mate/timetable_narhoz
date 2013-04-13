<?php
/**
 * @author: MUlt1mate
 * Date: 13.04.13
 * Time: 18:14
 */
?>
    <option></option>
<?
if (is_array($list))
    foreach ($list as $l):?>
        <option value="<?= $l['value'] ?>" <?if ($select == $l['value']) echo 'selected';?>><?=$l['name']?></option>
    <? endforeach;