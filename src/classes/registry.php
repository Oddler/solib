<?php

namespace Oddler\SOLib\classes;

/**
 * Примесь. Шаблон реестр.
 */
trait traitRegistry
{
    /**
     * @var array Массив элементов
     */
    protected $_aItems = array();

    //------------------------------------------------

    /**
     * Бросает исключение в случаи ошибки
     *
     * @param string $sText
     *
     * @throws \Exception
     */
    protected function _throwError($sText)
    {
        throw new \Exception($sText);
    }


    /**
     * Устанавливает значение по ключу.
     * Генерирует ошибку, если элемента с таким ключом нет.
     *
     * @param string $sKey
     * @param mixed $Value
     *
     * @return object $this
     * @throws \Exception
     */
    public function set($sKey, $Value)
    {
        if ($this->exists($sKey)) {
            $this->_aItems[$sKey] = $Value;
        } else {
            $this->_throwError('Key <b>' . $sKey . '</b> not exists');
        }

        return $this;
    }

    /**
     * Добавляет значение по ключу.
     * Генерирует ошибку, если элемента с таким ключом уже есть.
     *
     * @param string $sKey
     * @param mixed $Value
     *
     * @return object $this
     * @throws \Exception
     */
    public function add($sKey, $Value)
    {
        if (!$this->exists($sKey)) {
            $this->_aItems[$sKey] = $Value;
        } else {
            $this->_throwError('Key <b>' . $sKey . '</b> exists');
        }

        return $this;
    }

    /**
     * Получаем значение по ключу.
     *
     * @param string $sKey
     * @param mixed $DefValue
     *
     * @return mixed
     */
    public function get($sKey, $DefValue = NULL)
    {
        return isset($this->_aItems[$sKey]) ? $this->_aItems[$sKey] : $DefValue;
    }

    /**
     * Удаляем значение по ключу.
     *
     * @param string $sKey
     *
     * @return object $this
     */
    public function remove($sKey)
    {
        unset($this->_aItems[$sKey]);

        return $this;
    }

    /**
     * Возвращает количество элементов в реестре
     *
     * @return int
     */
    public function length()
    {
        return count($this->_aItems);
    }

    /**
     * Возвращает все ключи реестра
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->_aItems);
    }

    /**
     * Проверяет наличие элемента с заданным ключом
     *
     * @param string $sKey
     *
     * @return boolean
     */
    public function exists($sKey)
    {
        return isset($this->_aItems[$sKey]);
    }

}

/**
 * Класс реестр.
 *
 */
class registry
{
    use traitRegistry;

    /**
     * Возвращает все элементы в виде JSON
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->_aItems);
    }

    /**
     * Загружает элементы из JSON
     *
     * @param string $sJson
     *
     * @return void
     */
    public function fromJson($sJson)
    {
        $this->_aItems = json_decode($sJson);
    }

    /**
     * Возвращает все элементы в виде строки
     *
     * @return string
     */
    public function toString()
    {
        return print_r($this->_aItems, TRUE);
    }

    /**
     * Конвертация в строку
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

}