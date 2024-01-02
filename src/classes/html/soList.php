<?php
/**
 * Created by PhpStorm.
 * User: Oddler
 * Date: 26.06.2019
 * Time: 11:34
 */

namespace Oddler\SOLib\classes\html;


class soList
{
    /**
     * Generate HTML
     *
     * @param string $sName
     * @param array $aItems
     * @param array $aOptions
     *
     * @return string
     */
    public function _($sName, $aItems, $aOptions){
        $sId = isset($aOptions['id'])?$aOptions['id']:$sName;
        $Selected = isset($aOptions['selected'])?$aOptions['selected']:false;

        $sTMP  = isset($aOptions['class'])?' class="'.$aOptions['class'].'"':'';
        $sTMP .= isset($aOptions['attrebutes'])?' '.$aOptions['attrebutes']:'';
        $sTMP .= isset($aOptions['size'])?' size="'.$aOptions['size'].'"':'';
        $sTMP .= isset($aOptions['multiple'])?' multiple':'';
        $sTMP .= isset($aOptions['required'])?' required aria-required="true"':'';
        $sTMP .= isset($aOptions['autofocus'])?' autofocus':'';
        $sTMP .= (isset($aOptions['readonly'])) && ($aOptions['readonly'])?' disabled="disabled"':'';


        $sRet = "\n<select name='$sName' id='$sId' $sTMP>\n";
        foreach ($aItems as $sKey => $sVal) {
            $sSelected = $Selected == $sKey?'selected':'';
            $sRet .= "<option name='$sKey' $sSelected>$sVal</option>\n";
        }
        $sRet .= "</select>\n";
        return $sRet;
    }
}