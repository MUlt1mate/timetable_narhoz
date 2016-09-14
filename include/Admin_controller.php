<?php
/**
 * @author: MUlt1mate
 * Date: 30.03.13
 * Time: 14:13
 *
 * @todo создать возможность смены пароля
 */

class Admin_controller extends Main_controller
{
    const TEMPLATE_FOLDER = 'admin';
    private $secure_key = 'e366d105cfd734677897aaccf51e97a3';
    /**
     * @var TimeDate
     */
    private $TimeDate;
    private $params = array();

    private $modes = array('week', 'month', 'agenda');
    private $mode;
    private $type = array();
    /**
     * @var Timetable
     */
    private $timetable;

    public function __construct()
    {
        parent::__construct();
        session_start();
        $this->auth();
        $this->TimeDate = new TimeDate();
        $this->params = Shedule_params::get_array();
        $this->choose_action();
    }

    /**
     * Авторизация
     */
    private function auth()
    {
        if (isset($_POST['password'])) {
            if (md5(md5($_POST['password'] . $this->secure_key)) == $this->config['admin']['hash']) {
                session_regenerate_id(true);
                $_SESSION['admin_auth'] = true;
                $time = time();
                $time_hash = md5($time . $this->config['admin']['hash']);
                setcookie('now', $time, time() + TimeDate::WEEK_LEN * 4);
                setcookie('time_hash', $time_hash, time() + TimeDate::WEEK_LEN * 4);
            }
        }
        $access = false;
        if (isset($_SESSION['admin_auth']))
            $access = true;
        elseif (isset($_COOKIE['time_hash'])) {
            if ($_COOKIE['time_hash'] == md5($_COOKIE['now'] . $this->config['admin']['hash']))
                $access = true;
        }
        if (!$access) {
            $this->view->screen(View::A_LOGIN);
            die();
        }
    }

    /**
     * Список расписаний
     */
    protected function action_default()
    {
        if (isset($_POST['name'])) {
            if (isset($_POST['id']) && (0 < $_POST['id'])) {
                $shedule = Shedule::find($_POST['id']);
                $shedule->edit(
                    $_POST['name'],
                    $_POST['type'],
                    $_POST['status'],
                    $_POST['formstudy'],
                    $_POST['year'],
                    $_POST['numterm'],
                    $_POST['date_begin'],
                    $_POST['date_end']
                );
            } else {
                Shedule::add(
                    $_POST['name'],
                    $_POST['type'],
                    $_POST['status'],
                    $_POST['formstudy'],
                    $_POST['year'],
                    $_POST['numterm'],
                    $_POST['date_begin'],
                    $_POST['date_end']
                );
            }
        }
        $this->view->screen(View::A_SHEDULES, array(
            'shedules' => ShedulesView::all(),
            'shedule_status' => SheduleStatus::all(),
            'shedule_types' => SheduleType::all(),
            'form_study' => FormStudy::all(),
            'study_year' => $this->TimeDate->get_study_year(),

        ));
    }

    /**
     * Список предметов
     */
    protected function action_lessons()
    {
        if (isset($_GET['refresh'])) {
            //выключено, чтобы не нажать случайно
            //Lessons::change_all_colors();
        }
        $this->view->screen(View::A_LESSONS, array('lessons' => Lessons::all(array('order' => 'namesub'))));
    }

    /**
     * Список расписаний звонков
     */
    protected function action_times()
    {
        if (isset($_POST['time_begin'])) {
            LessonsTimes::add($_POST['time_begin'], $_POST['time_end'], $_POST['hours']);
        }
        $this->view->screen(View::A_TIMES, array('times' => LessonsTimes::all(array('order' => 'time_begin'))));
    }

    /**
     * Все аудитории
     */
    protected function action_rooms()
    {
        $this->view->screen(View::A_ROOMS, array('rooms' => RoomsView::all(array('order' => 'NumBuilding, CodRoomType, PlaceCount DESC'))));
    }

    /**
     * AJAX Создание файлов экспорта для всех групп и преподавателей, у которых есть хоть одно занятие
     */
    protected function action_ical_refresh()
    {
        set_time_limit(300);
        $groups = Group::get_list($this->TimeDate->get_study_year());
        foreach ($groups as $group) {
            if (0 < $group['count']) {
                $subgroups = array(0);
                $tt_type = array('group' => $group['codgrup']);
                $timetable = new Timetable();
                $timetable->init($tt_type);
                if ($timetable->getShowSubgroups())
                    $subgroups = array(0, 1, 2);
                foreach ($subgroups as $subgroup) {
                    $tt_type = array_merge($tt_type, array('subgroup' => $subgroup));
                    $lessons = Timetable::get_timetable($this->TimeDate->get_study_year_begin(), $this->TimeDate->get_study_year_end(), $tt_type);
                    $lessons_remove = Timetable::get_timetable($this->TimeDate->get_study_year_begin(), $this->TimeDate->get_study_year_end(), $tt_type, true);
                    Timetable::build_export(
                        $lessons,
                        $lessons_remove,
                        $tt_type,
                        $timetable->getTimetableTitle(),
                        true
                    );
                    echo '  ';
                }
                echo '<br>';
            }
        }

        $teachers = Teachers::all();
        foreach ($teachers as $teacher) {
            if (0 < $teacher->count) {
                $tt_type = array('teacher' => $teacher->id);
                $timetable = new Timetable();
                $timetable->init($tt_type);
                $lessons = Timetable::get_timetable($this->TimeDate->get_study_year_begin(), $this->TimeDate->get_study_year_end(), $tt_type);
                $lessons_remove = Timetable::get_timetable($this->TimeDate->get_study_year_begin(), $this->TimeDate->get_study_year_end(), $tt_type, true);
                Timetable::build_export(
                    $lessons,
                    $lessons_remove,
                    $tt_type,
                    $timetable->getTimetableTitle(),
                    true
                );
                echo '<br>';
            }
        }
    }

    /**
     * Список преподавателей
     */
    protected function action_teachers()
    {
        if (isset($_GET['id'])) {
            $lessons = Timetable::get_timetable(
                $this->TimeDate->get_study_year_begin(),
                $this->TimeDate->get_study_year_end(),
                array('teacher' => $_GET['id']));
            $grid = Timetable::build_all_by_weekdays($lessons);
            $this->view->screen(View::A_TEACHER_PRINT, array(
                'lessons' => $grid,
                'teacher' => Teachers::find($_GET['id']),
                'weekdays' => TimeDate::$weekdays,
            ));
        } else
            $this->view->screen(View::A_TEACHERS, array('teachers' => Teachers::get_list()));
    }

    /**
     * Редактирование расписания
     */
    protected function action_edit()
    {
        if (isset($_GET[Shedule_params::PARAM_SHEDULE])) {
            Shedule_params::set(Shedule_params::PARAM_SHEDULE, $_GET[Shedule_params::PARAM_SHEDULE]);
            $this->view->screen(View::A_TT_EDIT, array(
                'params' => $this->params,
                'faculty' => Lists::$faculty,
                'types_plan_work' => Lists::$type_plan_work,
                'groups' => Group::get_list($this->TimeDate->get_study_year()),
                'teachers' => Teachers::find('all', array('order' => 'fio')),
                'lessons' => Lessons::all(array('order' => 'namesub')),
                'types_lessons' => Lists::$lesson_type,
                'rooms' => Rooms::all(array('order' => 'numbuilding, placecount desc')),
                'times' => LessonsTimes::all(array('order' => 'time_begin',)),
            ));
        }
    }

    /**
     * AJAX Таблица занятости расписания
     */
    protected function action_busy_table()
    {
        $lessons = Timetable::get_busytable(
            $this->params[Shedule_params::PARAM_SHEDULE],
            $this->params[Shedule_params::PARAM_WEEK_ODD],
            $this->params[Shedule_params::PARAM_GROUP],
            $this->params[Shedule_params::PARAM_SUBGROUP],
            $this->params[Shedule_params::PARAM_IS_FLOW],
            $this->params[Shedule_params::PARAM_TEACHER],
            $this->params[Shedule_params::PARAM_ROOM],
            $this->params[Shedule_params::PARAM_DATE_BEGIN],
            $this->params[Shedule_params::PARAM_DATE_END]
        );
        $this->view->screen(View::A_TABLE_BUSY_LESSONS, array(
            'lessons' => $lessons,
            'work_days_times' => LessonsTimes::$MN_FR_times,
            'saturday_times' => LessonsTimes::$ST_times,
            'sunday_times' => LessonsTimes::$MN_FR_times,
            'days' => TimeDate::$weekdays,
        ));
    }

    /**
     * AJAX Таблица аудиторий
     */
    protected function action_rooms_table()
    {
        $this->view->screen(View::A_TABLE_ROOMS, array(
            'rooms' => Rooms::get_busy(
                Shedule::find($this->params[Shedule_params::PARAM_SHEDULE]),
                $this->params[Shedule_params::PARAM_GROUP],
                $this->params[Shedule_params::PARAM_FLOW],
                $this->params[Shedule_params::PARAM_TIME_BEGIN],
                $this->params[Shedule_params::PARAM_TIME_END],
                $this->params[Shedule_params::PARAM_WEEKDAY_ID],
                $this->params[Shedule_params::PARAM_WEEK_ODD],
                $this->params[Shedule_params::PARAM_SUBGROUP],
                $this->params[Shedule_params::PARAM_DATE_BEGIN],
                $this->params[Shedule_params::PARAM_DATE_END]
            ),
            'current_room' => $this->params[Shedule_params::PARAM_ROOM],
        ));
    }

    /**
     * AJAX Таблица учебного плана
     */
    protected function action_plan_table()
    {
        $plans = Plan_work::get(
            Shedule::find($this->params[Shedule_params::PARAM_SHEDULE]),
            $this->params[Shedule_params::PARAM_COURSE],
            $this->params[Shedule_params::PARAM_TEACHER_LIST],
            $this->params[Shedule_params::PARAM_GROUP_LIST],
            $this->params[Shedule_params::PARAM_PLAN_WORK]
        );
        $hours = 0;
        foreach ($plans as $p)
            $hours += $p['hours'];
        $this->view->screen(View::A_TABLE_PLAN_WORK, array(
            'plans' => $plans,
            'hours' => $hours,
        ));
    }

    /**
     * AJAX Таблица с уже добавленными занятиями
     */
    protected function action_time_table()
    {
        $lessons = Timetable::get_by_params(
            Shedule::find($this->params[Shedule_params::PARAM_SHEDULE]),
            $this->params[Shedule_params::PARAM_COURSE],
            $this->params[Shedule_params::PARAM_TEACHER_LIST],
            $this->params[Shedule_params::PARAM_GROUP_LIST],
            $this->params[Shedule_params::PARAM_PLAN_WORK]
        );
        $this->view->screen(View::A_TABLE_LESSONS, array(
            'lessons' => $lessons,
            'hours' => Timetable::calculate_hours($lessons),
        ));
    }

    /**
     * AJAX получение списков групп и преподавателей
     */
    protected function action_list()
    {
        if (isset($_GET['list']) && in_array($_GET['list'], array('groups', 'teachers'))) {
            if ('groups' == $_GET['list']) {
                $list = Lists::get_groups(
                    Shedule::find($this->params[Shedule_params::PARAM_SHEDULE]),
                    $this->params[Shedule_params::PARAM_FACULTY],
                    $this->params[Shedule_params::PARAM_COURSE],
                    $this->params[Shedule_params::PARAM_TEACHER_LIST]
                );
                $this->view->screen('options_list', array(
                    'list' => $list,
                    'select' => $this->params[Shedule_params::PARAM_GROUP_LIST],
                ));
            } elseif ('teachers' == $_GET['list']) {
                $list = Lists::get_teachers(
                    Shedule::find($this->params[Shedule_params::PARAM_SHEDULE]),
                    $this->params[Shedule_params::PARAM_FACULTY],
                    $this->params[Shedule_params::PARAM_COURSE],
                    $this->params[Shedule_params::PARAM_GROUP_LIST]
                );
                $this->view->screen('options_list', array(
                    'list' => $list,
                    'select' => $this->params[Shedule_params::PARAM_TEACHER_LIST],
                ));
            }
        }
    }

    /**
     * AJAX Добавление нового занятия
     */
    protected function action_add_new_lesson()
    {
        $result = Timetable::add(
            $this->params[Shedule_params::PARAM_SHEDULE],
            $this->params[Shedule_params::PARAM_GROUP],
            $this->params[Shedule_params::PARAM_FLOW],
            $this->params[Shedule_params::PARAM_IS_FLOW],
            $this->params[Shedule_params::PARAM_SUBGROUP],
            $this->params[Shedule_params::PARAM_TEACHER],
            $this->params[Shedule_params::PARAM_LESSON],
            $this->params[Shedule_params::PARAM_LESSON_TYPE],
            $this->params[Shedule_params::PARAM_TIME],
            $this->params[Shedule_params::PARAM_ROOM],
            $this->params[Shedule_params::PARAM_WEEK_ODD],
            $this->params[Shedule_params::PARAM_WEEKDAY_ID],
            $this->params[Shedule_params::PARAM_DATE_BEGIN],
            $this->params[Shedule_params::PARAM_DATE_END]
        );
        if ($result)
            echo 'success';
    }

    /**
     * AJAX Редактирование занятия
     */
    protected function action_edit_lesson()
    {
        if (isset($_GET['id'])) {
            $lesson = Timetable::get_by_id($_GET['id']);
            $result = $lesson->edit(
                $this->params[Shedule_params::PARAM_SHEDULE],
                $this->params[Shedule_params::PARAM_GROUP],
                $this->params[Shedule_params::PARAM_FLOW],
                $this->params[Shedule_params::PARAM_IS_FLOW],
                $this->params[Shedule_params::PARAM_SUBGROUP],
                $this->params[Shedule_params::PARAM_TEACHER],
                $this->params[Shedule_params::PARAM_LESSON],
                $this->params[Shedule_params::PARAM_LESSON_TYPE],
                $this->params[Shedule_params::PARAM_TIME],
                $this->params[Shedule_params::PARAM_ROOM],
                $this->params[Shedule_params::PARAM_WEEK_ODD],
                $this->params[Shedule_params::PARAM_WEEKDAY_ID],
                $this->params[Shedule_params::PARAM_DATE_BEGIN],
                $this->params[Shedule_params::PARAM_DATE_END]
            );
            if ($result)
                echo 'success';
        }
    }

    /**
     * AJAX Удаление занятия
     */
    protected function action_delete_lesson()
    {
        if (isset($_POST['lesson_id'])) {
            $lesson = Timetable::get_by_id($_POST['lesson_id']);
            if ($lesson->delete())
                echo 'success';
        }
    }

    /**
     * Отображает занятия, которые проходят в данный момент
     */
    protected function action_current()
    {
        $lessons = Timetable::get_current();
        $this->view->screen(View::A_CURRENT, array(
            'lessons' => $lessons,
        ));
    }

    /**
     * Подготовка расписания для вывода
     */
    private function timetable_init()
    {
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
            $this->timetable->show_rooms(true);
            $this->timetable->init($this->type);
        }
    }

    /**
     * Список групп, либо расписание
     */
    protected function action_timetable()
    {
        $this->timetable_init();

        if (0 == count($this->type)) {
            $groups = Group::get_group_list($this->config['HideGroups'], $this->forms_study, $this->TimeDate->get_study_year());
            $all_groups = $groups['groups'];
            $group_years = $groups['group_years'];
            $teachers = Teachers::get_list();
            $this->view->screen(View::A_INDEX, array(
                'groups_all' => $all_groups,
                'teachers' => $teachers,
                'forms_study' => $this->forms_study,
                'group_years' => $group_years,
                'announce' => Announce::group_by_cod_form_study(),
            ));
        } else {
            $this->view->screen(View::A_TIMETABLE, array(
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
     * AJAX Вывод расписания
     */
    protected function action_main_table()
    {
        $this->timetable_init();

        if (0 < count($this->type)) {
            $lessons = Timetable::get_timetable($this->TimeDate->get_date_begin(), $this->TimeDate->get_date_end(), $this->type, false, true);
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
            }
        } else
            die('type nsot define');
    }

    /**
     * AJAX Информация о занятии
     */
    protected function action_lesson_info()
    {
        if (isset($_GET['id']) && (0 < $_GET['id'])) {
            $lesson = Timetable::find((int)$_GET['id']);
            $this->view->screen(View::A_LESSON_INFO, array('lesson' => $lesson));
        }
    }

    /**
     * Выход из системы
     */
    protected function action_exit()
    {
        unset($_SESSION['admin_auth']);
        setcookie('now', '', -1);
        setcookie('time_hash', '', -1);
        header('location: /');
        exit();
    }

    /**
     * Список ближайших событий
     */
    protected function action_announce()
    {
        if (isset($_POST['event'])) {
            Announce::add($_POST['event'], $_POST['date'], $_POST['form_study']);
        } elseif (isset($_GET['delete_item'])) {
            try {
                $event = Announce::find_by_pk($_GET['delete_item']);
                $event->delete();
            } catch (\ActiveRecord\RecordNotFound $e) {

            }
        }
        $dates = Announce::group_by_cod_form_study();
        $this->view->screen(View::A_TABLE_ANNOUNCE, array(
            'dates' => $dates,
        ));
    }

    protected function action_settings()
    {
        $this->view->screen(View::A_SETTINGS);
    }
}