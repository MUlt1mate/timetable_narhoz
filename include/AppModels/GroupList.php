<?php
/**
 * @author: MUlt1mate
 * Date: 16.03.13
 * Time: 11:26
 */

class GroupList extends ActiveRecord\Model
{
    const procedure = 'sh_GroupList';

    static public function get($form_study_id = null)
    {
        $form_study_id = ($form_study_id == null) ? 'null' : (int)$form_study_id;
        $query = self::query(self::procedure . '
        @CodFormStudy=' . $form_study_id . ',
        @ThisYear =' . (int)TimeDate::get_study_year());
        return $query->fetchAll();
    }

}