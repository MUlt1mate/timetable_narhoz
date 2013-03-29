<?php
/**
 * Настройки подключения к БД
 * @author: MUlt1mate
 * Date: 16.03.13
 * Time: 10:35
 */

class DB_Connect
{

    /**
     * @param string $host
     * @param string $db
     * @param string $user
     * @param string $password
     */
    public function __construct($host, $db,$user,$password)
    {
        $cfg = ActiveRecord\Config::instance();
        $cfg->set_model_directory(__DIR__ . '/AppModels');
        $cfg->set_connections(
            array(
                'development' => 'sqlsrv://'.$user.':'.$password.'@' . $host . '/' . $db,
            )
        );
    }

}