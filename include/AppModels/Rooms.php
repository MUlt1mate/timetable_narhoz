<?php
/**
 * Аудитории
 * @author: MUlt1mate
 * Date: 18.03.13
 * Time: 22:29
 *
 * @property int $codroom
 * @property int $codroomstate
 * @property int $codroomtype
 * @property int $numbuilding
 * @property int $placecount
 * @property string $number
 * @property string $roomtype
 * @property string $roomstate
 */

class Rooms extends ActiveRecord\Model
{
    const  STATE_READY = 1;
    const  STATE_NOT_READY = 2;
    const  STATE_RESERVED = 3;

    const TYPE_LESSON = 1;
    const TYPE_COMPUTER = 2;
    const TYPE_LAB = 3;

    const DB_ROOMS_BUSY = 'sh_shiftRoom';

    static $primary_key = 'codroom';

    /**
     * Псевдонимы для корпусов
     * @var array
     */
    static public $build_aliases = array(
        1 => 'I-',
        2 => 'II-',
        3 => '',
    );

    /**
     * Возвращает список всех доступных аудиторий с указателем доступности в выбранный момент времени
     * @param Shedule $shedule
     * @param int $group
     * @param int $flow
     * @param string $time_begin
     * @param string $time_end
     * @param int $weekday_id
     * @param int $week
     * @param int $subgroup
     * @param int $date_begin
     * @param int $date_end
     * @return array
     */
    static function get_busy($shedule, $group, $flow, $time_begin, $time_end, $weekday_id, $week, $subgroup, $date_begin, $date_end)
    {
        $group = (null == $group) ? 'null' : (int)$group;
        $flow = (null == $flow) ? 'null' : (int)$flow;
        $weekday_id = (null == $weekday_id) ? 'null' : (int)$weekday_id;
        $week = (null == $week) ? 'null' : (int)$week;
        $time_begin = (null == $time_begin) ? 'null' : DB::escape($time_begin);
        $time_end = (null == $time_end) ? 'null' : DB::escape($time_end);
        $subgroup = (0 < $subgroup) ? 1 : 0;
        $date_begin = (null == $date_begin) ? 'null' : TimeDate::ts_to_db($date_begin);
        $date_end = (null == $date_end) ? 'null' : TimeDate::ts_to_db($date_end);
        $sql = 'SELECT * FROM ' . self::DB_ROOMS_BUSY . ' (
        ' . (int)$shedule->year . ',
        ' . (int)$shedule->numterm . ',
        ' . $group . ',
        ' . $flow . ',
        ' . (int)$shedule->id . ',
        ' . $time_begin . ',
        ' . $time_end . ',
        ' . $weekday_id . ',
        ' . $week . ',
        ' . $subgroup . ',
        ' . $date_begin . ',
        ' . $date_end . ')';

        $query = self::query($sql);
        return $query->fetchAll();
    }
}