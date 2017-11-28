<?php
/**
 * User: Tatiana Khegai
 * Date: 26.11.17
 */
include_once("ElasticSearch.php");

class ProductController {
    private $_dataFolderName = 'cache/product';
    private $productId;

    /**
     * ProductController constructor.
     * @param integer $productId
     */
    public function __construct($productId)
    {
        $this->productId = $productId;

        //check if cacha folder exists. Create if needed
        if (!is_dir($this->_dataFolderName)) {
            mkdir($this->_dataFolderName, 0777, true);
        }
    }


    /**
     * @return string
     */
    public function detail()
    {
        $product = $this->productCache();  //get product json
        $this->numberOfRequestCache(); //increase number of requests for product

        return $product;  //return json data of product
    }

    /**
     * @return string
     * @throws Exception
     */
    private function productCache()
    {
        $fileName = $this->_dataFolderName.'/product_'.$this->productId.'.txt';  //product cache path
        $fileContents = @file_get_contents($fileName);  //get cached content of product
        if ($fileContents == false) {  //if cache doesn't exist
            $elasticSearch = new ElasticSearch();  //class using interface IElasticSearchDriver
            $data = json_encode($elasticSearch->findById($this->productId));  //get product array and convert to json
            $productFile = fopen($fileName, "w") or die("Unable to open file!");  //open file
            fwrite($productFile, $data);  //write data to file
            fclose($productFile);  //close file
            return $data;  //return product json
        }

        return $fileContents;  // return product json
    }

    /**
     * @throws Exception
     */
    private function numberOfRequestCache()
    {
        $fileName = $this->_dataFolderName.'/numberOfRequests_'.$this->productId.'.txt';  //number of requests for product path
        $fileContents = @file_get_contents($fileName);  //get content of file
        if ($fileContents == false) {  //if cache doesn't exist, create new data to store
            $numberOfRequestCache[$this->productId] = 1;
        }
        else
        {
            $numberOfRequestCache = json_decode($fileContents, true);  //decode data from json to array
            $numberOfRequestCache[$this->productId] = intval($numberOfRequestCache[$this->productId]) + 1;  //increase number of requests
        }

        $data = json_encode($numberOfRequestCache);  //convert to json the number of requests per product
        $numberOfRequestsFile = fopen($fileName, "w") or die("Unable to open file!");   //open file
        fwrite($numberOfRequestsFile, $data);   //write data to file
        fclose($numberOfRequestsFile);   //close file
    }
} 