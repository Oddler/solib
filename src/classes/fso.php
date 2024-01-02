<?php

namespace Oddler\SOLib\classes;

/**
 * Класс File System Operations
 */
class FSO
{
    /**
     *
     * @var string Последние сообщения
     *
     */
    protected $_sMessages = '';


    /**
     * Рекурсивное создание цепочки директорий
     *
     * @param string $sPath
     *
     * @return boolean
     */
    public function makeDir($sPath)
    {
        $oDir = new Dir($sPath);
        $bRet = $oDir->create();
        $this->_sMessages = $oDir->getMessages();
        return $bRet;
    }

    /**
     * Копирует файл
     *
     * @param string $sFileIn Путь до файла
     * @param string $sFileOut Адрес нового файла
     *
     * @return boolean
     */
    public function copyFile($sFileIn, $sFileOut)
    {
        $oFile = new File($sFileIn);
        $bRet = $oFile->copy($sFileOut);
        $this->_sMessages = $oFile->getMessages();
        return $bRet;
    }

    /**
     * Создает объект типа "файл" и возвращает его
     *
     * @param string $sPath
     *
     * @return object
     */
    public function linkFile($sPath)
    {
        return new File($sPath);
    }

    /**
     * Создает объект типа "Директория" и возвращает его
     *
     * @param string $sPath
     *
     * @return object
     */
    public function linkDir($sPath)
    {
        return new Dir($sPath);
    }

    /**
     * Возвращает все сообщения, которые были получены в ходе последнего действия
     *
     * @return string
     */
    public function getMessages()
    {
        return $this->_sMessages;
    }
}


/**
 * Класс с общими для файлов и директорий функциями
 */
class FSObject
{
    /**
     *
     * @var string Адрес
     *
     */
    protected $_sPath = '';

    /**
     *
     * @var array Массив с сообщениями
     *
     */
    protected $_aMessages = array();

    /**
     * Конструктор
     *
     * @param string $sPath
     */
    function __construct($sPath)
    {
        $this->setPath($sPath);
    }

    /**
     * Устанавливает адрес
     *
     * @param string $sPath
     *
     * @return object $this
     */
    public function setPath($sPath)
    {
        $this->_sPath = $sPath;
        return $this;
    }

    /**
     * Возвращает адрес
     *
     * @return string
     */
    public function getPath()
    {
        return $this->_sPath;
    }

    /**
     * Проверка существование
     *
     * @param string $sPath
     *
     * @return boolean
     */
    public function exists($sPath = '')
    {
        $bRet = file_exists($sPath ? $sPath : $this->_sPath);
        if (!$bRet) $this->_addMessage('Not exists: "' . $sPath . '"');
        return $bRet;
    }

    /**
     * Добавить сообщение
     *
     * @param string $sMessage
     *
     * @return object $this
     */
    protected function _addMessage($sMessage)
    {
        $this->_aMessages[] = $sMessage;
        return $this;
    }

    /**
     * Возвращает все сообщения
     *
     * @return string $this
     */
    public function getMessages()
    {
        return implode(';<br/> ', $this->_aMessages);
    }
}


/**
 * Класс для работы с директориями
 */
class Dir extends FSObject
{

    /**
     *
     * @var array Настройки
     *
     */
    protected $_aOptions = [];

    /**
     *
     * @var array Найденные через Scan файлы
     *
     */
    protected $_aDirs = [];

    /**
     *
     * @var array Найденные через Scan директории
     *
     */
    protected $_aFiles = [];


    /**
     * Проверка директории на запись
     *
     * @param string $sPath
     *
     * @return boolean
     */
    public function writable($sPath = '')
    {
        $bRet = is_writable($sPath ? $sPath : $this->_sPath);
        if (!$bRet) $this->_addMessage('Not writable: "' . $sPath . '"');
        return $bRet;
    }


    /**
     * Рекурсивное создание директории
     *
     * @param string $sPath
     * @param int $mode
     *
     * @return boolean
     */
    public function create($sPath = '', $mode = 0777)
    {
        $sPath = $sPath ? $sPath : $this->_sPath;
        if ($this->exists($sPath)) //  || !$this->writable($sPath)
        {
            return FALSE;
        } else {
            return mkdir($sPath, $mode, TRUE);
        }
    }


    /**
     * Изменяет настройки (Только переданные)
     *
     * @param array $aOptions
     *
     * @return object $this
     */
    public function changeOptions($aOptions)
    {
        $this->_aOptions = [];
        $this->_aOptions['ScanMask'] = '*';
        $this->_aOptions['ScanSubDirs'] = TRUE;
        $this->_aOptions['KeepFullNames'] = FALSE;

        foreach ($aOptions as $key => $val) {
            $this->_aOptions[$key] = $val;
        }

        return $this;
    }

    /**
     * Рекурсивный обход директории
     *
     * @param string $sPath
     * @param int $iLevel
     *
     * @return void
     */
    public function _scan($sPath, $iLevel = 0)
    {
        $aOptions = $this->_aOptions;

        foreach (glob(str_replace('//', '/', $sPath . '/' . $aOptions['ScanMask'])) as $sFullFileName) {
            $sFileName = str_replace($this->_sPath, '', $sFullFileName);

            if (is_dir($sFullFileName)) {
                if ($aOptions['ScanSubDirs']) {
                    $this->_scan($sFullFileName, ++$iLevel);
                }

                $this->_aDirs[] = $aOptions['KeepFullNames'] ? $sFullFileName : $sFileName;
            } else {
                $this->_aFiles[] = $aOptions['KeepFullNames'] ? $sFullFileName : $sFileName;
            }
        }
    }


    /**
     * Рекурсивный обход директории
     *
     * @param array $aOptions
     *
     * @return array
     */
    public function scan($aOptions = [])
    {
        $this->_aDirs = [];
        $this->_aFiles = [];
        $this->changeOptions($aOptions);
        $this->_scan($this->_sPath);

        //return array_merge($this->_aDirs, $this->_aFiles);
        return array('Dirs' => $this->_aDirs, 'Files' => $this->_aFiles);
    }

    /**
     * Рекурсивное копирование директории
     *
     * @param string $sPath
     * @param array $aOptions
     *
     * @return object $this
     */
    public function copyDir($sPath, $aOptions = [])
    {
        $this->_aDirs = [];
        $this->_aFiles = [];

        $aOptions['KeepFullNames'] = TRUE;

        $this->changeOptions($aOptions);
        $this->_scan($this->_sPath);


        foreach ($this->_aDirs as $sDir) {
            $sDir = str_replace($this->_sPath, $sPath, $sDir);
            $this->create($sDir);
        }

        $oFSO = new FSO();
        foreach ($this->_aFiles as $sFile) {
            $sFileTo = str_replace($this->_sPath, $sPath, $sFile);
            $oFSO->copyFile($sFile, $sFileTo);
        }

        $this->_sPath = $sPath;

        return $this;
    }


}


/**
 * Класс для работы с файлами
 */
class File extends FSObject
{

    /**
     * Возвращает расширение файла
     *
     * @return string
     */
    public function getExtension()
    {
        //$path_info = pathinfo($this->_sPath);
        //return $path_info['extension'];
        return substr(strrchr($this->_sPath, '.'), 1);
    }

    /**
     * Возвращает имя файла
     *
     * @return string
     */
    public function getName()
    {
        return basename($this->_sPath);
    }

    /**
     * Копирует файл
     *
     * @param string $sFileOut Адрес нового файла
     *
     * @return boolean
     */
    public function copy($sFileOut)
    {
        return copy($this->_sPath, $sFileOut);
    }

    /**
     * Удаление файла
     *
     * @return boolean
     */
    public function remove()
    {
        return unlink($this->_sPath);
    }

}