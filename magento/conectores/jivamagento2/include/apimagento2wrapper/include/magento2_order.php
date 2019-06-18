<?php
class magento2Order extends magento2Rest
{
  public function __construct()
  {
    parent::__construct();

  }

  public function getCustomer($customerId)
  {
    return $this->get("customers/2");
  }

  public function getOrders()
  {
    return $this->get("orders",array( 'filters' => array('status','eq','processing')));
  }

  public function getOrderList()
  {
    $products = $this->getOrders();
    foreach ($products->items as $key => $value) $orderList[] = $value->increment_id;

    return $orderList;
  }

  public function getOrder($orderId)
  {
    return $this->get("orders/$orderId");
  }

  public function getOrderWithParam($param,$value)
  {
    return $this->get("orders",array( 'filters' => array($param,'eq',$value)));
  }

  public function postNewCustomer($param)
  {
    /*template param
    array(
      "customer" => array(
		    "email" => "jdoe@example.com",
    		"firstname" => "Jane",
		    "lastname" => "Doe",
		    "addresses" => array(
          array(
    		    "defaultShipping" => true,
    		    "defaultBilling" => true,
      		  "firstname" => "Jane",
      		  "lastname" => "Doe",
      		  "region" => array(
      		      "regionCode" => "NY",
      			    "region" => "New York",
                "regionId" =>43
            ),
    		  "postcode" => "10755",
      		"street" => ["123 Oak Ave"],
      		"city" => "Purchase",
      		"telephone" => "512-555-1111",
      		"countryId" => "US"
  		    )
        )
	    ),
      "password": "Password1"
    )*/
    return $this->post("customers",$param);
  }

  public function postOrderCart($customerId)
  {
     return $this->post("customers/$customerId/carts",array('customer_id' => $customerId));
  }

  public function postOrderItems($param)
  {
    /*template param simple/downloadable product
    array(
      "cartItem" => array(
        "sku" => "WS12-M-Orange",
        "qty" => 1,
        "quote_id" => "4"
      )
    )*/
    return $this->post("carts/mine/items",$param);
  }

  public function postEstimateShipping($param)
  {
    /* template param
    array(
      "address" => array(
        "region" => "New York",
        "region_id" => 43,
        "region_code" => "NY",
        "country_id" => "US",
        "street" => array(
          "123 Oak Ave"
        ),
        "postcode" => "10577",
        "city" => "Purchase",
        "firstname" => "Jane",
        "lastname" => "Doe",
        "customer_id" => 4,
        "email" => "jdoe@example.com",
        "telephone" => "(512) 555-1111",
        "same_as_billing" => 1
      )
    )*/
    $this->post("carts/mine/estimate-shipping-methods",$param);
  }

  public function postShippingMethod($param)
  {
    /*template param

array(
  "addressInformation" => array(
	  "shipping_address" => array(
       "region" => "New York",
       "region_id" => 43,
       "region_code" => "NY",
       "country_id" => "US",
       "street" => arary(
        "123 Oak Ave"
      ),
    "postcode" => "10577",
    "city" => "Purchase",
    "firstname" => "Jane",
    "lastname" => "Doe",
    "email" => "jdoe@example.com",
    "telephone" => "512-555-1111"
  },
  "billing_address" => array(
  	"region" => "New York",
    "region_id" => 43,
    "region_code" => "NY",
    "country_id" => "US",
    "street" => [
      "123 Oak Ave"
    ],
    "postcode" => "10577",
    "city" => "Purchase",
    "firstname" => "Jane",
    "lastname" => "Doe",
    "email" => "jdoe@example.com",
    "telephone" => "512-555-1111"
  },
  "shipping_carrier_code" => "tablerate",
  "shipping_method_code" => "bestway"
  }
}
    */
    // return $this->post("carts/mine/shipping-information",$param);
    return $this->post("guest-carts/50/shipping-information",$param);
  }

  public function postPaymentMethod($param)
  {
    /* template param
    array(
    	"paymentMethod": array(
        "method" => "banktransfer"
    	),
    	"billing_address" => array(
	    	"email" => "jdoe@example.com",
      	"region" => "New York",
      	"region_id" => 43,
      	"region_code" => "NY",
	    	"country_id" => "US",
	    	"street" => ["123 Oak Ave"],
	    	"postcode" => "10577",
	    	"city" => "Purchase",
	    	"telephone" => "512-555-1111",
	    	"firstname" => "Jane",
	    	"lastname" => "Doe"
    	 )
    )
    */
    return $this->post("carts/mine/payment-information",$param);
  }

  public function postPaymentReceive($param)
  {
    /* template param
    array(
      "capture" => true,
      "notify" => true
    )
    */
    return $this->post("order/3/invoice",$param);
  }

  public function getOrderInvoice($invoiceId)
  {
    return $this->get("invoices/$invoiceId"); // property order_item_id is used on the next step
  }

  public function postOrderShipment($orderId,$param)
  {
    /*
    array(
      "items" => array(
        array(
          "order_item_id" => 3,
          "qty" => 1
        ),
        array(
          "order_item_id" => 5,
          "qty" => 1
        ),
        array(
          "order_item_id" => 11,
          "qty" => 1
        )
      ], tracks property is optional
      "tracks" => array(
        array(
          "track_number" => "1Y-9876543210",
          "title" => "United Parcel Service",
          "carrier_code" => "ups"
        )
      )
    )
    */
    return $this->post("order/$orderId/ship",$param);
  }
}
?>
