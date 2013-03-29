<?php
/**
 * Преподаватели
 * @author: MUlt1mate
 * Date: 15.03.13
 * Time: 10:03
 */

class Teachers extends ActiveRecord\Model
{
    static $table = 'sh_teachers_with_counts';
    static $primary_key = 'id';

    /**
     * Получение списка преподавателей, отсортированных по фамилии
     * @return array
     */
    static public function get_list()
    {
        $a_list=array();
        $list = self::find('all', array('order' => 'fio'));
        if (is_array($list))
            foreach ($list as $l) {
                $a_list[substr($l->fio, 0, 2)][] = $l;
            }
        return $a_list;
    }
}