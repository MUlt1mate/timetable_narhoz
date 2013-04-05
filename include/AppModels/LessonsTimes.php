<?php
/**
 * @author: MUlt1mate
 * Date: 31.03.13
 * Time: 11:49
 */

class LessonsTimes extends ActiveRecord\Model
{
    static $table = 'LessonsTimes';
    static $primary_key = 'id';

    static public function add($time_begin, $time_end, $hours)
    {
        $duration = intval((substr($time_end, 0, 2) - substr($time_begin, 0, 2))) * 60 +
            intval((substr($time_end, 3, 2) - substr($time_begin, 3, 2)));
        if (0 < $duration) {
            $time = new LessonsTimes(array(
                'time_begin' => '1900-01-01 ' . $time_begin . ':00',
                'time_end' => '1900-01-01 ' . $time_end . ':00',
                'duration' => $duration,
                'hours' => $hours,
            ), false);
            //@todo выводит warning при добавлении
            return $time->save();
        }
        return false;
    }
}