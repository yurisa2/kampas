<?PHP
$sender = 'loja@cantinhodasessencias.com.br';
$recipient = 'yuri@sa2.com.br';

$subject = "php mail test";
$message = "php test message";
$headers = 'From:' . $sender;

if (mail($recipient, $subject, $message, $headers))
{
    echo "Message accepted";
}
else
{
    echo "Error: Message not accepted";
}
?>
