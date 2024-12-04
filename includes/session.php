<?
extract($_GET);
extract($_POST);
// ini_set('display_errors',1);
// ini_set('display_startup_erros',1);
// error_reporting(E_ALL);

#define variáveis de conexão ao banco de dados
// include "../includes/connection.php";
include('../includes/connection.php');

#inclui uma funcao de chamada de excessao para todas as páginas
include('../function/function.exception.php');

include('../class/class.sistema.php');
$system = new Sistema($_SERVER["REQUEST_URI"]);

#modo debug
$debug = 0;

#define variáveis para ordenação
include('../class/class.sort.php');
$sort_icon    = array('<i class="fas fa-arrow-up"></i>', '<i class="fas fa-arrow-down"></i>');
$sort_dirname = array("Ordenação Crescente", "Ordenação Decrescente");
$sort_style   = array("#F3EFE7","#aae3b7");

#define variáveis para paginação
include('../class/class.paging.php');
$paging_maxres  = 20;
$paging_maxlink = 10;
$paging_link    = array('<i class="fas fa-arrow-left"></i>', '<i class="fas fa-arrow-right"></i>');
$paging_flag    = 1;

#define variáveis para geração de relatório
$report_titulo      =  $system->getTitulo();
$report_orientation = 'P';
$report_unit        = 'mm';
$report_format      = 'A4';
$report_flag        = 0;


#seta os tipos de erros que sao reportados pelo PHP
// error_reporting (E_ERROR | E_WARNING | E_PARSE);
error_reporting (0);
include "login.php";

?>