<?php

class DBMANAGER
{
    protected $connections = array();
    protected $repository_connection_map = array();
    protected $repositories = array();

    // 接続情報の管理を行う処理
    public function connect($name, $params)
    {
        $params = array_merge(array(
            'dsn'       =>null,
            'user'      =>'',
            'password'  =>'',
            'options'   => array()
        ), $params);

        $con = new PDO(
            $params['dsn'],
            $params['user'],
            $params['password'],
            $params['options']
        );

        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->connections[$name] = $con;
    }

    public function getConnection($name = null)
    {
        if (is_null($name)){
            return current($this->connections);
        }
        return $this->connections[$name];
    }

    // 接続情報のマッピング
    public function setRepositoryConnectionMap($repository_name, $name)
    {
        $this->repository_connection_map[$repository_name] = $name;
    }

    public function getConnectionForRepository($repository_name)
    {
        if (isset($this->repository_connection_map[$repository_name])){
            $name = $this->repository_connection_map[$repository_name];
            $con = $this->getConnection($name);
        }else{
            $con = $this->getConnection();
        }
        return $con;
    }

    // Repositoryクラスの管理を行う処理
    public function get($repository_name)
    {
        if(!isset($this->repositories[$repository_name])){
            // Repositoryのクラス名を指定
            $repository_class = $repository_name . 'Repository';
            // コネクションを取得
            $con = $this->getConnectionForRepository($repository_name);

            $repository = new $repository_class($con);
            // 作成したインスタンスを格納
            $this->repositories[$repository_name] = $repository;
        }

        return $this->repositories[$repository_name];
    }

    // データベースを閉じる処理
    public function __destruct()
    {
        foreach ($this->repositories as $repository) {
            unset($repository);
        }

        foreach ($this->connections as $con) {
            unset($con);
        }
    }

}
?>
