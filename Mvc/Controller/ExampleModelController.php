<?php


namespace Mvc\Controller;


use Mvc\Model\User;
use System\Database\Db;

class ExampleModelController
{
    public function exampleModel()
    {
        //fetchAll
        $alTablaData = User::getAll();

        //insert
        $insertData = new User();
        $insertData->name = "Example";
        $insertData->insert();

        //update
        $oneData = User::find(1);
        $oneData->name = "change";
        $oneData->update();

        //delete
        $oneData = User::find(1);
        $oneData->delete();

    }

}