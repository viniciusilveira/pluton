<?php
namespace Multiple\Library;

use Swift_Message AS Message;
use Swift_SmtpTransport AS Smtp;
use Swift_Mailer AS Mailer;

/**
 * Classe para envios e confirmação de emails utilizando Swift Mailer
 * Verificar utilização em http://www.sitepoint.com/sending-confirmation-emails-phalcon-swift/
 */
class Mail
{
    
    private $transport;
    private $mailer;
    
    /**
     * Efetua a configuração do swift para envio de emails
     */
    public function __construct() {
        $this->transport = Smtp::newInstance('smtp.gmail.com', 465, 'ssl')->setUsername('cmspluton@gmail.com')->setPassword('p170ncm5');
        
        $this->mailer = Mailer::newInstance($this->transport);

    }
    
    /**
     * Envia um email
     * @param  string 	$subject  	Assunto do email
     * @param  array 	$addresse  	Array com todos os destinatários do email
     * @param  string 	$body     	Conteúdo da menssagem
     * @return bool		            true caso o email tenha sido enviado ou false caso ocorra algum erro
     */
    public function sendMessage($subject, $addresse, $body) {
        
        $message = Message::newInstance($subject)->setFrom(array(
            $this->transport->getUsername() => 'cmspluton@gmail.com'
        ))->setTo($addresse)->setBody($body);
        
        return $this->mailer->send($message);
    }
}
