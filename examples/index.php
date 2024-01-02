<?php

/*

composer require oddler/solib:dev-master

*/

error_reporting(E_ALL);
ini_set('display_errors', 'On');


require_once('vendor/autoload.php');

use Oddler\SOLib\core;

// For php <= 5.6 MUST to be commented out:
use function Oddler\SOLib\globalSOFactory AS SOFactory;

// For php <= 5.6 MUST to be uncommented:
/*
function SOFactory() //!!!
{
    $args = func_get_args();
    return call_user_func_array('Oddler\SOLib\globalSOFactory', $args);
    //return Oddler\SOLib\globalSOFactory();
}
*/

core::init([
    'bDebug' => TRUE
]);




class mySingleton extends Oddler\SOLib\classes\singleton
{
    public $iCount = 0;

    public function increment()
    {
        return $this->iCount++;
    }
}



class soTests
{
    /**
     * Тест: Массив
     *
     * @return void
     */
    protected function _runArray()
    {
        echo '<h2>Array</h2>';
        $oArray = SOFactory('array', array('a' => '31', 'b' => '32'));
        echo '<pre>';
        echo $oArray->add('c', 32);
        echo '</pre><hr/>';
    }

    /**
     * Тест: Registry
     *
     * @return void
     */
    protected function _runRegistry()
    {
        echo '<h2>Registry</h2>';
        $oRegistry = SOFactory('registry');
        $oRegistry->add('key 1', 'val 1');
        $oRegistry->add('key 2', 'val 2');
        $oRegistry->add('key 3', 'val 3');
        $oRegistry->add('key 4', 'val 4');

        echo '<pre>';
        print_r($oRegistry->toString());
        echo '</pre>';

        echo '<pre>';
        print_r($oRegistry);
        echo '</pre>';

        echo '<pre>';
        print_r($oRegistry->keys());
        echo '</pre>';

        $sJson = $oRegistry->toJson();

        echo $oRegistry->length().'<br />';

        $oRegistry->set('key 2', 'val 22');
        echo $oRegistry->get('key 2').'<br />';

        var_dump($oRegistry->exists('key 3'));
        echo '<br />';

        $oRegistry->remove('key 3');

        var_dump($oRegistry->exists('key 3'));
        echo '<br />';


        echo '<pre>';
        print_r($oRegistry->toString());
        echo '</pre>';

        $oRegistry->fromJson($sJson);

        echo '<pre>';
        echo $oRegistry->toString();
        echo '</pre><hr/>';
    }

    /**
     * Тест: String
     *
     * @return void
     */
    protected function _runString()
    {
        echo '<h2>String</h2>';
        $oString = SOFactory('string', 'строка 3');
        echo $oString.'<br />';
        echo $oString->toUpperCase()->replace(' 2', ' 10').'<br />';
        echo '<hr />';
    }

    /**
     * Тест: Прямое создание объекта
     *
     * @return void
     */
    protected function _runCall_1()
    {
        echo '<h2>Прямое создание объекта</h2>';
        $oString = new Oddler\SOLib\classes\soString('Это строка 2');
        echo $oString.'<br />';
        echo '<hr />';
    }

    /**
     * Тест: Некрасивый вызов через полный НеймСпейс
     *
     * @return void
     */
    protected function _runCall_2()
    {
        echo '<h2>Некрасивый вызов через полный НеймСпейс</h2>';
        $oString = Oddler\SOLib\core::factory('string', 'Это строка');
        echo $oString->toUpperCase().'<br />';
        echo '<hr />';
    }

    /**
     * Тест: Чуть более красивый вариант
     *
     * @return void
     */
    protected function _runCall_3()
    {
        echo '<h2>Чуть более красивый вариант</h2>';

        echo '<i>Для работы нужно использовать <b>use Oddler\SOLib\core;</b> ВНЕ функции. <br />Т.к. не уверен не повлеяет ли это на другие тесты пока закоментировал этот вариант </i><br />';
        /*
              use Oddler\SOLib\core;
              $oString = core::factory('string', 'Это строка 2');
              echo $oString->toUpperCase().'<br />';
        */
        echo '<hr />';
    }

    /**
     * Тест: Вызов через глобальную функцию
     *
     * @return void
     */
    protected function _runCall_4()
    {
        echo '<h2>Вызов через глобальную функцию</h2>';
        //require_once('vendor/oddler/solib/src/soc.php');
        $oString = SOFactory('string', 'строка 3');
        echo $oString->toUpperCase().'<br />';
        echo '<hr />';
    }

    /**
     * Тест: Неверный тип
     *
     * @return void
     */
    protected function _runError_1()
    {
        echo '<h2>Неверный тип</h2>';
        $oString = SOFactory('string1', 'строка 3');
        //echo $oString->toUpperCase().'<br />';
        echo '<hr />';
    }

    /**
     * Тест: DB
     *
     * @return void
     */
    protected function _runDB()
    {
/*
CREATE database db_test3;
GRANT ALL ON db_test3.* TO test3@'%' IDENTIFIED BY 'xxx';
GRANT ALL ON db_test3.* TO test3@'localhost' IDENTIFIED BY 'xxx';

CREATE TABLE `test1` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL
) ENGINE=InnoDB;
ALTER TABLE `test1` ADD PRIMARY KEY (`id`);
ALTER TABLE `test1` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

    define('DB_HOST', 'localhost');
    define('DB_USER', 'test3');
    define('DB_PASSWORD', 'xxx');
    define('DB_NAME', 'db_test3');
    
*/
        require_once('config.php'); // i61
        echo '<h2>DB</h2>';
        $oDB1 = SOFactory('db', array(
            'host'     => DB_HOST,
            'user'     => DB_USER,
            'password' => DB_PASSWORD,
            'database' => DB_NAME,
            'charset'  => 'utf8',
            'database_type' => 'mysql'
        ));

        $oDB1->setQuery('SELECT * FROM `test1` ');
        $aRows = $oDB1->loadObjectsList();
        echo '<pre>';
        print_r($aRows);
        echo '</pre>';

        echo '<hr />';
    }


    /**
     * Тест: Проверка наследования для паттерна одиночка
     *
     * @return void
     */
    protected function _runSingleton()
    {
        echo '<h2>Проверка наследования для паттерна одиночка </h2>';

        $oSingleton = mySingleton::getInstance();
        echo $oSingleton->increment().'<br />';
        echo $oSingleton->increment().'<br />';

        $oSingleton = mySingleton::getInstance();
        echo $oSingleton->increment().'<br />';
        echo $oSingleton->increment().'<br />';

        // $oSingleton2 = new mySingleton;
        // - выдаст ошибку создания второго экземпляра :-)


        echo '<hr />';
    }

    /**
     * Тест: Даты
     *
     * @return void
     */
    protected function _runDate()
    {
        echo '<h2>Даты</h2>';

        $oDateTools = SOFactory('dateTools');
        echo $oDateTools->getNextWorkDate().'<br />';
        echo '<hr />';
    }

    /**
     * Тест: Input
     *
     * @return void
     */
    protected function _runInput()
    {
        echo '<h2>Input</h2>';
        ?>
        <form action="/?test1=1" method="post">
            <input type="text" name="test2" value="2" />
            <button>SUBMIT</button>
        </form>
        <?
        $oInput = SOFactory('input');
        echo 'test1: '.$oInput->get('test1').'<br />';
        echo 'test2: '.$oInput->get('test2', 'Empty').'<br />';
        echo 'test3: '.$oInput->get('test3', 'Empty').'<br />';

        echo 'test1. From GET: '.$oInput->fromGet('test1', 'Empty').'<br />';
        echo 'test1. From POST: '.$oInput->fromPost('test2', 'Empty').'<br />';

        echo '<hr />';
    }

    /**
     * Тест: File System Operations
     *
     * @return void
     */
    protected function _runFSO()
    {
        echo '<h2>File System Operations</h2>';

        $oFSO = SOFactory('fso');

        $sPathIn = $_SERVER['DOCUMENT_ROOT'].'/vendor/oddler/solib/';
        $sPathOut = $_SERVER['DOCUMENT_ROOT'].'/OUT/Sub1/SUB2/';
        $sFileIn = 'index.php';

        echo 'makeDir: ';
        var_dump(
            $oFSO->makeDir($sPathOut)
        );
        echo '<br />';
        echo $oFSO->getMessages(). '<br />';

        echo 'copyFile: ';
        var_dump(
            $oFSO->copyFile($sFileIn, $sPathOut.$sFileIn)
        );
        echo '<br />';

        $oFile = $oFSO->linkFile($sPathOut.$sFileIn);
        if($oFile->exists())
        {
            echo $oFile->getExtension(). '<br />';

            echo 'Copy: ';
            var_dump(
                $oFile->copy($sPathOut.'index222.php')
            );
            echo '<br />';

            echo 'Remove: ';
            var_dump(
                $oFile->remove()
            );
            echo '<br />';
        }

        $oDir = $oFSO->linkDir($sPathIn);
        echo '<h3>scanDir: '.$sPathIn.'</h3><pre>';
        print_r($oDir->scan(array('ScanSubDirs' => FALSE)));
        echo '</pre>';

        if($oDir->exists())
        {
            $oDir->copyDir($sPathOut);
        }

        echo '<pre>';
        print_r($oDir->scan());
        echo '</pre>';

        echo '<hr />';
    }


    /**
     * Тест: Iterator
     *
     * @return void
     */
    protected function _runIterator()
    {
        echo '<h2>Iterator</h2>';

        $oIterator = SOFactory('Iterator', array('31', '32', 33, 34));
        echo '<pre>';
        echo $oIterator->add(99);
        echo '</pre>';
        foreach($oIterator as $key => $value)
        {
            echo 'key: '.$key.'; -- value: '.$value.'<br />';
        }
        echo '<hr />';
    }



    /**
     * Тест: cURL
     *
     * @return void
     */
    protected function _runCURL()
    {
        echo '<h2>cURL</h2>';

        $oCurl = SOFactory('curl');

        $sHost = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];

        echo 'Только контент: <pre>';
        // echo $sHost.'/curl_test.php'. '<br />';
        print_r($oCurl->getPageContent($sHost.'/curl_test.php'));
        echo '</pre>';
        echo 'LastError: '.$oCurl->getLastError().'<br />';

        $oCurl->setOption('USER_AGENT', 'MY AGENT');
        $oCurl->setOption('REFERER', 'http://ya.ru');

        //$oCurl->setOption('COOKIE_RESET', TRUE);
        $oCurl->setOption('COOKIE_FILE', 'cookie.txt');

        // Брал из http://free-proxy.cz/ru/ - С протокол SOCKS5!
//      $oCurl->setOption('PROXY', '146.185.168.235:1080');

        $oCurl->setHeaders(array(
//        'Content-Type: application/json;charset=utf-8', // - При таком заголовке POST данные не отрабатывают
            'My-CODE: 123rty111',
        ));

        $oCurl->setPost(array(
            'KEY1' => '1',
            'KEY2' => '3',
            'HTML' => '<b>BBBB</b>',
        ));

        echo 'Делает запрос к указанному адресу: <pre>';
        print_r($oCurl->request($sHost.'/curl_test.php?task=1'));
        echo '</pre>';

        echo '<hr />';
    }

    /**
     * Тест: «Наблюдатель»
     *
     * @return void
     */
    protected function _runObservable()
    {
        echo '<h2>По идее это должна быть демонстрация паттерна «Наблюдатель»<br/>
Но т.к. совершенно не понятно, пока, как такой шаблон проектирования привязать к библиотеке, то просто положил его как отдельный файл </h2>';

        echo '<hr />';
    }


    /**
     * Тест: Загрузка файлов на сервер
     *
     * @return void
     */
    protected function _runUpload()
    {
        echo '<h2>Загрузка файлов на сервер </h2>';
        ?>
        <form action="/" enctype="multipart/form-data" method="post">
            <input type="file" name="myFile1" />
            <input type="file" name="jform[myFile2]" />
            <input type="hidden" name="bUploading" value="1" />
            <button>SUBMIT</button>
        </form>
        <?
        /*
         * echo '<pre>';
        print_r($_FILES);
        echo '</pre>';
        //*/

        $oUploader = SOFactory('uploader', 'myFile1');
        if($oUploader->canUpload())
        {
            echo 'File name: '.$oUploader->getName().'<br />';
            echo 'File extension: '.$oUploader->getExtension().'<br />';

            if ($oUploader->upload(__DIR__.'/OUT/', 'new.name'))
            {
                echo 'Done!<br />';
            }
            else
            {
                echo 'Error: <span style="color:red">"'.$oUploader->getError().'"</span><br />';
            }
        }
        else
        {
            echo 'No file to upload<br />';
        }




        $oUploader2 = SOFactory('uploader', 'myFile2');
        if($oUploader2->canUpload())
        {
            if ($oUploader2->upload(__DIR__.'/OUT/'))
            {
                echo 'Done!<br />';
            }
            else
            {
                echo 'Error: <span style="color:red">"'.$oUploader2->getError().'"</span><br />';
            }
        }




        echo '<hr />';
    }


    /**
     * Тест: Работа с изображениями
     *
     * @return void
     */
    protected function _runGraphic()
    {
        echo '<h2>Работа с изображениями</h2>';

        $sInFile = 'examples/01.jpg';
        //$oImg = SOFactory('graphic');
        //$oImg = SOFactory('image');

        // Создание превьюшки
        $oImg = SOFactory('graphic');
        $oImg->load($sInFile);
        $oImg->createThumb(300);
        $oImg->save('OUT/Thumb.jpg');

        // Смена типа
        $oImg = SOFactory('graphic');
        $oImg->load($sInFile);
        $oImg->setType('png');
        $oImg->save('OUT/out.png');

        // Расчет пропорций для нового размера
        $oImg = SOFactory('graphic');
        $oImg->load($sInFile);
        echo '<pre>';
        print_r( $oImg->calcNewSize(100));
        echo '</pre>';

        // Вырезать часть изображения
        $oImg = SOFactory('graphic');
        $oImg->load($sInFile);
        $oImg->crop(700, 300, 300, 300);
        $oImg->save('OUT/croped.jpg');

        // Создание новой, пустой картинки
        $oImg = SOFactory('graphic');
        $oImg->create('jpg', 500, 100, '00FF00');
        $oImg->save('OUT/NEW.jpg');

        // Создаем, рисуем круги, выводим текст
        $oImg = SOFactory('graphic');
        $oImg->create( 'jpg', 500, 500, 'F0FF00' );
        $oImg->text( 'TeXt TeXt TeXt TeXt TeXt', 25, 10, 20, '000', 30, 'examples/Roboto-Regular.ttf', FALSE );
        $oImg->text( 'TeXt TeXt TeXt TeXt TeXt TeXt TeXt', 100, 100, 15, '000', -30, 'examples/Roboto-Regular.ttf', 30 );
        $oImg->textOnBottom( 'Текст внизу', 16, 10, -20, '000', 10, 'examples/Roboto-Regular.ttf');
        $oImg->borders();
        $oImg->circle(300, 300, 100);
        $oImg->save('OUT/Paint.jpg');

        // Наложение водяного знака
        $oImg = SOFactory('graphic');
        $oImg->load($sInFile);
        $oImg->addWatermark('examples/watermark.jpg', 100, 100, 50);
        $oImg->save('OUT/Watermarked.jpg');

        // Вывод картинки в буфер
        $oImg = SOFactory('graphic');
        $oImg->load($sInFile);
//-      $oImg->draw();

        echo '<hr />';
    }

    /**
     * Тест: Make Zip
     *
     * @return void
     */
    protected function _runZip()
    {
        echo '<h2>Make Zip</h2>';

        // Передаваемая директория не должна иметь слеша на конце
        $sourcePath = __DIR__.'/examples';
        $outZipPath = __DIR__.'/out.zip';

        $oZip = SOFactory('zip');
        $oZip->zipDir($sourcePath, $outZipPath);

        echo '<hr />';
    }

    /**
     * Тест: HTML: List
     *
     * @return void
     */
    protected function _runHTML_List()
    {
        echo '<h2>HTML: List</h2>';

        $oHtml = SOFactory('html');
        $sHtmlList = $oHtml->_('list', 'ListName',
            [
                1 => 'z1',
                2 => 'z2',
                3 => 'z3',
                4 => 'z4',
                5 => 'z5',
                6 => 'z6',
            ],
            [
                'selected' => '3',
                'attrebutes' => 'data-id="123"',
                'id' => 'NewId',
                'class' => 'myClass',
                'size' => '3',
                'multiple' => true,
                'required' => true,
                'autofocus' => true,
                //'readonly' => false,
            ]
        );

        echo $sHtmlList;

        echo '<hr />';
    }


    /**
     * Тест: HTML: Input
     *
     * @return void
     */
    protected function _runHTML_Input()
    {
        echo '<h2>HTML: Input</h2>';

        $oHtml = SOFactory('html');
        echo $oHtml->_('input', 'InputName', 'VALUE',
            [
                'attrebutes' => 'data-id="123"',
                'id' => 'NewId',
                'class' => 'myClass',
                'placeholder' => 'placeholder',
                'required' => true,
                'autofocus' => true,
                //'readonly' => false,
            ]
        );

        echo '<br />';

        echo $oHtml->_('input', 'InputName2', '',
            [
                'attrebutes' => 'data-id="123"',
                'id' => 'NewId',
                'class' => 'myClass',
                'placeholder' => 'placeholder 2',
                'required' => true,
                'autofocus' => true,
                //'readonly' => false,
            ]
        );


        echo '<hr />';
    }

    /**
     * Тест: HTML: Table From Array Of Objects
     *
     * @return void
     */
    protected function _runHTML_Table1()
    {
        echo '<h2>HTML: Table From Array Of Objects</h2>';

        $aTMP = [];
        for($i = 0; $i < 12; $i++)
        {
            $oTMP = new stdClass();
            $oTMP->id = $i;
            $oTMP->title = 'Name-'.$i;
            $oTMP->color = rand(1000, 9999);
            $oTMP->color2 = rand(1000, 9999);
            $aTMP[] = $oTMP;
        }
        echo SOFactory('html')->_('table:generateByObjectArray', 'Tabl-1', $aTMP,
            [
                'attrebutes' => ' border="1"',
                'id' => 'TBL1',
                'class' => 'myClass',
            ]
        );

        echo '<hr />';
    }

    /**
     * Тест: Набор утилит для работы с текстом
     *
     * @return void
     */
    protected function _runTextTools()
    {
        echo '<h2>Text Tools</h2>';

        $oTextTools = SOFactory('textTools');

        echo 'firstLetter2Lower: '. $oTextTools->firstLetter2Lower('НАБОР утилит для работы с текстом') . "<br />\n";
        echo 'firstLetter2Upper: '. $oTextTools->firstLetter2Upper('набор утилит для работы с текстом') . "<br />\n";
        echo 'priceFormat: '. $oTextTools->priceFormat('12365478.15') . " руб.<br />\n";
        echo 'getNextWorkDate: '. $oTextTools->getNextWorkDate() . "<br />\n";
        echo 'number2str: '. $oTextTools->number2str(1234567.89) . "<br />\n";
        echo 'toLatin: '. $oTextTools->toLatin('Первый тест') . "<br />\n";

        ob_start();
        ?>
        <div>
            <p style="color:red">
            <ul><li><a href="/packages/oddler/pdo">oddler/pdo</a>: *</li></ul>
            </p>
        </div>
        <?php
        $sText = ob_get_clean();
        echo 'clearHTML: '. $oTextTools->clearHTML($sText) . "<br />\n";

        echo '<hr />';
    }











    /**
     * Тест: BLANK
     *
     * @return void
     */
    protected function _runBLANK()
    {
        echo '<h2>BLANK</h2>';

        $oBLANK = SOFactory('BLANK');

        echo '<hr />';
    }



    /**
     * Run tests
     *
     * @return void
     */
    public function run()
    {
        //-- $this->_runObservable();
        //!$this->_runError_1();
        $this->_runRegistry();
        $this->_runArray();
        $this->_runCall_1();
        $this->_runCall_2();
        $this->_runCall_3();
        $this->_runCall_4();
        //-$this->_runDB();
        $this->_runSingleton();
        $this->_runDate();
        $this->_runInput();
        $this->_runFSO();
        $this->_runIterator();
        //-$this->_runCURL();
        $this->_runUpload();
        //-$this->_runGraphic();
        //-$this->_runZip();
        $this->_runString();
        $this->_runHTML_List();
        $this->_runHTML_Input();
        $this->_runHTML_Table1();
        $this->_runTextTools();
    }
}

$oTests = new soTests();
$oTests->run();