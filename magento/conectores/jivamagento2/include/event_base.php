<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class event_base
{
  public function __construct()
  {
    $this->send_mail = 0;
    /**
    * @param @property $novacompra True se email estiver habilitado/false se não estiver habilitado
    *
    */
    $this->email_novacompra = false;

    /**
    * @param $email_nfe True se email estiver habilitado/false se não estiver habilitado
    *
    */
    $this->email_nfe = false;

    /**
    * @property $titulo O assunto do email
    *
    */
    $this->titulo = '';

    /**
    * @property $nome_funcao A função que houve problema
    *
    */
    $this->nome_funcao = '';

    /**
    * @property $saida O debug da função
    *
    */
    $this->saida = '';

    /**
    * @property $tipo Qual a origem/significado da mensagem: Erro - log
    *
    */
    $this->tipo = '';

    /**
    * @property $mensagem Conteudo que irá no corpo do email/arquivo de log .json/gravado no banco de dados
    *
    */
    $this->mensagem = '';

    /**
    * @property $mensagemHTML Conteudo HTML que irá no corpo do email
    *
    */
    $this->mensagemHTML = '';

    /**
    * @property $data A data em segundos para a gravação no banco de dados
    *
    */
    $this->data = time();

    /**
    * @property $error_db CLASSE ERROR HANDLING - True para gravar informações no BD /false para não gravar
    *
    */
    $this->error_db = true;

    /**
    * @property $error_files CLASSE ERROR HANDLING - True para gravar informações no arquivo .json /false para não gravar
    *
    */
    $this->error_files = true;

    /**
    * @property $dir_file Diretório do arquivo de log .json pré-definido (modificavél para classe log)
    *
    */
    $this->dir_file = 'error_files/error_log.json';

    /**
    * @property $flag_HTML Comanda o tipo de dados que irá no corpo do email
    *
    */
    $this->flag_HTML = true;

    /**
    * @property $log_etiqueta Diretorio do arquivo de etiqueta .pdf - vazio caso nao exista necessidade de anexar
    *
    */
    $this->log_etiqueta = null;

    /**
    * @property $log_email CLASSE LOG - Comanda o tipo de dados que irá no corpo do email
    *
    */
    $this->log_email = false;

    /**
    * @property $log_db CLASSE LOG - True para gravar informações no BD /false para não gravar
    *
    */
    $this->log_db = false;

    /**
    * @property $log_files CLASSE LOG - True para gravar informações no arquivo .json /false para não gravar
    *
    */
    $this->log_files = false;

    /**
    * @property $mensagem_email classe log - Titulo do email que sera enviado
    *
    */
    $this->mensagem_email = '';

  }

  /**
  * Function responsible to send the email
  *
  * @return string if failure - Message could not be sent. Mailer Error: ErrorInfo or
  * if was send - e-mail enviado com sucesso!
  *
  */
  public function email()
  {
    $from_mail = 'mercomagento@sa2.com.br';
    $from_name = 'SA2 - Integração Jiva Magento';
    $titulo = $this->titulo;
    $mensagem = $this->mensagemHTML;
    $mail = new PHPMailer;
    $mail->IsHTML(true);

    if(SMTP == true)
    {
      $mail->isSMTP();                             // Set mailer to use SMTP
      $mail->Host = SMTP_HOST;                         // Specify main and backup SMTP servers
      $mail->SMTPAuth = SMTP_AUTH;                 // Enable SMTP authentication
      $mail->Username = SMTP_USERNAME;                 // SMTP username
      $mail->Password = SMTP_PASSWORD;                 // SMTP password
      $mail->SMTPSecure = SMTP_SECURE;             // Enable TLS encryption, `ssl` also accepted
      $mail->Port = 587;
      $mail->Body = $mensagem;
    }
    else
    {
      $from_name = 'SA2 - Integração Jiva Magento - SendMail';
      $mail->isSendmail();
      $mail->SMTPAuth = true;
      $mail->msgHTML($this->mensagemHTML);
      $mail->SMTPDebug=1;
    }

    $mail->CharSet = 'utf-8';  //Arrumar acentuação
    $mail->setFrom($from_mail, $from_name);

    if($this->email_nfe == true){
      foreach (EMAIL_ADDENVOICE as $key => $value) {
        $mail->addAddress($value);
      }
    }elseif($this->email_novacompra == true){
      foreach (EMAIL_NEWSALE as $key => $value) {
        $mail->addAddress($value);
      }
    }else {
      foreach (EMAIL_TO as $key => $value) {
        $mail->addAddress($value);
      }
    }

    $mail->Subject = $titulo;
    if($this->log_etiqueta !== null) $mail->addAttachment($this->log_etiqueta);
    $increment = file_get_contents("include/files/counter");
    // var_dump($increment,$this->send_mail);
    // exit("Verificação da variavel send_mail: $this->send_mail");
    if($increment >= $this->send_mail) {
      if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
      } else {
        echo "e-mail enviado com sucesso!<br>";
        file_put_contents("include/files/counter",0);
      }
    } else echo "Email é enviado a cada ".SEND_TIME." minutos. Contador em ".$increment."<br>";
    // file_put_contents("include/files/increment.json",$increment++);
  }

  function aux_db()
  {
    $this->mensagem = array('Nome Funcao' =>$this->nome_funcao ,
    'Msg de Erro' =>$this->saida ,
    'Titulo' =>$this->titulo ,
    'Tipo do Erro' =>$this->tipo );
  }

  /**
  * Function responsible to save data on DB
  *
  */
  public function db()
  {
    $mensagemdb = json_encode(array('Nome Funcao' =>$this->nome_funcao ,
    'Msg de Erro' =>$this->saida ,
    'Titulo' =>$this->titulo ,
    'Tipo do Erro' =>$this->tipo ));

    // echo "Função DB() conteudo de mensagem:<br>";
    // var_dump($this->mensagem); // DEBUG

    $sqlite = "sqlite:include/event.db";

    $pdo = new PDO($sqlite);
    $sql = "INSERT INTO event(nome_funcao, saida_erro, mensagem, titulo, tipo, data) VALUES (?,?,?,?,?,?)";
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $result = $pdo->prepare($sql);
    $result->bindParam(1, $this->nome_funcao);
    $result->bindParam(2, $this->saida);
    $result->bindParam(3, $mensagemdb);
    $result->bindParam(4, $this->titulo);
    $result->bindParam(5, $this->tipo);
    $result->bindParam(6, $this->data);
    $result->execute();
    $select = $result->fetchAll(PDO::FETCH_ASSOC);



  }

  /**
  * Function to write an json file
  *
  * @return string if failure - Arquivo não criado em error_files or
  * if was true - Concluido!!
  *
  */
  public function files()
  {
    if(!file_exists($this->dir_file)) file_put_contents($this->dir_file,'');
    $mensagem = json_decode(file_get_contents($this->dir_file));
    $mensagem[] = json_decode($this->mensagem);
    $resultado = file_put_contents($this->dir_file, json_encode($mensagem, JSON_UNESCAPED_UNICODE));
    //caso exista + de 100 erros no json manda email com todos.
    //OBS: Pode até mandar o arquivo em anexo;
    if (count($mensagem) > 100)
    {
      $this->titulo = "Erros JivaMagento";
      foreach ($mensagem as $key => $value)
      {
        foreach ($mensagem[$key] as $i => $values) {
          $this->mensagemHTML.= $i.": ".$values."<br>";
        }
          $this->mensagemHTML.="<b>-------------------------------</b><br>";
      }
      $this->email();
      file_put_contents($this->dir_file, "");
    }
    if($resultado == false) echo "Arquivo não criado em $this->dir_file";
    else echo "Gravado em: $this->dir_file <br>";
  }

  /**
  * Function Para executar as funções
  *
  * @param    $configmail   Variavel global para o envio de email
  * @property $log_email    Propriedade da classe para restringir o envio do email na Classe filha LOG
  * @property $log_db       Propriedade da classe para restringir a gravação no DB na Classe filha LOG
  * @property $log_files    Propriedade da classe para restringir a gravação no json na Classe filha LOG
  * @property $error_db     Propriedade da classe para restringir a gravação no DB na Classe filha ERROR_HANDLING
  * @property $error_files  Propriedade da classe para restringir a gravação no json na Classe filha ERROR_HANDLING
  */
  public function execute()
  {
    global $configmail;

    if(($configmail) || ($this->log_email)) $this->email();
    if(($this->log_db) || ($this->error_db)) $this->db();
    if(($this->log_files) || ($this->error_files)) $this->files();
  }

}

?>
