<?php

namespace Oddler\SOLib\classes;

use Oddler\Pdo\DBCore;

/**
 * Прокси класс для Oddler\Pdo\
 */
class db
{
    /**
     * Проверяет установлено ли Oddler\Pdo, если да возвращает connect
     *
     * @return object
     * @throws \Exception
     */
    static public function getInstance()
    {
        $args = func_get_args();
        if (class_exists('Oddler\Pdo\DBCore')) {
            // Тут не нужно хранить $this->_oDB!
            // Это уже делает DBCore + есть возможность создавать несколько экземпляров
            $DBCore = DBCore::getInstance();
            //return $DBCore->connect(... $args);
            return call_user_func_array(array($DBCore, 'connect'), $args);
        } else {
            throw new \Exception('PDO not installed! Use: <b>composer require oddler/pdo</b>');
        }
    }

}