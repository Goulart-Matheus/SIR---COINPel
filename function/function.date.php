<?
function retornaData($date) {
    if ($date=='') return ('');
    list($yyyy, $mm, $dd) = explode('-',$date);
    return $dd."/".$mm."/".$yyyy;
}
function retornaDataBd($date) {
    if ($date=='') return ('NULL');
    list($dd, $mm, $yyyy) = explode('/',$date);
    return $yyyy."-".$mm."-".$dd;
}
function diasemana($data) {
	$ano =  substr("$data", 0, 4);
	$mes =  substr("$data", 5, -3);
	$dia =  substr("$data", 8, 9);
	$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );

	switch($diasemana) {
		case"0": $diasemana = "Domingo";       break;
		case"1": $diasemana = "Segunda-Feira"; break;
		case"2": $diasemana = "Terça-Feira";   break;
		case"3": $diasemana = "Quarta-Feira";  break;
		case"4": $diasemana = "Quinta-Feira";  break;
		case"5": $diasemana = "Sexta-Feira";   break;
		case"6": $diasemana = "Sábado";        break;
	}
	echo "$diasemana";
}


function somar_dias_uteis($str_data, $int_qtd_dias_somar = 1) {
    // Transforma para DATE - aaaa-mm-dd
	$str_data = substr($str_data,0,10);
	
    // Se a data estiver no formato brasileiro: dd/mm/aaaa
    // Converte-a para o padrão americano: aaaa-mm-dd
    if ( preg_match("@/@",$str_data) == 1 )
    {
        $str_data = implode("-", array_reverse(explode("/",$str_data)));
    }	
    $array_data = explode('-', $str_data);
    $count_days = 0;
    $int_qtd_dias_uteis = 0;
	
	$nova_data = date('Y-m-d',strtotime('+1 day', mktime(0, 0, 0, $array_data[1], $array_data[2], $array_data[0])));
	   
    while ( $int_qtd_dias_uteis < $int_qtd_dias_somar )
    {
        $count_days++;
    	$nova_data = date('Y-m-d',strtotime('+'.$count_days.' day', mktime(0, 0, 0, $array_data[1], $array_data[2], $array_data[0])));
        if ( $dias_da_semana = gmdate('w', $nova_data) != '0' && $dias_da_semana != '6' ) {
		    // desviou sabados e domingos	
			// sobrou dias normais, verifica se dia normal não é feriado	
			$mes=gmdate('m',$nova_data);
			$dia=gmdate('d',$nova_data);
			switch ($mes){
				case 9:
				    if ($dia=!7)  { $int_qtd_dias_uteis++; }
					if ($dia=!20) { $int_qtd_dias_uteis++; }
					break;
				case 11:
				    if ($dia=!2)  { $int_qtd_dias_uteis++; }
					if ($dia=!15) { $int_qtd_dias_uteis++; }
					break;
				default;
				    $int_qtd_dias_uteis++; 		
			}
		}
    }
	$count_days--;
	return gmdate('Y-m-d',strtotime('+'.$count_days.' day',strtotime($str_data)));
 
}
