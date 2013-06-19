<?php
/**
 * @author: MUlt1mate
 * Date: 31.03.13
 * Time: 2:31
 *
 * @property int $codformstudy
 * @property string $formstudy
 */

class FormStudy extends ActiveRecord\Model
{
    static $table = 'FormStudy';

    public static $all = array(
        0 => 'Очная',
        1 => 'Заочная',
        3 => 'Сокращенная',
    );
}