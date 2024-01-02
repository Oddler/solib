# SOlib



## Requirements

- PHP 5.6 or higher

## Installation

### Using Composer
```
composer require oddler/solib:dev-master
```
OR:
```json
{
    "require": {
        "oddler/solib": "dev-master"
    }
}
```

## Usage
```php
require_once('vendor/autoload.php');

use Oddler\SOLib\core;
use function Oddler\SOLib\globalSOFactory AS SOFactory; core::init();
```

## PHP side

```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once('vendor/autoload.php');

use Oddler\SOLib\core;
use function Oddler\SOLib\globalSOFactory AS SOFactory; core::init();


// Registry
echo '<h2>Registry</h2>';
$oRegistry = SOFactory('registry');
$oRegistry->add('key 1', 'val 1');
$oRegistry->add('key 2', 'val 2');
$oRegistry->add('key 3', 'val 3');
$oRegistry->add('key 4', 'val 4');
echo '<pre>';
print_r($oRegistry->toString());
echo '</pre><hr/>';


// Array
echo '<h2>Array</h2>';
$oArray = SOFactory('array', array('a' => '31', 'b' => '32'));
echo '<pre>';
echo $oArray->add('c', 32);
echo '</pre><hr/>';


// String
echo '<h2>String</h2>';
$oString = SOFactory('string', 'строка 3');
echo $oString . '<br />';
echo $oString->toUpperCase()->replace(' 2', ' 10') . '<br />';
echo '<hr />';


// Даты
echo '<h2>Даты</h2>';
$oDateTools = SOFactory('dateTools');
echo $oDateTools->getNextWorkDate() . '<br />';
echo '<hr />';


// Iterator
echo '<h2>Iterator</h2>';
$oIterator = SOFactory('Iterator', array('31', '32', 33, 34));
echo '<pre>';
echo $oIterator->add(99);
echo '</pre>';
foreach ($oIterator as $key => $value) {
    echo '<b>key</b>: ' . $key . '; <b>value</b>: ' . $value . '<br />';
}
echo '<hr />';
```
