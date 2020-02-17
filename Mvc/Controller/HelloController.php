<?php
namespace Mvc\Controller;

use Mvc\Model\User;
use System\BaseMvc\Controller;
use System\Database\Db;

/*
 * example controller class
 */
class HelloController extends Controller
{
    public function hello()
    {
        $this->render("hello");
    }

}
