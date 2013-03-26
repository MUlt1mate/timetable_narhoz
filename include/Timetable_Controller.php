<?php
/**
 * @author: MUlt1mate
 * Date: 16.03.13
 * Time: 9:59
 */

class Timetable_Controller
{
    private $modes = array('week', 'month', 'agenda');
    private $forms_study = array(0, 1, 3);
    private $mode;
    private $type = array();
    private $config = array();
    private $action = 'default';
    private $view;
    private $TimeDate;
    private $show_subgroups = false;
    private $timetable_title;
    private $head_title;

    const CONFIG_INI = '../config.ini';
    const TEMPLATE_FOLDER = 'timetable';

    public function __construct()
    {
        $this->config = parse_ini_file(self::CONFIG_INI, TRUE);
        date_default_timezone_set(TimeDate::TIMEZONE);
        $this->view = new View(self::TEMPLATE_FOLDER);

        new DB_Connect(
            $this->config['connection']['host'],
            $this->config['connection']['db'],
            $this->config['connection']['user'],
            $this->config['connection']['password']
        );

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

        $this->get_timetable_title();

        if (isset($_GET['action']))
            $this->action = $_GET['action'];
        $method = 'action_' . $this->action;
        if (method_exists($this, $method))
            $this->$method();
        else
            $this->show_404();
    }

    private function show_404()
    {
        die('page not found');
    }

    private function action_default()
    {
        if (0 == count($this->type)) {
            $groups = Group::get_group_list($this->config['HideGroups'], $this->forms_study, $this->TimeDate->get_study_year());
            $all_groups = $groups['groups'];
            $group_years = $groups['group_years'];
            $teachers = Teachers::get_list();
            $show_alert = !(isset($_COOKIE['hide_alert']) && (1 == $_COOKIE['hide_alert']));
            $this->view->screen('index', array(
                'groups_all' => $all_groups,
                'teachers' => $teachers,
                'forms_study' => $this->forms_study,
                'group_years' => $group_years,
                'show_alert' => $show_alert,
                'announce' => Announce::group_by_cod_form_study(),
            ));
        } else {
            $this->view->screen('navigation', array(
                'mode' => $this->mode,
                'start_date' => TimeDate::ts_to_screen($this->TimeDate->get_date_begin()),
                'finish_date' => TimeDate::ts_to_screen($this->TimeDate->get_date_end()),
                'body_title' => $this->timetable_title,
                'title' => $this->head_title,
                'show_subgroup' => $this->show_subgroups,
                'week' => $this->TimeDate->get_week_id(),
                'month' => $this->TimeDate->get_month_id(),
            ));
        }
    }

    private function action_current_date()
    {
        $month = (isset($_GET['month'])) ? (int)$_GET['month'] : $this->TimeDate->get_month_id();
        $week = (isset($_GET['week'])) ? (int)$_GET['week'] : $this->TimeDate->get_week_id();
        $this->view->screen('current_date', array(
            'data' => $this->TimeDate,
            'week' => $week,
            'month' => $month,
            'mode' => $this->mode,
        ));
    }

    private function action_main_table()
    {
        if (0 < count($this->type)) {
            $lessons = Timetable::get_timetable($this->TimeDate->get_date_begin(), $this->TimeDate->get_date_end(), $this->type);
            $lessons_remove = Timetable::get_timetable($this->TimeDate->get_date_begin(), $this->TimeDate->get_date_end(), $this->type, true);

            $teacher_visible = (isset($this->type['teacher'])) ? false : true;
            $group_visible = (isset($this->type['group'])) ? false : true;
            $is_all_subgroup = true;
            if (!isset($this->type['group'])OR(isset($this->type['subgroup']) && ($this->type['subgroup'] > 0)))
                $is_all_subgroup = false;
            $this->view->screen('grid_params', array(
                'start_date' => TimeDate::ts_to_screen($this->TimeDate->get_date_begin()),
                'finish_date' => TimeDate::ts_to_screen($this->TimeDate->get_date_end()),
            ));
            switch ($this->mode) {
                case 'week':
                    $timetable = Timetable::build_week($this->TimeDate->get_date_begin(), $lessons, $lessons_remove);
                    $last_time = intval(substr($timetable['latest_time'], 0, 2)*60) + intval(substr($timetable['latest_time'], 3, 2));
                    $last_hour = ceil($last_time / 60);
                    $current_weekday = ($this->TimeDate->is_current_interval()) ? $this->TimeDate->get_today_weekday_id() : -1;
                    $this->view->screen('grid_week', array(
                        'grid' => $timetable['grid'],
                        'days_name' => TimeDate::$weekdays,
                        'days_date' => $this->TimeDate->get_dates(),
                        'current_weekday' => $current_weekday,
                        'days_count' => $timetable['days_count'],
                        'current_hour' => TimeDate::get_hour(),
                        'current_minutes' => TimeDate::get_minutes(),
                        'is_all_subgroup' => $is_all_subgroup,
                        'teacher_visible' => $teacher_visible,
                        'group_visible' => $group_visible,
                        'last_hour' => $last_hour,
                    ));
                    break;
                case 'month':
                    $timetable = Timetable::build_month($this->TimeDate->get_date_begin(), $lessons, $lessons_remove, $this->TimeDate->get_week_count());
                    $this->view->screen('grid_month', array(
                        'grid' => $timetable['grid'],
                        'days_name' => TimeDate::$weekdays,
                        'days_date' => $this->TimeDate->get_dates(),
                        'today_date' => $this->TimeDate->get_today_weekday_id(),
                        'days_count' => $timetable['days_count'],
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
                    $timetable = Timetable::build_agenda($this->TimeDate->get_date_begin(), $lessons, $lessons_remove);
                    $this->view->screen('grid_agenda', array(
                        'grid' => $timetable['grid'],
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

    private function action_export()
    {
        if (0 < count($this->type)) {
            $lessons = Timetable::get_timetable($this->TimeDate->get_date_begin(), $this->TimeDate->get_date_end(), $this->type);
            $lessons_remove = Timetable::get_timetable($this->TimeDate->get_date_begin(), $this->TimeDate->get_date_end(), $this->type, true);
            Timetable::build_export(
                $lessons,
                $lessons_remove,
                $this->type,
                $this->timetable_title
            );
        } else
            die('type not define');
    }

    private function action_lesson_info()
    {
        if (isset($_GET['id']) && (0 < $_GET['id'])) {
            $lesson = Timetable::find($_GET['id']);
            $this->view->screen('lesson_info', array('lesson' => $lesson));
        }
    }

    private function get_timetable_title()
    {
        if (isset($this->type['group'])) {
            $info = Group::get_info($this->type['group']);
            if (0 < $info->subgroup)
                $this->show_subgroups = true;
            $this->timetable_title = $info->namegrup;
            $this->head_title = 'Группа: ' . $info->namegrup;

            return;
        }
        if (isset($this->type['teacher'])) {
            $info = Teachers::find($this->type['teacher']);
            $this->timetable_title = $info->fio;
            $this->head_title = $info->fio;
            return;
        }
        if (isset($this->type['room'])) {
            $info = Rooms::find($this->type['room']);
            $this->timetable_title = 'Аудитория: ' . $info->number;
            $this->head_title = 'Аудитория: ' . $info->number;
            return;
        }
    }

}