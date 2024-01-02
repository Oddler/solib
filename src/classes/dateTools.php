<?php

namespace Oddler\SOLib\classes;

/**
 * Класс Паттерн одиночка
 */
class dateTools
{
    /**
     * Возвращает ближайшую дату не являющееся выходным днем.
     *
     * @return string
     * @throws \Exception
     */
    public function getNextWorkDate()
    {
        $i = 1;
        do {
            $date = new \DateTime();
            $date->add(new \DateInterval('P' . $i . 'D'));
            $iNum = $date->format('N');
            //echo $iNum.' : '.$date->format('Y-m-d') . "<br/>";

            $i++;
            if ($i > 7) {
                break;
            }
        } while ($iNum >= 6);

        return $date->format('d.m.Y');
    }
}