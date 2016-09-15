<?php
/**
 * @author: MUlt1mate
 * Date: 30.03.13
 * Time: 15:40
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Main_controller.php';

function tload($class_name)
{
    if (file_exists(__DIR__ . '/' . $class_name . '.php')) {
        require_once __DIR__ . '/' . $class_name . '.php';
        return;
    }
    if (file_exists(__DIR__ . '/AppModels/' . $class_name . '.php')) {
        require_once __DIR__ . '/AppModels/' . $class_name . '.php';
        return;
    }
}

spl_autoload_register('tload');
