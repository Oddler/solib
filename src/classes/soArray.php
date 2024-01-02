<?php

namespace Oddler\SOLib\classes;

/**
 * Класс обертка для массивов
 */
class soArray
{
    /**
     *
     * @var array Items
     *
     */
    protected $_aItems = array();

    /**
     * Constructor
     *
     * @param array $aItems
     */
    public function __construct($aItems)
    {
        $this->_aItems = $aItems;
    }

    /**
     *
     * Устанавливает значение
     *
     * @param string $key
     * @param mixed $val
     *
     * @return object $this
     */
    public function add($key, $val)
    {
        $this->_aItems[$key] = $val;
        return $this;
    }

    /**
     * Конвертация в строку
     *
     * @return string
     */
    public function __toString()
    {
        return print_r($this->_aItems, true);
    }

}