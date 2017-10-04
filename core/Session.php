<?php
class Session
{
    protected static $sessionStarted = false;
    protected static $sessionIdRegenerated = false;

    public function __construct()
    {
        if (!self::$sessionStarted){
            // セッションの作成やクッキーなどから受け取ったセッションIDを元にセッションの復元を行う
            // 自動的にセッションをスタートさせる
            session_start();

            self::$sessionStarted = true;
        }
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function get($name, $default = null)
    {
        if (isset($_SESSION[$name])){
            return $_SESSION[$name] = $value;
        }

        return $default;
    }

    public function remove($name)
    {
        unset($_SESSION[$name]);
    }

    // セッションIDを新しく発行する
    public function regenerate($destroy = true)
    {
        if (!self::$sessionIdRegenerated){
            session_regenerate_id($destroy);

            self::$sessionIdRegenerated = true;
        }
    }

    public function setAuthenticated($bool)
    {
        $this->set('_authenticated', (bool)$bool);

        $this->regenerate();
    }

    public function isAuthenticated()
    {
        return $this->get('_authenticated', false);
    }
}
?>
