<?php

namespace Oddler\SOLib\classes;

/**
 * Базовый класс для работы с изображениями
 *
 */
class image
{
    /**
     * Картинка с которой работаем
     *
     * @var resource
     *
     */
    protected $_img = NULL;

    /**
     * Характеристики картинки:
     *
     *
     * @var array
     *
     */
    protected $_params = array();

    /**
     * Цвета, которые можно использовать на картинке
     *
     * @var array
     *
     */
    protected $_colors = array();


    //------------------------------------------------

    /**
     * Бросает исключение в случаи ошибки
     *
     * @param string $sText
     *
     * @throws \Exception
     */
    protected function _throwError($sText)
    {
        throw new \Exception($sText);
    }

    /**
     * Возвращает тип в "человекочитаемом виде" по Id
     *
     * (!!!) - зачем, если есть mime?
     *
     * @param int $id
     *
     * @return string
     */
    /*
        public function getTypeById($id)
        {
          $sRet = 'undefined';
          switch ($Num)
          {
            case 1:
              $sRet = 'GIF';
            break;

            case 2:
              $sRet = 'JPG';
            break;

            case 3:
              $sRet = 'PNG';
            break;

            case 4:
              $sRet = 'SWF';
            break;

            case 5:
              $sRet = 'PSD';
            break;

            case 6:
              $sRet = 'BMP';
            break;

            case 7:
              $sRet = 'TIFF(intel byte order)';
            break;

            case 8:
              $sRet = 'TIFF(motorola byte order)';
            break;

            case 9:
              $sRet = 'JPC';
            break;

            case 10:
              $sRet = 'JP2';
            break;

            case 11:
              $sRet = 'JPX';
            break;

            case 12:
              $sRet = 'JB2';
            break;

            case 13:
              $sRet = 'SWC';
            break;

            case 14:
              $sRet = 'IFF';
            break;

            case 15:
              $sRet = 'WBMP';
            break;

            case 16:
              $sRet = 'XBM';
            break;
          }

          return $sRet;
        }*/

    /**
     * Создаем пустую картинку нужного размера и заливаем ее цветом
     *
     * @param string $sType
     * @param int $width
     * @param int $height
     * @param string $BGcolor
     *
     * @return $this
     * @throws \Exception
     */
    public function create($sType, $width, $height, $BGcolor)
    {
        $this->_params = array();

        $this->setType($sType);
        $this->_params['width'] = $width;
        $this->_params['height'] = $height;

        // create image
        $this->_img = ImageCreateTrueColor($width, $height);

        $this->addHexColor('bg', $BGcolor);

        ImageFill($this->_img, 0, 0, $this->_colors['bg']);

        return $this;
    }

    /**
     * Загружаем картинку
     *
     * @param string $sFile
     *
     * @return object this
     * @throws \Exception
     */
    public function load($sFile)
    {
        if (!is_file($sFile)) {
            $this->_throwError('Not File: "' . $sFile . '"');
        }

        /*
        echo '<pre>';
        print_r(GetImageSize($sFile));
        echo '</pre>';

        * [0] => 1600			- $width
        * [1] => 1200			- $height
        * [2] => 2				- $type
        * [3] => width="1600" height="1200"
        * [bits] => 8
        * [channels] => 3
        * [mime] => image/jpeg
        */

        $aTmp = getImageSize($sFile);

        //$sType = $this->_getTypeById( $aTmp[2] );

        $this->_params = array();
        $this->_params['file'] = $sFile;
        $this->_params['width'] = $aTmp[0];
        $this->_params['height'] = $aTmp[1];
        //$this->_params['type']	= $sType;
        $this->_params['mime'] = $aTmp['mime'];


        switch ($this->_params['mime']) {
            case 'image/jpeg':
                $this->_img = ImageCreateFromJpeg($sFile);
                break;

            case 'image/gif':
                $this->_img = ImageCreateFromGif($sFile);
                break;

            case 'image/png':
                $this->_img = ImageCreateFromPng($sFile);
                break;

            default:
                $this->_throwError('Wrong ' . $this->_params['mime'] . ' in (should be JPEG, PNG or GIF). File: "' . $sFile . '"');
                break;
        }

        return $this;
    }

    /**
     * Сохраняет картинку в файл
     *
     * @param string $sFile
     * @param boolean $bFinal - если TRUE, то ресурс будет уничтожен
     *
     * @return object this
     * @throws \Exception
     */
    public function save($sFile, $bFinal = TRUE)
    {
        switch ($this->_params['mime']) {
            case 'image/jpeg':
                ImageJPEG($this->_img, $sFile, 75);
                break;

            case 'image/png':
                ImagePNG($this->_img, $sFile);
                break;

            case 'image/gif':
                ImageGIF($this->_img, $sFile);
                break;

            default:
                $this->_throwError('Wrong ' . $this->_params['mime'] . ' in (should be JPEG, PNG or GIF)');
                break;
        }

        if ($bFinal) {
            ImageDestroy($this->_img);
        }

        return $this;
    }

    /**
     * Рисует картинку. Выдает ее в буфер вывода.
     *
     * @param boolean $bFinal - если TRUE, то ресурс будет уничтожен
     *
     * @throws \Exception
     */
    public function draw($bFinal = TRUE)
    {
        switch ($this->_params['mime']) {
            case 'image/jpeg':
                Header('Content-Type: ' . $this->_params['mime']);
                ImageJPEG($this->_img);
                break;

            case 'image/png':
                Header('Content-Type: ' . $this->_params['mime']);
                ImagePNG($this->_img);
                break;

            case 'image/gif':
                Header('Content-Type: ' . $this->_params['mime']);
                ImageGIF($this->_img);
                break;

            default:
                $this->_throwError('Wrong ' . $this->_params['mime'] . ' in (should be JPEG, PNG or GIF)');
                break;
        }

        if ($bFinal) {
            ImageDestroy($this->_img);
        }
    }


    /**
     * Создание уменьшенного изображения
     *
     * @param int $iMaxW - Максимальная ширина
     * @param int $iMaxH - Максимальная высота
     *
     * @return $this
     */
    public function createThumb($iMaxW, $iMaxH = 0)
    {
        $newH = $this->_params['height'];
        if ($this->_params['height'] > $iMaxH) {
            $newH = $iMaxH;
        }

        $newW = $this->_params['width'];
        if ($this->_params['width'] > $iMaxW) {
            $newW = $iMaxW;
            $newH = (int)round(($newW * $this->_params['height']) / $this->_params['width']);
        }

        $image_tmp = ImageCreateTrueColor($newW, $newH);

        // Решение проблемы с черной обводкой, при конвертации PNG с прозрачным фоном
        imageAlphaBlending($image_tmp, false);
        imageSaveAlpha($image_tmp, true);

        ImageCopyResampled($image_tmp, $this->_img, 0, 0, 0, 0, $newW, $newH, $this->_params['width'], $this->_params['height']);
        $this->_img = $image_tmp;

        return $this;
    }

    /**
     * Меняем тип картинки
     *
     * @param int $sType - новый тип: jpg, png, gif
     *
     * @return object this
     * @throws \Exception
     */
    public function setType($sType)
    {
        switch ($sType) {
            case 'jpg':
                $this->_params['mime'] = 'image/jpeg';
                break;

            case 'png':
                $this->_params['mime'] = 'image/png';
                break;

            case 'gif':
                $this->_params['mime'] = 'image/gif';
                break;

            default:
                $this->_throwError('Wrong type ' . $sType . ' should be jpg, png OR gif');
                break;
        }

        return $this;
    }


    /**
     * Добавляем в палитру цвет в формате RGB
     *
     * @param string $name
     * @param array $aRGBColor
     *
     * @return $this;
     */
    public function addRGBColor($name, $aRGBColor)
    {
        $this->_colors[$name] = ImageColorAllocate($this->_img, $aRGBColor[0], $aRGBColor[1], $aRGBColor[2]);
        return $this;
    }

    /**
     * Добавляем в палитру цвет в формате HEX
     *
     * @param string $name
     * @param string $HexColor
     *
     * @return $this;
     * @throws \Exception
     */
    public function addHexColor($name, $HexColor)
    {
        $this->addRGBColor($name, $this->_hex2rgb($HexColor));
        return $this;
    }

    /**
     * Перемодим HEX в RGB
     *
     * @param string $hex
     *
     * @return array
     * @throws \Exception
     */
    protected function _hex2rgb($hex)
    {
        $hex = preg_replace('/[^a-fA-F0-9]/', '', $hex);
        $rgb = array();
        if (strlen($hex) == 3) {
            $rgb[0] = hexdec($hex[0] . $hex[0]);
            $rgb[1] = hexdec($hex[1] . $hex[1]);
            $rgb[2] = hexdec($hex[2] . $hex[2]);
        } elseif (strlen($hex) == 6) {
            $rgb[0] = hexdec($hex[0] . $hex[1]);
            $rgb[1] = hexdec($hex[2] . $hex[3]);
            $rgb[2] = hexdec($hex[4] . $hex[5]);
        } else {
            $this->_throwError('ERR: Incorrect colorcode, expecting 3 or 6 chars (a-f, A-F, 0-9)');
        }

        return $rgb;
    }

    /**
     * Возвращает картинку (ресурс)
     *
     * @return resource
     */
    public function getPicture()
    {
        return $this->_img;
    }

    /**
     * Возвращает параметры изображения
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

}


// ==============================================================
// ==============================================================

/**
 * Расширенный класс для работы с изображениями
 */
class graphic extends image
{
    /**
     * Считает новый размер для картинки
     *
     * @param int $iMaxW
     * @param int $iMaxH
     *
     * @return array
     */
    public function calcNewSize($iMaxW, $iMaxH = 0)
    {
        $newW = $this->_params['width'];
        $newH = $this->_params['height'];

        if ($newW > $newH) {
            if ($newW > $iMaxH) {
                $percent = round(($iMaxW * 100) / ($newW));
                $newW = $iMaxW;
                $newH = round(($percent * $newH) / (100));
            }
        } else {
            if ($newH > $iMaxH) {
                $percent = round(($iMaxH * 100) / ($newH));
                $newH = $iMaxH;
                $newW = round(($percent * $newW) / (100));
            }
        }

        $aRet = array();
        $aRet['width'] = $newW;
        $aRet['height'] = $newH;

        return $aRet;
    }

    /**
     * Обрезаем картинку
     *
     * @param int $x
     * @param int $y
     * @param int $iWidth
     * @param int $iHeight
     *
     * @return $this;
     */
    public function crop($x, $y, $iWidth, $iHeight)
    {
        $image_tmp = ImageCreateTrueColor($iWidth, $iHeight);
        ImageCopyResampled($image_tmp, $this->_img,
            0, 0, $x, $y, $this->_params['width'], $this->_params['height'],
            $iWidth, $iHeight
        );
        $this->_img = $image_tmp;
        return $this;
    }


    /**
     * Выводит текст на картинку
     *
     * @param string $text
     * @param int $height
     * @param int $x
     * @param int $y
     * @param string $HexColor
     * @param float $angle
     * @param string $FontFile
     * @param boolean $CRLF_after
     *
     * @return $this
     * @throws \Exception
     */
    public function text($text, $height = 25, $x = 0, $y = 0, $HexColor = 'FFFF00', $angle = 0.0, $FontFile = '', $CRLF_after = FALSE)
    {
        if ($HexColor) {
            $this->addHexColor('text', $HexColor);
        }

        $FontFile = $FontFile ? $FontFile : __DIR__ . '/fonts/font1.ttf';
        if (!file_exists($FontFile)) {
            $this->_throwError('No font file: ' . $FontFile);
        }

        if ($CRLF_after) {
            $aLines = array();
            if (strlen($text) > $CRLF_after) {
                $aLines[] = substr($text, 0, $CRLF_after);
                $aLines[] = substr($text, $CRLF_after, strlen($text));
            } else {
                $aLines[] = $text;
            }

            $i = 1;
            foreach ($aLines as $Line) {
                imageTTFText($this->_img, $height, $angle, $x, $y + $height * $i, $this->_colors['text'], $FontFile, $Line);
                $i++;
            }
        } else {
            ImageTTFText($this->_img, $height, $angle, $x, $y + $height, $this->_colors['text'], $FontFile, $text);
        }

        return $this;
    }

    /**
     *
     * Выводит текст на картинку снизу.
     *
     * @param string $text
     * @param int $height
     * @param int $x
     * @param int $y
     * @param string $HexColor
     * @param float $angle
     * @param string $FontFile
     * @return $this
     * @throws \Exception
     */
    public function textOnBottom($text, $height = 0, $x = 0, $y = 0, $HexColor = 'FFFF00', $angle = 0.0, $FontFile = '')
    {
        $height = $height ? $height : round($this->_params['width'] / 13);
        $this->text($text, $height, $x, $this->_params['height'] - $height - 10 + $y, $HexColor, $angle, $FontFile);

        return $this;
    }

    /**
     * Делает рамку вокруг (по границам) картинки
     *
     * @param int $Thickness - толщина
     * @param string $HexColor - цвет
     *
     * @return $this
     * @throws \Exception
     */
    public function borders($Thickness = 1, $HexColor = 'FF0000')
    {
        $this->addHexColor('border', $HexColor);

        ImageSetThickness($this->_img, $Thickness);
        ImageLine($this->_img, 0, 0, 0, $this->_params['height'], $this->_colors['border']); // left
        ImageLine($this->_img, 0, 0, $this->_params['width'], 0, $this->_colors['border']); // top
        ImageLine($this->_img, 0, $this->_params['height'] - 1, $this->_params['width'], $this->_params['height'] - 1, $this->_colors['border']); // bottom
        ImageLine($this->_img, $this->_params['width'] - 1, 0, $this->_params['width'] - 1, $this->_params['height'] - 1, $this->_colors['border']); // right

        return $this;
    }

    /**
     * Рисует круг
     *
     * @param int $x
     * @param int $y
     * @param int $width
     * @param int $height
     * @param string $bgHexColor
     * @param int $start
     * @param int $end
     *
     * @return object this
     * @throws \Exception
     */
    public function circle($x, $y, $width, $height = 0, $bgHexColor = 'FF00FF', $start = 0, $end = 360)
    {
        $this->addHexColor('bg_circle', $bgHexColor);
        $height = $height ? $height : $width;
        imagefilledarc($this->_img, $x, $y, $width, $height, $start, $end, $this->_colors['bg_circle'], IMG_ARC_PIE);

        return $this;
    }

    /**
     * Добавление водяного знака
     *
     * @param string $FN
     * @param int $x
     * @param int $y
     * @param int $alpha
     *
     * @return $this;
     * @throws \Exception
     */
    public function addWatermark($FN, $x = 0, $y = 0, $alpha = 100)
    {
        $o = new image();
        $o->load($FN);

        $tmpImg = $o->getPicture();
        $aParams = $o->getParams();

        ImageCopyMerge($this->_img, $tmpImg, $x, $y, 0, 0, $aParams['width'], $aParams['height'], $alpha);

        return $this;
    }

}
