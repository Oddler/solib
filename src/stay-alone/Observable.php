<?php

/**
 * Абстрактный класс "Наблюдаемый"
 */
abstract class Observable implements SplSubject
{
    /**
     *
     * @var array Наблюдатели
     *
     */
    protected $_aObservers = [];

    /**
     * Добавление наблюдателя
     *
     * @param SplObserver $oObserver
     *
     * @return object $this
     */
    public function attach(SplObserver $oObserver)
    {
        $id = spl_object_hash($oObserver);
        $this->_aObservers[$id] = $oObserver;
        return $this;
    }

    /**
     * Исключить наблюдателя
     *
     * @param SplObserver $oObserver
     *
     * @return object $this
     */
    public function detach(SplObserver $oObserver)
    {
        $id = spl_object_hash($oObserver);

        if (isset($this->_aObservers[$id])) {
            unset($this->_aObservers[$id]);
        }

        return $this;
    }

    /**
     * Оповестить всех наблюдателей
     *
     * @return object $this
     */
    public function notify()
    {
        foreach ($this->_aObservers as $oObserver) {
            $oObserver->update($this);
        }

        return $this;
    }
}


/**
 * Абстрактный класс "Наблюдатель"
 */
abstract class Observer implements SplObserver
{

}

//////////////////////////////////////////////////////////////

// Классы примеров

/**
 * Профессор
 */
class Professor extends Observable
{
    private $_sString = '';

    public function say($sString)
    {
        $this->_sString = $sString;
        $this->notify();
    }

    /**
     * Возвращает что сказал профессор
     *
     * @return string
     */
    public function getString()
    {
        return $this->_sString;
    }
}

/**
 * Студент
 */
class Student extends Observer
{
    private $_sName = '';

    public function setName($sName)
    {
        $this->_sName = $sName;
    }

    /**
     * @param SplSubject $oProfessor
     */
    public function update($oProfessor)
    {
        echo '<b>' . $this->_sName . '</b> услышал: "' . $oProfessor->getString() . '"<br />';
    }
}


$oProfessor = new Professor();

$oStudent1 = new Student();
$oStudent1->setName('Alex');
$oProfessor->attach($oStudent1);

$oStudent2 = new Student();
$oStudent2->setName('Ivan');
$oProfessor->attach($oStudent2);

$oStudent3 = new Student();
$oStudent3->setName('Sky');
$oProfessor->attach($oStudent3);


$oProfessor->say('Тема лекции: ...');


