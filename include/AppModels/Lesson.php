<?php
/**
 * Класс-модель занятия
 * @author: MUlt1mate
 * Date: 17.03.13
 * Time: 21:14
 */

class Lesson
{
//поля доступные через __get()
//[id] => уникальный ID занятия
//[group_id] => id группы
//[flow_id] => id потока
//[grupflowname] => Название группы или потока
//[teacher_id] => id преподавателя
//[teacher] => Ф.И.О преподавателя
//[lesson_id] => id предмета
//[lesson] => название предмета
//[subcolor] => цвет занятия
//[typelessonid] => id типа занятия
//[typelesson] => название типа занятия
//[room_id] => id аудитории
//[room] => название аудитории
//[numbuilding] => id здания
//[weekday_id] => номер дня недели
//[weekday] => название дня недели
//[week] => повторение занятия по четности недель
//[subgroup] => подгруппа группы
//[time_id] => id времени
//[time_begin] => время начала занятия
//[time_end] => время окончания занятия
//[duration] => продолжительность занятия в минутах
//[lesson_date_begin] => дата начала занятия. может быть пустым, рекомендуется использовать date_begin
//[lesson_date_end] => дата окончания занятия. может быть пустым, рекомендуется использовать date_end
//[shedule_id] => id расписания
//[weeknum] => указатель чётности первой недели расписания
//[shedule_begin] => дата начала расписания
//[shedule_end] => дата окончания расписания
//[status] => статус расписания
//[typelessonabbr] => аббревиатура типа занятия
//[hours] => количество академических часов
//[weekdayabbr] => аббревиатура дня недели
//[codformstudy] => id формы обучения
//[shedule_type] => id типа расписания
//[codfaculty] => id факультета
//[course] => номер курса группы или потока
//[days] => количество дней между начальной и конечной датой занятия или расписания
//[date_begin] => дата начала занятия, либо расписания
//[date_end] => дата окончания занятия, либо расписания
    private $row = array();

    public function __construct($row)
    {
        $this->row = $row;
    }

    public function __get($name)
    {
        if ('GroupFlowPopup' == $name) {
            return str_replace(';', ' ', $this->row['grupflowname']);
        }
        if ('GroupFlowName' == $name) {
            if ($this->row['group_id'] == '') {
                return 'Поток';
            } else {
                $sub = '';
                if (0 < $this->subgroup) {
                    $sub = '-' . $this->subgroup;
                }
                return $this->row['grupflowname'] . $sub;
            }
        }
        return $this->row[strtolower($name)];
    }

    /**
     * Определяет количество минут, которое прошло от начала дня (08:00) до начала текущего занятия
     * @return int
     */
    public function TimeOffset()
    {
        $pos = strpos($this->time_begin, ':');
        $h = ((int)substr($this->time_begin, 0, $pos) - 8) * 60;
        $m = (int)substr($this->time_begin, $pos + 1, 2);
        return $h + $m;
    }

    /**
     * Возвращает время начала занятия в формате часы:минуты
     * @return string
     */
    public function get_time_begin()
    {
        return substr($this->time_begin, 0, 5);
    }

    /**
     * Возвращает время окончания занятия в формате часы:минуты
     * @return string
     */
    public function get_time_end()
    {
        return substr($this->time_end, 0, 5);
    }

    /**
     * Возвращает название аудитории с приставкой номера корпуса
     * @return string
     */
    public function get_room()
    {
        switch ($this->NumBuilding) {
            case 1:
                $build = 'I-';
                break;
            case 2:
                $build = 'II-';
                break;
            default:
                $build = '';
        }
        return $build . $this->room;
    }

    /**
     * Формирует массив даты для iCal
     * @param string|bool $date текстовое представление даты
     * @return array
     */
    public function iCalDateBegin($date = false)
    {
        if (false == $date)
            $date = $this->iCalLessonDateBegin();
        $arr_date = getdate(TimeDate::db_to_ts($date, $this->time_begin));
        return array(
            "year" => $arr_date['year'],
            "month" => $arr_date['mon'],
            "day" => $arr_date['mday'],
            "hour" => $arr_date['hours'],
            "min" => $arr_date['minutes'],
            "sec" => 0,
            "tz" => TimeDate::TIMEZONE,
        );
    }

    /**
     * Возвращает дату, в которую произойдет первое фактическое проведенние данного занятия в текущем расписании
     * @return string
     */
    private function iCalLessonDateBegin()
    {
        $begin_date = TimeDate::db_to_ts($this->date_begin);
        //день недели даты начала занятия
        $lesson_start_week_day = TimeDate::get_weekday_by_ts($begin_date);
        if ($this->weekday_id < $lesson_start_week_day) {
            $days_shift = 7 - ($lesson_start_week_day - $this->weekday_id);
        } else
            $days_shift = $this->weekday_id - $lesson_start_week_day;
        // дата начала + смещение до дня недели
        $begin_date += $days_shift * TimeDate::DAY_LEN;

        //если первого занятия не было в первую учебную неделю, то прибавляем еще неделю к началу
        if ((($this->week == 2) && ($lesson_start_week_day <= $this->weekday_id)) ||
            (($this->week == 1) && ($this->weekday_id < $lesson_start_week_day))
        )
            $begin_date += TimeDate::WEEK_LEN;
        return TimeDate::ts_to_db($begin_date);
    }

    /**
     * форматирует дату для iCal
     * @param $db_date
     * @return string
     */
    public function iCalDate($db_date)
    {
        $date = TimeDate::db_to_ts($db_date);
        return date('Ymd', $date);
    }

    /**
     * Интервал повторения занятия по неделям для iCal
     * @return int
     */
    public function iCalInterval()
    {
        return (0 < $this->week) ? 2 : 1;
    }

    /**
     * идентификатор дня недели для iCal
     * @return array
     */
    public function iCalByDay()
    {
        switch ($this->weekday_id) {
            case 1:
                $WD = 'MO';
                break;
            case 2:
                $WD = 'TU';
                break;
            case 3:
                $WD = 'WE';
                break;
            case 4:
                $WD = 'TH';
                break;
            case 5:
                $WD = 'FR';
                break;
            case 6:
                $WD = 'SA';
                break;
            case 7:
                $WD = 'SU';
                break;
        }
        return array("DAY" => $WD);
    }

    /**
     * название занятия для iCal
     * @param $for_group
     * @return string
     */
    public function iCalSummary($for_group)
    {
        $subgroup = (0 < $this->subgroup) ? '-' . $this->subgroup : '';
        $summary = $this->lesson;
        //если расписание не для группы, то добавляем название группы к названию
        if (!$for_group)
            $summary .= ' ' . $this->GrupFlowName;
        $summary .= $subgroup;
        return $summary;
    }

    /**
     * уникальный идентификатор занятия для iCal
     * @return string
     */
    public function iCalUID()
    {
        return $this->iCalDate($this->iCalLessonDateBegin()) . 'T000000YAKT-0000' . substr(md5($this->id), 0, 8) . '@narhoz_timetable';
    }
}