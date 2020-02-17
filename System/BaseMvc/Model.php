<?php

namespace System\BaseMvc;

use System\Database\Db;

abstract class Model
{
    protected $_table = null;
    protected $_primaryKey = null;

    public static function getAll()
    {
        $model = new static();
        if ($model->_table == null)
            throw new \Error(   '$__table name is not Found');

        return Db::getInstance()
            ->table($model->_table)
            ->model(get_called_class())
            ->getAll();
    }

    public static function find($id)
    {
        $model = new static();
        if ($model->_table == null)
            throw new \Error('$__table name is not Found');
        if ($model->_primaryKey == null)
            throw new \Error('$__primaryKey name is not Found');
        return Db::getInstance()
            ->table($model->_table)
            ->model(get_called_class())
            ->where($model->_primaryKey,$id)
            ->first();
    }

    public function insert()
    {
        $data = array();
        foreach ($this as $key => $value) {
            if ($key == "_table" || $key == "_primaryKey" )
                continue;
            $data[$key] = $value;
        }
        return Db::getInstance()
            ->table($this->_table)
            ->insert($data);
    }

    public function update()
    {

        $data = $this->objectToArray();
        $whereValue = $data[$this->_primaryKey];
        unset($data[$this->_primaryKey]);

        return Db::getInstance()
            ->table($this->_table)
            ->where($this->_primaryKey, $whereValue)
            ->update($data);
    }

    public function delete()
    {
        $data = $this->objectToArray();
        $whereValue = $data[$this->_primaryKey];
        return Db::getInstance()
            ->table($this->_table)
            ->where($this->_primaryKey,$whereValue)
            ->delete();
    }

    private function objectToArray()
    {
        $data = array();
        foreach ($this as $key => $value) {
            if ($key == "_table" || $key == "_primaryKey" )
                continue;
            $data[$key] = $value;
        }
        return $data;
    }
    
}
