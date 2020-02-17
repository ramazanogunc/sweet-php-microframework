<?php

namespace System\Database;

use http\Exception\InvalidArgumentException;
use System\Core\FileRepository;

class Db
{
    private static $singleton = null;
    private $db = null;
    private $table = null;
    private $whereKey = null;
    private $whereOperator = null;
    private $where = null;
    private $column = null;
    private $model = null;


    public static function getInstance()
    {
        if (FileRepository::getGeneralInfo()->databaseActive === false){
            throw new \Error("Database is not Active in General.json");
        }
        else{
            if (self::$singleton == null)
                self::$singleton = new Db();
            return self::$singleton;
        }
    }
    private function connect()
    {
        if ($this->db == null)
        {
            $info = FileRepository::getDatabaseInfo();

            $this->db = new \PDO("mysql:host={$info->host};
            dbname={$info->database};
            charset={$info->charset}",
                $info->user,
                $info->password);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
    }

    private function close()
    {
        $this->db = null;
    }

    public function query($query)
    {
        self::connect();
        $result = self::$db->query($query);
        self::close();
        return $result;
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function column($array)
    {
        $dataKeyString = "";

        foreach ($array as $item){
            $dataKeyString .= $item.",";
        }
        $this->column = rtrim($dataKeyString,",");

        return $this;
    }

    public function where($key,$value,$operator = "=")
    {
        $this->whereKey = $key;
        $this->where = $value;
        $this->whereOperator = $operator;
        return $this;
    }

    public function model($modelName)
    {
        $this->model = $modelName;
        return $this;
    }

    private function reset()
    {
        $this->table = null;
        $this->whereKey = null;
        $this->where = null;
        $this->model = null;
    }

    private function getWhere()
    {
        if ( ($this->whereKey != null && $this->where == null)
            || ($this->whereKey == null && $this->where != null))
            throw new \Error("Where key or  where value is Not Found");

        if ($this->whereKey != null)
        {
            return "WHERE {$this->whereKey}{$this->whereOperator}'{$this->where}'";
        }
        return "";
    }

    public function getAll()
    {
        if ($this->column == null)
            $this->column = "*";
        if ($this->table == null)
            throw new \Error("Table Name is Not Found");

        $query = "SELECT {$this->column} FROM {$this->table} {$this->getWhere()}";

        $this->connect();
        $query = $this->db->query($query);
        if ($this->model == null)
            $query->setFetchMode(\PDO::FETCH_OBJ);
        else
            $query->setFetchMode( \PDO::FETCH_CLASS , $this->model);
        $data = $query->fetchAll();
        $this->reset();
        $this->close();
        return $data;


    }

    public function first()
    {
        if ($this->column == null)
            $this->column = "*";
        if ($this->table == null)
            throw new \Error("Table Name is Not Found");

        $query = "SELECT {$this->column} FROM {$this->table} {$this->getWhere()}";

        $this->connect();
        $query = $this->db->query($query);
        if ($this->model == null)
            $query->setFetchMode(\PDO::FETCH_OBJ);
        else
            $query->setFetchMode( \PDO::FETCH_CLASS , $this->model);
        $data = $query->fetch();
        $this->reset();
        $this->close();
        return $data;
    }

    public function insert($array)
    {
        if ($this->table == null)
            throw new \Error("Table Name is Not Found");

        $query = "INSERT INTO {$this->table}(";
        $valueString = "(";

        foreach ($array as $key => $value) {
            $query .= $key.",";
            $valueString .= "'{$value}',";
        }
        $query = rtrim($query,",");
        $valueString = rtrim($valueString,",");
        $query = $query.") VALUES {$valueString})";

        $this->connect();
        $success = $this->db->query($query);
        $this->reset();
        $this->close();
        return $success;
    }

    public function update($array)
    {
        if ($this->table == null)
            throw new \Error("Table Name is Not Found");

        $whereString = $this->getWhere();

        if ($whereString == "")
            throw new \Error("Where is not found");

        $query = "UPDATE {$this->table} SET ";
        $dataString = "";
        foreach ($array as $key => $value) {
            $dataString .= "{$key}='{$value}',";
        }
        $dataString = rtrim($dataString,",");
        $query =  $query. $dataString." ".$whereString;
        $this->connect();
        $success = $this->db->query($query);
        $this->close();
        $this->reset();
        return $success;

    }

    public function delete()
    {
        if ($this->table == null)
            throw new \Error("Table Name is Not Found");

        $query = "DELETE FROM {$this->table} ".$this->getWhere();

        $this->connect();
        $success = $this->db->query($query);
        $this->close();
        $this->reset();
    }







}