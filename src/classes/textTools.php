<?php
/**
 * Created by PhpStorm.
 * User: Oddler
 * Date: 19.07.2019
 * Time: 13:22
 */

namespace Oddler\SOLib\classes;


/**
* В отличии от string этот класс предназначен для работы, не с  $this->_text, а с переданным текстом
*/
class texttools
{
    /**
     * Делает первую букву в строке прописной
     *
     * @param string $sStr
     *
     * @return string
     */
    public function firstLetter2Lower($sStr)
    {
        $l = mb_strtolower(mb_substr($sStr, 0, 1));
        return $l . mb_substr($sStr, 1);
    }

    /**
     * Делает первую букву в строке заглавной
     *
     * @param string $sStr
     *
     * @return string
     */
    public function firstLetter2Upper($sStr)
    {
        $sTMP = mb_strtoupper(mb_substr($sStr, 0, 1));
        return $sTMP . mb_substr($sStr, 1);
    }

    /**
     * Возвращает красиво оформленную цену
     *
     * @param string $sPrice
     *
     * @return string
     */
    public function priceFormat($sPrice)
    {
        return number_format($sPrice, 2, '.', ' ' );
    }


    /**
     * Возвращает следующий день не являющийся выходным
     *
     * @return string
     * @throws \Exception
     */
    public function getNextWorkDate()
    {
        $i = 1;
        do
        {
            $date = new \DateTime();
            $date->add(new \DateInterval('P'.$i.'D'));
            $iNum = $date->format('N');

            $i++;
            if($i > 7)
            {
                break;
            }
        } while($iNum >= 6);

        return $date->format('d.m.Y');
    }

    /**
     * Возвращает сумму прописью
     * @param $num
     * @param null $translite
     * @return string
     */
    public function number2str($num, $translite=null)
    {
        $defaultTranslite = array(
            'null' => 'ноль',
            'a1' => array(1=>'один',2=>'два',3=>'три',4=>'четыре',5=>'пять',6=>'шесть',7=>'семь',8=>'восемь',9=>'девять'),
            'a2' => array(1=>'одна',2=>'две',3=>'три',4=>'четыре',5=>'пять',6=>'шесть',7=>'семь',8=>'восемь',9=>'девять'),
            'a10' => array(0=>'десять',1=>'одиннадцать',2=>'двенадцать',3=>'тринадцать',4=>'четырнадцать',5=>'пятнадцать',6=>'шестнадцать',7=>'семнадцать',8=>'восемнадцать',9=>'девятнадцать'),
            'a20' => array(2=>'двадцать',3=>'тридцать',4=>'сорок',5=>'пятьдесят',6=>'шестьдесят',7=>'семьдесят',8=>'восемьдесят',9=>'девяносто'),
            'a100' => array(1=>'сто',2=>'двести',3=>'триста',4=>'четыреста',5=>'пятьсот',6=>'шестьсот',7=>'семьсот',8=>'восемьсот',9=>'девятьсот'),
            'uc' => array('копейка', 'копейки', 'копеек'),
            'ur' => array('рубль', 'рубля', 'рублей'),
            'u3' => array('тысяча', 'тысячи', 'тысяч'),
            'u2' => array('миллион', 'миллиона', 'миллионов'),
            'u1' => array('миллиард', 'миллиарда', 'миллиардов'),
        );

        $translite = is_null($translite) ? $defaultTranslite : $translite;

        list($rub, $kop) = explode('.',sprintf("%015.2f", floatval($num)));
        $out = array();
        if (intval($rub) > 0)
        {
            // Разбиваем число по три символа
            $cRub = str_split($rub,3);

            foreach($cRub as $uk=>$v)
            {
                if (!intval($v)) continue;
                list($i1,$i2,$i3) = array_map('intval', str_split($v,1));

                $out[] = isset($translite['a100'][$i1]) ? $translite['a100'][$i1] : ''; // 1xx-9xx
                $ax = ($uk+1 == 3) ? 'a2' : 'a1';
                if ($i2 > 1)
                    @$out[]= $translite['a20'][$i2].' '.$translite[$ax][$i3]; // 20-99
                else
                    $out[]= $i2 > 0 ? $translite['a10'][$i3] : $translite[$ax][$i3]; // 10-19 | 1-9

                if (count($cRub) > $uk+1)
                {
                    $uName = $translite['u'.($uk+1)];
                    $out[]= $this->_morph($v,$uName);
                }
            }
        }
        else
        {
            $out[] = $translite['null'];
        }

        // Дописываем название "рубли"
        $out[] = $this->_morph(intval($rub),$translite['ur']); // rub
        // Дописываем название "копейка"
        $out[] = $kop.' '.$this->_morph($kop,$translite['uc']); // kop

        // Объединяем маcсив в строку
        $sText = join(' ',$out);

        // Удаляем лишние пробелы и возвращаем результат
        return trim(preg_replace('/ {2,}/', ' ', $sText));
    }

    /**
     * Склоняем словоформу
     * Возвращает окончания слова при склонении (https://oddler.ru/blog/i1016)
     *
     * Функция возвращает окончание слова, в зависимости от примененного к ней числа
     * Например: 5 товаров, 1 товар, 3 товара
     *
     * @param int $number - число, к которому необходимо применить склонение
     * @param array $titles - массив возможных окончаний
     *
     * @return mixed
     */
    private function _morph($number, $titles)
    {
        $cases = array (2, 0, 1, 1, 1, 2);
        return $titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ];
    }

    /**
     * Чистка HTML
     *
     * @param string $sText
     *
     * @return string
     */
    public function clearHTML($sText)
    {
        $sText = preg_replace([
            '|style="(.*?)"|si',
            '|<a .*?>(.*?)</a>|si',
            '|class="(.*?)"|si',
            '|class=\'(.*?)\'|si',
            '|(onClick=\'.*?\')|si',
            '|(onClick=".*?")|si',
            '|<div.*?>|si',
            '|(<style>.*?</style>)|si',
        ], [
            '',
            '$1',
            '',
            '',
            '',
            '',
            '<p>',
            '',
        ], $sText);

        //$sText = str_replace('<div>', '<p>', $sText);
        $sText = str_replace('</div>', '</p>', $sText);
        $sText = str_replace('<p></p>', '', $sText);
        return trim($sText);
    }

    /**
     * toLatin
     *
     * @param string $sText
     *
     * @return string
     */
    public function toLatin($sText) {
        //$sText = iconv('windows-1251', 'utf-8', $sText);
        $sText = strtolower($sText);
        // Коррекция первого символа
        $num = intval($sText);
        if (!empty($num))
            $sText = '_' . $sText;

        $sText = str_replace("&quot;", "", $sText);
        $sText = str_replace("&nbsp;", "", $sText);
        $sText = str_replace("/", "-", $sText); // Добавлено для SeoPro
        $sText = str_replace("\\", "", $sText);
        $sText = str_replace("(", "", $sText);
        $sText = str_replace(")", "", $sText);
        $sText = str_replace(":", "", $sText);
        //$sText = str_replace("-", "", $sText); // Добавлено для SeoPro
        $sText = str_replace(" ", "_", $sText);
        $sText = str_replace("!", "", $sText);
        $sText = str_replace("|", "_", $sText);
        $sText = str_replace(".", "_", $sText);
        $sText = str_replace("№", "N", $sText);
        $sText = str_replace("?", "", $sText);
        $sText = str_replace("&nbsp", "_", $sText);
        $sText = str_replace("&amp;", '_', $sText);
        $sText = str_replace("ь", "", $sText);
        $sText = str_replace("Ь", "", $sText);
        $sText = str_replace("ъ", "", $sText);
        $sText = str_replace("«", "", $sText);
        $sText = str_replace("»", "", $sText);
        $sText = str_replace("“", "", $sText);
        $sText = str_replace(",", "", $sText);
        $sText = str_replace("™", "", $sText);
        $sText = str_replace("’", "", $sText);
        $sText = str_replace("®", "", $sText);
        $sText = str_replace(array('&#43;', '&#43'), '+', $sText);

        $new_str = '';
        $_Array = array(" " => "_", "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ё" => "e", "ж" => "zh", "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "i", "ы" => "y", "ь" => "i", "э" => "e", "ю" => "u", "я" => "ya", "А" => "a", "Б" => "b", "В" => "v", "Г" => "g", "Д" => "d", "Е" => "e", "Ё" => "e", "Ж" => "zh", "З" => "z", "И" => "i", "Й" => "y", "К" => "k", "Л" => "l", "М" => "m", "Н" => "n", "О" => "o", "П" => "p", "Р" => "r", "С" => "s", "Т" => "t", "Ы" => "Y", "У" => "u", "Ф" => "f", "Х" => "h", "Ц" => "c", "Ч" => "ch", "Ш" => "sh", "Щ" => "sch", "Э" => "e", "Ю" => "u", "Я" => "ya", "." => "_", "$" => "i", "%" => "i", "&" => "_and_");

        $chars = preg_split('//u', $sText, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($chars as $val)
            if (empty($_Array[$val]))
                $new_str.=$val;
            else
                $new_str.=$_Array[$val];
        return preg_replace('([^a-z0-9/_\.-])', '', $new_str);
    }


}