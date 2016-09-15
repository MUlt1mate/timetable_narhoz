<?php

/**
 * Даты с событиями учебного плана ( даты начала триместра, сессии)
 * @author: MUlt1mate
 * Date: 24.03.13
 * Time: 1:04
 */
class Announce extends ActiveRecord\Model
{
    public static $table = 'sh_announce';
    public static $pk = 'id';

    /**
     * Группировка дат по форме обучения
     * @return array
     */
    public static function group_by_cod_form_study()
    {
        $list = array();
        $rows = self::find('all', array(
            'conditions' => 'value>=\'' . TimeDate::get_current_day_db() . "'",
            'order' => 'value asc'
        ));
        if (!is_array($rows)) {
            return $list;
        }
        foreach ($rows as $row) {
            $list[$row->codformstudy][] = $row;
        }
        return $list;
    }

    /**
     * Добавление нового события
     * @param string $event
     * @param string $date
     * @param int $form_study
     * @return bool
     */
    public static function add($event, $date, $form_study)
    {
        $event = new self(array(
            'name' => $event,
            'value' => $date,
            'codformstudy' => $form_study,
        ), false);
        return $event->save();
    }
}
