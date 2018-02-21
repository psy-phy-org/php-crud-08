<?php

class DbConnect
{
    private $_user = "root";
    private $_password = "root";
    private $_dns = "mysql:dbname=crud_08;host=localhost;charset=utf8";
    private $_options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    protected $dbh;

    public function __construct()
    {
        try {
            $this->dbh = new PDO($this->_dns, $this->_user, $this->_password, $this->_options);
            return $this->dbh;
        } catch (PDOException $e) {
            echo 'ERR! : ' . $e->getMessage();
            return false;
        } finally {
            return true;
        }
    }
}
