<?php
class jivamangento_order extends magento2Order
{
  public function __construct()
  {
    parent::__construct();
    $this->jivaObject = '';//jiva order class
    $this->order_id = '';
  }

  public function jivamagentoOrderUpdate()
  {
    $orderInfo = $this->getOrder($this->order_id);

    // normalize data to put on Jiva system
    // call object with method to create a order

    return $orderInfo;
  }
}
?>
