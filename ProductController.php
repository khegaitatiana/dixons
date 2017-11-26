<?php
/**
 * User: Tatiana Khegai
 * Date: 26.11.17
 */

class ProductController {
    private $_dataFolderName = 'product';
    /**
     * @param string $id
     * @return string
     */
    public function detail($id)
    {
        $product = self::productCache($id);  //get product json
        self::numberOfRequestCache($id); //increase number of requests for product

        return $product;  //return json data of product
    }

    /**
     * @param string $id
     * @return string
     * @throws Exception
     */
    private function productCache($id)
    {
        $fileName = $this->_dataFolderName.'/product_'.$id.'.txt';  //product cache path
        $fileContents = @file_get_contents($fileName);  //get cached content of product
        if ($fileContents !== false) {  //if cache doesn't exist
            $elasticSearch = new ElasticSearch();  //class using interface IElasticSearchDriver
            $data = json_encode($elasticSearch->findById($id));  //get product array and convert to json
            if (@file_put_contents($fileName, $data) === false) {  //save json to cache file, throws exception if can't save
                throw new Exception('Cannot write to file: '.$fileName);
            }
            return $data;  //return product json
        }

        return $fileContents;  // return product json
    }

    /**
     * @param string $id
     * @throws Exception
     */
    private function numberOfRequestCache($id)
    {
        $fileName = $this->_dataFolderName.'/numberOfRequests_'.$id.'.txt';  //number of requests for product path
        $fileContents = @file_get_contents($fileName);  //get content of file
        if ($fileContents !== false) {  //if cache doesn't exist, create new data to store
            $numberOfRequestCache[$id] = 1;
        }
        else
        {
            $numberOfRequestCache = json_decode($fileContents);  //decode data from json to array
            $numberOfRequestCache[$id] = intval($numberOfRequestCache[$id]) + 1;  //increase number of requests
        }

        $data = json_encode($numberOfRequestCache);  //convert to json the number of requests per product
        if (@file_put_contents($fileName, $data) === false) { //save json to cache file, throws exception if can't save
            throw new Exception('Cannot write to file: '.$fileName);
        }
    }
} 