<?php
/**
 * @author: MUlt1mate
 * Date: 16.03.13
 * Time: 10:52
 */

class View
{
    const view_path = "../templates/";

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