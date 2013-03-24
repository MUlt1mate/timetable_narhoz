<?php
/**
 * @author: MUlt1mate
 * Date: 16.03.13
 * Time: 9:58
 */
function __autoload($class_name)
{
    require_once('../include/php-activerecord-master/ActiveRecord.php');
    if (file_exists('../include/' . $class_name . '.php'))
        include_once '../include/' . $class_name . '.php';
    if (file_exists('../include/AppModels/' . $class_name . '.php'))
        include_once '../include/AppModels/' . $class_name . '.php';
}

$c = new Timetable_Controller();