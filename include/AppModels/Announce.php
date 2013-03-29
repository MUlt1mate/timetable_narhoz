<?php
/**
 * Даты с событиями учебного плана ( даты начала триместра, сессии)
 * @author: MUlt1mate
 * Date: 24.03.13
 * Time: 1:04
 */

class Announce extends ActiveRecord\Model
{
    static $table = 'sh_announce';

    /**
     * Группировка дат по форме обучения
     * @return array
     */
    public static function group_by_cod_form_study()
    {
        $list = array();
        $rows = self::find('all', array('order'=>'value asc'));
        if (is_array($rows))
            foreach ($rows as $row) {
                $list[$row->codformstudy][] = $row;
            }
        return $list;
    }
}