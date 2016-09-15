<?php

/**
 * Настройки подключения к БД
 * @author: MUlt1mate
 * Date: 16.03.13
 * Time: 10:35
 */
class DB
{
    /**
     * Соединение с БД
     * @param string $host
     * @param string $db
     * @param string $user
     * @param string $password
     */
    public static function connect($host, $db, $user, $password)
    {
        $cfg = ActiveRecord\Config::instance();
        $cfg->set_model_directory(__DIR__ . '/AppModels');
        $cfg->set_connections(
            array(
                'development' => 'sqlsrv://' . $user . ':' . $password . '@' . $host . '/' . $db,
            )
        );
    }

    /**
     * Выполнение запроса к БД
     * @param string $sql
     * @param array $values
     * @return mixed
     */
    public static function query($sql, $values = null)
    {
        return ActiveRecord\Connection::instance()->query($sql, $values);
    }

    /**
     * Экранирование строк
     * @param string $value
     * @return string
     */
    public static function escape($value)
    {
        return ActiveRecord\Connection::instance()->escape($value);
    }
}
