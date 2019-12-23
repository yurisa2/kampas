<?php
Class jivaOrder extends base
{

  public function __construct()
  {
    global $options;

    $auth = new authentication;
    $sess = $auth->get_session();

    $this->opt_req = $options;
    $this->opt_req["http"]["header"] = $this->opt_req["http"]["header"]."Cookie: JSESSIONID=".$sess;
    $this->order_url = BASE_URL."/mgecom/service.sbr?serviceName=CACSP.incluirNota&mgeSession=".$sess;
  }

  public function jivaCreateOrder()
  {
    $options_ = array('http' => array(
                      'header'  => "Content-type: text/xml;charset=iso-8859-1\r\n",
                      'method'  => 'POST',
                      'content' => "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
                      <serviceRequest serviceName=\"CACSP.incluirNota\">
                        <requestBody>
                          <nota>
                            <cabecalho>
                              <NUNOTA/>
                              <TIPMOV>P</TIPMOV>
                              <DTNEG>12/10/2015</DTNEG>
                              <CODTIPVENDA>11</CODTIPVENDA>
                              <CODPARC>1</CODPARC>
                              <CODTIPOPER></CODTIPOPER>
                              <CODEMP>1</CODEMP>
                              <CODVEND></CODVEND>
                              <OBSERVACAO><![CDATA[pedido incluido via integração]]></OBSERVACAO>
                            </cabecalho>
                            <itens INFORMARPRECO=\"N\">
                              <item>
                                <NUNOTA/>
                                <SEQUENCIA/>
                                <CODPROD>1392</CODPROD>
                                <CODVOL><![CDATA[UN]]></CODVOL>
                                <CODLOCALORIG>1</CODLOCALORIG>
                                <CONTROLE/>
                                <QTDNEG>1</QTDNEG>
                              </item>
                            </itens>
                          </nota>
                        </requestBody>
                      </serviceRequest>"
                      )
                    );


    // var_dump($options_);

    $context  = stream_context_create($options_);
    $result = file_get_contents($this->order_url, false, $context);

    $this->prod = $this->return_prop($result);

    return $this->prod;
  }
}


  ?>
