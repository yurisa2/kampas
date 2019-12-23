<?php

Class authentication extends base{

  public function __construct() {
      global $options;

      $this->own_url = BASE_URL."/mge/service.sbr?serviceName=MobileLoginSP.login";
      $this->opt_req = $options;
  }

  public function login() {
    $body = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
    <serviceRequest serviceName=\"MobileLoginSP.login\">
    <requestBody>
    <NOMUSU>".USERNAME."</NOMUSU>
    <INTERNO2>".INTERNO2."</INTERNO2>
    </requestBody>
    </serviceRequest>";

    // var_dump($options);

    $options_ = $this->opt_req;
    $options_['http']['content'] = $body;

    $context  = stream_context_create($options_);
    $result = file_get_contents($this->own_url, false, $context);

    $this->login = $this->return_prop($result);
    var_dump($this->login);
    exit;
    return (string)$this->login->responseBody->jsessionid;
  }

  protected function write_session() {
    $session_file_contents = array();
    $session_file_contents["time"] = time();
    $session_file_contents["session_id"] = $this->login();

    $session_file_json = json_encode($session_file_contents);

    $result_fp = file_put_contents(SESSION_FILE,$session_file_json);


    return (string)$session_file_contents["session_id"];
  }


  public function get_session(){
    $session_id = NULL;
    if(!file_exists(SESSION_FILE) || !$this->session_active()) {
    $session_id = $this->write_session();
  } else {
    $session_file = file_get_contents(SESSION_FILE);
    var_dump($session_file);
    $session_file = json_decode($session_file);
    $session_id = (string)$session_file->session_id;
    }

  return $session_id;
  }

  public function get_session_time(){
    $session_time = 0;

    if(!file_exists(SESSION_FILE)) {
    $session_time = $this->write_session();
    } else {
    $session_file = file_get_contents(SESSION_FILE);
    $session_file = json_decode($session_file);
    $session_time = (integer)$session_file->time;
    }

    return $session_time;
  }


  public function session_active(){
    $return_ttl = false;

    if(time()-SESSION_TTL < $this->get_session_time()) $return_ttl = true;

    return (bool)$return_ttl;
  }



}


?>
