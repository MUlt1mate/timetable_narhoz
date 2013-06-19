<?php
/**
 * Шаблонизатор
 * @author: MUlt1mate
 * Date: 16.03.13
 * Time: 10:52
 */

class View
{
    /**
     * Путь к папке с шаблонами
     */
    const view_path = "../templates/";

    const TT_INDEX = 'index';
    const TT_NAVIGATION = 'navigation';
    const TT_CURRENT_DATE = 'current_date';
    const TT_GRID_PARAMS = 'grid_params';
    const TT_GRID_WEEK = 'grid_week';
    const TT_GRID_MONTH = 'grid_month';
    const TT_GRID_AGENDA = 'grid_agenda';
    const TT_LESSON_INFO = 'lesson_info';
    const TT_ANALYTICS = 'analytics';
    const TT_STATUS = 'status';
    const TT_ABOUT = 'about';
    const TT_EXPORT_MODAL = 'export_modal';
    const TT_HEADER = 'header';
    const TT_FOOTER = 'footer';

    const A_INDEX = 'index';
    const A_HEADER = 'header';
    const A_FOOTER = 'footer';
    const A_SHEDULES = 'shedules';
    const A_LOGIN = 'login';
    const A_LESSONS = 'lessons';
    const A_TIMES = 'times';
    const A_TEACHERS = 'teachers';
    const A_CURRENT = 'current';
    const A_TIMETABLE = 'timetable';
    const A_ROOMS = 'rooms';
    const A_TEACHER_PRINT = 'teacher_print';
    const A_TT_EDIT = 'tt_edit';
    const A_TABLE_ROOMS = 'rooms_table';
    const A_TABLE_BUSY_LESSONS = 'busy_table';
    const A_TABLE_PLAN_WORK = 'plan_table';
    const A_TABLE_LESSONS = 'time_table';
    const A_TABLE_ANNOUNCE = 'announce';
    const A_TABLE_HEADER = 'tables_header';
    const A_GRID_PARAMS = 'grid_params';
    const A_GRID_WEEK = 'grid_week';
    const A_GRID_MONTH = 'grid_month';
    const A_GRID_AGENDA = 'grid_agenda';
    const A_LESSON_INFO = 'lesson_info';
    const A_SETTINGS = 'settings';

    private $folder;

    public function __construct($folder)
    {
        $this->folder = $folder;
    }

    /**
     * Вывод готового шаблона на экран
     * @param string $file
     * @param null|array $args
     */
    public function screen($file, $args = null)
    {
        echo $this->render($file, $args);
    }

    /**
     * Получение готового шаблона в виде строки
     * @param string $file
     * @param null|array $args
     * @return string
     */
    public function get($file, $args = null)
    {
        return $this->render($file, $args);
    }

    /**
     * Построение шаблона
     * @param string $file
     * @param array $args
     * @return string
     */
    private function render($file, $args)
    {
        $template = self::view_path . $this->folder . '/' . $file . ".php";
        if (is_array($args))
            extract($args, EXTR_PREFIX_SAME, 'data');
        ob_start();
        ob_implicit_flush(false);
        require($template);
        return ob_get_clean();
    }
}