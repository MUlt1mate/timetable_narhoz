<?php
/**
 * @author: MUlt1mate
 * Date: 31.03.13
 * Time: 2:28
 */

class SheduleStatus extends ActiveRecord\Model
{
    static $table = 'SheduleStatus';
    const STATUS_PUBLIC = 1;
    const STATUS_EDIT = 2;
    const STATUS_RETIRED = 3;
}