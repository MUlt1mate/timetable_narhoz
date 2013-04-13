<?php
/**
 * Расписания
 * @author: MUlt1mate
 * Date: 15.03.13
 * Time: 9:43
 */

class Shedules extends ActiveRecord\Model
{
    static $table = 'shedules';
    static $primary_key = 'id';
    const SHEDULES_STATUS_READY = 1;
}