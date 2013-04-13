<?php
/**
 * @author: MUlt1mate
 * Date: 13.04.13
 * Time: 22:19
 */

class Plan_work extends ActiveRecord\Model
{
    static $table = '';
    const DB_PLAN_WORK = 'sh_PlanWork';

    static public function get($shedule, $faculty, $course, $teacher, $group, $plan_work)
    {
        if ((null == $group) && (null == $teacher))
            return false;
        $faculty = (null == $faculty) ? 'null' : (int)$faculty;
        $course = (null == $course) ? 'null' : (int)$course;
        $teacher = (null == $teacher) ? 'null' : (int)$teacher;
        $group = (null == $group) ? 'null' : (int)$group;
        $plan_work = (null == $plan_work) ? 'null' : (int)$plan_work;

        $sql = self::DB_PLAN_WORK . '
        @Year = ' . (int)$shedule->year . ',
		@NumTerm = ' . (int)$shedule->numterm . ',
		@CodFormStudy = ' . (int)$shedule->formstudy . ',
		@SheduleType = ' . (int)$shedule->type . ',
		@CodPlanWork = ' . $plan_work . ',
		@CodFaculty = ' . $faculty . ',
		@Course = ' . $course . ',
		@CodPrep = ' . $teacher . ',
		@Grup = ' . $group;

        $query = DB::query($sql);
        return $query->fetchAll();
    }
}