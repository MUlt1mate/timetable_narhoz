<?php
/**
 * Аудитории
 * @author: MUlt1mate
 * Date: 18.03.13
 * Time: 22:29
 */

class Rooms extends ActiveRecord\Model
{
    const  STATE_READY = 1;
    const  STATE_NOT_READY = 2;
    const  STATE_RESERVED = 3;

    const TYPE_LESSON = 1;
    const TYPE_COMPUTER = 2;
    const TYPE_LAB = 3;

    static $primary_key = 'codroom';

    static public $build_aliases = array(
        1 => 'I-',
        2 => 'II-',
        3 => '',
    );
}