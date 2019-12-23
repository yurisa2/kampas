<?php
class flux
{
  public $list_item;
  public $nfeFile;
  public $storeOrderList;
  public function __construct($filename)
  {
    // $this->endpoint = $endpoint;
    // $this->marketplace = $marketplace;
    $this->filename = $filename;
    $this->pathFiles = PATHFILES.$filename;
    $this->counterFile = PATHFILES."counter";
    // $this->pathListItem = PATHFILES.$filename.'_LIST';

    // $this->setFiles();
    // $this->getFiles();
  }

  public function setFiles()
  {
    if($this->nfeFile) {
      $this->nfeFile = PATHFILES.$this->filename.'_nfe';
      if(!file_exists($this->nfeFile)) file_put_contents($this->nfeFile,'');
    }
    if($this->storeOrderList) {
      $this->storeOrderList = PATHFILES.$this->filename.'_CREATE';
      if(!file_exists($this->storeOrderList)) file_put_contents($this->storeOrderList,json_encode(array()));
    }
    if(!file_exists($this->pathFiles)) file_put_contents($this->pathFiles,'');
    if(!file_exists($this->counterFile)) file_put_contents($this->counterFile,0);

    // if(!file_exists($this->pathListItem)) file_put_contents($this->pathListItem,json_encode($this->list_item));
  }

  public function getFiles()
  {
    $this->last_item = trim(file_get_contents($this->pathFiles));
    $this->counter = (int)file_get_contents($this->counterFile);
  }

  public function addCounter()
  {
    // var_dump($this->counter,$this->counter+1);
    return file_put_contents($this->counterFile,$this->counter+1);
  }

  public function next_item()
  {
    $index = array_search($this->last_item,$this->list_item);

    if(is_bool($index)) return $this->list_item[0];

    if(strpos($this->pathFiles,"prod")) {
      if($index+1 == count($this->list_item)) {
        unlink($this->pathFiles);
        return false;
      }
    } else {
      if($index+1 == count($this->list_item)) {
        return false;
      }
    }
    return $this->list_item[$index+1];
  }

  public function previous_item()
  {
    $index = array_search($this->list_item,$this->last_item);

    return $this->list_item[$index-1];
  }

  public function last_item()
  {
    return $this->last_item;
  }

  public function position_item($position)
  {
    return $this->list_item[$position];
  }

  public function getOrderStoreList()
  {
    return json_decode(file_get_contents($this->storeOrderList));
  }

  public function add_item($item_id)
  {
    return file_put_contents($this->pathFiles,$item_id);
  }

  public function addOrderStore($order_id)
  {
    $storeList = json_decode(file_get_contents($this->storeOrderList));
    $storeList[] = $order_id;

    file_put_contents($this->storeOrderList,json_encode($storeList));
  }

  public function createLabelOrder($order_id,$label)
  {
    $this->labelFile = "etiquetas/".strtoupper($this->filename)."_".$order_id.".pdf";
    file_put_contents($this->labelFile,$label);
  }

  public function addOrderNfe($nfe_order)
  {
    file_put_contents($this->nfeFile,$nfe_order);
  }

  public function getIncrement()
  {
    return file_get_contents(PATHFILES.$this->counterFile);
  }

  public function addLabelHelper($order_id)
  {
    $this->labelHelper = PATHFILES.'helper_label';
    $order_id_label = file_get_contents();
    $order_id_label[] = array('order' => $order_id, 'time' => time());
    file_put_contents($this->labelHelper,$order_id_label);
  }

  public function getLabelHelper()
  {
    $order_id_label = file_get_contents($this->labelHelper);
    return $order_id_label[0];
  }
}
 ?>
