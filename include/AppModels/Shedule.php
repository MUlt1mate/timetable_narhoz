<?php
/**
 * Расписание
 * @author: MUlt1mate
 * Date: 15.03.13
 * Time: 9:43
 */

class Shedule extends ActiveRecord\Model
{
    static $table = 'shedules';
    static $primary_key = 'id';
    const SHEDULE_STATUS_READY = 1;

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