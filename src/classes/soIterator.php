<?php

namespace Oddler\SOLib\classes;

/**
 * Класс предоставляющий интерфейс итератор
 * TODO: Сделать из него примесь
 *
 * ////////////////////////////
 * Внимание, ключи массива должны быть только числовыми и идти по очереди!
 * ////////////////////////////
 *
 */
class soIterator implements \Iterator
{
    /**
     *
     * @var array Items
     *
     */
    protected $_aItems = array();

    /**
     *
     * @var array Items
     *
     */
    protected $_iPosition = 0;

    /**
     * Constructor
     *
     * @param array $aItems
     */
    public function __construct($aItems)
    {
        $this->_iPosition = 0;
        $this->_aItems = $aItems;
    }


    /**
     * (1) Вызывается вначале итераций
     *
     * @return void
     */
    public function rewind()
    {
        //echo __METHOD__.'<br />';
        $this->_iPosition = 0;
    }

    /**
     * (4) Возвращает запрошенный элемент (текущий)
     *
     * @return mixed
     */
    public function current()
    {
        //echo __METHOD__.'<br />';
        return $this->_aItems[$this->_iPosition];
    }

    /**
     * (5) Возвращает текущий ключ (для as $key => $value)
     *
     * @return int
     */
    public function key()
    {
        //echo __METHOD__.'<br />';
        return $this->_iPosition;
    }

    /**
     * (2) Переключение на следующий элемент (вызывается со второго шага и далее)
     *
     * @return void
     */
    public function next()
    {
        //echo __METHOD__.'<br />';
        ++$this->_iPosition;
    }

    /**
     * (3) Вызывается при каждом шаге, проверяет, есть ли элемент с таким ключом
     *
     * @return boolean
     */
    public function valid()
    {
        //echo __METHOD__.'<br />';
        return isset($this->_aItems[$this->_iPosition]);
    }


    /**
     *
     * Устанавливает значение
     *
     * @param mixed $val
     *
     * @return object $this
     */
    public function add($val)
    {
        $this->_aItems[] = $val;
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