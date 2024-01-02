<?php

namespace Oddler\SOLib\classes;

/**
 * Класс Паттерн одиночка
 */
class singleton
{
    /**
     *
     * @var object Instance
     *
     */
    static protected $_oInstance = NULL;

    /**
     * Возвращает единственный экземпляр объекта
     *
     * @return object
     */
    public static function getInstance()
    {
        if (self::$_oInstance === null) {
            $sClass = get_called_class();
            self::$_oInstance = new $sClass;
        }
        return self::$_oInstance;
    }

    // Предотвращаем создание
    //----------------------\/\/\/\/\/----------------------------
    private function __construct()
    {
    }

    //private function __clone()
    public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    //private function __wakeup()
    public function __wakeup()
    {
        trigger_error('Unserializing is not allowed.', E_USER_ERROR);
    }
    //----------------------/\/\/\/\/\----------------------------
}