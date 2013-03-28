<?php
/**
 * @author: MUlt1mate
 * Date: 16.03.13
 * Time: 10:52
 */

class View
{
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

    private $folder;

    public function __construct($folder)
    {
        $this->folder = $folder;
    }

    public function screen($file, $args = null)
    {
        echo $this->render($file, $args);
    }

    public function get($file, $args = null)
    {
        return $this->render($file, $args);
    }

    private function render($file, $args)
    {
//        print_r($args);
//        die();
        $template = self::view_path . $this->folder . '/' . $file . ".php";
        if (is_array($args))
            extract($args, EXTR_PREFIX_SAME, 'data');
        ob_start();
        ob_implicit_flush(false);
        require($template);
        return ob_get_clean();
    }
}