<?php
/**
 * @author: MUlt1mate
 * Date: 30.03.13
 * Time: 15:40
 */
function __autoload($class_name)
{
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/Main_controller.php';
    if (file_exists(__DIR__ . '/' . $class_name . '.php'))
        require_once __DIR__ . '/' . $class_name . '.php';
    if (file_exists(__DIR__ . '/AppModels/' . $class_name . '.php'))
        require_once __DIR__ . '/AppModels/' . $class_name . '.php';
}