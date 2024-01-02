<?php
/**
 * Created by PhpStorm.
 * User: Oddler
 * Date: 26.06.2019
 * Time: 11:30
 */

namespace Oddler\SOLib\classes;


class html
{
    /**
     * html generator.
     *
     * @throws \Exception
     */
    public function _()
    {
        $args = func_get_args();
        $sType = array_shift($args);

        $aType = explode(':', $sType);
        $sType = $aType[0];
        $sMethod = isset($aType[1])?$aType[1]:'_';

        $sTypeName = strtolower($sType);
        switch ($sTypeName){
            case 'list':
                $sClass = 'soList';
                break;

            default:
                $sClass = $sTypeName;
                break;
        }

        if ($sClass){
            $sClass = 'Oddler\SOLib\classes\html\\' . $sClass;
            $oTMP = new $sClass();
            return $oTMP->$sMethod(... $args);
        }else{
            throw new \Exception('Wrong HTML element "' . $sType . '"');
        }
    }
}