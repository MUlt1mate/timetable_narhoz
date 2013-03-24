<?php
/**
 * @author: MUlt1mate
 * Date: 15.03.13
 * Time: 10:03
 */

class Peoplen extends ActiveRecord\Model
{
    static $connection = 'personal';
    static $table = 'PEOPLEN';
    static $primary_key = 'codpe';
}