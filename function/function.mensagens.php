<? 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

function enviaEmail($pDestinatario,$pAssunto,$pMensagem) {

	// Instância da classe
	$mail = new PHPMailer(true);
   
	try
	{
	    // Configurações do servidor
	    $mail->isSMTP();                                    //Devine o uso de SMTP no envio
	    $mail->SMTPAuth     = true;                         //Habilita a autenticação SMTP
	    $mail->Username     = 'sac@pelotas.rs.gov.br';
	    $mail->Password     = 'NG4fDcS2rtLw';
	    
        // Informações específicadas do servidor
	    $mail->Host = '200.219.235.6';
	    $mail->Port = 465;
	    
        // Criptografia do envio SSL também é aceito
	    $mail->SMTPSecure = 'ssl';
		
        // Configurações de compatibilidade para autenticação em TLS 
		$mail->SMTPOptions = array( 'ssl' => array( 'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true ) ); 
		
        // Você pode habilitar esta opção caso tenha problemas. Assim pode identificar mensagens de erro. 
	 	//$mail->SMTPDebug = 2;
		$mail->CharSet = 'UTF-8'; 

	    // Define o remetente
	    $mail->setFrom('noreplay@pelotas.rs.gov.br', 'Secretaria Municipal de Desenvolvimento Rural');

	    // Define o destinatário
	    if (is_array($pDestinatario)) {
	    	foreach($pDestinatario as $valor) {
		    	$mail->addAddress($valor, 'Destinatário');
	    	}
		}
		else $mail->addAddress($pDestinatario, 'Destinatário');
	    
        // Conteúdo da mensagem
	    $mail->isHTML(true);  // Seta o formato do e-mail para aceitar conteúdo HTML

	    $mail->Subject = $pAssunto;
	    $mail->Body    = $pMensagem;
	    $mail->AltBody = strip_tags($pMensagem);
	    
        // Enviar
	    $mail->send();
	    return(1);
	}
	catch (Exception $e)
	{
	    return ($mail->ErrorInfo);
	}
}

?>
