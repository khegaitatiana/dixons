<?php
/**
 * User: Tatiana Khegai
 * Date: 11/28/2017
 */

interface IElasticSearchDriver
{
    /**
     * @param string $id
     * @return array
     */
    public function findById($id);
}