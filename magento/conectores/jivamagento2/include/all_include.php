<?php
$prefix_jiva = 'include/apijivaphp/';
$prefix_magento2 = 'include/apimagento2wrapper/';
$prefix_sa2_flux = 'include/sa2_flux/';

require_once "include/config.php";
require_once "include/defines.php";

require_once "include/apijivaphp/include/include_all.php";
require_once "include/apimagento2wrapper/include/all_include.php";
require_once "include/sa2_flux/include/all_include.php";

require_once 'include/event_base.php';        //control class: email/file/db
require_once 'include/error_handling.php';    //error class
require_once 'include/log.php';               //log class

require_once "include/jivamagento_product.php";
require_once "include/jivamagento_order.php";


require_once 'include/PHPMailer/src/Exception.php';
require_once 'include/PHPMailer/src/PHPMailer.php';
require_once 'include/PHPMailer/src/SMTP.php';

 ?>
