<?
/************************************************************************
    	   Sistema de Administra��o da Intranet
					     Vers�o 1.00
  				     Copyright (c) 2006
Data de cria��o:	    02/10/2006
Data de modifica��o:	03/10/2006
************************************************************************/

// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);

session_start();
unset($_SESSION["c01npel"]);
unset($_SESSION["submit"]);
unset($_SESSION["PHP_AUTH_USER2"]);
unset($_SESSION["PHP_AUTH_PW2"]); 
session_destroy();

header("location:index.php");
?>
