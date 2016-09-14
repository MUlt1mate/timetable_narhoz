<?php
/**
 * @author: MUlt1mate
 * Date: 13.04.13
 * Time: 18:08
 */

class Lists
{
    static $table = '';

    const GROUP_LIST_DB = 'sh_shiftGrup';
    const TEACHERS_LIST_DB = 'sh_shiftPrep';

    static $faculty = array(
        80 => 'Финансово-информационный факультет',
        81 => 'Экономический факультет',
        83 => 'Юридический факультет',
        84 => 'Факультативы',
    );

    static $type_plan_work = array(
        1 => 'Занятия',
        2 => 'Зачеты и экзамены',
        3 => 'Прочее'
    );
    static $lesson_type = array(
        0 => 'внеучебное занятие',
        1 => 'лекция',
        2 => 'практическое занятие',
        3 => 'лабораторная работа',
        4 => 'зачет',
        5 => 'экзамен',
        6 => 'консультация',
    );

    /**
     * Соответсвие типов для приложения Расписание вузов
     *
     * 0 - практическое занятие
     * 1 - лабораторная работа
     * 2 - лекция
     * 3 - семинар
     * 4 - консультация
     * 5 - внеучебное занятие
     * 6 - зачет
     * 7 - экзамен
     * @var array
     */
    static public $lesson_type_rv = array(
        0 => 5,
        1 => 2,
        2 => 0,
        3 => 1,
        4 => 6,
        5 => 7,
        6 => 4,
    );

    /**
     * Возвращает спискок групп
     * @param Shedule $shedule
     * @param int $faculty
     * @param int $course
     * @param int $teacher
     * @return mixed
     */
    public static function get_groups($shedule, $faculty, $course, $teacher)
    {
        $faculty = (null == $faculty) ? 'null' : (int)$faculty;
        $course = (null == $course) ? 'null' : (int)$course;
        $teacher = (null == $teacher) ? 'null' : (int)$teacher;
        $sql = self::GROUP_LIST_DB . '
        @Year = ' . (int)$shedule->year . ',
		@NumTerm = ' . (int)$shedule->numterm . ',
		@CodFormStudy = ' . (int)$shedule->formstudy . ',
		@CodFaculty = ' . $faculty . ',
		@Course = ' . $course . ',
		@CodPrep = ' . $teacher;
        $query = DB::query($sql);
        $list = $query->fetchAll();
        foreach ($list as &$l) {
            $l['value'] = $l['codgrup'];
            $l['name'] = $l['grupflowname'];
        }
        return $list;
    }

    /**
     * Возвращает список преподавателей
     * @param Shedule $shedule
     * @param int $faculty
     * @param int $course
     * @param int $group
     * @return mixed
     */
    public static function get_teachers($shedule, $faculty, $course, $group)
    {
        $faculty = (null == $faculty) ? 'null' : (int)$faculty;
        $course = (null == $course) ? 'null' : (int)$course;
        $group = (null == $group) ? 'null' : (int)$group;
        $sql = self::TEACHERS_LIST_DB . '
        @Year = ' . (int)$shedule->year . ',
		@NumTerm = ' . (int)$shedule->numterm . ',
		@CodFormStudy = ' . (int)$shedule->formstudy . ',
		@CodFaculty = ' . $faculty . ',
		@Course = ' . $course . ',
		@Grup = ' . $group;
        $query = DB::query($sql);
        $list = $query->fetchAll();
        foreach ($list as &$l) {
            $l['value'] = $l['codprep'];
            $l['name'] = $l['fio'];
        }
        return $list;
    }
}