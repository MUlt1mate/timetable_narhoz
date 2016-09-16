<?php

/**
 * @author: MUlt1mate
 * Date: 30.03.13
 * Time: 15:04
 */
abstract class Main_controller
{
    /**
     * Путь к файлу с конфигурацией
     */
    const CONFIG_INI = '../config.ini';
    protected $config = array();
    /**
     * @var View
     */
    protected $view;
    protected $action = 'default';
    protected $forms_study = array(0, 1);
    const TEMPLATE_FOLDER = '';

    public function __construct()
    {
        $this->config = parse_ini_file(self::CONFIG_INI, true);
        date_default_timezone_set(TimeDate::TIMEZONE);
        $this->view = new View(static::TEMPLATE_FOLDER);

        DB::connect(
            $this->config['connection']['host'],
            $this->config['connection']['db'],
            $this->config['connection']['user'],
            $this->config['connection']['password']
        );
    }

    /**
     * Выбор метода для выполнения
     */
    protected function choose_action()
    {
        if (isset($_GET['action'])) {
            $this->action = $_GET['action'];
        }
        $method = 'action_' . $this->action;
        if (method_exists($this, $method)) {
            $this->$method();
        } else {
            $this->show_404();
        }
    }

    /**
     * Страница не найдена
     */
    protected function show_404()
    {
        $this->view->screen('404');
        die();
    }
}
