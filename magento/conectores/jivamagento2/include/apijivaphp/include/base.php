<?php

Class base {

  public function __construct() {

    

  }

    public function  return_prop($response) {

      $xml_response = simplexml_load_string($response);

      return $xml_response;
    }

}


?>
