<?php
include('../includes/connection.php');
include('../function/function.date.php');
include('../class/class.report.php');


$id_projeto=implode(",",$id_projeto);

$query2 = new Query($bd); 
$query_acao_pai = new Query($bd); 
$query_microregiao = new Query($bd); 

$pdf = new ProjetoPDF(); 
$pdf->Open();
$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetFillColor(235,235,235);
$pdf->SetFont('Arial', '', 12);

$query->exec("
SELECT  p.id_projeto,
	p.titulo,
	u.nome,
	pr.descricao,
	u1.nome,
	e.descricao,
	p.descricao,
	p.objetivo,
	p.beneficios,
	p.dificuldades,
	p.meta,
	'R$ '||LTRIM(TO_CHAR(p.recursos_proprios,'999G999G999D99')),
	'R$ '||LTRIM(TO_CHAR(p.recursos_terceiros,'999G999G999D99')),
	p.localizacao,
	o.descricao,
	null,
	TO_CHAR(p.dt_inicio_prev,'DD/MM/YYYY'),
	TO_CHAR(p.dt_termino_prev,'DD/MM/YYYY'),
	p.fontes_recursos,
	TO_CHAR(p.dt_inicio_lb,'DD/MM/YYYY'),
	TO_CHAR(p.dt_inicio_lb+duracao_lb,'DD/MM/YYYY')    
FROM 	
       projeto p 
        INNER JOIN objetivo ob ON p.id_objetivo=ob.id_objetivo
        INNER JOIN eixo e ON ob.id_eixo=e.id_eixo
        LEFT  JOIN programa pr on p.id_programa=pr.id_programa
	LEFT JOIN usuario u1 ON pr.gestor=u1.login,
	usuario u,
	orgao o
WHERE 	p.gestor=u.login AND
	p.id_orgao=o.id_orgao AND
	p.id_projeto IN ($id_projeto)
");
$n=$query->rows();
while($n--){
    $query->proximo();
    $pdf->AddPage();
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFillColor(235,235,235);
    $pdf->SetFont('Arial', 'b', 14);
	$pdf->SetWidths(array(0=>'190'));
	$pdf->Row(array(0=>"Projeto: ".$query->record[1]), 10, 0, 1, 'L', 1);
    $pdf->ln();
   
    $pdf->SetFillColor(255,153 ,31 );
    $pdf->Row(array(0=>""), 1, 0, 1, 'L', 1);    
    $pdf->ln();

    $pdf->SetFont('Arial', '', 10);
	$pdf->SetWidths(array(0=>'40',1=>'150'));
	$pdf->Row(array(0=>"Responsсvel:",1=>$query->record[2]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
	$pdf->Row(array(0=>"Programa:",1=>$query->record[3]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
	$pdf->Row(array(0=>"Resp.Programa:",1=>$query->record[4]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
	$pdf->Row(array(0=>"Eixo:",1=>$query->record[5]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
	$pdf->Row(array(0=>"Descriчуo:",1=>$query->record[6]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
	$pdf->Row(array(0=>"Objetivo:",1=>$query->record[7]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
	$pdf->Row(array(0=>"Benefэcios:",1=>$query->record[8]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
	$pdf->Row(array(0=>"Dificuldades:",1=>$query->record[9]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
	$pdf->Row(array(0=>"Meta:",1=>$query->record[10]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
	$pdf->Row(array(0=>"Recursos Prѓprios:",1=>$query->record[11]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
	$pdf->Row(array(0=>"Recursos de Terceiros:",1=>$query->record[12]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
	$pdf->Row(array(0=>"Fonte de Recursos:",1=>$query->record[18]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
	$pdf->Row(array(0=>"Localizaчуo:",1=>$query->record[13]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
                $microregioes = '';
                $query_microregiao->exec('SELECT m.descricao 
                                                            FROM microregiao m 
                                                            WHERE m.id_microregiao IN (SELECT id_microregiao FROM projeto_microregiao WHERE id_projeto = '.$query->record[0].')
                                                            ORDER BY m.descricao');
                $nm = $query_microregiao->rows();
                while ($nm--) {
                    $query_microregiao->proximo();
                    $microregioes .= $query_microregiao->record[0].', ';
                }
                $microregioes = substr($microregioes, 0, -2);
	$pdf->Row(array(0=>"Microregiуo:",1=> $microregioes), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
	$pdf->Row(array(0=>"гrgуo:",1=>$query->record[14]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
	$pdf->Row(array(0=>"Status:",1=>$query->record[15]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
	$pdf->Row(array(0=>"Perэodo Previsto:",1=>$query->record[16]." a ".$query->record[17]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
	$pdf->Row(array(0=>"Linha de Base:",1=>$query->record[19]." a ".$query->record[20]), 5, 0, 1, 'L', 0);
    $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
    $pdf->ln();
    $pdf->ln();
    
    // AЧеES
    $query2->exec("
                    SELECT 
                    	pa.titulo,
                    	pa.descricao,  
                    	u.nome,
                    	o.descricao,  
                    	pa.localizacao,  
                    	TO_CHAR(pa.dt_inicio_rt,'DD/MM/YYYY'),
                    	TO_CHAR(pa.dt_termino_rt,'DD/MM/YYYY'),
                    	TO_CHAR(pa.dt_inicio_lb,'DD/MM/YYYY'),
                    	TO_CHAR(pa.dt_termino_lb,'DD/MM/YYYY'),
                    	pa.observacao,
                    	pa.cancelado,
                    	pa.motivo_cancelamento ,
                        pa.id_projeto_acoes,
                    	'R$ '||LTRIM(TO_CHAR(pa.recursos_proprios,'999G999G999D99')),
	                    'R$ '||LTRIM(TO_CHAR(pa.recursos_terceiros,'999G999G999D99'))
                    FROM 	projeto_acoes pa,orgao o, projeto_equipe pe, usuario u
                    WHERE 	pa.id_orgao=o.id_orgao AND
                    	pa.id_projeto_equipe=pe.id_projeto_equipe AND
                    	pe.login_equipe=u.login AND
                    	pa.id_acao_pai IS NULL AND
                        pa.id_projeto=".$query->record[0]."
                    ORDER BY ordem");
    $n2=$query2->rows();
    
    if ($n2>0){
    	$pdf->SetWidths(array(0=>'190'));
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(235,235,235);
        $pdf->SetFont('Arial', 'b', 12);
    	$pdf->Row(array(0=>"Aчѕes"),   8, 0, 1, 'L', 1);
        $pdf->ln();
        $pdf->ln();
        $contador=0;
        while ($n2--){
            $query2->proximo();
            $contador++;
            $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
            $pdf->SetFont('Arial', 'b', 10);
            $pdf->SetWidths(array(0=>'190'));
            $pdf->Row(array(0=>$contador.". ".$query2->record[0]), 5, 0, 1, 'L', 1);
            $pdf->ln();

            $pdf->SetFont('Arial', '', 10);
        	$pdf->SetWidths(array(0=>'40',1=>'150'));
	        $pdf->Row(array(0=>"гrgуo:",1=>$query2->record[3]), 5, 0, 1, 'L', 0);
            $pdf->ln();
	        $pdf->Row(array(0=>"Responsсvel:",1=>$query2->record[2]), 5, 0, 1, 'L', 1);
            $pdf->ln();
	        $pdf->Row(array(0=>"Previsуo (RT):",1=>$query2->record[5]." a ".$query2->record[6]), 5, 0, 1, 'L', 0);
            $pdf->ln();
	        $pdf->Row(array(0=>"Linha de Base:",1=>$query2->record[7]." a ".$query2->record[8]), 5, 0, 1, 'L', 1);
            $pdf->ln();
	        $pdf->Row(array(0=>"Recursos Prѓprios:",1=>$query2->record[13]), 5, 0, 1, 'L', 0);
            $pdf->ln();
	        $pdf->Row(array(0=>"Recursos de Terceiros:",1=>$query2->record[14]), 5, 0, 1, 'L', 1);
            $pdf->ln();
            if ($query2->record[10]=="S"){
                $pdf->SetFont('Arial', 'b', 10);
    	        $pdf->Row(array(0=>"CANCELADA"), 5, 0, 1, 'L', 1);
                $pdf->ln();
                $pdf->SetFont('Arial', '', 10);
    	        $pdf->Row(array(0=>"Motivo:",1=>$query2->record[11]), 5, 0, 1, 'L', 0);
                $pdf->ln();
            }
            $pdf->ln();
            $query_acao_pai->exec("SELECT 
                    	pa.titulo,
                    	pa.descricao,  
                    	u.nome,
                    	o.descricao,  
                    	pa.localizacao,  
                    	TO_CHAR(pa.dt_inicio_rt,'DD/MM/YYYY'),
                    	TO_CHAR(pa.dt_termino_rt,'DD/MM/YYYY'),
                    	TO_CHAR(pa.dt_inicio_lb,'DD/MM/YYYY'),
                    	TO_CHAR(pa.dt_termino_lb,'DD/MM/YYYY'),
                    	pa.observacao,
                    	pa.cancelado,
                    	pa.motivo_cancelamento ,
                        pa.id_projeto_acoes,
                    	'R$ '||LTRIM(TO_CHAR(pa.recursos_proprios,'999G999G999D99')),
	                    'R$ '||LTRIM(TO_CHAR(pa.recursos_terceiros,'999G999G999D99'))
                    FROM 	projeto_acoes pa,orgao o, projeto_equipe pe, usuario u
                    WHERE 	pa.id_orgao=o.id_orgao AND
                    	pa.id_projeto_equipe=pe.id_projeto_equipe AND 
                    	pe.login_equipe=u.login AND
                        pa.id_projeto=".$query->record[0]." AND pa.id_acao_pai =".$query2->record[12]."
                        ORDER BY ordem");
            $n3=$query_acao_pai->rows();
			$contador_filho=0;
            while ($n3--){
                $query_acao_pai->proximo();
                $contador_filho++;

                $pdf->line(10, $pdf->GetY()+5, 200, $pdf->GetY()+5);
                $pdf->SetFont('Arial', 'b', 10);
                $pdf->SetWidths(array(0=>"10",1=>'180'));
                $pdf->Row(array(0=>" ",1=>$contador.".".$contador_filho.". ".$query_acao_pai->record[0]), 5, 0, 1, 'L', 1);
                $pdf->ln();
    
                $pdf->SetFont('Arial', '', 10);
            	$pdf->SetWidths(array(0=>'10',1=>'40',2=>'140'));
    	        $pdf->Row(array(0=>"",1=>"гrgуo:",2=>$query_acao_pai->record[3]), 5, 0, 1, 'L', 0);
                $pdf->ln();
    	        $pdf->Row(array(0=>"",1=>"Responsсvel:",2=>$query_acao_pai->record[2]), 5, 0, 1, 'L', 1);
                $pdf->ln();
    	        $pdf->Row(array(0=>"",1=>"Localizaчуo:",2=>$query_acao_pai->record[4]), 5, 0, 1, 'L', 0);
                $pdf->ln();
    	        $pdf->Row(array(0=>"",1=>"Previsуo (RT):",2=>$query_acao_pai->record[5]." a ".$query_acao_pai->record[6]), 5, 0, 1, 'L', 1);
                $pdf->ln();
    	        $pdf->Row(array(0=>"",1=>"Linha de Base:",2=>$query_acao_pai->record[7]." a ".$query_acao_pai->record[8]), 5, 0, 1, 'L', 0);
                $pdf->ln();
    	        $pdf->Row(array(0=>"",1=>"Recursos Prѓprios:",2=>$query_acao_pai->record[13]), 5, 0, 1, 'L', 1);
                $pdf->ln();
    	        $pdf->Row(array(0=>"",1=>"Recursos de Terceiros:",2=>$query_acao_pai->record[14]), 5, 0, 1, 'L', 0);
                $pdf->ln();
    	        $pdf->Row(array(0=>"",1=>"Observaчуo:",2=>$query_acao_pai->record[9]), 5, 0, 1, 'L', 1);
                $pdf->ln();
                if ($query2->record[10]=="S"){
                    $pdf->SetFont('Arial', 'b', 10);
        	        $pdf->Row(array(0=>"",1=>"CANCELADA"), 5, 0, 1, 'L', 1);
                    $pdf->ln();
                    $pdf->SetFont('Arial', '', 10);
        	        $pdf->Row(array(0=>"",1=>"Motivo:",1=>$query_acao_pai->record[11]), 5, 0, 1, 'L', 0);
                    $pdf->ln();
                }
                $pdf->ln();
           }                          
        }                    
    }

    // RELATгRIOS
    $query2->exec("SELECT CASE WHEN r.data IS NULL THEN  TO_CHAR(pru.data,'DD/MM/YYYY') ELSE TO_CHAR(r.data,'DD/MM/YYYY') END as data,principais_acoes,proximos_passos,riscos_contramedidas,nao_alteracoes ,
                    CASE WHEN r.data IS NULL THEN  pru.data ELSE r.data END as data2
                   FROM projeto_relatorios pru LEFT JOIN  relatorio r ON pru.id_relatorio=r.id_relatorio WHERE id_projeto=".$query->record[0]." ORDER BY 6 DESC");
    $n2=$query2->rows();

    if ($n2>0){
       	$pdf->SetWidths(array(0=>'190'));
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(235,235,235);
        $pdf->SetFont('Arial', 'b', 12);
    	$pdf->Row(array(0=>"Relatѓrios"), 8, 0, 1, 'L', 1);
        $pdf->ln();
        $pdf->ln(1);

        $pdf->SetFont('Arial', '', 10);
        while ($n2--){
            $query2->proximo();
           	$pdf->SetWidths(array(0=>'40',1=>'150'));
   	        $pdf->Row(array(0=>"Data:",1=>$query2->record[0]), 5, 0, 1, 'L', 1);
            $pdf->ln();
            if ($query2->record[4]=="N"){
       	        $pdf->Row(array(0=>"Principais Atividades:",1=>$query2->record[1]), 5, 0, 1, 'L', 0);
                $pdf->ln();
       	        $pdf->Row(array(0=>"Prѓximos Passos:",1=>$query2->record[2]), 5, 0, 1, 'L', 1);
                $pdf->ln();
       	        $pdf->Row(array(0=>"Riscos/Contramedidas:",1=>$query2->record[3]), 5, 0, 1, 'L', 0);
                $pdf->ln();
            }
            else{
               	$pdf->SetWidths(array(0=>'190'));
       	        $pdf->Row(array(0=>"Nуo Houve Alteraчѕes:"), 5, 0, 1, 'L', 0);
                $pdf->ln();
            }
            $pdf->ln();
         }   
      }

    // EQUIPE
    $query2->exec("SELECT u.nome FROM projeto_equipe pe, usuario u WHERE id_projeto=".$query->record[0]." AND pe.login_equipe=u.login  ORDER BY u.nome");
    $n2=$query2->rows();
    if ($n2>0){
    	$pdf->SetWidths(array(0=>'190'));
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(235,235,235);
        $pdf->SetFont('Arial', 'b', 12);
    	$pdf->Row(array(0=>"Equipe"), 8, 0, 1, 'L', 1);
        $pdf->ln();
        $pdf->ln(1);

        $pdf->SetFont('Arial', '', 10);
        while ($n2--){
            $query2->proximo();
  	        $pdf->Row(array(0=>$query2->record[0]), 5, 0, 1, 'L', $n2%2);
            $pdf->ln();
      }
      $pdf->ln();
    }
    // Dependentes
    $query2->exec("SELECT p.titulo FROM projeto_dependentes pd, projeto p WHERE pd.id_projeto=".$query->record[0]." AND pd.id_projeto_dependente=p.id_projeto  ORDER BY p.titulo");
    $n2=$query2->rows();
    if ($n2>0){
    	$pdf->SetWidths(array(0=>'190'));
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(235,235,235);
        $pdf->SetFont('Arial', 'b', 12);
    	$pdf->Row(array(0=>"Projetos Dependentes"), 8, 0, 1, 'L', 1);
        $pdf->ln();
        $pdf->ln(1);

        $pdf->SetFont('Arial', '', 10);
        while ($n2--){
            $query2->proximo();
  	        $pdf->Row(array(0=>$query2->record[0]), 5, 0, 1, 'L', $n2%2);
            $pdf->ln();
      }
      $pdf->ln();
    }
    $query2->exec("SELECT u.nome FROM projeto_gestores pg, usuario u WHERE pg.id_projeto=".$query->record[0]." AND pg.gestor=u.login  ORDER BY u.nome");
    $n2=$query2->rows();
    if ($n2>0){
    	$pdf->SetWidths(array(0=>'190'));
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(235,235,235);
        $pdf->SetFont('Arial', 'b', 12);
    	$pdf->Row(array(0=>"Gestores"), 8, 0, 1, 'L', 1);
        $pdf->ln();
        $pdf->ln(1);

        $pdf->SetFont('Arial', '', 10);
        while ($n2--){
            $query2->proximo();
  	        $pdf->Row(array(0=>$query2->record[0]), 5, 0, 1, 'L', $n2%2);
            $pdf->ln();
      }
      $pdf->ln();
    }

    // FOTOS
    $query2->exec("SELECT anexo FROM projeto_anexos WHERE id_projeto=".$query->record[0]." and anexo ilike '%jpg%' ORDER BY id_projeto_anexos DESC LIMIT 3");
    $n2=$query2->rows();
    if ($n2>0){
    	$pdf->SetWidths(array(0=>'190'));
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFillColor(235,235,235);
        $pdf->SetFont('Arial', 'b', 12);
    	$pdf->Row(array(0=>"Fotos"), 8, 0, 1, 'L', 1);
        $pdf->ln();
        $pdf->ln();
        
        if ($n2==1) $largura=100; else $largura=50;
        while ($n2--){
            $query2->proximo();
            if (file_exists ('../arquivos/'.$query2->record[0])){
                $PosicaoX=$pdf->GetX();
                $pdf->SetX($PosicaoX+60);
                $pdf->Image('../arquivos/'.$query2->record[0], $PosicaoX+5, $pdf->GetY(), $largura);
            }
        }
    }
    
}
$pdf->Output();
?>