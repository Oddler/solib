<?php

namespace Oddler\SOLib\classes;

/**
 * Класс Обертка для CURL
 *
 */
class curl
{
    /**
     *
     * @var Object CURL handler
     *
     */
    protected $_ch = NULL;

    /**
     *
     * @var array Options
     *
     */
    protected $_aOptions = [];

    /**
     *
     * @var array Errors
     *
     */
    protected $_aErrors = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_ch = curl_init();
        $this->_aOptions['POST'] = [];
        $this->_aOptions['HEADERS'] = [];
        $this->_aOptions['USER_AGENT'] = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1';
        $this->_aOptions['REFERER'] = '';
        $this->_aOptions['PROXY'] = '';
        $this->_aOptions['COOKIE_FILE'] = '';
        $this->_aOptions['COOKIE_RESET'] = FALSE;
    }

    protected function _getPage($sUrl)
    {
        $oRet = new \stdClass();
        $oRet->status = 'error';
        $oRet->result = '';
        $oRet->code = 0;

        $aOptions = array(
            CURLOPT_URL => $sUrl,
            CURLOPT_HEADER => FALSE,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 45,
            CURLOPT_VERBOSE => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
        );

        if (count($this->_aOptions['HEADERS'])) {
            $aOptions[CURLOPT_HTTPHEADER] = $this->_aOptions['HEADERS'];
        }

        if (count($this->_aOptions['POST'])) {
            $aOptions[CURLOPT_POST] = TRUE;
            //$data = http_build_query($data, '', '&');
            $aOptions[CURLOPT_POSTFIELDS] = $this->_aOptions['POST'];
        }

        if ($this->_aOptions['USER_AGENT']) {
            $aOptions[CURLOPT_USERAGENT] = $this->_aOptions['USER_AGENT'];
        }

        if ($this->_aOptions['REFERER']) {
            //curl_setopt($this->_ch, CURLOPT_REFERER, $this->_aOptions['REFERER']);
            $aOptions[CURLOPT_REFERER] = $this->_aOptions['REFERER'];
        }

        if ($this->_aOptions['PROXY']) {
            $aOptions[CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS5; // или CURLPROXY_SOCKS4
            $aOptions[CURLOPT_PROXY] = $this->_aOptions['PROXY'];
        }

        if ($this->_aOptions['COOKIE_FILE']) {
            $sCookieFile = $this->_aOptions['COOKIE_FILE'];
            if ($this->_aOptions['COOKIE_RESET']) {
                $handle = fopen($sCookieFile, "w");
                fclose($handle);
            }
            $aOptions[CURLOPT_COOKIEFILE] = $sCookieFile;
            $aOptions[CURLOPT_COOKIEJAR] = $sCookieFile;
        }


        //$ch = curl_init();
        curl_setopt_array($this->_ch, $aOptions);
        $response = curl_exec($this->_ch);
        $this->_aErrors[] = curl_error($this->_ch);

        if ($response) {
            $oRet->status = 'ok';
            $oRet->result = $response;
            //$oRet->result = json_decode($oRet->result, TRUE);
        } else {
            $oRet->status = 'error';
            $oRet->result = 'Curl error: ' . curl_error($this->_ch);
        }
        $oRet->code = curl_getinfo($this->_ch, CURLINFO_HTTP_CODE);

        //-- curl_close($this->_ch);

        return $oRet;
    }

    /**
     * Возвращает только текст  страницы
     *
     * @param string $sUrl
     *
     * @return string
     */
    public function getPageContent($sUrl)
    {
        $oRet = $this->_getPage($sUrl);

        return $oRet->status == 'ok' ? $oRet->result : '';
    }

    /**
     * Делает запрос к указанному адресу
     *
     * @param string $sUrl
     *
     * @return object
     */
    function request($sUrl)
    {
        return $this->_getPage($sUrl);
    }

    /**
     * Устанавливает значения которые будут переданы через POST
     *
     * @param array $aPost
     *
     * @return object $this
     */
    function setPost($aPost)
    {
        $this->_aOptions['POST'] = $aPost;
        return $this;
    }

    /**
     * Устанавливает значения которые будут переданы через HTTP HEADER
     *
     * @param array $aHeaders
     *
     * @return object $this
     */
    function setHeaders($aHeaders)
    {
        $this->_aOptions['HEADERS'] = $aHeaders;
        return $this;
    }

    /**
     * Меняет значение настройки
     *
     * @param string $sKey
     * @param mixed $Val
     *
     * @return object $this
     */
    function setOption($sKey, $Val)
    {
        $this->_aOptions[$sKey] = $Val;
        return $this;
    }

    /**
     * Закрывает текущее соединение
     *
     * @return object $this
     */
    public function free()
    {
        curl_close($this->_ch);
        return $this;
    }

    /**
     * Возвращает последнее сообщение об ошибке
     *
     * @return string
     */
    public function getLastError()
    {
        return end($this->_aErrors);
    }

    /**
     * Возвращает все сообщения об ошибках
     *
     * @return string
     */
    public function getErrors()
    {
        return implode('; ', $this->_aErrors);
    }


}