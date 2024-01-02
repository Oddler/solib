<?php

if(version_compare(PHP_VERSION, '5.6.0', '>=')){
    require_once('core_56.php');
} else {
    require_once('core_54.php');
}