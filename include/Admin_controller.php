<?php
/**
 * @author: MUlt1mate
 * Date: 30.03.13
 * Time: 14:13
 */

class Admin_controller extends Main_controller
{
    const TEMPLATE_FOLDER = 'admin';

    private $TimeDate;

    public function __construct()
    {
        parent::__construct();
        $this->TimeDate = new TimeDate();
        $this->choose_action();
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
                $timetable = new Timetable($tt_type);
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
                $timetable = new Timetable($tt_type);
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
}