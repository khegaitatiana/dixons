<?php
/**
 * User: Tatiana Khegai
 * Date: 11/28/2017
 */
include_once("IElasticSearchDriver.php");

class ElasticSearch implements IElasticSearchDriver
{
    public function findById($id)
    {
        // TODO: Implement findById() method.

        //test data
        return array("id"=>1);
    }

}