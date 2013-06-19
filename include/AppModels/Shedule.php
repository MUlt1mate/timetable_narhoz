<?php
/**
 * Расписание
 * @author: MUlt1mate
 * Date: 15.03.13
 * Time: 9:43
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property int $status
 * @property int $year
 * @property int $numterm
 * @property int $formstudy
 * @property int $weeknum
 * @property int $status_id
 * @property int $type_id
 * @property int $formstudy_id
 * @property string $date_begin
 * @property string $date_end
 */

class Shedule extends ActiveRecord\Model
{
    static $table = 'shedules';
    static $primary_key = 'id';

    public static function add($name, $type, $status, $formstudy, $year, $numterm, $date_begin, $date_end)
    {
        $shedule = new self(array(
            'name' => $name,
            'type' => $type,
            'status' => $status,
            'formstudy' => $formstudy,
            'year' => $year,
            'numterm' => $numterm,
            'date_begin' => $date_begin,
            'date_end' => $date_end,
            'weeknum' => TimeDate::get_weeknum_by_ts(
                TimeDate::db_to_ts($date_begin)),
        ), false);
        return @$shedule->save();
    }

    public function edit($name, $type, $status, $formstudy, $year, $numterm, $date_begin, $date_end)
    {
        $this->name = $name;
        $this->type = $type;
        $this->status = $status;
        $this->formstudy = $formstudy;
        $this->year = $year;
        $this->numterm = $numterm;
        $this->date_begin = $date_begin;
        $this->date_end = $date_end;
        $this->weeknum = TimeDate::get_weeknum_by_ts(
            TimeDate::db_to_ts($date_begin));
        $this->readonly(false);
        $this->save();
    }
}