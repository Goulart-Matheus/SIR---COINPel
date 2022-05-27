<?
define("EXPIREINSECONDS",trim($system->getTimeout()) * 600);

class Session {
      var $name;
      var $record;
      var $count;

      function Session($name = 'SMSU') {
          $this->name = $name;
      }

      function startSession() {
          session_start();
          session_name('SMSU');
          $this->record=array();
          $this->count++;
          $this->registerSession();
          $this->verifySession();
      }

      function destroySession() {
          unset($this->name);
          // session_unregister($this->name);
          session_unset();
          $this->record=array();
          $this->count--;
      }

      function registerSession() {
          if (!$this->getSession()) {
              $this->record['ip'] = getenv('REMOTE_ADDR');
              $this->record['time'] = date('U');
              global $record;
			  $_SESSION['record'] = date('U');
          }
      }

      function verifySession() {
          if (($this->getSession()) && ((date('U') - $_SESSION['record']) > EXPIREINSECONDS)) {
              $this->destroySession();
              return 0;
          }
          else {
              $_SESSION['record'] = date('U');
              return 1;
          }
      }

      function getSession() {
          if ($_SESSION['record'])
              return 1;
          else
              return 0;
      }

      function getUser() {
          return $this->record['user'];
      }

      function getUserName() {
          global $queryauth;
          $queryauth->exec("SELECT nome FROM usuario WHERE login='".$this->getUser()."'");
          $queryauth->proximo();
          return $queryauth->record[0];
      }

      function getUserAviso() {
          global $queryauth;
          $queryauth->exec("SELECT aviso FROM usuario WHERE login='".$this->getUser()."'");
          $queryauth->proximo();
          return $queryauth->record[0];
      }

      function getIp() {
          return $this->record['ip'];
      }

      function getAltPass() {
        global $queryauth;
        $queryauth->exec("SELECT alterou_senha FROM usuario WHERE login='".
       $this->getUser()."'");
        $queryauth->proximo();
        return $queryauth->record[0];
      }
}

class Authentication extends Session {
      var $queryauth;

      function Authentication($queryauth,$session_name = '') {
          $this->queryauth = $queryauth;
          $this->session = new Session($session_name);
      }

      // 1 = all ok, 0 = error.
      function verifyAccess($user,$pass,$page) {
          if($this->verifyUser($user,$pass))
              if($this->verifyApplication($page))
                  return 1;
          return 0;
      }

      // 1 = all ok, 0 = error.
      function verifyUser($user,$pass) {
       $sql="SELECT * FROM usuario Where dt_validade>=current_date AND login ='".$user."' AND habilitado='S'";
	   $this->queryauth->exec($sql);
          $n=$this->queryauth->rows();
          while($n--){
              $this->queryauth->proximo();
              //verifica se usuario eh valido
                if(($this->queryauth->record[0] == $user) && ($this->queryauth->record[1] == sha1($pass))) {
                    $this->record['user'] = $user;
                    $this->record['pass'] = sha1($pass);
                    $this->queryauth->exec("UPDATE usuario SET dt_validade=current_date+40 WHERE login ='".$user."'");
                    return 1;
                } 
          }
          return 0;
      }

    // 1 = all ok, 0 = error.
    function verifyApplication($page) {
        $this->queryauth->exec(
            "SELECT a.fonte
            FROM usuario_grupo ug,  grupo_aplicacao ga, aplicacao a
            WHERE 
                ug.login        = '{$this->record['user']}' AND 
                ug.codgrupo     = ga.codgrupo               AND 
                ga.codaplicacao = a.codaplicacao            AND
                a.fonte         = '{$this->getApplicationName($page)}'");
        return $this->queryauth->rows() > 0;
    }

      function getApplicationName($page){
          $tmp = explode("/",$page);
          return $tmp[sizeof($tmp)-1];
      }

      function getApplicationCode($page){
          $this->queryauth->exec("SELECT codaplicacao FROM aplicacao WHERE fonte='$page'");
          $n = $this->queryauth->rows();
          if($n) {
              $this->queryauth->proximo();
              return $this->queryauth->record[0];
          }
          return 0;
      }

      function getApplicationPath($code) {
          if(!$code) return;
          $this->queryauth->exec("SELECT a.codaplicacao, b.codaplicacao, a.fonte, a.descricao FROM aplicacao a LEFT JOIN aplicacao b ON a.superior=b.codaplicacao WHERE a.codaplicacao=$code");
          $n = $this->queryauth->rows();
          if($n) {
              $this->queryauth->proximo();
              if($this->queryauth->record[0]==1) $str .="&nbsp;";
              else                           $str .="<strong>&nbsp;&raquo;&nbsp;</strong>";
              if($this->queryauth->record[2])    $str .="<span> <a href='".$this->queryauth->record[2]."'>".$this->queryauth->record[3]."</a></span>";
              else                           $str .="<span>".$this->queryauth->record[3]."</span>";
              $this->getApplicationPath($this->queryauth->record[1]);
              echo $str;
          }
      }
    function getApplicationDescription($page) {
        $user=$this->record['user'];
        $this->queryauth->exec("SELECT fonte,descricao
                              FROM usuario_grupo, grupo_aplicacao, aplicacao
                              WHERE login='$user'                                           AND
                                    usuario_grupo.codgrupo       = grupo_aplicacao.codgrupo AND
                                    grupo_aplicacao.codaplicacao = aplicacao.codaplicacao");
        $n = $this->queryauth->rows();
        while($n--){
            $this->queryauth->proximo();
            //verifica se usuario possui acesso a aplicacao
            if($this->getApplicationName($page) == $this->queryauth->record[0]) {
                return $this->queryauth->record[1];
            }
        }
        return false;
    }

}
?>