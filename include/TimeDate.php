<?php
/**
 * Класс для работы с датами
 * @author: MUlt1mate
 * Date: 20.01.13
 * Time: 12:18
 */
class TimeDate
{
    static public $weekdays = array(
        1 => "Понедельник",
        2 => "Вторник",
        3 => "Среда",
        4 => "Четверг",
        5 => "Пятница",
        6 => "Суббота",
        7 => "Воскресенье",
    );

    const TIMEZONE = 'Asia/Yakutsk';
    /**
     * Количество секунд в сутках
     */
    const DAY_LEN = 86400;
    /**
     *  Количество секунд в неделе
     */
    const WEEK_LEN = 604800;

    /**
     * @var int Номер недели в году
     */
    private $week_id;
    /**
     * @var int номер месяца в году
     */
    private $month_id;
    /**
     * @var int штамп начала года
     */
    private $year_begin;
    /**
     * @var количество недель в промежутке между начальной и конечной датой
     */
    private $week_count;
    /**
     * @var штамп начальной даты
     */
    private $date_begin;
    /**
     * @var штамп конечной даты
     */
    private $date_end;
    /**
     * @var int корректировка номера недели в начале года
     */
    private $week_correct_begin = 0;
    /**
     * @var int корректировка номера недели в конце года
     */
    private $week_correct_end = 0;

    function __construct($mode = 'week')
    {
        $this->current_date = $this->define_date($mode);
        $this->month_id = date('m', $this->current_date);
        $this->week_id = date('W', $this->current_date);
        //Если год начинается с 52-ой или 53-ей недели, то меняем на 0
        if (($this->month_id == 1) && (51 < $this->week_id)) {
            $this->week_id = 0;
        }
        if (($this->month_id == 12) && (1 == $this->week_id)) {
            $this->month_id = 1;
        }
        $this->year_begin = $this->get_year_begin($this->get_year($this->month_id));

        // Если 1 января относится к 1-ой неделе, то в $week получается одна "лишняя" неделя
        if (gmdate("W", $this->year_begin) == "01") {
            $this->week_correct_begin = 1;
            if ('month' == $mode)
                $this->week_id--;
        }

        $this->define_begin_end($mode);
    }

    /**
     * штамп времени 1 января, 00:00:00 для указанного года
     * @param int $year
     * @return int
     */
    private function get_year_begin($year)
    {
        $Jan1 = gmmktime(0, 0, 0, 1, 1, $year);
        $Jan1WeekdayNum = gmdate("w", $Jan1);
        //Если 0, значит воскресенье
        $Jan1WeekdayNum = ($Jan1WeekdayNum == 0) ? 7 : $Jan1WeekdayNum;
        // Определим дату начала недели, к которой относится 1 января
        return $Jan1 - ($Jan1WeekdayNum - 1) * self::DAY_LEN;
    }

    /**
     * определение текущей даты относительно заданных номера недели или месяца
     * @param $mode режим представления расписания
     * @return int
     */
    private function define_date($mode)
    {
        $year = date('Y');
        switch ($mode) {
            case 'week':
                $week = date('W');
                if (isset($_GET['week'])) {
                    $shift = (date('W') - $_GET['week']);
                } else {
                    $shift = 0;
                }
                $year_begin = $this->get_year_begin($year);
                return $year_begin + ($week - 1 - $shift) * self::WEEK_LEN;
                break;
            case 'month':
                if (isset($_GET['month'])) {
                    if (($_GET['month'] < 1)OR(12 < $_GET['month'])) {
                        $year += floor($_GET['month'] / 12);
                        if (0 == $_GET['month'])
                            --$year;
                        if ($_GET['month'] < 1) {
                            $month = 12 - (abs($_GET['month']) % 12);
                        } else
                            $month = $_GET['month'] % 12;
                    } else {
                        $month = $_GET['month'];
                    }
                } else {
                    $month = date('m');
                }
                return mktime(0, 0, 0, $month, 1, $year);
                break;
        }
        return time();
    }

    /**
     * опредяляет дату начала периода
     * @param $mode
     */
    public function define_begin_end($mode)
    {
        switch ($mode) {
            case 'month':
                $start = $this->year_begin + $this->week_id * self::WEEK_LEN;
                //последняя неделя месяца
                $last_week = date('W', mktime(0, 0, 0, $this->month_id + 1, 1, $this->get_year($this->month_id)) - self::DAY_LEN);
                //Если вылезли в следующий год.
                if ($last_week == '01') {
                    //уменьшаем на одну неделю
                    $last_week = date('W', mktime(0, 0, 0, $this->month_id + 1, 1, $this->get_year($this->month_id)) - self::DAY_LEN - self::WEEK_LEN);
                    $this->week_correct_end = 1;
                }
                $finish = $this->year_begin + ($last_week + $this->week_correct_end - $this->week_correct_begin + 1) * self::WEEK_LEN - self::DAY_LEN;
                $this->week_count = $last_week + $this->week_correct_end - $this->week_correct_begin - $this->week_id + 1;
                break;
            case 'agenda':
                $start = mktime(0, 0, 0, date('m'), date('d'), date('y'));
                $finish = $start + (Timetable::AGENDA_DAYS * self::DAY_LEN);
                break;
            case 'week':
                $start = $this->year_begin + ($this->week_id - $this->week_correct_begin) * self::WEEK_LEN;
                $finish = $start + self::WEEK_LEN - self::DAY_LEN;
                $this->week_count = 1;
                break;
        }
        $this->date_begin = $start;
        $this->date_end = $finish;
    }

    /**
     * Вывод текстовых данных для отладки
     * @return string
     */
    public function __toString()
    {
        return '
        Время: ' . date('H:i:s') . '
        Текущая дата: ' . date('d.m.Y', $this->current_date) . '
        Неделя: ' . $this->week_id . '
        Месяц: ' . $this->month_id . '
        Начало периода: ' . date('d.m.Y', $this->date_begin) . '
        Конец периода: ' . date('d.m.Y', $this->date_end) . '
        Количество недель: ' . $this->week_count . '
        Коррекция недели в начале года: ' . $this->week_correct_begin . '
        Коррекция недели в конце года: ' . $this->week_correct_end . '
        Год: ' . $this->get_year($this->month_id) . '
        Учебный год: ' . $this->get_study_year() . '
        Начало года: ' . date('d.m.Y', $this->year_begin) . '
        Четность недели: ' . $this->odd_week($this->week_id);
    }

    /**
     * Получение массива с датами для каждого дня между начальной и конечной датой
     * @return array
     */
    public function get_dates()
    {
        $dates = array();
        $day = $this->date_begin;
        $i = 1;
        while ($day <= $this->date_end) {
            $dates[$i] = date('d.m.y', $day);
            $day += self::DAY_LEN;
            ++$i;
        }
        return $dates;
    }

    /**
     * Возвращает номер дня недели для текущего времени
     * @return int
     */
    public function get_today_weekday_id()
    {
        $day = date('w');
        if ($day == 0)
            $day = 7;
        return $day;
    }

    /**
     * Возвращает количество часов, прошедшее с начала суток текущего времени
     * @return string
     */
    static public function get_hour()
    {
        return date('H');
    }

    /**
     * Возвращает количество минут прошедшее с начала часа текущего времени
     * @return string
     */
    static public function get_minutes()
    {
        return date('i');
    }

    /**
     * Определяет четность номера недели
     * @param null $week
     * @return int
     */
    static function odd_week($week)
    {
        return $week % 2;
    }

    /**
     * Возвращает количество недель между начальной и конечной датой
     * @return
     */
    public function get_week_count()
    {
        return $this->week_count;
    }

    /**
     * Возвращает номер года
     * @param int $month_id номер месяца. необходим, чтобы избежать ошибки,
     * когда в начале первой недели есть несколько дней предыдущего года
     * @return int
     */
    function get_year($month_id)
    {
        $year_correct = 0;
        if (($month_id == 1) && (date('m', $this->current_date) == 12))
            $year_correct = 1;
        return date('Y', $this->current_date) + $year_correct;
    }

    /**
     * Возвращает номер учебного года
     * @return int
     */
    function get_study_year()
    {
        $year = date('Y', $this->current_date);
        if (date('n', $this->current_date) < 8)
            return --$year;
        return $year;
    }

    /**
     * Конвертирует дату из количества секунд в формат БД
     * @param int $ts количество секунд
     * @return string вида 2013-12-22
     */
    static public function ts_to_db($ts)
    {
        return date('Y-m-d', $ts);
    }

    /**
     * Конвертирует дату из формата БД в количество секунд
     * @param string $db_date строка вида 2013-12-22
     * @param string|null $db_time часы и минуты. строка вида 08:43:00
     * @return int
     */
    static public function db_to_ts($db_date, $db_time = null)
    {
        $hour = 0;
        $minutes = 0;
        $date_array = date_parse_from_format('Y-m-d', $db_date);
        if (isset($db_time)) {
            $time_array = date_parse_from_format('H:i:s', $db_time);
            $hour = $time_array['hour'];
            $minutes = $time_array['minute'];
        }
        return mktime($hour, $minutes, 0, $date_array['month'], $date_array['day'], $date_array['year']);
    }

    static public function db_timedate_to_ts($db_timedate)
    {
        $ts_array = date_parse_from_format('Y-m-d H:i:s', $db_timedate);
        if ($ts_array['year'] <= 1970)
            $ts_array['year'] = 2000;
        return mktime($ts_array['hour'], $ts_array['minute'], $ts_array['second'], $ts_array['month'], $ts_array['day'], $ts_array['year']);
    }

    static public function db_timedate_to_screen_time($db_timedate)
    {
        $ts = self::db_timedate_to_ts($db_timedate);
        return date('H:i', $ts);
    }

    /**
     * Форматирует дату для вывода на экран
     * @param int $ts количество секунд
     * @return string вида 25.10.2013
     */
    static public function ts_to_screen($ts)
    {
        return date('d.m.y', $ts);
    }

    /**
     * Форматирует дату БД для вывода на экран
     * @param string $db_date
     * @return string
     */
    static public function db_to_screen($db_date)
    {
        return self::ts_to_screen(self::db_to_ts($db_date));
    }

    /**
     * возвращает порядковый номер недели года для текущей даты
     * @return int|string
     */
    public function get_week_id()
    {
        return $this->week_id;
    }

    /**
     * Возвращает порядковый номер месяца для текущей даты
     * @return int|string
     */
    public function get_month_id()
    {
        return $this->month_id;
    }

    /**
     * @return
     */
    public function get_date_begin()
    {
        return $this->date_begin;
    }

    /**
     * @return
     */
    public function get_date_end()
    {
        return $this->date_end;
    }

    /**
     * Возвращает дату начала учебного года -  1 августа текущего учебного года
     * @return int
     */
    public function get_study_year_begin()
    {
        return mktime(0, 0, 0, 8, 1, $this->get_study_year());
    }

    /**
     * возращает дату окончания учебного года -  31 июля года, следующего за учебным
     * @return int
     */
    public function get_study_year_end()
    {
        return mktime(0, 0, 0, 8, 1, $this->get_study_year() + 1) - self::DAY_LEN;
    }

    /**
     * Опряделяет входит ли текущее время в интервал между начальной и конечной датой
     * @return bool
     */
    public function is_current_interval()
    {
        if (($this->date_begin < time()) && (time() < $this->date_end)) {
            return true;
        }
        return false;
    }

    /**
     * получение номера дня недели для заданной даты
     * @param $ts
     * @return int|string
     */
    static public function get_weekday_by_ts($ts)
    {
        return (date('w', $ts) == 0) ? 7 : (date('w', $ts));
    }

    /**
     * получение номера недели в году для заданной даты
     * @param $ts
     * @return string
     */
    static public function get_weeknum_by_ts($ts)
    {
        return date('W', $ts);
    }

    /**
     * Получение порядкового номера дня в году для заданной даты
     * @param $ts
     * @return string
     */
    static public function get_year_day_by_ts($ts)
    {
        return date('z', $ts);
    }

    /**
     * Получение штампа текущего дня
     * @return int
     */
    static public function get_current_day_ts()
    {
        return mktime(0, 0, 0);
    }

}
