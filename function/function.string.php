<?

function strmax($string, $max = 50, $rep = '...', $end = 1) {
   $strlen =strlen($string);
   if ($strlen <= $max) return $string;
   else {
       if($end)
           return substr($string, 0, $max) . $rep;
       else {
           $lengthtokeep =$max - strlen($rep);
           $mod          =$lengthtokeep % 2;
           $start        =0;
           $end          =0;
           if ($mod == 0) {
               $start =$lengthtokeep / 2;
               $end   =$start;
           }
           else {
               $start =intval($lengthtokeep / 2);
               $end   =$start + 1;
           }
           return substr($string, 0, $start) .$rep. substr($string, -$end);
       }
   }
}
function limpa_caracteres($variavel){
	$retorno="";
	for ($i=0;$i<strlen($variavel);$i++) if (is_numeric($variavel[$i])) $retorno.=$variavel[$i];
	return $retorno;
}
function retira_acentos( $name ) 
{ 
  $array1 = array(   "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�" 
                     , "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�","'","�","`","/","\\","~","^","�" ); 
  $array2 = array(   "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c" 
                     , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C","","","","","","","","" ); 
  return str_replace( $array1, $array2, $name ); 
} 

function retira_acentos_kwy( $name ) 
{ 
  $array1 = array(   "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�" 
                     , "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�"
					 ,"'","�","`","/","\\","~","^","�","k","w","y","K","W","Y"); 
  $array2 = array(   "a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c" 
                     , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C"
					 ,"","","","","","","","","c","v","i","C","V","I"); 
  return str_replace( $array1, $array2, $name ); 
} 

?>
