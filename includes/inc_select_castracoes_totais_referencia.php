<?php

$query->exec("SELECT total_castracoes,percentual_canil,percentual_femeas,percentual_machos FROM canil.parametros");
$query->proximo();
$total_castracoes   = $query->record["total_castracoes"];
$percentual_canil   = $query->record["percentual_canil"];
$percentual_femeas  = $query->record["percentual_femeas"];
$percentual_machos  = $query->record["percentual_machos"];

$referencia=strftime("%Y%m");
$query->exec("SELECT referencia,total,macho,femea,canil FROM canil.castracoes_referencias where referencia='$referencia'");
if ($query->rows()==0) { 
	$castracoes        = round(($total_castracoes*(100-$percentual_canil)/100));
	$castracoes_f      = round($castracoes*($percentual_femeas/100));
	$castracoes_m      = round($castracoes*($percentual_machos/100));
	$castracoes_canil  = round(($total_castracoes*($percentual_canil/100)));
	$query->insertTupla('canil.castracoes_referencias', array( $referencia,
                                                               $castracoes,
                                                               $castracoes_m,
                                                               $castracoes_f,
                                                               $castracoes_canil,
                                                               $_login,
                                                               $_ip,
                                                               $_data,
                                                               $_hora));
}
else {
	$query->proximo();
	$castracoes        = $query->record[1];
	$castracoes_m      = $query->record[2];
	$castracoes_f      = $query->record[3];
	$castracoes_canil  = $query->record[4];
}

?>