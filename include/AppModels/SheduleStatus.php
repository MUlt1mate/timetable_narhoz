<?php

/**
 * @author: MUlt1mate
 * Date: 31.03.13
 * Time: 2:28
 *
 * @property int $id
 * @property string $name
 */
class SheduleStatus extends ActiveRecord\Model
{
    public static $table = 'SheduleStatus';
    const STATUS_PUBLIC = 1;
    const STATUS_EDIT = 2;
    const STATUS_RETIRED = 3;
}
