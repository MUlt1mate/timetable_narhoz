<?php
/**
 * Класс-модель для работы с расписаниями занятий
 * @author: MUlt1mate
 * Date: 16.03.13
 * Time: 11:45
 */

class Timetable extends ActiveRecord\Model
{
    static public $all_types = array('group', 'teacher', 'room', 'subgroup');
    const TIMETABLE_VIEW = 'sh_SheduleView';
    static $table = 'TimeGridView';
    static $primary_key = 'id';
    const TIMETABLE_REMOVE = 'sh_SheduleDeleteView';
    const AGENDA_DAYS = 30;
    const AGENDA_LESSONS = 50;

    private $show_subgroups = false;
    private $timetable_title;

    /**
     * Определение названия и заголовка расписания
     */
    public function init($types)
    {
        //@todo не выводить инфу, если нет пар
        if (isset($types['group'])) {
            $info = Group::get_info($types['group']);
            if (0 < $info->subgroup)
                $this->show_subgroups = true;
            $this->timetable_title = 'Группа: ' . $info->namegrup;
            return;
        }
        if (isset($types['teacher'])) {
            $info = Teachers::find($types['teacher']);
            $this->timetable_title = $info->fio;
            return;
        }
        if (isset($types['room'])) {
            $info = Rooms::find($types['room']);
            $this->timetable_title = 'Аудитория: ' . $info->number;
            return;
        }
    }

    public function getShowSubgroups()
    {
        return $this->show_subgroups;
    }

    public function getTimetableTitle()
    {
        return $this->timetable_title;
    }

    static private function  escape($string)
    {
        return self::connection()->escape($string);
    }

    /**
     * Возвращает массив с занятиями, либо массив с номерами занятий, у которых есть удаления
     * @param $date_begin
     * @param $date_end
     * @param $parameters параметры расписания: группа, преподаватель и пр.
     * @param bool $remove возвращает номера удаленных занятий, если true
     * @return array|bool
     */
    static public function get_timetable($date_begin, $date_end, $parameters, $remove = false)
    {
        $sql = ($remove) ? self::TIMETABLE_REMOVE : self::TIMETABLE_VIEW;
        if (!$remove)
            $sql .= ' @status=' . (int)Shedules::SHEDULES_STATUS_READY . ', ';
        $sql .= ' @begin=' . self::escape(TimeDate::ts_to_db($date_begin)) . ' ,
        @end=' . self::escape(TimeDate::ts_to_db($date_end));
        foreach (self::$all_types as $type)
            if (isset($parameters[$type]))
                $sql .= ', @' . $type . '=' . (int)$parameters[$type];
        $query = self::query($sql);
        $rows = $query->fetchAll();

        if (!is_array($rows))
            return false;
        if (!$remove) {
            foreach ($rows as &$row)
                //если расписание начинается с нечетной недели, меняем указатели недель местами
                if (($row['weeknum'] == 0) && ($row['week'] != 0)) {
                    $row['week'] = 3 - $row['week'];
                }
            return $rows;
        } else {
            $remove_ids = array();
            if (is_array($rows))
                foreach ($rows as $row)
                    $remove_ids[$row['TimeGrid_id_remove']][] = $row['lesson_date'];
            return $remove_ids;
        }
    }

    /**
     * Группирует занятия для недельного режима представления
     * @param $date_begin
     * @param $timetable массив с занятиями
     * @param $remove
     * @return array [grid,latest_time,days_count]
     */
    static public function build_week($date_begin, $timetable, $remove)
    {
        $latest_time = '08:00:00';
        $grid = array();
        foreach ($timetable as $row) {
            //если занятие не удалено
            if (!isset($remove[$row['id']])) {
                //добавляем занятие, если оно идёт по обоим неделям, либо совпадает по чётности
                if (($row['week'] == 0) || ($row['week'] == (TimeDate::odd_week(date('W', $date_begin)) + 1))) {
                    $grid[$row['weekday_id']][] = new Lesson($row);
                    if ($latest_time < $row['time_end'])
                        $latest_time = $row['time_end'];
                }
            }
        }
        $days_count = 7;
        if (!isset($grid[7])) {
            $days_count--;
            if (!isset($grid[6])) {
                $days_count--;
            }
        }

        return array(
            'grid' => $grid,
            'days_count' => $days_count,
            'latest_time' => $latest_time,
        );
    }

    /**
     * Группирует занятия для месячного режима представления
     * @param $date_begin
     * @param $timetable
     * @param $remove
     * @param $week_count количество недель в месяце
     * @return array [grid,days_count]
     */
    static public function build_month($date_begin, $timetable, $remove, $week_count)
    {
        $removed = false;
        $grid = array();
        foreach ($timetable as $row) {
            for ($k = 0; $k <= $week_count; $k++) {
                $this_date = $date_begin + TimeDate::WEEK_LEN * $k + TimeDate::DAY_LEN * ($row['weekday_id'] - 1);
                $lesson_date_begin = TimeDate::db_to_ts($row['date_begin']);
                $lesson_date_end = TimeDate::db_to_ts($row['date_end']);
                if (($lesson_date_begin <= $this_date) && ($this_date <= $lesson_date_end) &&
                    (($row['week'] == 0) || ($row['week'] == (3 - (TimeDate::odd_week($k) + 1))))
                ) {
                    if (isset($remove[$row['id']])) {
                        foreach ($remove[$row['id']] as $r) {
                            if (TimeDate::db_to_ts($r) == $this_date)
                                $removed = true;
                        }
                    }
                    if (!$removed)
                        $grid[$row['weekday_id']][$k + 1][] = new Lesson($row);
                    $removed = false;
                }
            }
        }

        $days_count = 7;
        if (!isset($grid[7])) {
            $days_count--;
            if (!isset($grid[6])) {
                $days_count--;
            }
        }
        return array(
            'grid' => $grid,
            'days_count' => $days_count,
        );
    }

    /**
     * Группирует занятия для режима представления ближайших занятий
     * @param $date_begin
     * @param $timetable
     * @param $remove
     * @return array [grid]
     */
    static public function build_agenda($date_begin, $timetable, $remove)
    {
        $lessons_count = 0;
        $days_count = 0;
        $today_date = $date_begin;
        $grid = array();
        do {
            $week_day = TimeDate::get_weekday_by_ts($today_date);
            $week_id = TimeDate::get_weeknum_by_ts($today_date);
            foreach ($timetable as $row) {
                $lesson_date_begin = TimeDate::db_to_ts($row['date_begin']);
                $lesson_date_end = TimeDate::db_to_ts($row['date_end']);
                if (($lesson_date_begin <= $today_date) && ($today_date <= $lesson_date_end) &&
                    ($row['weekday_id'] == $week_day) &&
                    (($row['week'] == 0) || ($row['week'] == (TimeDate::odd_week($week_id) + 1))) &&
                    (!isset($remove[$row['id']]))
                ) {
                    $grid[TimeDate::get_year_day_by_ts($today_date)][] = new Lesson($row);
                    $week_days[TimeDate::get_year_day_by_ts($today_date)] = $week_day;
                    ++$lessons_count;
                }
            }
            $today_date += TimeDate::DAY_LEN;
            ++$days_count;
        } while (($lessons_count <= self::AGENDA_LESSONS) && ($days_count <= self::AGENDA_DAYS));

        return array(
            'grid' => $grid,
        );
    }

    /**
     * Определяет имя файла экспорта и запускает экспорт
     * @param array $timetable
     * @param array $remove
     * @param array $type
     * @param string $title название расписания
     * @param bool $rewrite
     * @return bool
     * @throws Exception
     */
    static public function build_export($timetable, $remove, $type, $title, $rewrite = false)
    {
        $for_group = (array_key_exists('group', $type)) ? true : false;
        if (array_key_exists('teacher', $type)) {
            $cal_name = 'teacher' . $type['teacher'];
        } elseif (array_key_exists('group', $type)) {
            $cal_name = 'group' . $type['group'];
            if (array_key_exists('subgroup', $type) && (0 < $type['subgroup'])) {
                $cal_name .= 's' . $type['subgroup'];
            }
        } else
            throw(new Exception('type not define'));

        return iCal_Generator::iCalGener(
            $timetable,
            $remove,
            $cal_name,
            $title,
            $for_group,
            $rewrite
        );
    }

    static public function build_all_by_weekdays($lessons)
    {
        $by_weekday = array();
        foreach ($lessons as $l) {
            switch ($l['week']) {
                case 1:
                    $l['weekday_name'] = 'Верхняя';
                    break;
                case 2:
                    $l['weekday_name'] = 'Нижняя';
                    break;
                default:
                    $l['weekday_name'] = '';
            }
            $by_weekday[$l['weekday_id']][] = $l;
        }

        return $by_weekday;
    }
}