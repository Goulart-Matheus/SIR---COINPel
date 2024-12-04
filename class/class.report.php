<?
define('FPDF_FONTPATH','../class/fpdf/font/');
require('../class/fpdf/barcode.php');


class PDF extends Barcode {
    var $query;                //define a instancia da consulta utilizada (utiliza a class.connection)
    var $titulo;               //define o titulo do documento
    var $relatorio;            //define o subtitulo
    var $periodo;              //periodo da consulta (data inicial - data final)
    var $cabecalho;            //cabecalho da tabela a ser gerada
    var $orientation   ='P';   //orientacao (P = portrait, L = landscape)
    var $unit          ='mm';  //unidade (pt, mm, cm, in)
    var $format        ='A4';  //formato (a3, a4, a5, letter, legal)
    var $flag;                 //quebra de linha por registros iguais(explicar +)
	var $font_size;			   // Tamanho da Fonte
    function PDF($query, $titulo, $relatorio, $periodo, $cabecalho, $orientation = 'P', $unit='mm', $format='A4', $flag, $font_size=10) {
        $this->FPDF($orientation, $unit, $format);

        $this->query     =$query;
        $this->titulo    =utf8_decode($titulo);
        $this->relatorio =utf8_decode($relatorio);
        $this->periodo   =$periodo;
        $this->cabecalho =$cabecalho;
        $this->font_size =$font_size;

        $this->Open();
        $this->AddPage();

        $this->SetFont('Arial', '', $font_size);

        unset($str);
        for($n=0; $n < $this->query->rows(); $n++) {
            $this->query->result($n);

            $width  =array();
            $linha  =array();
            $inicio =0;

            if($flag && $this->cabecalho[0][2]){
                $inicio=1;
                array_push($width, utf8_decode($this->cabecalho[0][1]));
                if($str==$this->query->record[0]) array_push($linha, '');
                else                              array_push($linha, utf8_decode($this->query->record[0]));
                $str=utf8_decode($this->query->record[0]);
            }

            for($i=$inicio; $i < $this->query->cols(); $i++){
                if ($this->cabecalho[$i][2]) {
                    array_push($linha, utf8_decode($this->query->record[$i]));
                    array_push($width, utf8_decode($this->cabecalho[$i][1]));
                }
            }

            $this->SetWidths($width);
            $this->Row($linha);
            $this->ln();
        }
        return $this->Output();
    }

    function Header() {
        $this->AliasNbPages();
        $this->Image('../img/logo_pref.jpg', 10, 3, 55, 22);

        $this->SetFont('Arial', 'B', 18);
        $this->SetX(70);
        $this->Cell($this->GetStringWidth($this->titulo), -5, $this->titulo);

        $this->SetFont('Arial', 'B', 14);
        $this->SetX(70);
        $this->Cell($this->GetStringWidth($this->relatorio), 14, $this->relatorio);

        $this->SetFont('Arial', 'B', $this->font_size);
        $this->SetX(70);
        $this->Cell($this->GetStringWidth($this->periodo), 24, $this->periodo);

        if($this->cabecalho){
            $this->SetXY(10,$this->GetY()+25);
            $this->SetFont('Arial', 'B', 10);
            for($i=0; $i < $this->query->cols(); $i++)
                if ($this->cabecalho[$i][2])
                    $this->Cell(utf8_decode($this->cabecalho[$i][1]), 5, utf8_decode($this->cabecalho[$i][0]), 0, 0, 1);

            $this->ln();
        }

        $this->SetX(-10);
        $this->line(10, 25, $this->GetX(), 25);
        $this->SetXY(10, 40);
    }

    function Footer() {
        $this->SetXY(-10, -5);
        $this->line(10, $this->GetY()-2, $this->GetX(), $this->GetY()-2);

        $this->SetFont('Courier', 'B', 8);

        $this->SetX(10);
        $data=strftime('%d/%m/%Y') . " às " . strftime("%H:%M");
        $this->Cell($this->GetX(), 2, "Gerado: " . $data, 0, 0, 'L');

        $this->SetX(0);
        $this->Cell($this->GetX(), 2, "COINPEL", 0, 0, 'C');

        $this->SetX(0);
        $this->Cell($this->GetX(), 2, "P�gina: ".$this->PageNo()."/{nb}", 0, 0, 'R');
    }

    function SetWidths($w) {
        $this->widths=$w;
    }

    function Row($data) {
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=5*$nb;
        $this->CheckPageBreak($h);
        $this->SetX(10);
        for($i=0;$i<count($data);$i++) {
            $w=$this->widths[$i];
            $x=$this->GetX();
            $y=$this->GetY();
            $this->Rect($x,$y,$w,$h);
            $this->MultiCell($w,5,$data[$i],0,'L',0);
            $this->SetXY($x+$w,$y);
        }
        $this->Ln($h-5);
    }

    function CheckPageBreak($h) {
        if($this->GetY()+$h>$this->PageBreakTrigger)
            $this->AddPage($this->CurOrientation);
        $this->SetX(15);
    }

    function NbLines($w,$txt) {
        $cw=&$this->CurrentFont['cw'];
        if($w==0)
            $w=$this->w-$this->rMargin-$this->x;
        $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
        $s=str_replace("\r",'',$txt);
        $nb=strlen($s);
        if($nb>0 and $s[$nb-1]=="\n")
            $nb--;
        $sep=-1;
        $i=0;
        $j=0;
        $l=0;
        $nl=1;
        while($i<$nb){
            $c=$s[$i];
            if($c=="\n") {
                $i++;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
                continue;
            }
            if($c==' ')
                $sep=$i;
            $l+=$cw[$c];
            if($l>$wmax) {
                if($sep==-1) {
                    if($i==$j)
                        $i++;
                }
                else
                    $i=$sep+1;
                $sep=-1;
                $j=$i;
                $l=0;
                $nl++;
            }
            else
                $i++;
        }
        return $nl;
    }
}

class RelatorioPDF extends PDF {
    var $nome;          // nome do relatorio
    var $cabecalho;     // cabecalho para as colunas

    function RelatorioPDF($titulo, $relatorio, $periodo,$or = 'P',$un = 'mm',$fo='a4') { // Construtor: Chama a classe FPDF
        $this->FPDF($or,$un,$fo);
        $this->titulo    =$titulo;
        $this->relatorio =$relatorio;
        $this->periodo   =$periodo;

    }

    function SetCabecalho($cab) { // define o cabecalho
        $this->cabecalho = $cab;
    }

    function SetName($nomerel) { // nomeia o relatorio
        $this->nome = $nomerel;
    }
}

class ProjetoPDF extends PDF {
    function ProjetoPDF($or = 'P',$un = 'mm',$fo='a4') { // Construtor: Chama a classe FPDF
        $this->FPDF($or,$un,$fo);
    }
    function SetProjeto($projeto) { 
        $this->projeto = $projeto;
    }
    function Header() {
        $this->AliasNbPages();
        $this->Image('../img//cabecalho-relatorio.jpg', 0, 0, 210,30);
        $this->SetXY(10, 35);
    }

    function Footer() {
        if ($this->footer){
            $this->SetXY(-10, -5);
            $this->line(10, $this->GetY()-2, $this->GetX(), $this->GetY()-2);
    
            $this->SetFont('Courier', 'B', 8);
            
            $this->SetX(10);
            $data=strftime('%d/%m/%Y') . " �s " . strftime("%H:%M");
            $this->Cell($this->GetX(), 2, "Gerado: " . $data, 0, 0, 'L');
    
            $this->SetX(0);
            $this->Cell($this->GetX(), 2, "COINPEL", 0, 0, 'C');
    
            $this->SetX(0);
            $this->Cell($this->GetX(), 2, "P�gina: ".$this->PageNo()."/{nb}", 0, 0, 'R');
        }
    }

    function Row($data,$altura=5,$borda=1,$par4,$alinhamento,$preenchimento) {
        $nb=0;
        for($i=0;$i<count($data);$i++)
            $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
        $h=5*$nb;
        $this->CheckPageBreak($h);
        $this->SetX(10);
        for($i=0;$i<count($data);$i++) {
            $w=$this->widths[$i];
            $x=$this->GetX();
            $y=$this->GetY();
            //$this->Rect($x,$y,$w,$h);
            $this->MultiCell($w,$altura,$data[$i],$borda,$alinhamento,$preenchimento);
            $this->SetXY($x+$w,$y);
        }
        $this->Ln($h-5);
    }

}

class CSV  {
    var $query;                //define a instancia da consulta utilizada (utiliza a class.connection)
    var $cabecalho;
    function CSV($query, $cabecalho, $arquivo="arquivo.csv", $diretorio="arquivos") {
        $this->query     = $query;
        $this->cabecalho = $cabecalho;

        $filename="../$diretorio/$arquivo";

		if (!$fp = fopen($filename, 'w')) {
	    	echo callException("Erro $fp abrindo arquivo", 2);
	    	exit;
		}

       	$fdl="\r\n"; //Final de Linha

        unset($conteudo);
        for($i=0; $i < $this->query->cols(); $i++)
        	if ($this->cabecalho[$i][1])
                $conteudo.="\"".$this->cabecalho[$i][0]."\";";

       	if (!fwrite($fp, substr($conteudo,0,-1).$fdl)) {
			echo callException('Erro escrevendo no arquivo 1', 2);
			exit;
		}

        for($n=0; $n < $this->query->rows(); $n++) {
            $this->query->proximo();
		        unset($conteudo);
	            for($i=0; $i < $this->query->cols(); $i++){
	                if ($this->cabecalho[$i][1]) {
	                	if ($this->cabecalho[$i][2]=="C") $conteudo.="\"".trim($this->query->record[$i])."\";";
	                	elseif ($this->cabecalho[$i][2]=="N") $conteudo.= str_replace(",",".",trim($this->query->record[$i])) .";";
					}
	            }
		        $conteudo=substr($conteudo,0,-1).$fdl;
		       	if (!fwrite($fp, $conteudo)) {
					echo callException('Erro escrevendo no arquivo 1', 2);
					exit;
				}

        }
		fclose($fp);
	?>
	<table width=500 class="texto" border="0" cellspacing="1" cellpadding="3" align="center" >

	   <tr><td align="center" class="texton"><br>O arquivo  foi gerado com sucesso !<br>
	   <br>
	   </td></tr>
	   <tr><td class="texton" align="center"><a href="<?echo $filename;?>" ><span class=form_erro>Para salva-lo no seu computador clique aqui com o bot&atilde;o direito do mouse<br>selecione "Salvar destino como ..." e salve como <br></span></a><?=$filename1?></td></tr>
	</table>
	<br>


	<?
        return $fp;
    }
}
?>
