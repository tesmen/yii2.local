<?php

namespace app\traits;

trait ControllerTrait
{
    /**
     * @param null $key
     * @param null $default
     * @return array|null
     */
    private function getCookies($key = null, $default = null)
    {
//        $cookies = Application::instance()->getRequest()->cookies();
//
//        if (is_null($key)) {
//            return $cookies;
//        }
//
//        return isset($cookies[$key])
//            ? $cookies[$key]
//            : $default;
    }

    /**
     * @param null $key
     * @param null $value
     */
    private function setCookie($key, $value)
    {
//        Application::instance()->getResponse()->cookie(
//            $key, $value
//        );
    }

    /**
     * POST parameter(s)
     * @param null $key
     * @param null $default
     * @return array | mixed
     */
    private function getQueryParams($key = null, $default = null)
    {
        $post = \Yii::$app->request->get();

        if (is_null($key)) {
            return $post;
        }

        return isset($post[$key]) && !is_null($post[$key])
            ? $post[$key]
            : $default;
    }

    /**
     * POST parameter(s)
     * @param null $key
     * @param null $default
     * @return array | mixed
     */
    private function getRequestParams($key = null, $default = null)
    {
        $post = \Yii::$app->request->post();

        if (is_null($key)) {
            return $post;
        }

        return isset($post[$key]) && !is_null($post[$key])
            ? $post[$key]
            : $default;
    }


    private function getResult($value = [], $message = '', $success = true)
    {
        return [
            'data'    => $value,
            'success' => $success,
            'message' => $message,
        ];
    }


    /**
     * @param $res
     * @param string $message
     * @param array $data
     * @return bool
     */
    private function jsonActionResponse($res = true, $message = '', $data = [])
    {
        return $this->asJson($this->getResult($data, $message, $res));
//        return $this->setResponse($this->getResult($data, $message, (bool)$res));
    }

    /**
     * @param array $data
     * @param string $message
     * @return bool
     */
    private function jsonSuccessResponse($data = [], $message = '')
    {
        return $this->asJson($this->getResult($data, $message, true));
    }

    /**
     * @param string $message
     * @param array $data
     * @return bool
     */
    private function jsonFailureResponse($message = '', $data = [])
    {
        return $this->asJson($this->getResult($data, $message, false));
    }

    /**
     * @param string $templateName
     * @param array $data
     * @param string $layout
     * @return bool
     */
    private function renderResponse($templateName, $data = [], $layout = 'index')
    {
//        /** @var Controller $this */
//        $this->View->template($templateName);
//        $this->View->layout($layout);
//
//        $this->setResponse($data, true);
//
//        return true;
    }

    /**
     * @param $url
     * @return bool
     */
    private function redirectResponse($url)
    {
//        Application::instance()->redirect($url);

//        return true;
    }

    /**
     * @param string $fileName
     * @param array $rows array(array(string,string),array(string,string), [...])
     * @param string $delimiter
     * @param bool $addBom
     * @return bool
     */
    private function csvFileResponse($fileName, array $rows, $delimiter = ',', $addBom = true)
    {
        $this->forceDownload($fileName);
        $output = fopen('php://output', 'w');

        if ($addBom) {
            $BOM = chr(0xEF) . chr(0xBB) . chr(0xBF); // excel and others compatibility
            fputs($output, $BOM);
        }

        foreach ($rows as $row) {
            fputcsv($output, $row, $delimiter);
        }

        return $this->stopApplication();
    }

    /**
     * @return bool
     */
    private function stopApplication()
    {
        die;
    }

    private function forceDownload($baseFileName, $fileSize = false)
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $baseFileName . '"');
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        if ($fileSize) {
            header('Content-Length: ' . $fileSize);
        }
    }

    /**
     * @param $callback
     * @param $url
     * @param array $params
     * @deprecated
     */
    private static function addPageAction($callback, $url, $params = [])
    {
//        static::addPage($callback, $url, $params, ViewConfig::STD_VIEW);
    }

    /**
     * @param $callback
     * @param $url
     * @param $params
     */
    private static function addPostAction($callback, $url, $params = [])
    {
//        self::addAction(self::METHOD_POST, $callback, $url, $params, ViewConfig::JSON_VIEW);
    }

    /**
     * @param $callback
     * @param $url
     * @param $params
     */
    private static function addGetAction($callback, $url, $params = [])
    {
//        self::addAction(self::METHOD_GET, $callback, $url, $params, ViewConfig::JSON_VIEW);
    }

    /**
     * @return string | bool
     */
    private static function getClientIp()
    {
        return Server::getClientIp();
    }
}
