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
    const VIEW_PATH = '../templates/';

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
        $template = self::VIEW_PATH . $this->folder . '/' . $file . ".php";
        if (is_array($args)) {
            extract($args, EXTR_PREFIX_SAME, 'data');
        }
        ob_start();
        ob_implicit_flush(false);
        require($template);
        return ob_get_clean();
    }
}
