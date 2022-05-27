<?php
class Sistema {
 var $database;
 var $titulo;
 var $msgInterna;
 var $msgExterna;
 var $mainURL;
 var $timeout;

 function Sistema($url) {
  if ($this->mainURL == null) {
   $this->mainURL = $url;
  }
  include "../includes/connection.php";

  $query->exec("select * from parametros");
  $query->proximo();
  $this->msgInterna = $query->record[1];
  $this->msgExterna = $query->record[2];
  $this->titulo = $query->record[3];
  $this->timeout = $query->record[5];
 }

 function getSession() {
  return 'admin_conpel';
 }

 function getMsgInterna() {
  return $this->msgInterna;
 }

 function getMsgExterna() {
  return $this->msgExterna;
 }

  function getTimeout() {
  return $this->timeout;
 }

 function getTitulo() {
  return $this->titulo;
 }
}
?>