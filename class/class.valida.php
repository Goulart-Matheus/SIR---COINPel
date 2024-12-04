<?

class Valida{
	var $erro;
	var $valorcampo;
	var $nomecampo;

	function Valida($v,$n){
		$this->valorcampo=$v;
		$this->nomecampo=$n;
	}

	function ENumerico(){
		if(!is_numeric($this->valorcampo)){
			$this->erro.="O valor ".$this->nomecampo." não é numérico. Informe apenas números e ponto.<br>\n";
		}
	}

	function EstaNoIntervalo($inic,$fim,$tipo){
	    if ($tipo=='N') {
		   if(($this->valorcampo<$inic) or ($this->valorcampo>$fim)) 
		       $this->erro.="O valor ".$this->nomecampo." esta fora do intervalo permitido ($inic-$fim).<br>\n";
	    }
		elseif ($tipo=='D')	{
		echo date("d/m/Y",$inic);
		   $data=mktime(0,0,0,substr($this->valorcampo,3,2),substr($this->valorcampo,0,2),substr($this->valorcampo,6,4));
		   if(($data<$inic) or ($data>$fim)) 
		      $this->erro.="Data fora do Intervalo Permitido (".date("d/m/Y",$inic)." - ".date("d/m/Y",$fim).").<br>\n";
		}
	}
	
	function TamMinimo($tam){
		if(strlen($this->valorcampo)<$tam){
			$this->erro.="O campo ".$this->nomecampo." deve conter no mínimo ".$tam." caracteres.<br>\n";
		}
	}

	function TamMaximo($tam){
		if(strlen($this->valorcampo)>$tam){
			$this->erro.="O campo ".$this->nomecampo." deve conter no máximo ".$tam." caracteres.<br>\n";
		}
	}
 
	function TipoTextarea(){
	}
 
	function Select(){
		if(strlen($this->valorcampo)<=0){
			$this->erro.="O campo ".$this->nomecampo." deve ser selecionado.<br>\n";
		}
	}

	function FormatoEmail(){
	
		if((!eregi("@", $this->valorcampo) || !eregi("\.", $this->valorcampo)) && (strlen($this->valorcampo)>0)){
			$this->erro.="O campo ".$this->nomecampo." possui um formato inválido.<br>\n";
		}
	}

	function FormatoData(){
		if (!ereg ("([0-9]{2})/([0-9]{1,2})/([0-9]{1,4})", $this->valorcampo, $regs)) {
			$this->erro.="O campo ".$this->nomecampo." possui um formato inválido.<br>\n";
		}
		else
		{
		 $anoClass=substr($this->valorcampo,6,4);
	     $mesClass=substr($this->valorcampo,3,2);
	     $diaClass=substr($this->valorcampo,0,2); 
		   if (!checkdate($mesClass, $diaClass, $anoClass))
		     $this->erro.="O campo ".$this->nomecampo." possui um formato inválido.<br>\n";
		}
	}
	function checacpf(){
		$cpf = preg_replace("[^0-9]", "", $this->valorcampo); 
		if (strlen($cpf)!=11) 
			$this->erro.="O campo ".$this->nomecampo." deve conter 11 caracteres.<br>\n";
		else 
		{ 
		  $dv = substr($cpf,-2); 
		  $compdv = 0; 
		  $nulos = array("12345678909","11111111111","22222222222","33333333333", 
    		             "44444444444","55555555555","66666666666","77777777777", 
        		         "88888888888","99999999999","00000000000");    
		  if(in_array($cpf, $nulos)) $this->erro.="O campo ".$this->nomecampo." possui um formato inválido.<br>\n";
		  else 
    		  { 
	    	   $acum=0; 
	    	   for ($i=0; $i<9; $i++) $acum+=$cpf[$i]*(10-$i); 
		       $x=$acum % 11; 
		   	   $acum = ($x>1) ? (11 - $x) : 0; 
	    	   $compdv = $acum * 10; 
            
		       $acum=0; 
    		   for ($i=0; $i<10; $i++)  $acum+=$cpf[$i]*(11-$i); 
	    	   $x=$acum % 11; 
	    	   $acum = ($x>1) ? (11 - $x) : 0; 
		       $compdv = $compdv + $acum; 
                
	    	   if($compdv <> $dv) $this->erro.="O campo ".$this->nomecampo." possui um formato inválido.<br>\n";; 
	  		} //fim else
		} //fim else
	}
	function PegaErros(){
		return $this->erro;
	}
}
?>
