<?php
ini_set("error_reporting",E_ALL);
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'include/all_include.php';
echo "<pre>";

// $magento2Product = new magento2Product;

// var_dump($magento2Product->getProduct('exemplo-produto-configuravel'));   //return information about product
// var_dump($magento2Product->getProductWithParam('entity_id','2068'));      //return information about product using something like parameter
// var_dump($magento2Product->getProductStock('1082'));                      //return stock properties
// var_dump($magento2Product->getProducts());                                //return list of products
// var_dump($magento2Product->getProductList());                             //return list of skus
// var_dump($magento2Product->postProductStock('1010','10'));                //update ṕroduct stock
// var_dump($magento2Product->postProductPrice('1010','35'));                //update ṕroduct price

$magento2Order = new magento2Order;

// var_dump($magento2Order->getCustomer(2));
// var_dump($magento2Order->getOrders());                                        //return orders with status equals processing
// var_dump($magento2Order->getOrderList());                                     //return list of increment
// var_dump($magento2Order->getOrder($orderId));                                 //return information about the order
// var_dump($magento2Order->getOrderWithParam($param,$value));                   //return information about the order using something like parameter
// $param = array(
//   "customer" => array(
//     "email" => "teste2@example.com",
//     "firstname" => "Teste",
//     "lastname" => "Teste 2",
//     "addresses" => array(
//       array(
//         "defaultShipping" => true,
//         "defaultBilling" => true,
//         "firstname" => "Teste",
//         "lastname" => "Teste",
//         "region" => array(
//             "regionCode" => "SP",
//             "region" => "São Paulo",
//             "regionId" => 508
//         ),
//       "postcode" => "18135350",
//       "street" => ["São Sebastião, 41","Jardim Villaça"],
//       "city" => "São Paulo",
//       "telephone" => "11943700572",
//       "countryId" => "BR"
//       )
//     )
//   ),
//   "password" => "@Senha123"
// );
//
// $newCustomer = $magento2Order->postNewCustomer($param);                            //create new customer
// var_dump($newCustomer->id);

// $cartId = $magento2Order->postOrderCart($newCustomer->id);                                   //create a cart
// var_dump($cartId);

// $param = array(
//   "cartItem" => array(
//     "sku" => "1082",
//     "qty" => 1,
//     "quote_id" => $cartId
//   )
// );
// var_dump($magento2Order->postOrderItems($param));                             //add items of cart

// $param = array(
//   "address" => array(
//     "region" => "São Paulo",
//     "region_id" => 508,
//     "region_code" => "SP",
//     "country_id" => "BR",
//     "street" => array(
//       "Rua São Sebastião","Número 41"
//     ),
//     "postcode" => "18135350",
//     "city" => "São Roque",
//     "firstname" => "Teste",
//     "lastname" => "Teste",
//     "customer_id" => 2,
//     "email" => "teste@example.com",
//     "telephone" => "11943700572",
//     "same_as_billing" => 1
//   )
// );
// var_dump($magento2Order->postEstimateShipping($param));                       //verify the price of shipping the products
//
// $param = array(
//   "addressInformation" => array(
//     "shipping_address" => array(
//       "region" => "São Paulo",
//       "region_id" => 508,
//       "region_code" => "SP",
//       "country_id" => "BR",
//       "street" => array("São Sebastião, 41","Jardim Villaça"),
//       "postcode" => "18135350",
//       "city" => "São Roque",
//       "firstname" => "Teste",
//       "lastname" => "Teste",
//       "email" => "Teste@example.com",
//       "telephone" => "11943700572"
//         ),
//     "billing_address" => array(
//       "region" => "São Paulo",
//       "region_id" => 508,
//       "region_code" => "SP",
//       "country_id" => "BR",
//       "street" => array("São Sebastião, 41","Jardim Villaça"),
//       "postcode" => "18135350",
//       "city" => "São Roque",
//       "firstname" => "Teste",
//       "lastname" => "Teste",
//       "email" => "Teste@example.com",
//       "telephone" => "11943700572"
//     ),
//     "shipping_carrier_code" => "Flat Rate",
//     "shipping_method_code" => "Fixed"
//     )
//   );
// var_dump($magento2Order->postShippingMethod($param));                         //add shipping method,shipping and billing address
// var_dump($magento2Order->postPaymentMethod($param));                          //add payment method
// var_dump($magento2Order->postPaymentReceive($param));                         //confirms that you received the payment
// var_dump($magento2Order->getOrderInvoice($invoiceId));                        //return information about order and more important order item id of each item
// var_dump($magento2Order->postOrderShipment($orderId,$param));                 //finish the order adding the items to be shipped
?>
