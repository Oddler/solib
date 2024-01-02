<?php
/**
 * Created by PhpStorm.
 * User: Oddler
 * Date: 11.07.2019
 * Time: 15:44
 */

namespace Oddler\SOLib\classes\html;


class table
{
    public function generateByObjectArray($sName, $aItems, $aOptions)
    {
        $sId = isset($aOptions['id'])?$aOptions['id']:$sName;

        $sTMP  = isset($aOptions['class'])?' class="'.$aOptions['class'].'"':'';
        $sTMP .= isset($aOptions['attrebutes'])?' '.$aOptions['attrebutes']:'';

        $sRet = '';
        if(count($aItems))
        {
            $sRet .= "\n<table id='$sId' $sTMP >\n";
            $aVars = get_object_vars($aItems[0]);

            foreach($aItems as $oItem)
            {
                $sRet .= '<tr>';
                foreach($aVars as $key => $val)
                {
                    $sRet .= '<td>'.$oItem->$key.'</td>';
                }
                $sRet .= '</tr>'."\n";
            }
            $sRet .= "</table>\n";

        }

        return $sRet;
    }
}