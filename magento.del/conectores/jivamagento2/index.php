<?php
ini_set("error_reporting",E_ALL);
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'include/all_include.php';
$time = time();
echo "<pre>";
echo "<h1>PRODUTO</h1>";
// get skus of products registered on Magento object extends magento2Product class
$jivamangento_product = new jivamangento_product;
$listProductSku = $jivamangento_product->getProductList();

// instance of object responsable of manage json files
$flux_product = new flux('jivamangento_product');
$flux_product->list_item = $listProductSku;
$flux_product->nfeFile = false;
$flux_product->storeOrderList = false;
$flux_product->setFiles();
$flux_product->getFiles();
$flux_product->addCounter();
$sku = $flux_product->next_item();

echo "<h2>$sku</h2>";

// instance Jiva object to get data of product (price and stock)
$product = new product;
$product_price = $product->prod_price($sku);
$product_stock = $product->prod_stock($sku);
$product_stock = str_replace("@",'',json_encode($product_stock));
$product_stock = json_decode($product_stock);

if(isset($product_stock->statusMessage) && isset($product_price->statusMessage)) echo "Produto não encontrado<br>";
else {
  if(isset($product_stock->responseBody->produto->estoques->estoque)) $stock = $product_stock->responseBody->produto->estoques->estoque[0]->ESTOQUE;
  elseif(isset($product_stock->responseBody->produto->estoques->attributes)) $stock = $product_stock->responseBody->produto->estoques->attributes->QTDTOTALEST;
  else $stock = 0;

  if($stock < 0) $stock = 0;
  $jivamangento_product->product_id = $sku;

  if(isset($product_price->responseBody->produto->precos->preco->VALOR)) $jivamangento_product->product_price = (float)$product_price->responseBody->produto->precos->preco->VALOR;
  else $jivamangento_product->product_price = null;

  $jivamangento_product->product_stock = (int)$stock;

  var_dump($jivamangento_product->jivamagentoProductUpdate());
}
$flux_product->add_item($sku);

echo "Fim de produtos: ";
echo time()-$time;
// code block not finished yet
// echo "<h1>PEDIDO</h1>";
// $jivamangento_order = new jivamangento_order;
// $jivamangento_order = $jivamangento_order->getOrderList();
// if(!$jivamangento_order) echo "Não há novos pedidos<br>";
// else {
//   $flux_order = new flux('jivamangento_order');
//   $flux_order->list_item = $jivamangento_order;
//   $flux_order->nfeFile = false;
//   $flux_order->storeOrderList = true;
//   $flux_order->setFiles();
//   $flux_order->getFiles();
//   $order_id = $flux_order->next_item();
//   if(!$order_id) echo "Todos os itens foram usados. Recomeçando a partir da proxima execução";
//   else {
//     if(is_bool(array_search($order_id,$flux_order->getOrderStoreList()))) {
//       $jivamangento_order->order_id = $order_id;
//       $jivamangento_order->jivamagentoOrderUpdate();
//
//     } else echo "Já inserido no JIVA";
//     var_dump($flux_order->add_item($order_id));
//   }
// }


 ?>
