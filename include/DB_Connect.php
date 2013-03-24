<?php
/**
 * @author: MUlt1mate
 * Date: 16.03.13
 * Time: 10:35
 */

class DB_Connect
{

    public function __construct($host, $db)
    {
        $cfg = ActiveRecord\Config::instance();
        $cfg->set_model_directory(__DIR__ . '/AppModels');
        $cfg->set_connections(
            array(
                'development' => 'sqlsrv://:@' . $host . '/' . $db,
            )
        );
    }

}