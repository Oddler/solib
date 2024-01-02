<?php
/**
 * Created by PhpStorm.
 * User: Oddler
 * Date: 04.07.2019
 * Time: 12:23
 */

namespace Oddler\SOLib\classes\html;


class input
{

    /**
     * Generate HTML
     *
     * @param string $sName
     * @param string $sValue
     * @param array $aOptions
     *
     * @return string
     */
    public function _($sName, $sValue, $aOptions){
        $sId = isset($aOptions['id'])?$aOptions['id']:$sName;

        $sTMP  = isset($aOptions['class'])?' class="'.$aOptions['class'].'"':'';
        $sTMP .= isset($aOptions['placeholder'])?' placeholder="'.$aOptions['placeholder'].'"':'';
        $sTMP .= isset($aOptions['attrebutes'])?' '.$aOptions['attrebutes']:'';
        $sTMP .= isset($aOptions['required'])?' required aria-required="true"':'';
        $sTMP .= isset($aOptions['autofocus'])?' autofocus':'';
        $sTMP .= (isset($aOptions['readonly'])) && ($aOptions['readonly'])?' disabled="disabled"':'';


        return "\n<input name='$sName' value='$sValue' id='$sId' $sTMP />\n";
    }
}