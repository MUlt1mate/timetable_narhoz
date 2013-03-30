<?php
/**
 * @author: MUlt1mate
 * Date: 30.03.13
 * Time: 15:04
 */

class Main_controller
{
    const CONFIG_INI = '../config.ini';
    protected $config = array();
    protected $view;
    protected $action = 'default';
    protected $forms_study = array(0, 1, 3);

    public function __construct()
    {
        $this->config = parse_ini_file(self::CONFIG_INI, TRUE);
        date_default_timezone_set(TimeDate::TIMEZONE);
        $this->view = new View(static::TEMPLATE_FOLDER);

        new DB_Connect(
            $this->config['connection']['host'],
            $this->config['connection']['db'],
            $this->config['connection']['user'],
            $this->config['connection']['password']
        );
    }

    protected function choose_action()
    {
        if (isset($_GET['action']))
            $this->action = $_GET['action'];
        $method = 'action_' . $this->action;
        if (method_exists($this, $method))
            $this->$method();
        else
            $this->show_404();
    }

    /**
     * Страница не найдена
     */
    protected function show_404()
    {
        die('page not found');
    }
}