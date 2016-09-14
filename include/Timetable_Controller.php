<?php
/**
 * Контроллер клиентской части приложения
 * @author: MUlt1mate
 * Date: 16.03.13
 * Time: 9:59
 */
class Timetable_Controller extends Main_controller
{
    /**
     * @var array Все доступные режимы отображения
     */
    private $modes = array('week', 'month', 'agenda');
    /**
     * @var выбранный режим отображения
     */
    private $mode;
    /**
     * @var array массив с параметрами расписания
     */
    private $type = array();
    /**
     * @var TimeDate
     */
    private $TimeDate;
    /**
     * @var Timetable
     */
    private $timetable;

    const TEMPLATE_FOLDER = 'timetable';
    const ERROR_IE6 = 'IE6';
    const ERROR_IE7 = 'IE7';

    public function __construct()
    {
        parent::__construct();
        $this->browser_version_control();

        //если кто-то пришел из VK, перенапрявляем на определенный адрес. Для нормального отображения статистики
        if (isset($_GET['api_id']) && ($this->config['VK']['app_id'] == $_GET['api_id'])) {
            header('location: /?ref=vk_app');
            exit();
        }

        if (isset($_GET['mode'])) {
            setcookie('mode', $_GET['mode'], time() + 60 * 60 * 24 * 365, '/');
            $_COOKIE['mode'] = $_GET['mode'];
        }

        if (isset($_COOKIE['mode']) && in_array($_COOKIE['mode'], $this->modes))
            $this->mode = $_COOKIE['mode'];
        else
            $this->mode = $this->modes[0];

        $this->TimeDate = new TimeDate($this->mode);

        foreach (Timetable::$all_types as $t) {
            if (isset($_GET[$t]))
                $this->type[$t] = (int)$_GET[$t];
        }
        if ((count($this->type) == 1) && isset($this->type['subgroup']))
            $this->type = array();

        if (0 < count($this->type)) {
            $this->timetable = new Timetable();
            if (!$this->timetable->init($this->type))
                $this->show_404();
        }
        $this->choose_action();
    }

    /**
     * Проверка совместимости браузеров
     */
    private function browser_version_control()
    {
        if ((isset($_GET['action']) && ('status' != $_GET['action'])) OR (!isset($_GET['action']))) {
            if ((isset($_SERVER['HTTP_USER_AGENT'])) && (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0'))) {
                header('location: /?action=status&error=' . self::ERROR_IE6);
                exit;
            }
            if ((isset($_SERVER['HTTP_USER_AGENT'])) && (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0'))) {
                header('location: /?action=status&error=' . self::ERROR_IE7);
                exit;
            }
        }
    }

    /**
     * Вывод информации о состоянии и ошибках
     */
    protected function action_status()
    {
        if (isset($_GET['error'])) {
            $this->view->screen(View::TT_STATUS, array('error' => $_GET['error']));
        }
    }

    /**
     * Вывод списка групп, либо сетки расписания
     */
    protected function action_default()
    {
        //если параметры не заданы, выводим список групп
        if (0 == count($this->type)) {
            $groups = Group::get_group_list($this->config['HideGroups'], $this->forms_study, $this->TimeDate->get_study_year());
            $all_groups = $groups['groups'];
            $group_years = $groups['group_years'];
            $teachers = Teachers::get_list();
            $this->view->screen(View::TT_INDEX, array(
                'groups_all' => $all_groups,
                'teachers' => $teachers,
                'forms_study' => $this->forms_study,
                'group_years' => $group_years,
                'announce' => Announce::group_by_cod_form_study(),
            ));
        } else {
            $this->view->screen(View::TT_NAVIGATION, array(
                'mode' => $this->mode,
                'start_date' => TimeDate::ts_to_screen($this->TimeDate->get_date_begin()),
                'finish_date' => TimeDate::ts_to_screen($this->TimeDate->get_date_end()),
                'body_title' => $this->timetable->getTimetableTitle(),
                'title' => $this->timetable->getTimetableTitle(),
                'show_subgroup' => $this->timetable->getShowSubgroups(),
                'week' => $this->TimeDate->get_week_id(),
                'month' => $this->TimeDate->get_month_id(),
            ));
        }
    }

    /**
     * Информация о текущей дате
     */
    protected function action_current_date()
    {
        $month = (isset($_GET['month'])) ? (int)$_GET['month'] : $this->TimeDate->get_month_id();
        $week = (isset($_GET['week'])) ? (int)$_GET['week'] : $this->TimeDate->get_week_id();
        $this->view->screen(View::TT_CURRENT_DATE, array(
            'data' => $this->TimeDate,
            'week' => $week,
            'month' => $month,
            'mode' => $this->mode,
        ));
    }

    /**
     * AJAX Вывод расписания
     */
    protected function action_main_table()
    {
        if (0 < count($this->type)) {
            $lessons = Timetable::get_timetable($this->TimeDate->get_date_begin(), $this->TimeDate->get_date_end(), $this->type);
            $lessons_remove = Timetable::get_timetable($this->TimeDate->get_date_begin(), $this->TimeDate->get_date_end(), $this->type, true);

            $teacher_visible = (isset($this->type['teacher'])) ? false : true;
            $group_visible = (isset($this->type['group'])) ? false : true;
            $is_all_subgroup = true;
            if (!isset($this->type['group'])OR(isset($this->type['subgroup']) && ($this->type['subgroup'] > 0)))
                $is_all_subgroup = false;
            $this->view->screen(View::TT_GRID_PARAMS, array(
                'start_date' => TimeDate::ts_to_screen($this->TimeDate->get_date_begin()),
                'finish_date' => TimeDate::ts_to_screen($this->TimeDate->get_date_end()),
            ));
            switch ($this->mode) {
                case 'week':
                    $grid_lessons = Timetable::build_week($this->TimeDate->get_date_begin(), $lessons, $lessons_remove);
                    $last_time = intval(substr($grid_lessons['latest_time'], 0, 2) * 60) + intval(substr($grid_lessons['latest_time'], 3, 2));
                    $last_hour = ceil($last_time / 60);
                    $current_weekday = ($this->TimeDate->is_current_interval()) ? $this->TimeDate->get_today_weekday_id() : -1;
                    $this->view->screen(View::TT_GRID_WEEK, array(
                        'grid' => $grid_lessons['grid'],
                        'days_name' => TimeDate::$weekdays,
                        'days_date' => $this->TimeDate->get_dates(),
                        'current_weekday' => $current_weekday,
                        'days_count' => $grid_lessons['days_count'],
                        'current_hour' => TimeDate::get_hour(),
                        'current_minutes' => TimeDate::get_minutes(),
                        'is_all_subgroup' => $is_all_subgroup,
                        'teacher_visible' => $teacher_visible,
                        'group_visible' => $group_visible,
                        'last_hour' => $last_hour,
                    ));
                    break;
                case 'month':
                    $grid_lessons = Timetable::build_month($this->TimeDate->get_date_begin(), $lessons, $lessons_remove, $this->TimeDate->get_week_count());
                    $this->view->screen(View::TT_GRID_MONTH, array(
                        'grid' => $grid_lessons['grid'],
                        'days_name' => TimeDate::$weekdays,
                        'days_date' => $this->TimeDate->get_dates(),
                        'today_date' => TimeDate::get_current_day_ts(),
                        'days_count' => $grid_lessons['days_count'],
                        'current_hour' => TimeDate::get_hour(),
                        'current_minutes' => TimeDate::get_minutes(),
                        'is_all_subgroup' => $is_all_subgroup,
                        'teacher_visible' => $teacher_visible,
                        'group_visible' => $group_visible,
                        'week_count' => $this->TimeDate->get_week_count(),
                        'begin_date' => $this->TimeDate->get_date_begin(),
                        'month' => $this->TimeDate->get_month_id(),
                    ));
                    break;
                case 'agenda':
                    $grid_lessons = Timetable::build_agenda($this->TimeDate->get_date_begin(), $lessons, $lessons_remove);
                    $this->view->screen(View::TT_GRID_AGENDA, array(
                        'grid' => $grid_lessons['grid'],
                        'days_name' => TimeDate::$weekdays,
                        'days_date' => $this->TimeDate->get_dates(),
                        'today_date' => $this->TimeDate->get_today_weekday_id(),
                        'current_hour' => TimeDate::get_hour(),
                        'current_minutes' => TimeDate::get_minutes(),
                        'is_all_subgroups' => $is_all_subgroup,
                        'teacher_visible' => $teacher_visible,
                        'group_visible' => $group_visible,
                        'week_count' => $this->TimeDate->get_week_count(),
                        'begin_date' => $this->TimeDate->get_date_begin(),
                        'month' => $this->TimeDate->get_month_id(),
                    ));
                    break;
            }
        } else
            die('type not define');
    }

    /**
     * Экспорт в формате JSON
     * @todo переделать сохранение json в файл, перенести функционал в адм. часть
     */
    protected function action_json()
    {
        $tt = Timetable::all(array('status' => SheduleStatus::STATUS_PUBLIC));
        foreach ($tt as &$lesson) {
            $lesson = $lesson->to_array();
        }
        $json_encode = json_encode($tt);
        print_r(Text::json_cyrillic_encode($json_encode));
    }

    protected function action_json_rv()
    {
        $tt = Timetable::all(array('status' => SheduleStatus::STATUS_PUBLIC));
        foreach ($tt as &$lesson) {
            $lesson = $lesson->to_array();
        }

        $groups = array();
        $group_list = array();
        foreach ($tt as $l) {
            $lesson_info = array(
                "subject" => $l['lesson'],
                "type" => Lists::$lesson_type_rv[$l['typelessonid']],
                "time_start" => substr($l['time_begin'], 0, 5),
                "time_end" => substr($l['time_end'], 0, 5),
                "parity" => (int)$l['week'],
                "teachers" => array(
                    array(
                        'teacher_name' => $l['teacher']
                    )
                ),
                "auditories" => array(
                    array(
                        'auditory_name' => Rooms::$build_aliases[$l['numbuilding']] . $l['room'],
                        'auditory_address' => null,
                    )
                ),
                'dates' => null,
                'date_start' => null,
                'date_end' => null,
            );
            if (isset($l['group_id'])) {
                if (!isset($groups[$l['group_id']]))
                    $groups[$l['group_id']] = array();
                if (!isset($groups[$l['group_id']][$l['weekday_id']]))
                    $groups[$l['group_id']][$l['weekday_id']] = array();

                $groups[$l['group_id']][$l['weekday_id']][] = $lesson_info;
                if (!isset($group_list[$l['group_id']]))
                    $group_list[$l['group_id']] = array(
                        'name' => $l['grupflowname'],
                        'faculty' => $l['codfaculty'],
                    );
            } else {
                $flow_groups = Group::get_groups_by_flow($l['flow_id']);
                foreach ($flow_groups as $f_group) {
                    if (!isset($groups[$f_group]))
                        $groups[$f_group] = array();
                    if (!isset($groups[$f_group][$l['weekday_id']]))
                        $groups[$f_group][$l['weekday_id']] = array();
                    $groups[$f_group][$l['weekday_id']][] = $lesson_info;
                }
            }
        }
        $all_groups = array();
        foreach ($groups as $group_id => $days) {
            $group = array(
                'group_id' => $group_id,
                'group_name' => $group_list[$group_id]['name'],
                'days' => array(),
            );
            $all_days = array();
            foreach ($days as $weekday_id => $day_lessons) {
                $all_days[] = array(
                    'weekday' => $weekday_id,
                    'lessons' => $day_lessons,
                );
            }
            $group['days'] = $all_days;
            $all_groups[$group_list[$group_id]['faculty']][] = $group;
        }
        $timetable = array();
        foreach (Lists::$faculty as $fac_id => $fac_name)
            if (isset($all_groups[$fac_id]))
                $timetable['faculties'][] = array(
                    'faculty_id' => $fac_id,
                    'faculty_name' => $fac_name,
                    'groups' => $all_groups[$fac_id],
                    'date_start' => null,
                    'date_end' => null,
                );
        $json_encode = json_encode($timetable);
        print_r(Text::json_cyrillic_encode($json_encode));
    }

    /**
     * AJAX Экспорт расписания
     */
    protected function action_export()
    {
        if (0 < count($this->type)) {
            $lessons = Timetable::get_timetable($this->TimeDate->get_study_year_begin(), $this->TimeDate->get_study_year_end(), $this->type);
            $lessons_remove = Timetable::get_timetable($this->TimeDate->get_study_year_begin(), $this->TimeDate->get_study_year_end(), $this->type, true);
            Timetable::build_export(
                $lessons,
                $lessons_remove,
                $this->type,
                $this->timetable->getTimetableTitle()
            );
        } else
            die('type not define');
    }

    /**
     * AJAX Информация о занятии
     */
    protected function action_lesson_info()
    {
        if (isset($_GET['id']) && (0 < $_GET['id'])) {
            $lesson = Timetable::find((int)$_GET['id']);
            $this->view->screen(View::TT_LESSON_INFO, array('lesson' => $lesson));
        }
    }
}