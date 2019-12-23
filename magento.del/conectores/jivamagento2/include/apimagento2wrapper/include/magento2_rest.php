<?php
class magento2Rest extends maRest
{
  public function __construct()
  {
    parent::__construct(URL_STORE);
    $this->connect(USER,PASSWORD);
  }
}
?>
