<?
class DataBase
	{
	var $host;				//hostname do servidor
	var $port=5432;			//porta do servidor (default 5432)
	var $database;			//base de dados
	var $user;				//usuario, com permissao para manipular base de dados
	var $password;			//senha do usuario
	var $connection;		//conexao persistente na base de dados

	function DataBase($host, $port, $database, $user, $pass)
	{
		$this->host			=$host;
		$this->port			=$port;
		$this->database		=$database;
		$this->user			=$user;
		$this->pass			=$pass;
		$this->connection	=pg_connect ("host=$this->host port=$this->port dbname=$this->database user=$this->user password=$this->pass");

		if ( !$this->connection )
			$this->error("Falha na conexão!");
	}

	function disconnect() {
		if ( $this->connection )
			pg_close($this->connection);
	}

    function listTable($tipo) {
        //tipo assume:
        //  i para chaves
        //  r para relacionamentos
        //  S para sequencias
        $sql    ="SELECT relname FROM pg_class WHERE relkind='$tipo' AND relname !~ '^pg_' ORDER BY relname DESC";
        $result =pg_exec($this->connection, $sql);
        while($row = pg_fetch_array($result))
            $array_out[]=$row[0];
        return $array_out;
    }

    function listDataBase() {
        $sql    ="SELECT datname FROM pg_database";
        $result =pg_exec($this->connection, $sql);
        while($row = pg_fetch_array($result))
            $array_out[]=$row[0];
        return $array_out;
    }

    function listColumn($tabela){
        $sql    ="SELECT * FROM $tabela LIMIT 1";
        $result =pg_exec($this->connection, $sql);
        $i      =pg_numfields($result);
        while($i--)
            $array_out[]=pg_fieldname($result,$i);
        return $array_out;
    }
}

class Query {
	var $bd;				//define a instancia do banco utilizada
	var $sql;			    //comando sql
	var $sql_inst;			//instrucao sql (0-> desconhecido, 1->select, 2->insert, 3->update, 4->update)
    var $sql_err;           //verifica erro (0-> ok, 1->erro)
    var $sql_err_msg;       //recebe o erro do banco
	var $consulta;			//consulta corrente
    var $tabela;            //ultima tabela consultada (select, insert, delete, update)
	var $last_insert;		//ultima tupla inserida
	var $nlinha;		    //numero de linhas afetadas
	var $ncoluna;		    //numero de colunas afetadas
	var $linha;				//linha atual
	var $record;			//sa�da de dados

	function all()     {
		$this->record =pg_fetch_all($this->consulta);     
	}

	function query($bdInstance) {
		$this->bd			=$bdInstance;
	}

	function insertTupla($tabela, $colunas)
	{
		$tamanho      =sizeof($colunas);
		$coluna       =$this->bd->listColumn($tabela);
        $var = "";
        $col = "";

		for($i = 0; $i < $tamanho; $i++) {
            if($colunas[$i]=='NULL') $var .=$colunas[$i] . ",";
            else $var .="'" . $colunas[$i] . "',";
        }
		while($tamanho--)                $col .=$coluna[$tamanho] . ",";
		$col          =substr($col, 0, -1);
		$var          =substr($var, 0, -1);

		$this->tabela =$tabela;
		$this->exec("INSERT INTO $tabela($col) values($var)");
		
		$this->lastInsert();
		return $this->consulta;
	}

	function insertTuplaFast($tabela, $colunas,$valores)
	{
		$tamanho      =sizeof($valores);
                $var = "";

		for($i = 0; $i <$tamanho; $i++) {
            if($valores[$i]=='NULL') $var .=$valores[$i] . ",";
            else $var .="'" . $valores[$i] . "',";
        }
		$var          =substr($var, 0, -1);
		$this->exec("INSERT INTO $tabela($colunas) values($var)");
		return;
	}

    function lastInsert() {
    	/*
        $oid    =pg_getlastoid($this->consulta);
        $result =pg_exec($this->bd->connection, "SELECT * FROM $this->tabela WHERE oid=$oid");
        if(pg_numrows($result)) $this->last_insert =pg_fetch_array($result, 0, PGSQL_NUM);
        return $this->last_insert;
*/
        $result =pg_exec($this->bd->connection, "SELECT lastval()");
        if(pg_numrows($result)) $this->last_insert =pg_fetch_array($result, 0, PGSQL_NUM);
        return $this->last_insert;
    }

    function deleteTupla($tabela, $array_where) {
        $tamanho =sizeof($array_where);
        for($i = 0; $i < $tamanho; $i++)
            $where .=$array_where[$i][0] . "='" . $array_where[$i][1] . "' and ";
		$where =substr($where, 0, -5);

		$this->tabela =$tabela;
        return $this->exec("DELETE FROM $tabela WHERE $where");
    }

    function updateTupla($tabela, $array_campo, $array_where) {
		$tamanho =sizeof($array_campo);
		$coluna  =$this->bd->listColumn($tabela);

        while($tamanho--) {
            $valor =$array_campo[sizeof($array_campo)-$tamanho-1];
            if($valor=='NULL') $campo .=$coluna[$tamanho] . "=" . $valor . ",";
            else               $campo .=$coluna[$tamanho] . "='" . $valor . "',";
        }
		$campo   =substr($campo, 0, -1);

        $tamanho =sizeof($array_where);
        for($i = 0; $i < $tamanho; $i++)
            $where .=$array_where[$i][0] . "='" . $array_where[$i][1] . "' and ";
		$where   =substr($where, 0, -5);

		$this->tabela =$tabela;
        return $this->exec("UPDATE $tabela SET $campo WHERE $where");
    }

    function updateTupla1Coluna($tabela, $campo,$valor,$coluna, $where) {
        return $this->exec("UPDATE $tabela SET $campo='$valor' WHERE $coluna='$where'");
    }

    function verifyDuplicidade($tabela,$coluna, $valor,$limit=1) {
    	$retorno="";
    	$this->exec("SELECT * FROM $tabela WHERE $coluna='$valor' LIMIT $limit");
    	if ($this->rows()) $retorno="Item \"$valor\" j� cadastrado";
        return $retorno;
    }

    function returnColuna($tabela,$coluna,$valor,$retorno) {
    	$this->exec("SELECT $retorno FROM $tabela WHERE $coluna='$valor' LIMIT 1");
    	$this->proximo();
        return $this->record[0];
    }

	function exec($sql)
	{
		$this->instrucao($sql);

		global $debug, $debug_info;
  		if($debug)
			$debug_info .="<div class='form_debug'>[".strftime('%H:%M:%S')."]&nbsp;<font color='#0000FF'>".$this->sql."</font></div>";

  		if($sql=="") {
			$this->record     =array();
			$this->nlinha     =0;
			$this->linha      =-1;
        }
        else {
			$this->consulta   =pg_exec($this->bd->connection, $this->sql);
			$this->ncoluna    =$this->cols();
			$this->nlinha     =$this->rows();
			$this->result(-1);
			if(pg_errormessage($this->bd->connection)) {
                $this->sql_err=1;
                $this->sql_err_msg=pg_errormessage($this->bd->connection);
                $this->abort();
                return 0;
            }
        	return $this->consulta;
     	}
	}

 	function instrucao($sql)
	{
		$this->sql =trim($sql);
        $instrucao =substr($this->sql, 0, strpos($this->sql, ' '));
        switch($instrucao) {
        	case 'SELECT': $this->sql_inst=1; break;
        	case 'INSERT': $this->sql_inst=2; break;
        	case 'UPDATE': $this->sql_inst=3; break;
        	case 'DELETE': $this->sql_inst=4; break;
        	default      : $this->sql_inst=0; break;
        }
	}

	function primeiro()
	{
		$this->linha =0;
		$this->dados();
	}

	function proximo()
	{
		$this->linha =($this->linha < ($this->rows()-1)) ? ++$this->linha : ($this->rows()-1);
		$this->dados();
	}

	function anterior()
	{
		$this->linha =($this->linha > 0) ? --$this->linha : 0;
		$this->dados();
	}

	function ultimo()
	{
		$this->linha =$this->rows()-1;
		$this->dados();
	}

	function result($linha) {
		if($linha>=0 AND $linha<$this->rows()) {
			$this->linha =$linha;
			$this->dados();
		}
	}

	function dados()
	{
		$this->record =pg_fetch_array($this->consulta);
	}

	function cols()
	{
		return pg_numfields($this->consulta);
	}

	function rows()
	{
		return pg_numrows($this->consulta);
	}

	function begin()
	{
		pg_exec($this->bd->connection,"Begin;");
		$this->sql_err=0;
	}

	function commit()
	{
		pg_exec($this->bd->connection,"Commit;");
		if(!$this->sql_err) {
    		switch($this->sql_inst) {
    			case 1:	$msg="Seleção realizada com sucesso!";
    					break;
         		case 2:	$msg="Inserção realizada com sucesso!";
      					break;
		       	case 3:	$msg="Edição realizada com sucesso!";
					    break;
		        case 4:	$msg="Remoção realizada com sucesso!";
      					break;
		    }
            echo callException($msg,0);
		}
	}

	function rollback()
	{
		pg_exec($this->bd->connection,"Rollback;");
		$this->abort();
	}

	function abort()
	{
		pg_exec($this->bd->connection,"Abort;");
		if($this->sql_err) {
            switch($this->sql_inst) {
    			case 1:	$msg="Impossíel selecão!<br>".$this->sql_err_msg;
      					break;
        		case 2:	$msg="Impossível inserção!<br>".$this->sql_err_msg;
      					break;
    			case 3:	$msg="Impossível edição!<br>".$this->sql_err_msg;
      					break;
    			case 4:	$msg="Impossível remoção! <br>".$this->sql_err_msg;
      					break;
    			default:$msg="Erro desconhecido!<br>".$this->sql_err_msg;
      					break;
    		}
            echo callException($msg,1);
		}
	}
}
?>
