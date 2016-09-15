<?php

/**
 * @author: MUlt1mate
 * Date: 31.03.13
 * Time: 11:49
 *
 * @property string time_begin
 * @property string time_end
 * @property int duration
 * @property int hours
 */
class LessonsTimes extends ActiveRecord\Model
{
    public static $table = 'LessonsTimes';
    public static $primary_key = 'id';

    /**
     * @var array Расписание звонков для субботы
     */
    public static $ST_times = array(
        '0' => '08:00 09:25',
        '45' => '09:35 11:00',
        '90' => '11:10 12:35',
        '150' => '12:45 14:10',
        '195' => '14:20 15:45',
        '240' => '15:55 17:20',
        '295' => '17:30 18:55',
        '340' => '19:05 20:30'
    );

    /**
     * @var array расписание звонков для будних дней
     */
    public static $MN_FR_times = array(
        '0' => '08:00 09:25',
        '45' => '09:35 11:00',
        '90' => '11:10 12:35',
        '150' => '13:15 14:40',
        '195' => '14:50 16:15',
        '240' => '16:25 17:50',
        '295' => '18:00 19:25',
        '340' => '19:35 21:00'
    );

    /**
     * Добавление времени для занятия
     * @param string $time_begin
     * @param string $time_end
     * @param int $hours
     * @return bool
     */
    public static function add($time_begin, $time_end, $hours)
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
            return $time->save();
        }
        return false;
    }

    /**
     * Возвращает массив со всеми значениями времени начала занятия
     * @return array
     */
    public static function get_all_begin_times()
    {
        $params = array(
            'select' => 'id, time_begin',
            'group' => 'id, time_begin',
            'order' => 'time_begin',
        );
        return self::all($params);
    }
}
