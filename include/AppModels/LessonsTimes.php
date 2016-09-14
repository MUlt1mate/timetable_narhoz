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
    static $table = 'LessonsTimes';
    static $primary_key = 'id';

    /**
     * @var array Расписание звонков для субботы
     */
    static $ST_times = array(
        '0' => '08:00 09:20',
        '45' => '09:30 10:50',
        '90' => '11:00 12:20',
        '135' => '12:30 13:50',
        '180' => '14:00 15:20',
        '225' => '15:30 16:50',
        '270' => '17:00 18:20',
        '315' => '18:30 19:50'
    );

    /**
     * @var array расписание звонков для будних дней
     */
    static $MN_FR_times = array(
        '0' => '08:00 09:20',
        '45' => '09:30 10:50',
        '90' => '11:00 12:20',
        '150' => '13:00 14:20',
        '195' => '14:30 15:50',
        '240' => '16:00 17:20',
        '295' => '17:50 19:10',
        '340' => '19:20 20:40'
    );

    /**
     * Добавление времени для занятия
     * @param string $time_begin
     * @param string $time_end
     * @param int $hours
     * @return bool
     */
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
            return $time->save();
        }
        return false;
    }

    /**
     * Возвращает массив со всеми значениями времени начала занятия
     * @return array
     */
    static public function get_all_begin_times()
    {
        $params = array(
            'select' => 'id, time_begin',
            'group' => 'id, time_begin',
            'order' => 'time_begin',
        );
        return self::all($params);
    }
}