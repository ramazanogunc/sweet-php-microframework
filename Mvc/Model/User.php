<?php

namespace Mvc\Model;

use System\BaseMvc\Model;
/*
 * example model class
 */
class User extends Model
{
    //example table name
    protected $_table = "users";
    //example primary key
    protected $_primaryKey = "userId";
}
