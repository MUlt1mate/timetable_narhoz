<?php

/**
 * Класс-модель для работы с расписаниями занятий
 * @author: MUlt1mate
 * Date: 16.03.13
 * Time: 11:45
 *
 * @property int $id уникальный ID занятия
 * @property int $group_id id группы
 * @property int $flow_id  id потока
 * @property string $grupflowname Название группы или потока
 * @property int $teacher_id id преподавателя
 * @property string $teacher Ф.И.О преподавателя
 * @property int $lesson_id id предмета
 * @property string $lesson название предмета
 * @property string $subcolor цвет занятия
 * @property int $typelessonid id типа занятия
 * @property string $typelesson название типа занятия
 * @property int $room_id id аудитории
 * @property string $room название аудитории
 * @property int $numbuilding id здания
 * @property int $weekday_id номер дня недели
 * @property string $weekday название дня недели
 * @property int $week повторение занятия по четности недель
 * @property int $subgroup подгруппа группы
 * @property int $time_id id времени
 * @property string $time_begin время начала занятия
 * @property string $time_end время окончания занятия
 * @property int $duration продолжительность занятия в минутах
 * @property string $lesson_date_begin дата начала занятия. может быть пустым, рекомендуется использовать date_begin
 * @property string $lesson_date_end дата окончания занятия. может быть пустым, рекомендуется использовать date_end
 * @property int $shedule_id id расписания
 * @property int $weeknum указатель чётности первой недели расписания
 * @property string $shedule_begin дата начала расписания
 * @property string $shedule_end дата окончания расписания
 * @property int $status статус расписания
 * @property string $typelessonabbr аббревиатура типа занятия
 * @property int $hours количество академических часов
 * @property string $weekdayabbr аббревиатура дня недели
 * @property int $codformstudy id формы обучения
 * @property int $shedule_type id типа расписания
 * @property int $codfaculty id факультета
 * @property int $course номер курса группы или потока
 * @property int $days количество дней между начальной и конечной датой занятия или расписания
 * @property string $date_begin дата начала занятия, либо расписания
 * @property string $date_end дата окончания занятия, либо расписания
 */
class Timetable extends ActiveRecord\Model
{
    public static $all_types = array('group', 'teacher', 'room', 'subgroup');
    public static $table = 'TimeGridView';
    public static $primary_key = 'id';
    const TIMEGRID_TABLE = 'TimeGrid';
    const TIMETABLE_VIEW = 'sh_SheduleView';
    const TIMETABLE_REMOVE = 'sh_SheduleDeleteView';
    const TIMETABLE_PARAM = 'sh_SheduleParam';
    const TIMETABLE_BUSY = 'sh_TimeGridBusyView';
    /**
     * Количество дней выводимых в режиме "Ближайшие"
     */
    const AGENDA_DAYS = 30;
    /**
     * Количество занятий выводимых в режиме "Ближайшие"
     */
    const AGENDA_LESSONS = 50;
    const LESSON_TYPE_TEACHER = 'teacher';
    const LESSON_TYPE_GROUP = 'group';
    const LESSON_TYPE_ROOM = 'room';
    /**
     * Поле в таблицы занятости. Указывает что строка не должна быть свзяана с группой
     */
    const BUSY_PARAMETER_NOT_GROUP = 1;
    /**
     * Поле в таблицы занятости. Указывает что строка должна быть свзяана с группой
     */
    const BUSY_PARAMETER_GROUP = 2;

    /**
     * @var bool true, если у группы несколько подгрупп
     */
    private $show_subgroups = false;
    /**
     * @var string Название расписание для отображения в заголовке
     */
    private $timetable_title;
    /**
     * @var bool true, если разрешено показывать расписание для аудитории
     */
    private $show_room = false;

    /**
     * Определение названия и заголовка расписания
     * @param array $types
     * @return bool
     */
    public function init($types)
    {
        try {
            if (isset($types['group'])) {
                $info = Group::get_info($types['group']);
                if (0 < $info->subgroup) {
                    $this->show_subgroups = true;
                }
                $this->timetable_title = 'Группа: ' . $info->namegrup;
                return (0 < $info->lessons_count);
            }
            if (isset($types['teacher'])) {
                $info = Teachers::find($types['teacher']);
                $this->timetable_title = $info->fio;
                return (0 < $info->count);
            }
            if (isset($types['room']) && $this->show_room) {
                $info = Rooms::find($types['room']);
                $this->timetable_title = 'Аудитория: ' . Rooms::$build_aliases[$info->numbuilding] . $info->number;
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }

    /**
     * Нужно ли отображать подгруппы
     * @return bool
     */
    public function getShowSubgroups()
    {
        return $this->show_subgroups;
    }

    /**
     * Возвращает заголовок для расписания
     * @return string
     */
    public function getTimetableTitle()
    {
        return $this->timetable_title;
    }

    /**
     * Возвращает массив с занятиями, либо массив с номерами занятий, у которых есть удаления
     * @param int $date_begin
     * @param int $date_end
     * @param array $parameters параметры расписания: группа, преподаватель и пр.
     * @param bool $remove возвращает номера удаленных занятий, если true
     * @param bool $all вывести все занятия (и старые, и черновики)
     * @return array|bool
     */
    public static function get_timetable($date_begin, $date_end, $parameters, $remove = false, $all = false)
    {
        $sql = ($remove) ? self::TIMETABLE_REMOVE : self::TIMETABLE_VIEW;
        if (!$remove && !$all) {
            $sql .= ' @status=' . (int)SheduleStatus::STATUS_PUBLIC . ', ';
        }
        $sql .= ' @begin=' . DB::escape(TimeDate::ts_to_db($date_begin)) . ' ,
        @end=' . DB::escape(TimeDate::ts_to_db($date_end));
        foreach (self::$all_types as $type) {
            if (isset($parameters[$type])) {
                $sql .= ', @' . $type . '=' . (int)$parameters[$type];
            }
        }
        $query = self::query($sql);
        $rows = $query->fetchAll();

        if (!is_array($rows)) {
            return false;
        }
        if (!$remove) {
            foreach ($rows as &$row) {//если расписание начинается с нечетной недели, меняем указатели недель местами
                if (($row['weeknum'] == 0) && ($row['week'] != 0)) {
                    $row['week'] = 3 - $row['week'];
                }
            }
            return $rows;
        }

        $remove_ids = array();
        if (!is_array($rows)) {
            return $remove_ids;
        }
        foreach ($rows as $row) {
            $remove_ids[$row['TimeGrid_id_remove']][] = $row['lesson_date'];
        }
        return $remove_ids;
    }

    /**
     * Получение расписания по указанным параметрам
     * @param Shedule $shedule
     * @param int $course
     * @param int $teacher
     * @param int $group
     * @param int $plan_work
     * @return array
     */
    public static function get_by_params($shedule, $course, $teacher, $group, $plan_work)
    {
        if ((null == $group) && (null == $teacher)) {
            return false;
        }
        $course = (null == $course) ? 'null' : (int)$course;
        $teacher = (null == $teacher) ? 'null' : (int)$teacher;
        $plan_work = (null == $plan_work) ? 'null' : (int)$plan_work;
        $group = (null == $group) ? 'null' : (int)$group;
        $sql = self::TIMETABLE_PARAM . '
        @shedule_id = ' . (int)$shedule->id . ',
		@CodPlanWork = ' . $plan_work . ',
		@Course = ' . $course . ',
		@CodPrep = ' . $teacher . ',
		@Grup = ' . $group;

        $query = self::query($sql);
        $result = $query->fetchAll();
        foreach ($result as &$row) {
            if ((null == $row['group_id'])) {
                $row['is_flow'] = '1';
                $row['group_flow_id'] = $row['flow_id'];
            } else {
                $row['is_flow'] = '0';
                $row['group_flow_id'] = $row['group_id'];
                if (0 < $row['subgroup']) {
                    $row['grupflowname'] = $row['grupflowname'] . '-' . $row['subgroup'];
                }
            }

            $weeks = ceil($row['days'] / 7);
            if ((1 == $weeks % 2) && (2 == $row['week'])) {
                $multiply = floor($weeks / 2);
            } elseif (0 < $row['week']) {
                $multiply = ceil($weeks / 2);
            } else {
                $multiply = $weeks;
            }
            $row['hours'] *= $multiply;
        }

        return $result;
    }

    /**
     * Группирует занятия для недельного режима представления
     * @param int $date_begin
     * @param array $timetable массив с занятиями
     * @param array $remove
     * @return array [grid,latest_time,days_count]
     */
    public static function build_week($date_begin, $timetable, $remove)
    {
        $latest_time = '08:00:00';
        $grid = array();
        foreach ($timetable as $row) {
            //если занятие не удалено
            if (!isset($remove[$row['id']])) {
                //добавляем занятие, если оно идёт по обоим неделям, либо совпадает по чётности
                if (($row['week'] == 0) || ($row['week'] == (TimeDate::odd_week(date('W', $date_begin)) + 1))) {
                    $grid[$row['weekday_id']][] = new Lesson($row);
                    if ($latest_time < $row['time_end']) {
                        $latest_time = $row['time_end'];
                    }
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
     * @param int $date_begin
     * @param array $timetable
     * @param array $remove
     * @param int $week_count количество недель в месяце
     * @return array [grid,days_count]
     */
    public static function build_month($date_begin, $timetable, $remove, $week_count)
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
                            if (TimeDate::db_to_ts($r) == $this_date) {
                                $removed = true;
                            }
                        }
                    }
                    if (!$removed) {
                        $grid[$row['weekday_id']][$k + 1][] = new Lesson($row);
                    }
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
     * @param int $date_begin
     * @param array $timetable
     * @param array $remove
     * @return array [grid]
     */
    public static function build_agenda($date_begin, $timetable, $remove)
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
    public static function build_export($timetable, $remove, $type, $title, $rewrite = false)
    {
        $for_group = (array_key_exists('group', $type)) ? true : false;
        if (array_key_exists('teacher', $type)) {
            $cal_name = 'teacher' . $type['teacher'];
        } elseif (array_key_exists('group', $type)) {
            $cal_name = 'group' . $type['group'];
            if (array_key_exists('subgroup', $type) && (0 < $type['subgroup'])) {
                $cal_name .= 's' . $type['subgroup'];
            }
        } else {
            throw(new Exception('type not define'));
        }

        return iCal_Generator::iCalGener(
            $timetable,
            $remove,
            $cal_name,
            $title,
            $for_group,
            $rewrite
        );
    }

    /**
     * Группирует занятия по дням недели
     * @param array $lessons
     * @return array
     */
    public static function build_all_by_weekdays($lessons)
    {
        $by_weekday = array();
        foreach ($lessons as $l) {
            switch ($l['week']) {
                case 1:
                    $l['weekday_name'] = 'Нижняя';
                    break;
                case 2:
                    $l['weekday_name'] = 'Верхняя';
                    break;
                default:
                    $l['weekday_name'] = '';
            }
            $by_weekday[$l['weekday_id']][] = $l;
        }

        return $by_weekday;
    }

    /**
     * Определяет плановое количество фактических часов занятий для расписания
     * @param array $lessons
     * @return int
     */
    public static function calculate_hours($lessons)
    {
        $hours = 0;
        foreach ($lessons as $l) {
            $hours += $l['hours'];
        }
        return $hours;
    }

    /**
     * Получает таблицу занятости для группы, преподавателя и аудитории
     * @param int $shedule_id
     * @param int $week
     * @param int $group
     * @param int $subgroup
     * @param int $is_flow
     * @param int $teacher
     * @param int $room
     * @param string $date_begin
     * @param string $date_end
     * @return array
     */
    public static function get_busytable($shedule_id, $week, $group, $subgroup, $is_flow, $teacher, $room, $date_begin, $date_end)
    {
        $week = (null == $week) ? 'null' : (int)$week;
        $group = (null == $group) ? 'null' : (int)$group;
        $subgroup = (null == $subgroup) ? 'null' : (int)$subgroup;
        $is_flow = (null == $is_flow) ? 'null' : (int)$is_flow;
        $teacher = (null == $teacher) ? 'null' : (int)$teacher;
        $room = (null == $room) ? 'null' : (int)$room;
        $date_begin = (null == $date_begin) ? 'null' : DB::escape(TimeDate::ts_to_db($date_begin));
        $date_end = (null == $date_end) ? 'null' : DB::escape(TimeDate::ts_to_db($date_end));
        $sql = self::TIMETABLE_BUSY . ' @shedule=' . (int)$shedule_id;
        $sql .= ' ,@week=' . $week;
        $sql .= ' ,@group=' . $group;
        $sql .= ' ,@subgroup=' . $subgroup;
        $sql .= ' ,@isFlow=' . $is_flow;
        $sql .= ' ,@teacher=' . $teacher;
        $sql .= ' ,@room=' . $room;
        $sql .= ' ,@begin=' . $date_begin;
        $sql .= ' ,@end=' . $date_end;
        $query = self::query($sql);
        $lessons = $query->fetchAll();

        $new_array = array();
        foreach ($lessons as $row) {
            if (($row['teacher_id'] == $teacher) && self::BUSY_PARAMETER_NOT_GROUP == $row['par']) {
                $row['busy_type'] = self::LESSON_TYPE_TEACHER;
                $new_array[$row['weekday_id']][] = new Lesson($row);
            }
            if (($row['room_id'] == $room) && self::BUSY_PARAMETER_NOT_GROUP == $row['par']) {
                $row['busy_type'] = self::LESSON_TYPE_ROOM;
                $new_array[$row['weekday_id']][] = new Lesson($row);
            }
            if (self::BUSY_PARAMETER_GROUP == $row['par']) {
                $row['busy_type'] = self::LESSON_TYPE_GROUP;
                $new_array[$row['weekday_id']][] = new Lesson($row);
            }
        }
        return $new_array;
    }

    /**
     * Добавляет новое занятие в расписание
     * @param int $shedule_id
     * @param int $group_id
     * @param int $flow_id
     * @param int $is_flow
     * @param int $subgroup
     * @param int $teacher_id
     * @param int $lesson_id
     * @param int $type_lesson_id
     * @param int $time_id
     * @param int $room_id
     * @param int $week
     * @param int $weekday_id
     * @param string $lesson_date_begin
     * @param string $lesson_date_end
     * @return PDOStatement
     */
    public static function add(
        $shedule_id,
        $group_id,
        $flow_id,
        $is_flow,
        $subgroup,
        $teacher_id,
        $lesson_id,
        $type_lesson_id,
        $time_id,
        $room_id,
        $week,
        $weekday_id,
        $lesson_date_begin,
        $lesson_date_end
    )
    {
        if (0 == $is_flow) {
            $flow_id = 'null';
            $group_id = (int)$group_id;
        } else {
            $group_id = 'null';
            $flow_id = (int)$flow_id;
            $subgroup = 0;
        }

        if (empty($lesson_date_begin)) {
            $lesson_date_begin = 'null';
        } else {
            $lesson_date_begin = DB::escape($lesson_date_begin);
        }
        if (empty($lesson_date_end)) {
            $lesson_date_end = 'null';
        } else {
            $lesson_date_end = DB::escape($lesson_date_end);
        }

        $sql = 'INSERT INTO ' . self::TIMEGRID_TABLE . '
           ([lesson_id]
           ,[group_id]
           ,[flow_id]
           ,[room_id]
           ,[teacher_id]
           ,[time_id]
           ,[week]
           ,[subgroup]
           ,[weekday_id]
           ,[shedule_id]
           ,[lesson_type]
           ,[lesson_date_begin]
           ,[lesson_date_end])
           VALUES (' .
            (int)$lesson_id . ',' .
            $group_id . ',' .
            $flow_id . ',' .
            (int)$room_id . ',' .
            (int)$teacher_id . ',' .
            (int)$time_id . ',' .
            (int)$week . ',' .
            (int)$subgroup . ',' .
            (int)$weekday_id . ',' .
            (int)$shedule_id . ',' .
            (int)$type_lesson_id . ',' .
            $lesson_date_begin . ',' .
            $lesson_date_end . ')';
        return self::query($sql);
    }

    /**
     * Редактирует занятие
     * @param int $shedule_id
     * @param int $group_id
     * @param int $flow_id
     * @param int $is_flow
     * @param int $subgroup
     * @param int $teacher_id
     * @param int $lesson_id
     * @param int $type_lesson_id
     * @param int $time_id
     * @param int $room_id
     * @param int $week
     * @param int $weekday_id
     * @param string $lesson_date_begin
     * @param string $lesson_date_end
     * @return PDOStatement
     */
    public function edit(
        $shedule_id,
        $group_id,
        $flow_id,
        $is_flow,
        $subgroup,
        $teacher_id,
        $lesson_id,
        $type_lesson_id,
        $time_id,
        $room_id,
        $week,
        $weekday_id,
        $lesson_date_begin,
        $lesson_date_end
    )
    {
        if (0 == $is_flow) {
            $flow_id = 'null';
            $group_id = (int)$group_id;
        } else {
            $group_id = 'null';
            $flow_id = (int)$flow_id;
            $subgroup = 0;
        }

        if (empty($lesson_date_begin)) {
            $lesson_date_begin = 'null';
        } else {
            $lesson_date_begin = DB::escape($lesson_date_begin);
        }
        if (empty($lesson_date_end)) {
            $lesson_date_end = 'null';
        } else {
            $lesson_date_end = DB::escape($lesson_date_end);
        }

        $sql = '
        UPDATE ' . self::TIMEGRID_TABLE . '
        SET [lesson_id]=' . (int)$lesson_id . '
        ,[group_id]  =' . $group_id . '
        ,[flow_id] =' . $flow_id . '
        ,[room_id] =' . (int)$room_id . '
        ,[teacher_id]=' . (int)$teacher_id . '
        ,[time_id]=' . (int)$time_id . '
        ,[week] =' . (int)$week . '
        ,[subgroup] =' . (int)$subgroup . '
        ,[weekday_id] =' . (int)$weekday_id . '
        ,[shedule_id] =' . (int)$shedule_id . '
        ,[lesson_type] =' . (int)$type_lesson_id . '
        ,[lesson_date_begin] =' . $lesson_date_begin . '
        ,[lesson_date_end] =' . $lesson_date_end . '
         WHERE id=' . (int)$this->id;
        return self::query($sql);
    }

    /**
     * Удаляет занятие
     * @return PDOStatement
     */
    public function delete()
    {
        $sql = 'DELETE FROM ' . self::TIMEGRID_TABLE . ' WHERE id=' . (int)$this->id;
        return self::query($sql);
    }

    /**
     * Возвращает занятие, которые идут в данный момент
     * @return array
     */
    public static function get_current()
    {
        $sql = 'declare @time datetime
        declare @dw int
        select @time=CONVERT(varchar,GETDATE(),108)
        select @dw=datepart(dw, getdate())-1
        SELECT * FROM ' . self::$table . ' WHERE GETDATE() BETWEEN date_begin and date_end
        AND @time BETWEEN time_begin and time_end
        AND weekday_id=@dw
        ORDER BY CodFaculty, course
        ';

        $query = self::query($sql);
        $result = $query->fetchAll();

        $grid = array();
        foreach ($result as $row) {
            if (($row['week'] == 0) || ($row['week'] == (TimeDate::odd_week(date('W')) + 1))) {
                $grid[$row['codfaculty']][$row['course']][] = new Lesson($row);
            }
        }
        return $grid;
    }

    /**
     * Возвращает объект класса
     * @param int $id
     * @return self
     */
    public static function get_by_id($id)
    {
        return self::find($id);
    }

    /**
     * Изменяет параметр для отображения аудиторий
     * @param $bool
     */
    public function show_rooms($bool)
    {
        $this->show_room = $bool;
    }
}
