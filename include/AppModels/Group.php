<?php
/**
 * @author: MUlt1mate
 * Date: 18.03.13
 * Time: 22:18
 */

class Group extends ActiveRecord\Model
{
    static protected $table = 'grup';
    static public $primary_key = 'codgrup';
    static private $proc_name = 'sh_GroupList';

    static private function get_list($study_year, $form_study_id = null)
    {
        $form_study_id = ($form_study_id == null) ? 'null' : (int)$form_study_id;
        $sql = self::$proc_name . ' @CodFormStudy=' . $form_study_id . ', @ThisYear=' . (int)$study_year;
        $query = self::query($sql);
        return $query->fetchAll();
    }

    static public function get_info($group_id)
    {
        $params = array(
            'select' => 'CodGrup, NameGrup, Count(id) as lessons_count, max(subgroup)as subgroup',
            'from' => self::$table,
            'joins' => 'LEFT JOIN TimeGrid on group_id=CodGrup',
            'limit' => 1,
            'group' => 'CodGrup, NameGrup',
        );
        return self::find_by_pk($group_id, $params);
    }

    static public function get_group_list($hide_groups, $forms_study, $study_year)
    {
        $groups = self::get_list($study_year);
        foreach ($forms_study as $key => $value) {
            $group_years[$key] = array();
        }
        $groups_all = array();
        $group_years = array();
        foreach ($groups as $row) {
            if (!in_array($row['codgrup'], $hide_groups)) {
                $groups_all[$row['codformstudy']][$row['codfaculty']][$study_year - $row['beginyear'] + 1][] = $row;
                $year = $study_year - $row['beginyear'] + 1;
                if (isset($group_years[$row['codformstudy']][$year]))
                    $group_years[$row['codformstudy']][$year]++;
                else
                    $group_years[$row['codformstudy']][$year] = 1;
            }
        }

        $result['groups'] = $groups_all;
        $result['group_years'] = $group_years;
        return $result;
    }
}