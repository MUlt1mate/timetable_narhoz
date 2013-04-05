<?php
/**
 * @author: MUlt1mate
 * Date: 30.03.13
 * Time: 14:13
 */

class Admin_controller extends Main_controller
{
    const TEMPLATE_FOLDER = 'admin';
    private $secure_key = 'e366d105cfd734677897aaccf51e97a3';

    private $TimeDate;


    public function __construct()
    {
        parent::__construct();
        session_start();
        $this->auth();
        $this->TimeDate = new TimeDate();
        $this->choose_action();
    }

    private function auth()
    {
        if (isset($_POST['password'])) {
            if (md5(md5($_POST['password'] . $this->secure_key)) == $this->config['admin']['hash']) {
                session_regenerate_id(true);
                $_SESSION['admin_auth'] = true;
            }
        }
        if (!isset($_SESSION['admin_auth'])) {
            $this->view->screen(View::A_LOGIN);
            die();
        }
    }

    protected function action_default()
    {
        $this->view->screen(View::A_SHEDULES, array(
            'shedules' => ShedulesView::all(),
            'shedule_status' => SheduleStatus::all(),
            'shedule_types' => SheduleType::all(),
            'form_study' => FormStudy::all(),
            'study_year' => $this->TimeDate->get_study_year(),

        ));
    }

    protected function action_lessons()
    {
        if (isset($_GET['refresh'])) {
            Lessons::change_all_colors();
        }
        $this->view->screen(View::A_LESSONS, array('lessons' => Lessons::all(array('order' => 'namesub'))));
    }

    protected function action_times()
    {
        if (isset($_POST['time_begin'])) {
            LessonsTimes::add($_POST['time_begin'], $_POST['time_end'], $_POST['hours']);
        }
        $this->view->screen(View::A_TIMES, array('times' => LessonsTimes::all(array('order' => 'time_begin'))));
    }

    protected function action_rooms()
    {
        $this->view->screen(View::A_ROOMS, array('rooms' => RoomsView::all(array('order' => 'NumBuilding, CodRoomType, PlaceCount DESC'))));
    }

    /**
     * Создание файлов экспорта для всех групп и преподавателей, у которых есть хоть одно занятие
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

    protected function action_current()
    {
        $this->view->screen(View::A_CURRENT);
    }

    protected function action_timetable()
    {
        $this->view->screen(View::A_TIMETABLE);
    }

    protected function action_exit()
    {
        unset($_SESSION['admin_auth']);
        header('location: /');
        exit();
    }
}