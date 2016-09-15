<?php
/**
 * @author: MUlt1mate
 * Date: 16.03.13
 * Time: 9:58
 */
try {
    require_once('../include/class_loader.php');
    new Timetable_Controller();
} catch (Exception $e) {
    echo '<h2>На сайте выполняются технические работы</h2>
    <!--' . $e->getMessage() . ' -->';
}