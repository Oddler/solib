<?php

namespace Oddler\SOLib;

/**
 * Основной класс библиотеки, предназначенный для вызова и создания остальных объектов.
 */
class core
{
    /**
     * Метод для создания объектов библиотеки
     *
     * @return object
     *
     * @throws \Exception
     */
    protected function _factory()
    {
        $args = func_get_args();
        $sType = array_shift($args);
        $sTypeName = strtolower($sType);

        $sMethod = '';
        switch ($sTypeName) {
            case 'string':
                $sClass = 'classes\soString';
                break;

            case 'array':
                $sClass = 'classes\soArray';
                break;

            case 'iterator':
                $sClass = 'classes\soIterator';
                break;

            case 'db':
                $sClass = 'classes\db';
                $sMethod = 'getInstance';
                break;

            default:
                $sClass = 'classes\\' . $sType;
                break;
        }

        $sClass = 'Oddler\SOLib\\' . $sClass;
        if (class_exists($sClass)) {
            if ($sMethod) {
                //return $sClass::$sMethod(... $args);
                return call_user_func_array(array($sClass, $sMethod), $args);
            } else {
                //return new $sClass(... $args);
                $reflect  = new \ReflectionClass($sClass);
                $instance = $reflect->newInstanceArgs($args);
                return $instance;
            }
        } else {
            throw new \Exception('Mismatch type "' . $sType . '"');
        }
    }

    /**
     * Публичный метод для получения объектов библиотеки
     *
     * @return object
     */
    static public function factory()
    {
        $o = new self();
        try {
            //return $o->_factory(... func_get_args());
                $args = func_get_args();
                return call_user_func_array(array($o, '_factory'), $args);
        } catch (\Exception $e) {
            //TODO: добавить логирование ошибок и нормальную обработку
            die('<b>Catch Exception</b>: '. $e->getMessage(). "<br />\n");
        }
    }

    /**
     * Вызов этого метода необходим для инициализации библиотеки.
     * Если нет вызова метода, композер не подключит нужный файл.
     *
     * @param array $aRuntimeSettings
     *
     * @return void
     */
    static public function init($aRuntimeSettings = [])
    {
        if(isset($aRuntimeSettings['bDebug']) && $aRuntimeSettings['bDebug']){
            if(class_exists('Whoops\Run')){
                $whoops = new \Whoops\Run;
                $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
                $whoops->register();
            }
        }
    }
}

/**
 * Глобальная функция обертка для Oddler\SOLib\core::factory
 *
 * @return object
 */
function globalSOFactory()
{
    $args = func_get_args();
    return call_user_func_array(array('Oddler\SOLib\core', 'factory'), $args);
}