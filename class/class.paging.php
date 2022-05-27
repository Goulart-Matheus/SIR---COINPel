<?
class Paging {
  var $query;        //define a instancia da consulta utilizada (utiliza a class.connection)
  var $maxres;       //resultado maximo de registros por pagina
  var $maxlink;      //numero de links numericos exibidos
  var $linkanterior; //string indica link para pagina anterior
  var $linkproxima;  //string indica link para pagina posterior
  var $page;         //define a pagina atual
  var $file;         //define o arquivo
  var $flag;         //seta uma flag que indica se ser� verificada variaveis do namespace request.

  /********************
  * Interface Pública *
  *********************/
  function Paging($query, $maxres, $maxlink, $link, $page, $flag)
  {
    $this->query        =$query;
    $this->maxres       =$maxres  ? $maxres  : 10;
    $this->maxlink      =$maxlink ? ($maxlink%2==0 ? $maxlink-1 : $maxlink) : 5;
    $this->linkanterior =$link[0] ? $link[0] : "<<";
    $this->linkproxima  =$link[1] ? $link[1] : ">>";
    $this->page         =$page   ? $page   : 0;
    $this->file         =$PHP_SELF;
    if (($flag == 0) || ($flag == 1)) $this->flag=$flag;
    else                              $this->flag=0;
  }

  function exec($sql)
  {
    $inicio_pesq        =$this->page * $this->maxres;
    $this->query->exec($sql);
    $this->totalRows    =$this->query->rows();
    return $this->query->exec($sql . " LIMIT $this->maxres OFFSET $inicio_pesq");
  }

  function viewLink()
  {
    $str   ='';
    $link  =$this->construirLink();
    for ($n = 0; $n < count($link); $n++) {
        $str .=$link[$n];
        if ($n != count($link)-1) $str .="&nbsp;&nbsp;";
    }
    return $str;
  }

  function viewSlice()
  {
    $str   ='';
    $link  =$this->construirSlice();
    for ($n = 0; $n < count($link); $n++) {
        $str .=$link[$n];
        if ($n != count($link)-1) $str .="&nbsp;&nbsp;";
    }
    return $str;
  }

  function viewTableSlice()
  {
      return "<div id='controle-paginacao'><span class=''>" . $this->viewSlice() . "</span></div>";
  }

  function getRows()
  {
    return $this->totalRows;
  }

  function getIter()
  {
    return ceil($this->getRows() / $this->maxres);
  }

  function getResultadoInicial()
  {
    return ($this->page*$this->maxres)+1;
  }

  function getResultadoFinal()
  {
    return ($this->page*$this->maxres)+$this->query->rows();
  }

  /********************
  * Interface Privada *
  *********************/
  function verificaVariaveis()
  {
    @list($this->nome_arq, $voided) = @explode("?", $_SERVER['REQUEST_URI']);

    if ($_SERVER['REQUEST_METHOD'] == "GET")    $cgi = $_GET;
    else                             $cgi = $_POST;
   	reset($cgi);

    while (list($chave, $valor) = each($cgi))
    if (($chave != "paging_page"))
       $query_string .= "&" . $chave . "=" . $valor;

    return $query_string;
  }

  function construirLink()
  {
    if ($this->flag) $extra_vars =$this->verificaVariaveis();
    else             $extra_vars ='';

    $arquivo      =$this->file;
    $indice       =-1;

    for ($atual = 0; $atual < $this->getIter(); $atual++) {
      // inclui link anterior
      if ($atual == 0) {
        if ($this->page != 0) $array[++$indice]='<a href="' . $arquivo . '?paging_page=' . ($this->page - 1) . $extra_vars . '">' . $this->linkanterior . '</a>';
        else                  $array[++$indice]=$this->linkanterior;
      }

      // inclui numeracao
      if ($this->page == $atual) $array[++$indice]='<a class="btn btn-sm btn-primary">' . ($atual > 0 ? ($atual + 1) : 1) . '</a>';
      else                       $array[++$indice]='<a href="' . $arquivo . '?paging_page=' . $atual . $extra_vars . '" class="paginacao">' . ($atual + 1) . '</a>';

      // inclui link posterior
      if ($atual == ($this->getIter()-1)) {
        if  ($this->page != ($this->getIter() - 1)) $array[++$indice]='<a href="' . $arquivo . '?paging_page=' . ($this->page + 1) . $extra_vars . '">' . $this->linkproxima . '</a>';
        else                                        $array[++$indice]=$this->linkproxima;
      }
    }
    return $array;
  }

  function construirSlice()
  {
    $array =$this->construirLink();
    $size  =count($array)-2;
    $atual =$this->page+1;
    if (($size <= 0) || ($size < $this->maxlink))
        $temp =array_slice($array, 1, $this->maxlink);
    else {
      $temp    =array();
      if (($atual + $this->maxlink/2) > $size ) $temp =array_slice($array, ($size - $this->maxlink)+1);
      else {
        if (($atual - $this->maxlink/2) < 1) $temp =array_slice($array, 1, $this->maxlink);
        else                                  $temp =array_slice($array, $atual-($this->maxlink/2)+1, $this->maxlink);
        array_push($temp, $array[$size+1]);
      }
    }
    if ($atual > 0)
       array_unshift($temp, $array[0]);
    return $temp;
  }
}

?>
