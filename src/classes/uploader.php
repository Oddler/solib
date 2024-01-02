<?php

namespace Oddler\SOLib\classes;

/**
 * Загрузчик файлов
 */
class uploader
{
    /**
     *
     * @var string Сообщение об ошибке
     *
     */
    protected $_sError = '';

    /**
     *
     * @var string Название поля с файлом
     *
     */
    protected $_sName = '';

    /**
     *
     * @var string Массив с файлами
     *
     */
    protected $_aFiles = [];

    /**
     *
     * @var string Массив с разрешенными расширениями файлов
     *
     */
    protected $_sAllowedExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'pdf', 'xls', 'xlsx', 'doc', 'docx', 'zip', 'rar'];


    /**
     * Constructor
     *
     * @param string $sName Название поля с файлом
     */
    public function __construct($sName)
    {
        $this->_sName = $sName;
        $this->_aFiles = $this->_normalize_files_array($_FILES);
    }


    /**
     * Есть проблема, что пришедший массив может быть в разных форматах.
     * Этот метод приводит их к одному виду.
     *
     * @param array $aFiles
     *
     * @return array
     */
    protected function _normalize_files_array($aFiles)
    {
        /*
            <input type="file" name="myFile1" />
            <input type="file" name="jform[myFile2]" />

        Array
        (
            [myFile1] => Array
                (
                    [name] => big_01.jpg
                    [type] => image/jpeg
                    [tmp_name] => /home/public/sky1.4kkk.ru/tmp/phpyMCEzs
                    [error] => 0
                    [size] => 139989
                )

            [jform] => Array
                (
                    [name] => Array
                        (
                            [myFile2] => med_01.jpg
                        )

                    [type] => Array
                        (
                            [myFile2] => image/jpeg
                        )

                    [tmp_name] => Array
                        (
                            [myFile2] => /home/public/sky1.4kkk.ru/tmp/php1RhNDj
                        )

                    [error] => Array
                        (
                            [myFile2] => 0
                        )

                    [size] => Array
                        (
                            [myFile2] => 36488
                        )

                )

        )
        */
        $aNormalized = [];

        foreach ($aFiles as $index => $file) {
            if (!is_array($file['name'])) {
                //$aNormalized[$index][] = $file;
                $aNormalized[$index] = $file;
                continue;
            }

            foreach ($file['name'] as $idx => $name) {
                $aNormalized[$idx] = [
                    'name' => $name,
                    'type' => $file['type'][$idx],
                    'tmp_name' => $file['tmp_name'][$idx],
                    'error' => $file['error'][$idx],
                    'size' => $file['size'][$idx]
                ];
            }
        }

        return $aNormalized;
    }


    /**
     * Производит загрузку файла
     *
     * @param string $sToPath Куда
     * @param string $sNewFileName Новое имя
     *
     * @return boolean
     */
    function upload($sToPath, $sNewFileName = '')
    {
        $bRet = FALSE;

        $aFile = $this->_aFiles[$this->_sName];

        $sNewFileName = $sNewFileName ? $sNewFileName : $aFile['name'];

        $this->_sError = '';
        if ($aFile['name'] != '') {
            if (isset($aFile) and !$aFile['error']) {
                if (!copy($aFile['tmp_name'], $sToPath . $sNewFileName)) {
                    $this->_sError = 'Cant copy file: "' . $sToPath . $sNewFileName . '"';
                } else {
                    $this->_sError = "Done";
                    $bRet = TRUE;
                }
            } else {
                $this->_sError = "Error copy file. Code: " . $aFile['error'];
            }
        }

        return $bRet;
    }

    /**
     * Возвращает сообщение об ошибке
     *
     * @return string
     */
    public function getError()
    {
        return $this->_sError;
    }

    /**
     * Возвращает расширение файла
     *
     * @return string
     */
    public function getExtension()
    {
        return substr(strrchr($this->getName(), '.'), 1);
    }

    /**
     * Возвращает имя файла
     *
     * @return string
     */
    public function getName()
    {
        return $this->_aFiles[$this->_sName]['name'];
    }

    /**
     * Возвращает TRUE если файл можно загрузить
     *
     * @return boolean
     */
    public function canUpload()
    {
        $bRet = FALSE;
        /*
              echo '<pre>';
              print_r($this->_aFiles);
              echo '</pre>';
        /*/
        if (isset($this->_aFiles[$this->_sName]['name']) && $this->_aFiles[$this->_sName]['name']) {
            $bRet = TRUE;
        }

        return $bRet;
    }

    public function checkAllowed()
    {
        $bRet = true;

        $sExtension = $this->getExtension();
        if (!in_array($sExtension, $this->_sAllowedExtensions)) {
            $this->_sError = 'Вы загрузили неподдерживаемый формат: ' . $sExtension;
            $bRet = false;
        }

        return $bRet;
    }
}
