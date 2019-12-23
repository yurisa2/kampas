<?php
class jivamangento_product extends magento2Product
{
  public function __construct()
  {
    parent::__construct();
    $this->product_id = '';
    $this->product_price = '';
    $this->product_stock = '';
  }

  public function jivamagentoProductUpdate()
  {
    if($this->product_price == '') {
      $erro = "Preço";
      $return['ErrorPrice'] = "Preço do produto não pode ser vazio ou diferente de float. valor: $this->product_price";
    } else $return['UpdatePrice'] = $this->postProductPrice($this->product_id,$this->product_price);

    if($this->product_stock == '' && $this->product_stock != 0) {
      $erro = "Estoque";
      $return['ErrorStock'] = "Estoque do produto não pode ser vazio ou diferente de inteiro. valor: $this->product_stock";
    } else $return['UpdateStock'] = $this->postProductStock($this->product_id,$this->product_stock);

    if(isset($return['ErrorPrice']) || isset($return['ErrorStock'])) {
      $error = new error_handling("Erro ao atualizar $erro do produto","Erro ao tentar atualizar $erro do produto", "Produto: $this->product_id<br>Valor Preço: $this->product_price - Valor Stock: $this->product_stock", "Erro produto");
      $error->send_error_email();
      $error->execute();
      return "Erro ao atualizar produto $this->product_id";
    }

    return $return;
  }
}
?>
