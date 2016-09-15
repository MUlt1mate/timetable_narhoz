<?php

/**
 * @author: MUlt1mate
 * Date: 13.04.13
 * Time: 22:19
 */
class Plan_work extends ActiveRecord\Model
{
    public static $table = '';
    const OLD_ZACHET = 7;
    const NEW_ZACHET = 4;
    const DB_PLAN_WORK = 'sh_PlanWork';

    /**
     * Получение учебного плана
     * @param Shedule $shedule
     * @param int $course
     * @param int $teacher
     * @param int $group
     * @param int $plan_work
     * @return array|bool
     */
    public static function get($shedule, $course, $teacher, $group, $plan_work)
    {
        if ((null == $group) && (null == $teacher)) {
            return false;
        }
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
		@Course = ' . $course . ',
		@CodPrep = ' . $teacher . ',
		@Grup = ' . $group;

        $query = DB::query($sql);
        $result = $query->fetchAll();
        foreach ($result as &$row) {
            if ((null == $row['codgrup'])) {
                $row['is_flow'] = '1';
                $row['group_flow_id'] = $row['codflow'];
            } else {
                $row['is_flow'] = '0';
                $row['group_flow_id'] = $row['codgrup'];
            }

            //определяем подгруппу
            if (('1' == $row['is_flow']) and (false === strstr($row['grupflowname'], ';'))) {
                $row['is_flow'] = 0;
                $row['group_flow_id'] = $group;
                $row['subgroup'] = substr($row['grupflowname'], -1);
            } else {
                $row['subgroup'] = 0;
            }

            if (self::OLD_ZACHET == $row['codworktype']) {
                $row['codworktype'] = self::NEW_ZACHET;
            }
        }

        return $result;
    }
}
