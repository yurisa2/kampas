<?php


Class product extends base{

  public function __construct() {
    global $options;

    $auth = new authentication;
    $sess = $auth->get_session();

    $this->own_url = BASE_URL."/mge/service.sbr?serviceName=CRUDServiceProvider.loadRecords";
    $this->opt_req = $options;
    $this->opt_req["http"]["header"] = $this->opt_req["http"]["header"]."Cookie: JSESSIONID=".$sess;
    $this->stock_url = BASE_URL."/mgecom/service.sbr?serviceName=ConsultaProdutosSP.getDetalhesEstoques&mgeSession=".$sess;
    $this->price_url = BASE_URL."/mgecom/service.sbr?serviceName=ConsultaProdutosSP.getDetalhesPrecos&mgeSession=".$sess;
  }

  public function list_prod() {
    $req = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
    <serviceRequest serviceName=\"CRUDServiceProvider.loadRecords\">
    <requestBody>
    <dataSet rootEntity=\"Produto\" includePresentationFields=\"S\" parallelLoader=\"false\">
    <entity path =\"\">
    <fieldset list=\"*\" />
    </entity>
    </dataSet>
    </requestBody>
    </serviceRequest>
    ";

    $options_ = $this->opt_req;
    $options_['http']['content'] = $req;

    // var_dump($options_);

    $context  = stream_context_create($options_);
    $result = file_get_contents($this->own_url, false, $context);

    $this->prod = $this->return_prop($result);

    return $this->prod;
  }

  public function prod_stock($sku_prod) {
    $options_ = array('http' => array(
                      'header'  => "Content-type: text/xml;charset=iso-8859-1\r\n",
                      'method'  => 'POST',
                      'content' => "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
                      <serviceRequest serviceName=\"ConsultaProdutosSP.getDetalhesEstoques\">
                      <requestBody>
                      <criterio RESOURCEID='br.com.sankhya.com.cons.consultaProdutos' CODPROD=\"$sku_prod\" MANDARVOLUMES='false'>
                      </criterio>
                      </requestBody>
                      </serviceRequest>"
                      )
                    );


    // var_dump($options_);

    $context  = stream_context_create($options_);
    $result = file_get_contents($this->stock_url, false, $context);

    $this->prod = $this->return_prop($result);

    return $this->prod;
  }

  public function prod_price($sku_prod) {
    $options_ = array('http' => array(
                      'header'  => "Content-type: text/xml;charset=iso-8859-1\r\n",
                      'method'  => 'POST',
                      'content' => "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
                      <serviceRequest serviceName=\"ConsultaProdutosSP.getDetalhesPrecos\">
                      <requestBody>
                      <criterio CODPROD=\"$sku_prod\" RESOURCEID='br.com.sankhya.com.cons.consultaProdutos'/>
                      </requestBody>
                      </serviceRequest>"
                      )
                    );
    // var_dump($options_);

    $context  = stream_context_create($options_);
    $result = file_get_contents($this->price_url, false, $context);

    $this->prod = $this->return_prop($result);

    return $this->prod;
  }
}


  ?>
