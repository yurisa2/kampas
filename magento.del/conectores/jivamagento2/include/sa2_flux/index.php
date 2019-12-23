<?php
ini_set("error_reporting",E_ALL);
ini_set('display_errors', 1);

require_once 'include/all_include.php';
echo "<pre>";

/*
template class flux
$flux = new flux($marketplace, $endpoint, $clientStore);
$flux->list_item = list of products or orders (only id/sku);
$flux->setFiles();
$flux->getFiles();
*/
$flux_b2w = new flux('b2w','order','magento');
var_dump($flux_b2w);

?>
