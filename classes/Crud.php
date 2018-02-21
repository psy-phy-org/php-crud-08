<?php

require_once('DbConnect.php');

class Crud extends DbConnect
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getData($sql)
    {
        $sth = $this->executeSQL($sql, null);
        foreach ($rows = $sth->fetchAll() as $row) {
            return $rows;
        }
    }

    public function delete($id, $table)
    {
        $sql = "DELETE FROM $table WHERE id=?";
        $array = array($id);
        $this->executeSQL($sql, $array);
        return true;
    }

    public function executeSQL($sql, $array)
    {
        try {
            $sth = $this->dbh->prepare($sql);
            $sth->execute($array);
            return $sth;
        } catch (PDOException $e) {
            echo 'ERR! : ' . $e->getMessage();
            return false;
        } finally {
            $this->dbh = null;
        }
    }

    public function h($str, string $charset = 'UTF-8'): string
    {
        return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, $charset);
    }
}
