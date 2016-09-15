<?php

/**
 * Преподаватели
 * @author: MUlt1mate
 * Date: 15.03.13
 * Time: 10:03
 *
 * @property int $id
 * @property int $count
 * @property string $fio
 * @property string $shortfio
 */
class Teachers extends ActiveRecord\Model
{
    public static $table = 'sh_teachers_with_counts';
    public static $primary_key = 'id';

    /**
     * Получение списка преподавателей, отсортированных по фамилии
     * @return array
     */
    public static function get_list()
    {
        $a_list = array();
        $list = self::find('all', array('order' => 'fio'));
        if (!is_array($list)) {
            return $a_list;
        }
        foreach ($list as $l) {
            $a_list[substr($l->fio, 0, 2)][] = $l;
        }
        return $a_list;
    }
}
