<?php

namespace Oddler\SOLib\classes;

/**
 * Класс обертка для строк
 */
class soString
{
    /**
     *
     * @var string Text
     *
     */
    protected $_sText = '';

    /**
     * Constructor
     *
     * @param string $sText
     */
    public function __construct($sText)
    {
        $this->_sText = $sText;
    }

    /**
     *
     * Приводит все буквы к верхнему регистру
     *
     * @return object $this
     */
    public function toUpperCase()
    {
        $this->_sText = mb_strtoupper($this->_sText);
        return $this;
    }


    /**
     * Производит замену в строке
     *
     * @param string $search С
     * @param string $replace На
     *
     * @return object $this
     */
    public function replace($search, $replace)
    {
        $this->_sText = str_replace($search, $replace, $this->_sText);
        return $this;
    }

    /**
     * Конвертация в строку
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_sText;
    }

}