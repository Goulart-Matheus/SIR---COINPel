<?

function smssend($mobile, $msg, $from="", $id=""){

	$conta		= "sanep";
	$codigo		= "kz5iauYZ7o";
	$msg		= URLEncode($msg);
	$response 	= fopen("http://system.human.com.br/GatewayIntegration/msgSms.do?dispatch=send&account=$conta&code=$codigo&to=55$mobile&msg=$msg&from=$from","r");
	$status_code= fgets($response,4);
	//echo "<br>Status code = ".$status_code;
	echo "<br><br>";
	if    ($status_code=="000") echo callException("Mensagem enviada com sucesso", 0);
	elseif($status_code=="010") echo callException("Mensagem vazia",1);
	elseif($status_code=="011") echo callException("Corpo da mensagem inválido",1);
	elseif($status_code=="012") echo callException("Corpo da mensagem excedeu o limite. Os campos devem ter no máximo 142 caracteres.",1);
	elseif($status_code=="013") echo callException("Número do destinatário está incompleto ou inválido.<br>O número deve conter o código do país e código de área além do número.<br>Apenas dígitos são aceitos.",1);
	elseif($status_code=="014") echo callException("Número do destinatário está vazio",1);
	elseif($status_code=="015") echo callException("A data de agendamento está mal formatada.",1);
	elseif($status_code=="016") echo callException("ID informado ultrapassou o limite de 20 caracteres.",1);
	elseif($status_code=="080") echo callException("Já foi enviada uma mensagem de sua conta com o mesmo identificador.",1);
	else  echo "Erro Desconhecido";

}
?>
