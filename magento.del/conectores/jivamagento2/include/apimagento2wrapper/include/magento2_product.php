<?php
class magento2Product extends magento2Rest
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getProducts()
  {
    return $this->get("products",array( 'filters' => array('status','like','1')));
  }

  public function getProductList()
  {
    $products = $this->getProducts();
    foreach ($products->items as $key => $value) $skulist[] = $value->sku;

    return $skulist;
  }

  public function getProduct($sku)
  {
    return $this->get("products/$sku");
  }

  public function getProductWithParam($param,$value)
  {
    return $this->get("products",array( 'filters' => array($param,'eq',$value)));
  }

  public function getProductStock($sku)
  {
    return $this->get("stockItems/$sku");
  }

  public function postProductStock($sku,$qty)
  {
    $param = array(
      'sourceItems' => array(
        array(
          "sku" => $sku,
          "source_code" => "default",
          "quantity" => $qty,
          "status" => 1
        )
      )
    );
    return $this->post("inventory/source-items",$param);
  }

  public function postProductPrice($sku,$price)
  {
    $param = array(
      'prices' => array(
        array(
          'price' => $price,
          'store_id' => 0,
          'sku' => $sku
        )
      )
    );

    return $this->post("products/base-prices",$param);
  }
}
?>
