<?php
calass Request
{
    public function isPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            return true;
        }

        return false;
    }

    public function getGet($name, $defalt = null)
    {
        if(isset($_GET[$name])){
            return $_GET[$name];
        }
        return $defalt;
    }

    public function getPost($name, $defalt = null)
    {
        if(isset($_POST[$name])){
            return $_POST[$name];
        }
        return $defalt;
    }

    public function getHost()
    {
        if (!empty($_SERVER['HTTP_HOST'])){
            return $_SERVER['HTTP_HOST'];
        }

        return $_SERVER['SERVER_NAME'];
    }

    public function isSsl()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
            return true;
        }

        return false;
    }

    public function getRequestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    // ベースURLの特定
    public function getBaseUrl()
    {
        $script_name = $_SERVER['SCRIPT_NAME'];

        $request_uri = $this->getRequestUri();

        // 文字列チェックstrpos()
        if(0 === strpos($request_uri, $script_name)){
            // ベースURL === $_SERVER['SCRIPT_NAME']の場合、$_SERVER['SCRIPT_NAME']
            return $script_name;
        } else if(0 === strpos($request_uri, dirname($script_name))){
            return rtrim(dirname($script_name), '/');
        }
        return ' ';
    }

    public function getPathInfo()
    {
        $base_url = $this->getBaseUrl();
        $request_uri = $this->getRequestUri();

        if(false !== ($pos = strpos($request_uri, '?'))){
            $request_uri = substr($request_uri, 0, $pos);
        }

        $path_info = (string)substr($request_uri, strlen($base_url));

        return $path_info;
    }
}
?>
