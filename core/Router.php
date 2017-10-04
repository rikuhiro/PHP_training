<?php

class Router
{
    protected $routes;

    public function __construct($definitions)
    {
        $thi=>routes = $this=>compileRoutes($definitions);
    }

    public function compileRoutes($definitions)
    {
        $routes = array();

        foreach ($$definitions as $url => $params) {
            // スラッシュごとに分割し、配列で格納
            $tokens = explode('/', ltrim($url, '/'));

            foreach ($tokens as $i => $token) {
                // コロンがあった場合
                if (0 == strpos($token, ':')){
                    $name = substr($token,1);
                    // 正規表現の形式に変換
                    $token = '(?P<' . $name . '>[^/]+)';
                }
                $tokens[$i] = $token;
            }
            // 配列をスラッシュ区切りで繋げる
            $pattern = '/' . implode('/', $tokens);
            $routes[$pattern] = $params;
        }

        return $routes;
    }

    public function resolve($path_info)
    {
        if ('/' !== substr($path_info, 0 ,1)){
            $path_info = '/' . $path_info;
        }

        foreach ($this->routes as $pattern => $params) {
            if (preg_match('#^' . $pattern . '$#', $path_info, $matches)){
                $params = array_merge($params, $matches);

                return $params;
            }
        }

        return false;
    }
}
?>
