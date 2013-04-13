<?php
/**
 * @author: MUlt1mate
 * Date: 13.04.13
 * Time: 19:58
 */

class Shedule_params
{
    const PREFFIX = 'param_';

    const PARAM_SHEDULE = 'shedule_id';
    const PARAM_FACULTY = 'faculty';
    const PARAM_GROUP = 'group';
    const PARAM_TEACHER = 'teacher';
    const PARAM_COURSE = 'course';
    const PARAM_PLAN_WORK = 'plan_work';

    private static $all_params = array(
        self::PARAM_SHEDULE,
        self::PARAM_FACULTY,
        self::PARAM_GROUP,
        self::PARAM_TEACHER,
        self::PARAM_COURSE,
        self::PARAM_PLAN_WORK,
    );

    static public function get_array()
    {
        $param = array();
        foreach ($_COOKIE as $key => $value) {
            if (0 === strpos($key, self::PREFFIX)) {
                if ('' != $value)
                    $param[substr($key, strlen(self::PREFFIX))] = $value;
            }
        }

        foreach (self::$all_params as $name_param)
            if (!isset($param[$name_param]))
                $param[$name_param] = null;
        return $param;
    }

    static public function set($name, $value)
    {
        if (in_array($name, self::$all_params)) {
            setcookie(self::PREFFIX . $name, $value, time() + TimeDate::YEAR_LEN);
            $_COOKIE[self::PREFFIX . $name] = $value;
        }
    }
}