<?php

namespace Oddler\SOLib\classes;

/**
 * Обертка вокруг $_REQUEST ($_GET, $_POST и $_COOKIE)
 *
 */
class input
{

    /**
     * Подготавливает и возвращает значение из указанного суперглобального массива
     *
     * @param string $sFrom
     * @param string $sKey
     * @param mixed $Def
     * @param string $sType
     *
     * @return mixed
     * @throws \Exception
     */
    protected function _get($sFrom, $sKey, $Def, $sType)
    {
        switch ($sFrom) {
            case 'REQUEST':
                $aFrom = $_REQUEST;
                break;

            case 'POST':
                $aFrom = $_POST;
                break;

            case 'GET':
                $aFrom = $_GET;
                break;

            default:
                throw new \Exception('Wrong From: <b>' . $sFrom . '</b>');
                break;
        }

        $Ret = isset($aFrom[$sKey]) ? $aFrom[$sKey] : $Def;

        switch ($sType) {
            case 'raw':
                // DO nothing
                break;

            case 'int':
                $Ret = (int)$Ret;
                break;

            case 'float':
                $Ret = (float)$Ret;
                break;

            default:
                throw new \Exception('Wrong type: <b>' . $sType . '</b>');
                break;
        }

        return $Ret;
    }


    /**
     * Возвращает значение из суперглобального массива REQUEST
     *
     * @param string $sKey
     * @param mixed $Def
     * @param string $sType
     *
     * @return mixed
     * @throws \Exception
     */
    public function get($sKey, $Def = NULL, $sType = 'raw')
    {
        return $this->_get('REQUEST', $sKey, $Def, $sType);
    }

    /**
     * Возвращает значение переданное через GET
     *
     * @param string $sKey
     * @param mixed $Def
     * @param string $sType
     *
     * @return mixed
     * @throws \Exception
     */
    public function fromGet($sKey, $Def = NULL, $sType = 'raw')
    {
        return $this->_get('GET', $sKey, $Def, $sType);
    }

    /**
     * Возвращает значение переданное через POST
     *
     * @param string $sKey
     * @param mixed $Def
     * @param string $sType
     *
     * @return mixed
     * @throws \Exception
     */
    public function fromPost($sKey, $Def = NULL, $sType = 'raw')
    {
        return $this->_get('POST', $sKey, $Def, $sType);
    }

}