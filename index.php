<?php
/**
 * User: Tatiana Khegai
 * Date: 11/28/2017
 */
include_once("classes/ProductController.php");

//test
$product = new ProductController(1);
echo $product->detail();